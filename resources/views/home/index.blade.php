@extends('layouts.app')

@section('title', __('site.nav.home'))

@section('content')

{{-- ── Hero ─────────────────────────────────────────────────────────────── --}}
<section class="relative bg-gradient-to-br from-green-900 via-green-800 to-emerald-700 text-white overflow-hidden">
    {{-- Background decoration --}}
    <div class="absolute inset-0 opacity-10">
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/20 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-emerald-300/20 blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
        <div class="max-w-2xl">
            <span class="inline-block bg-white/10 border border-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-6 backdrop-blur-sm">
                🏅 ISO 15189 Certified
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                {!! __('site.home.hero_title') !!}
            </h1>
            <p class="text-lg md:text-xl text-green-100 leading-relaxed mb-10">
                {{ __('site.home.hero_subtitle') }}
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.booking' : 'booking') }}"
                   class="inline-flex items-center gap-2 px-7 py-3.5 bg-white text-green-800 font-bold rounded-xl hover:bg-green-50 transition-colors shadow-lg">
                    {{ __('site.home.hero_cta') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="{{ app()->getLocale() === 'ar' ? 'M19 12H5m0 0l7-7m-7 7l7 7' : 'M5 12h14m0 0l-7-7m7 7l-7 7' }}"/>
                    </svg>
                </a>
                <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.tests' : 'tests') }}"
                   class="inline-flex items-center gap-2 px-7 py-3.5 bg-white/10 border border-white/30 text-white font-semibold rounded-xl hover:bg-white/20 transition-colors backdrop-blur-sm">
                    {{ __('site.home.hero_cta_alt') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Stats bar --}}
    <div class="relative border-t border-white/10 bg-green-900/40 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                @foreach([
                    ['50+', __('site.home.categories_title')],
                    ['300+', __('site.nav.tests')],
                    ['1M+', 'Tests Performed'],
                    ['24h', 'Results'],
                ] as [$val, $label])
                    <div>
                        <div class="text-2xl md:text-3xl font-extrabold text-white">{{ $val }}</div>
                        <div class="text-xs text-green-200 mt-1 font-medium">{{ $label }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ── Test Categories ──────────────────────────────────────────────────── --}}
