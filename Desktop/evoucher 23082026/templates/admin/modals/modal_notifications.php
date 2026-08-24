<!-- Notification Modals -->

<!-- Success Modal -->
<div id="success-notification-modal" class="modal notification-modal">
    <div class="modal-content">
        <div class="notification-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="notification-title" id="success-title">Thành công!</div>
        <div class="notification-message" id="success-message">Thao tác đã được thực hiện thành công.</div>
        <div class="notification-actions">
            <button class="notification-btn success"
                onclick="closeModal('success-notification-modal')">Đóng</button>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div id="error-notification-modal" class="modal notification-modal">
    <div class="modal-content">
        <div class="notification-icon error">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="notification-title" id="error-title">Có lỗi xảy ra!</div>
        <div class="notification-message" id="error-message">Đã xảy ra lỗi trong quá trình xử lý.</div>
        <div class="notification-actions">
            <button class="notification-btn danger" onclick="closeModal('error-notification-modal')">Đóng</button>
        </div>
    </div>
</div>

<!-- Warning Modal -->
<div id="warning-notification-modal" class="modal notification-modal">
    <div class="modal-content">
        <div class="notification-icon warning">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="notification-title" id="warning-title">Cảnh báo!</div>
        <div class="notification-message" id="warning-message">Bạn có chắc chắn muốn thực hiện hành động này?</div>
        <div class="notification-actions">
            <button class="notification-btn secondary"
                onclick="closeModal('warning-notification-modal')">Hủy</button>
            <button class="notification-btn danger" id="warning-confirm-btn">Xác nhận</button>
        </div>
    </div>
</div>

<!-- Info Modal -->
<div id="info-notification-modal" class="modal notification-modal">
    <div class="modal-content">
        <div class="notification-icon info">
            <i class="fas fa-info-circle"></i>
        </div>
        <div class="notification-title" id="info-title">Thông tin</div>
        <div class="notification-message" id="info-message">Thông tin quan trọng cần lưu ý.</div>
        <div class="notification-actions">
            <button class="notification-btn primary" onclick="closeModal('info-notification-modal')">Đóng</button>
        </div>
    </div>
</div>
