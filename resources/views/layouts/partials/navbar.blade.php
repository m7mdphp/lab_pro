@php
    $locale  = app()->getLocale();
    $isAr    = $locale === 'ar';
    $path    = request()->path();

    if ($isAr) {
        $stripped = ltrim(preg_replace('#^ar/?#', '', $path), '/');
        $altUrl   = $stripped === '' ? '/' : "/{$stripped}";
        $altLabel = 'English';
    } else {
        $stripped = ltrim($path, '/');
        $altUrl   = $stripped === '' || $stripped === '/' ? '/ar' : "/ar/{$stripped}";
        $altLabel = 'عربي';
    }

    $navLinks = [
        ['route' => 'home',     'label' => __('site.nav.home')],
        ['route' => 'about',    'label' => __('site.nav.about')],
        ['route' => 'services', 'label' => __('site.nav.services')],
        ['route' => 'tests',    'label' => __('site.nav.tests')],
        ['route' => 'packages', 'label' => __('site.nav.packages')],
        ['route' => 'prepare',  'label' => __('site.nav.prepare')],
        ['route' => 'branches', 'label' => __('site.nav.branches')],
        ['route' => 'partners', 'label' => __('site.nav.partners')],
        ['route' => 'contact',  'label' => __('site.nav.contact')],
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
                    <a href="tel:19XXX" class="hover:text-green-200 transition-colors font-semibold">الخط الساخن: 19XXX</a>
                </span>
                <span class="text-green-300">|</span>
                <span>السبت – الخميس: 7 ص – 9 م</span>
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                4 فروع في مصر — ISO 15189 معتمد
            </span>
        </div>
    </div>

    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-18 py-2">

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
                       class="px-3 py-2 rounded-md text-sm font-semibold transition-colors
                              {{ request()->routeIs($isAr ? 'ar.' . $link['route'] : $link['route'])
                                  ? 'text-green-700 bg-green-50'
                                  : 'text-slate-600 hover:text-green-700 hover:bg-green-50' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- CTA + lang --}}
            <div class="hidden lg:flex items-center gap-3">
                <a href="{{ $altUrl }}"
                   class="text-sm font-semibold text-slate-500 hover:text-green-700 transition-colors border border-slate-200 hover:border-green-300 px-3 py-1.5 rounded-lg">
                    {{ $altLabel }}
                </a>
                <a href="{{ route($isAr ? 'ar.booking' : 'booking') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-700 hover:bg-green-800 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
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
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center gap-3">
                <a href="{{ $altUrl }}" class="text-sm font-semibold text-slate-500 border border-slate-200 px-3 py-2 rounded-lg hover:border-green-300 hover:text-green-700 transition-colors">{{ $altLabel }}</a>
                <a href="{{ route($isAr ? 'ar.booking' : 'booking') }}"
                   class="flex-1 text-center px-4 py-2.5 bg-green-700 text-white text-sm font-bold rounded-xl">
                    {{ __('site.nav.book') }}
                </a>
            </div>
        </div>
    </nav>
</header>
