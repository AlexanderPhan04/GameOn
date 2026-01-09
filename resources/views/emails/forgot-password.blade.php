<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding: 30px 0;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            margin: -20px -20px 30px -20px;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .content {
            padding: 0 20px;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }

        .btn:hover {
            opacity: 0.9;
            color: white;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }

        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }

        .info-box {
            background-color: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            color: #004085;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-gamepad"></i> Game On</h1>
            <p>Yêu cầu đặt lại mật khẩu</p>
        </div>

        <div class="content">
            <h2>Xin chào {{ $user->name }}!</h2>

            <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản <strong>{{ $user->email }}</strong> tại <strong>Game On</strong>.</p>

            <div class="info-box">
                <p><strong>📧 Thông tin yêu cầu:</strong></p>
                <ul>
                    <li><strong>Email:</strong> {{ $user->email }}</li>
                    <li><strong>Thời gian:</strong> {{ now()->format('d/m/Y H:i:s') }}</li>
                    <li><strong>IP Address:</strong> {{ request()->ip() ?? 'N/A' }}</li>
                </ul>
            </div>

            <p>Để đặt lại mật khẩu, vui lòng nhấp vào nút bên dưới:</p>

            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="btn">
                    🔐 Đặt lại mật khẩu ngay
                </a>
            </div>

            <p>Hoặc bạn có thể copy và paste đường link sau vào trình duyệt:</p>
            <p style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; word-break: break-all; font-family: monospace;">
                {{ $resetUrl }}
            </p>

            <div class="warning">
                <p><strong>⚠️ Lưu ý bảo mật quan trọng:</strong></p>
                <ul>
                    <li><strong>Link này chỉ có hiệu lực trong 60 phút</strong></li>
                    <li>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này</li>
                    <li>Không chia sẻ link này với bất kỳ ai</li>
                    <li>Sau khi đặt lại mật khẩu, hãy đăng xuất khỏi tất cả thiết bị khác</li>
                </ul>
            </div>

            <h3>🛡️ Mẹo bảo mật tài khoản:</h3>
            <ul>
                <li>Sử dụng mật khẩu mạnh (ít nhất 8 ký tự, có chữ hoa, chữ thường và số)</li>
                <li>Không sử dụng mật khẩu giống với các trang web khác</li>
                <li>Kích hoạt xác thực 2 bước nếu có thể</li>
                <li>Thường xuyên thay đổi mật khẩu</li>
            </ul>

            <p>Nếu bạn gặp bất kỳ vấn đề nào trong quá trình đặt lại mật khẩu, vui lòng liên hệ với chúng tôi.</p>
        </div>

        <div class="footer">
            <p>Email này được gửi từ <strong>Game On</strong></p>
            <p>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua email: {{ config('mail.from.address') }}</p>
            <p>&copy; {{ date('Y') }} Game On. All rights reserved.</p>
        </div>
    </div>
</body>

</html>