@php
    use App\Models\SiteSetting;
    $locale  = app()->getLocale();
    $isAr    = $locale === 'ar';
    $path    = request()->path();
    $hotline      = SiteSetting::get('hotline', '19XXX');
    $workingHours = SiteSetting::get($isAr ? 'working_hours_ar' : 'working_hours_en', 'السبت – الخميس: 7 ص – 9 م');

    if ($isAr) {
        $stripped = ltrim(preg_replace('#^ar/?#', '', $path), '/');
        $altUrl   = $stripped === '' ? '/' : "/{$stripped}";
        $altLabel = 'EN';
    } else {
        $stripped = ltrim($path, '/');
        $altUrl   = $stripped === '' || $stripped === '/' ? '/ar' : "/ar/{$stripped}";
        $altLabel = 'ع';
    }

    $navLinks = [
        ['route' => 'home',     'label' => __('site.nav.home')],
        ['route' => 'about',    'label' => __('site.nav.about')],
        ['route' => 'services', 'label' => __('site.nav.services')],
        ['route' => 'tests',    'label' => __('site.nav.tests')],
        ['route' => 'packages', 'label' => __('site.nav.packages')],
        ['route' => 'branches', 'label' => __('site.nav.branches')],
        ['route' => 'blog',     'label' => __('site.nav.blog')],
        ['route' => 'contact',  'label' => __('site.nav.contact')],
    ];

    // "More" dropdown items
    $moreLinks = [
        ['route' => 'prepare',            'label' => __('site.nav.prepare'),            'icon' => '🧪'],
        ['route' => 'team',               'label' => __('site.nav.team'),               'icon' => '👨‍⚕️'],
        ['route' => 'partners',           'label' => __('site.nav.partners'),           'icon' => '🤝'],
        ['route' => 'doctor-services',    'label' => __('site.nav.doctor_services'),    'icon' => '👨‍⚕️'],
        ['route' => 'corporate-services', 'label' => __('site.nav.corporate_services'), 'icon' => '🏢'],
    ];
@endphp

<header
    x-data="{ open: false, scrolled: false }"
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 10 })"
    :class="scrolled ? 'shadow-lg' : 'shadow-sm'"
    class="sticky top-0 z-50 bg-white border-b border-slate-100 transition-shadow duration-200"
