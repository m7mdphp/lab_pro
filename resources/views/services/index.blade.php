@extends('layouts.app')

@section('title', __('site.services.title'))
@section('description', __('site.services.subtitle'))

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-blue-900 to-cyan-800 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">{{ __('site.services.title') }}</h1>
        <p class="text-blue-100 text-lg max-w-2xl mx-auto">{{ __('site.services.subtitle') }}</p>
    </div>
</section>

{{-- Service cards --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach([
                ['key' => 'home_collection', 'icon' => '🏠', 'color' => 'blue'],
                ['key' => 'walk_in',         'icon' => '🏥', 'color' => 'cyan'],
                ['key' => 'corporate',       'icon' => '🏢', 'color' => 'emerald'],
                ['key' => 'results_online',  'icon' => '📱', 'color' => 'violet'],
            ] as $svc)
                <div class="flex gap-5 p-6 bg-slate-50 rounded-2xl border border-slate-200 hover:border-blue-300 hover:shadow-md transition-all">
                    <div class="w-14 h-14 rounded-xl bg-{{ $svc['color'] }}-100 text-{{ $svc['color'] }}-700 text-2xl flex items-center justify-center flex-shrink-0">
                        {{ $svc['icon'] }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg mb-2">{{ __('site.services.' . $svc['key'] . '.title') }}</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">{{ __('site.services.' . $svc['key'] . '.desc') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

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
                   class="group bg-white rounded-xl p-4 text-center border border-slate-200 hover:border-blue-400 hover:shadow-md transition-all">
                    <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center mx-auto mb-2 group-hover:bg-blue-700 group-hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-slate-700 group-hover:text-blue-700 transition-colors">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="py-16 bg-blue-700 text-white text-center">
    <div class="max-w-xl mx-auto px-4">
        <h2 class="text-2xl font-bold mb-4">{{ __('site.home.cta_title') }}</h2>
        <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.booking' : 'booking') }}"
           class="inline-block px-8 py-3 bg-white text-blue-800 font-bold rounded-xl hover:bg-blue-50 transition-colors">
            {{ __('site.home.cta_button') }}
        </a>
    </div>
</section>

@endsection
