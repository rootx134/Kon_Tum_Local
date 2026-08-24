<?php
// Automatically route short URLs (like /PT198G277) to e.php
// This acts as a front-controller fallback for Nginx/FlyEnv/php-S
if (isset($_SERVER['REQUEST_URI'])) {
    $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = trim($request_uri, '/');
    if (preg_match('/^[A-Z0-9]{5,20}$/i', $path)) {
        $_GET['id'] = $path;
        require __DIR__ . '/e.php';
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>E-Voucher Kon Tum + | Tri ân Fan cứng</title>
    <meta name="description" content="Hệ thống E-Voucher Kon Tum + trực tuyến chuyên cung cấp ưu đãi đặc biệt để tri ân fan cứng, với nhiều voucher hấp dẫn từ các nhà tài trợ uy tín.">
    <meta name="keywords" content="e-voucher, kon tum, kon tum plus, voucher, quà tặng, fan cứng, mã giảm giá, ưu đãi">
    <meta name="author" content="Kon Tum +">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://e.kontumplus.com/">
    <meta property="og:title" content="E-Voucher Kon Tum + | Tri ân Fan cứng">
    <meta property="og:description" content="Tham gia tương tác để nhận huy hiệu fan cứng và trải nghiệm E-Voucher Kon Tum + với hàng loạt ưu đãi vô cùng hấp dẫn mỗi tháng.">
    <meta property="og:image" content="https://fc.kontumplus.com/bg_voucher.jpeg">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://e.kontumplus.com/">
    <meta property="twitter:title" content="E-Voucher Kon Tum + | Tri ân Fan cứng">
    <meta property="twitter:description" content="Tham gia tương tác để nhận huy hiệu fan cứng và trải nghiệm E-Voucher Kon Tum + với ưu đãi vô cùng hấp dẫn mỗi tháng.">
    <meta property="twitter:image" content="https://fc.kontumplus.com/bg_voucher.jpeg">
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
            color: #fff;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        .header {
            padding: 20px 0;
            position: relative;
            z-index: 10;
        }

        .logo {
            font-size: 2.5rem;
            font-weight: 800;
            text-align: center;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease-in-out infinite;
            text-shadow: 0 0 30px rgba(255, 255, 255, 0.3);
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Main Content */
        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 200px);
            text-align: center;
            position: relative;
            z-index: 5;
        }

        .hero-section {
            max-width: 800px;
            margin-bottom: 60px;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
            animation: fadeInUp 1s ease-out;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 30px;
            opacity: 0.9;
            line-height: 1.6;
            animation: fadeInUp 1s ease-out 0.3s both;
        }

        .hero-description {
            font-size: 1.1rem;
            font-weight: 400;
            margin-bottom: 40px;
            opacity: 0.8;
            line-height: 1.8;
            animation: fadeInUp 1s ease-out 0.6s both;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease-out 0.9s both;
        }

        .btn {
            padding: 18px 40px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255, 107, 107, 0.6);
        }

        .btn-secondary {
            background: linear-gradient(45deg, #4ecdc4, #44a08d);
            color: white;
            box-shadow: 0 8px 25px rgba(78, 205, 196, 0.4);
        }

        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(78, 205, 196, 0.6);
        }

        /* Features Section */
        .features {
            margin-top: 80px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            animation: fadeInUp 1s ease-out 1.2s both;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .feature-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .feature-description {
            font-size: 1rem;
            opacity: 0.8;
            line-height: 1.6;
        }

        /* Footer */
        .footer {
            margin-top: 80px;
            padding: 40px 0;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-text {
            font-size: 1rem;
            opacity: 0.7;
            margin-bottom: 20px;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .social-link:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
        }

        /* Animations */
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

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }

            .features {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .logo {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .hero-description {
                font-size: 0.95rem;
            }

            .btn {
                padding: 15px 30px;
                font-size: 1rem;
            }
        }

        /* Loading Animation */
        .loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .loading.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loading" id="loading">
        <div class="spinner"></div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="logo floating">KON TUM +</div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="hero-section">
                <h1 class="hero-title">E-VOUCHER</h1>
                <h2 class="hero-subtitle">Tri ân Fan cứng Kon Tum +</h2>
                <p class="hero-description">
                    Đây là sản phẩm đặc biệt của Kon Tum + để tri ân những fan cứng đã theo dõi và ủng hộ fanpage Kon Tum + suốt thời gian qua. 
                    Hãy cùng trải nghiệm những ưu đãi tuyệt vời từ các đối tác của chúng tôi!
                </p>
            </div>

            <div class="action-buttons">
                <a href="https://www.facebook.com/kontumplusss" target="_blank" class="btn btn-primary">
                    <i class="fab fa-facebook-f"></i>
                    Theo dõi Fanpage
                </a>
                <a href="login.php" class="btn btn-secondary">
                    <i class="fas fa-cog"></i>
                    Đăng nhập
                </a>
            </div>

            <div class="features">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-gift"></i>
                    </div>
                    <h3 class="feature-title">Ưu đãi độc quyền</h3>
                    <p class="feature-description">
                        Những voucher đặc biệt chỉ dành riêng cho fan cứng của Kon Tum +
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="feature-title">Sử dụng dễ dàng</h3>
                    <p class="feature-description">
                        Chỉ cần quét mã QR hoặc bấm xác nhận để sử dụng voucher ngay lập tức
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="feature-title">Tri ân chân thành</h3>
                    <p class="feature-description">
                        Lời cảm ơn chân thành từ Kon Tum + đến những người bạn đồng hành
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="footer-text">
                © 2024 Kon Tum +. Tất cả quyền được bảo lưu.
            </p>
            <div class="social-links">
                <a href="https://www.facebook.com/kontumplusss" target="_blank" class="social-link">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://e.kontumplus.com/" class="social-link">
                    <i class="fas fa-home"></i>
                </a>
            </div>
        </div>
    </footer>

    <script>
        // Loading screen
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loading').classList.add('hidden');
            }, 1000);
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add click effect to buttons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                let ripple = document.createElement('span');
                let rect = this.getBoundingClientRect();
                let size = Math.max(rect.width, rect.height);
                let x = e.clientX - rect.left - size / 2;
                let y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add ripple effect CSS
        const style = document.createElement('style');
        style.textContent = `
            .btn {
                position: relative;
                overflow: hidden;
            }
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                pointer-events: none;
            }
            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

    </script>
</body>
</html>
