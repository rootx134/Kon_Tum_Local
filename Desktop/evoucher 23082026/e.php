<?php
require_once 'config.php';
require_once __DIR__ . '/includes/rate_limiter.php';
require_once __DIR__ . '/includes/audit_log.php';

$voucherCode = $_GET['id'] ?? '';

// Rate limit: 30 page views per minute per IP
checkRateLimit('voucher_view', 30, 60);

// If no voucher code provided, show error page
if (empty($voucherCode)) {
    http_response_code(404);
    include __DIR__ . '/includes/error_404.php';
    exit;
}

// Try to find voucher in campaigns first (format: SHORTNAME+CODE, e.g., "CTG123")
// Optimized: avoid CONCAT() to allow index usage on sponsor_short and code columns
$campaignShorts = $pdo->query("SELECT id, sponsor_short FROM campaigns")->fetchAll();
$voucher = null;
foreach ($campaignShorts as $camp) {
    $short = $camp['sponsor_short'];
    if (stripos($voucherCode, $short) === 0 && strlen($voucherCode) > strlen($short)) {
        $codeOnly = substr($voucherCode, strlen($short));
        $stmt = $pdo->prepare("
            SELECT v.*, c.sponsor_name, c.sponsor_short, c.description, 
                   COALESCE(v.logo, c.logo) as logo, 
                   c.start_date, c.end_date, c.guide_content, c.menu_content,
                   'campaign' as voucher_type
            FROM vouchers v
            JOIN campaigns c ON v.campaign_id = c.id
            WHERE c.id = ? AND v.code = ?
        ");
        $stmt->execute([$camp['id'], $codeOnly]);
        $voucher = $stmt->fetch();
        if ($voucher)
            break;
    }
}

// If not found in campaigns, try free_vouchers table
// Free vouchers can have code with or without "FREE" prefix
if (!$voucher) {
    // Try exact match first
    $stmt = $pdo->prepare("
        SELECT *, 
               code as sponsor_short,
               description,
               logo,
               start_date,
               end_date,
               guide_content,
               menu_content,
               'free' as voucher_type
        FROM free_vouchers
        WHERE code = ?
    ");
    $stmt->execute([$voucherCode]);
    $voucher = $stmt->fetch();

    // If not found and code doesn't start with "FREE", try with "FREE" prefix
    if (!$voucher && strpos($voucherCode, 'FREE') !== 0) {
        $stmt = $pdo->prepare("
            SELECT *, 
                   code as sponsor_short,
                   description,
                   logo,
                   start_date,
                   end_date,
                   guide_content,
                   menu_content,
                   'free' as voucher_type
            FROM free_vouchers
            WHERE code = ?
        ");
        $stmt->execute(['FREE' . $voucherCode]);
        $voucher = $stmt->fetch();
    }

    // For free vouchers, set sponsor_name to description or a default
    if ($voucher) {
        $voucher['sponsor_name'] = ($voucher['sponsor_name'] ?? '') ?: ($voucher['description'] ?: 'Voucher tự do');
        $voucher['sponsor_short'] = ''; // Free vouchers don't have sponsor_short prefix
    }
}

if (!$voucher) {
    http_response_code(404);
    include __DIR__ . '/includes/error_404.php';
    exit;
}
// Check if voucher is expired or not yet valid (campaign + free when has dates)
$isExpired = false;
$isNotYetValid = false;

if (!empty($voucher['start_date']) && !empty($voucher['end_date'])) {
    $currentTime = time();
    $startTime = strtotime($voucher['start_date']);
    $endTime = strtotime($voucher['end_date']);
    $isExpired = $endTime <= $currentTime;
    $isNotYetValid = $startTime > $currentTime;

    if ($isExpired && $voucher['status'] !== 'used') {
        if ($voucher['voucher_type'] === 'campaign') {
            $stmt = $pdo->prepare("UPDATE vouchers SET status = 'expired' WHERE id = ?");
        } else {
            $stmt = $pdo->prepare("UPDATE free_vouchers SET status = 'expired' WHERE id = ?");
        }
        $stmt->execute([$voucher['id']]);
        $voucher['status'] = 'expired';
    } elseif (!$isExpired && $voucher['status'] === 'expired') {
        // Self-healing: if voucher is actually not expired but DB says 'expired' (e.g. admin updated end_date), restore to 'unused'
        if ($voucher['voucher_type'] === 'campaign') {
            $stmt = $pdo->prepare("UPDATE vouchers SET status = 'unused' WHERE id = ?");
        } else {
            $stmt = $pdo->prepare("UPDATE free_vouchers SET status = 'unused' WHERE id = ?");
        }
        $stmt->execute([$voucher['id']]);
        $voucher['status'] = 'unused';
    }
}
// Free vouchers without dates => always valid unless used

// Handle voucher usage
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['use_voucher'])) {
    // Stricter rate limit for voucher usage: 5 per minute
    checkRateLimit('voucher_use', 5, 60);

    if ($voucher['status'] === 'unused' && !$isExpired && !$isNotYetValid) {
        try {
            // Update based on voucher type
            if ($voucher['voucher_type'] === 'campaign') {
                $stmt = $pdo->prepare("UPDATE vouchers SET status = 'used', used_at = NOW() WHERE id = ?");
            } else {
                // Free voucher
                $stmt = $pdo->prepare("UPDATE free_vouchers SET status = 'used', used_at = NOW() WHERE id = ?");
            }
            $result = $stmt->execute([$voucher['id']]);

            if ($result) {
                auditLog($pdo, 'use', $voucher['voucher_type'] === 'free' ? 'free_voucher' : 'voucher', (int) $voucher['id'], 'Voucher used: ' . $voucherCode);
                $voucher['status'] = 'used';
                $voucher['used_at'] = date('Y-m-d H:i:s');

                // Redirect to prevent form resubmission
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit;
            }
        } catch (Exception $e) {
            // Only log actual errors, not debug info
            error_log('Voucher update error: ' . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>E-Voucher - <?php echo htmlspecialchars($voucher['sponsor_name'] ?? ''); ?></title>

    <link rel="icon" type="image/png" href="https://fc.kontumplus.com/favicon.png">
    <link rel="shortcut icon" type="image/png" href="https://fc.kontumplus.com/favicon.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">

    <?php
    $ogImage = 'https://fc.kontumplus.com/favicon.png';
    if (!empty($voucher['logo'])) {
        $ogImage = (strpos($voucher['logo'], 'http') === 0) ? $voucher['logo'] : 'https://e.kontumplus.com/uploads/' . ltrim($voucher['logo'], '/');
    }
    ?>
    <meta name="description"
        content="E-Voucher <?php echo htmlspecialchars($voucher['sponsor_name'] ?? ''); ?> - <?php echo htmlspecialchars($voucher['description'] ?? ''); ?>">

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo 'https://e.kontumplus.com/' . ($voucherCode ?? ''); ?>">
    <meta property="og:title" content="E-Voucher | <?php echo htmlspecialchars($voucher['sponsor_name'] ?? ''); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($voucher['description'] ?? ''); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($ogImage); ?>">
    <meta property="og:image:width" content="600">
    <meta property="og:image:height" content="600">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?php echo 'https://e.kontumplus.com/' . ($voucherCode ?? ''); ?>">
    <meta name="twitter:title" content="E-Voucher | <?php echo htmlspecialchars($voucher['sponsor_name'] ?? ''); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($voucher['description'] ?? ''); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($ogImage); ?>">

    <link href="/assets/vendor/fontawesome/css/all.min.css" rel="stylesheet"
        media="print" onload="this.media='all'">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="assets/css/voucher.css?v=<?= filemtime(__DIR__.'/assets/css/voucher.css') ?>">
    <link rel="stylesheet" href="assets/css/style.css?v=<?= filemtime(__DIR__.'/assets/css/style.css') ?>">
    <script src="assets/js/theme.js?v=<?= filemtime(__DIR__.'/assets/js/theme.js') ?>"></script>
</head>

<body>
    <div class="page-background"></div>
    <div class="page-backdrop"></div>

    <div class="main-container">
        <div class="voucher-card">
            <?php if ($voucher['status'] === 'used'): ?>
                <div class="card-used-state">
                    <div class="used-stamp-huge">
                        ĐÃ DÙNG
                        <div class="used-stamp-date">
                            LÚC <?php echo date('H:i d/m/Y', strtotime($voucher['used_at'])); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card-header fade-up-item delay-100">
                <img src="<?php echo !empty($voucher['logo']) ? 'uploads/' . htmlspecialchars($voucher['logo']) : 'https://fc.kontumplus.com/favicon.png'; ?>"
                    alt="Logo" class="brand-logo" onerror="this.src='https://fc.kontumplus.com/favicon.png'">
                <div class="brand-info">
                    <h1 class="brand-name"><?php echo htmlspecialchars($voucher['sponsor_name'] ?? ''); ?></h1>
                    <div class="offer-title"><?php echo htmlspecialchars($voucher['description'] ?? ''); ?></div>
                </div>
            </div>

            <div class="stamp-wrapper fade-up-item delay-200"><img src="fc.png" alt="Stamp" class="stamp-img"></div>

            <div class="ticket-box fade-up-item delay-300">
                <div class="voucher-code-label">Mã E-Voucher</div>
                <div class="code-display">
                    <?php
                    if (($voucher['voucher_type'] ?? '') === 'campaign') {
                        echo htmlspecialchars(($voucher['sponsor_short'] ?? '') . ($voucher['code'] ?? ''));
                    } else {
                        echo htmlspecialchars($voucher['code'] ?? '');
                    }
                    ?>
                </div>

                <?php
                $hasStart = !empty($voucher['start_date']);
                $hasEnd = !empty($voucher['end_date']);
                ?>
                <div class="expiry-info">
                    <i class="far fa-clock"></i>
                    <?php if ($hasStart && $hasEnd): ?>
                        Hiệu lực: <?php echo date('d/m/Y', strtotime($voucher['start_date'])); ?> -
                        <?php echo date('d/m/Y', strtotime($voucher['end_date'] . ' -1 day')); ?>
                    <?php elseif ($hasEnd): ?>
                        HSD: Đến hết <?php echo date('d/m/Y', strtotime($voucher['end_date'] . ' -1 day')); ?>
                    <?php elseif ($hasStart): ?>
                        Từ ngày <?php echo date('d/m/Y', strtotime($voucher['start_date'])); ?>
                    <?php else: ?>
                        Không giới hạn thời gian
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($voucher['status'] === 'unused' && !$isExpired && !$isNotYetValid): ?>
                <p class="instructions-text fade-up-item delay-400">VUI LÒNG ĐƯA MÀN HÌNH NÀY CHO NHÂN VIÊN QUÁN BẤM XÁC
                    NHẬN TẠI QUẦY.</p>
                <form method="POST" id="use-form" class="fade-up-item delay-500">
                    <input type="hidden" name="use_voucher" value="1">
                    <button type="button" class="btn btn-primary pulse-btn" onclick="openSheet('confirm-sheet')">XÁC NHẬN SỬ
                        DỤNG</button>
                </form>
            <?php elseif ($voucher['status'] === 'used'): ?>
                <div class="status-message status-used fade-up-item delay-400">
                    <i class="fas fa-check-circle"></i> VOUCHER ĐÃ ĐƯỢC SỬ DỤNG
                    <small>Lúc <?php echo date('H:i d/m/Y', strtotime($voucher['used_at'])); ?></small>
                </div>
            <?php elseif ($isExpired || $voucher['status'] === 'expired'): ?>
                <div class="status-message status-expired fade-up-item delay-400">
                    <i class="fas fa-times-circle"></i> VOUCHER ĐÃ HẾT HẠN
                </div>
            <?php elseif ($isNotYetValid): ?>
                <div class="status-message status-upcoming fade-up-item delay-400">
                    <i class="fas fa-clock"></i> VOUCHER CHƯA KHẢ DỤNG
                    <small>Hiệu lực từ: <?php echo date('d/m/Y', strtotime($voucher['start_date'])); ?></small>
                </div>
            <?php endif; ?>
        </div>

        <div class="bottom-actions fade-up-item delay-600">
            <button class="action-chip" onclick="openSheet('guide-sheet')"><i class="fas fa-info-circle"></i> Hướng dẫn</button>
            <button class="action-chip" onclick="openSheet('menu-sheet')"><i class="fas fa-utensils"></i> Menu áp dụng</button>
            <button class="action-chip theme-toggle-btn" title="Chuyển chế độ sáng/tối">
                <span class="theme-icon-wrapper"></span>
                <span class="theme-text">Tự động</span>
            </button>
        </div>
    </div>

    <div class="sheet-overlay" id="main-overlay" onclick="closeAllSheets()"></div>

    <div class="bottom-sheet" id="guide-sheet">
        <div class="sheet-handle"></div>
        <div class="sheet-header">
            <div class="sheet-title">Hướng dẫn sử dụng</div>
            <button class="sheet-close" onclick="closeAllSheets()"><i class="fas fa-times"></i></button>
        </div>
        <div class="sheet-body">
            <?php 
            if (!empty($voucher['guide_content'])) {
                $guideData = @json_decode($voucher['guide_content'], true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($guideData)) {
                    echo '<div class="guide-json-content">';
                    
                    // Address Section
                    echo '<div class="guide-address-section">';
                    echo '<div class="guide-icon-box"><i class="fas fa-map-marker-alt"></i></div>';
                    echo '<div class="guide-info-col">';
                    echo '<p class="guide-title">Địa điểm sử dụng thẻ:</p>';
                    
                    // Add the sponsor name here as requested
                    if (!empty($voucher['sponsor_name'])) {
                        echo '<h3 class="guide-sponsor-name">' . htmlspecialchars($voucher['sponsor_name']) . '</h3>';
                    }
                    
                    echo '<div class="guide-details">';
                    if (!empty($guideData['address'])) {
                        echo '<div class="guide-detail-item"><i class="fas fa-location-dot guide-detail-icon"></i> <span>' . htmlspecialchars($guideData['address']) . '</span></div>';
                    }
                    if (!empty($guideData['time'])) {
                        echo '<div class="guide-detail-item"><i class="far fa-clock guide-detail-icon"></i> <span>' . htmlspecialchars($guideData['time']) . '</span></div>';
                    }
                    if (!empty($guideData['phone'])) {
                        echo '<div class="guide-detail-item last"><i class="fas fa-phone-alt guide-detail-icon"></i> <span>' . htmlspecialchars($guideData['phone']) . '</span></div>';
                    }
                    echo '</div>'; // End info details
                    echo '</div>'; // End info column
                    echo '</div>'; // End flex box
                    
                    // Instructions Section
                    echo '<div>';
                    echo '<h4 class="guide-instructions-title"><i class="fas fa-clipboard-list"></i> Hướng dẫn thao tác thẻ:</h4>';
                    echo '<div class="guide-instructions-list">';
                    echo '<p class="guide-step"><span class="guide-step-number">1</span>Đến quán theo địa điểm trên.</p>';
                    echo '<p class="guide-step"><span class="guide-step-number">2</span>Mở trang này và đưa cho thu ngân bấm "Xác nhận sử dụng".</p>';
                    echo '<p class="guide-step last-step"><span class="guide-step-number">3</span>Chọn một món đồ theo danh sách đính kèm thẻ (nếu có).</p>';
                    echo '<p class="guide-footer-text">Cảm ơn bạn đã đồng hành cùng Kon Tum +</p>';
                    echo '</div>'; // End instruction list
                    echo '</div>'; // End instruction section
                    
                    echo '</div>';
                } else {
                    echo $voucher['guide_content'];
                }
            } else {
                echo '<p style="font-size: 15px; line-height: 1.6; color: #444;">Mang voucher này tới cửa hàng để được áp dụng khi thanh toán. Liên hệ fanpage Kon Tum Plus nếu cần hỗ trợ.</p>';
            }
            ?>
        </div>
    </div>

    <div class="bottom-sheet" id="menu-sheet">
        <div class="sheet-handle"></div>
        <div class="sheet-header">
            <div class="sheet-title">Menu áp dụng</div>
            <button class="sheet-close" onclick="closeAllSheets()"><i class="fas fa-times"></i></button>
        </div>
        <div class="sheet-body">
            <?php 
            if (!empty($voucher['menu_content'])) {
                $menuData = @json_decode($voucher['menu_content'], true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($menuData)) {
                    echo '<div class="menu-json-content">';

                    if (!empty($menuData['note'])) {
                        echo '<div style="background:linear-gradient(135deg,#f97316,#ef4444);color:white;border-radius:12px;padding:14px 16px;margin-bottom:16px;display:flex;align-items:flex-start;gap:10px;">';
                        echo '<i class="fas fa-exclamation-circle" style="margin-top:2px;flex-shrink:0;font-size:18px;"></i>';
                        echo '<div><div style="font-weight:700;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;opacity:0.85;">Lưu ý quan trọng</div>';
                        echo '<div style="font-size:15px;line-height:1.5;">' . nl2br(htmlspecialchars($menuData['note'])) . '</div></div>';
                        echo '</div>';
                    }

                    if (!empty($menuData['items'])) {
                        echo '<div class="menu-card">';
                        echo '<h4 class="menu-card-title"><i class="fas fa-gift"></i>Mời bạn chọn 1 món trong menu bên dưới</h4>';
                        echo '<ul class="menu-item-list">';
                        $items = explode("\n", trim($menuData['items']));
                        foreach ($items as $item) {
                            if (trim($item) !== '') {
                                echo '<li class="menu-item"><i class="fas fa-mug-hot menu-item-icon"></i>' . htmlspecialchars(trim($item)) . '</li>';
                            }
                        }
                        echo '</ul>';
                        echo '</div>';
                    }
                    if (!empty($menuData['image'])) {
                        $imageSrc = htmlspecialchars($menuData['image']);
                        // Check if it's external or local
                        if (strpos($imageSrc, 'http') === false) {
                            $imageSrc = 'uploads/' . $imageSrc;
                        }
                        echo '<p style="text-align:center;font-size:12px;color:#94a3b8;margin-bottom:8px;"><i class="fas fa-search-plus"></i> Chụm/giãn 2 ngón để phóng to ảnh menu</p>';
                        echo '<div class="menu-image-container">';
                        echo '<img src="' . $imageSrc . '" alt="Menu" class="menu-image" loading="lazy">';
                        echo '</div>';
                    } else if (empty($menuData['items'])) {
                        echo '<div class="menu-empty">';
                        echo '<i class="fas fa-utensils"></i>';
                        echo '<p>Số lượng món có thể áp dụng theo chính sách tại quán, vui lòng hỏi lại nhân viên để được hỗ trợ tốt nhất.</p>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo $voucher['menu_content'];
                }
            } else {
                echo '<p style="font-size: 15px; line-height: 1.6; color: #444; text-align: center; padding: 20px 0;">Voucher áp dụng cho các món theo chính sách quán đưa ra.</p>';
            }
            ?>
        </div>
    </div>

    <div class="bottom-sheet" id="confirm-sheet">
        <div class="sheet-handle"></div>
        <div class="sheet-body text-center pt-10">
            <div class="icon-circle icon-warning"><i class="fas fa-exclamation-triangle"></i></div>
            <h3 class="sheet-alert-title">Đổi E-Voucher</h3>
            <p class="sheet-alert-text">Thao tác này sẽ khóa thẻ voucher và chỉ thực hiện 1 lần duy nhất bởi
                nhân viên của hệ thống đối tác. Bạn có chắc chắn?</p>
            <div class="confirm-actions">
                <button class="btn btn-outline" onclick="closeAllSheets()">Hủy bỏ</button>
                <button class="btn btn-primary" id="btn-submit-confirm" onclick="executeVoucher()">Đồng ý dùng</button>
            </div>
        </div>
    </div>

    <div class="bottom-sheet" id="success-sheet">
        <div class="sheet-handle"></div>
        <div class="sheet-body text-center pt-10">
            <div class="icon-circle icon-success"><i class="fas fa-check"></i></div>
            <h3 class="sheet-success-title">Kon Tum +
                Xin Cảm Ơn!</h3>
            <p class="sheet-alert-text mb-20">Voucher đã được sử dụng thành công.</p>
            <div class="success-time-box">
                Thời gian: <strong><?php echo date('H:i d/m/Y'); ?></strong>
            </div>
            <button class="btn btn-primary mb-20" onclick="forceReload()">Đóng & Tải lại (<span
                    id="countdown-num">5</span>s)</button>
        </div>
    </div>

    <script>
        const overlay = document.getElementById('main-overlay');
        let activeSheet = null;

        function openSheet(id) {
            if (activeSheet) closeAllSheets();
            activeSheet = document.getElementById(id);
            overlay.classList.add('active');
            activeSheet.classList.add('active');
        }

        function closeAllSheets() {
            if (activeSheet) { activeSheet.classList.remove('active'); activeSheet = null; }
            overlay.classList.remove('active');
        }

        function executeVoucher() {
            const btn = document.getElementById('btn-submit-confirm');
            btn.innerHTML = '<div class="spinner"></div> Đang xử lý...';
            btn.disabled = true;

            const form = document.getElementById('use-form');
            const formData = new FormData(form);

            const timer = new Promise(resolve => setTimeout(resolve, 1500));
            const request = fetch(window.location.href, { method: 'POST', body: formData, redirect: 'follow' });

            Promise.all([timer, request])
                .then(([_, res]) => {
                    if (res.ok || res.status === 200 || res.redirected) {
                        closeAllSheets();
                        setTimeout(() => { openSheet('success-sheet'); startCountdown(); }, 400);
                    } else throw new Error();
                })
                .catch(err => {
                    alert('Có lỗi kiểm tra kết nối. Hãy tải lại trang và thử lại!');
                    btn.innerHTML = 'Đồng ý dùng';
                    btn.disabled = false;
                });
        }

        function forceReload() {
            window.location.assign(window.location.pathname + '?t=' + new Date().getTime());
        }

        let _countdownTimer = null;
        function startCountdown() {
            if (_countdownTimer) clearInterval(_countdownTimer);
            let count = 5;
            const el = document.getElementById('countdown-num');
            _countdownTimer = setInterval(() => {
                count--;
                if (count <= 0) { clearInterval(_countdownTimer); forceReload(); }
                else el.innerText = count;
            }, 1000);
        }

        function copyVoucherCode() {
            const code = document.querySelector('.code-display')?.textContent?.trim();
            if (!code) return;
            const btn = document.getElementById('copy-code-btn');
            navigator.clipboard?.writeText(code).then(() => {
                if (btn) { btn.innerHTML = '<i class="fas fa-check"></i> Đã copy'; setTimeout(() => { btn.innerHTML = '<i class="fas fa-copy"></i> Copy mã'; }, 2000); }
            }).catch(() => {
                if (btn) { btn.innerHTML = '<i class="fas fa-times"></i> Lỗi'; setTimeout(() => { btn.innerHTML = '<i class="fas fa-copy"></i> Copy mã'; }, 2000); }
            });
        }
    </script>
</body>

</html>