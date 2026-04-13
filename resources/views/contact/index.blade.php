@extends('layouts.app')

@section('title', __('site.contact.title'))
@section('description', __('site.contact.subtitle'))

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-blue-900 to-cyan-800 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-extrabold mb-3">{{ __('site.contact.title') }}</h1>
        <p class="text-blue-100">{{ __('site.contact.subtitle') }}</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">

        {{-- Form --}}
        <div class="lg:col-span-3">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route(app()->getLocale() === 'ar' ? 'ar.contact.store' : 'contact.store') }}"
                  class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            {{ __('site.contact.name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-400 @enderror">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            {{ __('site.contact.phone') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-400 @enderror">
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('site.contact.email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-400 @enderror">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        {{ __('site.contact.message') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea name="message" rows="5" required
                              class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('message') border-red-400 @enderror">{{ old('message') }}</textarea>
                    @error('message')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <button type="submit"
                        class="w-full sm:w-auto px-8 py-3 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl transition-colors">
                    {{ __('site.contact.send') }}
                </button>
            </form>
        </div>

        {{-- Info --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-blue-50 rounded-2xl p-6">
                <h3 class="font-bold text-slate-900 mb-4">{{ __('site.contact.info_title') }}</h3>
                <ul class="space-y-4 text-sm text-slate-600">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ __('site.contact.address') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <a href="tel:19000" class="font-semibold text-blue-700 hover:text-blue-800">19XXX</a>
                    </li>
                </ul>
            </div>

            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200">
                <p class="text-sm text-slate-500">{{ __('site.booking.hint') }}</p>
                <a href="tel:19000" class="text-2xl font-bold text-blue-700 mt-1 block">📞 19XXX</a>
            </div>
        </div>
    </div>
</div>

@endsection
