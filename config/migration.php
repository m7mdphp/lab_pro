<?php

return [

    // ── Source site ───────────────────────────────────────────────────────────
    'source' => [
        'domain'  => 'https://acculab.org',
        'sitemap' => 'https://acculab.org/sitemap.xml',
        'entry'   => 'https://acculab.org/',
    ],

    // ── HTTP client ───────────────────────────────────────────────────────────
    'http' => [
        'timeout'         => 30,
        'connect_timeout' => 10,
        'delay_ms'        => 600,       // polite delay between requests
        'retry_times'     => 3,
        'retry_sleep_ms'  => 2000,
        'user_agent'      => 'Mozilla/5.0 (compatible; site-migration/1.0; +internal)',
        'verify_ssl'      => true,
    ],

    // ── Crawler behaviour ─────────────────────────────────────────────────────
    'crawl' => [
        'max_pages'     => 600,
        'allowed_hosts' => ['acculab.org', 'www.acculab.org'],

        'exclude_patterns' => [
            '#[?#]#',               // drop query strings and fragments
            '#/cart/?$#',
            '#/checkout/?$#',
            '#/my-account#',
            '#/wp-admin#',
            '#/wp-login#',
            '#/wp-json#',
            '#/feed/?$#',
            '#/page/\d+#',          // pagination beyond first page
            '#\.(pdf|zip|doc|xls|exe|gz)$#i',
        ],
    ],

    // ── URL type classification (order matters — first match wins) ────────────
    'url_patterns' => [
        'package'  => '#/product/[^/?#]+/?$#',
        'category' => '#/product-category/[^/?#]+/?$#',
        'page'     => '#^https://acculab\.org/[^/?#]+/?$#',
    ],

    // ── File paths ────────────────────────────────────────────────────────────
    'storage' => [
        'base_path' => storage_path('app/migration'),
        'raw_html'  => storage_path('app/migration/raw'),
        'logs'      => storage_path('logs/migration'),

        'exports' => [
            'packages'   => storage_path('app/migration/extracted/packages.jsonl'),
            'categories' => storage_path('app/migration/extracted/categories.jsonl'),
            'branches'   => storage_path('app/migration/extracted/branches.jsonl'),
            'faqs'       => storage_path('app/migration/extracted/faqs.jsonl'),
            'partners'   => storage_path('app/migration/extracted/partners.jsonl'),
            'pages'      => storage_path('app/migration/extracted/pages.jsonl'),
            'media'      => storage_path('app/migration/extracted/media.jsonl'),
            'urls'       => storage_path('app/migration/extracted/discovered_urls.json'),
            'failed'     => storage_path('app/migration/extracted/failed.jsonl'),
        ],
    ],

    // ── Content extraction rules ──────────────────────────────────────────────
    'extraction' => [

        // Tags allowed in cleaned HTML fields
        'allowed_html_tags' => '<p><br><ul><ol><li><strong><em><b><i><h2><h3><h4><table><tr><td><th><thead><tbody>',

        // Phrases stripped from all text/HTML fields
        'boilerplate_phrases' => [
            'At AccuLab',
            'Visit our website',
            'Contact us today',
            'Book now',
            'acculab.org',
            'Maadi | Nasr City',
            'Nasr City | Maadi',
            'Our laboratory',
            'Call us',
            'WhatsApp us',
        ],

        // Source brand names — replaced with placeholder during normalization
        'brand_names' => ['AccuLab', 'Accu Lab', 'Acculab'],

        // Known slug remappings
        'slug_corrections' => [
            'cbc'          => 'complete-blood-count',
            'lft'          => 'liver-function-test',
            'kft'          => 'kidney-function-test',
            'rft'          => 'kidney-function-test',
            'tsh'          => 'thyroid-stimulating-hormone',
            'hba1c'        => 'hemoglobin-a1c',
            'accupackages' => 'gut-health',
            'tests'        => 'laboratory-tests',
        ],

        // Slugs that must not be imported as real entities
        'slug_blocklist' => [
            'uncategorized', 'test', 'page', 'home', 'shop',
            'store', 'product', 'sample', 'example',
        ],

        // Regex patterns tried in order to parse a price string
        'price_patterns' => [
            '/EGP\s*([\d,]+(?:\.\d{1,2})?)/i',
            '/([\d,]+(?:\.\d{1,2})?)\s*EGP/i',
            '/LE\s*([\d,]+(?:\.\d{1,2})?)/i',
            '/([\d,]+(?:\.\d{1,2})?)\s*LE/i',
            '/ج\.م\s*([\d,]+(?:\.\d{1,2})?)/u',
            '/([\d,]+(?:\.\d{1,2})?)\s*ج\.م/u',
        ],

        // Sanity cap: 500 000 EGP × 100 piastres
        'max_price_piastres' => 50_000_000,

        // Minimum content lengths for quality scoring
        'min_description_chars' => 30,
        'min_name_chars'        => 3,
    ],

    // ── Media download rules ──────────────────────────────────────────────────
    'media' => [
        'allowed_mimes' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml',
        ],
        'skip_pdf'     => true,
        'max_size'     => 10 * 1024 * 1024,  // 10 MB

        // WordPress image size suffixes to strip from URLs
        'wp_size_suffixes' => [
            '-150x150', '-300x200', '-300x300', '-400x400',
            '-600x400', '-768x512', '-768x768',
            '-1024x683', '-1024x768', '-1024x1024',
            '-1200x800', '-scaled', '-e1',
        ],

        'local_disk'   => 'public',
        'local_prefix' => 'media',
    ],

    // ── Import behaviour ──────────────────────────────────────────────────────
    'import' => [
        'dry_run'    => false,
        'chunk_size' => 50,
        'order'      => ['categories', 'packages', 'branches', 'faqs', 'partners', 'pages'],
    ],

    // ── Quality score thresholds (grade = first threshold met) ────────────────
    'quality' => [
        'A' => 0.85,
        'B' => 0.65,
        'C' => 0.40,
        // below C = F
    ],

];
