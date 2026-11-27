// File: resources/js/consent.js
// Analytics consent (CNIL-friendly): Google Analytics is only loaded after an
// explicit "Accepter". Refusing is one click, same weight as accepting. The
// choice is stored in localStorage (not a cookie) and asked again after 13 months.
(function () {
    const KEY = 'analytics-consent';
    const MAX_AGE_MS = 395 * 24 * 60 * 60 * 1000; // ~13 months
    const gaId = window.GA_MEASUREMENT_ID;

    function readChoice() {
        try {
            const raw = JSON.parse(localStorage.getItem(KEY) || 'null');
            if (!raw || !raw.value || Date.now() - raw.at > MAX_AGE_MS) return null;
            return raw.value;
        } catch (e) { return null; }
    }

    function saveChoice(value) {
        try { localStorage.setItem(KEY, JSON.stringify({ value, at: Date.now() })); } catch (e) { /* private mode */ }
    }

    function loadAnalytics() {
        if (!gaId) return;
        window['ga-disable-' + gaId] = false;
        if (window.__gaLoaded) return;
        window.__gaLoaded = true;
        window.dataLayer = window.dataLayer || [];
        window.gtag = function () { window.dataLayer.push(arguments); };
        window.gtag('js', new Date());
        window.gtag('config', gaId);
        const s = document.createElement('script');
        s.async = true;
        s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(gaId);
        document.head.appendChild(s);
    }

    function stopAnalytics() {
        if (gaId) window['ga-disable-' + gaId] = true;
        const host = window.location.hostname;
        const parts = host.split('.');
        const domains = ['', host, '.' + host];
        if (parts.length > 2) domains.push('.' + parts.slice(-2).join('.'));
        document.cookie.split(';')
            .map((c) => c.split('=')[0].trim())
            .filter((name) => name === '_ga' || name.startsWith('_ga_'))
            .forEach((name) => domains.forEach((d) => {
                document.cookie = name + '=; Max-Age=0; path=/' + (d ? '; domain=' + d : '');
            }));
    }

    // Keep fixed bottom-right controls (mobile section button) above the banner.
    function syncOffset(banner) {
        const h = banner && !banner.hidden ? banner.getBoundingClientRect().height + 12 : 0;
        document.documentElement.style.setProperty('--consent-offset', h + 'px');
    }

    function init() {
        const banner = document.getElementById('consent-banner');
        const choice = readChoice();
        if (choice === 'granted') loadAnalytics();
        if (!banner) return;
        if (choice === null) banner.hidden = false;
        syncOffset(banner);
        window.addEventListener('resize', () => syncOffset(banner));

        banner.querySelectorAll('[data-consent]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const value = btn.dataset.consent === 'granted' ? 'granted' : 'denied';
                saveChoice(value);
                banner.hidden = true;
                syncOffset(banner);
                if (value === 'granted') loadAnalytics(); else stopAnalytics();
            });
        });

        document.querySelectorAll('[data-consent-open]').forEach((el) => {
            el.addEventListener('click', (e) => {
                e.preventDefault();
                banner.hidden = false;
                syncOffset(banner);
                const first = banner.querySelector('[data-consent]');
                if (first) first.focus();
            });
        });
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
