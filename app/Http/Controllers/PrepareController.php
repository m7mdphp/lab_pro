<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PrepareController extends Controller
{
    public function index(): View
    {
        return view('prepare.index');
    }
}
