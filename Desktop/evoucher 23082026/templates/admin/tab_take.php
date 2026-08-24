        <div id="take" class="tab-content <?php echo $currentTab == 'take' ? 'active' : ''; ?>">
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>Lấy E-voucher</h2>
                    <div style="display: flex; gap: 10px;">
                        <button class="btn btn-primary" onclick="confirmTakeVouchers()" title="Xác nhận lấy E-voucher">
                            <i class="fas fa-check"></i> Xác nhận
                        </button>
                        <button class="btn btn-danger" onclick="clearAllTakenVouchers()" title="Xóa tất cả E-voucher đã lấy">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div id="take-campaigns-list">
                    <!-- Take campaigns will be loaded here -->
                </div>
            </div>
        </div>

