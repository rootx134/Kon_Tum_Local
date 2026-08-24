<?php

require_once __DIR__ . '/api_base.php';
requireApiLogin();

// Get 5 most recently used vouchers from campaigns
$stmt = $pdo->query(
    "SELECT v.id, v.code, v.used_at, c.sponsor_name, c.sponsor_short, 'campaign' as voucher_type
     FROM vouchers v
     JOIN campaigns c ON v.campaign_id = c.id
     WHERE v.status = 'used' AND v.used_at IS NOT NULL
     ORDER BY v.used_at DESC
     LIMIT 5"
);

$campaignVouchers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get 5 most recently used free vouchers
$stmt = $pdo->query(
    "SELECT id, code, used_at, COALESCE(sponsor_name, description) as sponsor_name, '' as sponsor_short, 'free' as voucher_type
     FROM free_vouchers
     WHERE status = 'used' AND used_at IS NOT NULL
     ORDER BY used_at DESC
     LIMIT 5"
);

$freeVouchers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Combine and sort by used_at
$allVouchers = array_merge($campaignVouchers, $freeVouchers);

// Sort by used_at descending
usort($allVouchers, function($a, $b) {
    $timeA = strtotime($a['used_at']);
    $timeB = strtotime($b['used_at']);
    return $timeB - $timeA;
});

// Get top 5
$vouchers = array_slice($allVouchers, 0, 5);

jsonResponse($vouchers);
?>
