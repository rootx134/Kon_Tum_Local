<?php

require_once __DIR__ . '/api_base.php';
requireApiLogin();

// Lấy thống kê dashboard thông qua service để dễ bảo trì/nâng cấp
$service = new \App\Services\DashboardService($pdo);
$stats   = $service->getAllStats();

// Giữ nguyên format JSON cũ để không phải sửa JS
jsonResponse($stats);
?>
