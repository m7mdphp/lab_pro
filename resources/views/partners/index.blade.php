@extends('layouts.app')
@section('title', __('site.partners.title'))
@section('description', __('site.partners.subtitle'))

@section('content')
@php
    use App\Models\SiteSetting;
    $partnersHeroImage = SiteSetting::get('image_partners_hero');
    $partnersHeroUrl = $partnersHeroImage
        ? (str_starts_with($partnersHeroImage, 'http') ? $partnersHeroImage : asset('storage/' . $partnersHeroImage))
        : 'https://images.unsplash.com/photo-1512678080530-7760d81faba6?w=1920&q=80&auto=format&fit=crop';
    $isAr = app()->getLocale() === 'ar';
@endphp

{{-- Hero --}}
<section class="relative text-white overflow-hidden" style="min-height: 400px;">
    <div class="page-hero-bg absolute inset-0"
         style="background-image: url('{{ $partnersHeroUrl }}'); background-size: cover; background-position: center;"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-green-950/90 via-green-900/88 to-emerald-900/92"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <div class="page-hero-badge inline-flex items-center gap-2 bg-white/10 border border-white/20 backdrop-blur-sm text-white text-xs font-bold px-4 py-1.5 rounded-full mb-5">
            <span class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0"></span>
            {{ $isAr ? 'شركاؤنا في الرعاية الصحية' : 'Our Healthcare Partners' }}
        </div>
        <h1 class="page-hero-title text-4xl md:text-5xl font-extrabold mb-4 leading-tight">{{ __('site.partners.title') }}</h1>
        <p class="page-hero-subtitle text-green-100/85 text-lg max-w-2xl mx-auto leading-relaxed">{{ __('site.partners.subtitle') }}</p>
    </div>
</section>

{{-- Partners grid --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($partners->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($partners as $i => $partner)
                    <div class="group bg-white rounded-2xl border border-slate-200 hover:border-green-300 hover:shadow-xl transition-all overflow-hidden hover:-translate-y-0.5"
                         data-aos="fade-up" data-aos-delay="{{ min(($i % 3) * 100, 200) }}">
                        <div class="h-1.5 bg-gradient-to-r from-green-500 to-emerald-400"></div>
                        <div class="p-7">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-100 to-emerald-100 text-green-800 flex items-center justify-center mb-5 text-2xl font-black border border-green-200 group-hover:scale-105 transition-transform">
                                {{ mb_substr($partner->name, 0, 1) }}
                            </div>
                            <h2 class="text-lg font-bold text-slate-900 mb-1 group-hover:text-green-700 transition-colors">{{ $partner->name }}</h2>
                            @if($partner->specialty)
                                <p class="text-green-700 text-sm font-semibold mb-3">{{ $partner->specialty }}</p>
                            @endif
                            @if($partner->description)
                                <p class="text-slate-500 text-sm leading-relaxed mb-5">{{ $partner->description }}</p>
                            @endif
                            <div class="flex flex-wrap gap-3 pt-4 border-t border-slate-100">
                                @if($partner->phone)
                                    <a href="tel:{{ $partner->phone }}"
                                       class="inline-flex items-center gap-1.5 text-sm text-slate-600 hover:text-green-700 transition-colors font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        {{ $partner->phone }}
                                    </a>
                                @endif
                                @if($partner->website_url)
                                    <a href="{{ $partner->website_url }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center gap-1.5 text-sm text-slate-600 hover:text-green-700 transition-colors font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        {{ __('site.partners.visit_website') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-24">
                <div class="text-6xl mb-4">🤝</div>
                <p class="text-slate-400 text-lg">{{ __('site.partners.empty') }}</p>
            </div>
        @endif
    </div>
</section>

{{-- Partnership CTA --}}
<section class="relative py-20 overflow-hidden text-white text-center"
         style="background-image: url('https://images.unsplash.com/photo-1531482615713-2afd69097998?w=1600&q=80&auto=format&fit=crop'); background-size: cover; background-position: center;">
    <div class="absolute inset-0 bg-gradient-to-r from-green-900/93 to-emerald-800/90"></div>
    <div class="relative max-w-2xl mx-auto px-4">
        <h2 class="text-3xl font-extrabold mb-3" data-aos="fade-up">{{ __('site.partners.cta_title') }}</h2>
        <p class="text-green-100/80 mb-8 text-lg" data-aos="fade-up" data-aos-delay="100">{{ __('site.partners.cta_desc') }}</p>
        <a href="{{ route($isAr ? 'ar.contact' : 'contact') }}"
           class="inline-flex items-center gap-2 px-8 py-4 bg-white text-green-800 font-extrabold rounded-2xl hover:bg-green-50 transition-all shadow-xl hover:-translate-y-0.5"
           data-aos="fade-up" data-aos-delay="200">
            {{ __('site.contact.title') }}
        </a>
    </div>
</section>

@endsection
