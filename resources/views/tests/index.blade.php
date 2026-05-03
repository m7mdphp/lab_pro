@extends('layouts.app')

@section('title', __('site.tests.title'))
@section('description', __('site.tests.subtitle'))

@section('content')
@php
    use App\Models\SiteSetting;
    $testsHeroImage = SiteSetting::get('image_tests_hero');
    $testsHeroUrl = $testsHeroImage
        ? (str_starts_with($testsHeroImage, 'http') ? $testsHeroImage : asset('storage/' . $testsHeroImage))
        : 'https://images.unsplash.com/photo-1579154204601-01588f351e67?w=1920&q=80&auto=format&fit=crop';
@endphp

{{-- Hero --}}
<section class="relative text-white overflow-hidden">
    <div class="absolute inset-0"
         style="background-image: url('{{ $testsHeroUrl }}'); background-size: cover; background-position: center;">
    </div>
    <div class="absolute inset-0 bg-gradient-to-br from-green-950/92 to-emerald-800/85"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-4xl font-extrabold mb-3">{{ __('site.tests.title') }}</h1>
        <p class="text-green-100 mb-8">{{ __('site.tests.subtitle') }}</p>

        {{-- Search form --}}
        <form method="GET" class="flex gap-3 max-w-xl">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="{{ __('site.tests.search') }}"
                   class="flex-1 px-4 py-2.5 rounded-lg text-slate-800 text-sm border-0 focus:ring-2 focus:ring-white/50 bg-white/95">
            <button type="submit"
                    class="px-5 py-2.5 bg-white text-green-800 font-semibold rounded-lg hover:bg-green-50 transition-colors text-sm">
                🔍
            </button>
        </form>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- Sidebar: categories --}}
        @if($categories->isNotEmpty())
        <aside class="lg:w-56 flex-shrink-0">
            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">{{ __('site.tests.filter') }}</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.tests' : 'tests') }}"
                       class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ !request('category') ? 'bg-green-700 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                        {{ __('site.tests.all') }}
                    </a>
                </li>
                @foreach($categories as $cat)
                    <li>
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.tests' : 'tests', ['category' => $cat->slug]) }}"
                           class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors
                                  {{ request('category') === $cat->slug ? 'bg-green-700 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                            {{ $cat->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>
        @endif

        {{-- Grid --}}
        <div class="flex-1">
            @if($packages->isEmpty())
                <div class="text-center py-20 text-slate-400">
                    <div class="text-4xl mb-4">🔬</div>
                    <p>{{ __('site.tests.empty') }}</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($packages as $pkg)
                        <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-md transition-shadow flex flex-col">
                            <h3 class="font-bold text-slate-900 mb-2">{{ $pkg->name }}</h3>
                            @if($pkg->short_description)
                                <p class="text-sm text-slate-500 flex-1 mb-3">{{ Str::limit($pkg->short_description, 100) }}</p>
                            @else
                                <div class="flex-1"></div>
                            @endif
                            @if($pkg->categories->isNotEmpty())
                                <div class="flex flex-wrap gap-1 mb-3">
                                    @foreach($pkg->categories->take(2) as $cat)
                                        <span class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded-full font-medium">{{ $cat->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                                @if($pkg->price_egp)
                                    <span class="font-bold text-green-700">
                                        {{ number_format($pkg->price_egp, 0) }} <span class="text-xs font-normal text-slate-500">{{ __('site.common.egp') }}</span>
                                    </span>
                                @endif
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.booking' : 'booking') }}"
                                   class="px-4 py-1.5 bg-green-700 hover:bg-green-800 text-white text-xs font-semibold rounded-lg transition-colors">
                                    {{ __('site.tests.book') }}
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
    </div>
</div>

@endsection
