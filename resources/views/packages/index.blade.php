@extends('layouts.app')

@section('title', __('site.packages.title'))
@section('description', __('site.packages.subtitle'))

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-green-900 to-emerald-700 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-extrabold mb-3">{{ __('site.packages.title') }}</h1>
        <p class="text-green-100">{{ __('site.packages.subtitle') }}</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Category filter --}}
    @if($categories->isNotEmpty())
        <div class="flex flex-wrap gap-2 mb-8">
            <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.packages' : 'packages') }}"
               class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors
                      {{ !request('category') ? 'bg-green-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                {{ __('site.tests.all') }}
            </a>
            @foreach($categories as $cat)
                <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.packages' : 'packages', ['category' => $cat->slug]) }}"
                   class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors
                          {{ request('category') === $cat->slug ? 'bg-green-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    @endif

    @if($packages->isEmpty())
        <div class="text-center py-24 text-slate-400">
            <div class="text-5xl mb-4">📦</div>
            <p>{{ __('site.packages.empty') }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($packages as $pkg)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow flex flex-col">
                    <div class="p-6 flex-1 flex flex-col">
                        @if($pkg->is_featured)
                            <span class="text-xs font-bold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full self-start mb-3">⭐ Featured</span>
                        @endif
                        <h3 class="font-bold text-slate-900 text-lg mb-2">{{ $pkg->name }}</h3>
                        @if($pkg->short_description)
                            <p class="text-sm text-slate-500 flex-1 leading-relaxed mb-4">{{ Str::limit($pkg->short_description, 120) }}</p>
                        @else
                            <div class="flex-1"></div>
                        @endif
                        @if($pkg->categories->isNotEmpty())
                            <div class="flex flex-wrap gap-1 mb-4">
                                @foreach($pkg->categories->take(3) as $cat)
                                    <span class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded-full">{{ $cat->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            @if($pkg->price_egp)
                                <div class="font-bold text-green-700 text-lg">
                                    {{ number_format($pkg->price_egp, 0) }}
                                    <span class="text-xs font-normal text-slate-500">{{ __('site.common.egp') }}</span>
                                </div>
                                @if($pkg->original_price_egp && $pkg->original_price_egp > $pkg->price_egp)
                                    <div class="text-xs text-slate-400 line-through">{{ number_format($pkg->original_price_egp, 0) }}</div>
                                @endif
                            @endif
                        </div>
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.booking' : 'booking') }}"
                           class="px-4 py-2 bg-green-700 hover:bg-green-800 text-white text-sm font-semibold rounded-lg transition-colors">
                            {{ __('site.packages.book') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $packages->links() }}
        </div>
    @endif
</div>

@endsection
