<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảo trì hệ thống - Game On</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 30%, #16213e 70%, #0f0f23 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            overflow: hidden;
            position: relative;
        }

        /* Animated background particles */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(102, 126, 234, 0.6);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .particle:nth-child(1) { left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 1s; }
        .particle:nth-child(3) { left: 30%; animation-delay: 2s; }
        .particle:nth-child(4) { left: 40%; animation-delay: 3s; }
        .particle:nth-child(5) { left: 50%; animation-delay: 4s; }
        .particle:nth-child(6) { left: 60%; animation-delay: 5s; }
        .particle:nth-child(7) { left: 70%; animation-delay: 0.5s; }
        .particle:nth-child(8) { left: 80%; animation-delay: 1.5s; }
        .particle:nth-child(9) { left: 90%; animation-delay: 2.5s; }

        @keyframes float {
            0%, 100% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100px) scale(1); opacity: 0; }
        }

        /* Main container */
        .maintenance-container {
            text-align: center;
            z-index: 2;
            position: relative;
            max-width: 600px;
            padding: 40px 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        /* Logo */
        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 2rem;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        /* Title */
        .maintenance-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Subtitle */
        .maintenance-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        /* Status card */
        .status-card {
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.3);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
        }

        .status-icon {
            font-size: 2rem;
            color: #ffc107;
            margin-bottom: 15px;
            animation: rotate 2s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .status-text {
            font-size: 1.1rem;
            font-weight: 600;
            color: #ffc107;
            margin-bottom: 10px;
        }

        .status-description {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Info section */
        .info-section {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .info-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .info-list {
            list-style: none;
            text-align: left;
        }

        .info-list li {
            padding: 8px 0;
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-list li i {
            color: #667eea;
            width: 16px;
        }

        /* Contact section */
        .contact-section {
            background: rgba(72, 199, 116, 0.1);
            border: 1px solid rgba(72, 199, 116, 0.3);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .contact-title {
            font-size: 1rem;
            font-weight: 600;
            color: #48c774;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .contact-info {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* Footer */
        .footer {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
            margin-top: 20px;
        }

        .footer-brand {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 5px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .maintenance-container {
                margin: 20px;
                padding: 30px 15px;
            }

            .maintenance-title {
                font-size: 2rem;
            }

            .maintenance-subtitle {
                font-size: 1rem;
            }

            .logo {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }

        /* Auto refresh indicator */
        .refresh-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            padding: 10px 15px;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 3;
        }

        .refresh-indicator i {
            margin-right: 5px;
            animation: pulse 1.5s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <!-- Animated particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- Auto refresh indicator -->
    <div class="refresh-indicator">
        <i class="fas fa-sync-alt"></i>
        Tự động kiểm tra lại sau <span id="countdown">30</span>s
    </div>

    <!-- Main content -->
    <div class="maintenance-container">
        <!-- Logo -->
        <div class="logo">
            <i class="fas fa-gamepad"></i>
        </div>

        <!-- Title -->
        <h1 class="maintenance-title">Hệ thống đang bảo trì</h1>
        
        <!-- Subtitle -->
        <p class="maintenance-subtitle">
            Xin lỗi vì sự bất tiện này. Chúng tôi đang thực hiện bảo trì hệ thống để cải thiện trải nghiệm người dùng.
        </p>

        <!-- Status card -->
        <div class="status-card">
            <div class="status-icon">
                <i class="fas fa-tools"></i>
            </div>
            <div class="status-text">Đang bảo trì cơ sở dữ liệu</div>
            <div class="status-description">
                Hệ thống đang được cập nhật và tối ưu hóa. Vui lòng quay lại sau ít phút.
            </div>
        </div>

        <!-- Info section -->
        <div class="info-section">
            <div class="info-title">
                <i class="fas fa-info-circle"></i>
                Thông tin bảo trì
            </div>
            <ul class="info-list">
                <li>
                    <i class="fas fa-clock"></i>
                    <span>Thời gian bảo trì: {{ $timestamp ?? now()->format('d/m/Y H:i:s') }}</span>
                </li>
                <li>
                    <i class="fas fa-database"></i>
                    <span>Lỗi kết nối cơ sở dữ liệu</span>
                </li>
                <li>
                    <i class="fas fa-sync-alt"></i>
                    <span>Trang sẽ tự động kiểm tra lại</span>
                </li>
                <li>
                    <i class="fas fa-shield-alt"></i>
                    <span>Dữ liệu của bạn được bảo vệ an toàn</span>
                </li>
            </ul>
        </div>

        <!-- Contact section -->
        <div class="contact-section">
            <div class="contact-title">
                <i class="fas fa-headset"></i>
                Cần hỗ trợ?
            </div>
            <div class="contact-info">
                Nếu bạn cần hỗ trợ khẩn cấp, vui lòng liên hệ với chúng tôi qua email hoặc hotline. 
                Chúng tôi sẽ phản hồi trong thời gian sớm nhất có thể.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-brand">Game On</div>
            <div>Nền tảng quản lý giải đấu esports chuyên nghiệp</div>
        </div>
    </div>

    <script>
        // Auto refresh countdown
        let countdown = 30;
        const countdownElement = document.getElementById('countdown');
        
        // Function to check system status
        async function checkSystemStatus() {
            try {
                const response = await fetch('/api/check-status');
                const data = await response.json();
                
                if (data.online) {
                    // System is back online, redirect to home
                    window.location.href = '/';
                }
            } catch (error) {
                console.log('System still offline');
            }
        }
        
        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(timer);
                // Check system status before reloading
                checkSystemStatus().then(() => {
                    window.location.reload();
                });
            }
        }, 1000);

        // Check status every 10 seconds
        setInterval(checkSystemStatus, 10000);

        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to cards
            const cards = document.querySelectorAll('.status-card, .info-section, .contact-section');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.transition = 'transform 0.3s ease';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
            
            // Add click to refresh functionality
            const refreshIndicator = document.querySelector('.refresh-indicator');
            refreshIndicator.addEventListener('click', function() {
                window.location.reload();
            });
            refreshIndicator.style.cursor = 'pointer';
            refreshIndicator.title = 'Click để kiểm tra lại ngay';
        });
    </script>
</body>
</html>
