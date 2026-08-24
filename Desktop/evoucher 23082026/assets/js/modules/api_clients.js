import { openModal, closeModal } from './modal.js';

function showModalSafe(id) {
    const el = document.getElementById(id);
    if (!el) return;
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const modal = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
        modal.show();
    } else {
        openModal(id);
    }
}

function hideModalSafe(id) {
    const el = document.getElementById(id);
    if (!el) return;
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const modalInstance = bootstrap.Modal.getInstance(el);
        if (modalInstance) modalInstance.hide();
    }
    closeModal(id);
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('tableApiClients')) {
        loadApiClients();
    }

    const form = document.getElementById('formApiClient');
    if (form) {
        form.addEventListener('submit', handleApiClientSubmit);
    }
});

export async function loadApiClients() {
    const tbody = document.getElementById('apiClientsTableBody');
    if (!tbody) return;

    try {
        const response = await fetch('api/api_clients.php');
        const res = await response.json();

        if (!res.success || !res.data) {
            tbody.innerHTML = `<tr><td colspan="9" class="text-center py-4 text-danger">Không thể tải danh sách API Clients.</td></tr>`;
            return;
        }

        if (res.data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="fas fa-key fa-2x mb-3 d-block opacity-50"></i>
                        Chưa có đối tác API nào. Hãy bấm <strong>Thêm Đối Tác API Mới</strong> để cấp API Key cho Fan Cứng hoặc các ứng dụng khác.
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = res.data.map(client => renderApiClientRow(client)).join('');
    } catch (err) {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="9" class="text-center py-4 text-danger">Lỗi kết nối máy chủ.</td></tr>`;
    }
}

function renderApiClientRow(client) {
    const scopesList = (client.scopes || '').split(',').map(s => {
        let badgeClass = 'bg-secondary';
        if (s === 'claim') badgeClass = 'bg-success';
        if (s === 'read') badgeClass = 'bg-info text-dark';
        if (s === 'manage') badgeClass = 'bg-danger';
        return `<span class="badge ${badgeClass} me-1">${s}</span>`;
    }).join('');

    let statusBadge = `<span class="badge bg-success">Active</span>`;
    if (client.status === 'suspended') statusBadge = `<span class="badge bg-warning text-dark">Suspended</span>`;
    if (client.status === 'revoked') statusBadge = `<span class="badge bg-danger">Revoked</span>`;

    const dailyLimitText = client.daily_limit > 0 ? `${client.daily_limit} / ngày` : `<span class="text-muted">Không giới hạn</span>`;
    const lastUsedText = client.last_used_at ? new Date(client.last_used_at).toLocaleString('vi-VN') : `<span class="text-muted">Chưa sử dụng</span>`;

    return `
        <tr>
            <td class="ps-4">
                <strong class="text-dark">${escapeHtml(client.client_name)}</strong>
                ${client.notes ? `<div class="small text-muted">${escapeHtml(client.notes)}</div>` : ''}
            </td>
            <td><code class="text-dark">${escapeHtml(client.client_id)}</code></td>
            <td>
                <span class="font-monospace text-muted small">${escapeHtml(client.api_key_masked)}</span>
            </td>
            <td>${scopesList}</td>
            <td>${dailyLimitText}</td>
            <td><code>${client.rate_limit_per_min || 60} req/m</code></td>
            <td>${statusBadge}</td>
            <td class="small">${lastUsedText}</td>
            <td class="pe-4 text-end">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-secondary" onclick="editApiClient(${client.id})" title="Chỉnh sửa">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-warning" onclick="regenApiKey(${client.id}, '${escapeHtml(client.client_name)}')" title="Tạo lại API Key mới">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    ${client.status !== 'revoked' ? `
                        <button class="btn btn-outline-danger" onclick="revokeApiClient(${client.id}, '${escapeHtml(client.client_name)}')" title="Thu hồi API Key">
                            <i class="fas fa-ban"></i>
                        </button>
                    ` : ''}
                </div>
            </td>
        </tr>
    `;
}

export function openCreateApiClientModal() {
    const form = document.getElementById('formApiClient');
    if (!form) return;
    form.reset();

    document.getElementById('api_client_id').value = '';
    document.getElementById('api_client_action').value = 'create';
    document.getElementById('modalApiClientTitle').innerHTML = `<i class="fas fa-key me-2"></i>Thêm Đối Tác API Client Mới`;
    document.getElementById('group_client_status').style.display = 'none';

    // Set default scopes
    document.getElementById('scope_read').checked = true;
    document.getElementById('scope_claim').checked = true;
    document.getElementById('scope_report').checked = true;
    document.getElementById('scope_manage').checked = false;

    showModalSafe('modalApiClient');
}

export async function editApiClient(id) {
    try {
        const response = await fetch('api/api_clients.php');
        const res = await response.json();
        if (!res.success) return;

        const client = res.data.find(c => c.id == id);
        if (!client) return;

        document.getElementById('api_client_id').value = client.id;
        document.getElementById('api_client_action').value = 'UPDATE';
        document.getElementById('modalApiClientTitle').innerHTML = `<i class="fas fa-edit me-2"></i>Chỉnh Sửa API Client: ${escapeHtml(client.client_name)}`;
        
        document.getElementById('client_name').value = client.client_name;
        document.getElementById('client_daily_limit').value = client.daily_limit || 0;
        document.getElementById('client_rate_limit').value = client.rate_limit_per_min || 60;
        document.getElementById('client_notes').value = client.notes || '';
        document.getElementById('client_status').value = client.status || 'active';
        document.getElementById('group_client_status').style.display = 'block';

        const scopes = (client.scopes || '').split(',');
        document.getElementById('scope_read').checked = scopes.includes('read');
        document.getElementById('scope_claim').checked = scopes.includes('claim');
        document.getElementById('scope_report').checked = scopes.includes('report');
        document.getElementById('scope_manage').checked = scopes.includes('manage');

        showModalSafe('modalApiClient');
    } catch (err) {
        console.error(err);
    }
}

export async function handleApiClientSubmit(e) {
    e.preventDefault();

    const selectedScopes = [];
    document.querySelectorAll('.scope-checkbox:checked').forEach(cb => {
        selectedScopes.push(cb.value);
    });

    if (selectedScopes.length === 0) {
        alert('Vui lòng chọn ít nhất 1 quyền hạn (scope)!');
        return;
    }

    const action = document.getElementById('api_client_action').value;
    const payload = {
        action: action,
        id: document.getElementById('api_client_id').value,
        client_name: document.getElementById('client_name').value,
        scopes: selectedScopes.join(','),
        daily_limit: document.getElementById('client_daily_limit').value,
        rate_limit_per_min: document.getElementById('client_rate_limit').value,
        status: document.getElementById('client_status').value,
        notes: document.getElementById('client_notes').value
    };

    try {
        const response = await fetch('api/api_clients.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': window.csrfToken || ''
            },
            body: JSON.stringify(payload)
        });

        const res = await response.json();

        if (res.success) {
            // Close edit/create modal
            hideModalSafe('modalApiClient');

            loadApiClients();

            // If newly created, show full key modal
            if (action === 'create' && res.new_client) {
                document.getElementById('display_client_id').value = res.new_client.client_id;
                document.getElementById('display_api_key').value = res.new_client.api_key;
                
                showModalSafe('modalShowApiKey');
            } else {
                alert(res.message || 'Cập nhật thành công!');
            }
        } else {
            alert(res.error || 'Có lỗi xảy ra!');
        }
    } catch (err) {
        console.error(err);
        alert('Lỗi kết nối máy chủ!');
    }
}

export async function revokeApiClient(id, name) {
    if (!confirm(`Bạn có chắc chắn muốn THU HỒI (Revoke) API Key của "${name}"?\nĐối tác này sẽ ngay lập tức bị ngắt kết nối phát voucher!`)) {
        return;
    }

    try {
        const response = await fetch('api/api_clients.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': window.csrfToken || ''
            },
            body: JSON.stringify({ action: 'revoke', id: id })
        });

        const res = await response.json();
        if (res.success) {
            alert(res.message || 'Đã thu hồi API Key.');
            loadApiClients();
        } else {
            alert(res.error || 'Lỗi thu hồi key.');
        }
    } catch (err) {
        console.error(err);
    }
}

export async function regenApiKey(id, name) {
    if (!confirm(`Bạn có chắc chắn muốn TẠO LẠI (Regenerate) API Key cho "${name}"?\nAPI Key cũ sẽ bị vô hiệu hóa ngay lập tức!`)) {
        return;
    }

    try {
        const response = await fetch('api/api_clients.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': window.csrfToken || ''
            },
            body: JSON.stringify({ action: 'regen_key', id: id })
        });

        const res = await response.json();
        if (res.success && res.new_api_key) {
            loadApiClients();

            document.getElementById('display_client_id').value = `ID Client #${id}`;
            document.getElementById('display_api_key').value = res.new_api_key;
            
            showModalSafe('modalShowApiKey');
        } else {
            alert(res.error || 'Lỗi cấp lại key.');
        }
    } catch (err) {
        console.error(err);
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
