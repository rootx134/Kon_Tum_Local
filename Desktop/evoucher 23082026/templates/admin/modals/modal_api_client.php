<!-- Modal Tạo/Sửa API Client -->
<div class="modal fade" id="modalApiClient" tabindex="-1" aria-labelledby="modalApiClientTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="modalApiClientTitle">
                    <i class="fas fa-key me-2"></i>Thêm Đối Tác API Client Mới
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formApiClient" autocomplete="off">
                <input type="hidden" id="api_client_id" name="id" value="">
                <input type="hidden" id="api_client_action" name="action" value="create">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="client_name" class="form-label font-weight-bold">Tên Đối Tác / Ứng Dụng <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="client_name" name="client_name" required placeholder="Ví dụ: App Fan Cứng Kon Tum Plus">
                        <div class="form-text">Tên tổ chức hoặc ứng dụng sẽ kết nối phát voucher qua API.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Quyền Hạn (Scopes) <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap gap-3 p-3 bg-light rounded border">
                            <div class="form-check">
                                <input class="form-check-input scope-checkbox" type="checkbox" value="read" id="scope_read" checked>
                                <label class="form-check-label" for="scope_read">
                                    <span class="badge bg-info text-dark">read</span> Đọc thông tin chiến dịch
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input scope-checkbox" type="checkbox" value="claim" id="scope_claim" checked>
                                <label class="form-check-label" for="scope_claim">
                                    <span class="badge bg-success">claim</span> Phát / nhận voucher
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input scope-checkbox" type="checkbox" value="report" id="scope_report" checked>
                                <label class="form-check-label" for="scope_report">
                                    <span class="badge bg-secondary">report</span> Xem báo cáo & thống kê
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input scope-checkbox" type="checkbox" value="manage" id="scope_manage">
                                <label class="form-check-label" for="scope_manage">
                                    <span class="badge bg-danger">manage</span> Quản trị nâng cao
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="client_daily_limit" class="form-label font-weight-bold">Hạn Mức Phát / Ngày</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="client_daily_limit" name="daily_limit" min="0" value="0" placeholder="0 = Không giới hạn">
                                <span class="input-group-text">voucher</span>
                            </div>
                            <div class="form-text">Tổng voucher tối đa client này phát mỗi ngày.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="client_rate_limit" class="form-label font-weight-bold">Tốc Độ Yêu Cầu / Phút</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="client_rate_limit" name="rate_limit_per_min" min="1" value="60">
                                <span class="input-group-text">req/min</span>
                            </div>
                            <div class="form-text">Số request tối đa cho phép trong 1 phút.</div>
                        </div>
                    </div>

                    <div class="mb-3" id="group_client_status" style="display: none;">
                        <label for="client_status" class="form-label font-weight-bold">Trạng Thái API Key</label>
                        <select class="form-select" id="client_status" name="status">
                            <option value="active">Active (Hoạt động)</option>
                            <option value="suspended">Suspended (Tạm dừng)</option>
                            <option value="revoked">Revoked (Thu hồi)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="client_notes" class="form-label font-weight-bold">Ghi Chú / Liên Hệ</label>
                        <textarea class="form-control" id="client_notes" name="notes" rows="2" placeholder="Nhập thông tin người quản trị phía đối tác, email, SĐT..."></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" id="btnSaveApiClient">
                        <i class="fas fa-save me-1"></i> Lưu API Client
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hiển Thị API Key Lần Đầu -->
<div class="modal fade" id="modalShowApiKey" tabindex="-1" aria-labelledby="modalShowApiKeyTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold" id="modalShowApiKeyTitle">
                    <i class="fas fa-check-circle me-2"></i>Tạo API Key Thành Công!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Quan trọng:</strong> Hãy sao chép API Key bên dưới ngay bây giờ. Chuỗi Key này sẽ <strong>KHÔNG KHÔI PHỤC ĐƯỢC</strong> sau khi đóng cửa sổ này!
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small font-weight-bold">CLIENT ID</label>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control font-monospace bg-light" id="display_client_id" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('display_client_id').value)">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small font-weight-bold">API SECRET KEY (EVOUCHER LIVE KEY)</label>
                    <div class="input-group">
                        <input type="text" class="form-control font-monospace fw-bold text-danger bg-light" id="display_api_key" readonly>
                        <button class="btn btn-danger" type="button" onclick="navigator.clipboard.writeText(document.getElementById('display_api_key').value); alert('Đã sao chép API Key!');">
                            <i class="fas fa-copy me-1"></i> Sao Chép
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success fw-bold px-4" data-bs-dismiss="modal">Đã Sao Chép & Đóng</button>
            </div>
        </div>
    </div>
</div>
