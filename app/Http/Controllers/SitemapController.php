<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $baseUrl = rtrim(config('app.url'), '/');

        // Static pages (EN + AR)
        $staticPages = [
            '', 'about', 'services', 'tests', 'packages', 'branches',
            'partners', 'prepare-for-your-tests', 'contact', 'book-a-test',
            'blog', 'team', 'my-results', 'doctor-services', 'corporate-services',
        ];

        $urls = [];

        foreach ($staticPages as $page) {
            $enUrl = $baseUrl . ($page ? '/' . $page : '/');
            $arUrl = $baseUrl . '/ar' . ($page ? '/' . $page : '');

            $urls[] = [
                'loc'        => $enUrl,
                'alternates' => ['en' => $enUrl, 'ar' => $arUrl],
                'priority'   => $page === '' ? '1.0' : '0.8',
                'changefreq' => 'weekly',
            ];
        }

        // Blog posts
        $posts = Post::published()->get(['slug', 'updated_at']);
        foreach ($posts as $post) {
            $enUrl = $baseUrl . '/blog/' . $post->slug;
            $arUrl = $baseUrl . '/ar/blog/' . $post->slug;
            $urls[] = [
                'loc'        => $enUrl,
                'alternates' => ['en' => $enUrl, 'ar' => $arUrl],
                'lastmod'    => $post->updated_at->toAtomString(),
                'priority'   => '0.7',
                'changefreq' => 'monthly',
            ];
        }

        // Packages
        $packages = Package::active()->get(['slug', 'updated_at']);
        foreach ($packages as $pkg) {
            $enUrl = $baseUrl . '/packages/' . $pkg->slug;
            $arUrl = $baseUrl . '/ar/packages/' . $pkg->slug;
            $urls[] = [
                'loc'        => $enUrl,
                'alternates' => ['en' => $enUrl, 'ar' => $arUrl],
                'lastmod'    => $pkg->updated_at->toAtomString(),
                'priority'   => '0.7',
                'changefreq' => 'weekly',
            ];
        }

        // Services
        $services = Service::active()->get(['slug', 'updated_at']);
        foreach ($services as $svc) {
            $enUrl = $baseUrl . '/services/' . $svc->slug;
            $arUrl = $baseUrl . '/ar/services/' . $svc->slug;
            $urls[] = [
                'loc'        => $enUrl,
                'alternates' => ['en' => $enUrl, 'ar' => $arUrl],
                'lastmod'    => $svc->updated_at->toAtomString(),
                'priority'   => '0.7',
                'changefreq' => 'monthly',
            ];
        }

        $xml = view('sitemap', compact('urls'))->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
