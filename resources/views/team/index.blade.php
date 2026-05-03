@extends('layouts.app')
@php
    use App\Models\SiteSetting;
    $isAr = app()->getLocale() === 'ar';
    $l    = $isAr ? 'ar' : 'en';
    $pageTitle    = SiteSetting::get("text_team_title_{$l}")    ?: ($isAr ? 'فريق عملنا' : 'Our Team');
    $pageSubtitle = SiteSetting::get("text_team_subtitle_{$l}") ?: ($isAr ? 'نخبة من الأطباء وعلماء المختبرات والمتخصصين في خدمتك' : 'An elite team of doctors, laboratory scientists, and specialists at your service');
    $heroImg = SiteSetting::get('image_team_hero');
    $heroUrl = $heroImg
        ? (str_starts_with($heroImg, 'http') ? $heroImg : asset('storage/' . $heroImg))
        : 'https://images.unsplash.com/photo-1582750433449-648ed127bb54?w=1920&q=80&auto=format&fit=crop';
@endphp
@section('title', $pageTitle)
@section('description', $pageSubtitle)

@section('content')

{{-- Hero --}}
<section class="relative text-white overflow-hidden" style="min-height: 380px;">
    <div class="page-hero-bg absolute inset-0"
         style="background-image: url('{{ $heroUrl }}'); background-size: cover; background-position: center;"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-green-950/90 via-green-900/88 to-emerald-900/92"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <div class="page-hero-badge inline-flex items-center gap-2 bg-white/10 border border-white/20 backdrop-blur-sm text-white text-xs font-bold px-4 py-1.5 rounded-full mb-5">
            <span class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0"></span>
            👨‍⚕️ {{ $isAr ? 'خبراء في خدمتكم' : 'Experts at Your Service' }}
        </div>
        <h1 class="page-hero-title text-4xl md:text-5xl font-extrabold mb-4 leading-tight">{{ $pageTitle }}</h1>
        <p class="page-hero-subtitle text-green-100/85 text-lg max-w-2xl mx-auto leading-relaxed">{{ $pageSubtitle }}</p>
    </div>
</section>

{{-- Team grid --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($members->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($members as $i => $member)
                    <div class="group bg-white rounded-2xl border border-slate-200 hover:border-green-300 hover:shadow-xl transition-all overflow-hidden text-center hover:-translate-y-0.5"
                         data-aos="fade-up" data-aos-delay="{{ min(($i % 4) * 80, 240) }}">
                        <div class="h-1.5 bg-gradient-to-r from-green-500 to-emerald-400"></div>

                        {{-- Photo --}}
                        <div class="p-6 pb-0">
                            @if($member->getImageUrl())
                                <img src="{{ $member->getImageUrl() }}"
                                     alt="{{ $member->getName() }}"
                                     class="w-28 h-28 rounded-full object-cover mx-auto border-4 border-green-100 group-hover:border-green-300 transition-colors shadow-md">
                            @else
                                <div class="w-28 h-28 rounded-full bg-gradient-to-br from-green-100 to-emerald-100 text-green-700 text-4xl font-black flex items-center justify-center mx-auto border-4 border-green-100 group-hover:border-green-300 transition-colors shadow-md">
                                    {{ mb_substr($member->getName(), 0, 1) }}
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="p-6">
                            <h2 class="font-extrabold text-slate-900 text-lg mb-1 group-hover:text-green-700 transition-colors">
                                {{ $member->getName() }}
                            </h2>
                            @if($member->getJobTitle())
                                <p class="text-green-700 text-sm font-semibold mb-1">{{ $member->getJobTitle() }}</p>
                            @endif
                            @if($member->getSpecialty())
                                <span class="inline-block text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full mb-3">
                                    {{ $member->getSpecialty() }}
                                </span>
                            @endif
                            @if($member->getBio())
                                <p class="text-xs text-slate-500 leading-relaxed line-clamp-3 mb-4">{{ $member->getBio() }}</p>
                            @endif
                            @if($member->linkedin_url)
                                <a href="{{ $member->linkedin_url }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                                    LinkedIn
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-24">
                <div class="text-6xl mb-4">👨‍⚕️</div>
                <p class="text-slate-400 text-lg">{{ $isAr ? 'سيتم إضافة أعضاء الفريق قريباً.' : 'Team members will be added soon.' }}</p>
            </div>
        @endif
    </div>
</section>

{{-- Values strip --}}
<section class="py-16 bg-green-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-sm font-bold text-green-300 uppercase tracking-widest mb-8" data-aos="fade-up">
            {{ $isAr ? 'ما يميز فريقنا' : 'What Sets Our Team Apart' }}
        </p>
        <div class="flex flex-wrap justify-center gap-5" data-aos="fade-up" data-aos-delay="100">
            @foreach([
                ['ar' => '🎓 مؤهلون طبياً', 'en' => '🎓 Medically Qualified'],
                ['ar' => '🔬 خبرة مخبرية', 'en' => '🔬 Lab Expertise'],
                ['ar' => '❤️ رعاية المريض أولاً', 'en' => '❤️ Patient Care First'],
                ['ar' => '🌍 تدريب دولي', 'en' => '🌍 International Training'],
                ['ar' => '🏅 ISO معتمدون', 'en' => '🏅 ISO Certified'],
            ] as $badge)
                <div class="px-5 py-3 bg-white/10 border border-white/20 rounded-full text-sm font-semibold">
                    {{ $isAr ? $badge['ar'] : $badge['en'] }}
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 bg-slate-50 text-center">
    <div class="max-w-xl mx-auto px-4" data-aos="fade-up">
        <h2 class="text-2xl font-extrabold text-slate-900 mb-3">
            {{ $isAr ? 'مهتم بالانضمام لفريقنا؟' : 'Interested in Joining Our Team?' }}
        </h2>
        <p class="text-slate-500 mb-6">
            {{ $isAr ? 'نسعد باستقبال الكفاءات المتميزة — تواصل معنا.' : 'We welcome outstanding talent — get in touch.' }}
        </p>
        <a href="{{ route($isAr ? 'ar.contact' : 'contact') }}"
           class="inline-flex items-center gap-2 px-8 py-3 bg-green-700 hover:bg-green-600 text-white font-extrabold rounded-xl transition-colors shadow-lg">
            {{ $isAr ? 'تواصل معنا' : 'Contact Us' }}
        </a>
    </div>
</section>

@endsection
