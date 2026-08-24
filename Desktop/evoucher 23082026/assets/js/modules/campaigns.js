// modules/campaigns.js — Campaign CRUD

import { apiMutate } from './api.js';
import { escapeHtml, showSuccess, showError, showWarning, invalidateTabs } from './ui.js';
import { openModal, closeModal } from './modal.js';
import { updateLogoUploader } from './uploader.js';
import { populateStructuredContent } from './ui.js';

// Request token — prevents stale async responses from overwriting newer data
let _editCampaignToken = 0;

export function loadCampaigns() {
    fetch('api/campaigns.php')
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('campaigns-list');
            if (!container) return;
            container.innerHTML = '';

            data.forEach(campaign => {
                const card = document.createElement('div');
                card.className = 'campaign-card';
                const cid       = parseInt(campaign.id);
                const isExpired = new Date() >= new Date(campaign.end_date);

                const usedCount  = parseInt(campaign.used_count  || 0);
                const totalCount = parseInt(campaign.voucher_count || 0);
                const unusedCount = totalCount - usedCount;
                const usedPct    = totalCount > 0 ? Math.round(usedCount / totalCount * 100) : 0;
                const barColor   = usedPct >= 75 ? '#ef4444' : usedPct >= 40 ? '#f59e0b' : '#10b981';

                const expiredBadge = isExpired
                    ? `<span style="background:#fee2e2; color:#ef4444; font-size:0.75rem; padding:2px 6px; border-radius:4px; font-weight:600; margin-left:8px; vertical-align:middle;">Đã hết hạn</span>`
                    : '';

                const apiAvailable = parseInt(campaign.api_available_qty || 0);
                const apiIssued    = parseInt(campaign.api_issued_count || 0);
                const isApiVisible = parseInt(campaign.api_visible || 0) === 1;

                card.innerHTML = `
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:15px; flex-wrap:wrap; gap:10px;">
                        <div style="flex:1; min-width:200px;">
                            <div class="campaign-name">${escapeHtml(campaign.sponsor_name)}${expiredBadge}</div>
                            <div class="campaign-date"><i class="fas fa-calendar-alt" style="margin-right:4px; color:#94a3b8;"></i>Ngày tạo: ${escapeHtml(campaign.created_at)}</div>
                        </div>
                        <div style="display:flex; gap:8px;">
                            <button class="btn btn-sm btn-muted" onclick="editCampaign(${cid})" title="Chỉnh sửa"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-primary" onclick="addVouchersToCampaign(${cid})" title="Thêm thẻ"><i class="fas fa-plus"></i></button>
                            <button class="btn btn-sm btn-danger" onclick="deleteCampaign(${cid})" title="Xoá"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    <div class="vaas-badge-bar" style="margin-bottom:12px; padding:10px 14px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span style="font-size:0.85rem; font-weight:600; color:#334155;"><i class="fas fa-plug" style="color:#6366f1;"></i> Phát qua API (VaaS):</span>
                            <label style="position:relative; display:inline-block; width:44px; height:24px; margin:0; cursor:pointer;">
                                <input type="checkbox" ${isApiVisible ? 'checked' : ''} onchange="window.toggleCampaignApiVisible(${cid}, this.checked)" style="opacity:0; width:0; height:0;">
                                <span style="position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background-color:${isApiVisible ? '#10b981' : '#cbd5e1'}; transition:.3s; border-radius:24px; display:block;">
                                    <span style="position:absolute; content:''; height:18px; width:18px; left:${isApiVisible ? '22px' : '3px'}; bottom:3px; background-color:white; transition:.3s; border-radius:50%;"></span>
                                </span>
                            </label>
                            <span style="font-size:0.8rem; font-weight:600; color:${isApiVisible ? '#059669' : '#64748b'};">${isApiVisible ? 'ĐANG BẬT' : 'TẮT'}</span>
                        </div>
                        <div style="font-size:0.8rem; color:#475569;">
                            API Kho trống: <strong style="color:#059669;">${apiAvailable}</strong> | 
                            Đã cấp qua API: <strong style="color:#6366f1;">${apiIssued}</strong>
                        </div>
                    </div>
                    <div class="campaign-footer">
                        <div class="campaign-usage">
                            <div class="campaign-usage-nums">
                                <span class="usage-used" title="Đã dùng">
                                    <i class="fas fa-check-circle"></i> ${usedCount}
                                </span>
                                <span class="usage-sep">/</span>
                                <span class="usage-total" title="Tổng">${totalCount}</span>
                                <span class="usage-label">mã đã dùng</span>
                                <span class="usage-remaining" title="Còn lại chưa dùng">(còn ${unusedCount})</span>
                            </div>
                            <div class="campaign-progress-bar">
                                <div class="campaign-progress-fill" style="width:${usedPct}%; background:${barColor};"></div>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline-primary" onclick="toggleVouchers(${cid})">
                            <i class="fas fa-chevron-down"></i> Xem ds mã
                        </button>
                    </div>
                    <div class="voucher-list" id="vouchers-${cid}" style="display:none; margin-top:15px;"></div>
                `;
                container.appendChild(card);
            });
        })
        .catch(err => console.error('Error loading campaigns:', err));
}

