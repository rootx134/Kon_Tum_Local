<?php
/**
 * File-based rate limiter for shared hosting environments.
 * Stores request counts in temp files per IP.
 *
 * Usage:
 *   require_once 'includes/rate_limiter.php';
 *   checkRateLimit('voucher_use', 10, 60); // 10 requests per 60 seconds
 */

/**
 * Check rate limit for current request.
 * Aborts with 429 if limit exceeded.
 *
 * @param string $action   Action identifier (e.g., 'voucher_view', 'voucher_use')
 * @param int    $maxRequests Maximum requests allowed in the window
 * @param int    $windowSeconds Time window in seconds
 * @return void
 */
function checkRateLimit(string $action, int $maxRequests, int $windowSeconds): void
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $key = md5($action . '_' . $ip);
    
    $rateLimitDir = __DIR__ . '/../storage/rate_limits';
    if (!is_dir($rateLimitDir)) {
        @mkdir($rateLimitDir, 0755, true);
    }
    
    $file = $rateLimitDir . '/' . $key . '.json';
    
    $data = ['count' => 0, 'window_start' => time()];
    
    if (file_exists($file)) {
        $raw = @file_get_contents($file);
        $stored = $raw ? json_decode($raw, true) : null;
        
        if ($stored && isset($stored['count'], $stored['window_start'])) {
            // Check if window has expired
            if ((time() - $stored['window_start']) < $windowSeconds) {
                $data = $stored;
            }
            // else: window expired, reset
        }
    }
    
    $data['count']++;
    
    // Save updated count
    @file_put_contents($file, json_encode($data), LOCK_EX);
    
    // Check if limit exceeded
    if ($data['count'] > $maxRequests) {
        $retryAfter = $windowSeconds - (time() - $data['window_start']);
        header('Retry-After: ' . max(1, $retryAfter));
        http_response_code(429);
        
        // Return JSON for API calls, HTML for page requests
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        if (stripos($accept, 'application/json') !== false) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => 'Quá nhiều yêu cầu. Vui lòng thử lại sau ' . max(1, $retryAfter) . ' giây.'
            ]);
        } else {
            echo '<h1>429 - Too Many Requests</h1>';
            echo '<p>Quá nhiều yêu cầu. Vui lòng thử lại sau ' . max(1, $retryAfter) . ' giây.</p>';
        }
        exit;
    }
}

/**
 * Clean up expired rate limit files (call periodically, e.g., via cron).
 *
 * @param int $maxAge Maximum file age in seconds (default: 1 hour)
 * @return int Number of files cleaned
 */
function cleanRateLimitFiles(int $maxAge = 3600): int
{
    $rateLimitDir = __DIR__ . '/../storage/rate_limits';
    if (!is_dir($rateLimitDir)) return 0;
    
    $cleaned = 0;
    foreach (glob($rateLimitDir . '/*.json') as $file) {
        if (filemtime($file) < (time() - $maxAge)) {
            @unlink($file);
            $cleaned++;
        }
    }
    return $cleaned;
}
