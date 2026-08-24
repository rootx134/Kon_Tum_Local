<?php
// ============================================================
// api/v1/middleware/RateLimitMiddleware.php
// Rate limits requests per client_id using database settings / audit_logs
// ============================================================

require_once __DIR__ . '/../helpers/ApiResponse.php';

class RateLimitMiddleware
{
    /**
     * Check if client exceeded rate limit (default 200 requests/minute)
     */
    public static function check(PDO $pdo, string $clientId, int $maxRequestsPerMinute = 200): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        
        // Count requests in the last 60 seconds from audit_logs or simple query
        $stmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM audit_logs 
            WHERE action = 'api_request' 
              AND entity = ? 
              AND created_at >= (NOW() - INTERVAL 1 MINUTE)
        ");
        $stmt->execute([$clientId]);
        $requestCount = (int) $stmt->fetchColumn();

        if ($requestCount >= $maxRequestsPerMinute) {
            ApiResponse::error('TOO_MANY_REQUESTS', 'Bạn đã vượt quá giới hạn request cho phép (200 req/min)', 429);
        }

        // Log current request for rate limiting tracker (non-blocking)
        try {
            $logStmt = $pdo->prepare("
                INSERT INTO audit_logs (action, entity, details, ip_address) 
                VALUES ('api_request', ?, ?, ?)
            ");
            $logStmt->execute([$clientId, $_SERVER['REQUEST_URI'] ?? '', $ip]);
        } catch (Exception $e) {
            // Ignore log failure
        }
    }
}
