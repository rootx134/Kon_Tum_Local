<?php
// ============================================================
// api/v1/controllers/AdminReportController.php
// Controller for summary reports exposed to Fan Cứng admin
// ============================================================

require_once __DIR__ . '/../helpers/ApiResponse.php';

class AdminReportController
{
    /**
     * GET /api/v1/external/admin/reports
     */
    public static function getMetrics(PDO $pdo, array $client): void
    {
        $clientId = $client['client_id'];

        // 1. Overall Metrics for this client
        $statsStmt = $pdo->prepare("
            SELECT 
                COUNT(*) AS total_issued,
                SUM(CASE WHEN status = 'used' THEN 1 ELSE 0 END) AS used_count,
                SUM(CASE WHEN status = 'unused' THEN 1 ELSE 0 END) AS unused_count,
                SUM(CASE WHEN status = 'expired' THEN 1 ELSE 0 END) AS expired_count,
                COUNT(DISTINCT issued_to_user_ref) AS unique_users
            FROM vouchers
            WHERE issued_to_client_id = ?
        ");
        $statsStmt->execute([$clientId]);
        $stats = $statsStmt->fetch(PDO::FETCH_ASSOC) ?: [];

        $totalIssued = (int) ($stats['total_issued'] ?? 0);
        $usedCount   = (int) ($stats['used_count'] ?? 0);
        $unusedCount = (int) ($stats['unused_count'] ?? 0);
        $expiredCount= (int) ($stats['expired_count'] ?? 0);
        $uniqueUsers = (int) ($stats['unique_users'] ?? 0);
        $usageRate   = $totalIssued > 0 ? round(($usedCount / $totalIssued) * 100, 2) : 0.0;

        // 2. Breakdown by Campaign
        $campStmt = $pdo->prepare("
            SELECT 
                c.id AS campaign_id,
                c.sponsor_name,
                c.sponsor_short,
                c.description,
                COUNT(v.id) AS total_issued,
                SUM(CASE WHEN v.status = 'used' THEN 1 ELSE 0 END) AS used_count,
                SUM(CASE WHEN v.status = 'unused' THEN 1 ELSE 0 END) AS unused_count,
                SUM(CASE WHEN v.status = 'expired' THEN 1 ELSE 0 END) AS expired_count
            FROM vouchers v
            JOIN campaigns c ON v.campaign_id = c.id
            WHERE v.issued_to_client_id = ?
            GROUP BY c.id
            ORDER BY total_issued DESC
        ");
        $campStmt->execute([$clientId]);
        $byCampaign = array_map(function($row) {
            return [
                'campaign_id'   => (int) $row['campaign_id'],
                'sponsor_name'  => $row['sponsor_name'],
                'title'         => $row['sponsor_name'] . ' - ' . $row['description'],
                'total_issued'  => (int) $row['total_issued'],
                'used_count'    => (int) $row['used_count'],
                'unused_count'  => (int) $row['unused_count'],
                'expired_count' => (int) $row['expired_count'],
            ];
        }, $campStmt->fetchAll(PDO::FETCH_ASSOC));

        // 3. Recent 10 Claims
        $recentStmt = $pdo->prepare("
            SELECT 
                v.code,
                v.status,
                v.issued_to_user_ref,
                v.issued_to_user_name,
                v.issued_via_api_at,
                c.sponsor_short,
                c.sponsor_name
            FROM vouchers v
            JOIN campaigns c ON v.campaign_id = c.id
            WHERE v.issued_to_client_id = ?
            ORDER BY v.issued_via_api_at DESC
            LIMIT 10
        ");
        $recentStmt->execute([$clientId]);
        $recentClaims = array_map(function($row) {
            return [
                'voucher_code'  => $row['sponsor_short'] . $row['code'],
                'user_ref_id'   => $row['issued_to_user_ref'],
                'user_name'     => $row['issued_to_user_name'],
                'sponsor_name'  => $row['sponsor_name'],
                'status'        => $row['status'],
                'claimed_at'    => $row['issued_via_api_at'],
            ];
        }, $recentStmt->fetchAll(PDO::FETCH_ASSOC));

        ApiResponse::success([
            'summary' => [
                'total_issued'       => $totalIssued,
                'used_count'         => $usedCount,
                'unused_count'       => $unusedCount,
                'expired_count'      => $expiredCount,
                'usage_rate_percent' => $usageRate,
                'unique_users_count' => $uniqueUsers,
            ],
            'by_campaign'   => $byCampaign,
            'recent_claims' => $recentClaims
        ], 'Lấy báo cáo thống kê thành công');
    }
}
