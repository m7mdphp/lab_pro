<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Branch;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $branches = Branch::active()->with('translations')->get();
        $packages = Package::active()->with('translations')->orderBy('sort_order')->get();

        return view('booking.index', compact('branches', 'packages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:50',
            'email'          => 'nullable|email|max:255',
            'test_name'      => 'nullable|string|max:255',
            'branch_id'      => 'nullable|exists:branches,id',
            'package_id'     => 'nullable|exists:packages,id',
            'preferred_date' => 'nullable|date|after:yesterday',
            'notes'          => 'nullable|string|max:2000',
        ]);

        Booking::create($request->only([
            'name', 'phone', 'email', 'test_name',
            'branch_id', 'package_id', 'preferred_date', 'notes',
        ]));

        $locale = app()->getLocale();
        $prefix = $locale === 'ar' ? '/ar' : '';

        return redirect($prefix . '/book-a-test')
            ->with('success', __('site.booking.success'));
    }
}
