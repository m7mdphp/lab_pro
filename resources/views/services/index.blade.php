@extends('layouts.app')
@section('title', __('site.services.title'))
@section('description', __('site.services.subtitle'))

@section('content')
@php
    use App\Models\SiteSetting;
    $servicesHeroImage = SiteSetting::get('image_services_hero');
    $servicesHeroUrl = $servicesHeroImage
        ? (str_starts_with($servicesHeroImage, 'http') ? $servicesHeroImage : asset('storage/' . $servicesHeroImage))
        : 'https://images.unsplash.com/photo-1530026405186-ed1f139313f3?w=1920&q=80&auto=format&fit=crop';
    $isAr = app()->getLocale() === 'ar';
@endphp

{{-- Hero --}}
<section class="relative text-white overflow-hidden" style="min-height: 400px;">
    <div class="page-hero-bg absolute inset-0"
         style="background-image: url('{{ $servicesHeroUrl }}'); background-size: cover; background-position: center 20%;"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-green-950/90 via-green-900/88 to-emerald-900/92"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <div class="page-hero-badge inline-flex items-center gap-2 bg-white/10 border border-white/20 backdrop-blur-sm text-white text-xs font-bold px-4 py-1.5 rounded-full mb-5">
            <span class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0"></span>
            {{ $isAr ? 'خدماتنا الطبية المتكاملة' : 'Our Integrated Medical Services' }}
        </div>
        <h1 class="page-hero-title text-4xl md:text-5xl font-extrabold mb-4 leading-tight">{{ __('site.services.title') }}</h1>
        <p class="page-hero-subtitle text-green-100/85 text-lg max-w-2xl mx-auto leading-relaxed">{{ __('site.services.subtitle') }}</p>
    </div>
</section>

{{-- Service cards --}}
@if($services->isNotEmpty())
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-xs font-bold text-green-600 uppercase tracking-widest mb-3 block">{{ $isAr ? 'ما نقدمه' : 'What We Offer' }}</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">{{ __('site.services.title') }}</h2>
        </div>
        @php
            $colorMap = [
                'green'  => ['bg'=>'bg-green-100',  'text'=>'text-green-700',  'border'=>'hover:border-green-400',  'btn'=>'bg-green-700 hover:bg-green-800',   'bar'=>'from-green-500 to-emerald-400'],
                'blue'   => ['bg'=>'bg-blue-100',   'text'=>'text-blue-700',   'border'=>'hover:border-blue-400',   'btn'=>'bg-blue-700 hover:bg-blue-800',     'bar'=>'from-blue-500 to-cyan-400'],
                'orange' => ['bg'=>'bg-orange-100', 'text'=>'text-orange-700', 'border'=>'hover:border-orange-400', 'btn'=>'bg-orange-700 hover:bg-orange-800', 'bar'=>'from-orange-500 to-amber-400'],
                'purple' => ['bg'=>'bg-purple-100', 'text'=>'text-purple-700', 'border'=>'hover:border-purple-400', 'btn'=>'bg-purple-700 hover:bg-purple-800', 'bar'=>'from-purple-500 to-violet-400'],
            ];
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($services as $i => $svc)
                @php
                    $colors = $colorMap[$svc->color] ?? $colorMap['green'];
                    $route  = $isAr ? 'ar.services.show' : 'services.show';
                @endphp
                <a href="{{ route($route, $svc->slug) }}"
                   class="group flex flex-col bg-white rounded-2xl border border-slate-200 {{ $colors['border'] }} hover:shadow-xl transition-all overflow-hidden hover:-translate-y-0.5"
                   data-aos="fade-up" data-aos-delay="{{ ($i % 2) * 120 }}">
                    <div class="h-1.5 bg-gradient-to-r {{ $colors['bar'] }}"></div>
                    <div class="p-8 flex flex-col flex-1">
                        <div class="flex items-start gap-5 mb-5">
                            <div class="w-14 h-14 rounded-2xl {{ $colors['bg'] }} {{ $colors['text'] }} flex items-center justify-center flex-shrink-0 text-2xl group-hover:scale-110 transition-transform shadow-sm">
                                @switch($svc->icon)
                                    @case('heroicon-o-user') 🧑‍⚕️ @break
                                    @case('heroicon-o-academic-cap') 👨‍🔬 @break
                                    @case('heroicon-o-building-office') 🏢 @break
                                    @case('heroicon-o-building-library') 🏥 @break
                                    @default 🔬
                                @endswitch
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-green-700 transition-colors leading-tight">{{ $svc->name }}</h2>
                                <p class="text-slate-500 text-sm leading-relaxed">{{ $svc->short_description }}</p>
                            </div>
                        </div>
                        @if(count($svc->features) > 0)
                        <ul class="space-y-2 mb-6 flex-1">
                            @foreach(array_slice($svc->features, 0, 4) as $feature)
                                <li class="flex items-start gap-2 text-sm text-slate-600">
                                    <svg class="w-4 h-4 mt-0.5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                        @else
                        <div class="flex-1"></div>
                        @endif
                        <span class="{{ $colors['btn'] }} text-white text-sm font-bold px-5 py-2.5 rounded-xl inline-block self-start transition-colors">
                            {{ __('site.services.learn_more') }} <span class="{{ $isAr ? 'mr-1' : 'ml-1' }}">→</span>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Test categories --}}
@if($categories->isNotEmpty())
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10" data-aos="fade-up">
            <h2 class="text-2xl font-bold text-slate-900">{{ __('site.home.categories_title') }}</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($categories as $i => $cat)
                <a href="{{ route($isAr ? 'ar.tests' : 'tests', ['category' => $cat->slug]) }}"
                   class="group bg-white rounded-xl p-4 text-center border border-slate-200 hover:border-green-400 hover:shadow-md transition-all hover:-translate-y-0.5"
                   data-aos="zoom-in" data-aos-delay="{{ min($i * 40, 200) }}">
                    <div class="w-9 h-9 rounded-lg bg-green-50 text-green-700 flex items-center justify-center mx-auto mb-2 group-hover:bg-green-700 group-hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-slate-700 group-hover:text-green-700 transition-colors leading-tight block">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="relative py-20 overflow-hidden text-white text-center"
         style="background-image: url('https://images.unsplash.com/photo-1631549916768-4119b2e5f926?w=1600&q=80&auto=format&fit=crop'); background-size: cover; background-position: center;">
    <div class="absolute inset-0 bg-gradient-to-r from-green-900/93 to-emerald-800/90"></div>
    <div class="relative max-w-xl mx-auto px-4">
        <h2 class="text-3xl font-extrabold mb-4" data-aos="fade-up">{{ __('site.home.cta_title') }}</h2>
        <p class="text-green-100/80 mb-8 text-lg" data-aos="fade-up" data-aos-delay="100">{{ __('site.home.cta_subtitle') }}</p>
        <a href="{{ route($isAr ? 'ar.booking' : 'booking') }}"
           class="inline-flex items-center gap-2 px-8 py-4 bg-white text-green-800 font-extrabold rounded-2xl hover:bg-green-50 transition-all shadow-xl hover:-translate-y-0.5"
           data-aos="fade-up" data-aos-delay="200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            {{ __('site.home.cta_button') }}
        </a>
    </div>
</section>

@endsection
