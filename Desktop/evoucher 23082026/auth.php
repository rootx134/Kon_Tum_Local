<?php
// ============================================================
// auth.php  –  Token-based multi-device authentication
// ============================================================
require_once __DIR__ . '/config.php';

define('AUTH_COOKIE_NAME',         'admin_token');
define('AUTH_TOKEN_BYTES',         32);
define('SESSION_CLEANUP_AFTER_DAYS', 30);

// Start PHP session for CSRF only (not used for login state)
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

// ============================================================
// Core auth helpers
// ============================================================

function isLoggedIn(): bool
{
    global $pdo;

    $token = $_COOKIE[AUTH_COOKIE_NAME] ?? '';
    if (empty($token) || strlen($token) !== AUTH_TOKEN_BYTES * 2) {
        return false;
    }

    if (mt_rand(1, 20) === 1) {
        _cleanExpiredSessions($pdo);
    }

    $stmt = $pdo->prepare("
        SELECT s.user_id, u.username
        FROM user_sessions s
        JOIN users u ON u.id = s.user_id
        WHERE s.id = ?
          AND s.last_seen > DATE_SUB(NOW(), INTERVAL ? DAY)
    ");
    $stmt->execute([$token, SESSION_CLEANUP_AFTER_DAYS]);
    $row = $stmt->fetch();

    if (!$row) {
        return false;
    }

    $_SESSION['user_id']  = $row['user_id'];
    $_SESSION['username'] = $row['username'];

    $pdo->prepare("UPDATE user_sessions SET last_seen = NOW() WHERE id = ?")
        ->execute([$token]);

    return true;
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Location: login.php');
        exit();
    }
}

// ============================================================
// Login / logout helpers
// ============================================================

function loginUser(int $userId, string $username): void
{
    global $pdo;

    session_regenerate_id(true);
    $_SESSION['user_id']    = $userId;
    $_SESSION['username']   = $username;
    $_SESSION['login_at']   = time();
    $_SESSION['csrf_token'] = generateCsrfToken();

    $token  = bin2hex(random_bytes(AUTH_TOKEN_BYTES));
    $device = _getDeviceLabel();
    $ip     = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

    $pdo->prepare("
        INSERT INTO user_sessions (id, user_id, device, ip)
        VALUES (?, ?, ?, ?)
    ")->execute([$token, $userId, $device, $ip]);

    setcookie(
        AUTH_COOKIE_NAME,
        $token,
        [
            'expires'  => time() + SESSION_CLEANUP_AFTER_DAYS * 86400,
            'path'     => '/',
            'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
            'httponly' => true,
            'samesite' => 'Strict',
        ]
    );
}

function logoutUser(): void
{
    global $pdo;

    $token = $_COOKIE[AUTH_COOKIE_NAME] ?? '';
    if ($token) {
        $pdo->prepare("DELETE FROM user_sessions WHERE id = ?")
            ->execute([$token]);
    }

    setcookie(AUTH_COOKIE_NAME, '', [
        'expires'  => time() - 3600,
        'path'     => '/',
        'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'httponly' => true,
        'samesite' => 'Strict',
    ]);

    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(), '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    session_destroy();
}

function logoutAllDevices(): void
{
    global $pdo;
    $userId = $_SESSION['user_id'] ?? null;
    if ($userId) {
        $pdo->prepare("DELETE FROM user_sessions WHERE user_id = ?")
            ->execute([$userId]);
    }
    logoutUser();
}

function getActiveSessions(): array
{
    global $pdo;
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) return [];

    $currentToken = $_COOKIE[AUTH_COOKIE_NAME] ?? '';
    $stmt = $pdo->prepare("
        SELECT id, device, ip, last_seen, created_at,
               (id = ?) AS is_current
        FROM user_sessions
        WHERE user_id = ?
          AND last_seen > DATE_SUB(NOW(), INTERVAL ? DAY)
        ORDER BY last_seen DESC
    ");
    $stmt->execute([$currentToken, $userId, SESSION_CLEANUP_AFTER_DAYS]);
    return $stmt->fetchAll();
}

// ============================================================
// CSRF helpers
// ============================================================

function generateCsrfToken(): string
{
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    return $token;
}

function getCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        return generateCsrfToken();
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(string $submittedToken): bool
{
    if (empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $submittedToken);
}

function csrfField(): string
{
    $token = htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8');
    return "<input type=\"hidden\" name=\"csrf_token\" value=\"{$token}\">";
}

// ============================================================
// Private helpers
// ============================================================

function _getDeviceLabel(): string
{
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    if (preg_match('/(iPhone|iPad|Android|Mobile)/i', $ua, $m)) {
        return 'Mobile - ' . $m[1];
    }
    if (preg_match('/(Windows|Macintosh|Linux)/i', $ua, $m)) {
        return 'Desktop - ' . $m[1];
    }
    return 'Unknown device';
}

function _cleanExpiredSessions(PDO $pdo): void
{
    try {
        $pdo->prepare("
            DELETE FROM user_sessions
            WHERE last_seen < DATE_SUB(NOW(), INTERVAL ? DAY)
        ")->execute([SESSION_CLEANUP_AFTER_DAYS]);
    } catch (\Exception $e) {
        error_log('Session cleanup error: ' . $e->getMessage());
    }
}
