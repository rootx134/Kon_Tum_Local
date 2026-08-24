<?php

require_once __DIR__ . '/api_base.php';
require_once __DIR__ . '/../includes/audit_log.php';
requireApiLogin();
requireCsrf();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $currentPassword = $data['current_password'] ?? '';
    $newPassword     = $data['new_password'] ?? '';
    $confirmPassword = $data['confirm_password'] ?? '';

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        jsonResponse(['success' => false, 'error' => 'Vui lòng điền đầy đủ thông tin'], 400);
    }

    if ($newPassword !== $confirmPassword) {
        jsonResponse(['success' => false, 'error' => 'Mật khẩu mới không khớp'], 400);
    }

    if (strlen($newPassword) < 6) {
        jsonResponse(['success' => false, 'error' => 'Mật khẩu mới phải có ít nhất 6 ký tự'], 400);
    }

    // Verify current password
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!password_verify($currentPassword, $user['password'])) {
        jsonResponse(['success' => false, 'error' => 'Mật khẩu hiện tại không đúng'], 400);
    }

    // Update password
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $result = $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $_SESSION['user_id']]);

    if ($result) {
        // Regenerate session after password change for safety
        session_regenerate_id(true);
        auditLog($pdo, 'update', 'user', (int)$_SESSION['user_id'], 'Changed password');
        jsonResponse(['success' => true]);
    } else {
        jsonResponse(['success' => false, 'error' => 'Có lỗi xảy ra khi cập nhật mật khẩu'], 500);
    }
}
