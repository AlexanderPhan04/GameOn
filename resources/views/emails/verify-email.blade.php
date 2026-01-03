<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Xác nhận địa chỉ email - Game On</title>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!--[if mso]>
    <style type="text/css">
        body, table, td {font-family: Arial, sans-serif !important;}
    </style>
    <![endif]-->
</head>

<body style="margin: 0; padding: 0; background-color: #000814; font-family: 'Inter', Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    
    <!-- Background wrapper -->
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #000814; min-height: 100vh;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                
                <!-- Main Card -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 500px; background: linear-gradient(180deg, #0d1b2a 0%, #000022 100%); border-radius: 20px; border: 1px solid rgba(0, 229, 255, 0.2); box-shadow: 0 0 60px rgba(0, 229, 255, 0.1);">
                    
                    <!-- Top Accent Line -->
                    <tr>
                        <td style="height: 2px; background: linear-gradient(90deg, transparent, #00E5FF, transparent);"></td>
                    </tr>
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 40px 30px 30px; background: linear-gradient(135deg, #000022 0%, #0d1b2a 100%);">
                            
                            <!-- Icon -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" style="width: 90px; height: 90px; background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%); border-radius: 50%; border: 3px solid rgba(0, 229, 255, 0.5); box-shadow: 0 0 30px rgba(0, 229, 255, 0.3);">
                                        <span style="font-size: 40px;">✉️</span>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Title -->
                            <h1 style="font-family: 'Rajdhani', Arial, sans-serif; font-size: 28px; font-weight: 700; color: #FFFFFF; margin: 20px 0 5px; text-transform: uppercase; letter-spacing: 2px;">
                                GAME ON
                            </h1>
                            <p style="font-family: 'Rajdhani', Arial, sans-serif; font-size: 14px; color: #00E5FF; margin: 0; text-transform: uppercase; letter-spacing: 1px;">
                                Xác nhận địa chỉ email
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 30px;">
                            
                            <!-- Greeting -->
                            <h2 style="font-family: 'Rajdhani', Arial, sans-serif; font-size: 22px; font-weight: 700; color: #FFFFFF; margin: 0 0 20px;">
                                Xin chào {{ $user->name }}! 👋
                            </h2>
                            
                            <!-- Message -->
                            <p style="font-size: 14px; color: #94a3b8; line-height: 1.6; margin: 0 0 15px;">
                                Cảm ơn bạn đã đăng ký tài khoản tại <strong style="color: #00E5FF;">Game On</strong>. Để hoàn tất quá trình đăng ký và bắt đầu sử dụng dịch vụ, bạn cần xác nhận địa chỉ email này.
                            </p>
                            
                            <p style="font-size: 14px; color: #94a3b8; line-height: 1.6; margin: 0 0 25px;">
                                Vui lòng nhấp vào nút bên dưới để xác nhận email:
                            </p>
                            
                            <!-- CTA Button -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" style="padding: 10px 0 25px;">
                                        <a href="{{ $verificationUrl }}" style="display: inline-block; padding: 16px 40px; background: linear-gradient(135deg, #000055 0%, #00E5FF 100%); color: #FFFFFF; text-decoration: none; border-radius: 12px; font-family: 'Rajdhani', Arial, sans-serif; font-weight: 700; font-size: 15px; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 0 20px rgba(0, 229, 255, 0.4);">
                                            ✅ XÁC NHẬN EMAIL
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Alternative Link -->
                            <p style="font-size: 13px; color: #94a3b8; margin: 0 0 10px;">
                                Hoặc copy và paste đường link sau vào trình duyệt:
                            </p>
                            <div style="background: rgba(0, 229, 255, 0.05); border: 1px solid rgba(0, 229, 255, 0.2); border-radius: 8px; padding: 12px; margin-bottom: 25px;">
                                <a href="{{ $verificationUrl }}" style="font-family: 'Monaco', 'Consolas', monospace; font-size: 11px; color: #00E5FF; word-break: break-all; text-decoration: none;">
                                    {{ $verificationUrl }}
                                </a>
                            </div>
                            
                            <!-- Warning Box -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background: linear-gradient(135deg, rgba(234, 179, 8, 0.1) 0%, rgba(249, 115, 22, 0.1) 100%); border-left: 3px solid #eab308; border-radius: 10px;">
                                <tr>
                                    <td style="padding: 15px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="vertical-align: top; padding-right: 12px;">
                                                    <span style="font-size: 20px;">⏰</span>
                                                </td>
                                                <td>
                                                    <p style="font-family: 'Rajdhani', Arial, sans-serif; font-weight: 700; font-size: 13px; color: #fde047; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 5px;">
                                                        LƯU Ý QUAN TRỌNG
                                                    </p>
                                                    <ul style="font-size: 12px; color: rgba(253, 224, 71, 0.8); margin: 0; padding-left: 16px; line-height: 1.6;">
                                                        <li>Link xác nhận chỉ có hiệu lực trong <strong>24 giờ</strong></li>
                                                        <li>Nếu bạn không yêu cầu tạo tài khoản, vui lòng bỏ qua email này</li>
                                                        <li>Không chia sẻ link này với bất kỳ ai</li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Features Section -->
                            <div style="margin-top: 25px; padding: 20px; background: rgba(0, 0, 34, 0.5); border-radius: 12px; border: 1px solid rgba(26, 35, 126, 0.3);">
                                <p style="font-family: 'Rajdhani', Arial, sans-serif; font-weight: 700; font-size: 14px; color: #FFFFFF; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 15px;">
                                    🎮 Sau khi xác nhận, bạn có thể:
                                </p>
                                
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td style="padding: 8px 0;">
                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                                <tr>
                                                    <td style="width: 24px; height: 24px; background: rgba(0, 229, 255, 0.1); border-radius: 6px; text-align: center; vertical-align: middle;">
                                                        <span style="color: #00E5FF; font-size: 12px;">🏆</span>
                                                    </td>
                                                    <td style="padding-left: 10px; font-size: 13px; color: #94a3b8;">Tham gia các giải đấu esports</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0;">
                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                                <tr>
                                                    <td style="width: 24px; height: 24px; background: rgba(0, 229, 255, 0.1); border-radius: 6px; text-align: center; vertical-align: middle;">
                                                        <span style="color: #00E5FF; font-size: 12px;">👥</span>
                                                    </td>
                                                    <td style="padding-left: 10px; font-size: 13px; color: #94a3b8;">Tạo và quản lý đội tuyển</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0;">
                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                                <tr>
                                                    <td style="width: 24px; height: 24px; background: rgba(0, 229, 255, 0.1); border-radius: 6px; text-align: center; vertical-align: middle;">
                                                        <span style="color: #00E5FF; font-size: 12px;">📊</span>
                                                    </td>
                                                    <td style="padding-left: 10px; font-size: 13px; color: #94a3b8;">Theo dõi thống kê và thành tích</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0;">
                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                                <tr>
                                                    <td style="width: 24px; height: 24px; background: rgba(0, 229, 255, 0.1); border-radius: 6px; text-align: center; vertical-align: middle;">
                                                        <span style="color: #00E5FF; font-size: 12px;">🌐</span>
                                                    </td>
                                                    <td style="padding-left: 10px; font-size: 13px; color: #94a3b8;">Kết nối với cộng đồng game thủ</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 30px; border-top: 1px solid rgba(0, 229, 255, 0.1); text-align: center;">
                            <p style="font-size: 12px; color: #94a3b8; margin: 0 0 10px;">
                                Email này được gửi từ <strong style="color: #00E5FF;">Game On</strong>
                            </p>
                            <p style="font-size: 11px; color: #64748b; margin: 0 0 10px;">
                                Nếu bạn có thắc mắc, vui lòng liên hệ: 
                                <a href="mailto:{{ config('mail.from.address') }}" style="color: #00E5FF; text-decoration: none;">{{ config('mail.from.address') }}</a>
                            </p>
                            <p style="font-family: 'Rajdhani', Arial, sans-serif; font-size: 11px; color: #64748b; margin: 0;">
                                © {{ date('Y') }} <span style="color: #00E5FF; font-weight: 700;">GAME ON</span> — Nền tảng Esports hàng đầu Việt Nam
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Bottom Accent Line -->
                    <tr>
                        <td style="height: 2px; background: linear-gradient(90deg, transparent, #00E5FF, transparent);"></td>
                    </tr>
                    
                </table>
                
            </td>
        </tr>
    </table>
    
</body>

</html>
