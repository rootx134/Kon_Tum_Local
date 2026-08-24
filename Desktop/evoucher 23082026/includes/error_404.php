<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>E-voucher không tồn tại</title>
    <link rel="stylesheet" href="/assets/vendor/fontawesome/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animated background */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
        }
        
        .error-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 50px 40px;
            text-align: center;
            max-width: 460px;
            width: 100%;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 1;
            animation: slideUp 0.5s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .brand-logo {
            display: inline-block;
            font-size: 14px;
            font-weight: 700;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 30px;
            text-decoration: none;
        }
        
        .error-icon {
            font-size: 56px;
            margin-bottom: 20px;
            color: #f59e0b;
        }
        
        .error-title {
            font-size: 22px;
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }
        
        .error-message {
            font-size: 15px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        .button-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 24px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }
        
        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #e0e0e0;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <a href="https://e.kontumplus.com/" class="brand-logo">KON TUM + RẤT TIẾC</a>
        <div class="error-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <h1 class="error-title">E-VOUCHER KHÔNG TỒN TẠI</h1>
        <p class="error-message">
            Mã e-voucher không hợp lệ hoặc đã hết hạn.<br>
            Vui lòng nhắn tin cho Kon Tum + để biết thêm.
        </p>
        <div class="button-group">
            <a href="https://e.kontumplus.com/" class="btn btn-primary">
                <i class="fas fa-home"></i> Trang chủ
            </a>
            <a href="https://www.facebook.com/kontumplusss" target="_blank" class="btn btn-secondary">
                <i class="fab fa-facebook-messenger"></i> Nhắn cho Kon Tum +
            </a>
        </div>
    </div>
</body>
</html>
