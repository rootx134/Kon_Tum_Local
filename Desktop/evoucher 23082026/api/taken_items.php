<?php
require_once __DIR__ . '/api_base.php';
requireApiLogin();
requireCsrf();

$service = new \App\Services\GiveTakeService($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $statusFilter = $_GET['status'] ?? '';
    jsonResponse($service->getTakenItems($statusFilter));

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true) ?: [];
    $ids  = $data['ids'] ?? [];

    if (empty($ids) || !is_array($ids)) {
        jsonResponse(['success' => false, 'error' => 'Danh sách ID không hợp lệ'], 400);
    }

    $result = $service->undoTakeItems($ids);
    jsonResponse($result, $result['success'] ? 200 : 400);
}
?>
