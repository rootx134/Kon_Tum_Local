<!-- Clear All Taken Vouchers Modal -->
<div id="clear-taken-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Xác nhận xóa</h3>
            <button class="close" onclick="closeModal('clear-taken-modal')">&times;</button>
        </div>
        <div class="modal-body-scroll">
            <p>Bạn có chắc muốn xóa tất cả e-voucher đã lấy từ các chiến dịch?</p>
            <p style="color: red; font-weight: bold;">Hành động này không thể hoàn tác!</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" onclick="closeModal('clear-taken-modal')">Hủy</button>
            <button type="button" class="btn btn-danger" onclick="confirmClearAllTakenVouchers()">Xóa tất cả</button>
        </div>
    </div>
</div>
