// modules/vouchers.js — Voucher search, edit, restore, delete

import { apiMutate } from './api.js';
import { escapeHtml, showSuccess, showError, showWarning, copyTextToClipboard, invalidateTabs } from './ui.js';
import { openModal, closeModal } from './modal.js';
import { updateLogoUploader } from './uploader.js';
import { populateStructuredContent } from './ui.js';

let _editVoucherToken = 0;
let _allVouchers = []; // client-side cache

export function loadVouchers() {
    const loadingEl = document.getElementById('search-loading');
    if (loadingEl) loadingEl.style.display = 'block';

    fetch('api/search_vouchers.php')
        .then(r => r.json())
        .then(data => {
            _allVouchers = data.vouchers || [];
            _applyFilters();
            if (loadingEl) loadingEl.style.display = 'none';
            setupVoucherSearch();
        })
        .catch(err => {
            console.error('Load error:', err);
            if (loadingEl) loadingEl.style.display = 'none';
        });
}

function _applyFilters() {
    const q      = (document.getElementById('search-voucher')?.value || '').toLowerCase().trim();
    const status = document.getElementById('filter-status')?.value || '';
    const sort   = document.getElementById('filter-sort')?.value   || 'newest';

    let list = _allVouchers.filter(v => {
        const fullCode = (v.sponsor_short + v.code).toLowerCase();
        const name     = (v.sponsor_name || '').toLowerCase();
        const matchQ      = !q || fullCode.includes(q) || name.includes(q);
        const matchStatus = !status || v.status === status;
        return matchQ && matchStatus;
    });

    if (sort === 'oldest') {
        list = list.slice().sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
    } else if (sort === 'used-recent') {
        list = list.filter(v => v.used_at).sort((a, b) => new Date(b.used_at) - new Date(a.used_at));
    }
    // 'newest' = default API order, no re-sort needed

    renderVouchers(list);
}

export function getStatusText(status) {
    switch (status) {
        case 'unused':  return 'Chưa sử dụng';
        case 'used':    return 'Đã sử dụng';
        case 'expired': return 'Hết hạn';
        default:        return status;
    }
}

export function searchVouchers() { _applyFilters(); }

export function renderVouchers(vouchers) {
    const container = document.getElementById('vouchers-list');
    if (!container) return;
    container.innerHTML = '';

    if (!vouchers || vouchers.length === 0) {
        container.innerHTML = '<p class="list-empty">Không tìm thấy e-voucher nào</p>';
        return;
    }

    vouchers.forEach(voucher => {
        const card = document.createElement('div');
        card.className = 'voucher-item-card';
        const fullCode        = voucher.sponsor_short + voucher.code;
        const escapedFullCode = escapeHtml(fullCode);
        const vid             = parseInt(voucher.id);

        const statusHtml = voucher.status === 'used'
            ? `<span class="voucher-status status-used used-status">${getStatusText(voucher.status)}<span class="used-tooltip">Thời gian: ${escapeHtml(voucher.used_at)}</span></span>`
            : `<span class="voucher-status status-${escapeHtml(voucher.status)}">${getStatusText(voucher.status)}</span>`;

        const voucherLink = `${window.location.origin}/${escapedFullCode}`;
        card.innerHTML = `
            <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:10px; width:100%;">
                <div style="flex:1; min-width:150px;">
                    <div class="campaign-name" style="font-size:1.1rem; margin-bottom:4px;">${escapeHtml(voucher.sponsor_name)}</div>
                    <div class="voucher-code-orange" onclick="openVoucherLink('${escapedFullCode}')" title="Mở link voucher">${escapedFullCode}</div>
                    <div class="campaign-date"><i class="fas fa-calendar-alt" style="margin-right:4px; color:#94a3b8;"></i>${escapeHtml(voucher.created_at)}</div>
                </div>
                <div style="max-width:100%; text-align:right;">${statusHtml}</div>
            </div>
            <div class="voucher-actions">
                <button class="btn btn-sm btn-teal" onclick="copyCodeWithFeedback(this, '${escapedFullCode}')" title="Copy mã voucher"><i class="fas fa-barcode"></i></button>
                <button class="btn-copy-link" onclick="copyLinkWithFeedback(this, '${voucherLink}')" title="Copy link voucher"><i class="fas fa-copy"></i></button>
                <button class="btn btn-sm btn-indigo" onclick="copyVoucherToFree(${vid})" title="Copy sang Voucher tự do"><i class="fas fa-bolt"></i></button>
                <button class="btn btn-sm btn-amber" onclick="editVoucherDetails(${vid})" title="Chỉnh sửa"><i class="fas fa-edit"></i></button>
                ${voucher.status === 'used' ? `<button class="btn btn-sm btn-green" onclick="restoreVoucher(${vid}, 'campaign')" title="Khôi phục"><i class="fas fa-undo"></i></button>` : ''}
                <button class="btn btn-sm btn-red" onclick="deleteVoucher(${vid})" title="Xóa"><i class="fas fa-trash"></i></button>
            </div>
        `;
        container.appendChild(card);
    });
}

