// File: resources/js/app.js
// Purpose: Application entry with Alpine.js initialization and Laravel bootstrap.

import './bootstrap';

// Alpine.js initialization
import Alpine from 'alpinejs';

// Ensure theme from localStorage is applied immediately (no 'auto' option):
{ const stored = localStorage.getItem('theme'); if (stored === 'dark') { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); } }

// Inject small UI hover/focus styles for icon links (moved from Blade)
(function injectIconHoverStyles() {
    if (typeof document === 'undefined') return;
    if (document.getElementById('ui-icon-hover-styles')) return;
    const css = `
/* Subtle lift + slight clockwise rotation on hover/focus for icon links and small buttons */
.repo-link,
.social-link,
#themeToggleButton,
#langToggleButton,
.repo-link .repo-icon,
.social-link .repo-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transform-origin: center;
    transition: transform .18s cubic-bezier(.2,.9,.2,1), box-shadow .16s ease;
    will-change: transform;
}
.repo-link:hover,
.social-link:hover,
#themeToggleButton:hover,
#langToggleButton:hover,
.repo-link:focus-visible,
.social-link:focus-visible,
#themeToggleButton:focus-visible,
#langToggleButton:focus-visible {
    transform: translateY(-4px) rotate(4deg);
}
.repo-link:active,
.social-link:active,
#themeToggleButton:active,
#langToggleButton:active {
    transform: translateY(-1px) rotate(2deg);
}
.repo-link:focus-visible,
.social-link:focus-visible,
#themeToggleButton:focus-visible,
#langToggleButton:focus-visible {
    outline: none;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
}

/* Cursor: show pointer (hand with index) on devices with fine pointer (desktop) */
@media (hover: hover) and (pointer: fine) {
    #themeToggleButton, #langToggleButton {
        cursor: pointer;
    }
}
`;
    const el = document.createElement('style');
    el.id = 'ui-icon-hover-styles';
    el.appendChild(document.createTextNode(css));
    document.head.appendChild(el);
})();

// Language toggle: small globe button with FR/EN badge — share behavior/colors with theme toggle
(function initLangToggle() {
    try {
        const btn = document.getElementById('langToggleButton');
        if (!btn) return;

        const badge = btn.querySelector('.lang-badge');

        function readLang() {
            return (localStorage.getItem('siteLang') || 'FR');
        }

        function writeLang(l) {
            localStorage.setItem('siteLang', l);
        }

        function render() {
            const l = readLang();
            if (badge) badge.textContent = l;
            btn.setAttribute('aria-label', `Changer la langue — actuellement ${l}`);
        }

        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const current = readLang();
            const next = current === 'FR' ? 'EN' : 'FR';
            writeLang(next);
            render();
        });

        // keyboard accessibility
        btn.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                btn.click();
            }
        });

        // initial render
        render();
    } catch (err) {
        // ignore
    }
})();

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

function syncCvSelectorModalState() {
    const modal = document.getElementById('cv-selector-modal');
    if (!modal) return false;

    const shouldOpen = window.location.hash === '#cv-selector-modal';
    modal.style.display = shouldOpen ? 'flex' : 'none';
    modal.classList.toggle('hidden', !shouldOpen);
    modal.classList.toggle('flex', shouldOpen);
    modal.setAttribute('aria-hidden', shouldOpen ? 'false' : 'true');

    return shouldOpen;
}

window.openCvSelectorModal = function () {
    window.location.hash = '#cv-selector-modal';
    return true;
};

window.closeCvSelectorModal = function () {
    if (window.location.hash === '#cv-selector-modal') {
        window.location.hash = '';
        return;
    }
    syncCvSelectorModalState();
};

window.addEventListener('DOMContentLoaded', () => {
    syncCvSelectorModalState();
    document.querySelectorAll('[data-cv-open]').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            window.openCvSelectorModal();
        });
    });
});

