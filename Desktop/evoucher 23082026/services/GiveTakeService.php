<?php
namespace App\Services;

use PDO;
use PDOException;

class GiveTakeService {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->_ensureTables();
    }

    private function _ensureTables(): void {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS taken_voucher_items (
                id          INT AUTO_INCREMENT PRIMARY KEY,
                voucher_id  INT NOT NULL,
                campaign_id INT NOT NULL,
                taken_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                status      ENUM('available','given','returned') NOT NULL DEFAULT 'available',
                UNIQUE KEY  uq_tvi_voucher (voucher_id),
                INDEX       idx_tvi_campaign (campaign_id),
                INDEX       idx_tvi_status   (status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        try {
            $this->pdo->exec("ALTER TABLE given_vouchers ADD UNIQUE KEY uq_gv_voucher (voucher_id)");
        } catch (PDOException $e) {}
        try {
            $this->pdo->exec("ALTER TABLE taken_voucher_items ADD COLUMN returned_at TIMESTAMP NULL DEFAULT NULL");
        } catch (PDOException $e) {}
    }

    public function getCampaignsWithAvailableVouchers(): array {
        $stmt = $this->pdo->query("
            SELECT c.id, c.sponsor_name, c.start_date, c.end_date,
                   COUNT(v.id) AS available_count
            FROM campaigns c
            JOIN vouchers v ON c.id = v.campaign_id
            WHERE v.status = 'unused'
              AND v.id NOT IN (SELECT voucher_id FROM taken_voucher_items WHERE status IN ('available','given'))
            GROUP BY c.id
            HAVING available_count > 0
            ORDER BY c.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function takeVouchers(array $campaigns): array {
        try {
            $this->pdo->beginTransaction();
            $insertStmt = $this->pdo->prepare("INSERT IGNORE INTO taken_voucher_items (voucher_id, campaign_id) VALUES (?, ?)");
            $totalTaken = 0;
            foreach ($campaigns as $camp) {
                $campaignId = (int) ($camp['id'] ?? 0);
                $quantity   = (int) ($camp['quantity'] ?? 0);
                if ($campaignId <= 0 || $quantity <= 0) continue;
                $sel = $this->pdo->prepare("
                    SELECT v.id FROM vouchers v
                    WHERE v.campaign_id = ? AND v.status = 'unused'
                      AND v.id NOT IN (SELECT voucher_id FROM taken_voucher_items WHERE status IN ('available','given'))
                    LIMIT ?
                ");
                $sel->execute([$campaignId, $quantity]);
                foreach ($sel->fetchAll(PDO::FETCH_COLUMN) as $vid) {
                    $insertStmt->execute([$vid, $campaignId]);
                    $totalTaken++;
                }
            }
            $this->pdo->commit();
            return ['success' => true, 'total_taken' => $totalTaken];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getGiveStats(): array {
        $available = (int) $this->pdo->query("SELECT COUNT(*) FROM taken_voucher_items WHERE status = 'available'")->fetchColumn();
        $given     = (int) $this->pdo->query("SELECT COUNT(*) FROM taken_voucher_items WHERE status = 'given'")->fetchColumn();
        return ['total_taken' => $available + $given, 'total_given' => $given, 'remaining' => $available];
    }

    public function giveOneVoucher(string $message = ''): array {
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("
                SELECT tvi.id AS tvi_id, tvi.voucher_id, v.code, c.sponsor_short, c.sponsor_name
                FROM taken_voucher_items tvi
                JOIN vouchers  v ON tvi.voucher_id  = v.id
                JOIN campaigns c ON tvi.campaign_id = c.id
                WHERE tvi.status = 'available' AND v.status = 'unused'
                ORDER BY RAND() LIMIT 1 FOR UPDATE
            ");
            $stmt->execute();
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$item) {
                $this->pdo->rollBack();
                return ['success' => false, 'error' => 'Kho trống — vui lòng lấy thêm voucher từ chiến dịch.'];
            }
            $this->pdo->prepare("UPDATE taken_voucher_items SET status = 'given' WHERE id = ?")->execute([$item['tvi_id']]);
            $this->pdo->prepare("INSERT IGNORE INTO given_vouchers (voucher_id) VALUES (?)")->execute([$item['voucher_id']]);
            $this->pdo->commit();

            $fullCode     = $item['sponsor_short'] . $item['code'];
            $link         = 'https://e.kontumplus.com/' . $fullCode;
            $finalMessage = $message;
            if (strpos($finalMessage, 'link=>') !== false) {
                $finalMessage = str_replace('link=>', $link, $finalMessage);
            } else {
                $finalMessage .= "\n" . $link;
            }
            return ['success' => true, 'message' => $finalMessage, 'voucher' => ['id' => $item['voucher_id'], 'code' => $fullCode, 'link' => $link, 'sponsor_name' => $item['sponsor_name']]];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return ['success' => false, 'error' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    public function getTakenItems(string $statusFilter = ''): array {
        $where  = '';
        $params = [];
        if (in_array($statusFilter, ['available', 'given', 'returned'], true)) {
            $where    = 'WHERE tvi.status = ?';
            $params[] = $statusFilter;
        }
        $stmt = $this->pdo->prepare("
            SELECT tvi.id, tvi.voucher_id, tvi.status, tvi.taken_at, tvi.returned_at,
                   gv.given_at,
                   CONCAT(c.sponsor_short, v.code) AS full_code,
                   c.id AS campaign_id, c.sponsor_name
            FROM   taken_voucher_items tvi
            JOIN   vouchers  v ON tvi.voucher_id  = v.id
            JOIN   campaigns c ON tvi.campaign_id = c.id
            LEFT JOIN given_vouchers gv ON gv.voucher_id = tvi.voucher_id
            $where
            ORDER BY c.sponsor_name ASC, tvi.taken_at DESC
        ");
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function undoTakeItems(array $ids): array {
        if (empty($ids)) return ['success' => false, 'error' => 'Không có mã nào được chọn'];
        $ids = array_values(array_filter(array_map('intval', $ids)));
        if (empty($ids)) return ['success' => false, 'error' => 'ID không hợp lệ'];
        $ph = implode(',', array_fill(0, count($ids), '?'));
        try {
            $this->pdo->beginTransaction();
            $this->pdo->prepare("DELETE FROM given_vouchers WHERE voucher_id IN (SELECT voucher_id FROM taken_voucher_items WHERE id IN ($ph))")->execute($ids);
            $this->pdo->prepare("UPDATE taken_voucher_items SET status = 'returned', returned_at = NOW() WHERE id IN ($ph)")->execute($ids);
            $this->pdo->commit();
            return ['success' => true, 'count' => count($ids)];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getGiveHistory(): array {
        try {
            $stmt = $this->pdo->query("
                SELECT g.id, g.given_at, CONCAT(c.sponsor_short, v.code) AS voucher_code, c.sponsor_name, c.sponsor_short
                FROM   given_vouchers g
                JOIN   vouchers  v ON g.voucher_id  = v.id
                JOIN   campaigns c ON v.campaign_id = c.id
                ORDER  BY g.given_at DESC
            ");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as &$row) { $row['link'] = 'https://e.kontumplus.com/' . $row['voucher_code']; }
            return $rows;
        } catch (PDOException $e) {
            error_log('Error fetching give history: ' . $e->getMessage());
            return [];
        }
    }

    public function clearAllTakenAndGiven(): array {
        try {
            $this->pdo->beginTransaction();
            $this->pdo->exec("DELETE FROM given_vouchers");
            $this->pdo->exec("DELETE FROM taken_voucher_items");
            $this->pdo->exec("DELETE FROM taken_vouchers");
            $this->pdo->commit();
            return ['success' => true];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
