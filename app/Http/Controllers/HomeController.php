<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Faq;
use App\Models\Package;
use App\Models\TestCategory;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $locale = app()->getLocale();

        $categories = TestCategory::active()
            ->with('translations')
            ->limit(8)
            ->get();

        $featuredPackages = Package::active()
            ->featured()
            ->with('translations')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $branches = Branch::active()
            ->with('translations')
            ->limit(6)
            ->get();

        $faqs = Faq::active()
            ->with('translations')
            ->limit(8)
            ->get();

        return view('home.index', compact('categories', 'featuredPackages', 'branches', 'faqs'));
    }
}
