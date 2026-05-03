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
        ['route' => 'prepare',  'label' => __('site.nav.prepare')],
        ['route' => 'contact',  'label' => __('site.nav.contact')],
    ];
@endphp

<footer class="bg-slate-900 text-slate-300">

    {{-- Main footer grid --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

            {{-- Brand column --}}
            <div class="lg:col-span-1">
                <a href="{{ route($isAr ? 'ar.home' : 'home') }}" class="inline-block mb-5">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="معامل الشيخة – El-Sheikha Lab"
                         class="h-20 w-auto object-contain bg-white rounded-xl p-2">
                </a>
                <p class="text-sm text-slate-400 leading-relaxed mb-5">
                    {{ __('site.footer.tagline') }}
                </p>
                {{-- Certifications --}}
                <div class="flex flex-wrap gap-2">
                    <span class="text-xs bg-green-900/60 border border-green-700/40 text-green-300 px-2.5 py-1 rounded-full font-semibold">✓ ISO 15189</span>
                    <span class="text-xs bg-green-900/60 border border-green-700/40 text-green-300 px-2.5 py-1 rounded-full font-semibold">✓ ISO 9001</span>
                </div>
            </div>

            {{-- Quick links --}}
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-5 pb-2 border-b border-slate-700">
                    {{ __('site.footer.quick_links') }}
                </h3>
                <ul class="space-y-2.5">
                    @foreach($footerLinks as $link)
                        @php $routeName = $isAr ? 'ar.' . $link['route'] : $link['route']; @endphp
                        <li>
                            <a href="{{ route($routeName) }}"
                               class="text-sm text-slate-400 hover:text-green-400 transition-colors flex items-center gap-2 group">
                                <svg class="w-3 h-3 text-green-600 group-hover:text-green-400 transition-colors flex-shrink-0" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-5 pb-2 border-b border-slate-700">
                    {{ __('site.footer.contact') }}
                </h3>
                <ul class="space-y-4 text-sm">
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-green-800/50 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="text-slate-400 leading-relaxed">{{ __('site.contact.address') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-green-800/50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <a href="tel:19XXX" class="text-slate-300 hover:text-green-400 transition-colors font-semibold tracking-wide">الخط الساخن: 19XXX</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-green-800/50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-slate-400">السبت – الخميس: 7 ص – 9 م</span>
                    </li>
                </ul>
            </div>

            {{-- CTA + social --}}
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-5 pb-2 border-b border-slate-700">
                    {{ __('site.nav.book') }}
                </h3>
                <p class="text-sm text-slate-400 mb-4 leading-relaxed">
                    احجز تحليلك الآن عبر الإنترنت أو تواصل معنا مباشرة.
                </p>
                <a href="{{ route($isAr ? 'ar.booking' : 'booking') }}"
                   class="inline-flex items-center gap-2 w-full justify-center px-5 py-3 bg-green-700 hover:bg-green-600 text-white text-sm font-bold rounded-xl transition-colors mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ __('site.nav.book') }}
                </a>
                {{-- Social --}}
                <div class="flex items-center gap-3 mt-2">
                    <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-green-800 flex items-center justify-center transition-colors group" aria-label="Facebook">
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-green-800 flex items-center justify-center transition-colors group" aria-label="Instagram">
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke-width="2" stroke-linecap="round"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-green-800 flex items-center justify-center transition-colors group" aria-label="WhatsApp">
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M11.5 2C6.25 2 2 6.25 2 11.5c0 1.85.51 3.58 1.39 5.06L2 22l5.58-1.38A9.46 9.46 0 0011.5 21C16.75 21 21 16.75 21 11.5S16.75 2 11.5 2z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom bar --}}
    <div class="border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-slate-500 text-center">
                &copy; {{ date('Y') }} معامل الشيخة للتحاليل الطبية — El-Sheikha Lab. {{ __('site.footer.rights') }}
            </p>
            <div class="flex items-center gap-4">
                <a href="{{ $isAr ? '/' : '/ar' }}"
                   class="text-xs text-slate-500 hover:text-green-400 transition-colors font-medium">
                    {{ $isAr ? 'English' : 'عربي' }}
                </a>
                <span class="text-slate-700">|</span>
                <a href="{{ route($isAr ? 'ar.prepare' : 'prepare') }}" class="text-xs text-slate-500 hover:text-green-400 transition-colors">
                    {{ __('site.nav.prepare') }}
                </a>
            </div>
        </div>
    </div>
</footer>
