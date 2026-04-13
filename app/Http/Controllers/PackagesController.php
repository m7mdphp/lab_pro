<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\TestCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackagesController extends Controller
{
    public function index(Request $request): View
    {
        $categories = TestCategory::active()->with('translations')->get();

        $query = Package::active()->with(['translations', 'categories.translations']);

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $packages = $query->orderBy('sort_order')->paginate(12)->withQueryString();

        return view('packages.index', compact('packages', 'categories'));
    }

    public function show(string $slug): View
    {
        $package = Package::where('slug', $slug)
            ->active()
            ->with(['translations', 'categories.translations', 'thumbnail'])
            ->firstOrFail();

        return view('packages.show', compact('package'));
    }
}
