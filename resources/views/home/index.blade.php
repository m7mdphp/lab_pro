@extends('layouts.app')

@section('title', __('site.nav.home'))

@section('content')
@php
    use App\Models\SiteSetting;
    $statTests    = SiteSetting::get('stat_tests_count', '300+');
    $statBranches = SiteSetting::get('branches_count', '4');
    $statDone     = SiteSetting::get('stat_analyses_done', '1M+');
    $statTime     = SiteSetting::get('stat_avg_time', '24 h');
    $hotline      = SiteSetting::get('hotline', '19XXX');
    $totalMilestone = SiteSetting::get('stat_total_analyses_milestone', '+1,000,000');
@endphp

{{-- ── Hero ─────────────────────────────────────────────────────────────── --}}
<section class="relative text-white overflow-hidden" style="min-height: 600px;">
    {{-- Background image --}}
    <div class="absolute inset-0"
         style="background-image: url('https://images.unsplash.com/photo-1579154204601-01588f351e67?w=1920&q=80&auto=format&fit=crop'); background-size: cover; background-position: center;">
    </div>
    {{-- Gradient overlay --}}
    <div class="absolute inset-0 bg-gradient-to-br from-green-950/90 via-green-900/85 to-emerald-800/80"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-28 md:py-36">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-flex items-center gap-2 bg-white/15 border border-white/25 text-white text-xs font-bold px-4 py-1.5 rounded-full mb-6 backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                    🏅 ISO 15189 Certified — معتمد دولياً
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6 tracking-tight">
                    {!! __('site.home.hero_title') !!}
                </h1>
                <p class="text-lg md:text-xl text-green-100 leading-relaxed mb-10 max-w-xl">
                    {{ __('site.home.hero_subtitle') }}
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.booking' : 'booking') }}"
                       class="inline-flex items-center gap-2.5 px-8 py-4 bg-white text-green-800 font-extrabold rounded-2xl hover:bg-green-50 transition-all shadow-xl hover:shadow-2xl hover:-translate-y-0.5 text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ __('site.home.hero_cta') }}
                    </a>
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.tests' : 'tests') }}"
                       class="inline-flex items-center gap-2 px-8 py-4 bg-white/10 border-2 border-white/40 text-white font-bold rounded-2xl hover:bg-white/20 transition-all backdrop-blur-sm text-base">
                        {{ __('site.home.hero_cta_alt') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                {{-- Trust indicators --}}
                <div class="flex flex-wrap items-center gap-5 mt-10 pt-8 border-t border-white/20">
                    <div class="flex items-center gap-2 text-sm text-green-200">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        نتائج دقيقة 100%
                    </div>
                    <div class="flex items-center gap-2 text-sm text-green-200">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        سحب عينات بالمنزل
                    </div>
                    <div class="flex items-center gap-2 text-sm text-green-200">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        نتائج خلال 24 ساعة
                    </div>
                </div>
            </div>

            {{-- Stats panel --}}
            <div class="hidden lg:grid grid-cols-2 gap-4">
                @foreach([
                    ['value' => $statTests,    'label' => 'تحليل وباقة', 'icon' => '🔬'],
                    ['value' => $statBranches, 'label' => 'فروع', 'icon' => '📍'],
                    ['value' => $statDone,     'label' => 'تحليل مُنجز', 'icon' => '✅'],
                    ['value' => $statTime,     'label' => 'متوسط الإنجاز', 'icon' => '⚡'],
                ] as $stat)
                    <div class="bg-white/10 border border-white/20 rounded-2xl p-6 text-center backdrop-blur-sm hover:bg-white/15 transition-colors">
                        <div class="text-3xl mb-2">{{ $stat['icon'] }}</div>
                        <div class="text-3xl font-extrabold text-white mb-1">{{ $stat['value'] }}</div>
                        <div class="text-sm text-green-200 font-medium">{{ $stat['label'] }}</div>
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
            <span class="text-xs font-bold text-green-600 uppercase tracking-widest mb-3 block">التحاليل الطبية</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">{{ __('site.home.categories_title') }}</h2>
            <p class="mt-3 text-slate-500 text-lg max-w-xl mx-auto">{{ __('site.home.categories_subtitle') }}</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($categories as $cat)
                <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.tests' : 'tests', ['category' => $cat->slug]) }}"
                   class="group bg-white rounded-2xl p-5 text-center border border-slate-200 hover:border-green-400 hover:shadow-lg transition-all hover:-translate-y-0.5">
                    <div class="w-12 h-12 rounded-xl bg-green-50 text-green-700 flex items-center justify-center mx-auto mb-3 group-hover:bg-green-700 group-hover:text-white transition-all group-hover:scale-110">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-bold text-slate-700 group-hover:text-green-700 transition-colors leading-tight block">
                        {{ $cat->name }}
                    </span>
                </a>
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.tests' : 'tests') }}"
               class="inline-flex items-center gap-2 text-green-700 font-bold hover:text-green-800 border-2 border-green-200 hover:border-green-400 px-6 py-3 rounded-xl transition-all">
                {{ __('site.common.view_all') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ── Why El-Sheikha Lab — Image split ────────────────────────────────── --}}
<section class="py-0 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 items-stretch">
            {{-- Image side --}}
            <div class="relative h-80 lg:h-auto min-h-96"
                 style="background-image: url('https://images.unsplash.com/photo-1582719471384-894fbb16e074?w=900&q=85&auto=format&fit=crop'); background-size: cover; background-position: center;">
                <div class="absolute inset-0 bg-green-900/30"></div>
                <div class="absolute bottom-6 left-6 right-6">
                    <div class="inline-flex items-center gap-2 bg-white/90 backdrop-blur-sm rounded-xl px-4 py-2 text-sm font-bold text-green-800 shadow-lg">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
                        مختبرات مؤتمتة بالكامل — تعمل 14 ساعة يومياً
                    </div>
                </div>
            </div>
            {{-- Content side --}}
            <div class="px-8 lg:px-16 py-16 flex flex-col justify-center">
                <span class="text-xs font-bold text-green-600 uppercase tracking-widest mb-3 block">لماذا معامل الشيخة؟</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-8">{{ __('site.home.why_title') }}</h2>
                <div class="space-y-5">
                    @foreach(__('site.home.why') as $item)
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0 text-xl border border-green-100">
                                {{ $item['icon'] }}
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 mb-1">{{ $item['title'] }}</h3>
                                <p class="text-sm text-slate-500 leading-relaxed">{{ $item['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.about' : 'about') }}"
                       class="inline-flex items-center gap-2 text-green-700 font-bold hover:text-green-800 transition-colors">
                        اقرأ أكثر عنّا
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Featured Packages ────────────────────────────────────────────────── --}}
@if($featuredPackages->isNotEmpty())
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-xs font-bold text-green-600 uppercase tracking-widest mb-3 block">باقات مميزة</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">{{ __('site.home.packages_title') }}</h2>
            <p class="mt-3 text-slate-500 text-lg">{{ __('site.home.packages_subtitle') }}</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredPackages as $pkg)
                <div class="group bg-white rounded-2xl border border-slate-200 hover:border-green-300 hover:shadow-xl transition-all overflow-hidden flex flex-col">
                    <div class="h-2 bg-gradient-to-r from-green-500 to-emerald-400"></div>
                    <div class="p-6 flex flex-col flex-1">
                        <h3 class="font-extrabold text-slate-900 mb-2 text-lg leading-tight group-hover:text-green-700 transition-colors">{{ $pkg->name }}</h3>
                        @if($pkg->short_description)
                            <p class="text-sm text-slate-500 mb-4 flex-1 leading-relaxed">{{ $pkg->short_description }}</p>
                        @else
                            <div class="flex-1"></div>
                        @endif
                        <div class="flex items-end justify-between mt-4 pt-4 border-t border-slate-100">
                            @if($pkg->price_egp)
                                <div>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-2xl font-extrabold text-green-700">{{ number_format($pkg->price_egp, 0) }}</span>
                                        <span class="text-sm text-slate-500 font-semibold">{{ __('site.common.egp') }}</span>
                                    </div>
                                    @if($pkg->original_price_egp && $pkg->original_price_egp > $pkg->price_egp)
                                        <div class="text-xs text-slate-400 line-through mt-0.5">{{ number_format($pkg->original_price_egp, 0) }} {{ __('site.common.egp') }}</div>
                                    @endif
                                </div>
                            @endif
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.booking' : 'booking') }}"
                               class="px-4 py-2 bg-green-700 hover:bg-green-800 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                                {{ __('site.tests.book') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.packages' : 'packages') }}"
               class="inline-flex items-center gap-2 text-green-700 font-bold hover:text-green-800 border-2 border-green-200 hover:border-green-400 px-6 py-3 rounded-xl transition-all">
                {{ __('site.common.view_all') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ── Home Sample Collection Banner ───────────────────────────────────── --}}
