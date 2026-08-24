        <div id="dashboard" class="tab-content <?php echo ($currentTab == 'dashboard' || $currentTab == '') ? 'active' : ''; ?>">
            <div class="card">
                <h2>Dashboard - Tổng quan</h2>
                <div class="stats" id="dashboard-stats">
                    <!-- Stats will be loaded here -->
                </div>
                <div style="margin-top: 30px;">
                    <h3>Thống kê theo chiến dịch khả dụng</h3>
                    <div id="campaign-stats-list">
                        <!-- Campaign stats will be loaded here -->
                    </div>
                </div>
                <div style="margin-top: 30px;">
                    <h3>Các e-voucher sử dụng gần nhất</h3>
                    <div id="recent-vouchers-list">
                        <!-- Recent vouchers will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

