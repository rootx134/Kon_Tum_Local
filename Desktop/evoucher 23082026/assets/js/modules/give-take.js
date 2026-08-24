// modules/give-take.js — Give & Take voucher logic

import { apiMutate } from './api.js';
import { escapeHtml, showSuccess, showError, showWarning, showInfo, copyTextToClipboard } from './ui.js';
import { openModal, closeModal } from './modal.js';
import { getGiveMessage } from './settings.js';

// ── Give tab subtab state ─────────────────────────────────────────────────
let _currentGiveSubtab = 'give';
let _khoFilter         = '';
let _selectedTakenIds  = new Set();

export function switchGiveSubtab(which) {
    _currentGiveSubtab = which;
    ['give', 'kho'].forEach(k => {
        document.getElementById(`give-panel-${k}`)?.style.setProperty('display', k === which ? '' : 'none');
        document.getElementById(`gstab-${k}`)?.classList.toggle('active', k === which);
    });

    // Sticky give button only visible on 'give' subtab
    const bar = document.getElementById('gtab-action-bar');
    if (bar) bar.classList.toggle('visible', which === 'give');

    if (which === 'kho') loadTakenItems();
}

// ── Load give tab ─────────────────────────────────────────────────────────
export function loadGiveTab() {
    loadGiveStats();
    getGiveMessage().then(msg => {
        const el = document.getElementById('give-message');
        if (el && !el.value) el.value = msg;
    });
    // Show action bar on initial load (default subtab = give)
    const bar = document.getElementById('gtab-action-bar');
    if (bar) bar.classList.add('visible');
}

// ── Campaigns available to take from ────────────────────────────────────
export function loadTakeCampaigns() {
    fetch('api/take_vouchers.php')
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('take-campaigns-list');
            if (!container) return;

            if (!data.length) {
                container.innerHTML = '<p class="give-empty" style="padding:16px 0;">Không có chiến dịch nào còn voucher chưa lấy.</p>';
                return;
            }

            container.innerHTML = '';
            data.forEach(campaign => {
                const card = document.createElement('div');
                card.className = 'campaign-card';
                card.innerHTML = `
                    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px;">
                        <div style="flex:1; min-width:0;">
                            <div class="campaign-name">${escapeHtml(campaign.sponsor_name)}</div>
                            <div class="campaign-date">
                                Có sẵn: <strong style="color:var(--color-primary)">${campaign.available_count}</strong> voucher chưa lấy
                            </div>
                        </div>
                        <label class="gtab-take-check">
                            <input type="checkbox"
                                   data-campaign-id="${campaign.id}"
                                   data-max-quantity="${campaign.available_count}"
                                   onchange="toggleCampaignSelection(this)">
                            <span>Chọn<br><small>(${campaign.available_count})</small></span>
                        </label>
                    </div>
                `;
                container.appendChild(card);
            });
        })
        .catch(err => console.error('Error loading take campaigns:', err));
}

export function toggleCampaignSelection(checkbox) {
    if (!window.selectedCampaigns) window.selectedCampaigns = {};
    const id = checkbox.dataset.campaignId;
    if (checkbox.checked) {
        window.selectedCampaigns[id] = parseInt(checkbox.dataset.maxQuantity);
    } else {
        delete window.selectedCampaigns[id];
    }
}

export function confirmTakeVouchers() {
    const campaigns = Object.entries(window.selectedCampaigns || {})
        .map(([id, quantity]) => ({ id: parseInt(id), quantity }));

    if (!campaigns.length) {
        showWarning('Chưa chọn chiến dịch', 'Vui lòng chọn ít nhất một chiến dịch.');
        return;
    }

    apiMutate('api/take_vouchers.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ campaigns }),
    })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showSuccess('Lấy thành công!', `Đã giữ chỗ ${data.total_taken} voucher vào kho.`);
                window.selectedCampaigns = {};
                loadGiveStats();
                loadTakeCampaigns();
            } else {
                showError('Lỗi', data.error);
            }
        })
        .catch(err => console.error('Error taking vouchers:', err));
}

