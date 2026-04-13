<?php

namespace App\Services\Migration;

use Illuminate\Support\Str;

/**
 * Stateless text and HTML normalizer for the migration pipeline.
 *
 * Responsibilities:
 *   - Collapse whitespace, strip invisible characters, decode entities.
 *   - Strip Elementor wrapper divs while preserving their inner content.
 *   - Remove disallowed HTML tags and strip style/class/id attributes.
 *   - Strip boilerplate phrases and source brand names.
 *   - Normalize prices to integer piastres (EGP × 100).
 *   - Extract phone numbers and normalize them to +20 E.164 format.
 *   - Resolve relative media URLs to absolute form.
 *   - Generate stable content hashes for duplicate detection.
 *   - Build and correct slugs.
 */
class ContentNormalizer
{
    private array $cfg;

    public function __construct()
    {
        $this->cfg = config('migration');
    }

    // ── Text cleaning ──────────────────────────────────────────────────────────

    /**
     * Normalize a plain-text string.
     * Safe to call with null (returns '').
     */
    public function cleanText(?string $text): string
    {
        if ($text === null || $text === '') return '';

        // Decode HTML entities
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Normalize line endings
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // Remove control characters except \n and \t
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);

        // Normalize non-breaking spaces (U+00A0)
        $text = str_replace(["\xc2\xa0", "\u{00A0}"], ' ', $text);

        // Collapse horizontal whitespace
        $text = preg_replace('/[ \t]+/', ' ', $text);

        // Collapse excessive newlines
        $text = preg_replace('/\n{3,}/', "\n\n", $text);

        // Strip breadcrumb patterns: "Home » Tests » CBC"
        $text = preg_replace('/^(?:[^\n»›>|]+[»›>|])+\s*/u', '', $text);

        // Strip boilerplate phrases
        foreach ($this->cfg['extraction']['boilerplate_phrases'] as $phrase) {
            $escaped = preg_quote($phrase, '/');
            $text    = preg_replace('/[,.]?\s*' . $escaped . '[^.\n]*/iu', '', $text);
        }

        // Replace source brand names with a neutral placeholder.
        // Import-time code will swap this with the actual new brand name from settings.
        foreach ($this->cfg['extraction']['brand_names'] as $brand) {
            $text = str_ireplace($brand, '__BRAND__', $text);
        }

