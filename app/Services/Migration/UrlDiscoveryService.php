<?php

namespace App\Services\Migration;

use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Discovers all crawlable URLs on the source site.
 *
 * Strategy:
 *   1. Parse sitemap.xml (and any sub-sitemaps it references).
 *   2. BFS-crawl from the entry point, following internal <a href> links.
 *   3. Classify every URL into package | category | page.
 *   4. Persist results to discovered_urls.json for checkpoint/resume.
 */
class UrlDiscoveryService
{
    /** @var array<string, array{url:string, type:string, discovered_at:string}> */
    private array $discovered = [];

    /** @var list<string> BFS queue */
    private array $queue = [];

    /** @var array<string, true> visited set */
    private array $visited = [];

    private string $baseDomain;
    private array  $cfg;

    public function __construct(private readonly HttpFetcher $fetcher)
    {
        $this->cfg        = config('migration');
        $this->baseDomain = $this->cfg['source']['domain'];
    }

    // ── Public API ─────────────────────────────────────────────────────────────

    /**
     * Run full discovery: sitemap seed → BFS crawl → classify → persist.
     *
     * @return array<string, array> Discovered URL map keyed by URL.
     */
    public function discover(bool $useSitemap = true): array
    {
        $this->discovered = [];
        $this->queue      = [];
        $this->visited    = [];

        if ($useSitemap) {
            $this->seedFromSitemap($this->cfg['source']['sitemap']);
        }

        $this->enqueue($this->cfg['source']['entry']);

        $this->bfsCrawl();
        $this->persist();

        return $this->discovered;
    }

    /**
     * Load previously persisted discovery results without re-crawling.
     */
    public function loadFromDisk(): array
    {
        $path = $this->cfg['storage']['exports']['urls'];

        if (!file_exists($path)) {
            return [];
        }

        $raw = json_decode(file_get_contents($path), true);

        return $raw['urls'] ?? [];
    }

    /**
     * Return a flat list of URLs for a specific type from persisted data.
     */
    public function urlsOfType(string $type): array
    {
        return array_keys(array_filter(
            $this->loadFromDisk(),
            fn($u) => ($u['type'] ?? '') === $type,
        ));
    }

    // ── Sitemap ────────────────────────────────────────────────────────────────

    private function seedFromSitemap(string $sitemapUrl): void
    {
        Log::channel('migration')->info('Fetching sitemap', ['url' => $sitemapUrl]);

        $body = $this->fetcher->fetchHtml($sitemapUrl);

        if (!$body) {
            Log::channel('migration')->warning('Sitemap unreachable — falling back to crawl-only');
            return;
        }

        try {
            // Suppress libxml errors on badly-formed XML
            $prev = libxml_use_internal_errors(true);
            $xml  = simplexml_load_string($body);
            libxml_use_internal_errors($prev);

            if ($xml === false) {
                Log::channel('migration')->warning('Sitemap XML parse failed');
                return;
            }

            $xml->registerXPathNamespace('sm', 'http://www.sitemaps.org/schemas/sitemap/0.9');

            // Sitemap index: contains <sitemap><loc> children
            $subSitemaps = $xml->xpath('//sm:sitemap/sm:loc') ?: $xml->xpath('//sitemap/loc') ?: [];
            foreach ($subSitemaps as $loc) {
                $this->fetchSubSitemap((string) $loc);
            }

            // Regular sitemap: contains <url><loc> children
            $locs = $xml->xpath('//sm:url/sm:loc') ?: $xml->xpath('//url/loc') ?: [];
            foreach ($locs as $loc) {
                $this->enqueue((string) $loc);
            }

            Log::channel('migration')->info('Sitemap seeded', ['queued' => count($this->queue)]);

        } catch (\Throwable $e) {
            Log::channel('migration')->warning('Sitemap processing exception', ['error' => $e->getMessage()]);
        }
    }

    private function fetchSubSitemap(string $url): void
    {
        $body = $this->fetcher->fetchHtml($url);
        if (!$body) return;

        try {
            $prev = libxml_use_internal_errors(true);
            $xml  = simplexml_load_string($body);
            libxml_use_internal_errors($prev);

            if ($xml === false) return;

            $locs = $xml->xpath('//url/loc') ?: [];
            foreach ($locs as $loc) {
                $this->enqueue((string) $loc);
            }
        } catch (\Throwable $e) {
            Log::channel('migration')->debug('Sub-sitemap parse error', ['url' => $url, 'error' => $e->getMessage()]);
        }
    }

