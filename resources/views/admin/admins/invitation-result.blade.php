<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $success ? 'Chấp nhận thành công' : 'Không thể xử lý' }} - Game On Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #000814;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Background Effects */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(0, 229, 255, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.03) 0%, transparent 50%);
            animation: rotate 30s linear infinite;
        }
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .container {
            max-width: 500px;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .card {
            background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
            border: 1px solid rgba(0, 229, 255, 0.2);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, 
                transparent, 
                {{ $success ? '#22c55e' : '#ef4444' }}, 
                transparent
            );
        }

        .logo {
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        .logo-icon {
            width: 45px; height: 45px;
            background: linear-gradient(135deg, #00E5FF, #0066ff);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
        }
        .logo-text {
            font-family: 'Rajdhani', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: #00E5FF;
        }

        .icon-wrapper {
            width: 100px; height: 100px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            position: relative;
        }
        .icon-wrapper.success {
            background: rgba(34, 197, 94, 0.1);
            border: 2px solid rgba(34, 197, 94, 0.3);
            color: #22c55e;
        }
        .icon-wrapper.error {
            background: rgba(239, 68, 68, 0.1);
            border: 2px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }
        .icon-wrapper::after {
            content: '';
            position: absolute;
            inset: -10px;
            border-radius: 50%;
            border: 1px solid {{ $success ? 'rgba(34, 197, 94, 0.2)' : 'rgba(239, 68, 68, 0.2)' }};
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.5; }
        }

        h1 {
            font-family: 'Rajdhani', sans-serif;
            color: #FFFFFF;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }
        
        .message {
            color: #94a3b8;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .info-box {
            background: rgba(0, 229, 255, 0.05);
            border: 1px solid rgba(0, 229, 255, 0.2);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: left;
        }
        .info-box-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            color: #00E5FF;
            font-weight: 600;
        }
        .info-box-header i { font-size: 1.1rem; }
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        }
        .info-item:last-child { border-bottom: none; }
        .info-label { color: #64748b; font-size: 0.9rem; }
        .info-value { 
            color: #FFFFFF; 
            font-weight: 600; 
            font-family: 'Courier New', monospace;
            background: rgba(0, 0, 0, 0.3);
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .warning-box {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            text-align: left;
        }
        .warning-box i { color: #f59e0b; margin-top: 2px; }
        .warning-box span { color: #fbbf24; font-size: 0.9rem; line-height: 1.5; }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background: linear-gradient(135deg, #00E5FF, #0066ff);
            color: #FFFFFF;
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 229, 255, 0.3);
        }
        .btn-secondary {
            background: rgba(100, 116, 139, 0.2);
            color: #94a3b8;
            border: 1px solid rgba(100, 116, 139, 0.3);
        }
        .btn-secondary:hover {
            background: rgba(100, 116, 139, 0.3);
            color: #FFFFFF;
        }

        .buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        @media (max-width: 480px) {
            .card { padding: 2rem 1.5rem; }
            .logo-text { font-size: 1.5rem; }
            h1 { font-size: 1.5rem; }
            .buttons { flex-direction: column; }
            .btn { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="logo">
                <div class="logo-icon"><i class="fas fa-gamepad"></i></div>
                <span class="logo-text">Game On</span>
            </div>

            <div class="icon-wrapper {{ $success ? 'success' : 'error' }}">
                @if($success)
                    <i class="fas fa-check"></i>
                @else
                    <i class="fas fa-times"></i>
                @endif
            </div>

            <h1>{{ $success ? 'Thành công!' : 'Không thể xử lý' }}</h1>
            <p class="message">{{ $message }}</p>

            @if(isset($isNewUser) && $isNewUser && isset($tempPassword))
                <div class="info-box">
                    <div class="info-box-header">
                        <i class="fas fa-key"></i>
                        <span>Thông tin đăng nhập</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $email }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Mật khẩu</span>
                        <span class="info-value">{{ $tempPassword }}</span>
                    </div>
                </div>
                <div class="warning-box">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Vui lòng đổi mật khẩu ngay sau khi đăng nhập lần đầu để bảo mật tài khoản!</span>
                </div>
            @endif

            <div class="buttons">
                @if($success)
                    <a href="{{ route('auth.login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        Đăng nhập ngay
                    </a>
                @else
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-home"></i>
                        Về trang chủ
                    </a>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
