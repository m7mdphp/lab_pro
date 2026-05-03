<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CorporateServicesController extends Controller
{
    public function index(): View
    {
        return view('corporate-services.index');
    }
}
