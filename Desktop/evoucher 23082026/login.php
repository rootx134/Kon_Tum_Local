<?php
require_once __DIR__ . '/auth.php'; // auth.php loads config.php + starts session
require_once __DIR__ . '/includes/rate_limiter.php';
require_once __DIR__ . '/includes/audit_log.php';

// Rate limit chỉ áp dụng cho POST (login attempts), không phải GET (load trang)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkRateLimit('login', 10, 60); // 10 lần đăng nhập / phút
}

// Redirect if already logged in
if (isLoggedIn()) {
    header('Cache-Control: no-store');
    header('Location: admin.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // SEC-5: Verify CSRF token
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($submittedToken)) {
        $error = 'Phiên làm việc không hợp lệ. Vui lòng thử lại.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $error = 'Vui lòng nhập đầy đủ thông tin.';
        } else {
            $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // SEC-4: Regenerate session ID on login (inside loginUser)
                loginUser((int) $user['id'], $username);
                auditLog($pdo, 'login', 'session', (int)$user['id'], 'Login successful');
                header('Cache-Control: no-store');
                header('Location: admin.php');
                exit();
            } else {
                auditLog($pdo, 'login_failed', 'session', null, 'Failed login for: ' . $username);
                $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Đăng nhập - Kon Tum + E-Voucher</title>
    <link href="/assets/vendor/fontawesome/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/voucher.css?v=<?= filemtime(__DIR__.'/assets/css/voucher.css') ?>">
    <script src="assets/js/theme.js?v=<?= filemtime(__DIR__.'/assets/js/theme.js') ?>"></script>
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

        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            animation: slideInUp 0.8s ease-out;
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
            text-align: center;
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

        .login-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 30px;
            color: #fff;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #fff;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4ecdc4;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 20px rgba(78, 205, 196, 0.3);
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 107, 0.4);
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .error-message {
            background: rgba(255, 107, 107, 0.2);
            border: 1px solid rgba(255, 107, 107, 0.5);
            color: #ff6b6b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: #4ecdc4;
        }


        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
                padding: 30px 20px;
            }

            .logo h1 {
                font-size: 2rem;
            }

            .login-title {
                font-size: 1.3rem;
            }
        }

        /* Loading animation */
        .loading {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Light Theme Overrides */
        [data-theme="light"] body {
            background: linear-gradient(135deg, rgba(243,244,246,0.92), rgba(229,231,235,0.85)), url('https://fc.kontumplus.com/bg_voucher.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #111827;
        }

        [data-theme="light"] .login-container {
            background: rgba(255, 255, 255, 0.9);
            border-color: rgba(0, 0, 0, 0.12);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            color: #111827;
        }

        [data-theme="light"] .login-title {
            color: #111827;
        }

        [data-theme="light"] .form-group label {
            color: #374151;
        }

        [data-theme="light"] .form-group input {
            background: rgba(0, 0, 0, 0.04);
            border-color: rgba(0, 0, 0, 0.15);
            color: #111827;
        }

        [data-theme="light"] .back-link a {
            color: #4B5563;
        }
    </style>
</head>
<body>
    <div style="position: absolute; top: 20px; right: 20px;">
        <button class="theme-toggle-btn" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: inherit; padding: 8px 16px; border-radius: 30px; cursor: pointer; backdrop-filter: blur(10px); display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; transition: all 0.3s ease;">
            <span class="theme-icon-wrapper"></span>
            <span class="theme-text">Tự động</span>
        </button>
    </div>
    <div class="login-container">
        <div class="logo">
            <h1>KON TUM +</h1>
        </div>
        
        <h2 class="login-title">Đăng nhập Quản trị</h2>
        
        <?php if ($error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" id="loginForm">
            <?php echo csrfField(); ?>
            <div class="form-group">
                <label for="username">
                    <i class="fas fa-user"></i> Tên đăng nhập
                </label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="Nhập tên đăng nhập"
                    value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                    required
                >
            </div>
            
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Mật khẩu
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Nhập mật khẩu"
                    required
                >
            </div>
            
            <button type="submit" class="login-btn">
                <span class="btn-text">Đăng nhập</span>
                <div class="loading">
                    <div class="spinner"></div>
                </div>
            </button>
        </form>
        
        <div class="back-link">
            <a href="index.php">
                <i class="fas fa-arrow-left"></i> Quay về trang chủ
            </a>
        </div>
        
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.querySelector('.login-btn');
            const btnText = document.querySelector('.btn-text');
            const loading = document.querySelector('.loading');
            
            // Show loading
            btnText.style.display = 'none';
            loading.style.display = 'block';
            btn.disabled = true;
            
            // Simulate loading time
            setTimeout(() => {
                // Form will submit normally
            }, 500);
        });

        // Add focus effects
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Auto-focus on username field
        document.getElementById('username').focus();
    </script>
</body>
</html>