<!-- Free Voucher Modal -->
<div id="free-voucher-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tạo Voucher tự do</h3>
            <button class="close" onclick="closeModal('free-voucher-modal')">&times;</button>
        </div>
        <form id="free-voucher-form" enctype="multipart/form-data">
            <div class="modal-body-scroll">
                <div class="form-group">
                    <label>Tên nhà tài trợ</label>
                    <input type="text" name="sponsor_name" placeholder="VD: the Dé">
                </div>
                <div class="form-group">
                    <label>Hình thức tài trợ</label>
                    <input type="text" name="description" value="TẶNG 1 LY NƯỚC">
                </div>
                <div class="form-group">
                    <label>Logo (tùy chọn)</label>
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
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Ngày bắt đầu (tùy chọn)</label>
                        <input type="date" name="start_date">
                    </div>
                    <div class="form-group">
                        <label>Ngày kết thúc (tùy chọn)</label>
                        <input type="date" name="end_date">
                    </div>
                </div>
                <div class="form-group">
                    <label>Mã code (mỗi mã trên 1 dòng) *</label>
                    <textarea name="codes" rows="4" placeholder="Nhập các mã code, mỗi mã trên 1 dòng"></textarea>
                    <button type="button" class="btn btn-success" onclick="generateRandomCodes(event)" style="margin-top: 10px;">
                        <i class="fas fa-random"></i> Tạo ngẫu nhiên 25 mã
                    </button>
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
                <button type="button" class="btn" onclick="closeModal('free-voucher-modal')">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu Voucher</button>
            </div>
        </form>
    </div>
</div>