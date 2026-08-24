<div id="settings" class="tab-content <?php echo ($currentTab == 'settings' || $currentTab == 'account') ? 'active' : ''; ?>">

    <!-- Account Info -->
    <div class="card">
        <h2 style="margin-bottom:20px;"><i class="fas fa-user-circle" style="color:var(--color-primary);margin-right:8px;"></i>Tài khoản</h2>
        <div class="form-group">
            <label>Tên đăng nhập</label>
            <input type="text" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button class="btn btn-warning" onclick="openChangePasswordModal()">
                <i class="fas fa-key"></i> Đổi mật khẩu
            </button>
        </div>
    </div>

    <!-- Active Sessions -->
    <div class="card">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:10px;">
            <h3 style="margin:0;">
                <i class="fas fa-mobile-alt" style="color:var(--color-primary);margin-right:8px;"></i>Thiết bị đang đăng nhập
            </h3>
            <button class="btn btn-danger btn-sm" onclick="logoutAllDevices()">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất tất cả
            </button>
        </div>
        <div id="sessions-list">
            <p style="color:var(--color-muted);text-align:center;padding:20px 0;">
                <i class="fas fa-circle-notch fa-spin"></i> Đang tải...
            </p>
        </div>
    </div>

    <!-- Give Message -->
    <div class="card">
        <h2 style="margin-bottom:20px;"><i class="fas fa-cog" style="color:var(--color-primary);margin-right:8px;"></i>Cài đặt chung</h2>
        <div class="form-group">
            <label style="font-weight:600;font-size:15px;">
                <i class="fas fa-comment-alt" style="color:#3b82f6;margin-right:6px;"></i>
                Nội dung tin nhắn tặng E-voucher
            </label>
            <p style="color:var(--color-muted);font-size:13px;margin:6px 0 12px;">
                Tin nhắn gửi kèm link e-voucher khi bấm "Tặng".
                Dùng <code style="background:var(--color-input-bg);padding:2px 6px;border-radius:4px;">link=></code> làm vị trí chèn link.
            </p>
            <textarea id="setting-give-message" rows="4"
                      placeholder="VD: Tặng bạn e-voucher, link=>"></textarea>
            <div style="margin-top:12px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                <button class="btn btn-primary" onclick="saveGiveMessage()">
                    <i class="fas fa-save"></i> Lưu tin nhắn
                </button>
                <button class="btn btn-muted" onclick="resetGiveMessage()">
                    <i class="fas fa-undo"></i> Đặt lại mặc định
                </button>
                <span id="settings-save-status" style="font-size:13px;color:var(--color-success);display:none;">
                    <i class="fas fa-check-circle"></i> Đã lưu
                </span>
            </div>
        </div>
    </div>

    <!-- Appearance -->
    <div class="card">
        <h2 style="margin-bottom:20px;"><i class="fas fa-palette" style="color:var(--color-primary);margin-right:8px;"></i>Giao diện</h2>
        <div class="form-group" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;padding-bottom:20px;border-bottom:1px solid var(--color-border);">
            <div>
                <div style="font-weight:600;font-size:15px;display:flex;align-items:center;gap:8px;">
                    <i id="dark-toggle-icon" class="fas fa-sun" style="color:#f59e0b;"></i>
                    Chế độ tối (Dark Mode)
                </div>
                <p style="color:var(--color-muted);font-size:13px;margin-top:4px;">
                    Giao diện nền tối, dễ nhìn trong môi trường thiếu sáng.
                </p>
            </div>
            <label class="dark-toggle-switch" title="Bật/tắt chế độ tối">
                <input type="checkbox" id="dark-mode-toggle" onchange="toggleDarkMode(this.checked)">
                <span class="dark-toggle-track"></span>
                <span class="dark-toggle-thumb"><i class="fas fa-circle" style="font-size:6px;color:#94a3b8;"></i></span>
            </label>
        </div>
    </div>

    <!-- Cache Management -->
    <div class="card">
        <h3 style="margin-bottom:12px;">
            <i class="fas fa-broom" style="color:var(--color-primary);margin-right:8px;"></i>Quản lý Cache trình duyệt
        </h3>
        <p style="color:var(--color-muted);font-size:13px;margin-bottom:16px;line-height:1.6;">
            Khi cập nhật JS/CSS mà người dùng vẫn thấy phiên bản cũ, bấm nút này để tăng phiên bản cache.
        </p>
        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
            <div style="background:var(--color-input-bg);padding:10px 18px;border-radius:10px;font-size:14px;">
                Phiên bản hiện tại: <strong style="color:var(--color-primary);">v<span id="sw-cache-version">—</span></strong>
            </div>
            <button class="btn btn-warning" onclick="bumpSwCache()">
                <i class="fas fa-sync-alt"></i> Xóa cache &amp; tăng phiên bản
            </button>
        </div>
    </div>

</div>