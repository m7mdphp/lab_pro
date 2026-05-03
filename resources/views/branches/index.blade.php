@extends('layouts.app')
@php
    $__l          = app()->getLocale() === 'ar' ? 'ar' : 'en';
    $pageTitle    = \App\Models\SiteSetting::get("text_branches_title_{$__l}") ?: __('site.branches.title');
    $pageSubtitle = \App\Models\SiteSetting::get("text_branches_subtitle_{$__l}") ?: __('site.branches.subtitle');
@endphp
@section('title', $pageTitle)
@section('description', $pageSubtitle)

@section('content')
@php
    use App\Models\SiteSetting;
    $branchesHeroImage = SiteSetting::get('image_branches_hero');
    $branchesHeroUrl = $branchesHeroImage
        ? (str_starts_with($branchesHeroImage, 'http') ? $branchesHeroImage : asset('storage/' . $branchesHeroImage))
        : 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=1920&q=80&auto=format&fit=crop';
    $isAr = app()->getLocale() === 'ar';
@endphp

{{-- Hero --}}
<section class="relative text-white overflow-hidden" style="min-height: 380px;">
    <div class="page-hero-bg absolute inset-0"
         style="background-image: url('{{ $branchesHeroUrl }}'); background-size: cover; background-position: center;"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-green-950/90 via-green-900/88 to-emerald-900/92"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <div class="page-hero-badge inline-flex items-center gap-2 bg-white/10 border border-white/20 backdrop-blur-sm text-white text-xs font-bold px-4 py-1.5 rounded-full mb-5">
            <span class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0"></span>
            {{ $isAr ? 'أقرب فرع إليك' : 'Find Your Nearest Branch' }}
        </div>
        <h1 class="page-hero-title text-4xl md:text-5xl font-extrabold mb-4 leading-tight">{{ $pageTitle }}</h1>
        <p class="page-hero-subtitle text-green-100/85 text-lg max-w-2xl mx-auto leading-relaxed">{{ $pageSubtitle }}</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    @if($branches->isEmpty())
        <div class="text-center py-24">
            <div class="text-6xl mb-4">📍</div>
            <p class="text-slate-400 text-lg">{{ __('site.branches.empty') }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($branches as $i => $branch)
                <div class="group bg-white rounded-2xl border border-slate-200 hover:border-green-300 hover:shadow-xl transition-all overflow-hidden hover:-translate-y-0.5"
                     data-aos="fade-up" data-aos-delay="{{ min(($i % 3) * 100, 200) }}">
                    <div class="h-1.5 bg-gradient-to-r from-green-500 to-emerald-400"></div>
                    <div class="p-6">
                        <div class="flex items-start gap-4 mb-5">
                            <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 flex items-center justify-center flex-shrink-0 group-hover:bg-green-700 group-hover:text-white transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base group-hover:text-green-700 transition-colors">{{ $branch->name }}</h3>
                                @if($branch->city)
                                    <p class="text-sm text-slate-500 mt-0.5">{{ $branch->city }}</p>
                                @endif
                            </div>
                        </div>

                        <ul class="space-y-3 text-sm text-slate-600">
                            @if($branch->address)
                                <li class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>{{ $branch->address }}</span>
                                </li>
                            @endif
                            @if($branch->phone)
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <a href="tel:{{ $branch->phone }}" class="font-semibold text-green-700 hover:text-green-800 transition-colors">{{ $branch->phone }}</a>
                                </li>
                            @endif
                            @if($branch->working_hours)
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ $branch->working_hours }}</span>
                                </li>
                            @endif
                        </ul>

                        @if($branch->google_maps_url)
                            <a href="{{ $branch->google_maps_url }}" target="_blank" rel="noopener"
                               class="mt-5 inline-flex items-center gap-1.5 text-xs font-bold text-green-700 hover:text-green-800 transition-colors border border-green-200 hover:border-green-400 px-3 py-1.5 rounded-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                {{ __('site.branches.map') }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- CTA --}}
<section class="relative py-16 overflow-hidden text-white text-center"
         style="background-image: url('https://images.unsplash.com/photo-1631549916768-4119b2e5f926?w=1600&q=80&auto=format&fit=crop'); background-size: cover; background-position: center;">
    <div class="absolute inset-0 bg-gradient-to-r from-green-900/93 to-emerald-800/90"></div>
    <div class="relative max-w-xl mx-auto px-4">
        <h2 class="text-2xl font-extrabold mb-4" data-aos="fade-up">{{ __('site.home.cta_title') }}</h2>
        <a href="{{ route($isAr ? 'ar.booking' : 'booking') }}"
           class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-green-800 font-extrabold rounded-xl hover:bg-green-50 transition-all shadow-xl"
           data-aos="fade-up" data-aos-delay="100">
            {{ __('site.home.cta_button') }}
        </a>
    </div>
</section>

@endsection
