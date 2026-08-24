<!-- Tab Phân Tích & Báo Cáo VaaS -->
<div id="vaas_analytics" class="tab-content <?php echo $currentTab == 'vaas_analytics' ? 'active' : ''; ?>">
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 font-weight-bold text-dark"><i class="fas fa-chart-line text-success me-2"></i>Thống Kê & Phân Tích VaaS</h4>
        <p class="text-muted small mb-0">Theo dõi lưu lượng phát voucher qua API, hiệu suất đối tác và tỷ lệ quy đổi thực tế.</p>
    </div>
    <div>
        <button class="btn btn-outline-secondary btn-sm" onclick="loadVaasAnalytics()">
            <i class="fas fa-sync-alt me-1"></i> Làm Mới
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small text-white-50 font-weight-bold">TỔNG VOUCHER ĐÃ PHÁT API</span>
                    <i class="fas fa-paper-plane fa-lg text-white-50"></i>
                </div>
                <h3 class="fw-bold mb-0" id="stat_vaas_total_issued">0</h3>
                <div class="small text-white-50 mt-1">Qua cổng Voucher-as-a-Service</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small text-white-50 font-weight-bold">ĐÃ SỬ DỤNG / ĐỔI</span>
                    <i class="fas fa-check-circle fa-lg text-white-50"></i>
                </div>
                <h3 class="fw-bold mb-0" id="stat_vaas_total_redeemed">0</h3>
                <div class="small text-white-50 mt-1">Khách hàng đã sử dụng voucher</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-danger text-white h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small text-white-50 font-weight-bold">ĐÃ THU HỒI</span>
                    <i class="fas fa-ban fa-lg text-white-50"></i>
                </div>
                <h3 class="fw-bold mb-0" id="stat_vaas_total_revoked">0</h3>
                <div class="small text-white-50 mt-1">Voucher API đã bị hủy bỏ</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-dark text-white h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small text-white-50 font-weight-bold">ĐỐI TÁC HOẠT ĐỘNG</span>
                    <i class="fas fa-handshake fa-lg text-white-50"></i>
                </div>
                <h3 class="fw-bold mb-0" id="stat_vaas_active_clients">0</h3>
                <div class="small text-white-50 mt-1">Client ID đang kết nối</div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Breakdown Tables / Charts -->
<div class="row g-4 mb-4">
    <!-- Top Campaigns via API -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-2 fw-bold text-dark">
                <i class="fas fa-fire text-danger me-2"></i>Top Chiến Dịch Được Phát Qua API Tối Đa
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th class="ps-3">Tên Chiến Dịch</th>
                                <th class="text-end pe-3">Số Lượng Phát</th>
                            </tr>
                        </thead>
                        <tbody id="topCampaignsVaasBody">
                            <tr>
                                <td colspan="2" class="text-center py-4 text-muted">Chưa có dữ liệu thống kê.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Client Distribution -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-2 fw-bold text-dark">
                <i class="fas fa-users-cog text-info me-2"></i>Phân Bố Theo Đối Tác API Client
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th class="ps-3">Tên Đối Tác</th>
                                <th class="text-end pe-3">Tổng Voucher Đã Cấp</th>
                            </tr>
                        </thead>
                        <tbody id="clientBreakdownVaasBody">
                            <tr>
                                <td colspan="2" class="text-center py-4 text-muted">Chưa có dữ liệu thống kê.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
