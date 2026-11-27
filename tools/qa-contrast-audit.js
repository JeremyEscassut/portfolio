// File: tools/qa-contrast-audit.js
// Purpose: find interactive elements whose text/icon contrast is too low, in the
// normal, hover and focus-visible states (WCAG: 4.5:1 for text, 3:1 for icons).
//
// Usage (browser console or any devtools snippet, on a page of the site):
//   1. copy this file to public/ temporarily, or paste it in the console;
//   2. await eval(await fetch('/qa-contrast-audit.js').then(r => r.text()))
//   3. repeat with the dark theme (document.documentElement.classList.add('dark'))
//      and with the dialogs open (window.openCvSelectorModal(), 'open-contact' event).
// Returns a list of "state | ratio | text|icon | label | classes" lines; [] = all good.
// Limits: hover/focus rules are copied without their cascade layer, decorative
// SVGs (photo frame) and image-only links are reported or skipped as false positives.
(async () => {
  document.getElementById('qa-style')?.remove();
  const out = [];
  const collect = (rules) => {
    for (const r of rules) {
      if (r.media && r.cssRules) { if (matchMedia(r.media.mediaText).matches) collect(r.cssRules); continue; }
      if (r.cssRules && !r.selectorText) { collect(r.cssRules); continue; }
      if (!r.selectorText || !/:hover|:focus-visible/.test(r.selectorText)) continue;
      const sel = r.selectorText.replace(/:hover/g, '.qa-hover').replace(/:focus-visible/g, '.qa-focus');
      // Tailwind v4 nests declarations in @media (hover: hover) inside the rule
      const nested = [...(r.cssRules || [])].flatMap((m) => (m.conditionText && matchMedia(m.conditionText).matches) ? [...m.cssRules].map((d) => d.style?.cssText || '') : []);
      out.push(`${sel}{${r.style.cssText}${nested.join(';')}}`);
    }
  };
  for (const s of document.styleSheets) { try { collect(s.cssRules); } catch (e) {} }
  // No transitions: computed values must be the final hover/focus values
  out.push('*,*::before,*::after{transition:none!important;animation:none!important}');
  const st = document.createElement('style'); st.id = 'qa-style'; st.textContent = out.join('\n'); document.head.appendChild(st);

  const parse = (c) => { const m = c.match(/[\d.]+/g); if (!m) return null; const [r, g, b, a = 1] = m.map(Number); return { r, g, b, a: c.startsWith('color(srgb') ? (m[3] ?? 1) : a, srgb: c.startsWith('color(srgb') }; };
  const toRgb = (p) => !p ? null : p.srgb ? { r: p.r * 255, g: p.g * 255, b: p.b * 255, a: p.a } : p;
  const lum = ({ r, g, b }) => { const f = (v) => { v /= 255; return v <= 0.03928 ? v / 12.92 : ((v + 0.055) / 1.055) ** 2.4; }; return 0.2126 * f(r) + 0.7152 * f(g) + 0.0722 * f(b); };
  const ratio = (a, b) => { const [x, y] = [lum(a), lum(b)].sort((p, q) => q - p); return (x + 0.05) / (y + 0.05); };
  const blend = (top, bottom) => ({ r: top.r * top.a + bottom.r * (1 - top.a), g: top.g * top.a + bottom.g * (1 - top.a), b: top.b * top.a + bottom.b * (1 - top.a), a: 1 });

  function background(el) {
    const layers = [];
    const pa = toRgb(parse(getComputedStyle(el, '::after').backgroundColor) || { r: 0, g: 0, b: 0, a: 0 });
    if (getComputedStyle(el, '::after').content !== 'none' && pa && pa.a > 0) layers.push(pa);
    const pb = toRgb(parse(getComputedStyle(el, '::before').backgroundColor) || { r: 0, g: 0, b: 0, a: 0 });
    if (getComputedStyle(el, '::before').content !== 'none' && pb.a > 0) layers.push(pb);
    for (let n = el; n; n = n.parentElement) {
      const c = toRgb(parse(getComputedStyle(n).backgroundColor));
      if (c && c.a > 0) { layers.push(c); if (c.a >= 0.95) break; }
    }
    let base = toRgb(parse(getComputedStyle(document.body).backgroundColor));
    if (!base || base.a < 0.95) base = { r: 255, g: 255, b: 255, a: 1 };
    return layers.reverse().reduce((acc, l) => blend(l, acc), base);
  }
  function foreground(el) {
    const hasText = [...el.childNodes].some((n) => n.nodeType === 3 && n.textContent.trim()) || el.querySelector('span:not(.lang-badge)')?.textContent.trim();
    if (!hasText) {
      const shape = el.querySelector('svg path, svg circle, svg rect');
      if (shape) { const cs = getComputedStyle(shape); const s = cs.stroke !== 'none' ? cs.stroke : cs.fill; return { c: toRgb(parse(s)), kind: 'icon' }; }
      if (el.querySelector('img')) return null;
    }
    return { c: toRgb(parse(getComputedStyle(el).color)), kind: 'text' };
  }

  const items = [...document.querySelectorAll('a, button, [role="button"], .project-tag')]
    .filter((el) => !el.classList.contains('carousel__dot'))
    .filter((el) => { const r = el.getBoundingClientRect(); const cs = getComputedStyle(el); return r.width > 0 && r.height > 0 && cs.visibility !== 'hidden' && cs.display !== 'none'; });
  const problems = [];
  for (const el of items) {
    for (const state of ['normal', 'qa-hover', 'qa-focus']) {
      if (state !== 'normal') el.classList.add(state);
      const fg = foreground(el);
      if (fg && fg.c) {
        const bg = background(el);
        const fgc = fg.c.a < 1 ? blend(fg.c, bg) : fg.c;
        const cr = ratio(fgc, bg);
        const min = fg.kind === 'text' ? 4.5 : 3;
        if (cr < min) problems.push(`${state.replace('qa-', '')} | ${cr.toFixed(2)} | ${fg.kind} | ${(el.getAttribute('aria-label') || el.textContent.trim()).slice(0, 30)} | ${el.className.toString().slice(0, 50)}`);
      }
      if (state !== 'normal') el.classList.remove(state);
    }
  }
  st.remove();
  return [...new Set(problems)];
})();
