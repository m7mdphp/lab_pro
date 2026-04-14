@extends('layouts.app')

@section('title', __('site.partners.title'))
@section('description', __('site.partners.subtitle'))

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-green-900 to-emerald-700 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">{{ __('site.partners.title') }}</h1>
        <p class="text-green-100 text-lg max-w-2xl mx-auto">{{ __('site.partners.subtitle') }}</p>
    </div>
</section>

{{-- Partners grid --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($partners->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($partners as $partner)
                    <div class="bg-slate-50 rounded-2xl border border-slate-200 p-8 hover:border-green-300 hover:shadow-md transition-all">

                        {{-- Logo placeholder or initial --}}
                        <div class="w-16 h-16 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center mb-5 text-2xl font-black">
                            {{ mb_substr($partner->name, 0, 1) }}
                        </div>

                        <h2 class="text-lg font-bold text-slate-900 mb-1">{{ $partner->name }}</h2>

                        @if($partner->specialty)
                            <p class="text-green-700 text-sm font-semibold mb-3">{{ $partner->specialty }}</p>
                        @endif

                        @if($partner->description)
                            <p class="text-slate-500 text-sm leading-relaxed mb-5">{{ $partner->description }}</p>
                        @endif

                        <div class="flex flex-wrap gap-3">
                            @if($partner->phone)
                                <a href="tel:{{ $partner->phone }}"
                                   class="inline-flex items-center gap-1.5 text-sm text-slate-600 hover:text-green-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    {{ $partner->phone }}
                                </a>
                            @endif
                            @if($partner->website_url)
                                <a href="{{ $partner->website_url }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-1.5 text-sm text-slate-600 hover:text-green-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    {{ __('site.partners.visit_website') }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20">
                <div class="text-6xl mb-4">🤝</div>
                <p class="text-slate-500">{{ __('site.partners.empty') }}</p>
            </div>
        @endif

    </div>
</section>

{{-- Partnership CTA --}}
<section class="py-16 bg-slate-50 border-t border-slate-200">
    <div class="max-w-2xl mx-auto px-4 text-center">
        <h2 class="text-2xl font-bold text-slate-900 mb-3">{{ __('site.partners.cta_title') }}</h2>
        <p class="text-slate-500 mb-6">{{ __('site.partners.cta_desc') }}</p>
        <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.contact' : 'contact') }}"
           class="inline-block px-8 py-3 bg-green-700 text-white font-bold rounded-xl hover:bg-green-800 transition-colors">
            {{ __('site.contact.title') }}
        </a>
    </div>
</section>

@endsection
