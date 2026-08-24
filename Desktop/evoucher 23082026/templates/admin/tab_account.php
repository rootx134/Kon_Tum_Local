        <div id="account" class="tab-content <?php echo $currentTab == 'account' ? 'active' : ''; ?>">
            <div class="card">
                <h2>Thông tin tài khoản</h2>
                <div class="form-group">
                    <label>Tên đăng nhập</label>
                    <input type="text" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                </div>
                <button class="btn btn-warning" onclick="openChangePasswordModal()">
                    <i class="fas fa-key"></i> Đổi mật khẩu
                </button>
            </div>

            <!-- Active Sessions Section -->
            <div class="card" style="margin-top: 20px;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:10px;">
                    <h3 style="margin:0;">
                        <i class="fas fa-mobile-alt" style="color:#3b82f6; margin-right:8px;"></i>
                        Thiết bị đang đăng nhập
                    </h3>
                    <button class="btn btn-danger" onclick="logoutAllDevices()">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất tất cả thiết bị
                    </button>
                </div>
                <div id="sessions-list">
                    <p style="color:#94a3b8; text-align:center; padding:20px 0;">
                        <i class="fas fa-circle-notch fa-spin"></i> Đang tải...
                    </p>
                </div>
            </div>
        </div>
