/**
 * Module Thống kê & Phân tích VaaS
 */

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('stat_vaas_total_issued')) {
        loadVaasAnalytics();
    }
});

export async function loadVaasAnalytics() {
    try {
        const response = await fetch('api/vaas_analytics.php');
        const res = await response.json();

        if (!res.success || !res.data) return;

        const { overall, top_campaigns, client_breakdown } = res.data;

        // Populate summary cards
        document.getElementById('stat_vaas_total_issued').textContent = (overall.total_issued || 0).toLocaleString('vi-VN');
        document.getElementById('stat_vaas_total_redeemed').textContent = (overall.total_redeemed || 0).toLocaleString('vi-VN');
        document.getElementById('stat_vaas_total_revoked').textContent = (overall.total_revoked || 0).toLocaleString('vi-VN');
        document.getElementById('stat_vaas_active_clients').textContent = (overall.active_clients_count || 0).toLocaleString('vi-VN');

        // Render Top Campaigns
        const topBody = document.getElementById('topCampaignsVaasBody');
        if (topBody) {
            if (!top_campaigns || top_campaigns.length === 0) {
                topBody.innerHTML = `<tr><td colspan="2" class="text-center py-4 text-muted">Chưa có lượt cấp phát qua API.</td></tr>`;
            } else {
                topBody.innerHTML = top_campaigns.map(c => `
                    <tr>
                        <td class="ps-3 fw-bold text-dark">${escapeHtml(c.title)}</td>
                        <td class="text-end pe-3 font-monospace fw-bold text-primary">${c.issue_count} voucher</td>
                    </tr>
                `).join('');
            }
        }

        // Render Client Breakdown
        const clientBody = document.getElementById('clientBreakdownVaasBody');
        if (clientBody) {
            if (!client_breakdown || client_breakdown.length === 0) {
                clientBody.innerHTML = `<tr><td colspan="2" class="text-center py-4 text-muted">Chưa có lượt cấp phát qua API.</td></tr>`;
            } else {
                clientBody.innerHTML = client_breakdown.map(cb => `
                    <tr>
                        <td class="ps-3 fw-bold text-dark">${escapeHtml(cb.client_name)}</td>
                        <td class="text-end pe-3 font-monospace fw-bold text-success">${cb.issue_count} voucher</td>
                    </tr>
                `).join('');
            }
        }

    } catch (err) {
        console.error('Lỗi khi tải VaaS Analytics:', err);
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
