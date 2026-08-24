<?php
require_once __DIR__ . '/api_base.php';
requireApiLogin();
requireCsrf();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $campaignId = $_GET['campaign_id'] ?? null;
    $page  = max(1, (int)($_GET['page'] ?? 1));
    $limit = min(200, max(1, (int)($_GET['limit'] ?? 50)));
    $offset = ($page - 1) * $limit;

    $filters = [
        'sponsor' => $_GET['sponsor'] ?? '',
        'short'   => $_GET['short'] ?? '',
        'status'  => $_GET['status'] ?? '',
        'sort'    => $_GET['sort'] ?? 'newest'
    ];
    
    $sql = "
        SELECT v.*, c.sponsor_name, c.sponsor_short, c.start_date, c.end_date
        FROM vouchers v
        JOIN campaigns c ON v.campaign_id = c.id
        WHERE 1=1
    ";
    
    $params = [];
    
    if ($campaignId) {
        $sql .= " AND v.campaign_id = ?";
        $params[] = $campaignId;
    }
    
    if (!empty($filters['sponsor'])) {
        $sql .= " AND c.sponsor_name LIKE ?";
        $params[] = '%' . $filters['sponsor'] . '%';
    }
    
    if (!empty($filters['short'])) {
        $sql .= " AND c.sponsor_short LIKE ?";
        $params[] = '%' . $filters['short'] . '%';
    }
    
    if (!empty($filters['status'])) {
        $sql .= " AND v.status = ?";
        $params[] = $filters['status'];
    }
    
    // Add sorting
    switch ($filters['sort']) {
        case 'oldest':
            $sql .= " ORDER BY v.created_at ASC";
            break;
        case 'used-recent':
            $sql .= " ORDER BY v.used_at DESC";
            break;
        default:
            $sql .= " ORDER BY v.created_at DESC";
    }
    
    // DB-3: Add pagination
    $sql .= " LIMIT $limit OFFSET $offset";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $vouchers = $stmt->fetchAll();
    
    jsonResponse($vouchers);
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Update voucher
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $pdo->prepare("UPDATE vouchers SET code = ? WHERE id = ?");
    $result = $stmt->execute([$data['code'], $data['id']]);
    
    jsonResponse(['success' => $result]);
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['campaign_id']) && isset($data['codes'])) {
        // Add vouchers to campaign
        try {
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("INSERT INTO vouchers (campaign_id, code) VALUES (?, ?)");
            
            foreach ($data['codes'] as $code) {
                $code = trim($code);
                if (!empty($code)) {
                    $stmt->execute([$data['campaign_id'], $code]);
                }
            }
            
            $pdo->commit();
            jsonResponse(['success' => true]);
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log('Error in vouchers API: ' . $e->getMessage());
            jsonResponse(['success' => false, 'error' => 'Đã xảy ra lỗi hệ thống'], 500);
        }
    } else {
        // Restore voucher
        $stmt = $pdo->prepare("UPDATE vouchers SET status = 'unused', used_at = NULL WHERE id = ?");
        $result = $stmt->execute([$data['id']]);
        
        jsonResponse(['success' => $result]);
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Delete voucher
    $voucherId = $_GET['id'] ?? 0;
    
    $stmt = $pdo->prepare("DELETE FROM vouchers WHERE id = ?");
    $result = $stmt->execute([$voucherId]);
    
    jsonResponse(['success' => $result]);
}
?>
