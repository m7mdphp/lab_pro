@extends('layouts.app')
@php
    use App\Models\SiteSetting;
    $isAr = app()->getLocale() === 'ar';
    $l    = $isAr ? 'ar' : 'en';
    $sc   = fn ($key, $fb) => SiteSetting::get($key) ?: $fb;
    $pageTitle    = $sc("text_corporate_title_{$l}",    $isAr ? 'خدمات الشركات والمؤسسات' : 'Corporate & Institutional Services');
    $pageSubtitle = $sc("text_corporate_subtitle_{$l}", $isAr ? 'برامج صحة مهنية شاملة لحماية موظفيك وتعزيز إنتاجية شركتك'
                                                              : 'Comprehensive occupational health programmes to protect your employees and boost productivity');
    $heroImg = SiteSetting::get('image_corporate_hero');
    $heroUrl = $heroImg
        ? (str_starts_with($heroImg, 'http') ? $heroImg : asset('storage/' . $heroImg))
        : 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=1920&q=80&auto=format&fit=crop';
@endphp
@section('title', $pageTitle)
@section('description', $pageSubtitle)

@section('content')

{{-- Hero --}}
<section class="relative text-white overflow-hidden" style="min-height: 420px;">
    <div class="page-hero-bg absolute inset-0"
         style="background-image: url('{{ $heroUrl }}'); background-size: cover; background-position: center;"></div>
    <div class="absolute inset-0 bg-gradient-to-br from-violet-950/92 via-purple-900/88 to-fuchsia-800/80"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32 text-center">
        <div class="page-hero-badge inline-flex items-center gap-2 bg-white/10 border border-white/20 backdrop-blur-sm text-white text-xs font-bold px-4 py-1.5 rounded-full mb-6">
            <span class="w-2 h-2 rounded-full bg-fuchsia-400 flex-shrink-0"></span>
            🏢 {{ $isAr ? 'حلول B2B للشركات والمؤسسات' : 'B2B Solutions for Corporates & Institutions' }}
        </div>
        <h1 class="page-hero-title text-4xl md:text-5xl font-extrabold leading-tight mb-5">{{ $pageTitle }}</h1>
        <p class="page-hero-subtitle text-purple-100/85 text-lg leading-relaxed max-w-2xl mx-auto">{{ $pageSubtitle }}</p>
        <div class="page-hero-extra mt-8 flex flex-wrap gap-4 justify-center">
            <a href="{{ route($isAr ? 'ar.contact' : 'contact') }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-white text-purple-900 font-extrabold rounded-2xl hover:bg-purple-50 transition-all shadow-lg hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                {{ $isAr ? 'احصل على عرض سعر' : 'Get a Quote' }}
            </a>
        </div>
    </div>
</section>

