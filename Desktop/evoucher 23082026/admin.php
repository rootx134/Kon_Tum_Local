<?php
require_once 'auth.php';
requireLogin();

$currentTab = $_GET['tab'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Quản lý E-Voucher</title>
    <link href="/assets/vendor/fontawesome/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- PWA Settings -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#3B82F6">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/img/icon-192.png">
    <link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png">
    <!-- End PWA -->

    <link rel="stylesheet" href="/assets/css/admin-layout.css?v=<?= filemtime(__DIR__.'/assets/css/admin-layout.css') ?>">
    <link rel="stylesheet" href="/assets/css/admin-components.css?v=<?= filemtime(__DIR__.'/assets/css/admin-components.css') ?>">
    <link rel="stylesheet" href="/assets/css/admin-modal.css?v=<?= filemtime(__DIR__.'/assets/css/admin-modal.css') ?>">
    <link rel="stylesheet" href="/assets/css/admin-forms.css?v=<?= filemtime(__DIR__.'/assets/css/admin-forms.css') ?>">
    <link rel="stylesheet" href="/assets/css/admin.css?v=<?= filemtime(__DIR__.'/assets/css/admin.css') ?>">
</head>

<body>

<?php require 'templates/admin/layout_sidebar.php'; ?>

        <!-- Tab content -->
        <?php require 'templates/admin/tab_dashboard.php'; ?>
        <?php require 'templates/admin/tab_campaigns.php'; ?>
        <?php require 'templates/admin/tab_vouchers.php'; ?>
        <?php require 'templates/admin/tab_give.php'; ?>
        <?php require 'templates/admin/tab_api_clients.php'; ?>
        <?php require 'templates/admin/tab_vaas_logs.php'; ?>
        <?php require 'templates/admin/tab_vaas_analytics.php'; ?>
        <?php require 'templates/admin/tab_settings.php'; ?>

    </div><!-- /.container -->

    <!-- Modals -->
    <?php require 'templates/admin/modals/modal_create_campaign.php'; ?>
    <?php require 'templates/admin/modals/modal_api_client.php'; ?>
    <?php require 'templates/admin/modals/modal_edit_voucher.php'; ?>
    <?php require 'templates/admin/modals/modal_add_voucher.php'; ?>
    <?php require 'templates/admin/modals/modal_free_voucher.php'; ?>
    <?php require 'templates/admin/modals/modal_edit_free_voucher.php'; ?>
    <?php require 'templates/admin/modals/modal_clear_taken.php'; ?>
    <?php require 'templates/admin/modals/modal_change_password.php'; ?>
    <?php require 'templates/admin/modals/modal_notifications.php'; ?>

    <!-- PWA Bottom Navigation (Mobile Only) -->
    <nav class="bottom-nav">
        <a href="javascript:void(0)" class="bottom-nav-item active" onclick="switchTab('dashboard')"
            data-tab="dashboard">
            <i class="fas fa-chart-line"></i>
            <span>Tổng Quan</span>
        </a>
        <a href="javascript:void(0)" class="bottom-nav-item" onclick="switchTab('campaigns')" data-tab="campaigns">
            <i class="fas fa-bullhorn"></i>
            <span>Chiến dịch</span>
        </a>
        <a href="javascript:void(0)" class="bottom-nav-item" onclick="switchTab('give')" data-tab="give">
            <i class="fas fa-gift"></i>
            <span>Tặng Voucher</span>
        </a>
        <a href="javascript:void(0)" class="bottom-nav-item" onclick="toggleMobileMenu()">
            <i class="fas fa-ellipsis-h"></i>
            <span>Mở rộng</span>
        </a>
    </nav>
    <div class="bottom-nav-spacing"></div>

    <script>
        window.CSRF_TOKEN = '<?= htmlspecialchars(getCsrfToken(), ENT_QUOTES, "UTF-8") ?>';
        window.CURRENT_TAB = '<?= htmlspecialchars($currentTab, ENT_QUOTES, "UTF-8") ?>';

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.php').then((reg) => {
                    console.log('SW registered!', reg);
                }).catch((err) => {
                    console.log('SW registration failed: ', err);
                });
            });
        }
    </script>
    <script type="module" src="assets/js/admin.js?v=<?= filemtime(__DIR__.'/assets/js/admin.js') ?>"></script>
</body>

</html>
