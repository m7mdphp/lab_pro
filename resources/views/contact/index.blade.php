@extends('layouts.app')
@php
    $__l          = app()->getLocale() === 'ar' ? 'ar' : 'en';
    $pageTitle    = \App\Models\SiteSetting::get("text_contact_title_{$__l}") ?: __('site.contact.title');
    $pageSubtitle = \App\Models\SiteSetting::get("text_contact_subtitle_{$__l}") ?: __('site.contact.subtitle');
@endphp
@section('title', $pageTitle)
@section('description', $pageSubtitle)

@section('content')
@php
    use App\Models\SiteSetting;
    $contactHotline = SiteSetting::get('hotline', '19XXX');
    $contactAddress = SiteSetting::get(app()->getLocale() === 'ar' ? 'address_ar' : 'address_en', __('site.contact.address'));
    $contactEmail   = SiteSetting::get('email', '');
    $contactHours   = SiteSetting::get(app()->getLocale() === 'ar' ? 'working_hours_ar' : 'working_hours_en', '');
    $contactHeroImage = SiteSetting::get('image_contact_hero');
    $contactHeroUrl = $contactHeroImage
        ? (str_starts_with($contactHeroImage, 'http') ? $contactHeroImage : asset('storage/' . $contactHeroImage))
        : 'https://images.unsplash.com/photo-1586773860418-d37222d8fce3?w=1920&q=80&auto=format&fit=crop';
    $isAr = app()->getLocale() === 'ar';
@endphp

{{-- Hero --}}
<section class="relative text-white overflow-hidden" style="min-height: 360px;">
    <div class="page-hero-bg absolute inset-0"
         style="background-image: url('{{ $contactHeroUrl }}'); background-size: cover; background-position: center;"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-green-950/90 via-green-900/88 to-emerald-900/92"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <div class="page-hero-badge inline-flex items-center gap-2 bg-white/10 border border-white/20 backdrop-blur-sm text-white text-xs font-bold px-4 py-1.5 rounded-full mb-5">
            <span class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0"></span>
            {{ $isAr ? 'نحن هنا للمساعدة' : 'We Are Here to Help' }}
        </div>
        <h1 class="page-hero-title text-4xl md:text-5xl font-extrabold mb-4 leading-tight">{{ $pageTitle }}</h1>
        <p class="page-hero-subtitle text-green-100/85 text-lg max-w-2xl mx-auto leading-relaxed">{{ $pageSubtitle }}</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">

        {{-- Form --}}
        <div class="lg:col-span-3" data-aos="fade-up">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route($isAr ? 'ar.contact.store' : 'contact.store') }}" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            {{ __('site.contact.name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-shadow @error('name') border-red-400 @enderror">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            {{ __('site.contact.phone') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-shadow @error('phone') border-red-400 @enderror">
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">{{ __('site.contact.email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-shadow @error('email') border-red-400 @enderror">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        {{ __('site.contact.message') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea name="message" rows="5" required
                              class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-shadow @error('message') border-red-400 @enderror">{{ old('message') }}</textarea>
                    @error('message')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-8 py-3.5 bg-green-700 hover:bg-green-800 text-white font-bold rounded-xl transition-colors shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    {{ __('site.contact.send') }}
                </button>
            </form>
        </div>

        {{-- Info --}}
        <div class="lg:col-span-2 space-y-5" data-aos="fade-up" data-aos-delay="150">
            <div class="bg-green-50 rounded-2xl p-6 border border-green-100">
                <h3 class="font-bold text-slate-900 mb-5 text-base">{{ __('site.contact.info_title') }}</h3>
                <ul class="space-y-4 text-sm text-slate-600">
                    <li class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-green-100 text-green-700 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="leading-relaxed mt-1.5">{{ $contactAddress }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-green-100 text-green-700 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <a href="tel:{{ $contactHotline }}" class="font-semibold text-green-700 hover:text-green-800 transition-colors">{{ $contactHotline }}</a>
                    </li>
                    @if($contactHours)
                    <li class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-green-100 text-green-700 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span>{{ $contactHours }}</span>
                    </li>
                    @endif
                    @if($contactEmail)
                    <li class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-green-100 text-green-700 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <a href="mailto:{{ $contactEmail }}" class="text-green-700 hover:text-green-800 transition-colors">{{ $contactEmail }}</a>
                    </li>
                    @endif
                </ul>
            </div>

            <div class="bg-green-700 rounded-2xl p-6 text-white">
                <p class="text-sm text-green-200 mb-2">{{ __('site.booking.hint') }}</p>
                <a href="tel:{{ $contactHotline }}" class="text-2xl font-extrabold block hover:text-green-100 transition-colors">
                    📞 {{ $contactHotline }}
                </a>
            </div>
        </div>

    </div>
</div>

@endsection
