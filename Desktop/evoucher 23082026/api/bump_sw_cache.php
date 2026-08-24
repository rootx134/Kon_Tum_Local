<?php
require_once __DIR__ . '/api_base.php';
requireApiLogin();
requireCsrf();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'error' => 'Method not allowed'], 405);
}

$pdo->exec("
    INSERT INTO settings (`key`, `value`) VALUES ('sw_cache_version', 1)
    ON DUPLICATE KEY UPDATE `value` = CAST(`value` AS UNSIGNED) + 1
");

$stmt = $pdo->prepare("SELECT `value` FROM settings WHERE `key` = 'sw_cache_version'");
$stmt->execute();
$row = $stmt->fetch();
$newVersion = (int) ($row['value'] ?? 1);

jsonResponse(['success' => true, 'version' => $newVersion]);
