// modules/free-vouchers.js — Free voucher CRUD

import { apiMutate } from './api.js';
import { escapeHtml, showSuccess, showError, showWarning, invalidateTabs } from './ui.js';
import { openModal, closeModal } from './modal.js';
import { updateLogoUploader } from './uploader.js';
import { populateStructuredContent } from './ui.js';

let _editFreeVoucherToken = 0;
let _allFreeVouchers = []; // client-side cache

// ── Load (fetch once) ─────────────────────────────────────────────────────
export function loadFreeVouchers() {
    fetch('api/free_vouchers.php')
        .then(r => {
            if (!r.ok) throw new Error('Network response was not ok');
            return r.json();
        })
        .then(data => {
            _allFreeVouchers = Array.isArray(data) ? data : [];
            _applyFreeFilters();
            setupFreeVoucherSearch();
        })
        .catch(err => {
            const container = document.getElementById('free-vouchers-list');
            if (container) container.innerHTML = `<p class="list-error">Lỗi khi tải: ${err.message}</p>`;
        });
}

// ── Client-side filter + render ───────────────────────────────────────────
function _applyFreeFilters() {
    const container = document.getElementById('free-vouchers-list');
    if (!container) return;

    const q      = (document.getElementById('search-free-voucher')?.value || '').toLowerCase().trim();
    const status = document.getElementById('filter-free-status')?.value || '';
    const sort   = document.getElementById('filter-free-sort')?.value   || 'newest';

    let list = _allFreeVouchers.filter(v => {
        const code = (v.code          || '').toLowerCase();
        const name = (v.sponsor_name  || '').toLowerCase();
        const desc = (v.description   || '').toLowerCase();
        const matchQ      = !q || code.includes(q) || name.includes(q) || desc.includes(q);
        const matchStatus = !status || v.status === status;
        return matchQ && matchStatus;
    });

    if (sort === 'oldest') {
        list = list.slice().sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
    }

    container.innerHTML = '';

    if (list.length === 0) {
        container.innerHTML = '<p class="list-empty">Chưa có voucher tự do nào</p>';
        return;
    }

    list.forEach(voucher => {
        const card        = document.createElement('div');
        card.className    = 'voucher-item-card';

        const statusClass = voucher.status === 'used'    ? 'status-used'
                          : voucher.status === 'expired' ? 'status-expired'
                          : 'status-unused';
        const statusText  = _getStatusText(voucher.status || 'unused');
        const escapedCode = escapeHtml(voucher.code);
        const vid         = parseInt(voucher.id);
        const voucherLink = `${window.location.origin}/${escapedCode}`;

        card.innerHTML = `
            <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:10px; width:100%;">
                <div style="flex:1; min-width:150px;">
                    <div class="campaign-name" style="font-size:1.1rem; margin-bottom:4px;">${voucher.sponsor_name ? escapeHtml(voucher.sponsor_name) : '<span class="list-meta">Voucher tự do</span>'}</div>
                    <div class="voucher-code-link" onclick="openVoucherLink('${escapedCode}')" title="Mở link voucher">${escapedCode}</div>
                    <div class="list-meta" style="margin-top:2px;">${escapeHtml(voucher.description || '')}</div>
                    <div class="list-sub"><i class="fas fa-calendar-alt" style="margin-right:4px; color:#94a3b8;"></i>${escapeHtml(voucher.created_at || '')}</div>
                </div>
                <div style="text-align:right;">
                    <span class="voucher-status ${statusClass}">${statusText}</span>
                </div>
            </div>
            <div class="voucher-actions">
                <button class="btn btn-sm btn-teal" onclick="copyCodeWithFeedback(this, '${escapedCode}')" title="Copy mã voucher"><i class="fas fa-barcode"></i></button>
                <button class="btn-copy-link" onclick="copyLinkWithFeedback(this, '${voucherLink}')" title="Copy link voucher"><i class="fas fa-copy"></i></button>
                <button class="btn btn-sm btn-amber" onclick="editFreeVoucher(${vid})" title="Chỉnh sửa"><i class="fas fa-edit"></i></button>
                ${voucher.status === 'used' ? `<button class="btn btn-sm btn-green" onclick="restoreVoucher(${vid}, 'free')" title="Khôi phục"><i class="fas fa-undo"></i></button>` : ''}
                <button class="btn btn-sm btn-red" onclick="deleteFreeVoucher(${vid})" title="Xóa"><i class="fas fa-trash"></i></button>
            </div>
        `;
        container.appendChild(card);
    });
}

// ── Wire up search + filters (idempotent) ─────────────────────────────────
let _freeSearchTimeout;
export function setupFreeVoucherSearch() {
    const debounce = () => {
        clearTimeout(_freeSearchTimeout);
        _freeSearchTimeout = setTimeout(_applyFreeFilters, 200);
    };
    document.getElementById('search-free-voucher')?.addEventListener('input', debounce);
    document.getElementById('filter-free-status')?.addEventListener('change', _applyFreeFilters);
    document.getElementById('filter-free-sort')?.addEventListener('change', _applyFreeFilters);
}

