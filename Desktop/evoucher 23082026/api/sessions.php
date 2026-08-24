<?php
// ============================================================
// api/sessions.php  –  Manage active device sessions
// ============================================================
// GET  → list all active sessions for the current user
// POST (_method=DELETE, session_id=X) → revoke one device
// POST (_method=DELETE, all=1)        → logout all devices
// ============================================================

require_once __DIR__ . '/api_base.php';
require_once __DIR__ . '/../includes/audit_log.php';
requireApiLogin();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    jsonResponse(['success' => true, 'sessions' => getActiveSessions()]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    $data = $_POST;

    if (($data['_method'] ?? '') === 'DELETE') {
        $sessionId = $data['session_id'] ?? null;
        $all       = !empty($data['all']);

        // Logout ALL devices
        if ($all) {
            $userId = $_SESSION['user_id'] ?? null;
            auditLog($pdo, 'logout_all', 'session', $userId, 'Logout all devices');
            logoutAllDevices();
            jsonResponse(['success' => true, 'redirect' => 'login.php']);
        }

        // Revoke a single session (must belong to current user)
        if ($sessionId) {
            $userId = $_SESSION['user_id'] ?? 0;
            $stmt   = $pdo->prepare("
                DELETE FROM user_sessions WHERE id = ? AND user_id = ?
            ");
            $stmt->execute([$sessionId, $userId]);
            auditLog($pdo, 'logout_device', 'session', $userId,
                'Revoked session: ' . substr($sessionId, 0, 8) . '...');
            jsonResponse(['success' => true]);
        }

        jsonResponse(['success' => false, 'error' => 'Thiếu tham số'], 400);
    }
}

jsonResponse(['success' => false, 'error' => 'Method not allowed'], 405);
