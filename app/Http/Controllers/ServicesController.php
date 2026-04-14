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
}
