<?php
require_once 'config.php';
require_once __DIR__ . '/includes/audit_log.php';

if (isLoggedIn()) {
    $userId = $_SESSION['user_id'] ?? null;
    $username = $_SESSION['username'] ?? 'Unknown';
    auditLog($pdo, 'logout', 'session', $userId, 'User logged out explicitly: ' . $username);
    logoutUser();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Đăng xuất - Kon Tum + E-Voucher</title>
    <link href="/assets/vendor/fontawesome/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(0,0,0,0.5)), url('https://fc.kontumplus.com/bg_voucher.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .logout-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            animation: slideInUp 0.8s ease-out;
            text-align: center;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            margin-bottom: 30px;
        }

        .logo h1 {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease-in-out infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .logout-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff;
        }

        .logout-message {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 30px;
            line-height: 1.5;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid #4ecdc4;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .success-icon {
            display: none;
            font-size: 3.5rem;
            color: #4ecdc4;
            margin-bottom: 20px;
            animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: rgba(78, 205, 196, 0.1);
            border-radius: 50%;
            width: 80px;
            height: 80px;
            line-height: 80px;
        }

        @keyframes popIn {
            0% { transform: scale(0); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .redirect-link {
            display: inline-block;
            margin-top: 25px;
            color: #fff;
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(10px);
        }

        .redirect-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }

        .redirect-link.show {
            opacity: 1;
            transform: translateY(0);
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .logout-container {
                margin: 20px;
                padding: 30px 20px;
            }

            .logo h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="logo">
            <h1>KON TUM +</h1>
        </div>
        
        <!-- Trạng thái đang đăng xuất -->
        <div id="loading-state">
            <div class="spinner"></div>
            <h2 class="logout-title">Đang đăng xuất...</h2>
            <p class="logout-message">Vui lòng chờ trong giây lát để hệ thống xử lý và lưu trạng thái bảo mật của bạn.</p>
        </div>

        <!-- Trạng thái thành công -->
        <div id="success-state" style="display: none;">
            <div style="display: flex; justify-content: center;">
                <i class="fas fa-check success-icon"></i>
            </div>
            <h2 class="logout-title">Đăng xuất thành công!</h2>
            <p class="logout-message">Phiên làm việc của bạn đã kết thúc. Hẹn gặp lại bạn lần sau.</p>
            <a href="login.php" class="redirect-link" id="redirect-link">
                <i class="fas fa-home"></i> Trở về trang đăng nhập
            </a>
        </div>
    </div>

    <script>
        // Hiệu ứng UX: Delay một chút để hiển thị hoạt ảnh đăng xuất, tạo cảm giác mượt mà
        setTimeout(() => {
            // Chuyển UI
            document.getElementById('loading-state').style.display = 'none';
            const successState = document.getElementById('success-state');
            successState.style.display = 'block';
            
            // Hiển thị icon thành công
            setTimeout(() => {
                successState.querySelector('.success-icon').style.display = 'inline-block';
            }, 50);

            // Hiển thị nút quay lại
            setTimeout(() => {
                document.getElementById('redirect-link').classList.add('show');
            }, 500);

            // Tự động chuyển hướng về trang login sau 2 giây
            setTimeout(() => {
                window.location.href = 'login.php';
            }, 2000);
            
        }, 1200);
    </script>
</body>
</html>
