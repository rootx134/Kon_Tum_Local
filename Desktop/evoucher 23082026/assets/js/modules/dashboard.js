// modules/dashboard.js — Dashboard stats + recent vouchers

import { escapeHtml, showError } from './ui.js';

export function loadDashboard() {
    const timeCache = new Date().getTime();

    fetch('api/dashboard_stats.php?t=' + timeCache)
        .then(r => r.json())
        .then(data => {
            const statsContainer = document.getElementById('dashboard-stats');
            if (statsContainer) {
                statsContainer.innerHTML = `
                    <div class="stat-card">
                        <div class="stat-number">${escapeHtml(data.total_campaigns)}</div>
                        <div class="stat-label">Tổng chiến dịch</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">${escapeHtml(data.total_vouchers)}</div>
                        <div class="stat-label">Tổng e-voucher</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">${escapeHtml(data.used_vouchers)}</div>
                        <div class="stat-label">Đã sử dụng</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">${escapeHtml(data.available_vouchers)}</div>
                        <div class="stat-label">Còn lại</div>
                    </div>
                `;
            }
            _renderCampaignStats(data);
        })
        .catch(err => console.error('Error loading dashboard stats:', err));

    // Load recent vouchers
    fetch('api/recent_vouchers.php?t=' + new Date().getTime())
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('recent-vouchers-list');
            if (!container) return;
            container.innerHTML = '';

            if (data.length === 0) {
                container.innerHTML = '<p class="list-empty">Chưa có e-voucher nào được sử dụng</p>';
                return;
            }

            data.forEach(voucher => {
                const voucherCard = document.createElement('div');
                voucherCard.className = 'card';
                voucherCard.style.marginBottom = '10px';

                const fullCode = voucher.voucher_type === 'free'
                    ? voucher.code
                    : (voucher.sponsor_short + voucher.code);

                const usedDate = new Date(voucher.used_at);
                const fTime = usedDate.toLocaleTimeString('vi-VN', { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' });
                const fDate = usedDate.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' });
                const timeString = `${fTime} ${fDate}`;

                const voucherTypeLabel = voucher.voucher_type === 'free'
                    ? ' <span class="badge-free">(Tự do)</span>'
                    : '';
                const escapedFullCode = escapeHtml(fullCode);

                voucherCard.innerHTML = `
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <div class="dashboard-voucher-name">${escapeHtml(voucher.sponsor_name)}${voucherTypeLabel}</div>
                            <div class="list-meta">Mã: <span class="voucher-code-link" onclick="openVoucherLink('${escapedFullCode}')" title="Click để mở link">${escapedFullCode}</span> | ${timeString}</div>
                        </div>
                        <div style="text-align:right;">
                            <button class="btn btn-sm btn-success" onclick="restoreVoucher(${parseInt(voucher.id)}, '${escapeHtml(voucher.voucher_type || 'campaign')}')" title="Khôi phục e-voucher">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(voucherCard);
            });
        })
        .catch(err => console.error('Error loading recent vouchers:', err));
}

function _renderCampaignStats(data) {
    const container = document.getElementById('campaign-stats-list');
    if (!container) return;
    container.innerHTML = '';

    if (!data.campaign_stats || data.campaign_stats.length === 0) {
        container.innerHTML = '<p class="list-empty">Chưa có chiến dịch khả dụng</p>';
        return;
    }

    data.campaign_stats.forEach(campaign => {
        const card = document.createElement('div');
        card.className = 'card';
        card.style.marginBottom = '15px';

        const usedPct = campaign.total_vouchers > 0
            ? Math.round((campaign.used_count / campaign.total_vouchers) * 100)
            : 0;

        let barColor = '#4caf50';
        if (usedPct > 50 && usedPct <= 75) barColor = '#ff9800';
        else if (usedPct > 75)             barColor = '#f44336';

        card.innerHTML = `
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;">
                <div style="flex:1; min-width:200px;">
                    <div class="campaign-name">${escapeHtml(campaign.sponsor_name)}</div>
                    <div class="list-meta">
                        <span>Tổng: <strong>${escapeHtml(campaign.total_vouchers)}</strong></span> |
                        <span class="text-success">Còn lại: <strong>${escapeHtml(campaign.unused_count)}</strong></span> |
                        <span class="text-danger">Đã dùng: <strong>${escapeHtml(campaign.used_count)}</strong></span>
                    </div>
                </div>
                <div style="min-width:150px;">
                    <div class="list-sub" style="margin-bottom:5px;">Tiến độ sử dụng</div>
                    <div class="progress-track">
                        <div class="progress-bar" style="background:${barColor}; width:${usedPct}%;">
                            ${usedPct}%
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(card);
    });
}