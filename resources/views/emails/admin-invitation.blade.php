<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lời mời Admin</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #0a0a0f;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 229, 255, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #00E5FF 0%, #006666 100%);
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #fff;
            margin: 0;
            font-size: 28px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content {
            padding: 40px 30px;
            color: #e0e0e0;
        }
        .greeting {
            font-size: 20px;
            color: #00E5FF;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            margin-bottom: 25px;
            color: #b0b0b0;
        }
        .inviter-box {
            background: rgba(0, 229, 255, 0.1);
            border-left: 4px solid #00E5FF;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .inviter-box strong {
            color: #00E5FF;
        }
        .permissions-section {
            margin: 30px 0;
        }
        .permissions-title {
            color: #00E5FF;
            font-size: 18px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .permission-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .permission-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #00E5FF, #006666);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
        }
        .permission-info h4 {
            margin: 0 0 4px 0;
            color: #fff;
            font-size: 14px;
        }
        .permission-info p {
            margin: 0;
            color: #888;
            font-size: 12px;
        }
        .btn-container {
            text-align: center;
            margin: 35px 0;
        }
        .btn {
            display: inline-block;
            padding: 16px 40px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 5px;
            transition: all 0.3s ease;
        }
        .btn-accept {
            background: linear-gradient(135deg, #00E5FF 0%, #006666 100%);
            color: #fff;
        }
        .btn-reject {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid #ef4444;
        }
        .expires-note {
            text-align: center;
            font-size: 13px;
            color: #f59e0b;
            margin-top: 20px;
            padding: 10px;
            background: rgba(245, 158, 11, 0.1);
            border-radius: 8px;
        }
        .footer {
            background: rgba(0, 0, 0, 0.3);
            padding: 20px 30px;
            text-align: center;
            font-size: 13px;
            color: #666;
        }
        .footer a {
            color: #00E5FF;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">🎮</div>
            <h1>Lời mời Admin</h1>
        </div>
        
        <div class="content">
            <div class="greeting">Xin chào!</div>
            
            <p class="message">
                Bạn đã được mời trở thành <strong style="color: #00E5FF;">Administrator</strong> 
                trên nền tảng Game On - Esports Platform.
            </p>
            
            <div class="inviter-box">
                <strong>{{ $invitation->inviter->name ?? 'Super Admin' }}</strong> đã mời bạn tham gia đội ngũ quản trị.
            </div>
            
            @if(count($permissionDetails) > 0)
            <div class="permissions-section">
                <div class="permissions-title">
                    <span>🔐</span> Quyền hạn được cấp:
                </div>
                
                @foreach($permissionDetails as $key => $permission)
                <div class="permission-item">
                    <div class="permission-icon">
                        <i class="{{ $permission['icon'] }}"></i>
                    </div>
                    <div class="permission-info">
                        <h4>{{ $permission['label'] }}</h4>
                        <p>{{ $permission['description'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
            
            <div class="btn-container">
                <a href="{{ route('admin.invitation.accept', $invitation->token) }}" class="btn btn-accept">
                    ✓ Chấp nhận lời mời
                </a>
                <br><br>
                <a href="{{ route('admin.invitation.reject', $invitation->token) }}" class="btn btn-reject">
                    ✗ Từ chối
                </a>
            </div>
            
            <div class="expires-note">
                ⏰ Lời mời này sẽ hết hạn vào: <strong>{{ $invitation->expires_at->format('d/m/Y H:i') }}</strong>
            </div>
        </div>
        
        <div class="footer">
            <p>Email này được gửi tự động từ <a href="{{ config('app.url') }}">Game On Platform</a></p>
            <p>Nếu bạn không yêu cầu điều này, vui lòng bỏ qua email này.</p>
        </div>
    </div>
</body>
</html>
