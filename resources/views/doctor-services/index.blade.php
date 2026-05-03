@extends('layouts.app')
@php
    use App\Models\SiteSetting;
    $isAr = app()->getLocale() === 'ar';
    $l    = $isAr ? 'ar' : 'en';
    $sc   = fn ($key, $fb) => SiteSetting::get($key) ?: $fb;
    $pageTitle    = $sc("text_doctor_title_{$l}",    $isAr ? 'خدمات الأطباء والمراكز الطبية' : 'Doctor & Medical Centre Services');
    $pageSubtitle = $sc("text_doctor_subtitle_{$l}", $isAr ? 'شراكة مهنية لتقديم أفضل رعاية مخبرية لمرضاكم'
                                                           : 'A professional partnership to deliver the best laboratory care for your patients');
    $heroImg = SiteSetting::get('image_doctor_hero');
    $heroUrl = $heroImg
        ? (str_starts_with($heroImg, 'http') ? $heroImg : asset('storage/' . $heroImg))
        : 'https://images.unsplash.com/photo-1576671414817-75cf7af9d64e?w=1920&q=80&auto=format&fit=crop';
@endphp
@section('title', $pageTitle)
@section('description', $pageSubtitle)

@section('content')

{{-- Hero --}}
<section class="relative text-white overflow-hidden" style="min-height: 420px;">
    <div class="page-hero-bg absolute inset-0"
         style="background-image: url('{{ $heroUrl }}'); background-size: cover; background-position: center;"></div>
    <div class="absolute inset-0 bg-gradient-to-br from-blue-950/92 via-blue-900/88 to-teal-800/80"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32 text-center">
        <div class="page-hero-badge inline-flex items-center gap-2 bg-white/10 border border-white/20 backdrop-blur-sm text-white text-xs font-bold px-4 py-1.5 rounded-full mb-6">
            <span class="w-2 h-2 rounded-full bg-teal-400 flex-shrink-0"></span>
            👨‍⚕️ {{ $isAr ? 'خدمات B2B للأطباء' : 'B2B Services for Doctors' }}
        </div>
        <h1 class="page-hero-title text-4xl md:text-5xl font-extrabold leading-tight mb-5">{{ $pageTitle }}</h1>
        <p class="page-hero-subtitle text-blue-100/85 text-lg leading-relaxed max-w-2xl mx-auto">{{ $pageSubtitle }}</p>
        <div class="page-hero-extra mt-8">
            <a href="{{ route($isAr ? 'ar.contact' : 'contact') }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-white text-blue-900 font-extrabold rounded-2xl hover:bg-blue-50 transition-all shadow-lg hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                {{ $isAr ? 'تواصل معنا للتعاون' : 'Contact Us for Partnership' }}
            </a>
        </div>
    </div>
</section>

