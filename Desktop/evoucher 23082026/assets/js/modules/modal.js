// modules/modal.js — Modal open/close with cleanup

import { updateLogoUploader } from './uploader.js';

export function openModal(modalId) {
    const el = document.getElementById(modalId);
    if (el) el.classList.add('active');
}

export function closeModal(modalId) {
    const modalEl = document.getElementById(modalId);
    if (!modalEl) return;
    modalEl.classList.remove('active');

    // Reset logo uploader inside the modal
    updateLogoUploader(modalEl, null);

    // Clear pasted base64 data
    const form = modalEl.querySelector('form');
    if (form) {
        ['logo_base64', 'menu_image_base64'].forEach(name => {
            const hidden = form.querySelector(`input[name="${name}"]`);
            if (hidden) hidden.value = '';
        });
    }

    // Reset create-campaign-modal back to create mode if it was in edit mode
    if (modalId === 'create-campaign-modal') {
        const f = document.getElementById('create-campaign-form');
        if (f && f.hasAttribute('data-edit-id')) {
            f.removeAttribute('data-edit-id');
            const title = document.querySelector('#create-campaign-modal .modal-header h3');
            if (title) title.textContent = 'Tạo Chiến dịch Mới';
            const submitBtn = document.querySelector('#create-campaign-modal button[type="submit"]');
            if (submitBtn) submitBtn.textContent = 'Tạo Chiến dịch';
            const codesField = f.querySelector('textarea[name="codes"]')?.closest('.form-group');
            if (codesField) {
                codesField.style.display = '';
                const label = codesField.querySelector('label');
                if (label) label.textContent = 'Mã code (mỗi mã trên 1 dòng) *';
            }
            f.reset();
        }
    }
}