<section class="relative py-20 overflow-hidden" style="background-image: url('https://images.unsplash.com/photo-1559757175-0eb30cd8c063?w=1600&q=80&auto=format&fit=crop'); background-size: cover; background-position: center top;">
    <div class="absolute inset-0 bg-green-950/85"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
            <div class="lg:col-span-2">
                <span class="text-xs font-bold text-green-400 uppercase tracking-widest mb-3 block">خدمة مميزة</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">سحب العينات في المنزل</h2>
                <p class="text-green-100 text-lg leading-relaxed">
                    فريق من الممرضين المتخصصين يأتون إليك في أي وقت — لا حاجة لزيارة المعمل.
                    خدمة متاحة على مدار الأسبوع في جميع المناطق.
                </p>
                <div class="flex flex-wrap gap-5 mt-6">
                    @foreach(['حجز سريع خلال 5 دقائق', 'وصول خلال 60 دقيقة', 'نتائج على واتساب أو إيميل', 'ممرضون معتمدون'] as $point)
                        <div class="flex items-center gap-2 text-sm text-green-200 font-semibold">
                            <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $point }}
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="text-center lg:text-right">
                <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.booking' : 'booking') }}"
                   class="inline-flex items-center gap-2 px-8 py-4 bg-white text-green-800 font-extrabold rounded-2xl hover:bg-green-50 transition-all shadow-xl text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    احجز الآن
                </a>
                <p class="text-green-400 text-sm mt-3">أو اتصل: <a href="tel:{{ $hotline }}" class="font-bold hover:text-white">{{ $hotline }}</a></p>
            </div>
        </div>
    </div>
