@extends('layouts.app')
@php
    use App\Models\SiteSetting;
    $isAr = app()->getLocale() === 'ar';
    $l    = $isAr ? 'ar' : 'en';
    $pageTitle    = \App\Models\SiteSetting::get("text_results_title_{$l}")    ?: ($isAr ? 'نتائجي الطبية'        : 'My Medical Results');
    $pageSubtitle = \App\Models\SiteSetting::get("text_results_subtitle_{$l}") ?: ($isAr ? 'استعرض نتائج تحاليلك الطبية بسرية وأمان من أي مكان' : 'View your medical test results securely from anywhere');
    $heroImg = \App\Models\SiteSetting::get('image_results_hero');
    $heroUrl = $heroImg
        ? (str_starts_with($heroImg, 'http') ? $heroImg : asset('storage/' . $heroImg))
        : 'https://images.unsplash.com/photo-1576671414817-75cf7af9d64e?w=1920&q=80&auto=format&fit=crop';
@endphp
@section('title', $pageTitle)
@section('description', $pageSubtitle)

@section('content')

{{-- Hero --}}
<section class="relative text-white overflow-hidden" style="min-height: 380px;">
    <div class="page-hero-bg absolute inset-0"
         style="background-image: url('{{ $heroUrl }}'); background-size: cover; background-position: center;"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-green-950/92 via-green-900/88 to-emerald-800/80"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <div class="page-hero-badge inline-flex items-center gap-2 bg-white/10 border border-white/20 backdrop-blur-sm text-white text-xs font-bold px-4 py-1.5 rounded-full mb-6">
            <span class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0 animate-pulse"></span>
            🔒 {{ $isAr ? 'محمي وآمن ١٠٠٪' : '100% Secure & Private' }}
        </div>
        <h1 class="page-hero-title text-4xl md:text-5xl font-extrabold leading-tight mb-5">{{ $pageTitle }}</h1>
        <p class="page-hero-subtitle text-green-100/85 text-lg leading-relaxed max-w-2xl mx-auto">{{ $pageSubtitle }}</p>
    </div>
</section>

