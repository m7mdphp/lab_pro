<?php

namespace App\Services\Migration;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Polite, retrying HTTP client for the migration pipeline.
 *
 * Enforces a configurable delay between requests, retries on transient
 * failures with exponential back-off, and surfaces clean null returns
 * rather than exceptions so callers can stay simple.
 */
class HttpFetcher
{
    private int   $requestCount  = 0;
    private float $lastRequestAt = 0.0;
    private array $cfg;

    public function __construct()
    {
        $this->cfg = config('migration.http');
    }

    // ── Public API ─────────────────────────────────────────────────────────────

    /**
     * Fetch a URL and return the full Response object, or null on failure.
     */
    public function get(string $url): ?Response
    {
        $this->throttle();

        $attempt = 0;
        $maxTries = (int) $this->cfg['retry_times'];

        while ($attempt <= $maxTries) {
            try {
                $response = Http::withHeaders($this->defaultHeaders())
                    ->timeout($this->cfg['timeout'])
                    ->connectTimeout($this->cfg['connect_timeout'])
                    ->get($url);

                $this->requestCount++;

                if ($response->successful()) {
                    return $response;
                }

                // 404 / 410 — permanent, do not retry
                if (in_array($response->status(), [404, 410, 403], true)) {
                    Log::channel('migration')->warning('HTTP permanent error', [
                        'url'    => $url,
                        'status' => $response->status(),
                    ]);
                    return null;
                }

                // 5xx / 429 — transient, retry
                Log::channel('migration')->debug('HTTP transient error, will retry', [
                    'url'     => $url,
                    'status'  => $response->status(),
                    'attempt' => $attempt + 1,
                ]);

            } catch (ConnectionException $e) {
                Log::channel('migration')->warning('HTTP connection error', [
                    'url'     => $url,
                    'error'   => $e->getMessage(),
                    'attempt' => $attempt + 1,
                ]);
            } catch (\Throwable $e) {
                // Non-retryable exception
                Log::channel('migration')->error('HTTP fetch exception', [
                    'url'   => $url,
                    'error' => $e->getMessage(),
                ]);
                return null;
            }

            $attempt++;
            if ($attempt <= $maxTries) {
                $sleepMs = (int) $this->cfg['retry_sleep_ms'] * (2 ** ($attempt - 1));
                usleep($sleepMs * 1_000);
            }
        }

        Log::channel('migration')->error('HTTP fetch exhausted retries', ['url' => $url]);
        return null;
    }

    /**
     * Fetch a URL and return its body as a string, or null on failure.
     */
    public function fetchBody(string $url): ?string
    {
        return $this->get($url)?->body();
    }

    /**
     * Fetch a URL for HTML pages (alias kept for readability at call sites).
     */
    public function fetchHtml(string $url): ?string
    {
        return $this->fetchBody($url);
    }

    /**
     * Fetch binary content (images, etc.) — same as fetchBody but named
     * to communicate intent at call sites.
     */
    public function fetchBinary(string $url): ?string
    {
        return $this->fetchBody($url);
    }

    /**
     * Return the HTTP status code for a URL without downloading the body.
     * Used by redirect verification.
     */
    public function head(string $url): ?int
    {
        $this->throttle();

        try {
            $response = Http::withHeaders($this->defaultHeaders())
                ->timeout($this->cfg['timeout'])
                ->connectTimeout($this->cfg['connect_timeout'])
                ->head($url);

            $this->requestCount++;
            return $response->status();
        } catch (\Throwable $e) {
            Log::channel('migration')->debug('HEAD request failed', [
                'url'   => $url,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function getRequestCount(): int
    {
        return $this->requestCount;
    }

    // ── Internal ───────────────────────────────────────────────────────────────

    private function throttle(): void
    {
        $delaySeconds = $this->cfg['delay_ms'] / 1_000;
        $elapsed      = microtime(true) - $this->lastRequestAt;

        if ($this->lastRequestAt > 0 && $elapsed < $delaySeconds) {
            $wait = (int) (($delaySeconds - $elapsed) * 1_000_000);
            usleep($wait);
        }

        $this->lastRequestAt = microtime(true);
    }

    private function defaultHeaders(): array
    {
        return [
            'User-Agent'      => $this->cfg['user_agent'],
            'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language' => 'en-US,en;q=0.9,ar;q=0.5',
            'Accept-Encoding' => 'gzip, deflate',
            'Cache-Control'   => 'no-cache',
            'Pragma'          => 'no-cache',
        ];
    }
}
