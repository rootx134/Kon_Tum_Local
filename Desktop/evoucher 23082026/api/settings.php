<?php
require_once __DIR__ . '/api_base.php';
requireApiLogin();

const ALLOWED_KEYS = ['give_message', 'sw_cache_version'];
const WRITABLE_KEYS = ['give_message'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $key = $_GET['key'] ?? null;

    if ($key) {
        if (!in_array($key, ALLOWED_KEYS, true)) {
            jsonResponse(['success' => false, 'error' => 'Key không hợp lệ'], 400);
        }
        $stmt = $pdo->prepare("SELECT `value` FROM settings WHERE `key` = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        jsonResponse(['success' => true, 'value' => $row ? $row['value'] : '']);
    }

    $placeholders = implode(',', array_fill(0, count(ALLOWED_KEYS), '?'));
    $stmt = $pdo->prepare("SELECT `key`, `value` FROM settings WHERE `key` IN ($placeholders)");
    $stmt->execute(ALLOWED_KEYS);
    $data = [];
    foreach ($stmt->fetchAll() as $row) {
        $data[$row['key']] = $row['value'];
    }
    jsonResponse(['success' => true, 'settings' => $data]);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();

    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        jsonResponse(['success' => false, 'error' => 'Dữ liệu không hợp lệ'], 400);
    }

    $key   = $input['key']   ?? '';
    $value = $input['value'] ?? '';

    if (!in_array($key, WRITABLE_KEYS, true)) {
        jsonResponse(['success' => false, 'error' => 'Key không được phép ghi'], 400);
    }

    $stmt = $pdo->prepare("
        INSERT INTO settings (`key`, `value`) VALUES (?, ?)
        ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)
    ");
    $stmt->execute([$key, $value]);

    jsonResponse(['success' => true]);
}
