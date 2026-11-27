// File: resources/js/theme.js
// Purpose: light/dark theme. The site follows the system setting by default; the
// toggle button (#themeToggleButton) lets the visitor force the other theme.
//
// - The first paint is handled by the inline script in layouts/app.blade.php
//   (it sets <html class="dark"> before the CSS renders, so there is no white flash).
// - Stored value in localStorage 'theme': none = follow the system, 'light' or 'dark' = forced.
// - Toggling picks the opposite of what is on screen. If that matches the system
//   setting, the stored value is removed (back to "follow the system") instead of
//   pinning it, so the site keeps following later system changes.
// - A stored value is kept even if the system later switches to match it.
// - Every change dispatches a window 'theme-change' event (detail: 'light' | 'dark'),
//   used by the Alpine state on #home to swap the button icon.

const KEY = 'theme';
const systemDark = window.matchMedia('(prefers-color-scheme: dark)');

function storedTheme() {
    try {
        const value = localStorage.getItem(KEY);
        return value === 'dark' || value === 'light' ? value : null;
    } catch (e) {
        return null; // site data blocked: the theme still works, it is just not remembered
    }
}

function systemTheme() {
    return systemDark.matches ? 'dark' : 'light';
}

export function currentTheme() {
    return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
}

function applyTheme(theme) {
    document.documentElement.classList.toggle('dark', theme === 'dark');
    window.dispatchEvent(new CustomEvent('theme-change', { detail: theme }));
}

export function toggleTheme() {
    const target = currentTheme() === 'dark' ? 'light' : 'dark';
    try {
        if (target === systemTheme()) localStorage.removeItem(KEY);
        else localStorage.setItem(KEY, target);
    } catch (e) { /* not persisted, still applied */ }
    applyTheme(target);
    return target;
}

// Follow the system while nothing is forced.
systemDark.addEventListener('change', () => {
    if (!storedTheme()) applyTheme(systemTheme());
});

// Another tab changed the choice.
window.addEventListener('storage', (event) => {
    if (event.key === KEY) applyTheme(storedTheme() || systemTheme());
});

window.toggleTheme = toggleTheme;
window.currentTheme = currentTheme;
