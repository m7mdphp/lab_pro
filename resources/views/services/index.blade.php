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
@endphp

{{-- Hero --}}
<section class="relative text-white overflow-hidden" style="min-height: 380px;">
    <div class="absolute inset-0"
         style="background-image: url('{{ $servicesHeroUrl }}'); background-size: cover; background-position: center 20%;">
    </div>
    <div class="absolute inset-0 bg-gradient-to-br from-green-950/92 to-emerald-800/85"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <span class="inline-block bg-white/15 border border-white/25 text-white text-xs font-bold px-4 py-1.5 rounded-full mb-5">خدماتنا الطبية المتكاملة</span>
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">{{ __('site.services.title') }}</h1>
        <p class="text-green-100 text-lg max-w-2xl mx-auto leading-relaxed">{{ __('site.services.subtitle') }}</p>
    </div>
</section>

{{-- Service type cards --}}
@if($services->isNotEmpty())
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @php
                $colorMap = [
                    'green'  => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'border' => 'hover:border-green-400',  'btn' => 'bg-green-700 hover:bg-green-800'],
                    'blue'   => ['bg' => 'bg-blue-100',   'text' => 'text-blue-700',   'border' => 'hover:border-blue-400',   'btn' => 'bg-blue-700 hover:bg-blue-800'],
                    'orange' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'border' => 'hover:border-orange-400', 'btn' => 'bg-orange-700 hover:bg-orange-800'],
                    'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'border' => 'hover:border-purple-400', 'btn' => 'bg-purple-700 hover:bg-purple-800'],
                ];
            @endphp
            @foreach($services as $svc)
                @php
                    $colors = $colorMap[$svc->color] ?? $colorMap['green'];
                    $locale = app()->getLocale();
                    $route  = $locale === 'ar' ? 'ar.services.show' : 'services.show';
                @endphp
                <a href="{{ route($route, $svc->slug) }}"
                   class="group flex flex-col p-8 bg-slate-50 rounded-2xl border border-slate-200 {{ $colors['border'] }} hover:shadow-lg transition-all">
                    <div class="flex items-start gap-5 mb-5">
                        <div class="w-16 h-16 rounded-2xl {{ $colors['bg'] }} {{ $colors['text'] }} flex items-center justify-center flex-shrink-0 text-3xl group-hover:scale-105 transition-transform">
                            @switch($svc->icon)
                                @case('heroicon-o-user') 🧑‍⚕️ @break
                                @case('heroicon-o-academic-cap') 👨‍🔬 @break
                                @case('heroicon-o-building-office') 🏢 @break
                                @case('heroicon-o-building-library') 🏥 @break
                                @default 🔬
                            @endswitch
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-green-700 transition-colors">{{ $svc->name }}</h2>
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
                    @endif

                    <span class="{{ $colors['btn'] }} text-white text-sm font-semibold px-5 py-2.5 rounded-xl inline-block text-center transition-colors">
                        {{ __('site.services.learn_more') }}
                        <span class="{{ app()->getLocale() === 'ar' ? 'mr-1' : 'ml-1' }}">→</span>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Test categories --}}
@if($categories->isNotEmpty())
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-900">{{ __('site.home.categories_title') }}</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($categories as $cat)
                <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.tests' : 'tests', ['category' => $cat->slug]) }}"
                   class="group bg-white rounded-xl p-4 text-center border border-slate-200 hover:border-green-400 hover:shadow-md transition-all">
                    <div class="w-9 h-9 rounded-lg bg-green-50 text-green-700 flex items-center justify-center mx-auto mb-2 group-hover:bg-green-700 group-hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-slate-700 group-hover:text-green-700 transition-colors">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="py-16 bg-green-700 text-white text-center">
    <div class="max-w-xl mx-auto px-4">
        <h2 class="text-2xl font-bold mb-4">{{ __('site.home.cta_title') }}</h2>
        <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.booking' : 'booking') }}"
           class="inline-block px-8 py-3 bg-white text-green-800 font-bold rounded-xl hover:bg-green-50 transition-colors">
            {{ __('site.home.cta_button') }}
        </a>
    </div>
</section>

@endsection