    // ── BFS crawl ──────────────────────────────────────────────────────────────

    private function bfsCrawl(): void
    {
        $maxPages = $this->cfg['crawl']['max_pages'];

        while (!empty($this->queue) && count($this->visited) < $maxPages) {
            $url = array_shift($this->queue);

            if (isset($this->visited[$url])) continue;
            $this->visited[$url] = true;

            Log::channel('migration')->debug('Visiting', [
                'url'     => $url,
                'visited' => count($this->visited),
                'queue'   => count($this->queue),
            ]);

            $html = $this->fetcher->fetchHtml($url);
            if (!$html) continue;

            // Classify and record
            $type = $this->classify($url);
            $this->discovered[$url] = [
                'url'           => $url,
                'type'          => $type,
                'discovered_at' => now()->toISOString(),
            ];

            // Extract outbound links
            $this->enqueueLinksFrom($html, $url);
        }

        Log::channel('migration')->info('BFS crawl finished', [
            'discovered' => count($this->discovered),
            'visited'    => count($this->visited),
        ]);
    }

    private function enqueueLinksFrom(string $html, string $baseUrl): void
    {
        try {
            $crawler = new Crawler($html, $baseUrl);
            $crawler->filter('a[href]')->each(function (Crawler $node) {
                $href     = $node->attr('href');
                $absolute = $this->resolveAbsolute($href ?? '');
                if ($absolute) {
                    $this->enqueue($absolute);
                }
            });
        } catch (\Throwable) {
            // Silently skip malformed HTML — do not abort the crawl
        }
    }

    // ── Queue helpers ──────────────────────────────────────────────────────────

    private function enqueue(string $url): void
    {
        $url = $this->normalize($url);

        if ($url === '') return;
        if (isset($this->visited[$url])) return;
        if (in_array($url, $this->queue, true)) return;
        if (!$this->isAllowed($url)) return;

        $this->queue[] = $url;
    }

    private function isAllowed(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST) ?? '';

        if (!in_array($host, $this->cfg['crawl']['allowed_hosts'], true)) {
            return false;
        }

        foreach ($this->cfg['crawl']['exclude_patterns'] as $pattern) {
            if (preg_match($pattern, $url)) {
                return false;
            }
        }

        return true;
    }

    // ── Classification ─────────────────────────────────────────────────────────

    private function classify(string $url): string
    {
        foreach ($this->cfg['url_patterns'] as $type => $pattern) {
            if (preg_match($pattern, $url)) {
                return $type;
            }
        }
        return 'page';
    }

    // ── URL utilities ──────────────────────────────────────────────────────────

    private function normalize(string $url): string
    {
        // Strip fragment
        if (($pos = strpos($url, '#')) !== false) {
            $url = substr($url, 0, $pos);
        }

        $url = trim($url);

        // Remove trailing slash unless it is the root path
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        if ($path !== '/' && str_ends_with($url, '/')) {
            $url = rtrim($url, '/');
        }

        return $url;
    }

    private function resolveAbsolute(string $href): ?string
    {
        if ($href === '' || preg_match('/^(#|mailto:|tel:|javascript:|data:)/i', $href)) {
            return null;
        }

        if (str_starts_with($href, 'https://') || str_starts_with($href, 'http://')) {
            return $href;
        }

        if (str_starts_with($href, '//')) {
            return 'https:' . $href;
        }

        // Relative → absolute using source domain
        return rtrim($this->baseDomain, '/') . '/' . ltrim($href, '/');
    }

    // ── Persistence ────────────────────────────────────────────────────────────

    private function persist(): void
    {
        $path = $this->cfg['storage']['exports']['urls'];
        $dir  = dirname($path);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Build summary by type
        $byType = [];
        foreach ($this->discovered as $u) {
            $byType[$u['type']] = ($byType[$u['type']] ?? 0) + 1;
        }

        $payload = [
            'crawled_at' => now()->toISOString(),
            'total'      => count($this->discovered),
            'by_type'    => $byType,
            'urls'       => $this->discovered,
        ];

        file_put_contents(
            $path,
            json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        );

        Log::channel('migration')->info('Discovered URLs persisted', [
            'path'    => $path,
            'total'   => count($this->discovered),
            'by_type' => $byType,
        ]);
    }
}
