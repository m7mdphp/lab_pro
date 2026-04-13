<?php

namespace App\Console\Commands;

use App\Services\Migration\ContentNormalizer;
use App\Services\Migration\HtmlExtractionService;
use App\Services\Migration\HttpFetcher;
use App\Services\Migration\MediaDownloader;
use App\Services\Migration\UrlDiscoveryService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Crawl acculab.org, extract structured content, and write JSONL export files.
 *
 * Usage examples
 * ──────────────
 *   # Full run (sitemap seed + BFS crawl + extraction)
 *   php artisan migration:crawl
 *
 *   # Re-extract from previously discovered URLs, no re-crawl
 *   php artisan migration:crawl --skip-discovery
 *
 *   # Only extract product pages
 *   php artisan migration:crawl --type=package
 *
 *   # Debug a single URL
 *   php artisan migration:crawl --url=https://acculab.org/product/cbc/
 *
 *   # Extract and download media in one pass
 *   php artisan migration:crawl --with-media
 *
 *   # Overwrite existing exports
 *   php artisan migration:crawl --force
 */
class CrawlAcculab extends Command
{
    protected $signature = 'migration:crawl
                            {--skip-discovery   : Use previously saved URL list instead of crawling}
                            {--type=all         : Filter by entity type: package|category|page|all}
                            {--url=             : Extract a single URL and exit}
                            {--limit=           : Cap the number of pages to extract}
                            {--with-media       : Download media files during extraction}
                            {--force            : Overwrite output JSONL files (default: append)}
                            {--dry-run          : Parse and log but write no files}';

    protected $description = 'Crawl acculab.org, extract structured content, export JSONL';

    private array $stats = [
        'queued' => 0, 'extracted' => 0,
        'failed' => 0, 'skipped'  => 0, 'media' => 0,
    ];

    private array $cfg;

    public function __construct(
        private readonly HttpFetcher           $fetcher,
        private readonly UrlDiscoveryService   $discovery,
        private readonly HtmlExtractionService $extractor,
        private readonly MediaDownloader       $mediaDownloader,
    ) {
        parent::__construct();
        $this->cfg = config('migration');
    }

    // ── Entry point ────────────────────────────────────────────────────────────

