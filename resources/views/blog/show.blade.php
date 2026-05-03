@extends('layouts.app')
@php
    $isAr = app()->getLocale() === 'ar';
@endphp
@section('title', $post->getSeoTitle())
@section('description', $post->getSeoDescription())
@section('og_image', $post->getFeaturedImageUrl() ?? '')
@section('og_type', 'article')

@section('content')

{{-- Breadcrumb + Hero --}}
<section class="bg-slate-900 text-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-xs text-slate-400 mb-6">
            <a href="{{ route($isAr ? 'ar.home' : 'home') }}" class="hover:text-white transition-colors">{{ $isAr ? 'الرئيسية' : 'Home' }}</a>
            <svg class="w-3 h-3 {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route($isAr ? 'ar.blog' : 'blog') }}" class="hover:text-white transition-colors">{{ $isAr ? 'المدونة' : 'Blog' }}</a>
            <svg class="w-3 h-3 {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-300 truncate max-w-[200px]">{{ $post->getTitle() }}</span>
        </nav>

        {{-- Category badge --}}
        @if($post->getCategory())
            <span class="inline-block text-xs font-bold text-green-400 bg-green-400/10 border border-green-400/20 px-3 py-1 rounded-full mb-4">
                {{ $post->getCategory() }}
            </span>
        @endif

        <h1 class="text-3xl md:text-4xl font-extrabold leading-tight mb-5">{{ $post->getTitle() }}</h1>

        {{-- Meta bar --}}
        <div class="flex flex-wrap items-center gap-4 text-sm text-slate-400">
            @if($post->getAuthor())
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ $post->getAuthor() }}
                </span>
            @endif
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $post->published_at?->format('d M Y') }}
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $post->read_time }} {{ $isAr ? 'دقائق قراءة' : 'min read' }}
            </span>
            @if($post->hasAudio())
                <span class="flex items-center gap-1.5 text-amber-400">
                    🎧 {{ $isAr ? 'يوجد تسجيل صوتي' : 'Audio available' }}
                </span>
            @endif
        </div>
    </div>
</section>

{{-- Featured image --}}
@if($post->getFeaturedImageUrl())
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 -mt-1">
        <img src="{{ $post->getFeaturedImageUrl() }}"
             alt="{{ $post->getTitle() }}"
             class="w-full h-72 md:h-96 object-cover rounded-b-3xl shadow-2xl">
    </div>
@endif

