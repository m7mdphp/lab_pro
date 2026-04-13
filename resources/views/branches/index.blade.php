@extends('layouts.app')

@section('title', __('site.branches.title'))
@section('description', __('site.branches.subtitle'))

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-blue-900 to-cyan-800 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-extrabold mb-3">{{ __('site.branches.title') }}</h1>
        <p class="text-blue-100">{{ __('site.branches.subtitle') }}</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($branches->isEmpty())
        <div class="text-center py-24 text-slate-400">
            <div class="text-5xl mb-4">📍</div>
            <p>{{ __('site.branches.empty') }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($branches as $branch)
                <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">{{ $branch->name }}</h3>
                            @if($branch->city)
                                <p class="text-sm text-slate-500">{{ $branch->city }}</p>
                            @endif
                        </div>
                    </div>

                    <ul class="space-y-2 text-sm text-slate-600">
                        @if($branch->address)
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2zm0 0V5a2 2 0 012-2h14a2 2 0 012 2v2M3 7h18"/>
                                </svg>
                                {{ $branch->address }}
                            </li>
                        @endif
                        @if($branch->phone)
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <a href="tel:{{ $branch->phone }}" class="hover:text-blue-700 transition-colors">{{ $branch->phone }}</a>
                            </li>
                        @endif
                        @if($branch->working_hours)
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $branch->working_hours }}
                            </li>
                        @endif
                    </ul>

                    @if($branch->google_maps_url)
                        <a href="{{ $branch->google_maps_url }}" target="_blank" rel="noopener"
                           class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-blue-700 hover:text-blue-800">
                            🗺 {{ __('site.branches.map') }}
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
