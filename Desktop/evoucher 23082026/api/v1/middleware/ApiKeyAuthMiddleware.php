<?php
// ============================================================
// api/v1/middleware/ApiKeyAuthMiddleware.php
// Validates X-API-Key header against api_clients table
// ============================================================

require_once __DIR__ . '/../helpers/ApiResponse.php';

class ApiKeyAuthMiddleware
{
    /**
     * Authenticate request using X-API-Key or Authorization Bearer header
     * Returns array containing client data
     */
    public static function authenticate(PDO $pdo, string $requiredScope = ''): array
    {
        $apiKey = '';

        // 1. Check X-API-Key header
        if (!empty($_SERVER['HTTP_X_API_KEY'])) {
            $apiKey = trim($_SERVER['HTTP_X_API_KEY']);
        } 
        // 2. Check Authorization Bearer header
        elseif (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            if (preg_match('/Bearer\s+(.*)$/i', trim($_SERVER['HTTP_AUTHORIZATION']), $matches)) {
                $apiKey = trim($matches[1]);
            }
        }
        // 3. Fallback to query param for GET tests
        elseif (!empty($_GET['api_key'])) {
            $apiKey = trim($_GET['api_key']);
        }

        if (empty($apiKey)) {
            ApiResponse::error('UNAUTHORIZED', 'Missing X-API-Key header or Authorization Bearer token', 401);
        }

        // Search active client with matching api_key
        $stmt = $pdo->prepare("
            SELECT id, client_name, client_id, api_key, scopes, status 
            FROM api_clients 
            WHERE api_key = ? AND status = 'active' 
            LIMIT 1
        ");
        $stmt->execute([$apiKey]);
        $client = $stmt->fetch();

        if (!$client) {
            ApiResponse::error('UNAUTHORIZED', 'Invalid or revoked API Key', 401);
        }

        // Update last_used_at timestamp
        $updateStmt = $pdo->prepare("UPDATE api_clients SET last_used_at = CURRENT_TIMESTAMP WHERE id = ?");
        $updateStmt->execute([$client['id']]);

        // Verify scope if required
        if ($requiredScope !== '') {
            self::requireScope($client, $requiredScope);
        }

        return $client;
    }

    /**
     * Helper to verify if client has a specific scope
     */
    public static function requireScope(array $client, string $requiredScope): void
    {
        $clientScopes = array_map('trim', explode(',', $client['scopes'] ?? ''));
        
        $hasScope = in_array($requiredScope, $clientScopes, true) || 
                    in_array('*', $clientScopes, true) || 
                    in_array('all', $clientScopes, true);

        // Alias check: 'report' scope allows 'admin' report access
        if (!$hasScope && $requiredScope === 'admin' && in_array('report', $clientScopes, true)) {
            $hasScope = true;
        }

        if (!$hasScope) {
            ApiResponse::error('FORBIDDEN', "API Key does not have the required scope: '{$requiredScope}'", 403);
        }
    }
}

