// File: resources/js/sectionFab.js
// Purpose: mobile/tablet "jump to section" button (markup in home.blade.php, #section-fab).
//
// Behaviour
// - Hidden on desktop (the aside nav is visible there) and near the top of the page.
//   It appears when <html> gets the `is-past-actions` class, which app.js toggles at
//   the same scroll position where the Contact/CV icons get pinned.
// - The toggle is a hexagon; the section links open as a quarter-circle fan:
//   the top link sits right above the button, each following link is placed lower
//   and further to the left, the last one ends up level with the button.
// - Choosing a section closes the fan and glides to it (see `glideTo`).
// - Closes on link selection, on backdrop tap and on Escape; focus moves to the
//   first link on open and back to the toggle on close.
// - The link of the section currently in view gets aria-current="true"
//   (see `markCurrentSection`, called from the section observer in app.js).

const ITEM_STEP = 52; // vertical distance between two links (44px target + 8px gap)
const EDGE = 16; // minimum distance between a link and the screen edge

(function initSectionFab() {
    const root = document.getElementById('section-fab');
    if (!root) return;

    const toggle = root.querySelector('.section-fab__toggle');
    const menu = root.querySelector('.section-fab__menu');
    const backdrop = root.querySelector('.section-fab__backdrop');
    const items = Array.from(menu.querySelectorAll('.section-fab__item'));
    const links = items.map((item) => item.querySelector('a'));

    /**
     * Place every link on a quarter ellipse centred on the toggle.
     *
     * Each link gets two CSS custom properties read by app.css:
     *   --dy : how far above the toggle's centre the link sits (px)
     *   --dx : how far left of the toggle's right edge the link ends (px)
     *
     * Links are spread with equal vertical steps (so they never overlap), then the
     * horizontal offset follows the ellipse x = rx * sqrt(1 - (dy / ry)^2):
     * 0 for the top link, close to rx for the bottom one.
     *
     * rx is as large as ry (a true quarter circle) when the screen is wide enough,
     * otherwise it shrinks so that the widest offset link still fits on screen.
     */
    function layout() {
        const count = items.length;
        const toggleRect = toggle.getBoundingClientRect();
        const step = Math.min(ITEM_STEP, (window.innerHeight - toggleRect.height - 2 * EDGE) / count);
        const ry = count * step;

        // dy for link i (0 = top): count*step, (count-1)*step, ..., 1*step
        const offsets = items.map((_, i) => {
            const dy = (count - i) * step;
            const spread = Math.sqrt(Math.max(0, 1 - (dy / ry) ** 2));
            return { dy, spread };
        });

        // Largest rx that keeps every link inside the screen:
        // right edge of the toggle - rx * spread - link width >= EDGE
        const available = toggleRect.right - EDGE;
        let rx = ry;
        offsets.forEach(({ spread }, i) => {
            if (spread === 0) return;
            const width = links[i].offsetWidth;
            rx = Math.min(rx, (available - width) / spread);
        });
        rx = Math.max(0, rx);

        items.forEach((item, i) => {
            item.style.setProperty('--dy', `${offsets[i].dy.toFixed(1)}px`);
            item.style.setProperty('--dx', `${(rx * offsets[i].spread).toFixed(1)}px`);
        });
    }

    function isOpen() {
        return root.classList.contains('is-open');
    }

    function open() {
        layout();
        root.classList.add('is-open');
        toggle.setAttribute('aria-expanded', 'true');
        toggle.setAttribute('aria-label', toggle.dataset.labelClose);
        backdrop.hidden = false;
        // Wait for the menu to become visible (visibility transition) before focusing.
        requestAnimationFrame(() => links[0] && links[0].focus({ preventScroll: true }));
    }

    function close({ restoreFocus = true } = {}) {
        if (!isOpen()) return;
        root.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', toggle.dataset.labelOpen);
        backdrop.hidden = true;
        if (restoreFocus) toggle.focus({ preventScroll: true });
    }

    toggle.addEventListener('click', () => (isOpen() ? close() : open()));
    backdrop.addEventListener('click', () => close());

    // Selecting a section: close the fan, then glide to the section so the visitor
    // sees the page travel up or down instead of jumping.
    links.forEach((link) => link.addEventListener('click', (event) => {
        const target = document.querySelector(link.getAttribute('href'));
        if (!target) return; // unknown anchor: default browser behaviour
        event.preventDefault();
        close({ restoreFocus: false });
        glideTo(target);
    }));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isOpen()) close();
    });

    // Fan positions depend on the screen size and on the label widths (language).
    window.addEventListener('resize', () => { if (isOpen()) layout(); });

    // The button disappears when scrolling back to the top: never leave the fan open.
    new MutationObserver(() => {
        if (!document.documentElement.classList.contains('is-past-actions')) close({ restoreFocus: false });
    }).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

    /**
     * Animated scroll to a section.
     *
     * - Duration grows with the distance (a short hop is quick, crossing the whole
     *   page takes a bit longer) but stays between 350 and 900 ms.
     * - easeInOutCubic: slow start, fast middle, soft landing, which reads as
     *   "moving up/down through the page".
     * - The section's scroll-margin-top (scroll-mt-24) is respected, so the title lands
     *   below the sticky section banner, exactly like a normal anchor jump.
     * - Reduced motion: jump instantly. Any wheel/touch/key input cancels the glide.
     * - Afterwards the URL hash is updated and focus moves to the section (for keyboard
     *   and screen reader users) without scrolling again.
     */
    function glideTo(section) {
        const margin = parseFloat(getComputedStyle(section).scrollMarginTop) || 0;
        const start = window.scrollY;
        const end = Math.max(0, section.getBoundingClientRect().top + start - margin);
        const distance = end - start;

        const finish = () => {
            history.pushState(null, '', '#' + section.id);
            if (!section.hasAttribute('tabindex')) section.setAttribute('tabindex', '-1');
            section.focus({ preventScroll: true });
        };

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || Math.abs(distance) < 2) {
            window.scrollTo(0, end);
            finish();
            return;
        }

        const duration = Math.min(900, Math.max(350, Math.abs(distance) * 0.35));
        const ease = (t) => (t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2);
        let startTime = null;
        let cancelled = false;
        const cancel = () => { cancelled = true; };
        const stopEvents = ['wheel', 'touchstart', 'keydown'];
        stopEvents.forEach((name) => window.addEventListener(name, cancel, { passive: true, once: true }));

        const step = (now) => {
            if (cancelled) return;
            if (startTime === null) startTime = now;
            const progress = Math.min(1, (now - startTime) / duration);
            window.scrollTo(0, start + distance * ease(progress));
            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                stopEvents.forEach((name) => window.removeEventListener(name, cancel));
                finish();
            }
        };
        requestAnimationFrame(step);
    }

    /** Highlight the link of the section in view (called by app.js). */
    window.markSectionFabCurrent = function markCurrentSection(sectionId) {
        links.forEach((link) => {
            if (link.getAttribute('href') === '#' + sectionId) link.setAttribute('aria-current', 'true');
            else link.removeAttribute('aria-current');
        });
    };
})();
