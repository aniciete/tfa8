/**
 * POS Eloquent — Client Form Accessibility, Live Avatar Preview & Submit Guard
 */
(() => {
    const MAX_AVATAR_BYTES = 2 * 1024 * 1024;
    const ALLOWED_IMAGE_TYPES = new Set([
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
        'image/bmp',
    ]);

    const initValidationAriaSync = () => {
        const forms = document.querySelectorAll('.ledger-form');
        if (!forms.length) {
            return;
        }

        const errorSummary = document.getElementById('form-error-summary');
        if (errorSummary) {
            errorSummary.focus();
        }

        const announcer = document.createElement('div');
        announcer.setAttribute('aria-live', 'polite');
        announcer.className = 'visually-hidden';
        document.body.append(announcer);

        const supportsUserInvalid = CSS.supports('selector(:user-invalid)');
        const dirtyState = new WeakMap();
        const flagged = new WeakSet();
        const announced = new WeakSet();
        let announceTimer = null;
        let announcedField = null;

        const linkError = (el, shown) => {
            const errorId = el.dataset.error;
            if (!errorId) {
                return;
            }
            const current = (el.getAttribute('aria-describedby') || '')
                .split(/\s+/)
                .filter((id) => id && id !== errorId);
            if (shown) {
                current.push(errorId);
            }
            if (current.length) {
                el.setAttribute('aria-describedby', current.join(' '));
            } else {
                el.removeAttribute('aria-describedby');
            }
        };

        const spokenText = (node) => {
            const copy = node.cloneNode(true);
            copy.querySelectorAll('[aria-hidden="true"]').forEach((n) => n.remove());
            return copy.textContent.replace(/\s+/g, ' ').trim();
        };

        const announceError = (el) => {
            const errorEl = el.dataset.error ? document.getElementById(el.dataset.error) : null;
            if (!errorEl) {
                return;
            }
            const label = el.labels?.[0] ? `${spokenText(el.labels[0])}: ` : '';
            clearTimeout(announceTimer);
            announcer.textContent = '';
            announcedField = el;
            announceTimer = setTimeout(() => {
                announcer.textContent = label + spokenText(errorEl);
            }, 500);
        };

        const isControlInvalid = (el) => {
            if (supportsUserInvalid) {
                return el.matches(':user-invalid');
            }
            const state = dirtyState.get(el);
            return Boolean(state?.hasBlurred && !el.validity.valid);
        };

        const syncAria = (el, announce = false) => {
            if (!el.matches?.('input, select, textarea') || el.type === 'radio' || el.type === 'checkbox') {
                return;
            }

            const hasServerError = el.closest('.form-group')?.classList.contains('has-error');
            const invalid = isControlInvalid(el);

            if (!supportsUserInvalid && dirtyState.get(el)?.hasBlurred) {
                el.classList.toggle('user-invalid-fallback', !el.validity.valid);
            }

            if (invalid) {
                if (announce && !announced.has(el)) {
                    announceError(el);
                    announced.add(el);
                }
                el.setAttribute('aria-invalid', 'true');
                linkError(el, true);
                flagged.add(el);
            } else if (flagged.has(el) || (!hasServerError && el.getAttribute('aria-invalid') !== 'true')) {
                el.setAttribute('aria-invalid', 'false');
                linkError(el, false);
                flagged.delete(el);
                announced.delete(el);
                el.closest('.form-group')?.classList.remove('has-error');
                if (announcedField === el) {
                    clearTimeout(announceTimer);
                    announcer.textContent = '';
                }
            }
        };

        forms.forEach((form) => {
            form.querySelectorAll('input, select, textarea').forEach((el) => {
                if (el.type !== 'radio' && el.type !== 'checkbox' && !el.hasAttribute('aria-invalid')) {
                    el.setAttribute('aria-invalid', 'false');
                }
            });

            form.addEventListener('blur', (event) => {
                const el = event.target;
                if (!el.matches?.('input, select, textarea')) {
                    return;
                }
                const state = dirtyState.get(el) || { hasInteracted: false, hasBlurred: false };
                state.hasBlurred = true;
                dirtyState.set(el, state);
                syncAria(el, true);
            }, true);

            form.addEventListener('input', (event) => {
                const el = event.target;
                if (!el.matches?.('input, select, textarea')) {
                    return;
                }
                const state = dirtyState.get(el) || { hasInteracted: false, hasBlurred: false };
                state.hasInteracted = true;
                dirtyState.set(el, state);
                if (el.type !== 'file') {
                    el.setCustomValidity('');
                }
                if (el.getAttribute('aria-invalid') === 'true' || flagged.has(el)) {
                    syncAria(el, false);
                }
            });

            form.addEventListener('invalid', (event) => {
                const el = event.target;
                dirtyState.set(el, { hasInteracted: true, hasBlurred: true });
                announced.add(el);
                syncAria(el, false);
            }, true);

            form.addEventListener('submit', () => {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn && form.checkValidity()) {
                    submitBtn.disabled = true;
                    submitBtn.setAttribute('aria-busy', 'true');
                }
            });
        });
    };

    const initAvatarPreview = () => {
        const input = document.getElementById('avatar');
        const preview = document.getElementById('avatar-live-preview');
        const statusEl = document.getElementById('avatar-preview-status');
        const filenameEl = document.getElementById('avatar-preview-filename');
        const removeCheckbox = document.getElementById('remove_avatar');

        if (!input || !preview) {
            return;
        }

        const defaultSrc = preview.dataset.defaultSrc || preview.src;
        const defaultAlt = preview.alt;
        const defaultStatus = statusEl ? statusEl.textContent.trim() : '';
        const defaultFilename = filenameEl ? filenameEl.textContent.trim() : '';
        let activeObjectUrl = null;

        const revokeActiveUrl = () => {
            if (activeObjectUrl) {
                URL.revokeObjectURL(activeObjectUrl);
                activeObjectUrl = null;
            }
        };

        window.addEventListener('pagehide', revokeActiveUrl);

        input.addEventListener('change', () => {
            revokeActiveUrl();
            input.setCustomValidity('');

            const file = input.files && input.files[0];
            if (!file) {
                preview.src = defaultSrc;
                preview.alt = defaultAlt;
                if (statusEl) {
                    statusEl.textContent = defaultStatus;
                }
                if (filenameEl) {
                    filenameEl.textContent = defaultFilename;
                }
                return;
            }

            if (file.type && !ALLOWED_IMAGE_TYPES.has(file.type)) {
                input.setCustomValidity('Please select a valid image file (JPG, PNG, GIF, BMP, or WebP).');
                preview.src = defaultSrc;
                return;
            }

            if (file.size > MAX_AVATAR_BYTES) {
                input.setCustomValidity('The profile picture must not be larger than 2 MB.');
                preview.src = defaultSrc;
                return;
            }

            if (removeCheckbox) {
                removeCheckbox.checked = false;
            }

            activeObjectUrl = URL.createObjectURL(file);
            preview.src = activeObjectUrl;
            preview.alt = `Selected avatar preview (${file.name})`;

            const sizeKb = Math.max(1, Math.round(file.size / 1024));
            if (statusEl) {
                statusEl.textContent = 'Ready to Upload (Cropped to 256×256 on Save)';
            }
            if (filenameEl) {
                filenameEl.textContent = `${file.name} (${sizeKb} KB)`;
            }
        });
    };

    document.addEventListener('DOMContentLoaded', () => {
        initValidationAriaSync();
        initAvatarPreview();
    });
})();
