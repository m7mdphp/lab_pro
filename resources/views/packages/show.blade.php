@extends('layouts.app')
@section('title', $package->name)
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.packages' : 'packages') }}"
       class="inline-flex items-center gap-2 text-sm text-blue-700 hover:text-blue-800 mb-6">
        ← {{ __('site.common.back') }}
    </a>
    <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
        <h1 class="text-3xl font-bold text-slate-900 mb-4">{{ $package->name }}</h1>
        @if($package->categories->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-5">
                @foreach($package->categories as $cat)
                    <span class="text-sm bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-medium">{{ $cat->name }}</span>
                @endforeach
            </div>
        @endif
        @if($package->description)
            <div class="prose max-w-none text-slate-600 mb-8">{!! nl2br(e($package->description)) !!}</div>
        @endif
        <div class="flex items-center justify-between pt-6 border-t border-slate-100">
            <div>
                @if($package->price_egp)
                    <div class="text-3xl font-bold text-blue-700">{{ number_format($package->price_egp, 0) }} <span class="text-base font-normal text-slate-500">{{ __('site.common.egp') }}</span></div>
                @endif
            </div>
            <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.booking' : 'booking') }}"
               class="px-6 py-3 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl transition-colors">
                {{ __('site.packages.book') }}
            </a>
        </div>
    </div>
</div>
@endsection
