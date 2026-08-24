<div id="vaas_logs" class="tab-content <?php echo $currentTab == 'vaas_logs' ? 'active' : ''; ?>">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
            <div>
                <h2><i class="fas fa-history" style="color: #6366f1; margin-right: 8px;"></i> Nhật ký Cấp phát VaaS</h2>
                <p style="color: #64748b; font-size: 0.9rem; margin-top: 4px;">Theo dõi tất cả e-voucher được cấp phát tự động cho đối tác (Fan Cứng, v.v.)</p>
            </div>
            <button class="btn btn-outline-primary" onclick="loadVaasLogs(1)">
                <i class="fas fa-sync-alt"></i> Làm mới
            </button>
        </div>

        <!-- Filter Bar -->
        <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <div style="flex: 1; min-width: 150px;">
                <label style="font-size: 0.8rem; font-weight: 600; color: #475569;">Đối tác (API Client)</label>
                <select id="vaas_filter_client" class="form-control" onchange="loadVaasLogs(1)" style="width: 100%; margin-top: 4px; padding: 8px; border-radius: 6px; border: 1px solid #cbd5e1;">
                    <option value="">Tất cả đối tác</option>
                </select>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label style="font-size: 0.8rem; font-weight: 600; color: #475569;">Trạng thái</label>
                <select id="vaas_filter_status" class="form-control" onchange="loadVaasLogs(1)" style="width: 100%; margin-top: 4px; padding: 8px; border-radius: 6px; border: 1px solid #cbd5e1;">
                    <option value="">Tất cả trạng thái</option>
                    <option value="unused">Chưa sử dụng (unused)</option>
                    <option value="used">Đã dùng tại quán (used)</option>
                    <option value="revoked">Đã thu hồi (revoked)</option>
                </select>
            </div>
            <div style="flex: 2; min-width: 200px;">
                <label style="font-size: 0.8rem; font-weight: 600; color: #475569;">Tìm kiếm</label>
                <div style="display: flex; gap: 6px; margin-top: 4px;">
                    <input type="text" id="vaas_filter_search" placeholder="Mã voucher, User Ref ID, Tên..." style="flex: 1; padding: 8px; border-radius: 6px; border: 1px solid #cbd5e1;" onkeyup="if(event.key==='Enter') loadVaasLogs(1)">
                    <button class="btn btn-primary" onclick="loadVaasLogs(1)"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div style="overflow-x: auto;">
            <table class="data-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: #f1f5f9; border-bottom: 2px solid #cbd5e1; font-size: 0.85rem; color: #334155;">
                        <th style="padding: 12px;">Mã Voucher</th>
                        <th style="padding: 12px;">Chiến dịch</th>
                        <th style="padding: 12px;">Đối tác</th>
                        <th style="padding: 12px;">User Ref ID</th>
                        <th style="padding: 12px;">Tên Khách</th>
                        <th style="padding: 12px;">Thời gian cấp</th>
                        <th style="padding: 12px;">Trạng thái</th>
                        <th style="padding: 12px; text-align: right;">Hành động</th>
                    </tr>
                </thead>
                <tbody id="vaas-logs-tbody">
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 30px; color: #94a3b8;">Đang tải dữ liệu nhật ký...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; flex-wrap: wrap; gap: 10px;">
            <div id="vaas-logs-count" style="font-size: 0.85rem; color: #64748b;">Trang 1 / 1 (0 bản ghi)</div>
            <div style="display: flex; gap: 8px;">
                <button id="vaas-logs-prev" class="btn btn-sm btn-outline-primary" disabled onclick="changeVaasLogsPage(-1)">
                    <i class="fas fa-chevron-left"></i> Trước
                </button>
                <button id="vaas-logs-next" class="btn btn-sm btn-outline-primary" disabled onclick="changeVaasLogsPage(1)">
                    Sau <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentVaasLogsPage = 1;
let totalVaasLogsPages = 1;