export function loadCampaignVouchers(campaignId) {
    fetch(`api/vouchers.php?campaign_id=${campaignId}`)
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById(`vouchers-${campaignId}`);
            if (!container) return;
            container.innerHTML = '';

            data.forEach(voucher => {
                const item = document.createElement('div');
                item.className = 'voucher-item';
                const fullCode        = voucher.sponsor_short + voucher.code;
                const escapedFullCode = escapeHtml(fullCode);
                const vid         = parseInt(voucher.id);
                const currentTime = new Date();
                const isNotYetValid = currentTime < new Date(voucher.start_date);
                const isExpired     = currentTime >= new Date(voucher.end_date);

                let statusHtml;
                if (voucher.status === 'used') {
                    let usedText = 'Đã sử dụng';
                    if (voucher.used_at) {
                        const d = new Date(voucher.used_at);
                        const fTime = d.toLocaleTimeString('vi-VN', { hour12: false, hour: '2-digit', minute: '2-digit' });
                        const fDate = d.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: '2-digit' });
                        usedText = `Dùng lúc ${fTime} ${fDate.replace(/\//g, '-')}`;
                    }
                    statusHtml = `<span class="voucher-status status-used used-status">${usedText}</span>`;
                } else if (isNotYetValid) {
                    statusHtml = `<span class="voucher-status status-unavailable">Chưa khả dụng</span>`;
                } else {
                    statusHtml = `<span class="voucher-status status-unused">Chưa sử dụng</span>`;
                }

                item.className = 'voucher-item-row';
                item.innerHTML = `
                    <div style="display:flex; justify-content:space-between; align-items:center; width:100%;">
                        <div class="voucher-code-link" onclick="openVoucherLink('${escapedFullCode}')" title="Click để mở link">${escapedFullCode}</div>
                        <div>${statusHtml}</div>
                    </div>
                    <div style="display:flex; gap:6px; width:100%; justify-content:flex-end;">
                        <button class="btn btn-sm btn-indigo" onclick="copyVoucherToFree(${vid})" title="Copy sang Voucher tự do"><i class="fas fa-bolt"></i></button>
                        <button class="btn btn-sm btn-amber" onclick="editVoucherDetails(${vid})" title="Chỉnh sửa"><i class="fas fa-edit"></i></button>
                        ${(voucher.status === 'used' && !isExpired) ? `<button class="btn btn-sm btn-green" onclick="restoreVoucher(${vid}, 'campaign')" title="Khôi phục"><i class="fas fa-undo"></i></button>` : ''}
                        <button class="btn btn-sm btn-red" onclick="deleteVoucher(${vid})" title="Xóa"><i class="fas fa-trash"></i></button>
                    </div>
                `;
                container.appendChild(item);
            });
        })
        .catch(err => console.error('Error loading vouchers:', err));
}

