<?php

namespace App\Services\Migration;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Extracts structured data from raw HTML pages.
 *
 * Each entity type (package, category, page) has its own private method.
 * The public extract() dispatcher routes to the correct one and always
 * returns a typed array or null — never throws.
 */
class HtmlExtractionService
{
    public function __construct(private readonly ContentNormalizer $normalizer) {}

    // ── Public dispatcher ──────────────────────────────────────────────────────

    public function extract(string $html, string $url, string $type): ?array
    {
        try {
            return match ($type) {
                'package'  => $this->extractPackage($html, $url),
                'category' => $this->extractCategory($html, $url),
                'page'     => $this->extractPage($html, $url),
                default    => null,
            };
        } catch (\Throwable $e) {
            Log::channel('migration')->error('Extraction failed', [
                'url'   => $url,
                'type'  => $type,
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
            ]);
            return null;
        }
    }

    // ── Package (WooCommerce product page) ─────────────────────────────────────

    private function extractPackage(string $html, string $url): array
    {
        $c = new Crawler($html, $url);

        $nameRaw = $this->firstText($c, [
            '.product_title',
            'h1.entry-title',
            'h1.product-title',
            '.woocommerce-loop-product__title',
            'h1',
        ]);

        $slug = $this->normalizer->slugFromUrl($url);

        // ── Prices ────────────────────────────────────────────────────────────
        $priceData = $this->extractPrices($c);

        // ── Descriptions ──────────────────────────────────────────────────────
        $shortHtml = $this->firstHtml($c, [
            '.woocommerce-product-details__short-description',
            '.product-short-description',
        ]);

        $longHtml = $this->firstHtml($c, [
            '#tab-description .woocommerce-Tabs-panel--description',
            '.woocommerce-Tabs-panel',
            '.entry-content',
            '.product-description',
        ]) ?? $shortHtml;

        // ── Category slugs ────────────────────────────────────────────────────
        $categorySlugs = [];

        // From "posted_in" meta
        try {
            $c->filter('.posted_in a, .product_meta a[href*="product-category"]')
              ->each(function (Crawler $a) use (&$categorySlugs) {
                  $href = $a->attr('href') ?? '';
                  if (str_contains($href, '/product-category/')) {
                      $categorySlugs[] = $this->normalizer->slugFromUrl($href);
                  }
              });
        } catch (\Throwable) {}

        // From body CSS classes (product_cat-{slug})
        try {
            $bodyClass = $c->filter('body')->attr('class') ?? '';
            preg_match_all('/product_cat-([a-z0-9-]+)/', $bodyClass, $m);
            foreach ($m[1] as $cs) {
                $categorySlugs[] = $cs;
            }
        } catch (\Throwable) {}

        $categorySlugs = array_values(array_unique(array_filter($categorySlugs)));

        // ── Images ────────────────────────────────────────────────────────────
        $images = [];
        try {
            $c->filter('.woocommerce-product-gallery img, .product-image img, .wp-post-image')
              ->each(function (Crawler $img) use (&$images, $url) {
                  $src = $img->attr('data-large_image')
                      ?? $img->attr('data-src')
                      ?? $img->attr('src')
                      ?? '';

                  if ($src && !$this->isPlaceholder($src)) {
                      $images[] = $this->normalizer->resolveMediaUrl($src, $url);
                  }
              });
        } catch (\Throwable) {}

        $images = array_values(array_unique(array_filter($images)));

        // ── Meta ──────────────────────────────────────────────────────────────
        $metaTitle = $this->firstAttr($c, [
            'meta[property="og:title"]',
            'meta[name="twitter:title"]',
        ], 'content') ?? $nameRaw ?? '';

        $metaDesc = $this->firstAttr($c, [
            'meta[name="description"]',
            'meta[property="og:description"]',
        ], 'content') ?? '';

        return [
            '_meta' => [
                'source_url'   => $url,
                'extracted_at' => now()->toISOString(),
                'entity_type'  => 'package',
                'content_hash' => $this->normalizer->contentHash([
                    $nameRaw ?? '',
                    (string) ($priceData['price_piastres'] ?? ''),
                ]),
            ],
            'slug'                      => $slug,
            'name_en'                   => $this->normalizer->cleanText($nameRaw ?? ''),
            'description_en'            => $this->normalizer->cleanHtml($longHtml ?? ''),
            'short_description_en'      => $this->normalizer->cleanHtml($shortHtml ?? ''),
            'price_raw'                 => $priceData['raw'],
            'price_piastres'            => $priceData['price_piastres'],
            'original_price_piastres'   => $priceData['original_price_piastres'],
            'category_slugs'            => $categorySlugs,
            'images'                    => $images,
            'sample_type'               => $this->inferSampleType($nameRaw ?? '', $longHtml ?? ''),
            'is_kit'                    => $this->inferIsKit($nameRaw ?? '', $longHtml ?? '', $url),
            'meta_title_en'             => $this->normalizer->cleanText($metaTitle),
            'meta_description_en'       => $this->normalizer->cleanText($metaDesc),
            // AR — populated by translation workflow, not by crawl
            'name_ar'                   => null,
            'description_ar'            => null,
            'short_description_ar'      => null,
            'meta_title_ar'             => null,
            'meta_description_ar'       => null,
        ];
    }