</section>

{{-- ── Branches ─────────────────────────────────────────────────────────── --}}
@if($branches->isNotEmpty())
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-xs font-bold text-green-600 uppercase tracking-widest mb-3 block">فروعنا</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">{{ __('site.home.branches_title') }}</h2>
            <p class="mt-3 text-slate-500 text-lg">{{ __('site.home.branches_subtitle') }}</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($branches as $branch)
                <div class="group flex items-start gap-4 bg-slate-50 rounded-2xl p-5 border border-slate-200 hover:border-green-300 hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 flex items-center justify-center flex-shrink-0 group-hover:bg-green-700 group-hover:text-white transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-slate-900 text-sm mb-1 group-hover:text-green-700 transition-colors">{{ $branch->name }}</h3>
                        @if($branch->address)
                            <p class="text-xs text-slate-500 leading-relaxed mb-1">{{ $branch->address }}</p>
                        @endif
                        @if($branch->phone)
                            <a href="tel:{{ $branch->phone }}" class="text-xs text-green-600 font-semibold hover:text-green-800 transition-colors">
                                {{ $branch->phone }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.branches' : 'branches') }}"
               class="inline-flex items-center gap-2 text-green-700 font-bold hover:text-green-800 border-2 border-green-200 hover:border-green-400 px-6 py-3 rounded-xl transition-all">
                {{ __('site.common.view_all') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
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
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">{{ __('site.home.partners_label') }}</p>
            <h2 class="text-2xl font-extrabold text-slate-900">{{ __('site.home.partners_title') }}</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($partners as $partner)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 text-center hover:border-green-300 hover:shadow-md transition-all">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-100 to-emerald-100 text-green-800 font-black text-2xl flex items-center justify-center mx-auto mb-3 border border-green-200">
                        {{ mb_substr($partner->name, 0, 1) }}
                    </div>
                    <p class="font-bold text-slate-800 text-sm leading-tight">{{ $partner->name }}</p>
                    @if($partner->specialty)
                        <p class="text-xs text-slate-400 mt-1 leading-tight">{{ $partner->specialty }}</p>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.partners' : 'partners') }}"
               class="text-sm font-bold text-green-700 hover:text-green-800 inline-flex items-center gap-1 transition-colors">
                {{ __('site.common.view_all') }} →
            </a>
        </div>
    </div>
</section>
@endif

{{-- ── FAQ ──────────────────────────────────────────────────────────────── --}}
@if($faqs->isNotEmpty())
<section class="py-20 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-xs font-bold text-green-600 uppercase tracking-widest mb-3 block">الأسئلة الشائعة</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">{{ __('site.home.faq_title') }}</h2>
        </div>
        <div x-data="{ open: null }" class="space-y-3">
            @foreach($faqs as $i => $faq)
                @if($faq->question)
                <div class="border border-slate-200 rounded-2xl overflow-hidden hover:border-green-200 transition-colors">
                    <button @click="open = open === {{ $i }} ? null : {{ $i }}"
                            class="w-full flex items-center justify-between px-6 py-5 text-start font-bold text-slate-800 hover:bg-slate-50 transition-colors">
                        <span class="text-sm sm:text-base leading-snug {{ app()->getLocale() === 'ar' ? 'pr-4' : 'pl-4' }}">{{ $faq->question }}</span>
                        <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center flex-shrink-0 transition-all"
                             :class="open === {{ $i }} ? 'bg-green-700' : 'bg-green-50'">
                            <svg :class="open === {{ $i }} ? 'rotate-180 text-white' : 'text-green-600'"
                                 class="w-4 h-4 transition-all"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </button>
                    <div x-show="open === {{ $i }}"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="px-6 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-4">
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
<section class="relative py-24 overflow-hidden" style="background-image: url('https://images.unsplash.com/photo-1631549916768-4119b2e5f926?w=1600&q=80&auto=format&fit=crop'); background-size: cover; background-position: center;">
    <div class="absolute inset-0 bg-gradient-to-r from-green-900/92 to-emerald-800/88"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block bg-white/15 border border-white/25 text-white text-xs font-bold px-4 py-1.5 rounded-full mb-6">
            🏅 معتمد ISO 15189 — دقة عالية، نتائج موثوقة
        </span>
        <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-5 leading-tight">{{ __('site.home.cta_title') }}</h2>
        <p class="text-green-100 mb-10 max-w-xl mx-auto text-lg leading-relaxed">{{ __('site.home.cta_subtitle') }}</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.booking' : 'booking') }}"
               class="inline-flex items-center gap-2 px-10 py-4 bg-white text-green-800 font-extrabold rounded-2xl hover:bg-green-50 transition-all shadow-xl hover:-translate-y-0.5 text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ __('site.home.cta_button') }}
            </a>
            <a href="tel:{{ $hotline }}"
               class="inline-flex items-center gap-2 px-10 py-4 bg-white/15 border-2 border-white/40 text-white font-bold rounded-2xl hover:bg-white/25 transition-all text-base backdrop-blur-sm">
                📞 {{ __('site.home.cta_call') }}
            </a>
        </div>
    </div>
</section>

@endsection