export function toggleVouchers(campaignId) {
    const container = document.getElementById(`vouchers-${campaignId}`);
    if (!container) return;
    if (container.style.display === 'none') {
        container.style.display = 'block';
        loadCampaignVouchers(campaignId);
    } else {
        container.style.display = 'none';
    }
}

window.toggleCampaignApiVisible = function(campaignId, isChecked) {
    const formData = new FormData();
    formData.append('id', campaignId);
    formData.append('_method', 'PUT');
    formData.append('api_visible', isChecked ? 1 : 0);

    apiMutate('api/campaigns.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showSuccess('Cập nhật VaaS', isChecked ? 'Đã BẬT phát qua API cho chiến dịch này' : 'Đã TẮT phát qua API');
                invalidateTabs('campaigns');
                loadCampaigns();
            } else {
                showError('Lỗi cập nhật VaaS', data.error);
                loadCampaigns();
            }
        })
        .catch(err => {
            showError('Lỗi kết nối', err.message);
            loadCampaigns();
        });
};

export function openCreateCampaignModal() {
    const form = document.getElementById('create-campaign-form');
    form.removeAttribute('data-edit-id');
    form.reset();
    updateLogoUploader(form, null);

    const vaasCheckbox = form.querySelector('#campaign_api_visible');
    if (vaasCheckbox) vaasCheckbox.checked = false;
    const vaasOptions = document.getElementById('vaas-options');
    if (vaasOptions) vaasOptions.style.display = 'none';

    const modal = document.getElementById('create-campaign-modal');
    const title = modal.querySelector('.modal-header h3');
    if (title) title.textContent = 'Tạo Chiến dịch Mới';
    const submitBtn = modal.querySelector('button[type="submit"]');
    if (submitBtn) submitBtn.textContent = 'Tạo Chiến dịch';

    openModal('create-campaign-modal');
}

export function editCampaign(campaignId) {
    const token = ++_editCampaignToken; // invalidates any pending previous call

    Promise.all([
        fetch(`api/campaigns.php?id=${campaignId}`).then(r => r.json()),
        fetch(`api/vouchers.php?campaign_id=${campaignId}`).then(r => r.json()),
    ])
        .then(([campaignData, vouchersData]) => {
            if (token !== _editCampaignToken) return; // stale response — discard
            const campaign = Array.isArray(campaignData) ? campaignData[0] : campaignData;
            if (!campaign || !campaign.id) {
                showError('Lỗi tải thông tin', 'Dữ liệu chiến dịch không hợp lệ');
                return;
            }

            const form = document.getElementById('create-campaign-form');
            form.querySelector('input[name="sponsor_name"]').value  = campaign.sponsor_name  || '';
            form.querySelector('input[name="sponsor_short"]').value = campaign.sponsor_short || '';
            form.querySelector('input[name="description"]').value   = campaign.description   || '';
            form.querySelector('input[name="start_date"]').value    = campaign.start_date    || '';
            form.querySelector('input[name="end_date"]').value      = campaign.end_date      || '';

            // VaaS fields
            const isApiVisible = parseInt(campaign.api_visible || 0) === 1;
            const vaasCheckbox = form.querySelector('#campaign_api_visible');
            if (vaasCheckbox) vaasCheckbox.checked = isApiVisible;
            const vaasOptions = document.getElementById('vaas-options');
            if (vaasOptions) vaasOptions.style.display = isApiVisible ? 'block' : 'none';

            const dailyQuotaInput = form.querySelector('input[name="api_daily_quota"]');
            if (dailyQuotaInput) dailyQuotaInput.value = campaign.api_daily_quota || 0;
            const maxUserInput = form.querySelector('input[name="max_per_user"]');
            if (maxUserInput) maxUserInput.value = campaign.max_per_user || 1;
            const pointsInput = form.querySelector('input[name="points_required"]');
            if (pointsInput) pointsInput.value = campaign.points_required || 0;

            populateStructuredContent(form, campaign);

            const codesTextarea = form.querySelector('textarea[name="codes"]');
            if (codesTextarea) {
                if (vouchersData && Array.isArray(vouchersData) && vouchersData.length > 0) {
                    codesTextarea.value = vouchersData.map(v => v.code).join('\n');
                }
                codesTextarea.disabled = false;
                const codesField = codesTextarea.closest('.form-group');
                if (codesField) {
                    codesField.style.display = 'block';
                    const label = codesField.querySelector('label');
                    if (label) label.textContent = 'Mã code (mỗi mã trên 1 dòng, có thể thêm/xóa/sửa)';
                }
            }

            form.setAttribute('data-edit-id', campaignId);
            const modal = document.getElementById('create-campaign-modal');
            const title = modal.querySelector('.modal-header h3');
            if (title) title.textContent = 'Chỉnh sửa Chiến dịch';
            const submitBtn = modal.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.textContent = 'Cập nhật Chiến dịch';

            updateLogoUploader(form, campaign.logo);
            openModal('create-campaign-modal');
        })
        .catch(err => showError('Lỗi kết nối', 'Không thể tải thông tin chiến dịch: ' + err.message));
}

