// modules/sessions.js — Multi-device session management

import { apiMutate } from './api.js';
import { escapeHtml, showSuccess, showError, showWarning } from './ui.js';

export function loadSessions() {
    const list = document.getElementById('sessions-list');
    if (!list) return;

    fetch('api/sessions.php')
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                list.innerHTML = '<p style="color:#ef4444;">Không thể tải danh sách thiết bị.</p>';
                return;
            }
            if (!data.sessions || data.sessions.length === 0) {
                list.innerHTML = '<p style="color:#94a3b8; text-align:center; padding:20px 0;">Không có thiết bị nào.</p>';
                return;
            }
            list.innerHTML = data.sessions.map(s => `
                <div class="session-item ${s.is_current ? 'session-current' : ''}">
                    <div style="flex:1; min-width:0;">
                        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                            <i class="fas ${s.device && s.device.toLowerCase().includes('mobile') ? 'fa-mobile-alt' : 'fa-desktop'}"
                               style="color:#3b82f6;"></i>
                            <strong style="font-size:14px;">${escapeHtml(s.device || 'Thiết bị không xác định')}</strong>
                            ${s.is_current ? '<span class="session-badge-current">Thiết bị này</span>' : ''}
                        </div>
                        <div class="session-meta">
                            <i class="fas fa-map-marker-alt" style="margin-right:4px;"></i>${escapeHtml(s.ip || '')}
                            &nbsp;·&nbsp;
                            <i class="fas fa-clock" style="margin-right:4px;"></i>Hoạt động: ${escapeHtml(s.last_seen || '')}
                        </div>
                    </div>
                    ${!s.is_current ? `
                        <button class="btn btn-sm btn-danger" style="margin-left:12px; white-space:nowrap;"
                                onclick="revokeSession('${escapeHtml(s.id)}')">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </button>` : ''}
                </div>
            `).join('');
        })
        .catch(() => {
            if (list) list.innerHTML = '<p style="color:#ef4444;">Lỗi kết nối. Vui lòng thử lại.</p>';
        });
}

export function revokeSession(sessionId) {
    const fd = new FormData();
    fd.append('_method', 'DELETE');
    fd.append('session_id', sessionId);
    apiMutate('api/sessions.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showSuccess('Đã đăng xuất', 'Thiết bị đã bị thu hồi quyền truy cập.');
                loadSessions();
            } else {
                showError('Lỗi', data.error || 'Không thể đăng xuất thiết bị này.');
            }
        })
        .catch(() => showError('Lỗi kết nối', 'Vui lòng thử lại.'));
}

export function logoutAllDevices() {
    showWarning(
        'Đăng xuất tất cả thiết bị',
        'Bạn sẽ bị đăng xuất khỏi TẤT CẢ thiết bị, bao gồm thiết bị này. Tiếp tục?',
        function () {
            const fd = new FormData();
            fd.append('_method', 'DELETE');
            fd.append('all', '1');
            apiMutate('api/sessions.php', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    if (data.redirect) window.location.href = data.redirect;
                    else if (!data.success) showError('Lỗi', data.error || 'Không thể đăng xuất tất cả thiết bị.');
                })
                .catch(() => showError('Lỗi kết nối', 'Vui lòng thử lại.'));
        }
    );
}
