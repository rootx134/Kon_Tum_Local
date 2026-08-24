<?php

require_once __DIR__ . '/api_base.php';
requireApiLogin();
requireCsrf();

// Sao chép một e-voucher thuộc chiến dịch sang bảng voucher tự do

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'error' => 'Method not allowed'], 405);
}

$data      = json_decode(file_get_contents('php://input'), true) ?: [];
$voucherId = (int) ($data['voucher_id'] ?? 0);

if ($voucherId <= 0) {
    jsonResponse(['success' => false, 'error' => 'voucher_id không hợp lệ'], 400);
}

// Lấy thông tin voucher + campaign
$stmt = $pdo->prepare(
    "SELECT v.id, v.code, COALESCE(v.logo, c.logo) as logo,
            c.sponsor_name, c.sponsor_short, c.description, c.start_date, c.end_date, c.guide_content, c.menu_content
     FROM vouchers v
     JOIN campaigns c ON v.campaign_id = c.id
     WHERE v.id = ?"
);
$stmt->execute([$voucherId]);
$voucher = $stmt->fetch();

if (!$voucher) {
    jsonResponse(['success' => false, 'error' => 'E-voucher không tồn tại'], 404);
}

// Tạo mã đầy đủ: FREE + sponsor_short + code
$fullCode = 'FREE' . $voucher['sponsor_short'] . $voucher['code'];

// Kiểm tra mã đã tồn tại chưa
$checkStmt = $pdo->prepare("SELECT id FROM free_vouchers WHERE code = ?");
$checkStmt->execute([$fullCode]);
if ($checkStmt->fetch()) {
    jsonResponse(['success' => false, 'error' => 'Mã voucher "' . $fullCode . '" đã tồn tại trong danh sách voucher tự do'], 400);
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO free_vouchers (code, sponsor_name, description, logo, start_date, end_date, guide_content, menu_content)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $fullCode,
        $voucher['sponsor_name'],
        $voucher['description'],
        $voucher['logo'],
        $voucher['start_date'],
        $voucher['end_date'],
        $voucher['guide_content'],
        $voucher['menu_content'],
    ]);

    jsonResponse([
        'success'        => true,
        'free_voucher_id'=> $pdo->lastInsertId(),
        'code'           => $fullCode,
    ]);
} catch (PDOException $e) {
    // Bắt riêng lỗi Duplicate entry (race condition)
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        jsonResponse(['success' => false, 'error' => 'Mã voucher "' . $fullCode . '" đã tồn tại'], 400);
    }
    error_log('Error copying voucher: ' . $e->getMessage());
    jsonResponse(['success' => false, 'error' => 'Đã xảy ra lỗi hệ thống'], 500);
} catch (Exception $e) {
    error_log('Error copying voucher: ' . $e->getMessage());
    jsonResponse(['success' => false, 'error' => 'Đã xảy ra lỗi hệ thống'], 500);
}
?>
