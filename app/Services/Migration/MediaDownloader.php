<?php

namespace App\Services\Migration;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Downloads, validates, deduplicates, and stores media files
 * discovered during the HTML extraction pass.
 *
 * Deduplication is two-layer:
 *   1. content_hash (MD5 of raw bytes) — catches identical files with
 *      different URLs.
 *   2. source_url normalisation (WP size-suffix stripping) — catches
 *      the same logical image referenced at multiple WP resize variants.
 *
 * Every successful download returns a structured array that is appended
 * to media.jsonl for later import into the `media` table.
 */
class MediaDownloader
{
    /** In-run hash → result cache (deduplication across multiple calls). */
    private array $seenHashes = [];

    /** Normalised source URL → result cache. */
    private array $seenUrls   = [];

    private array $cfg;

    public function __construct(private readonly HttpFetcher $fetcher)
    {
        $this->cfg = config('migration');
    }

    // ── Public API ─────────────────────────────────────────────────────────────

    /**
     * Download a single media URL.
     *
     * @param  string $url         Original source URL.
     * @param  string $entityType  e.g. 'package', 'category', 'partner'.
     * @param  string $entitySlug  Slug of the owning entity.
     * @param  string $field       Semantic role: 'thumbnail', 'image_1', 'logo'.
     * @return array|null          Structured result array, or null on failure.
     */
    public function download(
        string $url,
        string $entityType,
        string $entitySlug,
        string $field = 'thumbnail',
    ): ?array {
        $url = $this->stripWpSizes($url);

        if (!$this->hasMediaExtension($url)) {
            Log::channel('migration')->debug('Skipping non-media URL', ['url' => $url]);
            return null;
        }

        // URL-level dedup
        if (isset($this->seenUrls[$url])) {
            Log::channel('migration')->debug('Skipping duplicate URL', ['url' => $url]);
            return array_merge($this->seenUrls[$url], ['deduplicated' => true, 'dedup_reason' => 'url']);
        }

        // Fetch binary
        $body = $this->fetcher->fetchBinary($url);

        if (!$body || strlen($body) < 512) {
            Log::channel('migration')->warning('Empty or too-small media response', [
                'url'  => $url,
                'size' => strlen($body ?? ''),
            ]);
            return null;
        }

        // File size cap
        $size = strlen($body);
        if ($size > $this->cfg['media']['max_size']) {
            Log::channel('migration')->warning('Media exceeds max size', [
                'url'      => $url,
                'size'     => $size,
                'max_size' => $this->cfg['media']['max_size'],
            ]);
            return null;
        }

        // MIME validation
        $mime = $this->detectMime($body);
        if (!$this->isMimeAllowed($mime)) {
            Log::channel('migration')->warning('Disallowed MIME type', ['url' => $url, 'mime' => $mime]);
            return null;
        }

        // Content-hash dedup
        $hash = md5($body);
        if (isset($this->seenHashes[$hash])) {
            Log::channel('migration')->debug('Skipping duplicate content hash', ['url' => $url, 'hash' => $hash]);
            $this->seenUrls[$url] = $this->seenHashes[$hash];
            return array_merge($this->seenHashes[$hash], ['deduplicated' => true, 'dedup_reason' => 'hash']);
        }

        // Build storage path: media/{entityType}/{entitySlug}/{name}_{hash8}.{ext}
        $ext        = $this->mimeToExt($mime);
        $nameBase   = Str::slug(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_FILENAME) ?: $entitySlug);
        $filename   = $nameBase . '_' . substr($hash, 0, 8) . '.' . $ext;
        $storagePath = implode('/', array_filter([
            $this->cfg['media']['local_prefix'],
            $entityType,
            $entitySlug,
            $filename,
        ]));

        // Persist to disk
        $disk = $this->cfg['media']['local_disk'];
        if (!Storage::disk($disk)->put($storagePath, $body)) {
            Log::channel('migration')->error('Storage::put failed', [
                'url'  => $url,
                'path' => $storagePath,
            ]);
            return null;
        }

        // Image dimensions (best-effort)
        [$width, $height] = $this->readDimensions($body, $mime);

        $result = [
            'source_url'     => $url,
            'disk'           => $disk,
            'path'           => $storagePath,
            'filename'       => $filename,
            'mime_type'      => $mime,
            'file_size'      => $size,
            'width'          => $width,
            'height'         => $height,
            'content_hash'   => $hash,
            'entity_type'    => $entityType,
            'entity_slug'    => $entitySlug,
            'field'          => $field,
            'deduplicated'   => false,
            'dedup_reason'   => null,
            'downloaded_at'  => now()->toISOString(),
        ];

        $this->seenHashes[$hash] = $result;
        $this->seenUrls[$url]    = $result;

        Log::channel('migration')->info('Media downloaded', [
            'url'      => $url,
            'path'     => $storagePath,
            'size'     => $size,
            'mime'     => $mime,
        ]);

        return $result;
    }

    /**
     * Download all images for a given entity.
     * The first URL is assigned field='thumbnail'; subsequent ones 'image_N'.
     *
     * @param  string[] $urls
     * @return array[]
     */
    public function downloadAll(array $urls, string $entityType, string $entitySlug): array
    {
        $results = [];

        foreach (array_values($urls) as $i => $url) {
            $field  = $i === 0 ? 'thumbnail' : "image_{$i}";
            $result = $this->download($url, $entityType, $entitySlug, $field);
            if ($result !== null) {
                $results[] = $result;
            }
        }

        return $results;
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    private function detectMime(string $body): string
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        return $finfo->buffer($body) ?: 'application/octet-stream';
    }

    private function isMimeAllowed(string $mime): bool
    {
        if ($mime === 'application/pdf' && $this->cfg['media']['skip_pdf']) {
            return false;
        }

        $allowed = array_merge(
            $this->cfg['media']['allowed_mimes'],
            $this->cfg['media']['skip_pdf'] ? [] : ['application/pdf'],
        );

        return in_array($mime, $allowed, true);
    }

    private function mimeToExt(string $mime): string
    {
        return match ($mime) {
            'image/jpeg'      => 'jpg',
            'image/png'       => 'png',
            'image/gif'       => 'gif',
            'image/webp'      => 'webp',
            'image/svg+xml'   => 'svg',
            'application/pdf' => 'pdf',
            default           => 'bin',
        };
    }

    private function hasMediaExtension(string $url): bool
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        $blocked = ['html', 'htm', 'php', 'js', 'css', 'xml', 'txt', 'map'];
        return !in_array($ext, $blocked, true) && $ext !== '';
    }

    private function stripWpSizes(string $url): string
    {
        // Strip dynamic -NNNxNNN suffixes
        $url = preg_replace('/-\d{2,4}x\d{2,4}(\.[a-z]{3,4})$/i', '$1', $url);

        // Strip known named suffixes
        foreach ($this->cfg['media']['wp_size_suffixes'] as $suffix) {
            $ext = '.' . pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
            if ($ext !== '.') {
                $url = str_replace($suffix . $ext, $ext, $url);
            }
        }

        return $url;
    }

    /**
     * Read image width/height from raw bytes without saving to a temp file.
     * Returns [null, null] on failure or for non-raster formats.
     */
    private function readDimensions(string $body, string $mime): array
    {
        if (in_array($mime, ['image/svg+xml', 'application/pdf'], true)) {
            return [null, null];
        }

        try {
            $info = @getimagesizefromstring($body);
            if ($info !== false) {
                return [$info[0] ?: null, $info[1] ?: null];
            }
        } catch (\Throwable) {}

        return [null, null];
    }
}
