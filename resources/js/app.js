// File: resources/js/app.js
// Purpose: Application entry with Alpine.js initialization and Laravel bootstrap.

import './bootstrap';

// Alpine.js initialization
import Alpine from 'alpinejs';

// Ensure theme from localStorage is applied immediately (no 'auto' option):
{ const stored = localStorage.getItem('theme'); if (stored === 'dark') { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); } }

// Register contact modal factory and other small page behaviors here so markup can remain clean.
// We keep a window.contactModal factory for compatibility with existing x-data="contactModal()" usage.
window.contactModal = function () {
    return {
        isOpen: false,
        lastActive: null,
        openModal() {
            this.lastActive = document.activeElement;
            this.isOpen = true;
            this.$nextTick(() => {
                if (this.$refs && this.$refs.email) this.$refs.email.focus();
            });
            document.addEventListener('keydown', this._escHandler = (e) => {
                if (e.key === 'Escape') this.closeModal();
            });
        },
        closeModal() {
            this.isOpen = false;
            document.removeEventListener('keydown', this._escHandler);
            this.$nextTick(() => {
                if (this.lastActive && typeof this.lastActive.focus === 'function') this.lastActive.focus();
            });
        }
    }
}

// Fallback global listener to ensure the contact modal opens even if Alpine scope issues occur
window.addEventListener('open-contact', function _openContactFallback() {
    function tryOpen() {
        const el = document.querySelector('[x-data="contactModal()"]');
        if (!el) return false;
        try {
            if (el.__x && el.__x.$data) {
                el.__x.$data.isOpen = true;
                setTimeout(() => {
                    try {
                        const email = el.querySelector('[x-ref="email"]');
                        if (email && typeof email.focus === 'function') email.focus();
                    } catch (e) {
                        // ignore
                    }
                }, 40);
                return true;
            }
        } catch (e) {
            // ignore
        }
        return false;
    }

    if (!tryOpen()) {
        setTimeout(tryOpen, 50);
        setTimeout(tryOpen, 200);
    }
});

// Page-specific UI helpers moved from home.blade.php
document.addEventListener('alpine:init', () => {
    // highlight active section in left nav using IntersectionObserver
    const navLinks = document.querySelectorAll('aside nav a');
    const sections = document.querySelectorAll('main#content > section[id]');

    function clearActive() {
        navLinks.forEach(a => a.classList.remove('text-[#1b1b18]', 'font-semibold'));
    }

    if (sections && navLinks) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.id;
                    clearActive();
                    const active = Array.from(navLinks).find(a => a.getAttribute('href') === '#' + id);
                    if (active) active.classList.add('text-[#1b1b18]', 'font-semibold');
                }
            });
        }, { root: null, rootMargin: '0px', threshold: 0.6 });

        sections.forEach(s => observer.observe(s));
    }

    // smooth-scroll behavior for nav links is left to CSS scroll-behavior; keep default handlers minimal
});

// Contact/CV actions: make them fixed on small screens when scrolled past their original top
(function () {
    // support legacy id 'contact-cv-actions' or new id 'home-contact-cv-icons'
    const target = document.getElementById('home-contact-cv-icons') || document.getElementById('contact-cv-actions');
    if (!target) return;

    let origRect = null;

    function recalc() {
        origRect = target.getBoundingClientRect();
        target.__origTop = origRect.top + window.scrollY;
    }

    function applyFixed() {
        if (target.classList.contains('fixed')) return;
        const w = target.offsetWidth;
        const h = target.offsetHeight;
        target.style.width = w + 'px';
        target.style.height = h + 'px';
        target.classList.add('fixed');
    }

    function removeFixed() {
        if (!target.classList.contains('fixed')) return;
        target.classList.remove('fixed');
        target.style.width = '';
        target.style.height = '';
    }

    function onScroll() {
        if (window.innerWidth > 640) { removeFixed(); return; }
        if (origRect === null) recalc();
        if (window.scrollY > target.__origTop) applyFixed(); else removeFixed();
    }

    window.addEventListener('resize', () => { recalc(); onScroll(); });
    window.addEventListener('orientationchange', () => { recalc(); onScroll(); });
    window.addEventListener('load', () => { recalc(); onScroll(); });
    window.addEventListener('scroll', onScroll, { passive: true });
    setTimeout(() => { recalc(); onScroll(); }, 120);
})();

// Ensure footer reserved space on mobile when it becomes stuck
(function () {
    // support legacy id 'right-footer' or new id 'footer'
    const footer = document.getElementById('footer') || document.getElementById('right-footer');
    const main = document.querySelector('main#content') || document.querySelector('main');
    if (!footer || !main) return;

    function applyFooterPadding() {
        if (window.innerWidth > 640) { main.style.paddingBottom = ''; return; }
        if (footer.classList.contains('stuck')) {
            const rect = footer.getBoundingClientRect();
            const height = Math.ceil(rect.height);
            main.style.paddingBottom = height + 'px';
        } else { main.style.paddingBottom = ''; }
    }

    const observer = new MutationObserver(mutations => {
        for (const m of mutations) {
            if (m.type === 'attributes' && m.attributeName === 'class') applyFooterPadding();
        }
    });

    observer.observe(footer, { attributes: true, attributeFilter: ['class'] });
    window.addEventListener('resize', applyFooterPadding, { passive: true });
    window.addEventListener('load', applyFooterPadding);
    window.addEventListener('orientationchange', applyFooterPadding);
    setTimeout(applyFooterPadding, 60);
})();

// Initialize Alpine after we've registered data and listeners
window.Alpine = Alpine;
Alpine.start();

// Ambient halo: small, non-intrusive background glow updated on scroll/mousemove
import './halo';