{{-- Portal card --}}
<section class="py-16 bg-slate-50">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">

        @if($portalUrl)
        {{-- External portal redirect --}}
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200" data-aos="fade-up">
            <div class="h-2 bg-gradient-to-r from-green-500 to-emerald-400"></div>
            <div class="p-10 text-center">
                <div class="w-20 h-20 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center mx-auto mb-6 text-4xl">🧾</div>
                <h2 class="text-2xl font-extrabold text-slate-900 mb-3">
                    {{ $isAr ? 'بوابة النتائج الإلكترونية' : 'Online Results Portal' }}
                </h2>
                <p class="text-slate-500 text-sm leading-relaxed mb-8">
                    {{ $isAr
                        ? 'سيتم تحويلك إلى بوابة الطرف الثالث لعرض نتائجك. ستحتاج إلى رقم مرجعي تحليلك أو رقم هاتفك المسجل.'
                        : 'You will be redirected to the third-party portal to view your results. You will need your test reference number or registered phone number.' }}
                </p>
                <a href="{{ $portalUrl }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-3 px-8 py-4 bg-green-700 hover:bg-green-600 text-white font-extrabold text-lg rounded-2xl transition-all shadow-lg hover:-translate-y-0.5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    {{ $isAr ? $portalLabel : $portalLabelEn }}
                </a>
                <p class="mt-4 text-xs text-slate-400">
                    {{ $isAr ? 'سيُفتح في نافذة جديدة' : 'Opens in a new window' }}
                </p>
            </div>
        </div>
        @else
        {{-- No portal configured — show contact info --}}
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200" data-aos="fade-up">
            <div class="h-2 bg-gradient-to-r from-green-500 to-emerald-400"></div>
            <div class="p-10 text-center">
                <div class="w-20 h-20 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-6 text-4xl">📞</div>
                <h2 class="text-2xl font-extrabold text-slate-900 mb-3">
                    {{ $isAr ? 'للحصول على نتائجك' : 'To Get Your Results' }}
                </h2>
                <p class="text-slate-500 text-sm leading-relaxed mb-6">
                    {{ $isAr
                        ? 'يمكنك الحصول على نتائج تحاليلك عبر التواصل المباشر مع المعمل بالوسائل التالية:'
                        : 'You can obtain your test results by contacting the laboratory directly through the following means:' }}
                </p>
                <div class="space-y-3 text-right {{ $isAr ? '' : 'text-left' }}">
                    <a href="tel:{{ \App\Models\SiteSetting::get('hotline','19XXX') }}"
                       class="flex items-center gap-3 p-4 bg-green-50 rounded-xl border border-green-100 hover:border-green-300 transition-colors group">
                        <div class="w-10 h-10 rounded-xl bg-green-700 text-white flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs text-slate-400">{{ $isAr ? 'الخط الساخن' : 'Hotline' }}</div>
                            <div class="font-bold text-slate-900 group-hover:text-green-700 transition-colors">
                                {{ \App\Models\SiteSetting::get('hotline','19XXX') }}
                            </div>
                        </div>
                    </a>
                    @if(\App\Models\SiteSetting::get('whatsapp_url'))
                    <a href="{{ \App\Models\SiteSetting::get('whatsapp_url') }}" target="_blank"
                       class="flex items-center gap-3 p-4 bg-green-50 rounded-xl border border-green-100 hover:border-green-300 transition-colors group">
                        <div class="w-10 h-10 rounded-xl bg-green-700 text-white flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/><path d="M11.5 2C6.25 2 2 6.25 2 11.5c0 1.85.51 3.58 1.39 5.06L2 22l5.58-1.38A9.46 9.46 0 0011.5 21C16.75 21 21 16.75 21 11.5S16.75 2 11.5 2z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs text-slate-400">WhatsApp</div>
                            <div class="font-bold text-slate-900 group-hover:text-green-700 transition-colors">
                                {{ $isAr ? 'تواصل عبر واتساب' : 'Message via WhatsApp' }}
                            </div>
                        </div>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- How it works --}}
        <div class="mt-12" data-aos="fade-up" data-aos-delay="100">
            <h2 class="text-xl font-extrabold text-slate-900 mb-6 text-center">
                {{ $isAr ? 'كيف تصل إلى نتائجك؟' : 'How to Access Your Results?' }}
            </h2>
            <div class="space-y-4">
                @foreach([
                    ['step' => '1', 'icon' => '🏥',
                     'title_ar' => 'أجرِ تحليلك في أحد فروعنا أو عبر الخدمة المنزلية',
                     'title_en' => 'Have your test done at one of our branches or via home service'],
                    ['step' => '2', 'icon' => '📲',
                     'title_ar' => 'ستتلقى إشعاراً بجاهزية النتيجة عبر الرسائل القصيرة أو البريد الإلكتروني',
                     'title_en' => 'You will receive a notification when results are ready via SMS or email'],
                    ['step' => '3', 'icon' => '🔐',
                     'title_ar' => 'ادخل إلى البوابة برقمك المرجعي أو اتصل بنا مباشرةً',
                     'title_en' => 'Access the portal with your reference number or call us directly'],
                    ['step' => '4', 'icon' => '📄',
                     'title_ar' => 'استعرض وحمّل نتائجك أو أرسلها مباشرةً لطبيبك',
                     'title_en' => 'View and download your results or send them directly to your doctor'],
                ] as $i => $step)
                    <div class="flex items-start gap-4 p-4 bg-white rounded-xl border border-slate-200">
                        <div class="w-9 h-9 rounded-full bg-green-700 text-white text-sm font-extrabold flex items-center justify-center flex-shrink-0">
                            {{ $step['step'] }}
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">{{ $step['icon'] }}</span>
                            <p class="text-slate-700 text-sm">{{ $isAr ? $step['title_ar'] : $step['title_en'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Need help --}}
        <div class="mt-10 bg-green-50 rounded-2xl p-6 border border-green-100 text-center" data-aos="fade-up">
            <p class="text-slate-600 text-sm mb-4">
                {{ $isAr ? 'لم تجد نتيجتك؟ تواصل معنا مباشرةً' : "Can't find your result? Contact us directly" }}
            </p>
            <a href="{{ route($isAr ? 'ar.contact' : 'contact') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-green-700 hover:bg-green-600 text-white font-bold rounded-xl transition-colors">
                {{ $isAr ? 'اتصل بنا' : 'Contact Us' }}
            </a>
        </div>
    </div>
</section>

@endsection
