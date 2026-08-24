<?php
namespace App\Services;

use PDO;
use PDOException;

class DashboardService {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAllStats(): array {
        $stats = [
            'total_campaigns' => 0,
            'total_vouchers' => 0,
            'used_vouchers' => 0,
            'available_vouchers' => 0,
            'campaign_stats' => []
        ];

        try {
            // Detailed Campaign Stats (Active campaigns only)
            $stmt = $this->pdo->query("
                SELECT 
                    c.id, 
                    c.sponsor_name, 
                    c.sponsor_short,
                    COUNT(v.id) as total_vouchers,
                    SUM(CASE WHEN v.status = 'used' THEN 1 ELSE 0 END) as used_count,
                    SUM(CASE WHEN v.status = 'unused' THEN 1 ELSE 0 END) as unused_count
                FROM campaigns c
                LEFT JOIN vouchers v ON c.id = v.campaign_id
                WHERE CURDATE() >= c.start_date AND CURDATE() < c.end_date
                GROUP BY c.id
                ORDER BY c.created_at DESC
            ");
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $row['total_vouchers'] = (int)($row['total_vouchers'] ?? 0);
                $row['used_count'] = (int)($row['used_count'] ?? 0);
                $row['unused_count'] = (int)($row['unused_count'] ?? 0);
                
                $stats['campaign_stats'][] = $row;

                // Aggregate overall counts for available campaigns
                $stats['total_campaigns']++;
                $stats['total_vouchers'] += $row['total_vouchers'];
                $stats['used_vouchers'] += $row['used_count'];
                $stats['available_vouchers'] += ($row['total_vouchers'] - $row['used_count']);
            }
        } catch (PDOException $e) {
            error_log("DashboardService Stats Error: " . $e->getMessage());
        }

        return $stats;
    }
}
