// File: resources/js/dialogs.js
// Purpose: shared behaviour of the native modal dialogs (contact, CV choice, Admiral video).
//
// Markup (home.blade.php): <dialog class="pf-dialog"> is a transparent full-screen layer;
// the visible box is its child .pf-dialog__panel. Opened with showModal(), the browser
// already provides: the rest of the page made inert, focus kept inside, Escape and the
// mobile back gesture to close, focus returned to the button that opened it.
// So there is no focus trap, no Escape handler and no role="dialog" / aria-modal here.
//
// This file only adds:
// - closing on a click outside the panel (the transparent layer around it). Both the
//   press and the release must be outside, so selecting text and releasing on the
//   backdrop does not close the dialog;
// - closing with any [data-dialog-close] button;
// - pausing a <video> when its dialog closes;
// - window.openDialog(id, focusSelector) to open from other scripts.

function setUpDialog(dialog) {
    let pressedOutside = false;
    dialog.addEventListener('pointerdown', (event) => {
        pressedOutside = event.target === dialog;
    });
    dialog.addEventListener('click', (event) => {
        if (pressedOutside && event.target === dialog) dialog.close();
        pressedOutside = false;
    });

    dialog.querySelectorAll('[data-dialog-close]').forEach((button) => {
        button.addEventListener('click', () => dialog.close());
    });

    dialog.addEventListener('close', () => {
        dialog.querySelectorAll('video').forEach((video) => video.pause());
    });
}

/**
 * Open a dialog as a modal. `focusSelector` names the element that gets focus
 * (otherwise the element with `autofocus`, else the first focusable one).
 */
export function openDialog(id, focusSelector) {
    const dialog = document.getElementById(id);
    if (!dialog || dialog.open) return dialog;
    dialog.showModal();
    if (focusSelector) {
        const target = dialog.querySelector(focusSelector);
        if (target) target.focus();
    }
    return dialog;
}

window.openDialog = openDialog;

function init() {
    document.querySelectorAll('dialog.pf-dialog').forEach(setUpDialog);

    // Contact: every "contact" control dispatches a window 'open-contact' event.
    window.addEventListener('open-contact', () => openDialog('contact-dialog', '#contact-email'));

    // Contact reopens by itself after a failed send or a validation error, focus on the
    // field to fix (the server already refilled the fields with old()).
    const contact = document.getElementById('contact-dialog');
    if (contact && contact.dataset.reopen) {
        openDialog('contact-dialog', '#contact-' + contact.dataset.reopen);
    }

    // Admiral video
    document.querySelectorAll('[data-dialog-open]').forEach((button) => {
        button.addEventListener('click', () => openDialog(button.dataset.dialogOpen));
    });
}

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
else init();
