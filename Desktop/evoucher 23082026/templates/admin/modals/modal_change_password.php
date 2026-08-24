<!-- Change Password Modal -->
<div id="change-password-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Đổi Mật khẩu</h3>
            <button class="close" onclick="closeModal('change-password-modal')">&times;</button>
        </div>
        <form id="change-password-form">
            <div class="modal-body-scroll">
                <div class="form-group">
                    <label>Mật khẩu hiện tại *</label>
                    <input type="password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label>Mật khẩu mới *</label>
                    <input type="password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label>Xác nhận mật khẩu mới *</label>
                    <input type="password" name="confirm_password" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="closeModal('change-password-modal')">Hủy</button>
                <button type="submit" class="btn btn-primary">Đổi Mật khẩu</button>
            </div>
        </form>
    </div>
</div>