// ── Stats ─────────────────────────────────────────────────────────────────
export function loadGiveStats() {
    fetch('api/give_vouchers.php?_t=' + Date.now())
        .then(r => r.json())
        .then(data => {
            document.querySelector('.give-stat-taken .give-stat-num')?.let?.(el => el.textContent = data.total_taken);
            document.querySelector('.give-stat-given .give-stat-num')?.let?.(el => el.textContent = data.total_given);
            document.querySelector('.give-stat-remaining .give-stat-num')?.let?.(el => el.textContent = data.remaining);

            // Fallback for browsers that don't support ?.let?.()
            const takenEl     = document.querySelector('.give-stat-taken .give-stat-num');
            const givenEl     = document.querySelector('.give-stat-given .give-stat-num');
            const remainingEl = document.querySelector('.give-stat-remaining .give-stat-num');
            if (takenEl)     takenEl.textContent     = data.total_taken;
            if (givenEl)     givenEl.textContent     = data.total_given;
            if (remainingEl) remainingEl.textContent = data.remaining;

            const countEl = document.getElementById('give-btn-count');
            if (countEl) countEl.textContent = data.remaining;

            const btn = document.getElementById('give-btn');
            if (btn) btn.disabled = data.remaining <= 0;

            loadGiveHistory();
        })
        .catch(err => console.error('Error loading give stats:', err));
}

// ── Give history (no limit) ───────────────────────────────────────────────
export function loadGiveHistory() {
    fetch('api/give_history.php?_t=' + Date.now())
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('give-history');
            if (!container) return;

            if (!data.length) {
                container.innerHTML = '<p class="give-empty">Chưa có lịch sử tặng e-voucher</p>';
                return;
            }

            container.innerHTML = '';
            data.forEach(item => {
                const el   = document.createElement('div');
                el.className = 'give-history-item';
                const link   = escapeHtml(item.link);
                el.innerHTML = `
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;">
                        <div style="min-width:0;">
                            <div class="give-history-code">
                                <i class="fas fa-ticket-alt" style="color:#3b82f6;margin-right:6px;font-size:13px;"></i>
                                ${escapeHtml(item.voucher_code)}
                            </div>
                            <div class="give-history-time">
                                <i class="fas fa-clock" style="margin-right:4px;"></i>${escapeHtml(item.given_at)}
                                &nbsp;·&nbsp;${escapeHtml(item.sponsor_name)}
                            </div>
                        </div>
                        <button class="give-history-copy-btn" onclick="copyLinkWithFeedback(this, '${link}')" title="Copy link">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                `;
                container.appendChild(el);
            });
        })
        .catch(err => console.error('Error loading give history:', err));
}

// ── Give one voucher — debounced, race-condition safe ─────────────────────
let _giving = false;

export function giveVoucher() {
    if (_giving) return;          // block double-tap
    _giving = true;

    const message  = document.getElementById('give-message')?.value || '';
    const giveBtn  = document.getElementById('give-btn');
    const origHtml = giveBtn?.innerHTML;
    if (giveBtn) { giveBtn.disabled = true; giveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tặng...'; }

    const reqPromise = apiMutate('api/give_vouchers.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ message }),
    }).then(r => r.json());

    // Try modern clipboard API (async copy while waiting for response)
    let usedClipboardItem = false;
    if (navigator.clipboard && window.ClipboardItem && window.isSecureContext) {
        try {
            const blobPromise = reqPromise.then(data => {
                if (data.success) return new Blob([data.message], { type: 'text/plain' });
                throw new Error(data.error || 'Lỗi API');
            });
            navigator.clipboard.write([new ClipboardItem({ 'text/plain': blobPromise })]);
            usedClipboardItem = true;
        } catch (e) { /* Safari or unsupported */ }
    }

    reqPromise
        .then(data => {
            if (data.success) {
                if (!usedClipboardItem) copyTextToClipboard(data.message).then(() => {});
                loadGiveHistory();
                loadGiveStats();
            } else {
                showError('Lỗi tặng', data.error);
            }
        })
        .catch(() => showError('Lỗi kết nối', 'Không thể tặng e-voucher. Vui lòng thử lại.'))
        .finally(() => {
            _giving = false;
            if (giveBtn) { giveBtn.disabled = false; giveBtn.innerHTML = origHtml; }
        });
}

