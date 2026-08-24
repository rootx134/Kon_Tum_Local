<?php
// Common bootstrap for JSON APIs

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';

// Always return JSON
header('Content-Type: application/json; charset=utf-8');
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");

/**
 * Send a JSON response and end script.
 *
 * @param mixed $data
 * @param int   $statusCode
 * @return void
 */
function jsonResponse($data, int $statusCode = 200): void {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

/**
 * Convenience helper for APIs that require login.
 *
 * @return void
 */
function requireApiLogin(): void {
    requireLogin();
}

/**
 * Validate CSRF token for mutation requests (POST/PUT/DELETE).
 * Accepts token via X-CSRF-Token header (preferred for AJAX)
 * or csrf_token in request body (for form submissions).
 *
 * Call this AFTER requireApiLogin() on any mutation endpoint.
 *
 * @return void
 */
function requireCsrf(): void {
    // Only enforce on mutation methods
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    if (in_array($method, ['GET', 'HEAD', 'OPTIONS'], true)) {
        return;
    }

    // Try header first (AJAX), then POST body, then JSON body
    $token = $_SERVER['HTTP_X_CSRF_TOKEN']
          ?? $_POST['csrf_token']
          ?? null;

    // For JSON requests, parse body if token not found yet
    if ($token === null) {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            $input = json_decode(file_get_contents('php://input'), true);
            $token = $input['csrf_token'] ?? null;
        }
    }

    if (!$token || !verifyCsrfToken($token)) {
        jsonResponse(['success' => false, 'error' => 'CSRF token không hợp lệ. Vui lòng tải lại trang.'], 403);
    }
}
