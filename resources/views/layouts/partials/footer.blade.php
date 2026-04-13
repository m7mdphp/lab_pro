@php
    $locale = app()->getLocale();
    $isAr   = $locale === 'ar';

    $footerLinks = [
        ['route' => 'home',     'label' => __('site.nav.home')],
        ['route' => 'about',    'label' => __('site.nav.about')],
        ['route' => 'services', 'label' => __('site.nav.services')],
        ['route' => 'tests',    'label' => __('site.nav.tests')],
        ['route' => 'packages', 'label' => __('site.nav.packages')],
        ['route' => 'branches', 'label' => __('site.nav.branches')],
        ['route' => 'contact',  'label' => __('site.nav.contact')],
    ];
@endphp

<footer class="bg-slate-900 text-slate-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-2.5 mb-4">
                    <svg viewBox="0 0 50 50" width="38" height="38" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="25" cy="25" r="22" stroke="#4ade80" stroke-width="2.5"/>
                        <text x="25" y="31" font-family="'Arial Black',Arial,sans-serif" font-weight="900" font-size="13" fill="#4ade80" text-anchor="middle" letter-spacing="-0.5">MSH</text>
                    </svg>
                    <div class="leading-tight">
                        <div class="font-extrabold text-white text-sm leading-none tracking-tight">El-Sheikha Lab</div>
                        <div class="text-xs text-green-400 font-semibold leading-tight mt-0.5">معامل الشيخة</div>
                    </div>
                </div>
                <p class="text-sm text-slate-400 leading-relaxed">
                    {{ __('site.footer.tagline') }}
                </p>
            </div>

            {{-- Quick links --}}
            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">
                    {{ __('site.footer.quick_links') }}
                </h3>
                <ul class="space-y-2">
                    @foreach($footerLinks as $link)
                        @php $routeName = $isAr ? 'ar.' . $link['route'] : $link['route']; @endphp
                        <li>
                            <a href="{{ route($routeName) }}"
                               class="text-sm text-slate-400 hover:text-white transition-colors">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">
                    {{ __('site.footer.contact') }}
                </h3>
                <ul class="space-y-3 text-sm text-slate-400">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ __('site.contact.address') }}
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <a href="tel:+20800000000" class="hover:text-white transition-colors">19XXX</a>
                    </li>
                </ul>

                <a href="{{ route($isAr ? 'ar.booking' : 'booking') }}"
                   class="mt-6 inline-flex items-center px-5 py-2.5 bg-green-700 hover:bg-green-600 text-white text-sm font-semibold rounded-lg transition-colors">
                    {{ __('site.nav.book') }}
                </a>
            </div>
        </div>

        <div class="mt-12 pt-6 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-slate-500">
                &copy; {{ date('Y') }} معامل الشيخة للتحاليل الطبية. {{ __('site.footer.rights') }}
            </p>
            <div class="flex items-center gap-4">
                <a href="{{ $isAr ? '/' : '/ar' }}"
                   class="text-xs text-slate-500 hover:text-slate-300 transition-colors">
                    {{ $isAr ? 'English' : 'عربي' }}
                </a>
            </div>
        </div>
    </div>
</footer>
