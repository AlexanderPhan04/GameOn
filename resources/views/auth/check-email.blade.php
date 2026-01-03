<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiểm tra email - Game On</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --void: #000814;
            --midnight: #000022;
            --surface: #0d1b2a;
            --deep-navy: #000055;
            --neon: #00E5FF;
            --border: #1a237e;
            --text-main: #FFFFFF;
            --text-muted: #94a3b8;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--void);
            min-height: 100vh;
            color: var(--text-main);
            position: relative;
            overflow-x: hidden;
        }
        
        .font-display {
            font-family: 'Rajdhani', sans-serif;
        }
        
        /* Animated Background */
        .bg-grid {
            position: fixed;
            inset: 0;
            background-image: 
                linear-gradient(rgba(0, 229, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 229, 255, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }
        
        .bg-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            animation: pulse 4s ease-in-out infinite;
        }
        
        .bg-orb-1 {
            top: -100px;
            left: -100px;
            width: 400px;
            height: 400px;
            background: rgba(0, 229, 255, 0.15);
        }
        
        .bg-orb-2 {
            bottom: -100px;
            right: -100px;
            width: 500px;
            height: 500px;
            background: rgba(0, 0, 85, 0.3);
            animation-delay: 2s;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.1); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(0, 229, 255, 0.3), 0 0 40px rgba(0, 229, 255, 0.1); }
            50% { box-shadow: 0 0 30px rgba(0, 229, 255, 0.5), 0 0 60px rgba(0, 229, 255, 0.2); }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Main Container */
        .main-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            z-index: 10;
        }
        
        .card-wrapper {
            width: 100%;
            max-width: 480px;
        }
        
        /* Card */
        .card {
            width: 100%;
            background: linear-gradient(180deg, rgba(13, 27, 42, 0.95) 0%, rgba(0, 0, 34, 0.95) 100%);
            border-radius: 20px;
            border: 1px solid rgba(0, 229, 255, 0.2);
            overflow: hidden;
            box-shadow: 
                0 0 60px rgba(0, 229, 255, 0.1),
                0 25px 50px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.6s ease-out;
            position: relative;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--neon), transparent);
        }
        
        .card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--neon), transparent);
        }
        
        /* Header */
        .card-header {
            background: linear-gradient(135deg, var(--midnight) 0%, var(--surface) 100%);
            padding: 1.5rem 1.5rem;
            text-align: center;
            position: relative;
        }
        
        .icon-wrapper {
            display: inline-block;
            position: relative;
            margin-bottom: 1rem;
            animation: float 3s ease-in-out infinite;
        }
        
        .icon-glow {
            position: absolute;
            inset: -15px;
            background: var(--neon);
            border-radius: 50%;
            filter: blur(30px);
            opacity: 0.3;
            animation: pulse 2s ease-in-out infinite;
        }
        
        .icon-circle {
            position: relative;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--surface) 0%, var(--midnight) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid rgba(0, 229, 255, 0.5);
            box-shadow: 
                0 0 30px rgba(0, 229, 255, 0.3),
                inset 0 0 30px rgba(0, 229, 255, 0.1);
        }
        
        .icon-circle i {
            font-size: 2.2rem;
            color: var(--neon);
            filter: drop-shadow(0 0 10px rgba(0, 229, 255, 0.8));
        }
        
        .title {
            font-family: 'Rajdhani', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 20px rgba(0, 229, 255, 0.3);
        }
        
        .title span {
            color: var(--neon);
            display: block;
        }
        
        .success-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(0, 229, 255, 0.1);
            border: 1px solid rgba(0, 229, 255, 0.4);
            border-radius: 50px;
            animation: glow 2s ease-in-out infinite;
        }
        
        .success-badge i {
            color: var(--neon);
            font-size: 0.9rem;
        }
        
        .success-badge span {
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 0.8rem;
            color: var(--neon);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Content */
        .card-content {
            padding: 1.25rem;
        }
        
        /* Info Box */
        .info-box {
            background: rgba(0, 229, 255, 0.05);
            border-left: 3px solid var(--neon);
            border-radius: 10px;
            padding: 0.875rem;
            margin-bottom: 1rem;
        }
        
        .info-box p {
            color: var(--text-muted);
            line-height: 1.5;
            font-size: 0.8rem;
        }
        
        .info-box p:first-child {
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }
        
        /* Steps Section */
        .steps-section {
            background: rgba(0, 0, 34, 0.5);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(26, 35, 126, 0.3);
        }
        
        .steps-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        }
        
        .steps-header-icon {
            width: 36px;
            height: 36px;
            background: rgba(0, 229, 255, 0.1);
            border: 1px solid rgba(0, 229, 255, 0.3);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .steps-header-icon i {
            font-size: 1rem;
            color: var(--neon);
        }
        
        .steps-header h2 {
            font-family: 'Rajdhani', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-main);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Step Items */
        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.625rem;
            background: rgba(13, 27, 42, 0.5);
            border-radius: 8px;
            margin-bottom: 0.5rem;
            border: 1px solid rgba(26, 35, 126, 0.2);
            transition: all 0.3s ease;
            animation: slideUp 0.5s ease-out backwards;
        }
        
        .step-item:nth-child(1) { animation-delay: 0.1s; }
        .step-item:nth-child(2) { animation-delay: 0.2s; }
        .step-item:nth-child(3) { animation-delay: 0.3s; }
        .step-item:nth-child(4) { animation-delay: 0.4s; }
        
        .step-item:hover {
            border-color: rgba(0, 229, 255, 0.4);
            background: rgba(13, 27, 42, 0.8);
            transform: translateX(5px);
        }
        
        .step-number {
            flex-shrink: 0;
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, rgba(0, 229, 255, 0.2) 0%, rgba(0, 229, 255, 0.05) 100%);
            border: 1px solid rgba(0, 229, 255, 0.4);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--neon);
            transition: all 0.3s ease;
        }
        
        .step-item:hover .step-number {
            box-shadow: 0 0 15px rgba(0, 229, 255, 0.4);
            transform: scale(1.05);
        }
        
        .step-content {
            flex: 1;
            padding-top: 0.25rem;
        }
        
        .step-content p {
            color: var(--text-muted);
            font-size: 0.8rem;
        }
        
        .step-content strong {
            color: var(--text-main);
        }
        
        .email-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            background: rgba(0, 229, 255, 0.1);
            border: 1px solid rgba(0, 229, 255, 0.3);
            border-radius: 6px;
            margin-top: 0.25rem;
        }
        
        .email-badge i {
            color: var(--neon);
            font-size: 0.7rem;
        }
        
        .email-badge span {
            font-family: 'Monaco', 'Consolas', monospace;
            font-size: 0.7rem;
            color: var(--neon);
        }
        
        /* Warning Box */
        .warning-box {
            background: linear-gradient(135deg, rgba(234, 179, 8, 0.1) 0%, rgba(249, 115, 22, 0.1) 100%);
            border-left: 3px solid #eab308;
            border-radius: 10px;
            padding: 0.75rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .warning-icon {
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            background: rgba(234, 179, 8, 0.2);
            border: 1px solid rgba(234, 179, 8, 0.4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .warning-icon i {
            font-size: 1rem;
            color: #eab308;
        }
        
        .warning-content h4 {
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            color: #fde047;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.125rem;
        }
        
        .warning-content p {
            color: rgba(253, 224, 71, 0.7);
            font-size: 0.75rem;
        }
        
        /* Buttons */
        .btn-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.875rem 1.5rem;
            background: linear-gradient(135deg, var(--deep-navy) 0%, var(--neon) 100%);
            border: none;
            border-radius: 10px;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            margin-bottom: 0.75rem;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 40px rgba(0, 229, 255, 0.5);
        }
        
        .btn-primary:hover::before {
            transform: translateX(100%);
        }
        
        .btn-secondary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.75rem 1.5rem;
            background: rgba(13, 27, 42, 0.8);
            border: 1px solid rgba(26, 35, 126, 0.4);
            border-radius: 10px;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            border-color: rgba(0, 229, 255, 0.5);
            color: var(--text-main);
            background: rgba(13, 27, 42, 1);
        }
        
        .btn-secondary:hover i {
            transform: rotate(15deg);
        }
        
        .btn-secondary i {
            transition: transform 0.3s ease;
        }
        
        /* Footer */
        .card-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(0, 229, 255, 0.1);
            text-align: center;
        }
        
        .card-footer p {
            color: var(--text-muted);
            font-size: 0.75rem;
        }
        
        .card-footer a {
            color: var(--neon);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .card-footer a:hover {
            text-decoration: underline;
            text-shadow: 0 0 10px rgba(0, 229, 255, 0.5);
        }
        
        /* Page Footer */
        .page-footer {
            text-align: center;
            padding: 1rem;
            color: var(--text-muted);
            font-size: 0.75rem;
        }
        
        .page-footer span {
            color: var(--neon);
            font-weight: 700;
            font-family: 'Rajdhani', sans-serif;
        }
        
        /* Button states */
        .btn-loading {
            background: rgba(13, 27, 42, 0.8) !important;
            cursor: not-allowed !important;
        }
        
        .btn-success {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.3) 0%, rgba(34, 197, 94, 0.1) 100%) !important;
            border-color: rgba(34, 197, 94, 0.5) !important;
            color: #4ade80 !important;
        }
        
        .btn-error {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.3) 0%, rgba(239, 68, 68, 0.1) 100%) !important;
            border-color: rgba(239, 68, 68, 0.5) !important;
            color: #f87171 !important;
        }
        
        /* Responsive */
        @media (max-width: 640px) {
            .main-container {
                padding: 1rem;
            }
            
            .card-header {
                padding: 2rem 1.5rem;
            }
            
            .title {
                font-size: 2rem;
            }
            
            .icon-circle {
                width: 100px;
                height: 100px;
            }
            
            .icon-circle i {
                font-size: 2.5rem;
            }
            
            .card-content {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Background Effects -->
    <div class="bg-grid"></div>
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    
    <!-- Main Content -->
    <div class="main-container">
        <div class="card-wrapper">
            <div class="card">
            <!-- Header -->
            <header class="card-header">
                <div class="icon-wrapper">
                    <div class="icon-glow"></div>
                    <div class="icon-circle">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                </div>
                
                <h1 class="title">
                    Kiểm tra
                    <span>Email</span>
                </h1>
                
                <div class="success-badge">
                    <i class="fas fa-check-circle"></i>
                    <span>Đăng ký thành công!</span>
                </div>
            </header>
            
            <!-- Content -->
            <div class="card-content">
                <!-- Info Message -->
                <div class="info-box">
                    <p><i class="fas fa-paper-plane" style="color: var(--neon); margin-right: 8px;"></i>Chúng tôi đã gửi một email xác nhận đến địa chỉ email của bạn.</p>
                    <p>Vui lòng kiểm tra hộp thư (bao gồm cả thư mục spam) và nhấp vào link xác nhận để kích hoạt tài khoản.</p>
                </div>
                
                <!-- Steps -->
                <div class="steps-section">
                    <div class="steps-header">
                        <div class="steps-header-icon">
                            <i class="fas fa-list-check"></i>
                        </div>
                        <h2>Hướng dẫn</h2>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-number">01</div>
                        <div class="step-content">
                            <p>Kiểm tra hộp thư email</p>
                            <div class="email-badge">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $email ?? 'your@email.com' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-number">02</div>
                        <div class="step-content">
                            <p>Tìm email từ <strong>"Game On"</strong></p>
                        </div>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-number">03</div>
                        <div class="step-content">
                            <p>Nhấp vào nút <strong>"Xác nhận địa chỉ email"</strong></p>
                        </div>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-number">04</div>
                        <div class="step-content">
                            <p>Quay lại và đăng nhập</p>
                        </div>
                    </div>
                </div>
                
                <!-- Warning -->
                <div class="warning-box">
                    <div class="warning-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="warning-content">
                        <h4>Thời hạn: 24 giờ</h4>
                        <p>Link xác nhận sẽ hết hiệu lực sau 24 giờ. Vui lòng xác nhận email trước thời hạn.</p>
                    </div>
                </div>
                
                <!-- Buttons -->
                <a href="{{ route('auth.login') }}" class="btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Đến trang đăng nhập</span>
                </a>
                
                <button type="button" id="resendBtn" class="btn-secondary">
                    <i class="fas fa-paper-plane"></i>
                    <span>Gửi lại email xác nhận</span>
                </button>
            </div>
            
            <!-- Footer -->
            <div class="card-footer">
                <p>
                    <i class="fas fa-question-circle" style="color: var(--neon); margin-right: 6px;"></i>
                    Gặp vấn đề? <a href="mailto:{{ config('mail.from.address') }}">Liên hệ hỗ trợ</a>
                </p>
            </div>
        </div>
        </div>
        
        <!-- Page Footer -->
        <div class="page-footer">
            <p>© {{ date('Y') }} <span>GAME ON</span> — Nền tảng Esports hàng đầu Việt Nam</p>
        </div>
    </div>
    
    <script>
        document.getElementById('resendBtn')?.addEventListener('click', function() {
            const email = '{{ $email ?? "" }}';
            if (!email) {
                alert('Email không hợp lệ');
                return;
            }

            const btn = this;
            const originalHTML = btn.innerHTML;
            btn.disabled = true;
            btn.classList.add('btn-loading');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Đang gửi...</span>';

            fetch('{{ route("auth.resend.verification") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                btn.classList.remove('btn-loading');
                if (data.success) {
                    btn.classList.add('btn-success');
                    btn.innerHTML = '<i class="fas fa-check-circle"></i><span>Đã gửi thành công!</span>';
                } else {
                    btn.classList.add('btn-error');
                    btn.innerHTML = '<i class="fas fa-times-circle"></i><span>Gửi thất bại</span>';
                }

                setTimeout(() => {
                    btn.disabled = false;
                    btn.classList.remove('btn-success', 'btn-error');
                    btn.innerHTML = originalHTML;
                }, 3000);
            })
            .catch(() => {
                btn.classList.remove('btn-loading');
                btn.classList.add('btn-error');
                btn.innerHTML = '<i class="fas fa-exclamation-triangle"></i><span>Lỗi mạng</span>';

                setTimeout(() => {
                    btn.disabled = false;
                    btn.classList.remove('btn-error');
                    btn.innerHTML = originalHTML;
                }, 3000);
            });
        });
    </script>
</body>

</html>
