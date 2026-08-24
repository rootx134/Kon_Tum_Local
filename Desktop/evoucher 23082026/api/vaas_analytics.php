<?php
require_once __DIR__ . '/api_base.php';
requireApiLogin();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // 1. Overall stats from vouchers table where issued_to_client_id is set
    $stmtOverall = $pdo->query("
        SELECT 
            COUNT(*) as total_claims,
            SUM(CASE WHEN status = 'unused' THEN 1 ELSE 0 END) as total_issued,
            SUM(CASE WHEN status = 'used' THEN 1 ELSE 0 END) as total_redeemed,
            SUM(CASE WHEN status = 'revoked' THEN 1 ELSE 0 END) as total_revoked,
            COUNT(DISTINCT issued_to_client_id) as active_clients_count
        FROM vouchers
        WHERE issued_to_client_id IS NOT NULL
    ");
    $overall = $stmtOverall->fetch(PDO::FETCH_ASSOC);

    // 2. Top campaigns distributed via API
    $stmtTopCampaigns = $pdo->query("
        SELECT c.id, CONCAT(c.sponsor_name, ' - ', c.description) as title, COUNT(v.id) as issue_count
        FROM vouchers v
        JOIN campaigns c ON v.campaign_id = c.id
        WHERE v.issued_to_client_id IS NOT NULL
        GROUP BY c.id, c.sponsor_name, c.description
        ORDER BY issue_count DESC
        LIMIT 5
    ");
    $topCampaigns = $stmtTopCampaigns->fetchAll(PDO::FETCH_ASSOC);

    // 3. Distribution by API Client
    $stmtClientBreakdown = $pdo->query("
        SELECT COALESCE(ac.client_name, v.issued_to_client_id) as client_name, COUNT(v.id) as issue_count
        FROM vouchers v
        LEFT JOIN api_clients ac ON v.issued_to_client_id = ac.client_id
        WHERE v.issued_to_client_id IS NOT NULL
        GROUP BY v.issued_to_client_id, ac.client_name
        ORDER BY issue_count DESC
    ");
    $clientBreakdown = $stmtClientBreakdown->fetchAll(PDO::FETCH_ASSOC);

    // 4. Daily trend (Last 14 days)
    $stmtDailyTrend = $pdo->query("
        SELECT DATE(v.issued_via_api_at) as date, COUNT(*) as count
        FROM vouchers v
        WHERE v.issued_to_client_id IS NOT NULL 
          AND v.issued_via_api_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
        GROUP BY DATE(v.issued_via_api_at)
        ORDER BY date ASC
    ");
    $dailyTrend = $stmtDailyTrend->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse([
        'success' => true,
        'data' => [
            'overall'          => $overall,
            'top_campaigns'    => $topCampaigns,
            'client_breakdown' => $clientBreakdown,
            'daily_trend'      => $dailyTrend
        ]
    ]);
} else {
    jsonResponse(['success' => false, 'error' => 'Phương thức không hợp lệ'], 405);
}

