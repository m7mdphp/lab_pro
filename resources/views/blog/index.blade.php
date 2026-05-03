@extends('layouts.app')
@php
    use App\Models\SiteSetting;
    $isAr = app()->getLocale() === 'ar';
    $l    = $isAr ? 'ar' : 'en';
    $pageTitle    = SiteSetting::get("text_blog_title_{$l}")    ?: ($isAr ? 'المدونة الطبية' : 'Medical Blog');
    $pageSubtitle = SiteSetting::get("text_blog_subtitle_{$l}") ?: ($isAr ? 'مقالات ونصائح صحية من فريق معامل الشيخة' : 'Health articles and tips from the El-Sheikha Lab team');
    $heroImg = SiteSetting::get('image_blog_hero');
    $heroUrl = $heroImg
        ? (str_starts_with($heroImg, 'http') ? $heroImg : asset('storage/' . $heroImg))
        : 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?w=1920&q=80&auto=format&fit=crop';
@endphp
@section('title', $pageTitle)
@section('description', $pageSubtitle)

@section('content')

{{-- Hero --}}
<section class="relative text-white overflow-hidden" style="min-height: 360px;">
    <div class="page-hero-bg absolute inset-0"
         style="background-image: url('{{ $heroUrl }}'); background-size: cover; background-position: center;"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-green-950/92 via-green-900/88 to-emerald-900/92"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <div class="page-hero-badge inline-flex items-center gap-2 bg-white/10 border border-white/20 backdrop-blur-sm text-white text-xs font-bold px-4 py-1.5 rounded-full mb-5">
            <span class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0"></span>
            ✍️ {{ $isAr ? 'محتوى طبي موثوق' : 'Trusted Medical Content' }}
        </div>
        <h1 class="page-hero-title text-4xl md:text-5xl font-extrabold mb-4 leading-tight">{{ $pageTitle }}</h1>
        <p class="page-hero-subtitle text-green-100/85 text-lg max-w-2xl mx-auto leading-relaxed">{{ $pageSubtitle }}</p>
    </div>
</section>

{{-- Filters + Search --}}
<div class="bg-white border-b border-slate-200 sticky top-0 z-10 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" action="{{ route($isAr ? 'ar.blog' : 'blog') }}"
              class="flex flex-wrap gap-3 py-3 items-center">
            {{-- Search --}}
            <div class="relative flex-1 min-w-[180px]">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="{{ $isAr ? 'ابحث في المقالات...' : 'Search articles...' }}"
                       class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-400 focus:border-green-400 outline-none">
                <svg class="absolute start-3 top-2.5 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            {{-- Category chips --}}
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route($isAr ? 'ar.blog' : 'blog') }}"
                   class="text-xs font-semibold px-3 py-1.5 rounded-full border transition-colors {{ !request('category') ? 'bg-green-700 text-white border-green-700' : 'border-slate-200 text-slate-500 hover:border-green-400 hover:text-green-700' }}">
                    {{ $isAr ? 'الكل' : 'All' }}
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route($isAr ? 'ar.blog' : 'blog', ['category' => $cat]) }}"
                       class="text-xs font-semibold px-3 py-1.5 rounded-full border transition-colors {{ request('category') === $cat ? 'bg-green-700 text-white border-green-700' : 'border-slate-200 text-slate-500 hover:border-green-400 hover:text-green-700' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </form>
    </div>
</div>

{{-- Posts grid --}}
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($posts->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $i => $post)
                    <article class="group bg-white rounded-2xl border border-slate-200 hover:border-green-300 hover:shadow-xl transition-all overflow-hidden hover:-translate-y-0.5"
                             data-aos="fade-up" data-aos-delay="{{ min(($i % 3) * 100, 200) }}">
                        {{-- Thumbnail --}}
                        <a href="{{ route($isAr ? 'ar.blog.show' : 'blog.show', $post->slug) }}" class="block overflow-hidden h-48 bg-slate-100">
                            @if($post->featured_image)
                                <img src="{{ $post->getFeaturedImageUrl() }}"
                                     alt="{{ $post->getTitle() }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-green-100 to-emerald-50">
                                    <svg class="w-16 h-16 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                    </svg>
                                </div>
                            @endif
                        </a>

                        <div class="p-6">
                            {{-- Category + read time --}}
                            <div class="flex items-center gap-2 mb-3">
                                @if($post->getCategory())
                                    <span class="text-xs font-bold text-green-700 bg-green-50 px-2.5 py-1 rounded-full">
                                        {{ $post->getCategory() }}
                                    </span>
                                @endif
                                @if($post->hasAudio())
                                    <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full flex items-center gap-1">
                                        🎧 {{ $isAr ? 'مسموع' : 'Audio' }}
                                    </span>
                                @endif
                                <span class="text-xs text-slate-400 ms-auto">{{ $post->read_time }} {{ $isAr ? 'د' : 'min' }}</span>
                            </div>

                            {{-- Title --}}
                            <h2 class="font-extrabold text-slate-900 text-lg mb-2 leading-snug group-hover:text-green-700 transition-colors">
                                <a href="{{ route($isAr ? 'ar.blog.show' : 'blog.show', $post->slug) }}">
                                    {{ $post->getTitle() }}
                                </a>
                            </h2>

                            {{-- Excerpt --}}
                            @if($post->getExcerpt())
                                <p class="text-sm text-slate-500 leading-relaxed mb-4 line-clamp-3">{{ $post->getExcerpt() }}</p>
                            @endif

                            {{-- Footer --}}
                            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                <div class="text-xs text-slate-400">
                                    @if($post->getAuthor())
                                        <span>{{ $post->getAuthor() }}</span> ·
                                    @endif
                                    {{ $post->published_at?->format('d M Y') }}
                                </div>
                                <a href="{{ route($isAr ? 'ar.blog.show' : 'blog.show', $post->slug) }}"
                                   class="text-xs font-bold text-green-700 hover:text-green-600 transition-colors flex items-center gap-1">
                                    {{ $isAr ? 'اقرأ المزيد' : 'Read More' }}
                                    <svg class="w-3.5 h-3.5 {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($posts->hasPages())
                <div class="mt-12 flex justify-center" data-aos="fade-up">
                    {{ $posts->withQueryString()->links() }}
                </div>
            @endif

        @else
            <div class="text-center py-24">
                <div class="text-6xl mb-4">✍️</div>
                <p class="text-slate-400 text-lg">{{ $isAr ? 'لا توجد مقالات بعد. تابعنا قريباً.' : 'No articles yet. Stay tuned.' }}</p>
            </div>
        @endif
    </div>
</section>

@endsection
