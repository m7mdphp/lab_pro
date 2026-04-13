<?php

namespace App\Http\Controllers;

use App\Models\TestCategory;
use Illuminate\View\View;

class ServicesController extends Controller
{
    public function index(): View
    {
        $categories = TestCategory::active()
            ->with('translations')
            ->get();

        return view('services.index', compact('categories'));
    }
}
