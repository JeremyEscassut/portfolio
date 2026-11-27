// File: resources/js/carousel.js
// Purpose: Admiral screenshots carousel (markup in home.blade.php, [data-carousel]).
//
// The track is a native horizontal scroller with scroll snap (app.css, "Carousel"):
// swiping, trackpads and the keyboard (arrow keys once the track has focus) work without
// this file, and every screenshot is in the HTML. This file only:
// - wires the previous/next arrows (looping from the last slide to the first) and the dots;
// - keeps the active dot in sync with the slide in view (aria-current + .is-active);
// - announces "Capture 2 sur 3" in the role="status" region once a scroll settles,
//   whatever started it (arrows, dots, swipe). Nothing is announced on page load.

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

function initCarousel(root) {
    const track = root.querySelector('.carousel__track');
    const slides = Array.from(track.children);
    const dots = Array.from(root.querySelectorAll('.carousel__dot'));
    const status = root.querySelector('[data-carousel-status]');
    if (!slides.length) return;

    let current = 0;
    let userScrolled = false;

    const indexInView = () => Math.round(track.scrollLeft / Math.max(1, track.clientWidth));

    function goTo(index) {
        const target = (index + slides.length) % slides.length;
        userScrolled = true;
        track.scrollTo({ left: target * track.clientWidth, behavior: reducedMotion.matches ? 'auto' : 'smooth' });
    }

    function sync() {
        current = Math.min(slides.length - 1, Math.max(0, indexInView()));
        dots.forEach((dot, i) => {
            const active = i === current;
            dot.classList.toggle('is-active', active);
            dot.setAttribute('aria-current', active ? 'true' : 'false');
        });
        if (userScrolled && status) status.textContent = slides[current].getAttribute('aria-label') || '';
    }

    root.querySelector('[data-direction="previous"]')?.addEventListener('click', () => goTo(current - 1));
    root.querySelector('[data-direction="next"]')?.addEventListener('click', () => goTo(current + 1));
    dots.forEach((dot) => dot.addEventListener('click', () => goTo(Number(dot.dataset.slide))));

    // Any scroll started by the visitor (swipe, trackpad, keyboard) counts.
    ['pointerdown', 'touchstart', 'wheel', 'keydown'].forEach((type) => {
        track.addEventListener(type, () => { userScrolled = true; }, { passive: true });
    });

    // Settled scroll: `scrollend` where available, otherwise a short debounce.
    if ('onscrollend' in window) {
        track.addEventListener('scrollend', sync);
    } else {
        let timer = null;
        track.addEventListener('scroll', () => {
            clearTimeout(timer);
            timer = setTimeout(sync, 120);
        }, { passive: true });
    }

    // Keep the same slide in view when the width changes (rotation, resize).
    window.addEventListener('resize', () => {
        track.scrollTo({ left: current * track.clientWidth, behavior: 'auto' });
    });

    sync();
}

function init() {
    document.querySelectorAll('[data-carousel]').forEach(initCarousel);
}

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
else init();
