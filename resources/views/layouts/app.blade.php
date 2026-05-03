<!DOCTYPE html>
@php
    use App\Models\SiteSetting;
    $locale  = app()->getLocale();
    $isAr    = $locale === 'ar';
    $baseUrl = rtrim(config('app.url'), '/');
    $path    = request()->getPathInfo();

    // Canonical & hreflang
    $canonicalUrl = $baseUrl . $path;
    if ($isAr) {
        $stripped = ltrim(preg_replace('#^/ar/?#', '', $path), '/');
        $enUrl    = $baseUrl . ($stripped ? '/' . $stripped : '/');
        $arUrl    = $canonicalUrl;
    } else {
        $enUrl = $canonicalUrl;
        $arUrl = $baseUrl . '/ar' . (ltrim($path, '/') ? '/' . ltrim($path, '/') : '');
    }

    $siteName = SiteSetting::get($isAr ? 'site_name_ar' : 'site_name_en',
                    $isAr ? 'معامل الشيخة للتحاليل الطبية' : 'El-Sheikha Lab');
    $hotline  = SiteSetting::get('hotline', '19XXX');
    $logoUrl  = asset('images/logo.png');
@endphp
<html lang="{{ $locale }}" dir="{{ $isAr ? 'rtl' : 'ltr' }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Primary SEO --}}
    <title>@yield('title', $siteName) — {{ $siteName }}</title>
    <meta name="description" content="@yield('description', SiteSetting::get($isAr ? 'tagline_ar' : 'tagline_en', 'Accurate lab testing you can trust.'))">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    {{-- Hreflang --}}
    <link rel="alternate" hreflang="ar" href="{{ $arUrl }}">
    <link rel="alternate" hreflang="en" href="{{ $enUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $enUrl }}">

    {{-- Open Graph --}}
    <meta property="og:type"        content="@hasSection('og_type')@yield('og_type')@else website @endif">
    <meta property="og:url"         content="{{ $canonicalUrl }}">
    <meta property="og:title"       content="@yield('title', $siteName) — {{ $siteName }}">
    <meta property="og:description" content="@yield('description', SiteSetting::get($isAr ? 'tagline_ar' : 'tagline_en', 'Accurate lab testing.'))">
    <meta property="og:image"       content="@hasSection('og_image')@yield('og_image')@else{{ $logoUrl }}@endif">
    <meta property="og:site_name"   content="{{ $siteName }}">
    <meta property="og:locale"      content="{{ $isAr ? 'ar_EG' : 'en_US' }}">
    <meta property="og:locale:alternate" content="{{ $isAr ? 'en_US' : 'ar_EG' }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="@yield('title', $siteName)">
    <meta name="twitter:description" content="@yield('description', '')">
    <meta name="twitter:image"       content="@hasSection('og_image')@yield('og_image')@else{{ $logoUrl }}@endif">

    {{-- JSON-LD: MedicalOrganization (GEO + structured data) --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "MedicalOrganization",
        "name": "{{ SiteSetting::get('site_name_en', 'El-Sheikha Lab') }}",
        "alternateName": "{{ SiteSetting::get('site_name_ar', 'معامل الشيخة للتحاليل الطبية') }}",
        "url": "{{ $baseUrl }}",
        "logo": "{{ $logoUrl }}",
        "image": "{{ $logoUrl }}",
        "description": "ISO-certified medical laboratory testing with fast turnaround and home sample collection across Egypt.",
        "telephone": "{{ $hotline }}",
        "email": "{{ SiteSetting::get('email', '') }}",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "EG",
            "streetAddress": "{{ SiteSetting::get('address_en', 'El-Sheikha Lab, Egypt') }}"
        },
        "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Saturday","Sunday","Monday","Tuesday","Wednesday","Thursday"],
            "opens": "07:00",
            "closes": "21:00"
        },
        "hasCredential": [
            {"@type": "EducationalOccupationalCredential", "credentialCategory": "ISO 15189"},
            {"@type": "EducationalOccupationalCredential", "credentialCategory": "ISO 9001"}
        ],
        "medicalSpecialty": "Laboratory Medicine",
        "sameAs": [
            "{{ SiteSetting::get('facebook_url', '') }}",
            "{{ SiteSetting::get('instagram_url', '') }}"
        ]
    }
    </script>

    {{-- JSON-LD: WebSite --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "{{ $siteName }}",
        "url": "{{ $baseUrl }}"
    }
    </script>

    {{-- Per-page JSON-LD injected via @push('json_ld') --}}
    @stack('json_ld')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="{{ $isAr ? 'font-cairo' : 'font-sans' }} antialiased bg-white text-slate-800">

    @include('layouts.partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    @stack('scripts')
</body>
</html>
