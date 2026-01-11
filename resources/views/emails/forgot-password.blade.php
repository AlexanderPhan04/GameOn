<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu - Game On</title>
    <!--[if mso]>
    <style type="text/css">
        body, table, td {font-family: Arial, Helvetica, sans-serif !important;}
    </style>
    <![endif]-->
    <style>
        /* Reset styles */
        body {
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        
        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        
        img {
            border: 0;
            line-height: 100%;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }
    </style>
</head>

<body style="margin: 0; padding: 0; background-color: #000814; font-family: 'Segoe UI', Arial, sans-serif;">
    <!-- Main Container -->
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color: #000814;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <!-- Email Card -->
                <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="max-width: 600px; background-color: #0d1b2a; border-radius: 20px; border: 1px solid rgba(0, 229, 255, 0.2); box-shadow: 0 8px 40px rgba(0, 0, 0, 0.6);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #000022 0%, #000055 50%, #000022 100%); padding: 40px 30px; text-align: center; border-radius: 20px 20px 0 0;">
                            <!-- Logo Icon -->
                            <table role="presentation" cellpadding="0" cellspacing="0" align="center">
                                <tr>
                                    <td style="width: 70px; height: 70px; background: linear-gradient(135deg, #000055 0%, #000077 100%); border: 2px solid rgba(0, 229, 255, 0.4); border-radius: 50%; text-align: center; vertical-align: middle;">
                                        <span style="font-size: 28px; color: #00E5FF;">🔐</span>
                                    </td>
                                </tr>
                            </table>
                            <h1 style="margin: 20px 0 5px; font-size: 28px; font-weight: 700; color: #00E5FF; text-transform: uppercase; letter-spacing: 2px;">Game On</h1>
                            <p style="margin: 0; font-size: 14px; color: #94a3b8;">Yêu cầu đặt lại mật khẩu</p>
                        </td>
                    </tr>
                    
                    <!-- Divider Line -->
                    <tr>
                        <td style="height: 2px; background: linear-gradient(90deg, transparent, rgba(0, 229, 255, 0.5), transparent);"></td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 35px;">
                            <!-- Greeting -->
                            <h2 style="margin: 0 0 20px; font-size: 22px; font-weight: 600; color: #FFFFFF;">Xin chào {{ $user->name }}!</h2>
                            
                            <p style="margin: 0 0 25px; font-size: 15px; line-height: 1.7; color: #94a3b8;">
                                Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản <strong style="color: #00E5FF;">{{ $user->email }}</strong> tại <strong style="color: #FFFFFF;">Game On</strong>.
                            </p>
                            
                            <!-- Info Box -->
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color: rgba(0, 229, 255, 0.05); border: 1px solid rgba(0, 229, 255, 0.2); border-radius: 12px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 12px; font-size: 14px; font-weight: 600; color: #00E5FF;">
                                            📧 Thông tin yêu cầu:
                                        </p>
                                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="padding: 5px 0; font-size: 13px; color: #94a3b8;">
                                                    <strong style="color: #FFFFFF;">Email:</strong> {{ $user->email }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0; font-size: 13px; color: #94a3b8;">
                                                    <strong style="color: #FFFFFF;">Thời gian:</strong> {{ now()->format('d/m/Y H:i:s') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0; font-size: 13px; color: #94a3b8;">
                                                    <strong style="color: #FFFFFF;">IP Address:</strong> {{ request()->ip() ?? 'N/A' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 0 0 25px; font-size: 15px; line-height: 1.7; color: #94a3b8;">
                                Để đặt lại mật khẩu, vui lòng nhấp vào nút bên dưới:
                            </p>
                            
                            <!-- CTA Button -->
                            <table role="presentation" cellpadding="0" cellspacing="0" align="center" style="margin: 30px 0;">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #000055 0%, #000077 100%); border: 1px solid rgba(0, 229, 255, 0.4); border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 229, 255, 0.3);">
                                        <a href="{{ $resetUrl }}" style="display: inline-block; padding: 16px 40px; font-size: 14px; font-weight: 700; color: #FFFFFF; text-decoration: none; text-transform: uppercase; letter-spacing: 1px;">
                                            🔐 Đặt lại mật khẩu ngay
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Alternative Link -->
                            <p style="margin: 25px 0 10px; font-size: 13px; color: #94a3b8;">
                                Hoặc bạn có thể copy và paste đường link sau vào trình duyệt:
                            </p>
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="background-color: rgba(0, 229, 255, 0.05); padding: 15px; border-radius: 8px; border: 1px solid rgba(0, 229, 255, 0.1);">
                                        <p style="margin: 0; font-size: 12px; color: #00E5FF; word-break: break-all; font-family: 'Courier New', monospace;">
                                            {{ $resetUrl }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Warning Box -->
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color: rgba(251, 191, 36, 0.1); border: 1px solid rgba(251, 191, 36, 0.3); border-radius: 12px; margin-top: 30px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 12px; font-size: 14px; font-weight: 600; color: #fbbf24;">
                                            ⚠️ Lưu ý bảo mật quan trọng:
                                        </p>
                                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="padding: 4px 0; font-size: 13px; color: #94a3b8;">
                                                    • <strong style="color: #fbbf24;">Link này chỉ có hiệu lực trong 60 phút</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0; font-size: 13px; color: #94a3b8;">
                                                    • Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0; font-size: 13px; color: #94a3b8;">
                                                    • Không chia sẻ link này với bất kỳ ai
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0; font-size: 13px; color: #94a3b8;">
                                                    • Sau khi đặt lại mật khẩu, hãy đăng xuất khỏi tất cả thiết bị khác
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Security Tips Box -->
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; margin-top: 20px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 12px; font-size: 14px; font-weight: 600; color: #10b981;">
                                            🛡️ Mẹo bảo mật tài khoản:
                                        </p>
                                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="padding: 4px 0; font-size: 13px; color: #94a3b8;">
                                                    • Sử dụng mật khẩu mạnh (ít nhất 8 ký tự, có chữ hoa, chữ thường và số)
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0; font-size: 13px; color: #94a3b8;">
                                                    • Không sử dụng mật khẩu giống với các trang web khác
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0; font-size: 13px; color: #94a3b8;">
                                                    • Kích hoạt xác thực 2 bước nếu có thể
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0; font-size: 13px; color: #94a3b8;">
                                                    • Thường xuyên thay đổi mật khẩu
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 25px 0 0; font-size: 14px; line-height: 1.7; color: #94a3b8;">
                                Nếu bạn gặp bất kỳ vấn đề nào trong quá trình đặt lại mật khẩu, vui lòng liên hệ với chúng tôi.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer Divider -->
                    <tr>
                        <td style="height: 1px; background: linear-gradient(90deg, transparent, rgba(0, 229, 255, 0.3), transparent);"></td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px; text-align: center;">
                            <p style="margin: 0 0 10px; font-size: 14px; color: #FFFFFF;">
                                Email này được gửi từ <strong style="color: #00E5FF;">Game On</strong>
                            </p>
                            <p style="margin: 0 0 15px; font-size: 12px; color: #64748b;">
                                Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua email: {{ config('mail.from.address') }}
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #475569;">
                                © {{ date('Y') }} Game On. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>