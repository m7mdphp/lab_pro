@extends('layouts.app')

@section('title', __('site.about.title'))
@section('description', __('site.about.subtitle'))

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-green-900 to-emerald-700 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">{{ __('site.about.title') }}</h1>
        <p class="text-green-100 text-lg max-w-2xl mx-auto">{{ __('site.about.subtitle') }}</p>
    </div>
</section>

{{-- Mission + Vision --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div class="bg-green-50 rounded-2xl p-8">
                <div class="w-12 h-12 rounded-xl bg-green-700 text-white flex items-center justify-center mb-5 text-2xl">🎯</div>
                <h2 class="text-2xl font-bold text-slate-900 mb-4">{{ __('site.about.mission_title') }}</h2>
                <p class="text-slate-600 leading-relaxed">{{ __('site.about.mission') }}</p>
            </div>
            <div class="bg-emerald-50 rounded-2xl p-8">
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

{{-- Certifications --}}
<section class="py-16 bg-white border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-sm font-semibold text-slate-400 uppercase tracking-widest mb-8">Accreditations & Certifications</p>
        <div class="flex flex-wrap justify-center gap-8 items-center">
            @foreach(['ISO 15189', 'ISO 9001', 'CAP Accredited', 'JCI Certified'] as $cert)
                <div class="px-6 py-3 border-2 border-green-200 rounded-xl text-green-800 font-bold text-sm">{{ $cert }}</div>
            @endforeach
        </div>
    </div>
</section>

@endsection
