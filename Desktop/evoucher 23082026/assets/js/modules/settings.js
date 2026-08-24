// modules/settings.js — App settings management

import { apiMutate } from './api.js';
import { showSuccess, showError, showWarning } from './ui.js';

const DEFAULT_GIVE_MESSAGE = 'Tặng bạn e-voucher, link=>';

export function loadSettings() {
    fetch('api/settings.php')
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            const s = data.settings || {};
            const msgEl = document.getElementById('setting-give-message');
            if (msgEl && s.give_message !== undefined) msgEl.value = s.give_message;

            const verEl = document.getElementById('sw-cache-version');
            if (verEl && s.sw_cache_version !== undefined) verEl.textContent = s.sw_cache_version;
        })
        .catch(err => console.error('Error loading settings:', err));
}

export function saveGiveMessage() {
    const el = document.getElementById('setting-give-message');
    if (!el) return;

    const value = el.value.trim();
    if (!value) {
        showError('Lỗi', 'Nội dung tin nhắn không được để trống');
        return;
    }

    apiMutate('api/settings.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ key: 'give_message', value }),
    })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const status = document.getElementById('settings-save-status');
                if (status) {
                    status.style.display = 'inline-flex';
                    setTimeout(() => { status.style.display = 'none'; }, 3000);
                }
                showSuccess('Đã lưu', 'Nội dung tin nhắn đã được cập nhật');
            } else {
                showError('Lỗi', data.error || 'Không thể lưu cài đặt');
            }
        })
        .catch(() => showError('Lỗi kết nối', 'Vui lòng thử lại'));
}

export function resetGiveMessage() {
    const el = document.getElementById('setting-give-message');
    if (el) el.value = DEFAULT_GIVE_MESSAGE;
}

export function bumpSwCache() {
    showWarning(
        'Xóa cache trình duyệt',
        'Tất cả người dùng sẽ tải lại JS/CSS mới nhất khi reload trang. Tiếp tục?',
        () => {
            apiMutate('api/bump_sw_cache.php', { method: 'POST' })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const verEl = document.getElementById('sw-cache-version');
                        if (verEl) verEl.textContent = data.version;
                        showSuccess('Cache đã được xóa', `Phiên bản cache mới: v${data.version}`);
                    } else {
                        showError('Lỗi', data.error || 'Không thể bump cache');
                    }
                })
                .catch(() => showError('Lỗi kết nối', 'Vui lòng thử lại'));
        }
    );
}

export function getGiveMessage() {
    return fetch('api/settings.php?key=give_message')
        .then(r => r.json())
        .then(data => (data.success && data.value) ? data.value : DEFAULT_GIVE_MESSAGE)
        .catch(() => DEFAULT_GIVE_MESSAGE);
}
