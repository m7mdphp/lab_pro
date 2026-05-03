import './bootstrap';
import Alpine from 'alpinejs';
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/effect-fade';
import AOS from 'aos';
import 'aos/dist/aos.css';

/* ── Alpine ──────────────────────────────────────────────────────────── */
window.Alpine = Alpine;
Alpine.start();

/* ── Boot after DOM ready ────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 700,
        once: true,
        offset: 50,
        easing: 'ease-out-cubic',
    });

    initHeroSwiper();
});

/* ── Hero Swiper ─────────────────────────────────────────────────────── */
function initHeroSwiper() {
    if (!document.querySelector('.hero-swiper')) return;

    const swiper = new Swiper('.hero-swiper', {
        modules: [Navigation, Pagination, Autoplay, EffectFade],
        loop: true,
        speed: 1000,
        effect: 'fade',
        fadeEffect: { crossFade: true },
        autoplay: {
            delay: 6500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        navigation: {
            nextEl: '.hero-btn-next',
            prevEl: '.hero-btn-prev',
        },
        pagination: {
            el: '.hero-pagination',
            clickable: true,
        },
        on: {
            realIndexChange(s) {
                const el = document.querySelector('.hero-slide-num');
                if (el) el.textContent = String(s.realIndex + 1).padStart(2, '0');
            },
            autoplayTimeLeft(s, time, progress) {
                const fill = document.querySelector('.hero-progress-fill');
                if (fill) fill.style.transform = `scaleX(${1 - progress})`;
            },
        },
    });
}