    // ── Category page ──────────────────────────────────────────────────────────

    private function extractCategory(string $html, string $url): array
    {
        $c = new Crawler($html, $url);

        $nameRaw = $this->firstText($c, [
            '.woocommerce-products-header__title',
            'h1.page-title',
            'h1.entry-title',
            'h1',
        ]);

        $descHtml = $this->firstHtml($c, [
            '.term-description',
            '.category-description',
            '.woocommerce-product-details__short-description',
        ]);

        $metaTitle = $this->firstAttr($c, [
            'meta[property="og:title"]',
            'meta[name="title"]',
        ], 'content') ?? $nameRaw ?? '';

        $metaDesc = $this->firstAttr($c, [
            'meta[name="description"]',
            'meta[property="og:description"]',
        ], 'content') ?? '';

        $slug = $this->normalizer->slugFromUrl($url);

        // Count products visible in the listing
        $productCount = 0;
        try { $productCount = $c->filter('.product, li.product')->count(); } catch (\Throwable) {}

        return [
            '_meta' => [
                'source_url'   => $url,
                'extracted_at' => now()->toISOString(),
                'entity_type'  => 'category',
                'content_hash' => $this->normalizer->contentHash([$slug]),
            ],
            'slug'                => $slug,
            'name_en'             => $this->normalizer->cleanText($nameRaw ?? ''),
            'description_en'      => $this->normalizer->cleanHtml($descHtml ?? ''),
            'meta_title_en'       => $this->normalizer->cleanText($metaTitle),
            'meta_description_en' => $this->normalizer->cleanText($metaDesc),
            'product_count'       => $productCount,
            'name_ar'             => null,
            'description_ar'      => null,
            'meta_title_ar'       => null,
            'meta_description_ar' => null,
        ];
    }

    // ── Generic page ───────────────────────────────────────────────────────────

    private function extractPage(string $html, string $url): array
    {
        $c = new Crawler($html, $url);

        $titleRaw = $this->firstText($c, [
            'h1.entry-title',
            'h1.page-title',
            'h1',
        ]);

        $contentHtml = $this->firstHtml($c, [
            '.entry-content',
            'article .content',
            '.page-content',
            'main',
        ]);

        $metaTitle = $this->firstAttr($c, ['meta[property="og:title"]', 'meta[name="title"]'], 'content') ?? $titleRaw ?? '';
        $metaDesc  = $this->firstAttr($c, ['meta[name="description"]', 'meta[property="og:description"]'], 'content') ?? '';

        $slug     = $this->normalizer->slugFromUrl($url);
        $pageType = $this->classifyPageType($url, $titleRaw ?? '', $contentHtml ?? '');

        // Specialised sub-extractions
        $faqItems   = $pageType === 'faq'      ? $this->extractFaqItems($c)    : [];
        $branchData = $pageType === 'branch'    ? $this->extractBranchData($c, $url) : [];
        $partners   = $pageType === 'partners'  ? $this->extractPartners($c, $url) : [];

        return [
            '_meta' => [
                'source_url'   => $url,
                'extracted_at' => now()->toISOString(),
                'entity_type'  => 'page',
                'page_type'    => $pageType,
                'content_hash' => $this->normalizer->contentHash([$slug]),
            ],
            'slug'                => $slug,
            'page_type'           => $pageType,
            'title_en'            => $this->normalizer->cleanText($titleRaw ?? ''),
            'content_en'          => $this->normalizer->cleanHtml($contentHtml ?? ''),
            'meta_title_en'       => $this->normalizer->cleanText($metaTitle),
            'meta_description_en' => $this->normalizer->cleanText($metaDesc),
            'faq_items'           => $faqItems,
            'branch_data'         => $branchData,
            'partners'            => $partners,
            'title_ar'            => null,
            'content_ar'          => null,
            'meta_title_ar'       => null,
            'meta_description_ar' => null,
        ];
    }

    // ── Sub-extractors ─────────────────────────────────────────────────────────