>
    {{-- Top bar --}}
    <div class="bg-green-800 text-white text-xs py-1.5 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <span class="flex items-center gap-4">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <a href="tel:{{ $hotline }}" class="hover:text-green-200 transition-colors font-semibold">{{ $hotline }}</a>
                </span>
                <span class="text-green-300">|</span>
                <span>{{ $workingHours }}</span>
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ SiteSetting::get('branches_count', '4') }} {{ $isAr ? 'فروع في مصر — ISO 15189 معتمد' : 'Branches in Egypt — ISO 15189 Certified' }}
            </span>
        </div>
    </div>

    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-2">

            {{-- Logo --}}
            <a href="{{ route($isAr ? 'ar.home' : 'home') }}" class="flex-shrink-0 flex items-center">
                <img src="{{ asset('images/logo.png') }}"
                     alt="معامل الشيخة للتحاليل الطبية – El-Sheikha Lab"
                     class="h-14 w-auto object-contain"
                     loading="eager">
            </a>

            {{-- Desktop nav --}}
            <div class="hidden lg:flex items-center gap-0.5">
                @foreach($navLinks as $link)
                    @php $routeName = $isAr ? 'ar.' . $link['route'] : $link['route']; @endphp
                    <a href="{{ route($routeName) }}"
                       class="px-2.5 py-1.5 rounded-lg text-sm font-semibold transition-colors whitespace-nowrap
                              {{ request()->routeIs($isAr ? 'ar.' . $link['route'] : $link['route'])
                                  ? 'text-green-700 bg-green-50'
                                  : 'text-slate-600 hover:text-green-700 hover:bg-green-50' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- CTA + lang --}}
            <div class="hidden lg:flex items-center gap-2">
                {{-- More dropdown --}}
                <div class="relative" x-data="{ moreOpen: false }" @mouseleave="moreOpen = false">
                    <button @mouseenter="moreOpen = true" @click="moreOpen = !moreOpen"
                            class="px-2.5 py-1.5 rounded-lg text-sm font-semibold text-slate-600 hover:text-green-700 hover:bg-green-50 transition-colors flex items-center gap-1">
                        {{ $isAr ? 'المزيد' : 'More' }}
                        <svg class="w-3 h-3 transition-transform" :class="moreOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="moreOpen"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute {{ $isAr ? 'left-0' : 'right-0' }} top-full mt-1 w-56 bg-white border border-slate-200 rounded-xl shadow-xl z-50 py-2"
                         @mouseenter="moreOpen = true" @mouseleave="moreOpen = false">
                        @foreach($moreLinks as $link)
                            @php $rn = $isAr ? 'ar.' . $link['route'] : $link['route']; @endphp
                            <a href="{{ route($rn) }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-600 hover:text-green-700 hover:bg-green-50 transition-colors">
                                <span class="text-base">{{ $link['icon'] }}</span>
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- My Results CTA --}}
                <a href="{{ route($isAr ? 'ar.results' : 'results') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 border-2 border-green-700 text-green-700 hover:bg-green-700 hover:text-white text-sm font-bold rounded-lg transition-colors whitespace-nowrap">
                    🔬 {{ __('site.nav.results') }}
                </a>

                <a href="{{ $altUrl }}"
                   class="text-sm font-semibold text-slate-500 hover:text-green-700 transition-colors border border-slate-200 hover:border-green-300 px-2.5 py-1.5 rounded-lg min-w-[36px] text-center">
                    {{ $altLabel }}
                </a>
                <a href="{{ route($isAr ? 'ar.booking' : 'booking') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-700 hover:bg-green-800 text-white text-sm font-bold rounded-lg transition-colors shadow-sm whitespace-nowrap">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ __('site.nav.book') }}
                </a>
            </div>

            {{-- Mobile hamburger --}}
            <button @click="open = !open"
                    class="lg:hidden p-2 rounded-lg text-slate-500 hover:text-green-700 hover:bg-green-50 transition-colors"
                    aria-label="Toggle menu">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div x-show="open"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="lg:hidden pb-4 border-t border-slate-100 mt-1 pt-3 space-y-0.5">
            @foreach($navLinks as $link)
                @php $routeName = $isAr ? 'ar.' . $link['route'] : $link['route']; @endphp
                <a href="{{ route($routeName) }}"
                   class="block px-3 py-2.5 rounded-lg text-sm font-semibold
                          {{ request()->routeIs($routeName) ? 'text-green-700 bg-green-50' : 'text-slate-600 hover:text-green-700 hover:bg-green-50' }}
                          transition-colors">
                    {{ $link['label'] }}
                </a>
            @endforeach
            {{-- More links in mobile --}}
            <div class="pt-2 border-t border-slate-100">
                @foreach($moreLinks as $link)
                    @php $rn = $isAr ? 'ar.' . $link['route'] : $link['route']; @endphp
                    <a href="{{ route($rn) }}"
                       class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-semibold text-slate-500 hover:text-green-700 hover:bg-green-50 transition-colors">
                        <span>{{ $link['icon'] }}</span>{{ $link['label'] }}
                    </a>
                @endforeach
                <a href="{{ route($isAr ? 'ar.results' : 'results') }}"
                   class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-bold text-green-700 bg-green-50 hover:bg-green-100 transition-colors">
                    🔬 {{ __('site.nav.results') }}
                </a>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center gap-3">
                <a href="{{ $altUrl }}" class="text-sm font-semibold text-slate-500 border border-slate-200 px-3 py-2 rounded-lg hover:border-green-300 hover:text-green-700 transition-colors">{{ $altLabel }}</a>
                <a href="{{ route($isAr ? 'ar.booking' : 'booking') }}"
                   class="flex-1 text-center px-4 py-2.5 bg-green-700 text-white text-sm font-bold rounded-lg">
                    {{ __('site.nav.book') }}
                </a>
            </div>
        </div>
    </nav>
</header>
