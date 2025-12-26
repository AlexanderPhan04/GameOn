<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận địa chỉ email</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }

        .btn:hover {
            opacity: 0.9;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-gamepad"></i> Esport Manager</h1>
            <p>Xác nhận địa chỉ email của bạn</p>
        </div>

        <div class="content">
            <h2>Xin chào {{ $user->name }}!</h2>

            <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>Esport Manager</strong>. Để hoàn tất quá trình đăng ký và bắt đầu sử dụng dịch vụ, bạn cần xác nhận địa chỉ email này.</p>

            <p>Vui lòng nhấp vào nút bên dưới để xác nhận email:</p>

            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="btn">
                    ✅ Xác nhận địa chỉ email
                </a>
            </div>

            <p>Hoặc bạn có thể copy và paste đường link sau vào trình duyệt:</p>
            <p style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; word-break: break-all; font-family: monospace;">
                {{ $verificationUrl }}
            </p>

            <div class="warning">
                <p><strong>⚠️ Lưu ý quan trọng:</strong></p>
                <ul>
                    <li>Link xác nhận này chỉ có hiệu lực trong 24 giờ</li>
                    <li>Nếu bạn không yêu cầu tạo tài khoản, vui lòng bỏ qua email này</li>
                    <li>Không chia sẻ link này với bất kỳ ai</li>
                </ul>
            </div>

            <h3>🎮 Những gì bạn có thể làm sau khi xác nhận:</h3>
            <ul>
                <li>Tham gia các giải đấu esports</li>
                <li>Tạo và quản lý đội tuyển của bạn</li>
                <li>Theo dõi thống kê và thành tích</li>
                <li>Kết nối với cộng đồng game thủ</li>
            </ul>
        </div>

        <div class="footer">
            <p>Email này được gửi từ <strong>Esport Manager</strong></p>
            <p>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua email: {{ config('mail.from.address') }}</p>
            <p>&copy; {{ date('Y') }} Esport Manager. All rights reserved.</p>
        </div>
    </div>
</body>

</html>