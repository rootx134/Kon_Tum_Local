<?php
/**
 * WePla - Desktop SaaS Layout
 * True SaaS experience with sidebar, topbar, and high information density.
 */

$pageTitle = $pageTitle ?? 'WePla';
$activePage = $activePage ?? 'dashboard';
$wedding = $wedding ?? null;
$currentUser = $currentUser ?? Auth::user();
$role = $role ?? Auth::getWeddingRole();
$appConfig = $appConfig ?? require __DIR__ . '/../config/app.php';
$roleLabel = $appConfig['roles'][$role] ?? 'Khách';
$userInitials = mb_substr($currentUser['full_name'] ?? 'U', 0, 1);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - WePla Desktop</title>
    <meta name="csrf-token" content="<?= Auth::csrfToken() ?>">
    
    <!-- Core UI -->
    <link rel="stylesheet" href="/public/css/app.css?v=<?= strval($appConfig['version'] ?? '1.0.0') ?>">
    <link rel="stylesheet" href="/public/css/components.css?v=<?= strval($appConfig['version'] ?? '1.0.0') ?>">
    <link rel="stylesheet" href="/public/css/desktop-saas.css?v=<?= time() ?>">
    
    <?php if (isset($extraCss)):
        foreach ($extraCss as $css): ?>
            <link rel="stylesheet" href="<?= $css ?>">
        <?php endforeach; endif; ?>
        
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/vn.js"></script>
</head>

<body class="desktop-body">
    <?php if ($wedding): ?>
    <!-- Sidebar -->
    <aside class="desktop-sidebar">
        <div class="sidebar-header">
            <div class="brand-logo">
                <i data-lucide="heart-handshake"></i> WePla
            </div>
            <div class="wedding-switcher">
                <?= htmlspecialchars($wedding['bride_name']) ?> & <?= htmlspecialchars($wedding['groom_name']) ?>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">QUẢN LÝ</div>
            <a href="/dashboard" class="nav-item <?= $activePage === 'dashboard' ? 'active' : '' ?>">
                <i data-lucide="layout-dashboard"></i> Dashboard
            </a>
            <a href="/timeline" class="nav-item <?= $activePage === 'timeline' ? 'active' : '' ?>">
                <i data-lucide="kanban-square"></i> Lịch trình
            </a>
            <a href="/budget" class="nav-item <?= $activePage === 'budget' ? 'active' : '' ?>">
                <i data-lucide="pie-chart"></i> Ngân sách
            </a>
            <a href="/guests" class="nav-item <?= $activePage === 'guests' ? 'active' : '' ?>">
                <i data-lucide="users"></i> Khách mời
            </a>
            <a href="/materials" class="nav-item <?= $activePage === 'materials' ? 'active' : '' ?>">
                <i data-lucide="package"></i> Cơ sở vật chất
            </a>
        </nav>

        <div class="sidebar-footer">
            <button class="ai-trigger-btn" onclick="AiAssistant.toggle()">
                <i data-lucide="sparkles"></i> Luna AI Assistant
            </button>
            <?php if (($currentUser['role'] ?? '') === 'admin'): ?>
            <a href="/admin" class="nav-item">
                <i data-lucide="shield"></i> Chuyển sang Admin
            </a>
            <?php endif; ?>
            <a href="/settings" class="nav-item <?= $activePage === 'settings' ? 'active' : '' ?>">
                <i data-lucide="settings"></i> Cài đặt
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="desktop-main">
        <!-- Top Header -->
        <header class="desktop-header">
            <div class="header-search">
                <i data-lucide="search"></i>
                <input type="text" placeholder="Tìm kiếm công việc, khách mời, khoản chi...">
            </div>
            
            <div class="header-actions">
                <button class="header-icon-btn" onclick="toggleNotifPanel()">
                    <i data-lucide="bell"></i>
                    <span id="notif-badge" class="badge">0</span>
                </button>
                
                <div class="user-profile-dropdown">
                    <div class="avatar"><?= $userInitials ?></div>
                    <span class="user-name"><?= htmlspecialchars($currentUser['full_name']) ?></span>
                    <i data-lucide="chevron-down"></i>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="desktop-content-scroll">
            <div class="desktop-content-inner">
                <?php if (isset($content)) echo $content; ?>
            </div>
        </div>
        
        <!-- Floating AI Widget (Stage 12.1) -->
        <div class="ai-floating-widget" onclick="AiAssistant.toggle()">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);">
                <i data-lucide="sparkles" style="width: 18px; height: 18px;"></i>
            </div>
            <div>
                <div style="font-weight: 700; font-size: 14px; color: var(--desktop-text); line-height: 1.2;">Luna AI</div>
                <div style="font-size: 12px; color: var(--primary); font-weight: 500;">Trợ lý thông minh</div>
            </div>
        </div>
    </main>
    <?php else: ?>
        <div class="desktop-content-inner">
            <?php if (isset($content)) echo $content; ?>
        </div>
    <?php endif; ?>

    <script src="/public/js/app.js?v=<?= strval($appConfig['version'] ?? '1.0.0') ?>"></script>
    <script src="/public/js/api.js?v=<?= strval($appConfig['version'] ?? '1.0.0') ?>"></script>
    <script src="/public/js/ai-assistant.js?v=<?= strval($appConfig['version'] ?? '1.0.0') ?>"></script>
    <?php if (isset($extraJs)):
        foreach ($extraJs as $js): ?>
            <script src="<?= $js ?>"></script>
        <?php endforeach; endif; ?>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
