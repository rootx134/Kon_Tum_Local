<?php
// ============================================================
// api/v1/middleware/CorsMiddleware.php
// Handle Cross-Origin Resource Sharing (CORS) for external apps
// ============================================================

class CorsMiddleware
{
    public static function handle(): void
    {
        $allowedOrigins = [
            'https://fancung.kontumplus.com',
            'http://fancung.kontumplus.com',
            'http://localhost:3000',
            'http://127.0.0.1:3000'
        ];

        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if (in_array($origin, $allowedOrigins, true)) {
            header("Access-Control-Allow-Origin: $origin");
        } else {
            // Default fallback origin or wildcard for API key authenticated endpoints
            header("Access-Control-Allow-Origin: *");
        }

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key, X-Requested-With, Idempotency-Key");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Max-Age: 86400");

        // Handle preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
    }
}
