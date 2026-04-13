@extends('layouts.app')

@section('title', $package->name)

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.tests' : 'tests') }}"
       class="inline-flex items-center gap-2 text-sm text-green-700 hover:text-green-800 mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7-7l-7 7 7 7"/>
        </svg>
        {{ __('site.common.back') }}
    </a>

    <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
        <h1 class="text-3xl font-bold text-slate-900 mb-4">{{ $package->name }}</h1>

        @if($package->categories->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-5">
                @foreach($package->categories as $cat)
                    <span class="text-sm bg-green-50 text-green-700 px-3 py-1 rounded-full font-medium">{{ $cat->name }}</span>
                @endforeach
            </div>
        @endif

        @if($package->description)
            <div class="prose max-w-none text-slate-600 mb-8">
                {!! nl2br(e($package->description)) !!}
            </div>
        @endif

        @if($package->sample_type)
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
                Sample type: <span class="font-medium text-slate-700">{{ $package->sample_type }}</span>
            </div>
        @endif

        <div class="flex items-center justify-between pt-6 border-t border-slate-100">
            <div>
                @if($package->price_egp)
                    <div class="text-3xl font-bold text-green-700">
                        {{ number_format($package->price_egp, 0) }}
                        <span class="text-base font-normal text-slate-500">{{ __('site.common.egp') }}</span>
                    </div>
                    @if($package->original_price_egp && $package->original_price_egp > $package->price_egp)
                        <div class="text-sm text-slate-400 line-through">{{ number_format($package->original_price_egp, 0) }} {{ __('site.common.egp') }}</div>
                    @endif
                @endif
            </div>
            <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.booking' : 'booking') }}"
               class="px-6 py-3 bg-green-700 hover:bg-green-800 text-white font-bold rounded-xl transition-colors">
                {{ __('site.tests.book') }}
            </a>
        </div>
    </div>
</div>

@endsection