// ── Modal open ────────────────────────────────────────────────────────────
export function openFreeVoucherModal() {
    const form = document.getElementById('free-voucher-form');
    form.reset();
    updateLogoUploader(form, null);
    openModal('free-voucher-modal');
}

// ── Edit ──────────────────────────────────────────────────────────────────
export function editFreeVoucher(id) {
    const token     = ++_editFreeVoucherToken;
    const modal     = document.getElementById('edit-free-voucher-modal');
    const submitBtn = modal.querySelector('button[type="submit"]');
    const origText  = submitBtn.textContent;
    submitBtn.textContent = 'Đang tải...';
    submitBtn.disabled    = true;

    fetch(`api/free_vouchers.php?id=${id}`)
        .then(r => r.json())
        .then(data => {
            if (token !== _editFreeVoucherToken) return;
            if (data.success) {
                document.getElementById('edit-free-voucher-id').value   = id;
                document.getElementById('edit-free-sponsor-name').value = data.voucher.sponsor_name || '';
                document.getElementById('edit-free-description').value  = data.voucher.description  || '';
                document.getElementById('edit-free-voucher-code').value = data.voucher.code         || '';
                const startEl = document.getElementById('edit-free-start-date');
                const endEl   = document.getElementById('edit-free-end-date');
                if (startEl) startEl.value = data.voucher.start_date || '';
                if (endEl)   endEl.value   = data.voucher.end_date   || '';

                populateStructuredContent(modal, data.voucher);

                document.getElementById('edit-free-full-code').textContent = data.voucher.code || '';
                const sponsorDisplay = data.voucher.sponsor_name ? data.voucher.sponsor_name + ' | ' : '';
                document.getElementById('edit-free-description-display').textContent = sponsorDisplay + (data.voucher.description || '');

                const statusMap = {
                    unused:  '<span class="text-success">Chưa sử dụng</span>',
                    used:    '<span class="text-danger">Đã sử dụng</span>',
                    expired: '<span class="text-warning">Hết hạn</span>',
                };
                document.getElementById('edit-free-voucher-status').innerHTML = statusMap[data.voucher.status] || data.voucher.status;

                const title = modal.querySelector('.modal-header h3');
                if (title) title.textContent = `Chỉnh sửa Voucher tự do: ${data.voucher.code}`;

                updateLogoUploader(modal, data.voucher.logo);
                openModal('edit-free-voucher-modal');
            } else {
                showError('Lỗi tải thông tin', data.error);
            }
        })
        .catch(() => showError('Lỗi kết nối', 'Lỗi khi tải thông tin voucher tự do'))
        .finally(() => {
            submitBtn.textContent = origText;
            submitBtn.disabled    = false;
        });
}

// ── Delete ────────────────────────────────────────────────────────────────
export function deleteFreeVoucher(id) {
    showWarning('Xác nhận xóa', 'Bạn có chắc chắn muốn xóa voucher tự do này?', () => {
        const fd = new FormData();
        fd.append('_method', 'DELETE');
        fd.append('id', id);
        apiMutate('api/free_vouchers.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Xóa thành công!', 'Đã xóa voucher tự do thành công');
                    invalidateTabs('vouchers', 'dashboard');
                    loadFreeVouchers();
                } else {
                    showError('Lỗi xóa voucher', data.error || 'Không thể xóa voucher');
                }
            })
            .catch(err => console.error('Error deleting free voucher:', err));
    });
}

// ── Form submit handlers ──────────────────────────────────────────────────
export function initFreeVoucherForms() {
    const createForm = document.getElementById('free-voucher-form');
    if (createForm) {
        createForm.addEventListener('submit', function (e) {
            e.preventDefault();
            apiMutate('api/free_vouchers.php', { method: 'POST', body: new FormData(this) })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showSuccess('Tạo thành công!', 'Đã tạo voucher tự do thành công');
                        invalidateTabs('vouchers', 'dashboard');
                        closeModal('free-voucher-modal');
                        loadFreeVouchers();
                    } else {
                        showError('Lỗi tạo voucher tự do', data.error || 'Không thể tạo voucher tự do');
                    }
                })
                .catch(err => console.error('Error creating free voucher:', err));
        });
    }

    const editForm = document.getElementById('edit-free-voucher-form');
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            apiMutate('api/free_vouchers.php', { method: 'POST', body: new FormData(this) })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showSuccess('Cập nhật thành công!', 'Đã cập nhật voucher tự do thành công');
                        invalidateTabs('vouchers', 'dashboard');
                        closeModal('edit-free-voucher-modal');
                        loadFreeVouchers();
                    } else {
                        showError('Lỗi cập nhật voucher tự do', data.error || 'Không thể cập nhật voucher tự do');
                    }
                })
                .catch(err => console.error('Error updating free voucher:', err));
        });
    }
}

function _getStatusText(status) {
    switch (status) {
        case 'unused':  return 'Chưa sử dụng';
        case 'used':    return 'Đã sử dụng';
        case 'expired': return 'Hết hạn';
        default:        return status;
    }
}
