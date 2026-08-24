<?php

require_once __DIR__ . '/api_base.php';
require_once __DIR__ . '/../includes/audit_log.php';
requireApiLogin();
requireCsrf();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $voucherId = (int) ($data['id'] ?? 0);
    $voucherType = $data['voucher_type'] ?? 'campaign'; // 'campaign' or 'free'
    
    if (!$voucherId) {
        jsonResponse(['success' => false, 'error' => 'Thiếu ID e-voucher'], 400);
    }
    
    try {
        if ($voucherType === 'free') {
            // Check if free voucher exists and is used
            $stmt = $pdo->prepare("SELECT id, status FROM free_vouchers WHERE id = ?");
            $stmt->execute([$voucherId]);
            $voucher = $stmt->fetch();
            
            if (!$voucher) {
                jsonResponse(['success' => false, 'error' => 'Không tìm thấy voucher tự do'], 404);
            }
            
            if ($voucher['status'] !== 'used') {
                jsonResponse(['success' => false, 'error' => 'Voucher tự do chưa được sử dụng'], 400);
            }
            
            // Restore free voucher
            $stmt = $pdo->prepare("UPDATE free_vouchers SET status = 'unused', used_at = NULL WHERE id = ?");
            $result = $stmt->execute([$voucherId]);
        } else {
            // Check if campaign voucher exists and is used
            $stmt = $pdo->prepare("SELECT id, status FROM vouchers WHERE id = ?");
            $stmt->execute([$voucherId]);
            $voucher = $stmt->fetch();
            
            if (!$voucher) {
                jsonResponse(['success' => false, 'error' => 'Không tìm thấy e-voucher'], 404);
            }
            
            if ($voucher['status'] !== 'used') {
                jsonResponse(['success' => false, 'error' => 'E-voucher chưa được sử dụng'], 400);
            }
            
            // Restore campaign voucher
            $stmt = $pdo->prepare("UPDATE vouchers SET status = 'unused', used_at = NULL WHERE id = ?");
            $result = $stmt->execute([$voucherId]);
        }
        
        if ($result && $stmt->rowCount() > 0) {
            auditLog($pdo, 'restore', $voucherType === 'free' ? 'free_voucher' : 'voucher', $voucherId, 'Restored voucher');
            jsonResponse(['success' => true, 'message' => 'E-voucher đã được khôi phục thành công']);
        } else {
            jsonResponse(['success' => false, 'error' => 'Không thể khôi phục e-voucher'], 400);
        }
        
    } catch (Exception $e) {
        error_log('Error restoring voucher: ' . $e->getMessage());
        jsonResponse(['success' => false, 'error' => 'Đã xảy ra lỗi hệ thống'], 500);
    }
} else {
    jsonResponse(['success' => false, 'error' => 'Method not allowed'], 405);
}
?>
