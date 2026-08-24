<?php
// ============================================================
// api/v1/helpers/ApiResponse.php
// Standardized JSON response helper for Evoucher REST API
// ============================================================

class ApiResponse
{
    /**
     * Send a successful JSON response
     */
    public static function success(mixed $data = null, string $message = 'Thành công', int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * Send an error JSON response
     */
    public static function error(string $code = 'BAD_REQUEST', string $message = 'Yêu cầu không hợp lệ', int $statusCode = 400, mixed $details = null): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        $payload = [
            'success' => false,
            'error'   => [
                'code'    => $code,
                'message' => $message,
            ],
        ];
        if ($details !== null) {
            $payload['error']['details'] = $details;
        }
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