export function addVouchersToCampaign(campaignId) {
    document.getElementById('add-campaign-id').value = campaignId;
    const form = document.getElementById('add-voucher-form');
    updateLogoUploader(form, null);
    openModal('add-voucher-modal');
}

export function deleteCampaign(campaignId) {
    showWarning('Xác nhận xóa chiến dịch',
        'Bạn có chắc chắn muốn xóa chiến dịch này? Tất cả e-voucher trong chiến dịch cũng sẽ bị xóa.',
        () => _performDeleteCampaign(campaignId));
}

function _performDeleteCampaign(campaignId) {
    apiMutate(`api/campaigns.php?id=${campaignId}`, { method: 'DELETE' })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showSuccess('Xóa thành công!', 'Đã xóa chiến dịch thành công');
                invalidateTabs('campaigns', 'dashboard', 'vouchers');
                loadCampaigns();
            } else {
                showError('Lỗi xóa chiến dịch', data.error);
            }
        })
        .catch(err => console.error('Error deleting campaign:', err));
}

// ── Form submit handlers ──────────────────────────────────────────────────
export function initCampaignForms() {
    const createForm = document.getElementById('create-campaign-form');
    if (createForm) {
        createForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const editId   = this.getAttribute('data-edit-id');
            const formData = new FormData(this);

            if (editId) {
                formData.append('id', editId);
                formData.append('_method', 'PUT');
                apiMutate('api/campaigns.php', { method: 'POST', body: formData })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showSuccess('Cập nhật thành công!', 'Đã cập nhật chiến dịch thành công');
                            closeModal('create-campaign-modal');
                            invalidateTabs('campaigns', 'dashboard', 'vouchers');
                            loadCampaigns();
                        } else {
                            showError('Lỗi cập nhật chiến dịch', data.error);
                        }
                    })
                    .catch(err => console.error('Error updating campaign:', err));
            } else {
                apiMutate('api/campaigns.php', { method: 'POST', body: formData })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showSuccess('Tạo thành công!', 'Đã tạo chiến dịch thành công');
                            closeModal('create-campaign-modal');
                            invalidateTabs('campaigns', 'dashboard');
                            loadCampaigns();
                        } else {
                            showError('Lỗi tạo chiến dịch', data.error);
                        }
                    })
                    .catch(err => console.error('Error creating campaign:', err));
            }
        });
    }

    const addForm = document.getElementById('add-voucher-form');
    if (addForm) {
        addForm.addEventListener('submit', function (e) {
            e.preventDefault();
            apiMutate('api/campaigns.php', { method: 'POST', body: new FormData(this) })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showSuccess('Thêm thành công!', 'Đã thêm e-voucher thành công');
                        closeModal('add-voucher-modal');
                        invalidateTabs('campaigns', 'vouchers', 'dashboard');
                        loadCampaigns();
                    } else {
                        showError('Lỗi thêm e-voucher', data.error);
                    }
                })
                .catch(err => console.error('Error adding vouchers:', err));
        });
    }
}