{{-- Main content area --}}
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-12">

        {{-- Article body --}}
        <article>

            {{-- Audio player --}}
            @if($post->hasAudio())
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-8" id="audio-player-section">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center flex-shrink-0 text-xl">🎧</div>
                        <div>
                            <div class="font-bold text-slate-900 text-sm">{{ $isAr ? 'استمع إلى المقال' : 'Listen to the Article' }}</div>
                            <div class="text-xs text-slate-500">{{ $post->read_time }} {{ $isAr ? 'دقائق' : 'minutes' }}</div>
                        </div>
                    </div>
                    @if($post->getAudioUrl())
                        <audio controls class="w-full rounded-xl" style="height:48px">
                            <source src="{{ $post->getAudioUrl() }}" type="audio/mpeg">
                            {{ $isAr ? 'متصفحك لا يدعم الصوت.' : 'Your browser does not support audio.' }}
                        </audio>
                    @endif
                </div>
            @endif

            {{-- Web Speech API TTS player (always shown for Arabic/English) --}}
            <div class="bg-green-50 border border-green-200 rounded-2xl p-5 mb-8" id="tts-player-section">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-green-700 text-white flex items-center justify-center flex-shrink-0 text-lg" id="tts-icon">🔊</div>
                        <div>
                            <div class="font-bold text-slate-900 text-sm">{{ $isAr ? 'قراءة بالصوت (AI)' : 'Read Aloud (AI)' }}</div>
                            <div class="text-xs text-slate-500" id="tts-status">{{ $isAr ? 'اضغط للاستماع' : 'Click to listen' }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="tts-play-btn"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-green-700 hover:bg-green-600 text-white text-sm font-bold rounded-xl transition-colors">
                            <svg id="tts-play-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span id="tts-btn-label">{{ $isAr ? 'استمع' : 'Listen' }}</span>
                        </button>
                        <button id="tts-stop-btn"
                                class="hidden px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-bold rounded-xl transition-colors">
                            ⏹ {{ $isAr ? 'إيقاف' : 'Stop' }}
                        </button>
                    </div>
                </div>
            </div>

            {{-- Article content --}}
            <div class="prose prose-slate prose-lg max-w-none
                        prose-headings:font-extrabold prose-headings:text-slate-900
                        prose-a:text-green-700 prose-a:no-underline hover:prose-a:underline
                        prose-img:rounded-2xl prose-img:shadow-md
                        {{ $isAr ? 'text-right' : '' }}" id="article-content">
                {!! $post->getContent() !!}
            </div>

            {{-- Share buttons --}}
            <div class="mt-12 pt-8 border-t border-slate-200">
                <p class="text-sm font-bold text-slate-600 mb-4">{{ $isAr ? 'شارك المقال' : 'Share this article' }}</p>
                <div class="flex gap-3">
                    @php
                        $shareUrl = urlencode(request()->url());
                        $shareTitle = urlencode($post->getTitle());
                    @endphp
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener"
                       class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                        Facebook
                    </a>
                    <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" rel="noopener"
                       class="flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/><path d="M11.5 2C6.25 2 2 6.25 2 11.5c0 1.85.51 3.58 1.39 5.06L2 22l5.58-1.38A9.46 9.46 0 0011.5 21C16.75 21 21 16.75 21 11.5S16.75 2 11.5 2z"/></svg>
                        WhatsApp
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ $shareTitle }}&url={{ $shareUrl }}" target="_blank" rel="noopener"
                       class="flex items-center gap-2 px-4 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        X
                    </a>
                </div>
            </div>
        </article>

        {{-- Sidebar --}}
        <aside>
            {{-- CTA --}}
            <div class="bg-green-700 text-white rounded-2xl p-6 mb-6 text-center sticky top-24">
                <div class="text-3xl mb-3">🔬</div>
                <h3 class="font-extrabold text-lg mb-2">{{ $isAr ? 'احجز تحليلك الآن' : 'Book Your Test Now' }}</h3>
                <p class="text-green-200 text-sm mb-4">{{ $isAr ? 'فريقنا جاهز لخدمتك' : 'Our team is ready to serve you' }}</p>
                <a href="{{ route($isAr ? 'ar.booking' : 'booking') }}"
                   class="block w-full px-5 py-3 bg-white text-green-800 font-extrabold rounded-xl hover:bg-green-50 transition-colors text-sm">
                    {{ $isAr ? 'احجز الآن' : 'Book Now' }}
                </a>
            </div>

            {{-- Related posts --}}
            @if($related->isNotEmpty())
                <div>
                    <h3 class="font-extrabold text-slate-900 mb-4">{{ $isAr ? 'مقالات ذات صلة' : 'Related Articles' }}</h3>
                    <div class="space-y-4">
                        @foreach($related as $rel)
                            <a href="{{ route($isAr ? 'ar.blog.show' : 'blog.show', $rel->slug) }}"
                               class="flex gap-3 group">
                                @if($rel->getFeaturedImageUrl())
                                    <img src="{{ $rel->getFeaturedImageUrl() }}" alt="{{ $rel->getTitle() }}"
                                         class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
                                @else
                                    <div class="w-16 h-16 rounded-xl bg-green-100 flex-shrink-0 flex items-center justify-center text-2xl">🔬</div>
                                @endif
                                <div>
                                    <p class="text-sm font-bold text-slate-900 group-hover:text-green-700 transition-colors line-clamp-2 leading-snug">
                                        {{ $rel->getTitle() }}
                                    </p>
                                    <p class="text-xs text-slate-400 mt-1">{{ $rel->read_time }} {{ $isAr ? 'د' : 'min' }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const btn      = document.getElementById('tts-play-btn');
    const stopBtn  = document.getElementById('tts-stop-btn');
    const status   = document.getElementById('tts-status');
    const btnLabel = document.getElementById('tts-btn-label');
    const content  = document.getElementById('article-content');
    const isAr     = '{{ $isAr ? 'true' : 'false' }}' === 'true';

    if (!('speechSynthesis' in window) || !content) {
        document.getElementById('tts-player-section').style.display = 'none';
        return;
    }

    let utterance = null;

    btn.addEventListener('click', function () {
        if (window.speechSynthesis.speaking) {
            if (window.speechSynthesis.paused) {
                window.speechSynthesis.resume();
                status.textContent = isAr ? 'يُقرأ الآن...' : 'Reading...';
                btnLabel.textContent = isAr ? 'إيقاف مؤقت' : 'Pause';
            } else {
                window.speechSynthesis.pause();
                status.textContent = isAr ? 'متوقف مؤقتاً' : 'Paused';
                btnLabel.textContent = isAr ? 'استمع' : 'Resume';
            }
            return;
        }

        const text = content.innerText || content.textContent;
        utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = isAr ? 'ar-EG' : 'en-US';
        utterance.rate = 0.95;

        utterance.onstart = () => {
            status.textContent   = isAr ? 'يُقرأ الآن...' : 'Reading...';
            btnLabel.textContent = isAr ? 'إيقاف مؤقت' : 'Pause';
            stopBtn.classList.remove('hidden');
        };
        utterance.onend = utterance.onerror = () => {
            status.textContent   = isAr ? 'اضغط للاستماع' : 'Click to listen';
            btnLabel.textContent = isAr ? 'استمع' : 'Listen';
            stopBtn.classList.add('hidden');
        };

        window.speechSynthesis.speak(utterance);
    });

    stopBtn.addEventListener('click', function () {
        window.speechSynthesis.cancel();
    });
})();
</script>
@endpush

@endsection
