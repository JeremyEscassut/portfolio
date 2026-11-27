// File: resources/js/contactFeedback.js
// Purpose: what the visitor sees after submitting the contact form (markup in home.blade.php).
//
// 1. Toasts (#toast-region): the server flashes 'status' (sent) or 'error' (not sent);
//    Blade renders them as toasts; each one hides itself after `data-autohide` ms (10 s,
//    paused on hover and focus) or when closed. After a failed send there is no error
//    toast: the reopened contact dialog shows the message itself.
// 2. Fallback when the server could not send the mail: the contact modal reopens with the
//    visitor's text kept (Laravel old()), plus two ways to finish without retyping:
//    - "Open in my email app": a mailto: link built from the current field values,
//      addressed to the site owner, so the visitor only has to press Send;
//    - "Copy the message" (message + name): for webmail users without a default email app.
//    If the mailto: link opens nothing, a hint with the address appears (watchMailtoOutcome).

(function initToasts() {
    document.querySelectorAll('#toast-region .toast').forEach((toast) => {
        let timer = null;
        const dismiss = () => {
            toast.classList.add('is-leaving');
            // Remove after the fade-out (also fine when reduced motion shortens it).
            setTimeout(() => toast.remove(), 250);
        };
        toast.querySelector('.toast__close')?.addEventListener('click', dismiss);

        // Auto-hide, paused while the pointer or the keyboard focus is on the toast,
        // so nobody loses the message while reading it.
        const delay = Number(toast.dataset.autohide);
        const start = () => { if (delay > 0) timer = setTimeout(dismiss, delay); };
        const pause = () => { clearTimeout(timer); timer = null; };
        toast.addEventListener('pointerenter', pause);
        toast.addEventListener('pointerleave', start);
        toast.addEventListener('focusin', pause);
        toast.addEventListener('focusout', start);
        start();

        // A live region that is already filled when the page loads is not announced by
        // screen readers: put the text back a moment later so it is read out.
        const text = toast.querySelector('.toast__text');
        if (text) {
            const message = text.textContent;
            text.textContent = '';
            setTimeout(() => { text.textContent = message; }, 150);
        }
    });
})();

/** Current values of the contact form that contains `element`. */
function readContactForm(element) {
    const form = element.closest('form');
    const value = (name) => (form?.elements[name]?.value || '').trim();
    return { name: value('name'), email: value('email'), message: value('message') };
}

/**
 * Message text as it should appear in the visitor's own email: the message, then
 * their name. No email address: their mail app adds it as the sender.
 */
function composeBody({ name, message }) {
    return name ? `${message}\n\n${name}` : message;
}

/**
 * mailto: only works when the device has a default email app. When none is set
 * (common on computers where people use Gmail/Outlook in the browser), clicking
 * does nothing at all. If the page still has focus 1.5 s after the click (an app
 * opening would have taken it), show the "nothing opened?" hint with the address.
 */
function watchMailtoOutcome(link) {
    const hint = link.closest('.contact-feedback')?.querySelector('[data-mailto-not-opened]');
    if (!hint) return;
    let leftPage = false;
    const onLeave = () => { leftPage = true; };
    window.addEventListener('blur', onLeave, { once: true });
    document.addEventListener('visibilitychange', onLeave, { once: true });
    setTimeout(() => {
        window.removeEventListener('blur', onLeave);
        document.removeEventListener('visibilitychange', onLeave);
        if (!leftPage) hint.hidden = false;
    }, 1500);
}

document.addEventListener('click', (event) => {
    // "Open in my email app": rebuild the mailto: URL at click time so edits made
    // after the failure are included. encodeURIComponent keeps line breaks (%0A).
    const mailLink = event.target.closest('[data-mailto-fallback]');
    if (mailLink) {
        const fields = readContactForm(mailLink);
        const subject = fields.name ? `${mailLink.dataset.subject} — ${fields.name}` : mailLink.dataset.subject;
        mailLink.href = `mailto:${mailLink.dataset.to}`
            + `?subject=${encodeURIComponent(subject)}`
            + `&body=${encodeURIComponent(composeBody(fields))}`;
        watchMailtoOutcome(mailLink);
        return; // let the browser follow the updated link
    }

    // "Copy the message": Clipboard API when available (https / localhost),
    // otherwise the old select + execCommand('copy') route.
    const copyButton = event.target.closest('[data-copy-message]');
    if (copyButton) {
        const text = composeBody(readContactForm(copyButton));
        const done = () => {
            const original = copyButton.textContent;
            copyButton.textContent = copyButton.dataset.copiedLabel;
            setTimeout(() => { copyButton.textContent = original; }, 2000);
        };
        if (navigator.clipboard?.writeText) {
            navigator.clipboard.writeText(text).then(done).catch(() => {});
        } else {
            const area = document.createElement('textarea');
            area.value = text;
            area.setAttribute('readonly', '');
            area.style.position = 'fixed';
            area.style.opacity = '0';
            document.body.appendChild(area);
            area.select();
            try { if (document.execCommand('copy')) done(); } catch (e) { /* ignore */ }
            area.remove();
        }
    }
});
