<?php

require_once __DIR__ . '/api_base.php';
requireApiLogin();
requireCsrf();

$service = new \App\Services\GiveTakeService($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Thống kê đã lấy/đã tặng/còn lại
    try {
        $stats = $service->getGiveStats();
        jsonResponse($stats);
    } catch (Exception $e) {
        error_log('Error in give_vouchers.php GET: ' . $e->getMessage());
        jsonResponse([
            'total_taken' => 0,
            'total_given' => 0,
            'remaining'   => 0,
        ]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tặng một e-voucher
    $data        = json_decode(file_get_contents('php://input'), true) ?: [];
    $baseMessage = isset($data['message']) ? (string) $data['message'] : '';

    $result = $service->giveOneVoucher($baseMessage);
    jsonResponse($result, $result['success'] ? 200 : 400);
}
?>
