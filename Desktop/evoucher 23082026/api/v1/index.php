<?php
// ============================================================
// api/v1/index.php
// Central API Router for Evoucher External VaaS API
// ============================================================

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/helpers/ApiResponse.php';
require_once __DIR__ . '/middleware/CorsMiddleware.php';
require_once __DIR__ . '/middleware/ApiKeyAuthMiddleware.php';
require_once __DIR__ . '/middleware/RateLimitMiddleware.php';

require_once __DIR__ . '/controllers/RewardController.php';
require_once __DIR__ . '/controllers/UserVoucherController.php';
require_once __DIR__ . '/controllers/AdminReportController.php';

// 1. Apply CORS Middleware
CorsMiddleware::handle();

// 2. Extract requested URI path & HTTP method
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

// Parse URL path, remove query string
$path = parse_url($requestUri, PHP_URL_PATH);

// Standardize route path
// E.g., /api/v1/external/rewards/catalog or /evoucher/api/v1/external/...
if (preg_match('#/api/v1(?:/external)?(/.*)?$#', $path, $matches)) {
    $subPath = rtrim($matches[1] ?? '', '/');
    if ($subPath === '') {
        $subPath = '/';
    }
} else {
    $subPath = $path;
}

// 3. Simple Health Check (No Auth needed)
if ($method === 'GET' && ($subPath === '/health' || $subPath === '')) {
    ApiResponse::success([
        'service' => 'Evoucher VaaS API',
        'version' => 'v1',
        'status' => 'healthy',
        'timestamp' => date('c')
    ], 'Service is running');
}

// 4. Require API Key Authentication for all external endpoints
$client = ApiKeyAuthMiddleware::authenticate($pdo);

// 5. Rate Limiting (default 60 requests per minute per client)
RateLimitMiddleware::check($pdo, $client['client_id'], 60, 60);

// 6. Route Resolution
switch ($subPath) {
    case '/rewards/catalog':
        if ($method === 'GET') {
            RewardController::getCatalog($pdo);
        } else {
            ApiResponse::error('METHOD_NOT_ALLOWED', 'Phương thức HTTP không được hỗ trợ', 405);
        }
        break;

    case '/rewards/claim':
        if ($method === 'POST') {
            RewardController::claim($pdo, $client);
        } else {
            ApiResponse::error('METHOD_NOT_ALLOWED', 'Phương thức HTTP không được hỗ trợ', 405);
        }
        break;

    case '/user/vouchers':
        if ($method === 'GET') {
            UserVoucherController::getUserVouchers($pdo);
        } else {
            ApiResponse::error('METHOD_NOT_ALLOWED', 'Phương thức HTTP không được hỗ trợ', 405);
        }
        break;

    case '/voucher/use':
        if ($method === 'POST') {
            UserVoucherController::useVoucher($pdo, $client);
        } else {
            ApiResponse::error('METHOD_NOT_ALLOWED', 'Phương thức HTTP không được hỗ trợ', 405);
        }
        break;

    case '/admin/reports':
        if ($method === 'GET') {
            ApiKeyAuthMiddleware::requireScope($client, 'admin');
            AdminReportController::getMetrics($pdo, $client);
        } else {
            ApiResponse::error('METHOD_NOT_ALLOWED', 'Phương thức HTTP không được hỗ trợ', 405);
        }
        break;

    default:
        ApiResponse::error('NOT_FOUND', "Endpoint '{$subPath}' không tồn tại", 404);
        break;
}
