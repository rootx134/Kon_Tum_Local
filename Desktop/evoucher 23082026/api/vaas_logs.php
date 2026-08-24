<?php
require_once __DIR__ . '/api_base.php';
require_once __DIR__ . '/../includes/audit_log.php';
requireApiLogin();
requireCsrf();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $clientId   = trim($_GET['client_id'] ?? '');
    $campaignId = trim($_GET['campaign_id'] ?? '');
    $search     = trim($_GET['search'] ?? '');
    $status     = trim($_GET['status'] ?? '');
    $page       = max(1, (int)($_GET['page'] ?? 1));
    $limit      = min(100, max(1, (int)($_GET['limit'] ?? 20)));
    $offset     = ($page - 1) * $limit;

    $where   = ["v.issued_to_client_id IS NOT NULL"];
    $params  = [];

    if ($clientId !== '') {
        $where[]  = "v.issued_to_client_id = ?";
        $params[] = $clientId;
    }
    if ($campaignId !== '') {
        $where[]  = "v.campaign_id = ?";
        $params[] = (int)$campaignId;
    }
    if ($status !== '') {
        $where[]  = "v.status = ?";
        $params[] = $status;
    }
    if ($search !== '') {
        $where[]  = "(v.code LIKE ? OR v.issued_to_user_ref LIKE ? OR v.issued_to_user_name LIKE ?)";
        $searchTerm = '%' . $search . '%';
        $params[]   = $searchTerm;
        $params[]   = $searchTerm;
        $params[]   = $searchTerm;
    }

    $whereSql = implode(' AND ', $where);

    // Count total records
    $countStmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM vouchers v
        WHERE {$whereSql}
    ");
    $countStmt->execute($params);
    $totalRecords = (int)$countStmt->fetchColumn();

    // Fetch page items
    $sql = "
        SELECT 
            v.id, v.code, v.status,
            v.issued_to_client_id, v.issued_to_user_ref, v.issued_to_user_name,
            v.issued_via_api_at, v.used_at, v.idempotency_key,
            c.sponsor_name, c.sponsor_short,
            ac.client_name
        FROM vouchers v
        JOIN campaigns c ON v.campaign_id = c.id
        LEFT JOIN api_clients ac ON v.issued_to_client_id = ac.client_id
        WHERE {$whereSql}
        ORDER BY v.issued_via_api_at DESC
        LIMIT {$limit} OFFSET {$offset}
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $logs = $stmt->fetchAll();

    jsonResponse([
        'success'      => true,
        'data'         => $logs,
        'total'        => $totalRecords,
        'page'         => $page,
        'limit'        => $limit,
        'total_pages'  => ceil($totalRecords / $limit)
    ]);

} elseif ($method === 'POST') {
    $action = $_GET['action'] ?? $_POST['action'] ?? '';

    if ($action === 'revoke') {
        $voucherId = (int)($_POST['voucher_id'] ?? 0);
        if ($voucherId <= 0) {
            jsonResponse(['success' => false, 'error' => 'Voucher ID không hợp lệ'], 400);
        }

        try {
            $stmt = $pdo->prepare("SELECT * FROM vouchers WHERE id = ? AND issued_to_client_id IS NOT NULL");
            $stmt->execute([$voucherId]);
            $voucher = $stmt->fetch();

            if (!$voucher) {
                jsonResponse(['success' => false, 'error' => 'Không tìm thấy voucher cấp phát qua API'], 404);
            }

            if ($voucher['status'] === 'used') {
                jsonResponse(['success' => false, 'error' => 'Voucher đã được sử dụng tại quán, không thể thu hồi'], 400);
            }

            $updateStmt = $pdo->prepare("
                UPDATE vouchers 
                SET status = 'revoked',
                    idempotency_key = NULL
                WHERE id = ?
            ");
            $updateStmt->execute([$voucherId]);

            auditLog($pdo, 'revoke', 'voucher', $voucherId, "Thu hồi voucher code {$voucher['code']} cấp cho user {$voucher['issued_to_user_ref']}");

            jsonResponse(['success' => true, 'message' => 'Đã thu hồi voucher thành công']);
        } catch (Exception $e) {
            jsonResponse(['success' => false, 'error' => 'Lỗi hệ thống: ' . $e->getMessage()], 500);
        }
    } else {
        jsonResponse(['success' => false, 'error' => 'Hành động không hợp lệ'], 400);
    }
}