{{-- Why partner --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14" data-aos="fade-up">
            <span class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-3 block">{{ $isAr ? 'لماذا التعاون معنا' : 'Why Partner With Us' }}</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">
                {{ $isAr ? 'شريكك المخبري الأمثل' : 'Your Ideal Laboratory Partner' }}
            </h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['icon' => '⚡', 'color' => 'blue',
                 'title_ar' => 'نتائج فورية', 'title_en' => 'Rapid Results',
                 'desc_ar'  => 'معظم التحاليل جاهزة خلال ٢٤ ساعة مع إشعار فوري للطبيب عبر البريد أو الواتساب.',
                 'desc_en'  => 'Most results ready within 24 hours, with instant notification to the physician via email or WhatsApp.'],
                ['icon' => '🔬', 'color' => 'teal',
                 'title_ar' => 'أكثر من ٣٠٠ تحليل', 'title_en' => '300+ Tests',
                 'desc_ar'  => 'كتالوج شامل يغطي جميع التخصصات الطبية من علم الأمراض الجزيئي حتى الهرمونات والمناعة.',
                 'desc_en'  => 'A comprehensive catalogue covering all medical specialties from molecular pathology to hormones and immunology.'],
                ['icon' => '🏅', 'color' => 'indigo',
                 'title_ar' => 'معتمد ISO 15189', 'title_en' => 'ISO 15189 Certified',
                 'desc_ar'  => 'ضمان الدقة بأعلى معايير الجودة المعتمدة دولياً في التحاليل الطبية.',
                 'desc_en'  => 'Accuracy guaranteed by the highest internationally accredited quality standards in medical laboratory testing.'],
                ['icon' => '📋', 'color' => 'blue',
                 'title_ar' => 'تقارير إكلينيكية', 'title_en' => 'Clinical Reports',
                 'desc_ar'  => 'تقارير منظمة وموثقة بتفسيرات سريرية تساعد الطبيب في اتخاذ القرار.',
                 'desc_en'  => 'Structured, well-documented reports with clinical interpretations to help the physician make decisions.'],
                ['icon' => '🏠', 'color' => 'teal',
                 'title_ar' => 'خدمة منزلية للمرضى', 'title_en' => 'Home Collection for Patients',
                 'desc_ar'  => 'فريق متنقل يزور مرضاك في منازلهم — تجربة أفضل وامتثال أعلى للعلاج.',
                 'desc_en'  => 'A mobile team visits your patients at home — better experience and higher treatment compliance.'],
                ['icon' => '🤝', 'color' => 'indigo',
                 'title_ar' => 'تسعير خاص للشركاء', 'title_en' => 'Special Partner Pricing',
                 'desc_ar'  => 'باقات تسعير مخصصة لعيادتك أو مركزك الطبي بناءً على حجم الإحالات.',
                 'desc_en'  => 'Customized pricing packages for your clinic or medical centre based on referral volume.'],
            ] as $i => $card)
                <div class="bg-{{ $card['color'] }}-50 rounded-2xl p-7 border border-{{ $card['color'] }}-100 hover:shadow-lg hover:-translate-y-0.5 transition-all"
                     data-aos="fade-up" data-aos-delay="{{ min($i * 80, 320) }}">
                    <div class="text-4xl mb-4">{{ $card['icon'] }}</div>
                    <h3 class="font-extrabold text-slate-900 text-lg mb-3">
                        {{ $isAr ? $card['title_ar'] : $card['title_en'] }}
                    </h3>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        {{ $isAr ? $card['desc_ar'] : $card['desc_en'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Services for doctors --}}
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">
                {{ $isAr ? 'خدماتنا للأطباء' : 'Our Services for Doctors' }}
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach([
                ['num' => '01', 'color' => 'blue',
                 'title_ar' => 'نظام الإحالة الإلكترونية', 'title_en' => 'Electronic Referral System',
                 'desc_ar'  => 'نظام مبسط لإرسال التحاليل المطلوبة إلكترونياً دون الحاجة لنماذج ورقية. يتلقى المريض تأكيداً فورياً.',
                 'desc_en'  => 'A streamlined system for sending required tests electronically without paper forms. The patient receives immediate confirmation.'],
                ['num' => '02', 'color' => 'teal',
                 'title_ar' => 'استلام النتائج مباشرة', 'title_en' => 'Direct Results Delivery',
                 'desc_ar'  => 'تصلك النتائج فور اكتمالها عبر بريدك الإلكتروني المسجل أو رقم واتساب عيادتك.',
                 'desc_en'  => 'Results are delivered as soon as they are ready to your registered email or clinic WhatsApp number.'],
                ['num' => '03', 'color' => 'indigo',
                 'title_ar' => 'التحاليل المتخصصة والنادرة', 'title_en' => 'Specialised & Rare Tests',
                 'desc_ar'  => 'نوفر تحاليل الجينوميات، البيولوجيا الجزيئية، وتحاليل المناعة الذاتية التي لا تتوفر في معظم المعامل.',
                 'desc_en'  => 'We provide genomics, molecular biology, and autoimmune tests not available in most laboratories.'],
                ['num' => '04', 'color' => 'blue',
                 'title_ar' => 'دعم إكلينيكي متخصص', 'title_en' => 'Specialist Clinical Support',
                 'desc_ar'  => 'طبيب باثولوجيا إكلينيكية متاح للتشاور حول التحاليل المعقدة أو تفسير النتائج غير المعتادة.',
                 'desc_en'  => 'A clinical pathologist available for consultation on complex tests or interpretation of unusual results.'],
            ] as $i => $item)
                <div class="flex gap-6 bg-white rounded-2xl p-7 border border-slate-200 hover:border-{{ $item['color'] }}-300 hover:shadow-md transition-all"
                     data-aos="fade-up" data-aos-delay="{{ min($i * 100, 300) }}">
                    <div class="text-3xl font-black text-{{ $item['color'] }}-200 flex-shrink-0 leading-none">{{ $item['num'] }}</div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-lg mb-2">
                            {{ $isAr ? $item['title_ar'] : $item['title_en'] }}
                        </h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            {{ $isAr ? $item['desc_ar'] : $item['desc_en'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- How to start --}}
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-slate-900 mb-12" data-aos="fade-up">
            {{ $isAr ? 'كيف تبدأ الشراكة؟' : 'How to Start the Partnership?' }}
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
            @foreach([
                ['step' => '1', 'icon' => '📞',
                 'title_ar' => 'تواصل معنا', 'title_en' => 'Contact Us',
                 'desc_ar'  => 'اتصل على خطنا الساخن أو أرسل بريداً إلكترونياً.',
                 'desc_en'  => 'Call our hotline or send an email.'],
                ['step' => '2', 'icon' => '📝',
                 'title_ar' => 'توقيع الاتفاقية', 'title_en' => 'Sign the Agreement',
                 'desc_ar'  => 'نوثق الشراكة وشروط التسعير الخاصة بعيادتك.',
                 'desc_en'  => 'We document the partnership and your clinic\'s specific pricing terms.'],
                ['step' => '3', 'icon' => '🚀',
                 'title_ar' => 'ابدأ الإحالة فوراً', 'title_en' => 'Start Referring Immediately',
                 'desc_ar'  => 'يبدأ مرضاك في الاستفادة من خدماتنا المتكاملة.',
                 'desc_en'  => 'Your patients start benefiting from our integrated services.'],
            ] as $i => $s)
                <div data-aos="zoom-in" data-aos-delay="{{ $i * 150 }}">
                    <div class="w-16 h-16 rounded-2xl bg-blue-100 text-4xl flex items-center justify-center mx-auto mb-4">{{ $s['icon'] }}</div>
                    <div class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-1">{{ $isAr ? 'الخطوة' : 'Step' }} {{ $s['step'] }}</div>
                    <h3 class="font-extrabold text-slate-900 mb-2">{{ $isAr ? $s['title_ar'] : $s['title_en'] }}</h3>
                    <p class="text-sm text-slate-500">{{ $isAr ? $s['desc_ar'] : $s['desc_en'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-20 bg-blue-900 text-white text-center">
    <div class="max-w-2xl mx-auto px-4" data-aos="fade-up">
        <h2 class="text-3xl font-extrabold mb-4">{{ $isAr ? 'هل أنت طبيب أو صاحب مركز طبي؟' : 'Are You a Doctor or Medical Centre Owner?' }}</h2>
        <p class="text-blue-200 mb-8 text-lg">{{ $isAr ? 'تواصل معنا اليوم للحصول على باقة التسعير الخاصة بك.' : 'Contact us today to get your customised pricing package.' }}</p>
        <a href="{{ route($isAr ? 'ar.contact' : 'contact') }}"
           class="inline-flex items-center gap-2 px-8 py-4 bg-white text-blue-900 font-extrabold rounded-2xl hover:bg-blue-50 transition-all shadow-xl hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            {{ $isAr ? 'اتصل بنا الآن' : 'Contact Us Now' }}
        </a>
    </div>
</section>

@endsection
