<?php

require_once __DIR__ . '/api_base.php';
requireApiLogin();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $service = new \App\Services\GiveTakeService($pdo);
    $history = $service->getGiveHistory();
    jsonResponse($history);
}
?>
