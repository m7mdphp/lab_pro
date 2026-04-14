@extends('layouts.app')

@section('title', __('site.about.title'))
@section('description', __('site.about.subtitle'))

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-green-900 to-emerald-700 text-white py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-block bg-white/10 border border-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-5">
                    {{ __('site.about.since') }}
                </span>
                <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-5">{{ __('site.about.title') }}</h1>
                <p class="text-green-100 text-lg leading-relaxed">{{ __('site.about.subtitle') }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                @foreach(__('site.about.stats') as $stat)
                    <div class="bg-white/10 border border-white/20 rounded-2xl p-6 text-center backdrop-blur-sm">
                        <div class="text-3xl font-extrabold text-white mb-1">{{ $stat['value'] }}</div>
                        <div class="text-sm text-green-200">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Mission + Vision --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-green-50 rounded-2xl p-8 border border-green-100">
                <div class="w-12 h-12 rounded-xl bg-green-700 text-white flex items-center justify-center mb-5 text-2xl">🎯</div>
                <h2 class="text-2xl font-bold text-slate-900 mb-4">{{ __('site.about.mission_title') }}</h2>
                <p class="text-slate-600 leading-relaxed">{{ __('site.about.mission') }}</p>
            </div>
            <div class="bg-emerald-50 rounded-2xl p-8 border border-emerald-100">
                <div class="w-12 h-12 rounded-xl bg-emerald-700 text-white flex items-center justify-center mb-5 text-2xl">🔭</div>
                <h2 class="text-2xl font-bold text-slate-900 mb-4">{{ __('site.about.vision_title') }}</h2>
                <p class="text-slate-600 leading-relaxed">{{ __('site.about.vision') }}</p>
            </div>
        </div>
    </div>
</section>

{{-- Values --}}
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-900">{{ __('site.about.values_title') }}</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach(__('site.about.values') as $value)
                <div class="bg-white rounded-2xl p-6 border border-slate-200 text-center hover:shadow-md transition-shadow">
                    <div class="text-4xl mb-4">{{ $value['icon'] }}</div>
                    <h3 class="font-bold text-slate-900 mb-2">{{ $value['title'] }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $value['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Why choose us --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-900">{{ __('site.about.why_title') }}</h2>
            <p class="text-slate-500 mt-2">{{ __('site.about.why_subtitle') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach(__('site.about.why') as $item)
                <div class="flex gap-5 p-6 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 flex items-center justify-center flex-shrink-0 text-xl">
                        {{ $item['icon'] }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 mb-2">{{ $item['title'] }}</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">{{ $item['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Branded service packages (AccuLab-style) --}}
<section class="py-20 bg-green-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-900">{{ __('site.about.branded_title') }}</h2>
            <p class="text-slate-500 mt-2">{{ __('site.about.branded_subtitle') }}</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(__('site.about.branded') as $pkg)
                <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:border-green-400 hover:shadow-md transition-all">
                    <div class="text-3xl mb-4">{{ $pkg['icon'] }}</div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">{{ $pkg['title'] }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $pkg['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Certifications --}}
<section class="py-16 bg-white border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-sm font-semibold text-slate-400 uppercase tracking-widest mb-8">{{ __('site.about.certs_label') }}</p>
        <div class="flex flex-wrap justify-center gap-6 items-center">
            @foreach(__('site.about.certs') as $cert)
                <div class="px-6 py-3 border-2 border-green-200 rounded-xl text-green-800 font-bold text-sm">{{ $cert }}</div>
            @endforeach
        </div>
    </div>
</section>

@endsection