@if($categories->isNotEmpty())
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-900">{{ __('site.home.categories_title') }}</h2>
            <p class="mt-2 text-slate-500">{{ __('site.home.categories_subtitle') }}</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($categories as $cat)
                <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.tests' : 'tests', ['category' => $cat->slug]) }}"
                   class="group bg-white rounded-xl p-5 text-center border border-slate-200 hover:border-green-400 hover:shadow-md transition-all">
                    <div class="w-10 h-10 rounded-lg bg-green-50 text-green-700 flex items-center justify-center mx-auto mb-3 group-hover:bg-green-700 group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700 group-hover:text-green-700 transition-colors">
                        {{ $cat->name }}
                    </span>
                </a>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.tests' : 'tests') }}"
               class="text-sm font-semibold text-green-700 hover:text-green-800 inline-flex items-center gap-1">
                {{ __('site.common.view_all') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="{{ app()->getLocale() === 'ar' ? 'M19 12H5' : 'M5 12h14m-7-7l7 7-7 7' }}"/>
                </svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ── Why El-Sheikha Lab ────────────────────────────────────────────────── --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-900">{{ __('site.home.why_title') }}</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach(__('site.home.why') as $item)
                <div class="bg-slate-50 rounded-2xl p-6 hover:shadow-md transition-shadow">
                    <div class="text-3xl mb-4">{{ $item['icon'] }}</div>
                    <h3 class="font-bold text-slate-900 mb-2">{{ $item['title'] }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $item['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Featured Packages ────────────────────────────────────────────────── --}}
@if($featuredPackages->isNotEmpty())
<section class="py-20 bg-green-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-900">{{ __('site.home.packages_title') }}</h2>
            <p class="mt-2 text-slate-500">{{ __('site.home.packages_subtitle') }}</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredPackages as $pkg)
                <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:shadow-lg transition-shadow flex flex-col">
                    <h3 class="font-bold text-slate-900 mb-2 text-lg">{{ $pkg->name }}</h3>
                    @if($pkg->short_description)
                        <p class="text-sm text-slate-500 mb-4 flex-1">{{ $pkg->short_description }}</p>
                    @else
                        <div class="flex-1"></div>
                    @endif
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100">
                        @if($pkg->price_egp)
                            <div>
                                <span class="text-xl font-bold text-green-700">{{ number_format($pkg->price_egp, 0) }}</span>
                                <span class="text-sm text-slate-500 ml-1">{{ __('site.common.egp') }}</span>
                                @if($pkg->original_price_egp && $pkg->original_price_egp > $pkg->price_egp)
                                    <div class="text-xs text-slate-400 line-through">{{ number_format($pkg->original_price_egp, 0) }} {{ __('site.common.egp') }}</div>
                                @endif
                            </div>
                        @endif
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.booking' : 'booking') }}"
                           class="px-4 py-2 bg-green-700 hover:bg-green-800 text-white text-sm font-semibold rounded-lg transition-colors">
                            {{ __('site.tests.book') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.packages' : 'packages') }}"
               class="text-sm font-semibold text-green-700 hover:text-green-800 inline-flex items-center gap-1">
                {{ __('site.common.view_all') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ── Branches ─────────────────────────────────────────────────────────── --}}
@if($branches->isNotEmpty())
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-900">{{ __('site.home.branches_title') }}</h2>
            <p class="mt-2 text-slate-500">{{ __('site.home.branches_subtitle') }}</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($branches as $branch)
                <div class="flex items-start gap-4 bg-slate-50 rounded-xl p-5 border border-slate-200 hover:border-green-300 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-green-100 text-green-700 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900 text-sm">{{ $branch->name }}</h3>
                        @if($branch->address)
                            <p class="text-xs text-slate-500 mt-1">{{ $branch->address }}</p>
                        @endif
                        @if($branch->phone)
                            <a href="tel:{{ $branch->phone }}" class="text-xs text-green-600 mt-1 inline-block">{{ $branch->phone }}</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.branches' : 'branches') }}"
               class="text-sm font-semibold text-green-700 hover:text-green-800">
                {{ __('site.common.view_all') }} →
            </a>
        </div>
    </div>
</section>
@endif

{{-- ── Partners ──────────────────────────────────────────────────────────── --}}
@if($partners->isNotEmpty())
<section class="py-16 bg-slate-50 border-y border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <p class="text-sm font-semibold text-slate-400 uppercase tracking-widest mb-2">{{ __('site.home.partners_label') }}</p>
            <h2 class="text-2xl font-bold text-slate-900">{{ __('site.home.partners_title') }}</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($partners as $partner)
                <div class="bg-white rounded-xl border border-slate-200 p-5 text-center hover:border-green-300 hover:shadow-sm transition-all">
                    <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 font-black text-xl flex items-center justify-center mx-auto mb-3">
                        {{ mb_substr($partner->name, 0, 1) }}
                    </div>
                    <p class="font-semibold text-slate-800 text-sm">{{ $partner->name }}</p>
                    @if($partner->specialty)
                        <p class="text-xs text-slate-400 mt-1">{{ $partner->specialty }}</p>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.partners' : 'partners') }}"
               class="text-sm font-semibold text-green-700 hover:text-green-800 inline-flex items-center gap-1">
                {{ __('site.common.view_all') }} →
            </a>
        </div>
    </div>
</section>
@endif

{{-- ── FAQ ──────────────────────────────────────────────────────────────── --}}
@if($faqs->isNotEmpty())
<section class="py-20 bg-slate-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-900">{{ __('site.home.faq_title') }}</h2>
        </div>
        <div x-data="{ open: null }" class="space-y-3">
            @foreach($faqs as $i => $faq)
                @if($faq->question)
                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                    <button @click="open = open === {{ $i }} ? null : {{ $i }}"
                            class="w-full flex items-center justify-between px-6 py-4 text-start font-semibold text-slate-800 hover:bg-slate-50 transition-colors">
                        <span>{{ $faq->question }}</span>
                        <svg :class="open === {{ $i }} ? 'rotate-180' : ''"
                             class="w-5 h-5 text-slate-400 transition-transform flex-shrink-0"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === {{ $i }}"
                         x-transition
                         class="px-6 pb-5 text-sm text-slate-600 leading-relaxed">
                        {{ $faq->answer }}
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── CTA Banner ───────────────────────────────────────────────────────── --}}
<section class="py-20 bg-gradient-to-r from-green-800 to-emerald-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold mb-4">{{ __('site.home.cta_title') }}</h2>
        <p class="text-green-100 mb-8 max-w-xl mx-auto">{{ __('site.home.cta_subtitle') }}</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.booking' : 'booking') }}"
               class="px-8 py-3.5 bg-white text-green-800 font-bold rounded-xl hover:bg-green-50 transition-colors shadow-lg">
                {{ __('site.home.cta_button') }}
            </a>
            <a href="tel:19000"
               class="px-8 py-3.5 bg-white/10 border border-white/30 text-white font-semibold rounded-xl hover:bg-white/20 transition-colors">
                📞 {{ __('site.home.cta_call') }}
            </a>
        </div>
    </div>
</section>

@endsection
