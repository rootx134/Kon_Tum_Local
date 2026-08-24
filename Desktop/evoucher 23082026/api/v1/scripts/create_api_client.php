<?php
// ============================================================
// api/v1/scripts/create_api_client.php
// CLI script to generate API Keys for integration clients
// Usage via terminal: php create_api_client.php "Fan Cung App" "all"
// ============================================================

require_once dirname(__DIR__, 3) . '/config.php';

if (php_sapi_name() !== 'cli') {
    die("Script này chỉ có thể chạy qua Command Line (CLI).\n");
}

$clientName = $argv[1] ?? 'Fan Cứng Kon Tum Pluss';
$scope      = $argv[2] ?? 'read,claim,report';

// Generate raw key: ev_live_ + 32 random characters
$rawApiKey = 'ev_live_' . bin2hex(random_bytes(16));
$clientId  = 'client_' . bin2hex(random_bytes(4));

try {
    $stmt = $pdo->prepare("
        INSERT INTO api_clients (client_name, client_id, api_key, scopes, status)
        VALUES (?, ?, ?, ?, 'active')
    ");
    $stmt->execute([$clientName, $clientId, $rawApiKey, $scope]);
    $dbId = $pdo->lastInsertId();

    echo "========================================================\n";
    echo "  TẠO API KEY THÀNH CÔNG CHO CLIENT KHÁCH HÀNG\n";
    echo "========================================================\n";
    echo "ID          : {$dbId}\n";
    echo "Client ID   : {$clientId}\n";
    echo "Client Name : {$clientName}\n";
    echo "Scopes      : {$scope}\n";
    echo "--------------------------------------------------------\n";
    echo "API Key (LƯU LẠI NGAY, CHỈ HIỂN THỊ 1 LẦN NÀY):\n";
    echo "{$rawApiKey}\n";
    echo "--------------------------------------------------------\n";
    echo "Cấu hình key này vào file .env của Fan Cứng:\n";
    echo "EVOUCHER_API_KEY={$rawApiKey}\n";
    echo "EVOUCHER_API_URL=https://e.kontumplus.com/api/v1/external\n";
    echo "========================================================\n";
} catch (PDOException $e) {
    echo "Lỗi khi tạo API Client: " . $e->getMessage() . "\n";
}

