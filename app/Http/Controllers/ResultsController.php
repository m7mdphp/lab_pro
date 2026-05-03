<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\View\View;

class ResultsController extends Controller
{
    public function index(): View
    {
        $portalUrl    = SiteSetting::get('results_portal_url', '');
        $portalLabel  = SiteSetting::get('results_portal_label_ar', 'بوابة IbnHayan');
        $portalLabelEn = SiteSetting::get('results_portal_label_en', 'IbnHayan Portal');

        return view('results.index', compact('portalUrl', 'portalLabel', 'portalLabelEn'));
    }
}
