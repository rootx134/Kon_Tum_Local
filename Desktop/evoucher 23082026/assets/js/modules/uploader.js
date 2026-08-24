// modules/uploader.js — Logo & menu image upload/paste helpers

// ── Image compression via Canvas ─────────────────────────────────────────
/**
 * Compress a base64 data-URI to fit within maxWidth/maxHeight at given quality.
 * Returns a Promise<string> with the compressed data-URI (JPEG).
 */
function compressImage(dataUri, maxWidth, maxHeight, quality) {
    return new Promise((resolve) => {
        const img = new Image();
        img.onload = function () {
            let { width, height } = img;

            // Scale down proportionally if needed
            if (width > maxWidth || height > maxHeight) {
                const ratio = Math.min(maxWidth / width, maxHeight / height);
                width  = Math.round(width  * ratio);
                height = Math.round(height * ratio);
            }

            const canvas = document.createElement('canvas');
            canvas.width  = width;
            canvas.height = height;
            canvas.getContext('2d').drawImage(img, 0, 0, width, height);
            resolve(canvas.toDataURL('image/jpeg', quality));
        };
        img.onerror = () => resolve(dataUri); // fallback: return original
        img.src = dataUri;
    });
}

// ── Logo uploader ─────────────────────────────────────────────────────────
export function triggerLogoUpload(element) {
    const input = element.querySelector('.logo-file-input');
    if (input) input.click();
}

export function previewLogo(input) {
    const container = input.closest('.logo-uploader');
    if (!container) return;
    const preview     = container.querySelector('.logo-preview');
    const placeholder = container.querySelector('.upload-placeholder');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = async function (e) {
            const compressed = await compressImage(e.target.result, 600, 600, 0.85);
            if (preview)     { preview.src = compressed; preview.style.display = 'block'; }
            if (placeholder) { placeholder.style.display = 'none'; }
            const form = input.closest('form');
            if (form) {
                let hidden = form.querySelector('input[name="logo_base64"]');
                if (!hidden) {
                    hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'logo_base64';
                    form.appendChild(hidden);
                }
                hidden.value = compressed;
            }
            input.value = '';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

export function updateLogoUploader(container, logoPath) {
    if (!container) return;
    const uploader    = container.classList.contains('logo-uploader')
        ? container
        : container.querySelector('.logo-uploader');
    if (!uploader) return;

    const preview     = uploader.querySelector('.logo-preview');
    const placeholder = uploader.querySelector('.upload-placeholder');
    const input       = uploader.querySelector('.logo-file-input');

    if (input) input.value = '';

    if (logoPath) {
        if (preview)     { preview.src = 'uploads/' + logoPath; preview.style.display = 'block'; }
        if (placeholder) { placeholder.style.display = 'none'; }
    } else {
        if (preview)     { preview.src = ''; preview.style.display = 'none'; }
        if (placeholder) { placeholder.style.display = 'flex'; }
    }
}

export function handleLogoPaste(event, uploaderEl) {
    const items = (event.clipboardData || window.clipboardData).items;
    for (let i = 0; i < items.length; i++) {
        if (items[i].type.startsWith('image/')) {
            const file = items[i].getAsFile();
            if (!file) continue;

            const reader = new FileReader();
            reader.onload = async function (e) {
                const compressed = await compressImage(e.target.result, 600, 600, 0.85);

                const preview     = uploaderEl.querySelector('.logo-preview');
                const placeholder = uploaderEl.querySelector('.upload-placeholder');
                if (preview)     { preview.src = compressed; preview.style.display = 'block'; }
                if (placeholder) { placeholder.style.display = 'none'; }

                const fileInput = uploaderEl.querySelector('.logo-file-input');
                if (fileInput) fileInput.value = '';

                const form = uploaderEl.closest('form');
                if (form) {
                    let hidden = form.querySelector('input[name="logo_base64"]');
                    if (!hidden) {
                        hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = 'logo_base64';
                        form.appendChild(hidden);
                    }
                    hidden.value = compressed;
                }
            };
            reader.readAsDataURL(file);
            event.preventDefault();
            break;
        }
    }
}

// ── Menu image uploader ───────────────────────────────────────────────────
export function triggerMenuImageUpload(element) {
    const input = element.querySelector('.menu-image-file-input');
    if (input) input.click();
}

export function previewMenuImage(input) {
    const uploader = input.closest('.menu-image-uploader');
    if (!uploader) return;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = async function (e) {
            // High quality for menu — users need to zoom in to read
            const compressed = await compressImage(e.target.result, 2400, 1800, 0.92);
            _applyMenuImagePreview(uploader, compressed);
            const form = input.closest('form');
            if (form) {
                let hidden = form.querySelector('input[name="menu_image_base64"]');
                if (!hidden) {
                    hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'menu_image_base64';
                    form.appendChild(hidden);
                }
                hidden.value = compressed;
            }
            input.value = '';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

export function handleMenuImagePaste(event, uploaderEl) {
    const items = (event.clipboardData || window.clipboardData).items;
    for (let i = 0; i < items.length; i++) {
        if (items[i].type.startsWith('image/')) {
            const file = items[i].getAsFile();
            if (!file) continue;
            const reader = new FileReader();
            reader.onload = async function (e) {
                // High quality for menu — users need to zoom in to read
                const compressed = await compressImage(e.target.result, 2400, 1800, 0.92);

                _applyMenuImagePreview(uploaderEl, compressed);

                const fileInput = uploaderEl.querySelector('.menu-image-file-input');
                if (fileInput) fileInput.value = '';

                const form = uploaderEl.closest('form');
                if (form) {
                    let hidden = form.querySelector('input[name="menu_image_base64"]');
                    if (!hidden) {
                        hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = 'menu_image_base64';
                        form.appendChild(hidden);
                    }
                    hidden.value = compressed;
                }
            };
            reader.readAsDataURL(file);
            event.preventDefault();
            break;
        }
    }
}

export function _applyMenuImagePreview(uploaderEl, src) {
    const preview     = uploaderEl.querySelector('.menu-image-preview');
    const placeholder = uploaderEl.querySelector('.upload-placeholder');
    if (preview)     { preview.src = src; preview.style.display = 'block'; }
    if (placeholder) { placeholder.style.display = 'none'; }
}

// Expose to window for HTML inline onclick/onpaste
window.triggerLogoUpload      = triggerLogoUpload;
window.previewLogo            = previewLogo;
window.updateLogoUploader     = updateLogoUploader;
window.handleLogoPaste        = handleLogoPaste;
window.triggerMenuImageUpload = triggerMenuImageUpload;
window.previewMenuImage       = previewMenuImage;
window.handleMenuImagePaste   = handleMenuImagePaste;