window.addEventListener('hashchange', syncCvSelectorModalState);
window.addEventListener('load', syncCvSelectorModalState);

window.downloadSelectedCv = function (url) {
    if (!url) return;
    window.closeCvSelectorModal();
    try {
        const link = document.createElement('a');
        link.href = url;
        link.target = '_blank';
        link.rel = 'noopener noreferrer';
        link.download = '';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } catch (e) {
        window.location.assign(url);
    }
};

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        window.closeCvSelectorModal();
    }
});

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
        navLinks.forEach((link) => {
            link.classList.remove('text-[#1b1b18]', 'font-semibold');
        });
    }

    function setActive(id) {
        clearActive();
        const active = Array.from(navLinks).find((link) => link.getAttribute('href') === '#' + id);
        if (active) {
            active.classList.add('text-[#1b1b18]', 'font-semibold');
        }
    }

    if (navLinks.length && sections.length) {
        const observer = new IntersectionObserver((entries) => {
            const visibleEntries = entries
                .filter((entry) => entry.isIntersecting)
                .sort((a, b) => b.intersectionRatio - a.intersectionRatio);

            if (visibleEntries.length > 0) {
                setActive(visibleEntries[0].target.id);
            }
        }, {
            root: null,
            rootMargin: '-30% 0px -40% 0px',
            threshold: [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.8, 1]
        });

        sections.forEach((section) => observer.observe(section));

        const initialSection = Array.from(sections).find((section) => {
            const rect = section.getBoundingClientRect();
            return rect.top >= 0 && rect.top < window.innerHeight;
        });

        if (initialSection) {
            setActive(initialSection.id);
        }
    }

    // smooth-scroll behavior for nav links is left to CSS scroll-behavior; keep default handlers minimal
});

// Shim: copy all attributes named data-x-* to x-* so server-side HTML is W3C-valid
// while Alpine receives its expected attributes at runtime. Executed before
// Alpine.start() below so component initialization works as expected.
function applyDataXShim() {
    if (typeof document === 'undefined') return;
    try {
        document.querySelectorAll('*').forEach(el => {
            // copy attributes that start with data-x- or data-x-on- (events)
            Array.prototype.slice.call(el.attributes).forEach(attr => {
                if (!attr || typeof attr.name !== 'string') return;

                // special-case event handlers encoded as data-x-on-<event>
                if (attr.name.indexOf('data-x-on-') === 0) {
                    // convert to Alpine's x-on:<event> form (colon included)
                    const newName = attr.name.replace('data-x-on-', 'x-on:');
                    if (!el.hasAttribute(newName)) el.setAttribute(newName, attr.value);
                    return;
                }

                // generic data-x-* → x-* mapping (for x-data, x-init, x-ref, etc.)
                if (attr.name.indexOf('data-x-') === 0) {
                    const newName = attr.name.replace('data-x-', 'x-');
                    if (!el.hasAttribute(newName)) el.setAttribute(newName, attr.value);
                }
            });
        });
    } catch (e) {
        // silently ignore shim failures
    }
}

