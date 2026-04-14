@extends('layouts.app')

@section('title', $service->name)
@section('description', $service->short_description)

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-green-900 to-emerald-700 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.services' : 'services') }}"
               class="inline-flex items-center gap-2 text-green-200 hover:text-white text-sm mb-6 transition-colors">
                <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('site.services.all_services') }}
            </a>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4">{{ $service->name }}</h1>
            <p class="text-green-100 text-lg">{{ $service->short_description }}</p>
        </div>
    </div>
</section>

{{-- Content + Features --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            {{-- Main content --}}
            <div class="lg:col-span-2">
                @if($service->description)
                    <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed mb-10">
                        {!! $service->description !!}
                    </div>
                @endif

                @if(count($service->features) > 0)
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">{{ __('site.services.what_we_offer') }}</h2>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($service->features as $feature)
                            <li class="flex items-start gap-3 p-4 bg-green-50 rounded-xl border border-green-100">
                                <div class="w-6 h-6 rounded-full bg-green-700 text-white flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-slate-700 font-medium text-sm">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                {{-- CTA --}}
                <div class="mt-12 p-8 bg-green-700 rounded-2xl text-white text-center">
                    <h3 class="text-xl font-bold mb-3">{{ __('site.home.cta_title') }}</h3>
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.booking' : 'booking') }}"
                       class="inline-block px-8 py-3 bg-white text-green-800 font-bold rounded-xl hover:bg-green-50 transition-colors">
                        {{ __('site.home.cta_button') }}
                    </a>
                </div>
            </div>

            {{-- Sidebar: other services --}}
            <div>
                <h3 class="text-lg font-bold text-slate-900 mb-4">{{ __('site.services.other_services') }}</h3>
                <div class="space-y-3">
                    @foreach($otherServices as $other)
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.services.show' : 'services.show', $other->slug) }}"
                           class="block p-4 bg-slate-50 rounded-xl border border-slate-200 hover:border-green-400 hover:shadow-sm transition-all group">
                            <p class="font-semibold text-slate-800 group-hover:text-green-700 transition-colors text-sm">{{ $other->name }}</p>
                            <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $other->short_description }}</p>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8 p-6 bg-slate-50 rounded-xl border border-slate-200">
                    <p class="text-sm text-slate-600 mb-3">{{ __('site.services.need_help') }}</p>
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.contact' : 'contact') }}"
                       class="block text-center px-4 py-2 bg-green-700 text-white text-sm font-semibold rounded-lg hover:bg-green-800 transition-colors">
                        {{ __('site.contact.title') }}
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