{{-- Stats bar --}}
<div class="bg-purple-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            @foreach([
                ['val' => '500+', 'lbl_ar' => 'شركة عميل', 'lbl_en' => 'Client Companies'],
                ['val' => '300+', 'lbl_ar' => 'تحليل متاح', 'lbl_en' => 'Available Tests'],
                ['val' => '24h',  'lbl_ar' => 'إنجاز النتائج', 'lbl_en' => 'Results Turnaround'],
                ['val' => '100%', 'lbl_ar' => 'سرية طبية', 'lbl_en' => 'Medical Confidentiality'],
            ] as $stat)
                <div>
                    <div class="text-3xl font-extrabold text-white mb-1">{{ $stat['val'] }}</div>
                    <div class="text-sm text-purple-200">{{ $isAr ? $stat['lbl_ar'] : $stat['lbl_en'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Services grid --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">
                {{ $isAr ? 'ما نقدمه للشركات' : 'What We Offer Corporates' }}
            </h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['icon' => '🩺', 'color' => 'purple',
                 'title_ar' => 'الفحص الطبي قبل التوظيف', 'title_en' => 'Pre-Employment Medical Screening',
                 'desc_ar'  => 'فحص شامل لتحديد اللياقة الصحية للموظفين الجدد وفق متطلبات الصناعة.',
                 'desc_en'  => 'Comprehensive screening to determine health fitness for new employees per industry requirements.'],
                ['icon' => '📅', 'color' => 'fuchsia',
                 'title_ar' => 'برامج الفحص الدوري', 'title_en' => 'Periodic Health Programmes',
                 'desc_ar'  => 'فحوصات دورية مجدولة لمتابعة الصحة العامة لموظفيك وتقليل الغياب.',
                 'desc_en'  => 'Scheduled periodic check-ups to monitor your employees\' general health and reduce absenteeism.'],
                ['icon' => '🏭', 'color' => 'violet',
                 'title_ar' => 'صحة المهن والسلامة المهنية', 'title_en' => 'Occupational Health & Safety',
                 'desc_ar'  => 'تحاليل متخصصة لتقييم مخاطر بيئة العمل كالتعرض للمواد الكيميائية والمعادن الثقيلة.',
                 'desc_en'  => 'Specialised tests to assess workplace hazards such as exposure to chemicals and heavy metals.'],
                ['icon' => '📊', 'color' => 'purple',
                 'title_ar' => 'تقارير إحصائية للمؤسسة', 'title_en' => 'Institutional Statistical Reports',
                 'desc_ar'  => 'تقارير مجمعة بنتائج الفحوصات لمساعدة إدارة الموارد البشرية في اتخاذ القرار.',
                 'desc_en'  => 'Aggregated reports of examination results to help HR management make decisions.'],
                ['icon' => '🚐', 'color' => 'fuchsia',
                 'title_ar' => 'الزيارات المتنقلة للشركة', 'title_en' => 'On-Site Mobile Visits',
                 'desc_ar'  => 'نُرسل فريق السحب إلى مقر شركتك لأخذ عينات الموظفين دون الحاجة للتوقف عن العمل.',
                 'desc_en'  => 'We send the collection team to your company premises to collect employee samples without work interruption.'],
                ['icon' => '💳', 'color' => 'violet',
                 'title_ar' => 'الفوترة الشهرية المجمعة', 'title_en' => 'Monthly Consolidated Invoicing',
                 'desc_ar'  => 'فاتورة موحدة شهرية لجميع التحاليل مع تقرير تفصيلي لكل موظف — مناسبة للشركات الكبرى.',
                 'desc_en'  => 'A single monthly invoice for all tests with a detailed report for each employee — ideal for large companies.'],
            ] as $i => $card)
                <div class="bg-white rounded-2xl p-7 border border-slate-200 hover:border-{{ $card['color'] }}-300 hover:shadow-lg transition-all hover:-translate-y-0.5"
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

{{-- Process --}}
<section class="py-20 bg-slate-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl font-extrabold text-slate-900">
                {{ $isAr ? 'كيف يعمل البرنامج؟' : 'How Does the Programme Work?' }}
            </h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 text-center">
            @foreach([
                ['n'=>'1','icon'=>'🤝','title_ar'=>'اتفاقية تعاون','title_en'=>'Cooperation Agreement',
                 'desc_ar'=>'نُوثق الشراكة وشروط التسعير والجدول الزمني.','desc_en'=>'We document the partnership, pricing terms and schedule.'],
                ['n'=>'2','icon'=>'📋','title_ar'=>'قائمة الموظفين','title_en'=>'Employee List',
                 'desc_ar'=>'ترسل إلينا قائمة المراد فحصهم والتحاليل المطلوبة.','desc_en'=>'Send us the list to be examined and the required tests.'],
                ['n'=>'3','icon'=>'🚐','title_ar'=>'الزيارة والسحب','title_en'=>'Visit & Collection',
                 'desc_ar'=>'يحضر فريقنا لأخذ العينات في موقعك.','desc_en'=>'Our team attends to collect samples at your location.'],
                ['n'=>'4','icon'=>'📬','title_ar'=>'استلام النتائج','title_en'=>'Receive Results',
                 'desc_ar'=>'نُرسل النتائج والفاتورة المجمعة في الموعد المحدد.','desc_en'=>'We send results and consolidated invoice on schedule.'],
            ] as $i => $s)
                <div data-aos="zoom-in" data-aos-delay="{{ $i * 120 }}">
                    <div class="w-14 h-14 rounded-2xl bg-purple-100 text-3xl flex items-center justify-center mx-auto mb-3">{{ $s['icon'] }}</div>
                    <div class="text-xs font-bold text-purple-600 uppercase tracking-widest mb-1">{{ $isAr ? 'خطوة' : 'Step' }} {{ $s['n'] }}</div>
                    <h3 class="font-extrabold text-slate-900 mb-1">{{ $isAr ? $s['title_ar'] : $s['title_en'] }}</h3>
                    <p class="text-xs text-slate-500">{{ $isAr ? $s['desc_ar'] : $s['desc_en'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-20 bg-purple-900 text-white text-center">
    <div class="max-w-2xl mx-auto px-4" data-aos="fade-up">
        <h2 class="text-3xl font-extrabold mb-4">{{ $isAr ? 'جاهز لحجز برنامج صحة موظفيك؟' : 'Ready to Book Your Employee Health Programme?' }}</h2>
        <p class="text-purple-200 mb-8 text-lg">{{ $isAr ? 'تواصل الآن واحصل على عرض سعر مخصص لشركتك.' : 'Get in touch now and receive a customised quote for your company.' }}</p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route($isAr ? 'ar.contact' : 'contact') }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-white text-purple-900 font-extrabold rounded-2xl hover:bg-purple-50 transition-all shadow-xl hover:-translate-y-0.5">
                {{ $isAr ? 'احصل على عرض سعر' : 'Get a Quote' }}
            </a>
            <a href="tel:{{ \App\Models\SiteSetting::get('hotline', '19XXX') }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-purple-800 border-2 border-white/30 text-white font-extrabold rounded-2xl hover:bg-purple-700 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                {{ \App\Models\SiteSetting::get('hotline', '19XXX') }}
            </a>
        </div>
    </div>
</section>

@endsection
