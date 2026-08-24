<?php
// ============================================================
// api/v1/controllers/UserVoucherController.php
// Controller to retrieve user's voucher inventory & status
// ============================================================

require_once __DIR__ . '/../helpers/ApiResponse.php';

class UserVoucherController
{
    /**
     * GET /api/v1/external/user/vouchers
     */
    public static function getUserVouchers(PDO $pdo): void
    {
        $userRefId = trim((string) ($_GET['user_ref_id'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? 'all'));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = min(100, max(1, (int) ($_GET['limit'] ?? 20)));
        $offset = ($page - 1) * $limit;

        if (empty($userRefId)) {
            ApiResponse::error('INVALID_PARAMS', 'Thiếu tham số user_ref_id', 400);
        }

        // Build WHERE clause
        $whereSql = "WHERE v.issued_to_user_ref = ?";
        $params = [$userRefId];

        if (in_array($status, ['unused', 'used', 'expired'], true)) {
            $whereSql .= " AND v.status = ?";
            $params[] = $status;
        }

        // 1. Get summary counters for this user
        $summaryStmt = $pdo->prepare("
            SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'unused' THEN 1 ELSE 0 END) AS unused,
                SUM(CASE WHEN status = 'used' THEN 1 ELSE 0 END) AS used,
                SUM(CASE WHEN status = 'expired' THEN 1 ELSE 0 END) AS expired
            FROM vouchers
            WHERE issued_to_user_ref = ?
        ");
        $summaryStmt->execute([$userRefId]);
        $rawSummary = $summaryStmt->fetch(PDO::FETCH_ASSOC) ?: [];
        $summary = [
            'total' => (int) ($rawSummary['total'] ?? 0),
            'unused' => (int) ($rawSummary['unused'] ?? 0),
            'used' => (int) ($rawSummary['used'] ?? 0),
            'expired' => (int) ($rawSummary['expired'] ?? 0),
        ];

        // 2. Count total matching vouchers for pagination
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM vouchers v $whereSql");
        $countStmt->execute($params);
        $totalItems = (int) $countStmt->fetchColumn();

        // 3. Fetch vouchers list
        $fetchSql = "
            SELECT 
                v.id,
                v.code,
                v.status,
                v.used_at,
                v.issued_via_api_at,
                c.sponsor_name,
                c.sponsor_short,
                c.description,
                c.logo,
                c.end_date
            FROM vouchers v
            JOIN campaigns c ON v.campaign_id = c.id
            $whereSql
            ORDER BY v.issued_via_api_at DESC, v.id DESC
            LIMIT $limit OFFSET $offset
        ";
        $fetchStmt = $pdo->prepare($fetchSql);
        $fetchStmt->execute($params);
        $rows = $fetchStmt->fetchAll(PDO::FETCH_ASSOC);

        $baseUrl = 'https://e.kontumplus.com';
        $vouchers = array_map(function ($row) use ($baseUrl) {
            $logoUrl = '';
            if (!empty($row['logo'])) {
                $logoUrl = str_starts_with($row['logo'], 'http')
                    ? $row['logo']
                    : $baseUrl . '/uploads/' . ltrim($row['logo'], '/');
            }
            $fullCode = $row['sponsor_short'] . $row['code'];

            // Auto check expired status if end_date < CURDATE and status is unused
            $currentStatus = $row['status'];
            if ($currentStatus === 'unused' && !empty($row['end_date']) && strtotime($row['end_date']) < strtotime(date('Y-m-d'))) {
                $currentStatus = 'expired';
            }

            return [
                'id' => (int) $row['id'],
                'voucher_code' => $fullCode,
                'voucher_url' => $baseUrl . '/' . $fullCode,
                'campaign_title' => $row['sponsor_name'] . ' - ' . $row['description'],
                'sponsor_name' => $row['sponsor_name'],
                'description' => $row['description'],
                'logo_url' => $logoUrl,
                'status' => $currentStatus,
                'claimed_at' => $row['issued_via_api_at'],
                'used_at' => $row['used_at'],
                'expiry_date' => $row['end_date'],
            ];
        }, $rows);

        ApiResponse::success([
            'vouchers' => $vouchers,
            'summary' => $summary,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total_items' => $totalItems,
                'total_pages' => (int) ceil($totalItems / $limit),
            ]
        ], 'Lấy kho voucher thành công');
    }

    /**
     * POST /api/v1/external/voucher/use
     * Mark a voucher as used
     */
    public static function useVoucher(PDO $pdo, array $client): void
    {
        $rawInput = file_get_contents('php://input');
        $input = json_decode($rawInput, true) ?: $_POST;

        $voucherCode = trim((string) ($input['voucher_code'] ?? $input['voucherCode'] ?? ''));
        $userRefId   = trim((string) ($input['user_ref_id'] ?? $input['userRefId'] ?? ''));

        if (empty($voucherCode)) {
            ApiResponse::error('INVALID_PARAMS', 'Thiếu tham số voucher_code', 400);
        }

        // Find campaign & code match
        $campaigns = $pdo->query("SELECT id, sponsor_short FROM campaigns")->fetchAll(PDO::FETCH_ASSOC);
        $voucher = null;

        foreach ($campaigns as $camp) {
            $short = $camp['sponsor_short'];
            if (!empty($short) && stripos($voucherCode, $short) === 0) {
                $codeOnly = substr($voucherCode, strlen($short));
                $stmt = $pdo->prepare("
                    SELECT v.id, v.code, v.status, v.used_at, v.campaign_id, c.sponsor_name, c.sponsor_short, c.description, c.end_date
                    FROM vouchers v
                    JOIN campaigns c ON v.campaign_id = c.id
                    WHERE c.id = ? AND v.code = ?
                ");
                $stmt->execute([$camp['id'], $codeOnly]);
                $voucher = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($voucher) break;
            }
        }

        if (!$voucher) {
            // Direct search by code
            $stmt = $pdo->prepare("
                SELECT v.id, v.code, v.status, v.used_at, v.campaign_id, c.sponsor_name, c.sponsor_short, c.description, c.end_date
                FROM vouchers v
                JOIN campaigns c ON v.campaign_id = c.id
                WHERE v.code = ?
            ");
            $stmt->execute([$voucherCode]);
            $voucher = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if (!$voucher) {
            ApiResponse::error('VOUCHER_NOT_FOUND', 'Mã voucher không tồn tại trên hệ thống', 404);
        }

        if ($voucher['status'] === 'used') {
            ApiResponse::success([
                'voucher_code' => $voucherCode,
                'status'       => 'used',
                'used_at'      => $voucher['used_at'] ?: date('Y-m-d H:i:s'),
                'already_used' => true,
            ], 'Voucher này đã được xác nhận sử dụng trước đó');
        }

        if ($voucher['status'] === 'expired' || (!empty($voucher['end_date']) && strtotime($voucher['end_date']) < strtotime(date('Y-m-d')))) {
            ApiResponse::error('VOUCHER_EXPIRED', 'Mã voucher này đã hết hạn sử dụng', 400);
        }

        // Mark as used
        $usedAt = date('Y-m-d H:i:s');
        $updateStmt = $pdo->prepare("
            UPDATE vouchers 
            SET status = 'used', 
                used_at = ? 
            WHERE id = ?
        ");
        $updateStmt->execute([$usedAt, $voucher['id']]);

        ApiResponse::success([
            'voucher_code' => $voucherCode,
            'status'       => 'used',
            'used_at'      => $usedAt,
            'already_used' => false,
        ], 'Xác nhận sử dụng voucher thành công!');
    }
}
