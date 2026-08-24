<?php
/**
 * Audit logging helper.
 * Records sensitive admin operations for security compliance.
 *
 * Usage:
 *   require_once 'includes/audit_log.php';
 *   auditLog($pdo, 'create', 'campaign', $campaignId, 'Created campaign: Test');
 */

/**
 * Write an audit log entry.
 *
 * @param PDO         $pdo       Database connection
 * @param string      $action    Action performed (create, update, delete, login, logout, use, restore)
 * @param string      $entity    Entity type (campaign, voucher, free_voucher, user, session)
 * @param int|null    $entityId  ID of the affected entity
 * @param string|null $details   Additional details (free text)
 * @return void
 */
function auditLog(PDO $pdo, string $action, string $entity, ?int $entityId = null, ?string $details = null): void
{
    try {
        $userId = $_SESSION['user_id'] ?? null;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        $stmt = $pdo->prepare("
            INSERT INTO audit_logs (user_id, action, entity, entity_id, details, ip_address)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$userId, $action, $entity, $entityId, $details, $ip]);
    } catch (\Exception $e) {
        // Never let audit logging crash the application
        error_log('Audit log error: ' . $e->getMessage());
    }
}

/**
 * Get recent audit logs.
 *
 * @param PDO $pdo    Database connection
 * @param int $limit  Number of entries to return
 * @return array
 */
function getAuditLogs(PDO $pdo, int $limit = 50): array
{
    $stmt = $pdo->prepare("
        SELECT al.*, u.username 
        FROM audit_logs al
        LEFT JOIN users u ON al.user_id = u.id
        ORDER BY al.created_at DESC
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}