    public function handle(): int
    {
        $this->ensureDirectories();

        $dryRun = (bool) $this->option('dry-run');

        $this->printBanner($dryRun);

        // ── 1. Discover URLs ──────────────────────────────────────────────────
        $urls = $this->resolveUrls();

        if (empty($urls)) {
            $this->error('No URLs to process.');
            return self::FAILURE;
        }

        // ── 2. Filter by type ─────────────────────────────────────────────────
        $typeFilter = $this->option('type');
        if ($typeFilter !== 'all') {
            $urls = array_filter($urls, fn($u) => ($u['type'] ?? 'page') === $typeFilter);
            $this->line("  Filtered to <info>" . count($urls) . "</info> URLs of type <comment>{$typeFilter}</comment>.");
        }

        // ── 3. Limit ──────────────────────────────────────────────────────────
        if ($limit = (int) $this->option('limit')) {
            $urls = array_slice($urls, 0, $limit, true);
            $this->line("  Limited to <info>{$limit}</info> URLs.");
        }

        $this->stats['queued'] = count($urls);
        $this->newLine();

        // ── 4. Open output file handles ────────────────────────────────────────
        $handles = $dryRun ? [] : $this->openHandles();

        // ── 5. Extract each URL ────────────────────────────────────────────────
        $bar = $this->output->createProgressBar(count($urls));
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% <comment>%message%</comment>');
        $bar->start();

        foreach ($urls as $urlData) {
            $url  = $urlData['url'];
            $type = $urlData['type'] ?? 'page';

            $bar->setMessage(Str::limit($url, 70));

            $record = $this->extractOne($url, $type);

            if ($record === null) {
                $bar->advance();
                continue;
            }

            if (!$dryRun) {
                $this->writeRecord($handles, $type, $record);

                // Optionally download media
                if ($this->option('with-media') && !empty($record['images'])) {
                    $this->downloadMedia($record, $handles['media'] ?? null);
                }
            }

            $this->stats['extracted']++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // ── 6. Close handles ───────────────────────────────────────────────────
        foreach ($handles as $h) {
            if (is_resource($h)) fclose($h);
        }

        // ── 7. Summary ─────────────────────────────────────────────────────────
        $this->printSummary($dryRun);

        return self::SUCCESS;
    }

    // ── URL resolution ─────────────────────────────────────────────────────────

    private function resolveUrls(): array
    {
        // Single URL mode
        if ($singleUrl = $this->option('url')) {
            return [[
                'url'  => $singleUrl,
                'type' => $this->classifyUrl($singleUrl),
            ]];
        }

        // Resume from previously saved discovery
        if ($this->option('skip-discovery')) {
            $this->line('  Loading saved URL list from disk...');
            $raw = $this->discovery->loadFromDisk();
            if (empty($raw)) {
                $this->warn('  No saved URLs found. Run without --skip-discovery first.');
                return [];
            }
            $this->line('  Loaded <info>' . count($raw) . '</info> URLs from disk.');
            return array_values($raw);
        }

        // Full crawl + discovery
        $this->line('  Starting URL discovery (sitemap + BFS crawl)...');
        $raw = $this->discovery->discover(useSitemap: true);
        $this->line('  Discovered <info>' . count($raw) . '</info> URLs.');
        return array_values($raw);
    }

    // ── Extraction ─────────────────────────────────────────────────────────────

    private function extractOne(string $url, string $type): ?array
    {
        try {
            if (!$this->option('force') && $this->alreadyExported($url)) {
                Log::channel('migration')->debug('Skipped (already exported)', ['url' => $url]);
                $this->stats['skipped']++;
                return null;
            }

            $html = $this->fetcher->fetchHtml($url);

            if (!$html) {
                $this->stats['failed']++;
                $this->appendFailed($url, 'empty_response');
                return null;
            }

            $record = $this->extractor->extract($html, $url, $type);

            if ($record === null) {
                $this->stats['failed']++;
                $this->appendFailed($url, 'extractor_returned_null');
                return null;
            }

            // Reject packages with no name
            if ($type === 'package' && empty(trim($record['name_en'] ?? ''))) {
                $this->stats['failed']++;
                $this->appendFailed($url, 'package_missing_name');
                return null;
            }

            Log::channel('migration')->debug('Extracted', [
                'url'   => $url,
                'type'  => $type,
                'name'  => $record['name_en'] ?? $record['title_en'] ?? '—',
                'price' => $record['price_piastres'] ?? null,
            ]);

            return $record;

        } catch (\Throwable $e) {
            $this->stats['failed']++;
            $this->appendFailed($url, $e->getMessage());
            Log::channel('migration')->error('Extraction exception', [
                'url'   => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    // ── Media ──────────────────────────────────────────────────────────────────

    private function downloadMedia(array $record, mixed $mediaHandle): void
    {
        $slug      = $record['slug'] ?? 'unknown';
        $type      = $record['_meta']['entity_type'] ?? 'unknown';
        $images    = $record['images'] ?? [];

        $results = $this->mediaDownloader->downloadAll($images, $type, $slug);

        foreach ($results as $result) {
            $this->stats['media']++;
            if ($mediaHandle && is_resource($mediaHandle)) {
                fwrite($mediaHandle, json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n");
            }
        }
    }

    // ── Output file management ─────────────────────────────────────────────────

    private function openHandles(): array
    {
        $exports = $this->cfg['storage']['exports'];
        $mode    = $this->option('force') ? 'w' : 'a';
        $handles = [];

        $keys = ['packages', 'categories', 'branches', 'faqs', 'partners', 'pages', 'media', 'failed'];

        foreach ($keys as $key) {
            $path = $exports[$key] ?? null;
            if (!$path) continue;

            $dir = dirname($path);
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $handles[$key] = fopen($path, $mode);

            if ($handles[$key] === false) {
                $this->error("Cannot open output file: {$path}");
                exit(self::FAILURE);
            }
        }

        return $handles;
    }

    private function writeRecord(array $handles, string $type, array $record): void
    {
        $line = json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";

        $key = match ($type) {
            'package'  => 'packages',
            'category' => 'categories',
            default    => $this->pageTypeToKey($record['page_type'] ?? 'generic'),
        };

        if (isset($handles[$key]) && is_resource($handles[$key])) {
            fwrite($handles[$key], $line);
        }
    }

    private function pageTypeToKey(string $pageType): string
    {
        return match ($pageType) {
            'faq'      => 'faqs',
            'branch'   => 'branches',
            'partners' => 'partners',
            default    => 'pages',
        };
    }

    private function appendFailed(string $url, string $reason): void
    {
        $path = $this->cfg['storage']['exports']['failed'];
        $dir  = dirname($path);
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $line = json_encode([
            'url'    => $url,
            'reason' => $reason,
            'at'     => now()->toISOString(),
        ]) . "\n";

        file_put_contents($path, $line, FILE_APPEND | LOCK_EX);
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    private function alreadyExported(string $url): bool
    {
        if ($this->option('force')) return false;

        foreach ($this->cfg['storage']['exports'] as $key => $path) {
            if (in_array($key, ['failed', 'urls', 'media'], true)) continue;
            if (!file_exists($path)) continue;

            // Scan the JSONL file for the source_url value
            $handle = fopen($path, 'r');
            if (!$handle) continue;

            while (($line = fgets($handle)) !== false) {
                if (str_contains($line, json_encode($url))) {
                    fclose($handle);
                    return true;
                }
            }
            fclose($handle);
        }

        return false;
    }

    private function classifyUrl(string $url): string
    {
        foreach ($this->cfg['url_patterns'] as $type => $pattern) {
            if (preg_match($pattern, $url)) return $type;
        }
        return 'page';
    }

    private function ensureDirectories(): void
    {
        $dirs = [
            $this->cfg['storage']['base_path'],
            $this->cfg['storage']['raw_html'],
            $this->cfg['storage']['logs'],
            dirname($this->cfg['storage']['exports']['packages']),
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) mkdir($dir, 0755, true);
        }
    }

    private function printBanner(bool $dryRun): void
    {
        $this->newLine();
        $this->line('┌──────────────────────────────────────────────────────┐');
        $this->line('│  migration:crawl — AccuLab extraction pipeline        │');
        $this->line('└──────────────────────────────────────────────────────┘');
        if ($dryRun) {
            $this->warn('  DRY-RUN mode — no files will be written.');
        }
        $this->newLine();
    }

    private function printSummary(bool $dryRun): void
    {
        $this->table(
            ['Metric', 'Count'],
            [
                ['URLs queued',     $this->stats['queued']],
                ['Extracted',       $this->stats['extracted']],
                ['Skipped (dup)',   $this->stats['skipped']],
                ['Failed',          $this->stats['failed']],
                ['Media downloaded',$this->stats['media']],
                ['HTTP requests',   $this->fetcher->getRequestCount()],
            ],
        );

        if (!$dryRun) {
            $this->newLine();
            $this->line('<options=bold>Output files:</>');
            foreach ($this->cfg['storage']['exports'] as $key => $path) {
                if (!file_exists($path)) continue;
                $count = $this->countJsonlLines($path);
                $this->line("  <fg=green>✓</> {$key}: {$count} records → {$path}");
            }
        }

        Log::channel('migration')->info('migration:crawl complete', $this->stats);
    }

    private function countJsonlLines(string $path): int
    {
        $count  = 0;
        $handle = fopen($path, 'r');
        if (!$handle) return 0;
        while (($line = fgets($handle)) !== false) {
            if (trim($line) !== '') $count++;
        }
        fclose($handle);
        return $count;
    }
}
