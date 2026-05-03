@extends('layouts.app')
@php
    $__l          = app()->getLocale() === 'ar' ? 'ar' : 'en';
    $pageTitle    = \App\Models\SiteSetting::get("text_tests_title_{$__l}") ?: __('site.tests.title');
    $pageSubtitle = \App\Models\SiteSetting::get("text_tests_subtitle_{$__l}") ?: __('site.tests.subtitle');
@endphp
@section('title', $pageTitle)
@section('description', $pageSubtitle)

@section('content')
@php
    use App\Models\SiteSetting;
    $testsHeroImage = SiteSetting::get('image_tests_hero');
    $testsHeroUrl = $testsHeroImage
        ? (str_starts_with($testsHeroImage, 'http') ? $testsHeroImage : asset('storage/' . $testsHeroImage))
        : 'https://images.unsplash.com/photo-1579154204601-01588f351e67?w=1920&q=80&auto=format&fit=crop';
    $isAr = app()->getLocale() === 'ar';
@endphp

{{-- Hero --}}
<section class="relative text-white overflow-hidden" style="min-height: 280px;">
    <div class="page-hero-bg absolute inset-0"
         style="background-image: url('{{ $testsHeroUrl }}'); background-size: cover; background-position: center;"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-green-950/92 to-green-900/90"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="page-hero-badge inline-flex items-center gap-2 bg-white/10 border border-white/20 backdrop-blur-sm text-white text-xs font-bold px-4 py-1.5 rounded-full mb-4">
            <span class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0"></span>
            {{ $isAr ? 'أكثر من 300 تحليل طبي' : '300+ Medical Tests Available' }}
        </div>
        <h1 class="page-hero-title text-4xl font-extrabold mb-3 leading-tight">{{ $pageTitle }}</h1>
        <p class="page-hero-subtitle text-green-100/85 mb-6 max-w-xl leading-relaxed">{{ $pageSubtitle }}</p>
        {{-- Search --}}
        <form method="GET" class="page-hero-extra flex gap-3 max-w-xl">
            <div class="relative flex-1">
                <svg class="absolute {{ $isAr ? 'right-3' : 'left-3' }} top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="{{ __('site.tests.search') }}"
                       class="w-full {{ $isAr ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-2.5 rounded-xl text-slate-800 text-sm border-0 focus:ring-2 focus:ring-white/50 bg-white/95">
            </div>
            <button type="submit"
                    class="px-5 py-2.5 bg-white text-green-800 font-bold rounded-xl hover:bg-green-50 transition-colors text-sm shadow-lg">
                {{ $isAr ? 'بحث' : 'Search' }}
            </button>
        </form>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- Sidebar --}}
        @if($categories->isNotEmpty())
        <aside class="lg:w-56 flex-shrink-0">
            <div class="bg-white rounded-2xl border border-slate-200 p-4 sticky top-24">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">{{ __('site.tests.filter') }}</h3>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route($isAr ? 'ar.tests' : 'tests') }}"
                           class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors
                                  {{ !request('category') ? 'bg-green-700 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                            {{ __('site.tests.all') }}
                        </a>
                    </li>
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route($isAr ? 'ar.tests' : 'tests', ['category' => $cat->slug]) }}"
                               class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors
                                      {{ request('category') === $cat->slug ? 'bg-green-700 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                                {{ $cat->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>
        @endif

        {{-- Grid --}}
        <div class="flex-1">
            @if($packages->isEmpty())
                <div class="text-center py-24">
                    <div class="text-6xl mb-4">🔬</div>
                    <p class="text-slate-400 text-lg">{{ __('site.tests.empty') }}</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($packages as $i => $pkg)
                        <div class="group bg-white rounded-2xl border border-slate-200 hover:border-green-300 hover:shadow-lg transition-all overflow-hidden flex flex-col"
                             data-aos="fade-up" data-aos-delay="{{ min(($i % 3) * 80, 160) }}">
                            <div class="h-1 bg-gradient-to-r from-green-500 to-emerald-400"></div>
                            <div class="p-5 flex flex-col flex-1">
                                <h3 class="font-bold text-slate-900 mb-2 group-hover:text-green-700 transition-colors leading-tight">{{ $pkg->name }}</h3>
                                @if($pkg->short_description)
                                    <p class="text-sm text-slate-500 flex-1 mb-3 leading-relaxed">{{ Str::limit($pkg->short_description, 100) }}</p>
                                @else
                                    <div class="flex-1"></div>
                                @endif
                                @if($pkg->categories->isNotEmpty())
                                    <div class="flex flex-wrap gap-1 mb-3">
                                        @foreach($pkg->categories->take(2) as $cat)
                                            <span class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded-full font-medium">{{ $cat->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                                    @if($pkg->price_egp)
                                        <div>
                                            <span class="font-extrabold text-green-700 text-lg">{{ number_format($pkg->price_egp, 0) }}</span>
                                            <span class="text-xs text-slate-400 {{ $isAr ? 'mr-1' : 'ml-1' }}">{{ __('site.common.egp') }}</span>
                                        </div>
                                    @else
                                        <div></div>
                                    @endif
                                    <a href="{{ route($isAr ? 'ar.booking' : 'booking') }}"
                                       class="px-4 py-1.5 bg-green-700 hover:bg-green-800 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                                        {{ __('site.tests.book') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">{{ $packages->links() }}</div>
            @endif
        </div>

    </div>
</div>

@endsection
