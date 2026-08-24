<?php
require_once __DIR__ . '/api_base.php';
requireApiLogin();
requireCsrf();

$service = new \App\Services\GiveTakeService($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Danh sách chiến dịch có voucher khả dụng để lấy
    $campaigns = $service->getCampaignsWithAvailableVouchers();
    jsonResponse($campaigns);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy (giữ chỗ) voucher cụ thể từ chiến dịch
    $data      = json_decode(file_get_contents('php://input'), true) ?: [];
    $campaigns = $data['campaigns'] ?? [];

    $result = $service->takeVouchers($campaigns);
    jsonResponse($result, $result['success'] ? 200 : 400);
}
?>
