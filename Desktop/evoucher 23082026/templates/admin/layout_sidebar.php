<!-- Header -->
    <div class="header">
        <div class="header-content">
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="logo" onclick="switchTab('dashboard')" style="cursor:pointer;">
                <i class="fas fa-ticket-alt"></i>
                <span>E-Voucher Management</span>
            </div>
            <div class="user-info">
                <span>Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <button class="logout-btn" onclick="logout()">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu-overlay" onclick="closeMobileMenu()"></div>
    <div class="mobile-menu">
        <a href="admin.php" class="mobile-menu-item" onclick="switchTab('dashboard'); closeMobileMenu();">
            <i class="fas fa-home"></i> Trang chủ
        </a>
        <div class="mobile-menu-item" onclick="switchTab('dashboard'); closeMobileMenu();">
            <i class="fas fa-chart-line"></i> Tổng quan
        </div>
        <div class="mobile-menu-item" onclick="switchTab('campaigns'); closeMobileMenu();">
            <i class="fas fa-bullhorn"></i> Chiến dịch
        </div>
        <div class="mobile-menu-item" onclick="switchTab('vouchers'); closeMobileMenu();">
            <i class="fas fa-ticket-alt"></i> Kho Voucher
        </div>
        <div class="mobile-menu-item" onclick="switchTab('give'); closeMobileMenu();">
            <i class="fas fa-gift"></i> Tặng Voucher
        </div>
        <div class="mobile-menu-item" onclick="switchTab('vaas_clients'); closeMobileMenu();">
            <i class="fas fa-plug"></i> Đối tác API (VaaS)
        </div>
        <div class="mobile-menu-item" onclick="switchTab('vaas_logs'); closeMobileMenu();">
            <i class="fas fa-history"></i> Nhật ký VaaS
        </div>
        <div class="mobile-menu-item" onclick="switchTab('vaas_analytics'); closeMobileMenu();">
            <i class="fas fa-chart-line"></i> Thống kê VaaS
        </div>
        <div class="mobile-menu-item" onclick="switchTab('settings'); closeMobileMenu();">
            <i class="fas fa-cog"></i> Cài đặt
        </div>
        <div class="mobile-menu-item" onclick="logout(); closeMobileMenu();"
            style="border-top:2px solid var(--color-border,#e2e8f0); margin-top:10px;">
            <i class="fas fa-sign-out-alt"></i> Đăng xuất
        </div>
    </div>

    <!-- Desktop Tab Bar -->
    <div class="container">
        <div class="tabs">
            <button class="tab <?php echo ($currentTab == 'dashboard' || $currentTab == '') ? 'active' : ''; ?>"
                onclick="switchTab('dashboard')">
                <i class="fas fa-chart-line"></i> Tổng quan
            </button>
            <button class="tab <?php echo $currentTab == 'campaigns' ? 'active' : ''; ?>"
                onclick="switchTab('campaigns')">
                <i class="fas fa-bullhorn"></i> Chiến dịch
            </button>
            <button class="tab <?php echo ($currentTab == 'vouchers' || $currentTab == 'free_vouchers') ? 'active' : ''; ?>"
                onclick="switchTab('vouchers')">
                <i class="fas fa-ticket-alt"></i> Kho Voucher
            </button>
            <button class="tab <?php echo ($currentTab == 'give' || $currentTab == 'take') ? 'active' : ''; ?>"
                onclick="switchTab('give')">
                <i class="fas fa-gift"></i> Tặng Voucher
            </button>
            <button class="tab <?php echo $currentTab == 'vaas_clients' ? 'active' : ''; ?>"
                onclick="switchTab('vaas_clients')">
                <i class="fas fa-plug"></i> Đối tác API
            </button>
            <button class="tab <?php echo $currentTab == 'vaas_logs' ? 'active' : ''; ?>"
                onclick="switchTab('vaas_logs')">
                <i class="fas fa-history"></i> Nhật ký VaaS
            </button>
            <button class="tab <?php echo $currentTab == 'vaas_analytics' ? 'active' : ''; ?>"
                onclick="switchTab('vaas_analytics')">
                <i class="fas fa-chart-bar"></i> Thống kê VaaS
            </button>
            <button class="tab <?php echo ($currentTab == 'settings' || $currentTab == 'account') ? 'active' : ''; ?>"
                onclick="switchTab('settings')">
                <i class="fas fa-cog"></i> Cài đặt
            </button>
        </div>