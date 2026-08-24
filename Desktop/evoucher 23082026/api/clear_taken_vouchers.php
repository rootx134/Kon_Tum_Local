<?php

require_once __DIR__ . '/api_base.php';
requireApiLogin();
requireCsrf();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service = new \App\Services\GiveTakeService($pdo);
    $result  = $service->clearAllTakenAndGiven();
    jsonResponse($result, $result['success'] ? 200 : 400);
}
?>
