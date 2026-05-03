@extends('layouts.app')
@php
    $__l          = app()->getLocale() === 'ar' ? 'ar' : 'en';
    $pageTitle    = \App\Models\SiteSetting::get("text_packages_title_{$__l}") ?: __('site.packages.title');
    $pageSubtitle = \App\Models\SiteSetting::get("text_packages_subtitle_{$__l}") ?: __('site.packages.subtitle');
@endphp
@section('title', $pageTitle)
@section('description', $pageSubtitle)

@section('content')
@php
    use App\Models\SiteSetting;
    $packagesHeroImage = SiteSetting::get('image_packages_hero');
    $packagesHeroUrl = $packagesHeroImage
        ? (str_starts_with($packagesHeroImage, 'http') ? $packagesHeroImage : asset('storage/' . $packagesHeroImage))
        : 'https://images.unsplash.com/photo-1576671081837-49000212a370?w=1920&q=80&auto=format&fit=crop';
    $isAr = app()->getLocale() === 'ar';
@endphp

{{-- Hero --}}
<section class="relative text-white overflow-hidden" style="min-height: 380px;">
    <div class="page-hero-bg absolute inset-0"
         style="background-image: url('{{ $packagesHeroUrl }}'); background-size: cover; background-position: center;"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-green-950/90 via-green-900/88 to-emerald-900/92"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <div class="page-hero-badge inline-flex items-center gap-2 bg-white/10 border border-white/20 backdrop-blur-sm text-white text-xs font-bold px-4 py-1.5 rounded-full mb-5">
            <span class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0"></span>
            {{ $isAr ? 'باقات شاملة لكل احتياجاتك' : 'Comprehensive Health Packages' }}
        </div>
        <h1 class="page-hero-title text-4xl md:text-5xl font-extrabold mb-4 leading-tight">{{ $pageTitle }}</h1>
        <p class="page-hero-subtitle text-green-100/85 text-lg max-w-2xl mx-auto leading-relaxed">{{ $pageSubtitle }}</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Category filter --}}
    @if($categories->isNotEmpty())
        <div class="flex flex-wrap gap-2 mb-10" data-aos="fade-up">
            <a href="{{ route($isAr ? 'ar.packages' : 'packages') }}"
               class="px-4 py-1.5 rounded-full text-sm font-semibold transition-all
                      {{ !request('category') ? 'bg-green-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-800' }}">
                {{ __('site.tests.all') }}
            </a>
            @foreach($categories as $cat)
                <a href="{{ route($isAr ? 'ar.packages' : 'packages', ['category' => $cat->slug]) }}"
                   class="px-4 py-1.5 rounded-full text-sm font-semibold transition-all
                          {{ request('category') === $cat->slug ? 'bg-green-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-800' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    @endif

    @if($packages->isEmpty())
        <div class="text-center py-24">
            <div class="text-6xl mb-4">📦</div>
            <p class="text-slate-400 text-lg">{{ __('site.packages.empty') }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($packages as $i => $pkg)
                <div class="group bg-white rounded-2xl border border-slate-200 hover:border-green-300 hover:shadow-xl transition-all overflow-hidden flex flex-col hover:-translate-y-0.5"
                     data-aos="fade-up" data-aos-delay="{{ min(($i % 3) * 100, 200) }}">
                    <div class="h-1.5 bg-gradient-to-r from-green-500 to-emerald-400"></div>
                    <div class="p-6 flex-1 flex flex-col">
                        @if($pkg->is_featured)
                            <span class="text-xs font-bold bg-amber-100 text-amber-700 px-2.5 py-0.5 rounded-full self-start mb-3">⭐ {{ $isAr ? 'مميز' : 'Featured' }}</span>
                        @endif
                        <h3 class="font-extrabold text-slate-900 text-lg mb-2 group-hover:text-green-700 transition-colors leading-tight">{{ $pkg->name }}</h3>
                        @if($pkg->short_description)
                            <p class="text-sm text-slate-500 flex-1 leading-relaxed mb-4">{{ Str::limit($pkg->short_description, 120) }}</p>
                        @else
                            <div class="flex-1"></div>
                        @endif
                        @if($pkg->categories->isNotEmpty())
                            <div class="flex flex-wrap gap-1 mb-4">
                                @foreach($pkg->categories->take(3) as $cat)
                                    <span class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded-full font-medium border border-green-100">{{ $cat->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            @if($pkg->price_egp)
                                <div class="font-extrabold text-green-700 text-xl leading-none">
                                    {{ number_format($pkg->price_egp, 0) }}
                                    <span class="text-xs font-normal text-slate-500 {{ $isAr ? 'mr-1' : 'ml-1' }}">{{ __('site.common.egp') }}</span>
                                </div>
                                @if($pkg->original_price_egp && $pkg->original_price_egp > $pkg->price_egp)
                                    <div class="text-xs text-slate-400 line-through mt-0.5">{{ number_format($pkg->original_price_egp, 0) }}</div>
                                @endif
                            @endif
                        </div>
                        <a href="{{ route($isAr ? 'ar.booking' : 'booking') }}"
                           class="px-4 py-2 bg-green-700 hover:bg-green-800 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                            {{ __('site.packages.book') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $packages->links() }}</div>
    @endif
</div>

@endsection
