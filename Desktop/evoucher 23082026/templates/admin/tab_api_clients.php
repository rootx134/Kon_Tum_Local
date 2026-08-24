<!-- Tab Quản lý API Clients (VaaS Partners) -->
<div id="vaas_clients" class="tab-content <?php echo $currentTab == 'vaas_clients' ? 'active' : ''; ?>">
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 font-weight-bold text-dark"><i class="fas fa-plug text-primary me-2"></i>Đối Tác VaaS & API Keys</h4>
        <p class="text-muted small mb-0">Quản lý các ứng dụng đối tác (như Fan Cứng Kon Tum Plus) tích hợp phát voucher tự động qua API.</p>
    </div>
    <div>
        <button class="btn btn-primary shadow-sm font-weight-bold" onclick="openCreateApiClientModal()">
            <i class="fas fa-plus-circle me-1"></i> Thêm Đối Tác API Mới
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableApiClients">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4">Tên Đối Tác</th>
                        <th>Client ID</th>
                        <th>API Key</th>
                        <th>Quyền (Scopes)</th>
                        <th>Hạn Mức / Ngày</th>
                        <th>Rate Limit</th>
                        <th>Trạng Thái</th>
                        <th>Lần Cuối Dùng</th>
                        <th class="pe-4 text-end">Thao Tác</th>
                    </tr>
                </thead>
                <tbody id="apiClientsTableBody">
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                            Đang tải danh sách API Clients...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
