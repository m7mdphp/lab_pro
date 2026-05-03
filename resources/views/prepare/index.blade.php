@extends('layouts.app')

@section('title', __('site.prepare.title'))
@section('description', __('site.prepare.subtitle'))

@section('content')
@php
    use App\Models\SiteSetting;
    $prepareHeroImage = SiteSetting::get('image_prepare_hero');
    $prepareHeroUrl = $prepareHeroImage
        ? (str_starts_with($prepareHeroImage, 'http') ? $prepareHeroImage : asset('storage/' . $prepareHeroImage))
        : 'https://images.unsplash.com/photo-1631549916768-4119b2e5f926?w=1920&q=80&auto=format&fit=crop';
@endphp

{{-- Hero --}}
<section class="relative text-white overflow-hidden" style="min-height: 360px;">
    <div class="absolute inset-0"
         style="background-image: url('{{ $prepareHeroUrl }}'); background-size: cover; background-position: center;">
    </div>
    <div class="absolute inset-0 bg-gradient-to-br from-green-950/92 to-emerald-800/85"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">{{ __('site.prepare.title') }}</h1>
        <p class="text-green-100 text-lg max-w-2xl mx-auto">{{ __('site.prepare.subtitle') }}</p>
    </div>
</section>

{{-- Quick nav anchors --}}
<section class="bg-white border-b border-slate-200 sticky top-0 z-10 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex gap-6 overflow-x-auto py-3 scrollbar-hide text-sm font-semibold">
            @foreach([
                ['id' => 'fasting',     'label' => __('site.prepare.fasting_title')],
                ['id' => 'blood',       'label' => __('site.prepare.blood_title')],
                ['id' => 'urine',       'label' => __('site.prepare.urine_title')],
                ['id' => 'stool',       'label' => __('site.prepare.stool_title')],
                ['id' => 'semen',       'label' => __('site.prepare.semen_title')],
                ['id' => 'appointments','label' => __('site.prepare.appointments_title')],
            ] as $anchor)
                <a href="#{{ $anchor['id'] }}"
                   class="whitespace-nowrap text-slate-600 hover:text-green-700 transition-colors border-b-2 border-transparent hover:border-green-700 pb-1">
                    {{ $anchor['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-16">

    {{-- Fasting --}}
    <section id="fasting">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl">⏳</div>
            <h2 class="text-2xl font-bold text-slate-900">{{ __('site.prepare.fasting_title') }}</h2>
        </div>
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-6">
            <p class="text-amber-800 font-semibold text-sm">⚠️ {{ __('site.prepare.fasting_notice') }}</p>
        </div>
        <div class="prose prose-slate max-w-none">
            <p class="text-slate-600 leading-relaxed">{{ __('site.prepare.fasting_desc') }}</p>
            <h3 class="text-lg font-bold text-slate-800 mt-6 mb-3">{{ __('site.prepare.fasting_tests') }}</h3>
            <ul class="space-y-2">
                @foreach((array) __('site.prepare.fasting_list') as $item)
                    <li class="flex items-start gap-2 text-slate-600">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        {{ $item }}
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <hr class="border-slate-200">

    {{-- Blood tests --}}
    <section id="blood">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-red-100 text-red-700 flex items-center justify-center text-xl">🩸</div>
            <h2 class="text-2xl font-bold text-slate-900">{{ __('site.prepare.blood_title') }}</h2>
        </div>
        <p class="text-slate-600 leading-relaxed mb-4">{{ __('site.prepare.blood_desc') }}</p>
        <ul class="space-y-3">
            @foreach((array) __('site.prepare.blood_tips') as $tip)
                <li class="flex items-start gap-3 p-4 bg-red-50 rounded-xl border border-red-100">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-slate-700 text-sm">{{ $tip }}</span>
                </li>
            @endforeach
        </ul>
    </section>

    <hr class="border-slate-200">

    {{-- Urine --}}
    <section id="urine">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-yellow-100 text-yellow-700 flex items-center justify-center text-xl">🧪</div>
            <h2 class="text-2xl font-bold text-slate-900">{{ __('site.prepare.urine_title') }}</h2>
        </div>
        <p class="text-slate-600 leading-relaxed mb-4">{{ __('site.prepare.urine_desc') }}</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach((array) __('site.prepare.urine_steps') as $i => $step)
                <div class="flex items-start gap-3 p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                    <span class="w-6 h-6 rounded-full bg-yellow-500 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                    <span class="text-slate-700 text-sm">{{ $step }}</span>
                </div>
            @endforeach
        </div>
    </section>

    <hr class="border-slate-200">

    {{-- Stool --}}
    <section id="stool">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-green-100 text-green-700 flex items-center justify-center text-xl">🧫</div>
            <h2 class="text-2xl font-bold text-slate-900">{{ __('site.prepare.stool_title') }}</h2>
        </div>
        <p class="text-slate-600 leading-relaxed mb-4">{{ __('site.prepare.stool_desc') }}</p>
        <ul class="space-y-3">
            @foreach((array) __('site.prepare.stool_tips') as $tip)
                <li class="flex items-start gap-3 p-4 bg-green-50 rounded-xl border border-green-100">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-slate-700 text-sm">{{ $tip }}</span>
                </li>
            @endforeach
        </ul>
    </section>

    <hr class="border-slate-200">

    {{-- Semen Analysis --}}
    <section id="semen">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl">🔬</div>
            <h2 class="text-2xl font-bold text-slate-900">{{ __('site.prepare.semen_title') }}</h2>
        </div>
        <p class="text-slate-600 leading-relaxed mb-4">{{ __('site.prepare.semen_desc') }}</p>
        <ul class="space-y-3">
            @foreach((array) __('site.prepare.semen_tips') as $tip)
                <li class="flex items-start gap-3 p-4 bg-purple-50 rounded-xl border border-purple-100">
                    <svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-slate-700 text-sm">{{ $tip }}</span>
                </li>
            @endforeach
        </ul>
    </section>

    <hr class="border-slate-200">

    {{-- Special appointments --}}
    <section id="appointments">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl">📅</div>
            <h2 class="text-2xl font-bold text-slate-900">{{ __('site.prepare.appointments_title') }}</h2>
        </div>
        <p class="text-slate-600 leading-relaxed mb-4">{{ __('site.prepare.appointments_desc') }}</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach((array) __('site.prepare.appointments_list') as $item)
                <div class="flex items-start gap-3 p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-slate-700 text-sm">{{ $item }}</span>
                </div>
            @endforeach
        </div>
    </section>

</div>

{{-- CTA --}}
<section class="py-16 bg-green-700 text-white text-center">
    <div class="max-w-xl mx-auto px-4">
        <h2 class="text-2xl font-bold mb-2">{{ __('site.prepare.cta_title') }}</h2>
        <p class="text-green-100 mb-6">{{ __('site.prepare.cta_desc') }}</p>
        <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.booking' : 'booking') }}"
           class="inline-block px-8 py-3 bg-white text-green-800 font-bold rounded-xl hover:bg-green-50 transition-colors">
            {{ __('site.home.cta_button') }}
        </a>
    </div>
</section>

@endsection