// Contact/CV actions: make them fixed on small screens when scrolled past their original top
(function () {
    // support legacy id 'contact-cv-actions' or new id 'home-contact-cv-icons'
    const target = document.getElementById('home-contact-cv-icons') || document.getElementById('contact-cv-actions');
    if (!target) return;

    let origRect = null;

    function recalc() {
        // Mobile browsers can emit resize events while scrolling.
        // If we recompute while fixed, we capture the fixed top (8px)
        // instead of the natural document position and the sticky can flicker/disappear.
        if (target.classList.contains('fixed') && target.__origTop !== undefined) {
            return;
        }
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

        // Pin to the top as soon as the original top reaches viewport top,
        // while preserving each icon column's horizontal alignment.
        target.style.top = '8px';
        if (origRect) {
            target.style.left = Math.round(Math.max(6, origRect.left)) + 'px';
        } else {
            target.style.left = '8px';
        }
        target.style.right = 'auto';
    }

    function removeFixed() {
        if (!target.classList.contains('fixed')) return;
        target.classList.remove('fixed');
        target.style.width = '';
        target.style.height = '';
        target.style.top = '';
        target.style.left = '';
        target.style.right = '';
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

// Theme toggle fallback: if Alpine isn't active for some reason, provide
// a small JS handler to ensure the button still toggles the theme class.
(function initThemeToggleFallback() {
    try {
        const btn = document.getElementById('themeToggleButton');
        if (!btn) return;

        btn.addEventListener('click', function (e) {
            // If Alpine is handling the event, prefer Alpine behaviour
            if (window.Alpine && btn.hasAttribute('x-on:click')) return;

            // Use the same cycle logic as the Alpine component
            const stored = localStorage.getItem('theme') || 'light';
            const order = ['light', 'dark'];
            let i = order.indexOf(stored);
            if (i === -1) i = 0;
            const next = order[(i + 1) % order.length];
            if (next === 'dark') document.documentElement.classList.add('dark'); else document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', next);
        });
    } catch (e) {
        // noop
    }
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

// Ensure any data-x-* attributes are copied to x-* before Alpine initializes
try { applyDataXShim(); } catch (e) { /* ignore shim failures */ }

// Initialize Alpine after we've registered data and listeners
window.Alpine = Alpine;
Alpine.start();

// Fix persistant highlight on the CV link by replacing the clicked link
// with a clone shortly after interaction. This removes any lingering
// :active/:focus/:hover visual state without changing CSS rules.
(function () {
    function replaceWithClone(el) {
        if (!el || !el.parentNode) return;
        try {
            const clone = el.cloneNode(true);
            // preserve id if present
            if (el.id) clone.id = el.id;
            // replace in DOM
            el.parentNode.replaceChild(clone, el);
            try { clone.blur && clone.blur(); } catch (e) { /* ignore */ }
            // After cloning, ensure any data-x-* attributes are applied and
            // Alpine re-initializes behaviour on the new node so interactions
            // (x-on, x-show, etc.) continue to work on the replaced node.
            try {
                if (typeof applyDataXShim === 'function') applyDataXShim();
                // Alpine exposes initTree in v3 — use it to init the newly cloned node.
                if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                    try { window.Alpine.initTree(clone); } catch (e) { /* ignore */ }
                }
            } catch (e) {
                // ignore failures; best-effort re-init only
            }
        } catch (e) {
            // ignore failures to clone
        }
    }

    function clearCvVisuals() {
        try {
            const links = document.querySelectorAll('a.social-link--cv, a.social-link.social-link--cv');
            links.forEach((a) => replaceWithClone(a));

            // briefly focus body to remove any focus ring
            try {
                const b = document.body;
                const had = b.hasAttribute('tabindex');
                const prev = b.getAttribute('tabindex');
                b.setAttribute('tabindex', '-1');
                b.focus();
                if (had) b.setAttribute('tabindex', prev); else b.removeAttribute('tabindex');
            } catch (e) { /* ignore */ }
        } catch (e) { /* ignore */ }
    }

    // Replace the clicked link shortly after the event so the browser can
    // open the target _blank, then we swap the node to clear visual state.
    document.addEventListener('click', (e) => {
        try {
            const a = e.target && e.target.closest && e.target.closest('a.social-link--cv, a.social-link.social-link--cv');
            if (!a) return;
            // replace shortly after click so the browser can open the target _blank,
            // then we swap the node to clear any lingering visual state.
            setTimeout(() => replaceWithClone(a), 120);
        } catch (err) { /* ignore */ }
    }, true);

    // Clean up when the page regains visibility or focus
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') clearCvVisuals();
    });
    window.addEventListener('focus', clearCvVisuals);
})();

// Ambient halo: small, non-intrusive background glow updated on scroll/mousemove
import './photoToggle';
import './halo';
