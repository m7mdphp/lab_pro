@extends('layouts.app')

@section('title', __('site.about.title'))
@section('description', __('site.about.subtitle'))
@php
    use App\Models\SiteSetting;
    $totalMilestone = SiteSetting::get('stat_total_analyses_milestone', '+1,000,000');
    $foundedYear    = SiteSetting::get('founded_year', '2010');
    $statTests      = SiteSetting::get('stat_tests_count', '300+');
    $statBranches   = SiteSetting::get('branches_count', '4');
    $statDone       = SiteSetting::get('stat_analyses_done', '1M+');
    $statTime       = SiteSetting::get('stat_avg_time', '24 h');
@endphp

@section('content')
@php
    $aboutHeroImage = SiteSetting::get('image_about_hero');
    $aboutHeroUrl = $aboutHeroImage
        ? (str_starts_with($aboutHeroImage, 'http') ? $aboutHeroImage : asset('storage/' . $aboutHeroImage))
        : 'https://images.unsplash.com/photo-1631815588090-d4bfec5b1ccb?w=1920&q=80&auto=format&fit=crop';
@endphp

{{-- Hero with background image --}}
<section class="relative text-white overflow-hidden" style="min-height: 480px;">
    <div class="absolute inset-0"
         style="background-image: url('{{ $aboutHeroUrl }}'); background-size: cover; background-position: center;">
    </div>
    <div class="absolute inset-0 bg-gradient-to-br from-green-950/92 via-green-900/88 to-emerald-800/80"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-block bg-white/15 border border-white/25 text-white text-xs font-bold px-4 py-1.5 rounded-full mb-6">
                    🔬 {{ app()->getLocale() === 'ar' ? 'في خدمة مرضانا منذ' : 'Serving patients since' }} {{ $foundedYear }}
                </span>
                <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-5">{{ __('site.about.title') }}</h1>
                <p class="text-green-100 text-lg leading-relaxed">{{ __('site.about.subtitle') }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                @foreach([
                    ['value' => $statTests,    'label' => __('site.about.stats')[0]['label'] ?? 'تحليل وباقة'],
                    ['value' => $statBranches, 'label' => __('site.about.stats')[1]['label'] ?? 'فروع'],
                    ['value' => $statDone,     'label' => __('site.about.stats')[2]['label'] ?? 'تحليل منجز'],
                    ['value' => $statTime,     'label' => __('site.about.stats')[3]['label'] ?? 'متوسط وقت الإنجاز'],
                ] as $stat)
                    <div class="bg-white/10 border border-white/20 rounded-2xl p-6 text-center backdrop-blur-sm hover:bg-white/15 transition-colors">
                        <div class="text-3xl font-extrabold text-white mb-1">{{ $stat['value'] }}</div>
                        <div class="text-sm text-green-200 font-medium">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Mission + Vision --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-green-50 rounded-2xl p-8 border border-green-100 hover:shadow-md transition-shadow">
                <div class="w-14 h-14 rounded-2xl bg-green-700 text-white flex items-center justify-center mb-6 text-2xl shadow-lg">🎯</div>
                <h2 class="text-2xl font-extrabold text-slate-900 mb-4">{{ __('site.about.mission_title') }}</h2>
                <p class="text-slate-600 leading-relaxed text-base">{{ __('site.about.mission') }}</p>
            </div>
            <div class="bg-emerald-50 rounded-2xl p-8 border border-emerald-100 hover:shadow-md transition-shadow">
                <div class="w-14 h-14 rounded-2xl bg-emerald-700 text-white flex items-center justify-center mb-6 text-2xl shadow-lg">🔭</div>
                <h2 class="text-2xl font-extrabold text-slate-900 mb-4">{{ __('site.about.vision_title') }}</h2>
                <p class="text-slate-600 leading-relaxed text-base">{{ __('site.about.vision') }}</p>
            </div>
        </div>
    </div>
</section>

{{-- Lab image full-width banner --}}
@php
    $aboutBannerImage = SiteSetting::get('image_about_banner');
    $aboutBannerUrl = $aboutBannerImage
        ? (str_starts_with($aboutBannerImage, 'http') ? $aboutBannerImage : asset('storage/' . $aboutBannerImage))
        : 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1920&q=80&auto=format&fit=crop';
@endphp
<div class="relative h-72 md:h-96 overflow-hidden"
     style="background-image: url('{{ $aboutBannerUrl }}'); background-size: cover; background-position: center 30%;">
    <div class="absolute inset-0 bg-green-900/50 flex items-center justify-center">
        <div class="text-center text-white">
            <div class="text-5xl font-extrabold mb-2">{{ $totalMilestone }}</div>
            <div class="text-xl font-semibold text-green-200">تحليل طبي منذ التأسيس</div>
        </div>
    </div>
</div>

{{-- Values --}}
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-xs font-bold text-green-600 uppercase tracking-widest mb-3 block">مبادئنا</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">{{ __('site.about.values_title') }}</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach(__('site.about.values') as $value)
                <div class="bg-white rounded-2xl p-7 border border-slate-200 text-center hover:border-green-300 hover:shadow-lg transition-all hover:-translate-y-0.5">
                    <div class="text-5xl mb-4">{{ $value['icon'] }}</div>
                    <h3 class="font-extrabold text-slate-900 mb-3 text-lg">{{ $value['title'] }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $value['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Why choose us --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="text-xs font-bold text-green-600 uppercase tracking-widest mb-3 block">تميّزنا</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-8">{{ __('site.about.why_title') }}</h2>
                <p class="text-slate-500 mb-8 text-base leading-relaxed">{{ __('site.about.why_subtitle') }}</p>
                <div class="space-y-5">
                    @foreach(__('site.about.why') as $item)
                        <div class="flex gap-5 p-5 bg-slate-50 rounded-2xl border border-slate-200 hover:border-green-200 hover:shadow-sm transition-all">
                            <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 flex items-center justify-center flex-shrink-0 text-xl">
                                {{ $item['icon'] }}
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 mb-1.5">{{ $item['title'] }}</h3>
                                <p class="text-sm text-slate-500 leading-relaxed">{{ $item['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="relative rounded-3xl overflow-hidden shadow-2xl h-[480px]"
                 style="background-image: url('https://images.unsplash.com/photo-1530026405186-ed1f139313f3?w=900&q=85&auto=format&fit=crop'); background-size: cover; background-position: center;">
                <div class="absolute inset-0 bg-gradient-to-t from-green-900/70 to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6">
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-green-700 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <div class="font-extrabold text-slate-900">ISO 15189 Certified</div>
                                <div class="text-xs text-slate-500">أعلى معايير الدقة في التحاليل الطبية</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Branded service packages --}}
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-xs font-bold text-green-600 uppercase tracking-widest mb-3 block">برامجنا المتخصصة</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">{{ __('site.about.branded_title') }}</h2>
            <p class="text-slate-500 mt-3 text-lg max-w-2xl mx-auto">{{ __('site.about.branded_subtitle') }}</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(__('site.about.branded') as $pkg)
                <div class="bg-white rounded-2xl p-7 border border-slate-200 hover:border-green-300 hover:shadow-lg transition-all">
                    <div class="text-4xl mb-4">{{ $pkg['icon'] }}</div>
                    <h3 class="font-extrabold text-slate-900 text-lg mb-3">{{ $pkg['title'] }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $pkg['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Certifications --}}
<section class="py-16 bg-green-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-sm font-bold text-green-300 uppercase tracking-widest mb-8">{{ __('site.about.certs_label') }}</p>
        <div class="flex flex-wrap justify-center gap-5 items-center">
            @foreach(__('site.about.certs') as $cert)
                <div class="px-7 py-3.5 bg-white/10 border-2 border-white/25 rounded-2xl text-white font-bold text-sm hover:bg-white/20 transition-colors backdrop-blur-sm">
                    ✓ {{ $cert }}
                </div>
            @endforeach
        </div>
        <div class="mt-10">
            <a href="{{ route(app()->getLocale() === 'ar' ? 'ar.booking' : 'booking') }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-white text-green-800 font-extrabold rounded-2xl hover:bg-green-50 transition-colors shadow-lg">
                {{ __('site.home.cta_button') }}
            </a>
        </div>
    </div>
</section>

@endsection
