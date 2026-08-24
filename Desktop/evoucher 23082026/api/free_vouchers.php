<?php
require_once __DIR__ . '/api_base.php';
require_once __DIR__ . '/../includes/upload_handler.php';
requireApiLogin();
requireCsrf();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $voucherId = $_GET['id'] ?? null;

    if ($voucherId) {
        $stmt = $pdo->prepare("SELECT * FROM free_vouchers WHERE id = ?");
        $stmt->execute([$voucherId]);
        $voucher = $stmt->fetch();

        if ($voucher) {
            jsonResponse(['success' => true, 'voucher' => $voucher]);
        } else {
            jsonResponse(['success' => false, 'error' => 'Voucher tự do không tồn tại'], 404);
        }
        exit;
    }

    $search = trim($_GET['q'] ?? '');
    if ($search === '') {
        $vouchers = $pdo->query("SELECT * FROM free_vouchers ORDER BY created_at DESC")->fetchAll();
    } else {
        $stmt = $pdo->prepare(
            "SELECT * FROM free_vouchers WHERE code LIKE ? OR description LIKE ? ORDER BY created_at DESC"
        );
        $stmt->execute(['%' . $search . '%', '%' . $search . '%']);
        $vouchers = $stmt->fetchAll();
    }
    jsonResponse($vouchers);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($contentType, 'multipart/form-data') !== false) {
        $data = $_POST;
    } else {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            jsonResponse(['success' => false, 'error' => 'Dữ liệu không hợp lệ'], 400);
        }
    }

    // ── DELETE (tunnelled via POST _method=DELETE) ────────────────────────
    if (isset($data['_method']) && $data['_method'] === 'DELETE') {
        $id = (int) ($data['id'] ?? 0);
        if ($id <= 0) {
            jsonResponse(['success' => false, 'error' => 'ID không hợp lệ'], 400);
        }
        $result = $pdo->prepare('DELETE FROM free_vouchers WHERE id = ?')->execute([$id]);
        jsonResponse(['success' => $result]);
        exit;
    }

    // ── UPDATE ────────────────────────────────────────────────────────────
    if (isset($data['voucher_id']) && (int) $data['voucher_id'] > 0) {
        $voucherId = (int) $data['voucher_id'];

        try {
            $pdo->beginTransaction();

            $logoName      = resolveLogoUpload($data, $_FILES);
            $menuImageName = resolveMenuImageUpload($data, $_FILES);
            $guideContent  = buildGuideContent($data);
            $menuContent   = buildMenuContent($data, $menuImageName);

            $fields = [];
            $params = [];

            if (isset($data['code']) && trim((string) $data['code']) !== '') {
                $fields[] = 'code = ?';
                $params[] = strtoupper(trim((string) $data['code']));
            }
            if (isset($data['sponsor_name']))      { $fields[] = 'sponsor_name = ?'; $params[] = $data['sponsor_name']; }
            if (isset($data['description']))        { $fields[] = 'description = ?';  $params[] = $data['description']; }
            if (array_key_exists('start_date', $data)) {
                $fields[] = 'start_date = ?';
                $params[] = ($data['start_date'] ?? '') !== '' ? $data['start_date'] : null;
            }
            if (array_key_exists('end_date', $data)) {
                $fields[] = 'end_date = ?';
                $params[] = ($data['end_date'] ?? '') !== '' ? $data['end_date'] : null;
            }
            if ($logoName !== null)        { $fields[] = 'logo = ?';           $params[] = $logoName; }
            if ($guideContent !== null)    { $fields[] = 'guide_content = ?';  $params[] = $guideContent; }
            if ($menuContent  !== null)    { $fields[] = 'menu_content = ?';   $params[] = $menuContent; }

            if (empty($fields)) {
                throw new Exception('Không có dữ liệu để cập nhật');
            }

            $params[] = $voucherId;
            $pdo->prepare('UPDATE free_vouchers SET ' . implode(', ', $fields) . ' WHERE id = ?')->execute($params);

            $pdo->commit();
            jsonResponse(['success' => true]);
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log('Error updating free voucher: ' . $e->getMessage());
            jsonResponse(['success' => false, 'error' => 'Đã xảy ra lỗi hệ thống'], 500);
        }
        exit;
    }

    // ── CREATE ────────────────────────────────────────────────────────────
    $codesRaw = $data['codes'] ?? '';
    if (empty($codesRaw)) {
        jsonResponse(['success' => false, 'error' => 'Vui lòng nhập ít nhất một mã code'], 400);
    }

    $codes = is_array($codesRaw)
        ? $codesRaw
        : preg_split('/\r\n|\r|\n/', (string) $codesRaw);

    $logoName      = resolveLogoUpload($data, $_FILES);
    $menuImageName = resolveMenuImageUpload($data, $_FILES);
    $guideContent  = buildGuideContent($data) ?? '';
    $menuContent   = buildMenuContent($data, $menuImageName) ?? '';

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            "INSERT INTO free_vouchers (code, sponsor_name, description, logo, start_date, end_date, guide_content, menu_content)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $inserted   = 0;
        $startDate  = trim($data['start_date'] ?? '');
        $endDate    = trim($data['end_date']   ?? '');

        foreach ($codes as $code) {
            $code = strtoupper(trim($code));
            if ($code === '') { continue; }

            try {
                $stmt->execute([
                    $code,
                    trim($data['sponsor_name'] ?? ''),
                    trim($data['description']  ?? 'TẶNG 1 LY NƯỚC'),
                    $logoName,
                    $startDate !== '' ? $startDate : null,
                    $endDate   !== '' ? $endDate   : null,
                    $guideContent,
                    $menuContent,
                ]);
                $inserted++;
            } catch (PDOException $e) {
                if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                    throw $e;
                }
            }
        }

        if ($inserted === 0) {
            $pdo->rollBack();
            jsonResponse(['success' => false, 'error' => 'Không có mã code hợp lệ nào để thêm'], 400);
        }

        $pdo->commit();
        jsonResponse(['success' => true, 'inserted' => $inserted]);
    } catch (Exception $e) {
        $pdo->rollBack();
        jsonResponse(['success' => false, 'error' => $e->getMessage()], 400);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    $id = (int) ($data['id'] ?? 0);
    if ($id <= 0) {
        jsonResponse(['success' => false, 'error' => 'ID không hợp lệ'], 400);
    }

    $fields = [];
    $params = [];

    if (isset($data['code']))        { $fields[] = 'code = ?';          $params[] = strtoupper(trim($data['code'])); }
    if (isset($data['description'])) { $fields[] = 'description = ?';   $params[] = $data['description']; }
    if (isset($data['sponsor_name'])){ $fields[] = 'sponsor_name = ?';  $params[] = $data['sponsor_name']; }
    if (array_key_exists('start_date', $data)) {
        $fields[] = 'start_date = ?';
        $params[] = ($data['start_date'] ?? '') !== '' ? $data['start_date'] : null;
    }
    if (array_key_exists('end_date', $data)) {
        $fields[] = 'end_date = ?';
        $params[] = ($data['end_date'] ?? '') !== '' ? $data['end_date'] : null;
    }
    if (isset($data['guide_content'])) { $fields[] = 'guide_content = ?'; $params[] = $data['guide_content']; }
    if (isset($data['menu_content']))  { $fields[] = 'menu_content = ?';  $params[] = $data['menu_content']; }
    if (isset($data['status']))        { $fields[] = 'status = ?';         $params[] = $data['status']; }

    if (empty($fields)) {
        jsonResponse(['success' => false, 'error' => 'Không có dữ liệu để cập nhật'], 400);
    }

    $params[] = $id;
    $result   = $pdo->prepare('UPDATE free_vouchers SET ' . implode(', ', $fields) . ' WHERE id = ?')->execute($params);
    jsonResponse(['success' => $result]);

} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        jsonResponse(['success' => false, 'error' => 'ID không hợp lệ'], 400);
    }

    $result = $pdo->prepare('DELETE FROM free_vouchers WHERE id = ?')->execute([$id]);
    jsonResponse(['success' => $result]);
}
