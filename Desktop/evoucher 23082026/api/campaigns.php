<?php
require_once __DIR__ . '/api_base.php';
require_once __DIR__ . '/../includes/audit_log.php';
require_once __DIR__ . '/../includes/upload_handler.php';
requireApiLogin();
requireCsrf();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $campaignId = $_GET['id'] ?? null;

    if ($campaignId) {
        $stmt = $pdo->prepare("SELECT * FROM campaigns WHERE id = ?");
        $stmt->execute([$campaignId]);
        $campaign = $stmt->fetch();

        if ($campaign) {
            echo json_encode([$campaign]);
        } else {
            jsonResponse(['success' => false, 'error' => 'Chiến dịch không tồn tại'], 404);
        }
    } else {
        $stmt = $pdo->query("
            SELECT c.*,
                   COUNT(v.id) as voucher_count,
                   COUNT(CASE WHEN v.status = 'unused'  THEN 1 END) as unused_count,
                   COUNT(CASE WHEN v.status = 'used'    THEN 1 END) as used_count,
                   COUNT(CASE WHEN v.status = 'expired' THEN 1 END) as expired_count,
                   COUNT(CASE WHEN v.status = 'unused' AND v.issued_to_client_id IS NULL THEN 1 END) as api_available_qty,
                   COUNT(CASE WHEN v.issued_to_client_id IS NOT NULL THEN 1 END) as api_issued_count
            FROM campaigns c
            LEFT JOIN vouchers v ON c.id = v.campaign_id
            GROUP BY c.id
            ORDER BY c.created_at DESC
        ");
        echo json_encode($stmt->fetchAll());
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    // Support raw JSON body if $_POST is empty
    if (empty($data)) {
        $jsonInput = json_decode(file_get_contents('php://input'), true);
        if (is_array($jsonInput)) {
            $data = $jsonInput;
        }
    }

    // ── UPDATE (tunnelled as POST with _method=PUT) ───────────────────────
    if (isset($data['_method']) && $data['_method'] === 'PUT') {
        $campaignId = (int) ($data['id'] ?? 0);

        try {
            $pdo->beginTransaction();

            $logoName      = resolveLogoUpload($data, $_FILES ?? []);
            $menuImageName = resolveMenuImageUpload($data, $_FILES ?? []);
            $guideContent  = buildGuideContent($data);
            $menuContent   = buildMenuContent($data, $menuImageName);

            $fields = [];
            $values = [];

            if (isset($data['sponsor_name']))     { $fields[] = 'sponsor_name = ?';     $values[] = $data['sponsor_name']; }
            if (isset($data['sponsor_short']))    { $fields[] = 'sponsor_short = ?';    $values[] = $data['sponsor_short']; }
            if (isset($data['description']))      { $fields[] = 'description = ?';      $values[] = $data['description']; }
            if ($logoName)                        { $fields[] = 'logo = ?';             $values[] = $logoName; }
            if (isset($data['start_date']))       { $fields[] = 'start_date = ?';       $values[] = $data['start_date']; }
            if (isset($data['end_date']))         { $fields[] = 'end_date = ?';         $values[] = $data['end_date']; }
            if ($guideContent !== null)           { $fields[] = 'guide_content = ?';    $values[] = $guideContent; }
            if ($menuContent  !== null)           { $fields[] = 'menu_content = ?';     $values[] = $menuContent; }
            if (isset($data['api_visible']))      { $fields[] = 'api_visible = ?';      $values[] = (int)$data['api_visible']; }
            if (isset($data['api_daily_quota']))  { $fields[] = 'api_daily_quota = ?';  $values[] = (int)$data['api_daily_quota']; }
            if (isset($data['max_per_user']))     { $fields[] = 'max_per_user = ?';     $values[] = (int)$data['max_per_user']; }
            if (isset($data['points_required']))  { $fields[] = 'points_required = ?';  $values[] = (int)$data['points_required']; }

            if (!empty($fields)) {
                $values[] = $campaignId;
                $pdo->prepare("UPDATE campaigns SET " . implode(', ', $fields) . " WHERE id = ?")->execute($values);
            }

            if (isset($data['codes']) && trim($data['codes']) !== '') {
                $pdo->prepare("DELETE FROM vouchers WHERE campaign_id = ?")->execute([$campaignId]);
                $vStmt = $pdo->prepare("INSERT INTO vouchers (campaign_id, code) VALUES (?, ?)");
                foreach (explode("\n", $data['codes']) as $code) {
                    $code = trim($code);
                    if ($code !== '') { $vStmt->execute([$campaignId, strtoupper($code)]); }
                }
            }

            $pdo->commit();
            auditLog($pdo, 'update', 'campaign', $campaignId, 'Updated campaign');
            jsonResponse(['success' => true]);

        } catch (Exception $e) {
            $pdo->rollBack();
            error_log('Error updating campaign: ' . $e->getMessage());
            jsonResponse(['success' => false, 'error' => 'Đã xảy ra lỗi hệ thống'], 500);
        }
        exit;
    }

    // ── CREATE or ADD VOUCHERS ────────────────────────────────────────────
    try {
        $pdo->beginTransaction();

        $logoName      = resolveLogoUpload($data, $_FILES);
        $menuImageName = resolveMenuImageUpload($data, $_FILES);
        $guideContent  = buildGuideContent($data) ?? '';
        $menuContent   = buildMenuContent($data, $menuImageName) ?? '';

        $campaignId = null;

        if (!empty($data['campaign_id'])) {
            // Add vouchers to existing campaign, optionally updating meta
            $campaignId = (int) $data['campaign_id'];

            $fields = [];
            $values = [];
            if ($logoName)                  { $fields[] = 'logo = ?';           $values[] = $logoName; }
            if (isset($data['description'])) { $fields[] = 'description = ?';   $values[] = $data['description']; }
            if (isset($data['start_date']))  { $fields[] = 'start_date = ?';    $values[] = $data['start_date']; }
            if (isset($data['end_date']))    { $fields[] = 'end_date = ?';      $values[] = $data['end_date']; }
            if ($guideContent !== '')        { $fields[] = 'guide_content = ?'; $values[] = $guideContent; }
            if ($menuContent  !== '')        { $fields[] = 'menu_content = ?';  $values[] = $menuContent; }

            if (!empty($fields)) {
                $values[] = $campaignId;
                $pdo->prepare("UPDATE campaigns SET " . implode(', ', $fields) . " WHERE id = ?")->execute($values);
            }

        } else {
            // Create new campaign
            if (empty($data['sponsor_name']) || empty($data['sponsor_short'])) {
                $pdo->rollBack();
                jsonResponse(['success' => false, 'error' => 'Tên chương trình và Mã ngắn gọn là bắt buộc'], 400);
            }

            $apiVisible     = isset($data['api_visible']) ? (int)$data['api_visible'] : 1;
            $apiDailyQuota  = isset($data['api_daily_quota']) ? (int)$data['api_daily_quota'] : 0;
            $pointsRequired = isset($data['points_required']) ? (int)$data['points_required'] : 0;
            $maxPerUser     = isset($data['max_per_user']) ? (int)$data['max_per_user'] : 1;

            $stmt = $pdo->prepare("
                INSERT INTO campaigns (sponsor_name, sponsor_short, description, logo, start_date, end_date, guide_content, menu_content, api_visible, api_daily_quota, points_required, max_per_user)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['sponsor_name'],
                $data['sponsor_short'],
                $data['description'] ?? '',
                $logoName,
                $data['start_date'] ?? null,
                $data['end_date']   ?? null,
                $guideContent,
                $menuContent,
                $apiVisible,
                $apiDailyQuota,
                $pointsRequired,
                $maxPerUser,
            ]);
            $campaignId = (int) $pdo->lastInsertId();
        }

        if (!empty($data['codes'])) {
            $vStmt = $pdo->prepare("INSERT INTO vouchers (campaign_id, code) VALUES (?, ?)");
            foreach (explode("\n", $data['codes']) as $code) {
                $code = trim($code);
                if ($code !== '') { $vStmt->execute([$campaignId, $code]); }
            }
        }

        $pdo->commit();
        auditLog($pdo, 'create', 'campaign', $campaignId, 'Created campaign: ' . ($data['sponsor_name'] ?? ''));
        jsonResponse(['success' => true, 'campaign_id' => $campaignId]);

    } catch (Exception $e) {
        $pdo->rollBack();
        error_log('Error creating campaign: ' . $e->getMessage());
        jsonResponse(['success' => false, 'error' => 'Đã xảy ra lỗi hệ thống'], 500);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $campaignId = (int) ($_GET['id'] ?? 0);
    if ($campaignId <= 0) {
        jsonResponse(['success' => false, 'error' => 'ID không hợp lệ'], 400);
    }

    try {
        $pdo->beginTransaction();

        // 1. Remove from take/give tracking tables (must be before vouchers deleted)
        $pdo->prepare("
            DELETE FROM given_vouchers
            WHERE voucher_id IN (SELECT id FROM vouchers WHERE campaign_id = ?)
        ")->execute([$campaignId]);

        $pdo->prepare("
            DELETE FROM taken_voucher_items
            WHERE voucher_id IN (SELECT id FROM vouchers WHERE campaign_id = ?)
        ")->execute([$campaignId]);

        // 2. Delete vouchers
        $pdo->prepare("DELETE FROM vouchers WHERE campaign_id = ?")->execute([$campaignId]);

        // 3. Delete campaign
        $pdo->prepare("DELETE FROM campaigns WHERE id = ?")->execute([$campaignId]);

        $pdo->commit();
        auditLog($pdo, 'delete', 'campaign', $campaignId, 'Deleted campaign');
        jsonResponse(['success' => true]);
    } catch (Exception $e) {
        $pdo->rollBack();
        jsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
    }
}