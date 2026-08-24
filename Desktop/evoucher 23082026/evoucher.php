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
        if ($voucher) break;
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
                auditLog($pdo, 'use', $voucher['voucher_type'] === 'free' ? 'free_voucher' : 'voucher', (int)$voucher['id'], 'Voucher used: ' . $voucherCode);
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
    <title>E-Voucher - <?php echo htmlspecialchars($voucher['sponsor_name']); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://fc.kontumplus.com/favicon.png">
    <link rel="shortcut icon" type="image/png" href="https://fc.kontumplus.com/favicon.png">
    
    <!-- SEO Meta Tags -->
    <?php
    $ogImage = 'https://fc.kontumplus.com/favicon.png';
    if (!empty($voucher['logo'])) {
        $ogImage = (strpos($voucher['logo'], 'http') === 0) ? $voucher['logo'] : 'https://e.kontumplus.com/uploads/' . ltrim($voucher['logo'], '/');
    }
    ?>
    <meta name="description" content="E-Voucher <?php echo htmlspecialchars($voucher['sponsor_name']); ?> - <?php echo htmlspecialchars($voucher['description']); ?>">
    <meta name="keywords" content="e-voucher, <?php echo htmlspecialchars(strtolower($voucher['sponsor_name'])); ?>, kon tum, quà tặng, fan cứng">
    <meta name="author" content="Kon Tum +">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo 'https://e.kontumplus.com/' . $voucherCode; ?>">
    <meta property="og:title" content="E-Voucher fan cứng | <?php echo htmlspecialchars($voucher['sponsor_name']); ?>">
    <meta property="og:description" content="Tương tác để có huy hiệu fan cứng và nhận evoucher đổi đồ uống vào mỗi cuối tháng tại fanpage Kon Tum +">
    <meta property="og:image" content="<?php echo htmlspecialchars($ogImage); ?>">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo 'https://e.kontumplus.com/' . $voucherCode; ?>">
    <meta property="twitter:title" content="E-Voucher <?php echo htmlspecialchars($voucher['sponsor_name']); ?>">
    <meta property="twitter:description" content="<?php echo htmlspecialchars($voucher['description']); ?>">
    <meta property="twitter:image" content="<?php echo htmlspecialchars($ogImage); ?>">
    
    <link href="/assets/vendor/fontawesome/css/all.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="/assets/vendor/fontawesome/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    </noscript>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        .page-wrapper {
            width: 100%;
            max-width: 600px;
            position: relative;
            z-index: 2;
        }

        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://fc.kontumplus.com/bg_voucher.jpeg') center/cover;
            background-attachment: fixed;
            z-index: 1;
            will-change: transform;
        }

        .voucher-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            padding: 30px;
            width: 100%;
            position: relative;
            margin-bottom: 20px;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .voucher-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            gap: 25px;
        }

        .logo-container {
            flex-shrink: 0;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #ff6b35;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff8f0;
            position: relative;
            overflow: hidden;
            animation: scaleIn 0.5s ease-out;
            margin-left: 30px;
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.5);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .logo-placeholder {
            font-size: 24px;
            color: #ff6b35;
        }

        .brand-info {
            flex: 1;
            min-width: 0;
        }

        .brand-name {
            font-size: 33px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 5px;
            line-height: 1.2;
            animation: slideInLeft 0.6s ease-out;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .offer-text {
            font-size: 22px;
            font-weight: 600;
            color: #ff6b35;
            margin-bottom: 10px;
            animation: slideInLeft 0.7s ease-out;
        }

        .separator {
            border: none;
            border-top: 2px dashed #ff6b35;
            margin: 15px 0;
        }

        .instructions {
            color: #ff6b35;
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 20px;
            text-align: center;
        }

        .stamp-container {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .stamp-image {
            max-width: 250px;
            height: auto;
            display: block;
            margin: 0 auto;
            transform: rotate(-2deg);
            will-change: transform;
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            animation: rotateIn 0.8s ease-out;
        }

        @keyframes rotateIn {
            from {
                opacity: 0;
                transform: rotate(-90deg) scale(0.5);
            }
            to {
                opacity: 1;
                transform: rotate(-2deg) scale(1);
            }
        }

        .voucher-details {
            text-align: center;
            margin: 20px 0;
        }

        .voucher-code {
            font-size: 20px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 10px;
            font-family: monospace;
        }

        .expiry-date {
            color: #ff6b35;
            font-size: 16px;
            font-weight: 500;
        }

        .use-button {
            width: 100%;
            background: linear-gradient(135deg, #ff6b35, #ff8c42);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 10px 0;
        }

        .use-button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.4);
        }

        .use-button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .use-button.loading {
            position: relative;
            pointer-events: none;
        }

        .use-button.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .footer-container {
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            padding: 20px 30px;
            animation: fadeInUp 1s ease-out;
            position: relative;
        }

        .footer-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            border-radius: 20px;
            z-index: 1;
            pointer-events: none; /* Allow clicks to pass through */
        }

        .footer-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            position: relative;
            z-index: 2; /* Ensure buttons are above overlay */
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .footer-btn {
            padding: 14px 20px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .footer-btn.primary {
            background: #ff6b35;
            color: white;
        }

        .footer-btn.secondary {
            background: white;
            color: #ff6b35;
            border: 2px solid #ff6b35;
        }

        .footer-btn:hover {
            transform: translateY(-1px);
        }

        .footer-container.has-overlay .footer-btn {
            opacity: 0.5;
            filter: grayscale(100%);
        }

        .footer-container.has-overlay .footer-btn:hover {
            opacity: 0.7;
        }

        .voucher-container.used {
            position: relative;
        }

        .used-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            pointer-events: none; /* Allow clicks to pass through */
        }

        .used-text {
            color: white;
            font-size: 42px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 4px;
            animation: blink 1s infinite;
            transform: rotate(-15deg);
            text-shadow: 3px 3px 6px rgba(0,0,0,0.5);
        }

        @keyframes blink {
            0%, 50% { 
                opacity: 1; 
                transform: rotate(-15deg) scale(1);
            }
            51%, 100% { 
                opacity: 0.5; 
                transform: rotate(-15deg) scale(1.05);
            }
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .modal.active {
            display: flex;
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 30px;
            max-width: 500px;
            width: 100%;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            transform: scale(0.7) translateY(-50px);
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .modal.active .modal-content {
            transform: scale(1) translateY(0);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            animation: slideInDown 0.5s ease-out 0.1s both;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            transform: scale(1);
        }

        .close-btn:hover {
            background: #f0f0f0;
            color: #333;
            transform: scale(1.1);
        }

        .close-btn:active {
            transform: scale(0.95);
        }

        .modal-body {
            line-height: 1.6;
            color: #333;
            animation: fadeInUp 0.5s ease-out 0.2s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .confirm-message {
            text-align: center;
            padding: 20px 0;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff6b35, #ff8c42);
            color: white;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }

        .success-message {
            text-align: center;
            padding: 20px 0;
        }

        .used-time {
            color: #666;
            font-size: 14px;
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .page-wrapper {
                max-width: 100%;
                min-height: 100vh;
            }

            .voucher-container {
                padding: 20px;
                margin-bottom: 15px;
            }

            .footer-container {
                padding: 15px 20px;
            }
            
            .logo-container {
                width: 110px;
                height: 110px;
                
            }
            
            .brand-name {
                font-size: 30px;
            }
            
            .offer-text {
                font-size: 20px;
            }
            
            .stamp-text {
                font-size: 24px;
            }
            
            .footer-buttons {
                gap: 10px;
            }

            .footer-btn {
                font-size: 15px;
                padding: 14px 18px;
            }
        }

        @media (max-width: 480px) {
            .voucher-container {
                padding: 15px;
                margin-bottom: 10px;
            }

            .footer-container {
                padding: 15px;
            }
            
            .brand-name {
                font-size: 22px;
            }
            
            .offer-text {
                font-size: 18px;
            }

            .footer-btn {
                font-size: 14px;
                padding: 12px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="background-image"></div>
    
    <div class="page-wrapper">
    <div class="voucher-container <?php echo $voucher['status'] === 'used' ? 'used' : ''; ?>" id="voucher-container">
        <div class="voucher-header">
            <div class="logo-container">
                <?php if ($voucher['logo']): ?>
                    <img src="uploads/<?php echo htmlspecialchars($voucher['logo']); ?>" alt="Logo">
                <?php else: ?>
                    <div class="logo-placeholder">
                        <i class="fas fa-coffee"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="brand-info">
                <div class="brand-name"><?php echo htmlspecialchars($voucher['sponsor_name']); ?></div>
                <div class="offer-text">| <?php echo htmlspecialchars($voucher['description']); ?></div>
            </div>
        </div>

        <hr class="separator">

        <div class="instructions">
            MỞ TRANG NÀY VÀ ĐƯA CHO QUÁN BẤM XÁC NHẬN SỬ DỤNG.
        </div>

        <div class="stamp-container">
            <img src="https://fc.kontumplus.com/fc.png" alt="FAN CỨNG Stamp" class="stamp-image">
        </div>

        <div class="voucher-details">
            <div class="voucher-code">Mã voucher: <?php 
                if ($voucher['voucher_type'] === 'campaign') {
                    echo htmlspecialchars($voucher['sponsor_short'] . $voucher['code']);
                } else {
                    echo htmlspecialchars($voucher['code']);
                }
            ?></div>
            <?php
                $hasStart = !empty($voucher['start_date']);
                $hasEnd   = !empty($voucher['end_date']);
            ?>
            <?php if ($hasStart && $hasEnd): ?>
                <div class="expiry-date">
                    Hiệu lực: <?php echo date('d-m-Y', strtotime($voucher['start_date'])); ?>
                    đến hết <?php echo date('d-m-Y', strtotime($voucher['end_date'] . ' -1 day')); ?>
                </div>
            <?php elseif ($hasEnd): ?>
                <div class="expiry-date">Hạn đổi đến hết <?php echo date('d-m-Y', strtotime($voucher['end_date'] . ' -1 day')); ?></div>
            <?php elseif ($hasStart): ?>
                <div class="expiry-date">Hiệu lực từ <?php echo date('d-m-Y', strtotime($voucher['start_date'])); ?></div>
            <?php else: ?>
                <div class="expiry-date" style="color: #28a745;">Voucher tự do - Không giới hạn thời gian</div>
            <?php endif; ?>
        </div>

        <?php if ($voucher['status'] === 'unused' && !$isExpired && !$isNotYetValid): ?>
            <form method="POST" id="use-voucher-form">
                <input type="hidden" name="use_voucher" value="1">
                <button type="button" class="use-button" id="use-voucher-btn" onclick="submitForm()">
                    <span id="btn-text">XÁC NHẬN SỬ DỤNG</span>
                </button>
            </form>
        <?php elseif ($voucher['status'] === 'used'): ?>
                <button class="use-button" disabled>
                Thời gian sử dụng: <?php echo date('H:i:s d/m/Y', strtotime($voucher['used_at'])); ?>
            </button>
        <?php elseif ($isExpired): ?>
            <button class="use-button" disabled>
                Đã hết hạn
            </button>
        <?php elseif ($isNotYetValid && $voucher['voucher_type'] === 'campaign'): ?>
            <button class="use-button" disabled>
                E-voucher có hiệu lực từ ngày <?php echo date('d/m/Y', strtotime($voucher['start_date'])); ?>
            </button>
        <?php endif; ?>

        <?php if ($voucher['status'] === 'used'): ?>
            <div class="used-overlay">
                <div class="used-text">Đã sử dụng</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer Buttons Container -->
    <div class="footer-container <?php echo ($voucher['status'] === 'used') ? 'has-overlay' : ''; ?>">
        <?php if ($voucher['status'] === 'used'): ?>
            <div class="footer-overlay"></div>
        <?php endif; ?>
        <div class="footer-buttons">
            <button class="footer-btn primary" onclick="openModal('guide-modal')">
                <i class="fas fa-info-circle"></i> Hướng dẫn
            </button>
            <button class="footer-btn secondary" onclick="openModal('menu-modal')">
                <i class="fas fa-utensils"></i> Xem Menu được chọn
            </button>
        </div>
    </div>
    </div><!-- End page-wrapper -->

    <!-- Guide Modal -->
    <div id="guide-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Hướng dẫn sử dụng</h3>
                <button class="close-btn" onclick="closeModal('guide-modal')">&times;</button>
            </div>
            <div class="modal-body">
                <?php 
                $guideJson = @json_decode($voucher['guide_content'], true);
                if ($guideJson && is_array($guideJson) && isset($guideJson['address'])): 
                    $address = $guideJson['address'] ?: '';
                    $time = $guideJson['time'] ?: '';
                    $phone = $guideJson['phone'] ?: '';
                    $sponsorName = isset($voucher['sponsor_name']) ? $voucher['sponsor_name'] : '';
                ?>
                <div style='font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; background-color: #ffffff;'>
                    <div style='display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;'>
                        <div style='margin-right: 15px; min-width: 40px;'>
                            <svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                <path d='M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z' fill='#3B82F6'/>
                            </svg>
                        </div>
                        <div>
                            <p style='margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;'>Địa chỉ sử dụng e-voucher:</p>
                            <h3 style='margin: 0 0 8px 0; color: #d9534f; font-size: 22px;'><?php echo htmlspecialchars($sponsorName); ?></h3>
                            <p style='margin: 0; color: #555; font-size: 16px; line-height: 1.6;'>
                                <i class="fas fa-map-marker-alt" style="color:#3B82F6; margin-right:6px; width:16px; text-align:center;"></i><?php echo htmlspecialchars($address); ?><br>
                                <i class="fas fa-clock" style="color:#3B82F6; margin-right:6px; width:16px; text-align:center;"></i><?php echo htmlspecialchars($time); ?><br>
                                <i class="fas fa-phone" style="color:#3B82F6; margin-right:6px; width:16px; text-align:center;"></i><?php echo htmlspecialchars($phone); ?>
                            </p>
                        </div>
                    </div>
                    <div>
                        <h4 style='margin: 0 0 15px 0; color: #3B82F6; font-size: 18px; font-weight: bold;'>Hướng dẫn sử dụng e-voucher:</h4>
                        <div style='color: #333; font-size: 16px; line-height: 1.7;'>
                            <p style='margin: 0 0 10px 0;'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>
                            <p style='margin: 0 0 10px 0;'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm 'Xác nhận sử dụng'.</p>
                            <p style='margin: 0 0 15px 0;'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>
                            <p style='margin: 0; font-style: italic; color: #777;'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                    <?php echo $voucher['guide_content'] ?: '<p>Vui lòng liên hệ với nhà tài trợ để biết thêm chi tiết.</p>'; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Menu Modal -->
    <div id="menu-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Menu được chọn</h3>
                <button class="close-btn" onclick="closeModal('menu-modal')">&times;</button>
            </div>
            <div class="modal-body">
                <?php 
                $menuJson = @json_decode($voucher['menu_content'], true);
                if ($menuJson && is_array($menuJson)): 
                ?>
                <div style='text-align: center;'>
                    <p style='margin: 0 0 15px 0; font-size: 18px; font-weight: bold; color: #3B82F6;'>
                        <i class="fas fa-heart" style="margin-right:8px; color:#e94e77;"></i>Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới
                    </p>
                    
                    <?php if (!empty($menuJson['image'])): ?>
                        <div style="margin-bottom: 20px;">
                            <img src='uploads/<?php echo htmlspecialchars($menuJson['image']); ?>' alt='menu' border='0' style='max-width: 100%; height: auto; border-radius: 8px;'>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($menuJson['items'])): ?>
                        <div style='text-align: left; display: inline-block; width: 100%;'>
                        <?php 
                            $lines = explode("\n", $menuJson['items']);
                            foreach($lines as $line) {
                                $line = trim($line);
                                if ($line === '') continue;
                                if (strpos($line, '---') !== false) {
                                    echo "<br><div style='text-align: center; font-style: italic; color: #666; margin-bottom: 5px; font-weight: bold;'>".htmlspecialchars($line)."</div>";
                                } else {
                                    echo "<div style='font-size: 15px; margin-bottom: 8px; padding-left: 10px; border-left: 3px solid #ff6b35;'>" . htmlspecialchars($line) . "</div>";
                                }
                            }
                        ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                    <?php echo $voucher['menu_content'] ?: '<p>Vui lòng liên hệ với nhà tài trợ để xem menu chi tiết.</p>'; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Confirm Use Modal -->
    <div id="confirm-use-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Xác nhận sử dụng E-voucher</h3>
                <button class="close-btn" onclick="closeModal('confirm-use-modal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="confirm-message">
                    <i class="fas fa-question-circle" style="font-size: 48px; color: #ff6b35; margin-bottom: 20px;"></i>
                    <p style="font-size: 18px; margin-bottom: 10px;">Bạn có chắc chắn muốn sử dụng evoucher này?</p>
                    <p style="color: #666; font-size: 14px;">Thao tác này phải được xác nhận bởi quán.</p>
                </div>
                <div style="display: flex; justify-content: center; gap: 15px; margin-top: 30px;">
                    <button class="btn btn-primary" onclick="confirmUseVoucher()">
                        <i class="fas fa-check"></i> ĐỒNG Ý
                    </button>
                    <button class="btn btn-secondary" onclick="closeModal('confirm-use-modal')">
                        <i class="fas fa-times"></i> HUỶ
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">"KON TUM +" CẢM ƠN BẠN!</h3>
                <button class="close-btn" onclick="closeModal('success-modal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="success-message">
                    <i class="fas fa-check-circle" style="font-size: 48px; color: #28a745; margin-bottom: 20px;"></i>
                    <p style="font-size: 18px; margin-bottom: 10px;">Evoucher được sử dụng thành công!</p>
                    <div class="used-time">
                        Thời gian sử dụng: <?php echo date('H:i:s d/m/Y'); ?>
                    </div>
                    <div id="countdown" style="margin-top: 15px; font-size: 14px; color: #666;">
                        Trang sẽ tự động tải lại sau <span id="countdown-number">10</span> giây...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Optimized modal functions
        const modalCache = new Map();
        
        function openModal(modalId) {
            if (!modalCache.has(modalId)) {
                modalCache.set(modalId, document.getElementById(modalId));
            }
            const modal = modalCache.get(modalId);
            
            // Force reflow to ensure display: flex is applied before animation
            modal.style.display = 'flex';
            modal.offsetHeight; // Trigger reflow
            
            // Add active class for animation
            setTimeout(() => {
                modal.classList.add('active');
            }, 10);
        }

        function closeModal(modalId) {
            if (!modalCache.has(modalId)) {
                modalCache.set(modalId, document.getElementById(modalId));
            }
            const modal = modalCache.get(modalId);
            
            // Remove active class first for exit animation
            modal.classList.remove('active');
            
            // Hide modal after animation completes
            setTimeout(() => {
                modal.style.display = 'none';

                // If closing success modal, reload page to update voucher status
                if (modalId === 'success-modal') {
                    window.location.reload();
                }
            }, 400); // Match transition duration
        }

        // Close modals when clicking outside - optimized
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        }, { passive: true });

        // Handle voucher submit - show confirmation modal first
        function submitForm() {
            openModal('confirm-use-modal');
        }

        // Confirm use voucher: submit form bằng AJAX, sau đó hiển thị modal success
        function confirmUseVoucher() {
            const confirmBtn = document.querySelector('#confirm-use-modal .btn-primary');
            const originalText = confirmBtn.innerHTML;
            
            // Show loading state
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<div class="loading-spinner"></div> Đang xử lý...';
            
            // Submit form bằng AJAX
            const form = document.getElementById('use-voucher-form');
            if (!form) {
                console.error('Form not found');
                return;
            }
            
            const formData = new FormData(form);
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData,
                redirect: 'follow' // Follow redirects
            })
            .then(response => {
                // Kiểm tra response - nếu thành công (200 hoặc redirect) thì hiển thị modal success
                if (response.ok || response.status === 200 || response.redirected) {
                    // Đóng modal xác nhận
                    closeModal('confirm-use-modal');
                    
                    // Mở modal thành công sau khi đóng modal xác nhận
                    setTimeout(() => {
                        openModal('success-modal');
                        
                    // Start countdown + auto reload when reaches 0
                    let countdown = 10;
                    const countdownElement = document.getElementById('countdown-number');
                    if (countdownElement) {
                        countdownElement.textContent = countdown;
                        const countdownInterval = setInterval(() => {
                            countdown--;
                            if (countdown <= 0) {
                                clearInterval(countdownInterval);
                                window.location.reload();
                                return;
                            }
                            countdownElement.textContent = countdown;
                        }, 1000);
                    } else {
                        // Fallback
                        setTimeout(() => window.location.reload(), 10000);
                    }
                    }, 400); // Đợi modal xác nhận đóng xong
                } else {
                    throw new Error('Server response not OK: ' + response.status);
                }
            })
            .catch(error => {
                console.error('Error submitting form:', error);
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = originalText;
                alert('Có lỗi xảy ra khi xác nhận sử dụng voucher. Vui lòng thử lại.');
            });
        }

        // Auto-close success modal after 3 seconds
        function autoCloseSuccessModal() {
            setTimeout(() => {
                closeModal('success-modal');
            }, 3000);
        }
    </script>
</body>
</html>
