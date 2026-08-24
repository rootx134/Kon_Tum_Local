<!-- Edit Voucher Modal -->
<div id="edit-voucher-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Chỉnh sửa E-voucher</h3>
            <button class="close" onclick="closeModal('edit-voucher-modal')">&times;</button>
        </div>
        <form id="edit-voucher-form" enctype="multipart/form-data">
            <input type="hidden" id="edit-voucher-id" name="voucher_id">
            <div class="modal-body-scroll">
                <div class="form-group" class="modal-info-box">
                    <label class="modal-section-label">Thông tin E-voucher hiện tại:</label>
                    <div style="margin-top: 10px;">
                        <div><strong>Mã đầy đủ:</strong> <span id="edit-full-code" class="info-code"></span></div>
                        <div><strong>Nhà tài trợ:</strong> <span id="edit-sponsor-name"></span></div>
                        <div><strong>Trạng thái:</strong> <span id="edit-voucher-status"></span></div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Hình thức tài trợ *</label>
                    <input type="text" id="edit-description" name="description" required>
                </div>
                <div class="form-group">
                    <label>Logo riêng cho e-voucher này (Tùy chọn)</label>
                    <div class="logo-input-row">
                        <div class="logo-uploader" onclick="triggerLogoUpload(this)">
                            <input type="file" name="logo" accept="image/*" class="logo-file-input" onchange="previewLogo(this)" style="display: none;">
                            <div class="logo-preview-container">
                                <img class="logo-preview" src="" alt="Logo Preview" style="display: none;">
                                <div class="upload-placeholder">
                                    <i class="fas fa-upload"></i>
                                    <span>Tải lên</span>
                                </div>
                            </div>
                        </div>
                        <div class="logo-paste-area" tabindex="0"
                             onclick="this.focus()"
                             onpaste="handleLogoPaste(event, this.closest('.logo-input-row').querySelector('.logo-uploader'))"
                             title="Bấm vào đây rồi nhấn Ctrl+V để dán ảnh">
                            <i class="fas fa-clipboard"></i>
                            <span>Dán ảnh<br><small>Ctrl+V</small></span>
                        </div>
                    </div>
                    <small>Để trống nếu không thay đổi. Logo mới sẽ ghi đè logo cũ của e-voucher này.</small>
                </div>
                <div class="form-group">
                    <label>Mã code của e-voucher *</label>
                    <input type="text" id="edit-voucher-code" name="voucher_code" required maxlength="5" style="font-family: monospace; text-transform: uppercase;">
                    <small>Chỉ nhập phần code, không bao gồm tên viết tắt nhà tài trợ</small>
                </div>
                <div class="form-group" style="padding: 15px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px;">
                    <label class="modal-section-label">Địa chỉ sử dụng e-voucher</label>
                    <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 10px;">
                        <input type="text" name="guide_address" placeholder="Địa chỉ (VD: 349 Trần Phú, P.Kon Tum)">
                        <input type="text" name="guide_time" placeholder="Giờ hoạt động (VD: 08h00 - 22h00)">
                        <input type="text" name="guide_phone" placeholder="Số điện thoại (VD: 0935.935.263)">
                    </div>
                    <small class="modal-hint">* Phần hướng dẫn các bước sử dụng sẽ được tự động thêm vào cố định.</small>
                </div>
                <div class="form-group" class="modal-border-box">
                    <label class="modal-section-label">Xem Menu được chọn (Tùy chọn)</label>
                    <div class="form-group" class="modal-menu-note-box">
                        <label >
                            <i class="fas fa-sticky-note"></i> Ghi chú hiển thị cho khách
                        </label>
                        <textarea name="menu_note" rows="2"
                                  rows="2"
                                  placeholder="VD: Áp dụng cho 1 ly nước bất kỳ, không áp dụng đồ ăn, không kết hợp KM khác..."></textarea>
                    </div>
                    <textarea name="menu_items" rows="4" placeholder="Nhập danh sách các món, mỗi món 1 dòng..."></textarea>
                    <label style="margin-top: 15px;">Hoặc tải lên ảnh Menu</label>
                    <div class="menu-image-input-row">
                        <div class="menu-image-uploader" onclick="triggerMenuImageUpload(this)">
                            <input type="file" name="menu_image" accept="image/*" class="menu-image-file-input" onchange="previewMenuImage(this)" style="display: none;">
                            <img class="menu-image-preview" src="" alt="" style="display:none;">
                            <div class="upload-placeholder">
                                <i class="fas fa-image"></i>
                                <span>Tải ảnh menu</span>
                            </div>
                        </div>
                        <div class="menu-paste-area" tabindex="0"
                             onclick="this.focus()"
                             onpaste="handleMenuImagePaste(event, this.closest('.menu-image-input-row').querySelector('.menu-image-uploader'))"
                             title="Bấm vào đây rồi nhấn Ctrl+V để dán ảnh menu">
                            <i class="fas fa-clipboard"></i>
                            <span>Dán ảnh<br><small>Ctrl+V</small></span>
                        </div>
                    </div>
                    <input type="hidden" name="existing_menu_image">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="closeModal('edit-voucher-modal')">Hủy</button>
                <button type="submit" class="btn btn-primary">Cập nhật E-voucher</button>
            </div>
        </form>
    </div>
</div>