    private function extractPrices(Crawler $c): array
    {
        $raw = null;

        // WooCommerce price selectors — most specific first
        $selectors = [
            '.price ins .woocommerce-Price-amount bdi',
            '.price .woocommerce-Price-amount:not(del *) bdi',
            '.entry-summary p.price',
            '.product-price',
            'span.price',
            'p.price',
        ];

        foreach ($selectors as $sel) {
            $val = $this->firstText($c, [$sel]);
            if ($val !== null && $val !== '') { $raw = $val; break; }
        }

        // Original price (before sale)
        $originalPiastres = null;
        try {
            $del = $c->filter('.price del .woocommerce-Price-amount bdi');
            if ($del->count() > 0) {
                $originalPiastres = $this->normalizer->parsePricePiastres($del->first()->text());
            }
        } catch (\Throwable) {}

        $pricePiastres = $raw !== null ? $this->normalizer->parsePricePiastres($raw) : null;

        // Sanity cap
        $cap = config('migration.extraction.max_price_piastres');
        if ($pricePiastres !== null && $pricePiastres > $cap) {
            Log::channel('migration')->warning('Price exceeds sanity cap — nulled', ['raw' => $raw, 'piastres' => $pricePiastres]);
            $pricePiastres = null;
        }

        return [
            'raw'                    => $raw,
            'price_piastres'         => $pricePiastres,
            'original_price_piastres' => $originalPiastres,
        ];
    }

    private function extractFaqItems(Crawler $c): array
    {
        $items = [];

        // Attempt 1 — Elementor toggle widget
        try {
            $c->filter('.elementor-toggle-item')->each(function (Crawler $node) use (&$items) {
                $q = $this->firstText($node, ['.elementor-toggle-title', '.elementor-tab-title']);
                $a = $this->firstHtml($node, ['.elementor-toggle-content', '.elementor-tab-content']);
                if ($q && $a) {
                    $items[] = [
                        'question' => $this->normalizer->cleanText($q),
                        'answer'   => $this->normalizer->cleanHtml($a),
                    ];
                }
            });
        } catch (\Throwable) {}

        // Attempt 2 — <dl><dt><dd>
        if (empty($items)) {
            try {
                $c->filter('dl dt')->each(function (Crawler $dt) use (&$items) {
                    $answer = '';
                    try {
                        $dd = $dt->nextAll()->filter('dd')->first();
                        if ($dd->count() > 0) $answer = $dd->html();
                    } catch (\Throwable) {}

                    $items[] = [
                        'question' => $this->normalizer->cleanText($dt->text()),
                        'answer'   => $this->normalizer->cleanHtml($answer),
                    ];
                });
            } catch (\Throwable) {}
        }

        // Attempt 3 — h3 followed by p pattern inside .entry-content
        if (empty($items)) {
            try {
                $c->filter('.entry-content h3, .faq h3')->each(function (Crawler $h3) use (&$items) {
                    $answer = '';
                    try {
                        $p = $h3->nextAll()->filter('p')->first();
                        if ($p->count() > 0) $answer = $p->html();
                    } catch (\Throwable) {}

                    $q = $this->normalizer->cleanText($h3->text());
                    if ($q) {
                        $items[] = [
                            'question' => $q,
                            'answer'   => $this->normalizer->cleanHtml($answer),
                        ];
                    }
                });
            } catch (\Throwable) {}
        }

        return $items;
    }

    private function extractBranchData(Crawler $c, string $url): array
    {
        $fullText = '';
        try { $fullText = $c->filter('main, .entry-content, .page-content')->text(''); } catch (\Throwable) {}

        return [
            'phones'      => $this->normalizer->extractPhones($fullText),
            'email'       => $this->extractEmail($fullText),
            'hours_raw'   => $this->extractHoursText($c, $fullText),
            'address_raw' => $this->extractAddressText($c),
            'map_url'     => $this->extractMapUrl($c),
        ];
    }

    private function extractPartners(Crawler $c, string $url): array
    {
        $partners = [];

        $selectors = [
            '.partner-logo img',
            '.accreditation img',
            '.clients-logo img',
            '[class*="partner"] img',
            '[class*="accredit"] img',
            '.gallery img',
        ];

        foreach ($selectors as $sel) {
            try {
                $c->filter($sel)->each(function (Crawler $img) use (&$partners, $url) {
                    $src = $img->attr('src') ?? '';
                    $alt = trim($img->attr('alt') ?? '');

                    if ($src && !$this->isPlaceholder($src)) {
                        $partners[] = [
                            'name'     => $this->normalizer->cleanText($alt),
                            'logo_url' => $this->normalizer->resolveMediaUrl($src, $url),
                            'website'  => null,
                        ];
                    }
                });
            } catch (\Throwable) {}

            if (!empty($partners)) break;
        }

        return $partners;
    }

