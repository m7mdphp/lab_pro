<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\TestCategory;
use Illuminate\View\View;

class ServicesController extends Controller
{
    public function index(): View
    {
        $services = Service::active()
            ->with('translations')
            ->get();

        $categories = TestCategory::active()
            ->with('translations')
            ->get();

        return view('services.index', compact('services', 'categories'));
    }

    public function show(string $slug): View
    {
        $service = Service::where('slug', $slug)
            ->where('is_active', true)
            ->with('translations')
            ->firstOrFail();

        $otherServices = Service::active()
            ->where('slug', '!=', $slug)
            ->with('translations')
            ->get();

        return view('services.show', compact('service', 'otherServices'));
    }
}
