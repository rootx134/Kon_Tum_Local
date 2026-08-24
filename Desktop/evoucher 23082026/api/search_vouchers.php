<?php
require_once __DIR__ . '/api_base.php';
requireApiLogin();

$searchTerm = $_GET['q'] ?? '';

// Full load (no search term) — return ALL for client-side filtering
if (empty($searchTerm)) {
    $sql = "
        SELECT v.*, c.sponsor_name, c.sponsor_short, c.start_date, c.end_date
        FROM vouchers v
        JOIN campaigns c ON v.campaign_id = c.id
        ORDER BY v.created_at DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([]);
    $vouchers = $stmt->fetchAll();

    jsonResponse([
        'vouchers' => $vouchers,
        'total'   => count($vouchers),
    ]);
    exit;
}

// Search with term — still paginated for large result sets
$page   = max(1, intval($_GET['page'] ?? 1));
$limit  = 200;
$offset = ($page - 1) * $limit;

$searchPattern = '%' . $searchTerm . '%';
$sql = "
    SELECT v.*, c.sponsor_name, c.sponsor_short, c.start_date, c.end_date
    FROM vouchers v
    JOIN campaigns c ON v.campaign_id = c.id
    WHERE (
        v.code LIKE ? OR
        c.sponsor_name LIKE ? OR
        c.sponsor_short LIKE ? OR
        CONCAT(c.sponsor_short, v.code) LIKE ?
    )
    ORDER BY v.created_at DESC
    LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$searchPattern, $searchPattern, $searchPattern, $searchPattern]);
$vouchers = $stmt->fetchAll();

$countSql = "
    SELECT COUNT(*)
    FROM vouchers v
    JOIN campaigns c ON v.campaign_id = c.id
    WHERE (
        v.code LIKE ? OR
        c.sponsor_name LIKE ? OR
        c.sponsor_short LIKE ? OR
        CONCAT(c.sponsor_short, v.code) LIKE ?
    )
";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute([$searchPattern, $searchPattern, $searchPattern, $searchPattern]);
$totalCount = $countStmt->fetchColumn();

jsonResponse([
    'vouchers' => $vouchers,
    'total'    => $totalCount,
    'page'     => $page,
    'limit'    => $limit,
    'hasMore'  => ($offset + $limit) < $totalCount,
]);
?>