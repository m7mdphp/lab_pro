<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\View\View;

class PartnersController extends Controller
{
    public function index(): View
    {
        $partners = Partner::active()
            ->with('translations')
            ->get();

        return view('partners.index', compact('partners'));
    }
}