// ── Kho đã lấy ───────────────────────────────────────────────────────────
export function setKhoFilter(btn, filter) {
    _khoFilter = filter;
    document.querySelectorAll('.kho-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    _selectedTakenIds.clear();
    _syncUndoBar();
    loadTakenItems();
}

export function loadTakenItems() {
    const url = _khoFilter
        ? `api/taken_items.php?status=${_khoFilter}`
        : 'api/taken_items.php';

    document.getElementById('taken-items-list').innerHTML =
        '<p class="give-empty">Đang tải...</p>';

    fetch(url)
        .then(r => r.json())
        .then(data => _renderTakenItems(data))
        .catch(() => {
            document.getElementById('taken-items-list').innerHTML =
                '<p class="give-empty" style="color:var(--color-danger);">Lỗi tải dữ liệu.</p>';
        });
}

function _renderTakenItems(items) {
    const container = document.getElementById('taken-items-list');
    const selectAllRow = document.getElementById('kho-select-all-row');
    if (!container) return;

    if (!items.length) {
        container.innerHTML = '<p class="give-empty">Không có voucher nào.</p>';
        if (selectAllRow) selectAllRow.style.display = 'none';
        return;
    }

    if (selectAllRow) selectAllRow.style.display = '';
    const allCheck = document.getElementById('kho-check-all');
    if (allCheck) allCheck.checked = false;

    // Group by campaign
    const grouped = {};
    items.forEach(item => {
        if (!grouped[item.campaign_id]) {
            grouped[item.campaign_id] = { name: item.sponsor_name, items: [] };
        }
        grouped[item.campaign_id].items.push(item);
    });

    container.innerHTML = '';
    Object.values(grouped).forEach(group => {
        const availableCount = group.items.filter(i => i.status === 'available').length;
        const givenCount     = group.items.filter(i => i.status === 'given').length;

        const groupEl = document.createElement('div');
        groupEl.className = 'kho-campaign-group';
        groupEl.innerHTML = `
            <div class="kho-campaign-header">
                <span>${escapeHtml(group.name)}</span>
                <div class="kho-badges">
                    ${availableCount ? `<span class="kho-badge kho-badge-available">${availableCount} chưa tặng</span>` : ''}
                    ${givenCount     ? `<span class="kho-badge kho-badge-given">${givenCount} đã tặng</span>` : ''}
                </div>
            </div>
        `;

        group.items.forEach(item => {
            const isSelected  = _selectedTakenIds.has(item.id);
            const statusLabel = { available: 'Chưa tặng', given: 'Đã tặng', returned: 'Đã hoàn trả' }[item.status] || item.status;
            const statusClass = `kho-status-${item.status}`;

            // Hiện đúng thời điểm theo trạng thái
            const timeIcon  = item.status === 'given'
                ? '<i class="fas fa-gift" style="color:#8b5cf6;margin-right:4px;"></i>'
                : item.status === 'returned'
                ? '<i class="fas fa-undo-alt" style="color:#6b7280;margin-right:4px;"></i>'
                : '<i class="fas fa-inbox" style="color:#3b82f6;margin-right:4px;"></i>';
            const timeLabel = item.status === 'given' ? 'Tặng lúc' : item.status === 'returned' ? 'Hoàn trả lúc' : 'Lấy lúc';
            const timeVal   = item.status === 'given' && item.given_at
                ? item.given_at.slice(0, 16).replace('T', ' ')
                : item.status === 'returned' && item.returned_at
                    ? item.returned_at.slice(0, 16).replace('T', ' ')
                    : (item.taken_at ? item.taken_at.slice(0, 16).replace('T', ' ') : '');

            const row = document.createElement('div');
            row.className = `kho-voucher-row${isSelected ? ' selected' : ''}`;
            row.dataset.id = item.id;
            row.innerHTML = `
                <input type="checkbox" class="kho-voucher-check"
                       data-id="${item.id}"
                       ${isSelected ? 'checked' : ''}
                       onchange="toggleTakenItem(this)"
                       ${item.status === 'returned' ? 'disabled' : ''}>
                <div style="flex:1; min-width:0;">
                    <div class="kho-voucher-code">${escapeHtml(item.full_code)}</div>
                    <div class="kho-voucher-meta">${timeIcon} ${timeLabel}: ${timeVal}</div>
                </div>
                <span class="voucher-status ${statusClass}">${statusLabel}</span>
            `;
            groupEl.appendChild(row);
        });

        container.appendChild(groupEl);
    });
}

export function toggleTakenItem(checkbox) {
    const id  = parseInt(checkbox.dataset.id);
    const row = checkbox.closest('.kho-voucher-row');
    if (checkbox.checked) {
        _selectedTakenIds.add(id);
        row?.classList.add('selected');
    } else {
        _selectedTakenIds.delete(id);
        row?.classList.remove('selected');
    }
    _syncUndoBar();
}

export function toggleAllTakenItems(masterCheck) {
    document.querySelectorAll('.kho-voucher-check:not(:disabled)').forEach(cb => {
        cb.checked = masterCheck.checked;
        const id  = parseInt(cb.dataset.id);
        const row = cb.closest('.kho-voucher-row');
        if (masterCheck.checked) {
            _selectedTakenIds.add(id);
            row?.classList.add('selected');
        } else {
            _selectedTakenIds.delete(id);
            row?.classList.remove('selected');
        }
    });
    _syncUndoBar();
}

function _syncUndoBar() {
    const bar     = document.getElementById('kho-undo-bar');
    const countEl = document.getElementById('kho-undo-count');
    const n       = _selectedTakenIds.size;
    if (bar)     bar.style.display    = n > 0 ? 'block' : 'none';
    if (countEl) countEl.textContent  = n;
}

export function undoSelectedTakenItems() {
    if (!_selectedTakenIds.size) return;
    const ids = [..._selectedTakenIds];
    const n   = ids.length;

    showWarning(
        'Xác nhận hoàn tác',
        `Hoàn trả ${n} voucher về kho chiến dịch. Các mã này sẽ được lấy lại bình thường.`,
        () => {
            apiMutate('api/taken_items.php', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify({ ids }),
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showSuccess('Hoàn tác thành công!', `Đã trả ${data.count} voucher về kho.`);
                        _selectedTakenIds.clear();
                        _syncUndoBar();
                        loadTakenItems();
                        loadGiveStats();
                    } else {
                        showError('Lỗi', data.error);
                    }
                })
                .catch(() => showError('Lỗi kết nối', 'Vui lòng thử lại.'));
        }
    );
}

