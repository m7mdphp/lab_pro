<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach($urls as $url)
<url>
    <loc>{{ $url['loc'] }}</loc>
    @if(isset($url['lastmod']))
    <lastmod>{{ $url['lastmod'] }}</lastmod>
    @endif
    <changefreq>{{ $url['changefreq'] ?? 'weekly' }}</changefreq>
    <priority>{{ $url['priority'] ?? '0.8' }}</priority>
    @if(isset($url['alternates']))
    @foreach($url['alternates'] as $lang => $href)
    <xhtml:link rel="alternate" hreflang="{{ $lang }}" href="{{ $href }}"/>
    @endforeach
    <xhtml:link rel="alternate" hreflang="x-default" href="{{ $url['alternates']['en'] ?? $url['loc'] }}"/>
    @endif
</url>
@endforeach
</urlset>
