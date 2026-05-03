<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DoctorServicesController extends Controller
{
    public function index(): View
    {
        return view('doctor-services.index');
    }
}