// ── Clear all ─────────────────────────────────────────────────────────────
export function clearAllTakenVouchers() {
    fetch('api/give_vouchers.php')
        .then(r => r.json())
        .then(data => {
            if ((data.total_taken || 0) > 0) {
                const p = document.querySelector('#clear-taken-modal .modal-body p');
                if (p) p.innerHTML =
                    `Xóa toàn bộ kho: <strong>${data.total_taken}</strong> voucher đã lấy (bao gồm đã tặng).`;
                openModal('clear-taken-modal');
            } else {
                showInfo('Kho trống', 'Không có voucher nào trong kho.');
            }
        });
}

export function confirmClearAllTakenVouchers() {
    apiMutate('api/clear_taken_vouchers.php', { method: 'POST' })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showSuccess('Đã xóa kho!', 'Toàn bộ dữ liệu lấy/tặng đã được reset.');
                closeModal('clear-taken-modal');
                loadGiveStats();
                loadTakenItems();
            } else {
                showError('Lỗi', data.error);
            }
        });
}

// ── Link helpers ──────────────────────────────────────────────────────────
export function copyLink(link) {
    copyTextToClipboard(link).then(() => showSuccess('Thành công', 'Đã sao chép liên kết'));
}

export function copyLinkWithFeedback(btn, link) {
    copyTextToClipboard(link).then(() => {
        const orig = btn.innerHTML;
        btn.classList.add('copied');
        btn.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => { btn.classList.remove('copied'); btn.innerHTML = orig; }, 2000);
    });
}

export function copyCodeWithFeedback(btn, code) {
    copyTextToClipboard(code).then(() => {
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => { btn.innerHTML = orig; }, 2000);
    });
}

export function openVoucherLink(code) {
    window.open(`${window.location.origin}/${code}`, '_blank');
}

export function loadGiveTab_alias() { return loadGiveTab(); }
