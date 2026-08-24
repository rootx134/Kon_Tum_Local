        <div id="free_vouchers" class="tab-content <?php echo $currentTab == 'free_vouchers' ? 'active' : ''; ?>">
            <div class="card">
                <h2>Voucher tự do</h2>
                <p style="color: #6b7280; margin-bottom: 15px;">
                    Các e-voucher độc lập, không thuộc chiến dịch nào. Mã trong danh sách này có thể là mã tự tạo hoặc sao chép từ e-voucher trong chiến dịch.
                </p>
                <div class="form-group" style="max-width: 500px; margin-bottom: 20px;">
                    <div style="position:relative;">
                        <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:14px; pointer-events:none;"></i>
                        <input type="text" id="search-free-voucher" placeholder="Tìm kiếm voucher tự do..." style="width: 100%; padding: 12px 12px 12px 36px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; box-sizing:border-box;">
                    </div>
                </div>
                <div style="margin-bottom: 15px;">
                    <button class="btn btn-primary" onclick="openFreeVoucherModal()">
                        <i class="fas fa-plus"></i> Tạo voucher tự do
                    </button>
                </div>
                <div id="free-vouchers-list">
                    <!-- Free vouchers will be loaded here -->
                </div>
            </div>
        </div>

