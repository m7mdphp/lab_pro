<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Post::published();

        // Category filter
        if ($request->filled('category')) {
            $locale = app()->getLocale();
            $col    = $locale === 'ar' ? 'category_ar' : 'category_en';
            $query->where($col, $request->category);
        }

        // Search
        if ($request->filled('q')) {
            $s = $request->q;
            $query->where(function ($q) use ($s) {
                $q->where('title_ar', 'like', "%{$s}%")
                  ->orWhere('title_en', 'like', "%{$s}%")
                  ->orWhere('excerpt_ar', 'like', "%{$s}%")
                  ->orWhere('excerpt_en', 'like', "%{$s}%");
            });
        }

        $posts = $query->paginate(9);

        // All unique categories for filter chips
        $locale     = app()->getLocale();
        $catCol     = $locale === 'ar' ? 'category_ar' : 'category_en';
        $categories = Post::published()->whereNotNull($catCol)->distinct()->pluck($catCol)->filter()->values();

        return view('blog.index', compact('posts', 'categories'));
    }

    public function show(string $slug): View
    {
        $post = Post::where('slug', $slug)
                    ->where('is_published', true)
                    ->firstOrFail();

        $related = Post::published()
                       ->where('id', '!=', $post->id)
                       ->when($post->category_ar, fn ($q) =>
                           $q->where('category_ar', $post->category_ar)
                       )
                       ->limit(3)
                       ->get();

        return view('blog.show', compact('post', 'related'));
    }
}
