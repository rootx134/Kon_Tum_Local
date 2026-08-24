<?php
require_once __DIR__ . '/api_base.php';
require_once __DIR__ . '/../includes/audit_log.php';
requireApiLogin();
requireCsrf();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $pdo->query("
        SELECT id, client_name, client_id, api_key, scopes, status, daily_limit, rate_limit_per_min, notes, last_used_at, created_at
        FROM api_clients
        ORDER BY created_at DESC
    ");
    $clients = $stmt->fetchAll();

    // Mask API Key for security (only show first 8 chars + ****) except when newly generated
    foreach ($clients as &$c) {
        if (!empty($c['api_key'])) {
            $c['api_key_masked'] = substr($c['api_key'], 0, 10) . '****************';
        } else {
            $c['api_key_masked'] = 'N/A';
        }
    }

    jsonResponse(['success' => true, 'data' => $clients]);

} elseif ($method === 'POST') {
    $data = $_POST;
    if (empty($data)) {
        $jsonInput = json_decode(file_get_contents('php://input'), true);
        if (is_array($jsonInput)) {
            $data = $jsonInput;
        }
    }

    $action = $data['action'] ?? ($data['_method'] ?? 'create');

    if ($action === 'create') {
        $clientName = trim($data['client_name'] ?? '');
        $scopes     = trim($data['scopes'] ?? 'read,claim,report');
        $dailyLimit = (int)($data['daily_limit'] ?? 0);
        $rateLimit  = (int)($data['rate_limit_per_min'] ?? 60);
        $notes      = trim($data['notes'] ?? '');

        if ($clientName === '') {
            jsonResponse(['success' => false, 'error' => 'Tên đối tác không được để trống'], 400);
        }

        $rawApiKey = 'ev_live_' . bin2hex(random_bytes(16));
        $clientId  = 'client_' . bin2hex(random_bytes(4));

        try {
            $stmt = $pdo->prepare("
                INSERT INTO api_clients (client_name, client_id, api_key, scopes, status, daily_limit, rate_limit_per_min, notes)
                VALUES (?, ?, ?, ?, 'active', ?, ?, ?)
            ");
            $stmt->execute([$clientName, $clientId, $rawApiKey, $scopes, $dailyLimit, $rateLimit, $notes]);
            $dbId = $pdo->lastInsertId();

            auditLog($pdo, 'create', 'api_client', $dbId, "Created API client: {$clientName}");

            jsonResponse([
                'success' => true,
                'message' => 'Tạo API Client thành công',
                'new_client' => [
                    'id'          => $dbId,
                    'client_id'   => $clientId,
                    'client_name' => $clientName,
                    'api_key'     => $rawApiKey, // Full API key returned ONLY once
                    'scopes'      => $scopes
                ]
            ]);
        } catch (Exception $e) {
            jsonResponse(['success' => false, 'error' => 'Lỗi tạo API Client: ' . $e->getMessage()], 500);
        }

    } elseif ($action === 'UPDATE' || $action === 'PUT') {
        $id         = (int)($data['id'] ?? 0);
        $clientName = trim($data['client_name'] ?? '');
        $scopes     = trim($data['scopes'] ?? 'read,claim,report');
        $status     = trim($data['status'] ?? 'active');
        $dailyLimit = (int)($data['daily_limit'] ?? 0);
        $rateLimit  = (int)($data['rate_limit_per_min'] ?? 60);
        $notes      = trim($data['notes'] ?? '');

        if ($id <= 0 || $clientName === '') {
            jsonResponse(['success' => false, 'error' => 'Dữ liệu không hợp lệ'], 400);
        }

        try {
            $stmt = $pdo->prepare("
                UPDATE api_clients
                SET client_name = ?, scopes = ?, status = ?, daily_limit = ?, rate_limit_per_min = ?, notes = ?
                WHERE id = ?
            ");
            $stmt->execute([$clientName, $scopes, $status, $dailyLimit, $rateLimit, $notes, $id]);

            auditLog($pdo, 'update', 'api_client', $id, "Updated API client {$clientName}");

            jsonResponse(['success' => true, 'message' => 'Đã cập nhật API Client thành công']);
        } catch (Exception $e) {
            jsonResponse(['success' => false, 'error' => 'Lỗi cập nhật: ' . $e->getMessage()], 500);
        }

    } elseif ($action === 'revoke') {
        $id = (int)($data['id'] ?? 0);
        if ($id <= 0) {
            jsonResponse(['success' => false, 'error' => 'ID không hợp lệ'], 400);
        }

        try {
            $stmt = $pdo->prepare("UPDATE api_clients SET status = 'revoked' WHERE id = ?");
            $stmt->execute([$id]);

            auditLog($pdo, 'revoke', 'api_client', $id, "Revoked API client ID {$id}");

            jsonResponse(['success' => true, 'message' => 'Đã thu hồi API Key thành công']);
        } catch (Exception $e) {
            jsonResponse(['success' => false, 'error' => 'Lỗi thu hồi: ' . $e->getMessage()], 500);
        }

    } elseif ($action === 'regen_key') {
        $id = (int)($data['id'] ?? 0);
        if ($id <= 0) {
            jsonResponse(['success' => false, 'error' => 'ID không hợp lệ'], 400);
        }

        $newApiKey = 'ev_live_' . bin2hex(random_bytes(16));

        try {
            $stmt = $pdo->prepare("UPDATE api_clients SET api_key = ?, status = 'active' WHERE id = ?");
            $stmt->execute([$newApiKey, $id]);

            auditLog($pdo, 'regen_key', 'api_client', $id, "Regenerated API Key for client ID {$id}");

            jsonResponse([
                'success' => true,
                'message' => 'Đã tạo lại API Key mới thành công',
                'new_api_key' => $newApiKey
            ]);
        } catch (Exception $e) {
            jsonResponse(['success' => false, 'error' => 'Lỗi tạo lại key: ' . $e->getMessage()], 500);
        }

    } else {
        jsonResponse(['success' => false, 'error' => 'Hành động không được hỗ trợ'], 400);
    }
}