let _searchTimeout;
export function setupVoucherSearch() {
    const debounce = () => { clearTimeout(_searchTimeout); _searchTimeout = setTimeout(_applyFilters, 200); };

    document.getElementById('search-voucher')?.addEventListener('input', debounce);
    document.getElementById('filter-status')?.addEventListener('change', _applyFilters);
    document.getElementById('filter-sort')?.addEventListener('change', _applyFilters);
}

export function editVoucherDetails(voucherId) {
    const token  = ++_editVoucherToken;
    const modal     = document.getElementById('edit-voucher-modal');
    const submitBtn = modal.querySelector('button[type="submit"]');
    const origText  = submitBtn.textContent;
    submitBtn.textContent = 'Đang tải...';
    submitBtn.disabled    = true;

    fetch(`api/get_voucher_details.php?id=${voucherId}`)
        .then(r => r.json())
        .then(data => {
            if (token !== _editVoucherToken) return; // stale — discard
            if (data.success) {
                document.getElementById('edit-voucher-id').value     = voucherId;
                document.getElementById('edit-description').value    = data.voucher.description || '';
                document.getElementById('edit-voucher-code').value   = data.voucher.code        || '';
                populateStructuredContent(modal, data.voucher);

                const fullCode = `${data.voucher.sponsor_short}${data.voucher.code}`;
                document.getElementById('edit-full-code').textContent    = fullCode;
                document.getElementById('edit-sponsor-name').textContent = data.voucher.sponsor_name || '';

                const statusMap = {
                    unused: '<span class="text-success">Chưa sử dụng</span>',
                    used:   '<span class="text-danger">Đã sử dụng</span>',
                    taken:  '<span class="text-warning">Đã lấy</span>',
                };
                document.getElementById('edit-voucher-status').innerHTML = statusMap[data.voucher.status] || data.voucher.status;

                const title = modal.querySelector('.modal-header h3');
                if (title) title.textContent = `Chỉnh sửa E-voucher: ${fullCode}`;

                updateLogoUploader(modal, data.voucher.logo);
                openModal('edit-voucher-modal');
            } else {
                showError('Lỗi tải thông tin', data.error);
            }
        })
        .catch(err => showError('Lỗi kết nối', 'Lỗi khi tải thông tin e-voucher'))
        .finally(() => {
            submitBtn.textContent = origText;
            submitBtn.disabled    = false;
        });
}

export function restoreVoucher(voucherId, voucherType = 'campaign') {
    showWarning('Xác nhận khôi phục', 'Bạn có chắc chắn muốn khôi phục e-voucher này?',
        () => _performRestoreVoucher(voucherId, voucherType));
}