    private function extractEmail(string $text): ?string
    {
        if (preg_match('/[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/', $text, $m)) {
            return strtolower(trim($m[0]));
        }
        return null;
    }

    private function extractHoursText(Crawler $c, string $fullText): ?string
    {
        // Try dedicated selectors first
        $text = $this->firstText($c, [
            '.opening-hours', '.business-hours', '.working-hours',
            '[class*="hour"]', '[class*="schedule"]',
        ]);

        if ($text) return $text;

        // Pattern match in full page text
        if (preg_match('/(?:open(?:ing)?\s*hours?|working\s*hours?)[^\n]{0,300}/si', $fullText, $m)) {
            return trim($m[0]);
        }

        return null;
    }

    private function extractAddressText(Crawler $c): ?string
    {
        return $this->firstText($c, [
            'address',
            '.address',
            '.location-address',
            '[class*="address"]',
            '[itemtype*="PostalAddress"]',
        ]);
    }

    private function extractMapUrl(Crawler $c): ?string
    {
        try {
            $iframe = $c->filter('iframe[src*="google.com/maps"], iframe[src*="maps.google"], iframe[src*="goo.gl/maps"]');
            if ($iframe->count() > 0) {
                return $iframe->first()->attr('src');
            }
        } catch (\Throwable) {}
        return null;
    }

    // ── Inference helpers ──────────────────────────────────────────────────────

    private function inferSampleType(string $name, string $html): ?string
    {
        $text = strtolower($name . ' ' . strip_tags($html));

        return match (true) {
            str_contains($text, 'urine') || str_contains($text, 'urinalysis')                           => 'Urine',
            str_contains($text, 'stool') || str_contains($text, 'feces') || str_contains($text, 'fecal') => 'Stool',
            str_contains($text, 'swab')                                                                   => 'Swab',
            str_contains($text, 'sputum') || str_contains($text, 'saliva')                               => 'Saliva',
            str_contains($text, 'blood') || str_contains($text, 'serum') || str_contains($text, 'plasma') => 'Blood',
            default                                                                                        => null,
        };
    }

    private function inferIsKit(string $name, string $html, string $url): bool
    {
        $combined = strtolower($name . ' ' . strip_tags($html) . ' ' . $url);

        return str_contains($combined, 'panel')
            || str_contains($combined, 'package')
            || str_contains($combined, 'profile')
            || str_contains($combined, ' kit')
            || str_contains($combined, 'screen');
    }

    private function classifyPageType(string $url, string $title, string $html): string
    {
        $combined = strtolower($url . ' ' . $title . ' ' . strip_tags($html));

        return match (true) {
            str_contains($combined, 'faq')        || str_contains($combined, 'frequently asked')  => 'faq',
            str_contains($combined, 'branch')     || str_contains($combined, 'location')
                                                  || str_contains($combined, 'contact')            => 'branch',
            str_contains($combined, 'accredit')   || str_contains($combined, 'partner')
                                                  || str_contains($combined, 'certif')             => 'partners',
            str_contains($combined, 'about')      || str_contains($combined, 'who we are')         => 'about',
            str_contains($combined, 'service')                                                     => 'services',
            default                                                                                => 'generic',
        };
    }

    private function isPlaceholder(string $src): bool
    {
        $lower = strtolower($src);
        return str_contains($lower, 'placeholder')
            || str_contains($lower, 'woocommerce-placeholder')
            || str_contains($lower, 'no-image');
    }

    // ── DOM traversal utilities ────────────────────────────────────────────────

    private function firstText(Crawler $c, array $selectors): ?string
    {
        foreach ($selectors as $sel) {
            try {
                $node = $c->filter($sel);
                if ($node->count() > 0) {
                    $text = trim($node->first()->text(''));
                    if ($text !== '') return $text;
                }
            } catch (\Throwable) {}
        }
        return null;
    }

    private function firstHtml(Crawler $c, array $selectors): ?string
    {
        foreach ($selectors as $sel) {
            try {
                $node = $c->filter($sel);
                if ($node->count() > 0) {
                    $html = trim($node->first()->html(''));
                    if ($html !== '') return $html;
                }
            } catch (\Throwable) {}
        }
        return null;
    }

    private function firstAttr(Crawler $c, array $selectors, string $attr): ?string
    {
        foreach ($selectors as $sel) {
            try {
                $node = $c->filter($sel);
                if ($node->count() > 0) {
                    $val = trim($node->first()->attr($attr) ?? '');
                    if ($val !== '') return $val;
                }
            } catch (\Throwable) {}
        }
        return null;
    }
}
