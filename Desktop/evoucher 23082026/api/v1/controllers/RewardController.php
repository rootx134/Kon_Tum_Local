<?php
// ============================================================
// api/v1/controllers/RewardController.php
// Controller for reward catalog and voucher claim endpoints
// ============================================================

require_once __DIR__ . '/../helpers/ApiResponse.php';

class RewardController
{
    /**
     * GET /api/v1/external/rewards/catalog
     * Returns list of campaigns marked as api_visible with remaining stock
     */
    public static function getCatalog(PDO $pdo): void
    {
        if (!headers_sent()) {
            header('Cache-Control: public, max-age=15, stale-while-revalidate=30');
        }

        $stmt = $pdo->query("
            SELECT 
                c.id,
                c.sponsor_name,
                c.sponsor_short,
                c.description,
                c.logo,
                c.start_date,
                c.end_date,
                c.guide_content,
                c.menu_content,
                DATEDIFF(c.end_date, CURDATE()) AS days_remaining,
                COUNT(v.id) AS available_qty
            FROM campaigns c
            LEFT JOIN vouchers v ON c.id = v.campaign_id 
                AND v.status = 'unused' 
                AND v.issued_to_client_id IS NULL
            WHERE c.api_visible = 1 
              AND c.end_date >= CURDATE()
            GROUP BY c.id
            ORDER BY c.created_at DESC
        ");
        
        $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $baseUrl = 'https://e.kontumplus.com';
        $formatted = array_map(function($item) use ($baseUrl) {
            $logoUrl = '';
            if (!empty($item['logo'])) {
                $logoUrl = str_starts_with($item['logo'], 'http') 
                    ? $item['logo'] 
                    : $baseUrl . '/uploads/' . ltrim($item['logo'], '/');
            }
            return [
                'id'               => (int) $item['id'],
                'sponsor_name'     => $item['sponsor_name'],
                'sponsor_short'    => $item['sponsor_short'],
                'title'            => $item['sponsor_name'] . ' - ' . $item['description'],
                'description'      => $item['description'],
                'logo_url'         => $logoUrl,
                'start_date'       => $item['start_date'],
                'end_date'         => $item['end_date'],
                'days_remaining'   => max(0, (int) $item['days_remaining']),
                'available_qty'    => (int) $item['available_qty'],
                'guide_content'    => $item['guide_content'],
                'menu_content'     => $item['menu_content'],
            ];
        }, $campaigns);

        ApiResponse::success(['campaigns' => $formatted], 'Lấy danh mục đổi thưởng thành công');
    }

    /**
     * POST /api/v1/external/rewards/claim
     * Claim/Issue 1 voucher for a user
     */
    public static function claim(PDO $pdo, array $client): void
    {
        $rawInput = file_get_contents('php://input');
        $input = json_decode($rawInput, true);

        if (!$input) {
            $input = $_POST;
        }

        $campaignId     = (int) ($input['campaign_id'] ?? 0);
        $userRefId      = trim((string) ($input['user_ref_id'] ?? ''));
        $userName       = trim((string) ($input['user_name'] ?? ''));
        $idempotencyKey = trim((string) ($input['idempotency_key'] ?? ''));
        $maxPerUser     = isset($input['max_per_user']) ? (int) $input['max_per_user'] : 0;

        if ($campaignId <= 0 || empty($userRefId)) {
            ApiResponse::error('INVALID_PARAMS', 'Thiếu tham số campaign_id hoặc user_ref_id', 400);
        }

        $baseUrl = 'https://e.kontumplus.com';

        // 1. Check Idempotency Key first
        if (!empty($idempotencyKey)) {
            $checkIdem = $pdo->prepare("
                SELECT v.code, v.issued_via_api_at, c.sponsor_short, c.sponsor_name, c.description
                FROM vouchers v
                JOIN campaigns c ON v.campaign_id = c.id
                WHERE v.idempotency_key = ?
                LIMIT 1
            ");
            $checkIdem->execute([$idempotencyKey]);
            $existing = $checkIdem->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                $fullCode   = $existing['sponsor_short'] . $existing['code'];
                $voucherUrl = $baseUrl . '/' . $fullCode;
                ApiResponse::success([
                    'voucher_code'    => $fullCode,
                    'voucher_url'     => $voucherUrl,
                    'campaign_title'  => $existing['sponsor_name'] . ' - ' . $existing['description'],
                    'already_claimed' => true,
                    'claimed_at'      => $existing['issued_via_api_at']
                ], 'Voucher đã được đổi trước đó (trùng idempotency_key)');
            }
        }

        // 2. Check max_per_user limit passed from Fan Cứng settings
        if ($maxPerUser > 0) {
            $countStmt = $pdo->prepare("
                SELECT COUNT(*) 
                FROM vouchers 
                WHERE campaign_id = ? AND issued_to_user_ref = ?
            ");
            $countStmt->execute([$campaignId, $userRefId]);
            $userClaimedCount = (int) $countStmt->fetchColumn();

            if ($userClaimedCount >= $maxPerUser) {
                // Fetch the latest voucher claimed by this user
                $latestStmt = $pdo->prepare("
                    SELECT v.code, v.issued_via_api_at, c.sponsor_short, c.sponsor_name, c.description
                    FROM vouchers v
                    JOIN campaigns c ON v.campaign_id = c.id
                    WHERE v.campaign_id = ? AND v.issued_to_user_ref = ?
                    ORDER BY v.issued_via_api_at DESC
                    LIMIT 1
                ");
                $latestStmt->execute([$campaignId, $userRefId]);
                $latest = $latestStmt->fetch(PDO::FETCH_ASSOC);

                $fullCode = $latest ? ($latest['sponsor_short'] . $latest['code']) : '';
                $voucherUrl = $fullCode ? ($baseUrl . '/' . $fullCode) : '';

                ApiResponse::error('LIMIT_EXCEEDED', "User đã đạt giới hạn đổi tối đa ({$maxPerUser} lần) cho chiến dịch này", 400, [
                    'claimed_count' => $userClaimedCount,
                    'max_allowed'   => $maxPerUser,
                    'voucher_code'  => $fullCode,
                    'voucher_url'   => $voucherUrl
                ]);
            }
        }

        // 3. Perform atomic transaction lock to claim 1 voucher
        try {
            $pdo->beginTransaction();

            // Verify campaign exists & api_visible
            $campStmt = $pdo->prepare("
                SELECT id, sponsor_name, sponsor_short, description, end_date 
                FROM campaigns 
                WHERE id = ? AND api_visible = 1 AND end_date >= CURDATE()
            ");
            $campStmt->execute([$campaignId]);
            $campaign = $campStmt->fetch(PDO::FETCH_ASSOC);

            if (!$campaign) {
                $pdo->rollBack();
                ApiResponse::error('CAMPAIGN_NOT_FOUND', 'Chiến dịch không tồn tại hoặc không khả dụng trên API', 444);
            }

            // Lock 1 unused voucher FOR UPDATE
            $lockStmt = $pdo->prepare("
                SELECT id, code 
                FROM vouchers 
                WHERE campaign_id = ? 
                  AND status = 'unused' 
                  AND issued_to_client_id IS NULL 
                ORDER BY id ASC 
                LIMIT 1 FOR UPDATE
            ");
            $lockStmt->execute([$campaignId]);
            $voucher = $lockStmt->fetch(PDO::FETCH_ASSOC);

            if (!$voucher) {
                $pdo->rollBack();
                ApiResponse::error('OUT_OF_STOCK', 'Chiến dịch này hiện đã hết voucher trong kho', 404);
            }

            $voucherId = (int) $voucher['id'];

            // Update voucher record
            $updateStmt = $pdo->prepare("
                UPDATE vouchers 
                SET issued_to_client_id = ?,
                    issued_to_user_ref  = ?,
                    issued_to_user_name = ?,
                    issued_via_api_at   = NOW(),
                    idempotency_key     = ?
                WHERE id = ?
            ");
            $updateStmt->execute([
                $client['client_id'],
                $userRefId,
                $userName,
                $idempotencyKey ?: null,
                $voucherId
            ]);

            // Sync with given_vouchers table for system compatibility
            $pdo->prepare("INSERT IGNORE INTO given_vouchers (voucher_id) VALUES (?)")->execute([$voucherId]);

            $pdo->commit();

            $fullCode   = $campaign['sponsor_short'] . $voucher['code'];
            $voucherUrl = $baseUrl . '/' . $fullCode;

            ApiResponse::success([
                'voucher_code'    => $fullCode,
                'voucher_url'     => $voucherUrl,
                'campaign_title'  => $campaign['sponsor_name'] . ' - ' . $campaign['description'],
                'sponsor_name'    => $campaign['sponsor_name'],
                'expiry_date'     => $campaign['end_date'],
                'already_claimed' => false,
                'claimed_at'      => date('c')
            ], 'Đổi voucher thành công!');

        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("API Claim Voucher Error: " . $e->getMessage());
            ApiResponse::error('SERVER_ERROR', 'Có lỗi xảy ra khi xử lý đổi voucher: ' . $e->getMessage(), 500);
        }
    }
}