function _performRestoreVoucher(voucherId, voucherType = 'campaign') {
    const fd = new FormData();
    fd.append('id', voucherId);
    fd.append('voucher_type', voucherType);

    apiMutate('api/restore_voucher.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showSuccess('Khôi phục thành công!', 'Đã khôi phục e-voucher thành công');
                invalidateTabs('vouchers', 'campaigns', 'dashboard');
                window.loadDashboard?.();
                const tab = new URLSearchParams(window.location.search).get('tab') || 'dashboard';
                if (tab === 'campaigns')     window.loadCampaigns?.();
                else if (tab === 'vouchers') window.loadVouchers?.();
                else if (tab === 'free_vouchers') window.loadFreeVouchers?.();
            } else {
                showError('Lỗi khôi phục', data.error || 'Không thể khôi phục e-voucher');
            }
        })
        .catch(err => showError('Lỗi kết nối', err.message));
}

export function deleteVoucher(voucherId) {
    showWarning('Xác nhận xóa', 'Bạn có chắc chắn muốn xóa e-voucher này?',
        () => _performDeleteVoucher(voucherId));
}

function _performDeleteVoucher(voucherId) {
    apiMutate(`api/vouchers.php?id=${voucherId}`, { method: 'DELETE' })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showSuccess('Xóa thành công!', 'Đã xóa e-voucher thành công');
                invalidateTabs('vouchers', 'campaigns', 'dashboard');
                loadVouchers();
            } else {
                showError('Lỗi xóa e-voucher', data.error);
            }
        })
        .catch(err => console.error('Error deleting voucher:', err));
}

export function copyVoucherToFree(voucherId) {
    apiMutate('api/copy_to_free_voucher.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ voucher_id: voucherId }),
    })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showSuccess('Đã sao chép!', 'E-voucher đã được đưa vào tab Voucher tự do');
                invalidateTabs('free_vouchers');
                window.loadFreeVouchers?.();
            } else {
                showError('Lỗi sao chép voucher', data.error || 'Không thể sao chép voucher');
            }
        })
        .catch(err => console.error('Error copying voucher to free list:', err));
}

export function copyVoucherLink(code) {
    const link = `https://e.kontumplus.com/${code}`;
    copyTextToClipboard(link).then(ok => {
        if (ok) alert('Đã copy link: ' + link);
        else    alert('Không thể tự động copy. Vui lòng copy thủ công: ' + link);
    });
}

export function editVoucherCode(voucherId) {
    const newCode = prompt('Nhập mã code mới:');
    if (!newCode) return;

    apiMutate('api/vouchers.php', {
        method:  'PUT',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ id: voucherId, code: newCode }),
    })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showSuccess('Cập nhật thành công!', 'Đã cập nhật mã code thành công');
                loadVouchers();
            } else {
                showError('Lỗi cập nhật mã code', data.error);
            }
        })
        .catch(err => console.error('Error editing voucher:', err));
}

// ── Form submit handler ───────────────────────────────────────────────────
export function initVoucherForms() {
    const editForm = document.getElementById('edit-voucher-form');
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            apiMutate('api/get_voucher_details.php', { method: 'POST', body: new FormData(this) })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showSuccess('Cập nhật thành công!', 'Đã cập nhật e-voucher thành công');
                        closeModal('edit-voucher-modal');
                        invalidateTabs('vouchers', 'campaigns', 'dashboard');
                        loadVouchers();
                        window.loadCampaigns?.();
                    } else {
                        showError('Lỗi cập nhật e-voucher', data.error);
                    }
                })
                .catch(err => console.error('Error updating voucher:', err));
        });
    }

    const changePasswordForm = document.getElementById('change-password-form');
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const data = Object.fromEntries(new FormData(this));
            apiMutate('api/change_password.php', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify(data),
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showSuccess('Đổi mật khẩu thành công!', 'Đã đổi mật khẩu thành công');
                        closeModal('change-password-modal');
                    } else {
                        showError('Lỗi đổi mật khẩu', data.error);
                    }
                })
                .catch(err => console.error('Error changing password:', err));
        });
    }
}

export function openChangePasswordModal() {
    openModal('change-password-modal');
}
