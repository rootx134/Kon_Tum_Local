<?php
require_once __DIR__ . '/api_base.php';
require_once __DIR__ . '/../includes/upload_handler.php';
requireApiLogin();
requireCsrf();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $voucherId = $_GET['id'] ?? 0;

    $stmt = $pdo->prepare("
        SELECT v.*, c.sponsor_name, c.sponsor_short, c.description,
               COALESCE(v.logo, c.logo) as logo,
               c.start_date, c.end_date, c.guide_content, c.menu_content
        FROM vouchers v
        JOIN campaigns c ON v.campaign_id = c.id
        WHERE v.id = ?
    ");
    $stmt->execute([$voucherId]);
    $voucher = $stmt->fetch();

    if ($voucher) {
        echo json_encode(['success' => true, 'voucher' => $voucher]);
    } else {
        echo json_encode(['success' => false, 'error' => 'E-voucher không tồn tại']);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;

    try {
        $pdo->beginTransaction();

        // Resolve logo upload
        $logoName = resolveLogoUpload($data, $_FILES);

        // Get current voucher
        $stmt = $pdo->prepare("SELECT * FROM vouchers WHERE id = ?");
        $stmt->execute([$data['voucher_id']]);
        $voucher = $stmt->fetch();

        if (!$voucher) {
            throw new Exception('E-voucher không tồn tại');
        }

        // Update voucher code
        if (!empty($data['voucher_code'])) {
            $pdo->prepare("UPDATE vouchers SET code = ? WHERE id = ?")
                ->execute([strtoupper($data['voucher_code']), $data['voucher_id']]);
        }

        // Update voucher logo
        if ($logoName) {
            $pdo->prepare("UPDATE vouchers SET logo = ? WHERE id = ?")
                ->execute([$logoName, $data['voucher_id']]);
        }

        // Build guide_content and menu_content using shared helpers
        $guideContent  = buildGuideContent($data) ?? '';
        $menuImageName = resolveMenuImageUpload($data, $_FILES);
        $menuContent   = buildMenuContent($data, $menuImageName) ?? '';

        // Update campaign-level fields (description, guide, menu)
        $pdo->prepare("
            UPDATE campaigns
            SET description = ?, guide_content = ?, menu_content = ?
            WHERE id = ?
        ")->execute([
            $data['description'] ?? '',
            $guideContent,
            $menuContent,
            $voucher['campaign_id'],
        ]);

        $pdo->commit();
        echo json_encode(['success' => true]);

    } catch (RuntimeException|Exception $e) {
        $pdo->rollBack();
        error_log('Error in get_voucher_details API: ' . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Đã xảy ra lỗi hệ thống']);
    }
}
