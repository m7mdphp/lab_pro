@php
    $locale  = app()->getLocale();
    $isAr    = $locale === 'ar';
    $path    = request()->path();

    // Build alternate-locale URL
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
        ['route' => 'branches', 'label' => __('site.nav.branches')],
        ['route' => 'contact',  'label' => __('site.nav.contact')],
    ];
@endphp

<header
    x-data="{ open: false, scrolled: false }"
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 10 })"
    :class="scrolled ? 'shadow-md bg-white' : 'bg-white'"
    class="sticky top-0 z-50 transition-shadow duration-200"
>
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route($isAr ? 'ar.home' : 'home') }}" class="flex items-center gap-2 flex-shrink-0">
                <div class="w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-sm">A</span>
                </div>
                <span class="font-bold text-lg text-blue-900 tracking-tight">AccuLab</span>
            </a>

            {{-- Desktop nav --}}
            <div class="hidden lg:flex items-center gap-1">
                @foreach($navLinks as $link)
                    @php $routeName = $isAr ? 'ar.' . $link['route'] : $link['route']; @endphp
                    <a href="{{ route($routeName) }}"
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                              {{ request()->routeIs($isAr ? 'ar.' . $link['route'] : $link['route'])
                                  ? 'text-blue-700 bg-blue-50'
                                  : 'text-slate-600 hover:text-blue-700 hover:bg-slate-50' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- CTA + lang switcher --}}
            <div class="hidden lg:flex items-center gap-3">
                <a href="{{ $altUrl }}"
                   class="text-sm font-medium text-slate-500 hover:text-blue-700 transition-colors px-2 py-1 rounded">
                    {{ $altLabel }}
                </a>
                <a href="{{ route($isAr ? 'ar.booking' : 'booking') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-lg transition-colors">
                    {{ __('site.nav.book') }}
                </a>
            </div>

            {{-- Mobile hamburger --}}
            <button @click="open = !open"
                    class="lg:hidden p-2 rounded-md text-slate-500 hover:text-blue-700 hover:bg-slate-100"
                    aria-label="Toggle menu">
                <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
             class="lg:hidden pb-4 border-t border-slate-100 mt-2 pt-3">
            @foreach($navLinks as $link)
                @php $routeName = $isAr ? 'ar.' . $link['route'] : $link['route']; @endphp
                <a href="{{ route($routeName) }}"
                   class="block px-3 py-2 rounded-md text-sm font-medium text-slate-600 hover:text-blue-700 hover:bg-slate-50">
                    {{ $link['label'] }}
                </a>
            @endforeach
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center gap-3">
                <a href="{{ $altUrl }}" class="text-sm text-slate-500 hover:text-blue-700 font-medium">{{ $altLabel }}</a>
                <a href="{{ route($isAr ? 'ar.booking' : 'booking') }}"
                   class="flex-1 text-center px-4 py-2 bg-blue-700 text-white text-sm font-semibold rounded-lg">
                    {{ __('site.nav.book') }}
                </a>
            </div>
        </div>
    </nav>
</header>