        return trim($text);
    }

    /**
     * Normalize an HTML string.
     * Unwraps Elementor containers, strips disallowed tags and attributes,
     * then delegates to cleanText for whitespace / boilerplate removal.
     */
    public function cleanHtml(?string $html): string
    {
        if ($html === null || $html === '') return '';

        // 1. Unwrap Elementor section/column/widget divs (recursive, up to 5 passes)
        $prev = null;
        $passes = 0;
        while ($html !== $prev && $passes < 5) {
            $prev = $html;
            $html = preg_replace(
                '/<div[^>]+class="[^"]*elementor-[^"]*"[^>]*>([\s\S]*?)<\/div>/i',
                '$1',
                $html,
            );
            $passes++;
        }

        // 2. Strip inline styles, data-* attributes, and id attributes
        $html = preg_replace('/\s(?:style|id|data-[a-z0-9_-]+)="[^"]*"/i', '', $html);

        // 3. Strip class attributes that contain elementor- classnames
        $html = preg_replace('/\s class="[^"]*elementor[^"]*"/i', '', $html);

        // 4. Whitelist-only tags
        $allowed = $this->cfg['extraction']['allowed_html_tags'];
        $html    = strip_tags($html, $allowed);

        // 5. Remove empty <p> and <li> tags
        $html = preg_replace('/<(p|li)>\s*<\/\1>/i', '', $html);

        // 6. Apply text-level normalisation
        $text = $this->cleanText($html);

        return trim($text);
    }

    // ── Price parsing ──────────────────────────────────────────────────────────

    /**
     * Parse a price string and return integer piastres (EGP × 100).
     * Returns null if no price pattern matches.
     */
    public function parsePricePiastres(?string $raw): ?int
    {
        if ($raw === null || trim($raw) === '') return null;

        foreach ($this->cfg['extraction']['price_patterns'] as $pattern) {
            if (preg_match($pattern, $raw, $m)) {
                $amount = (float) str_replace(',', '', $m[1]);
                return (int) round($amount * 100);
            }
        }

        return null;
    }

    // ── Phone extraction ───────────────────────────────────────────────────────

    /**
     * Extract all recognisable Egyptian phone numbers from a text block.
     * Returns an array of E.164-formatted strings.
     */
    public function extractPhones(string $text): array
    {
        $found = [];

        // Egyptian hotlines: 5-digit starting with 1 (e.g. 16029)
        preg_match_all('/\b1[0-9]{4}\b/', $text, $m);
        foreach ($m[0] as $h) {
            $found[] = $h;
        }

        // Mobile: 010 / 011 / 012 / 015 XXXXXXXX
        preg_match_all('/(?:\+?2?0?)(1[0125]\d{8})/', str_replace(['-', ' ', '.'], '', $text), $m);
        foreach ($m[1] as $mobile) {
            $found[] = '+20' . $mobile;
        }

        return array_values(array_unique(array_filter($found)));
    }

    // ── Media URL normalisation ────────────────────────────────────────────────

    /**
     * Strip WordPress size suffixes and resolve the URL to absolute form.
     */
    public function resolveMediaUrl(string $url, string $pageUrl): string
    {
        // Protocol-relative
        if (str_starts_with($url, '//')) {
            $url = 'https:' . $url;
        }

        // Absolute path relative to source domain
        if (str_starts_with($url, '/')) {
            $base = parse_url($pageUrl);
            $url  = ($base['scheme'] ?? 'https') . '://' . ($base['host'] ?? '') . $url;
        }

        // Strip known WP size suffixes
        foreach ($this->cfg['media']['wp_size_suffixes'] as $suffix) {
            // Only strip if it appears just before the file extension
            $ext = '.' . pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
            if ($ext !== '.') {
                $url = str_replace($suffix . $ext, $ext, $url);
            }
        }

        // Strip dynamic -NNNxNNN size variants (e.g. image-800x600.jpg → image.jpg)
        $url = preg_replace('/-\d{2,4}x\d{2,4}(\.[a-z]{3,4})$/i', '$1', $url);

        return $url;
    }

    // ── Slug utilities ─────────────────────────────────────────────────────────

    /**
     * Extract the last path segment of a URL and apply corrections + blocklist.
     */
    public function slugFromUrl(string $url): string
    {
        $path    = parse_url($url, PHP_URL_PATH) ?? '';
        $segment = basename(rtrim($path, '/'));
        return $this->normalizeSlug($segment);
    }

    /**
     * Convert an arbitrary string to a slug, applying corrections and blocklist.
     */
    public function normalizeSlug(string $input): string
    {
        $slug  = Str::slug($input);
        $corr  = $this->cfg['extraction']['slug_corrections'];
        $block = $this->cfg['extraction']['slug_blocklist'];

        if (isset($corr[$slug])) {
            $slug = $corr[$slug];
        }

        if (in_array($slug, $block, true)) {
            $slug = 'imported-' . $slug;
        }

        return $slug;
    }

    // ── Hashing ────────────────────────────────────────────────────────────────

    /**
     * Produce a stable MD5 hash over an ordered list of fields.
     * Used for duplicate detection across runs.
     *
     * @param array<string|null> $fields
     */
    public function contentHash(array $fields): string
    {
        $normalized = array_map(
            fn($v) => strtolower(trim(strip_tags((string) ($v ?? '')))),
            $fields,
        );

        return md5(implode('||', $normalized));
    }
}
