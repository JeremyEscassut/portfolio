// resources/js/halo.js
// Ambient halo / soft glow updated on scroll and mousemove.
// Respects prefers-reduced-motion.

(function () {
    if (typeof window === 'undefined' || !document.documentElement) return;

    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduced) {
        // Lower the intensity for reduced-motion users
        document.documentElement.style.setProperty('--halo-opacity', '0.02');
        return;
    }

    const root = document.documentElement;

    // Start halo centered-ish so it's visible and behaves the same before any scroll
    let lastX = window.innerWidth * 0.5;
    let lastY = window.innerHeight * 0.4; // start lower than top
    // targets start as percent strings so CSS initial state and JS are consistent
    let targetX = '50%';
    let targetY = '40%';

    // Update target based on scroll percentage (gives vertical movement)
    function onScroll() {
        const docH = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
        const maxScroll = Math.max(0, docH - window.innerHeight);
        const pct = maxScroll > 0 ? window.scrollY / maxScroll : 0;
        // halo Y moves between 40% (top) and 60% (bottom) - keeps it visible at start
        const baseY = 40;
        targetY = (baseY + pct * 20) + '%';
        // X gently shifts a bit with scroll for subtle motion
        targetX = (50 - pct * 6) + '%';
    }

    // Mouse adds subtle parallax
    function onMove(e) {
        const mx = (e.clientX / window.innerWidth - 0.5) * 20; // -10..10
        const my = (e.clientY / window.innerHeight - 0.5) * 12; // -6..6
        // convert to px offset from the percent base
        // we'll store as calc expressions for CSS: use pixel offsets
        // but to keep things simple, apply small pixel offsets to computed percent
        // Here we compute additional px and mix with percent via JS by converting target to px
        // We'll just nudge targetX/targetY via tiny pixel offsets appended in JS loop.
        // Save small values on window for rAF loop
        window.__haloMouseOffset = { mx, my };
    }

    // Smooth animation loop
    function animate() {
        // interpolate towards target (which may be percent string)
        // parse percent targets if needed
        const parsePercent = (v, fallback) => {
            if (typeof v === 'string' && v.endsWith('%')) return parseFloat(v) / 100;
            if (typeof v === 'number') return v;
            return fallback;
        };

        const tX = parsePercent(targetX, 0.5);
        const tY = parsePercent(targetY, 0.2);

        // convert to pixels for interpolation
        const targetPixelX = tX * window.innerWidth;
        const targetPixelY = tY * window.innerHeight;

        // small mouse offset
        const mo = window.__haloMouseOffset || { mx: 0, my: 0 };
        const offsetX = (mo.mx / 100) * window.innerWidth * 0.9; // small fraction
        const offsetY = (mo.my / 100) * window.innerHeight * 0.7;

        const finalTargetX = targetPixelX + offsetX;
        const finalTargetY = targetPixelY + offsetY;

        // lerp
        lastX += (finalTargetX - lastX) * 0.09;
        lastY += (finalTargetY - lastY) * 0.09;

        // write CSS variables in px
        root.style.setProperty('--halo-x', Math.round(lastX) + 'px');
        root.style.setProperty('--halo-y', Math.round(lastY) + 'px');

        requestAnimationFrame(animate);
    }

    // initialize
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('mousemove', onMove, { passive: true });

    requestAnimationFrame(animate);

    // expose a small API to toggle/adjust
    window.__halo = {
        setOpacity(v) { root.style.setProperty('--halo-opacity', '' + v); },
        disable() { root.style.setProperty('--halo-enabled', '0'); },
        enable() { root.style.setProperty('--halo-enabled', '1'); }
    };

})();