function loadVaasLogs(page = 1) {
    currentVaasLogsPage = page;
    const client = document.getElementById('vaas_filter_client').value;
    const status = document.getElementById('vaas_filter_status').value;
    const search = document.getElementById('vaas_filter_search').value;

    const tbody = document.getElementById('vaas-logs-tbody');
    tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 25px; color: #64748b;"><i class="fas fa-spinner fa-spin"></i> Đang tải dữ liệu...</td></tr>';

    fetch(`api/vaas_logs.php?page=${page}&limit=20&client_id=${encodeURIComponent(client)}&status=${encodeURIComponent(status)}&search=${encodeURIComponent(search)}`)
        .then(r => r.json())
        .then(res => {
            if (!res.success) {
                tbody.innerHTML = `<tr><td colspan="8" style="text-align: center; color: #ef4444; padding: 20px;">Lỗi: ${res.error}</td></tr>`;
                return;
            }

            totalVaasLogsPages = res.total_pages || 1;
            document.getElementById('vaas-logs-count').textContent = `Trang ${res.page} / ${totalVaasLogsPages} (${res.total} bản ghi)`;
            document.getElementById('vaas-logs-prev').disabled = (res.page <= 1);
            document.getElementById('vaas-logs-next').disabled = (res.page >= totalVaasLogsPages);

            if (!res.data || res.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 30px; color: #94a3b8;">Không tìm thấy nhật ký cấp phát nào.</td></tr>';
                return;
            }

            tbody.innerHTML = res.data.map(item => {
                const fullCode = (item.sponsor_short || '') + (item.code || '');
                let statusBadge = '<span style="background:#dcfce7; color:#15803d; padding:3px 8px; border-radius:12px; font-size:0.75rem; font-weight:600;">Chưa dùng</span>';
                if (item.status === 'used') {
                    statusBadge = '<span style="background:#f1f5f9; color:#475569; padding:3px 8px; border-radius:12px; font-size:0.75rem; font-weight:600;">Đã dùng tại quán</span>';
                } else if (item.status === 'revoked') {
                    statusBadge = '<span style="background:#fee2e2; color:#b91c1c; padding:3px 8px; border-radius:12px; font-size:0.75rem; font-weight:600;">Đã thu hồi</span>';
                }

                const canRevoke = item.status === 'unused';
                const revokeBtn = canRevoke
                    ? `<button class="btn btn-sm btn-danger" onclick="revokeVaasVoucher(${item.id}, '${fullCode}', '${item.issued_to_user_name || item.issued_to_user_ref}')" title="Thu hồi voucher"><i class="fas fa-ban"></i> Thu hồi</button>`
                    : '<span style="font-size:0.8rem; color:#cbd5e1;">-</span>';

                return `
                    <tr style="border-bottom: 1px solid #f1f5f9; font-size: 0.85rem;">
                        <td style="padding: 10px; font-weight: 700; color: #1e293b;">${fullCode}</td>
                        <td style="padding: 10px;">${item.sponsor_name || '-'}</td>
                        <td style="padding: 10px;"><span style="background:#e0e7ff; color:#3730a3; padding:2px 6px; border-radius:4px; font-size:0.75rem;">${item.client_name || item.issued_to_client_id}</span></td>
                        <td style="padding: 10px; font-family: monospace; color: #64748b;">${item.issued_to_user_ref || '-'}</td>
                        <td style="padding: 10px;">${item.issued_to_user_name || '-'}</td>
                        <td style="padding: 10px; color: #64748b;">${item.issued_via_api_at || '-'}</td>
                        <td style="padding: 10px;">${statusBadge}</td>
                        <td style="padding: 10px; text-align: right;">${revokeBtn}</td>
                    </tr>
                `;
            }).join('');
        })
        .catch(err => {
            tbody.innerHTML = `<tr><td colspan="8" style="text-align: center; color: #ef4444; padding: 20px;">Lỗi kết nối: ${err.message}</td></tr>`;
        });
}

function changeVaasLogsPage(delta) {
    const newPage = currentVaasLogsPage + delta;
    if (newPage >= 1 && newPage <= totalVaasLogsPages) {
        loadVaasLogs(newPage);
    }
}

function revokeVaasVoucher(id, code, user) {
    if (!confirm(`XÁC NHẬN THU HỒI:\n\nBạn có chắc muốn thu hồi mã [${code}] đã cấp cho khách hàng [${user}]?\n\nMã này sẽ ngay lập tức bị vô hiệu hóa.`)) {
        return;
    }

    const formData = new FormData();
    formData.append('voucher_id', id);
    formData.append('action', 'revoke');

    fetch('api/vaas_logs.php', {
        method: 'POST',
        headers: { 'X-CSRF-Token': window.csrfToken || '' },
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            alert('Đã thu hồi voucher thành công!');
            loadVaasLogs(currentVaasLogsPage);
        } else {
            alert('Lỗi thu hồi: ' + res.error);
        }
    })
    .catch(err => alert('Lỗi kết nối: ' + err.message));
}
</script>
