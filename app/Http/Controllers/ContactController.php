<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('contact.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'required|string|max:50',
            'message' => 'required|string|max:5000',
        ]);

        ContactMessage::create($request->only('name', 'email', 'phone', 'message'));

        $locale = app()->getLocale();
        $prefix = $locale === 'ar' ? '/ar' : '';

        return redirect($prefix . '/contact')
            ->with('success', __('site.contact.success'));
    }
}
