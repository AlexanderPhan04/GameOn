<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('app.name')) - {{ __('app.tagline') }}</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'GameOn - Nền tảng tổ chức giải đấu esport và kết nối gamer Việt Nam. Tạo giải đấu, lập team, tham gia tournament Liên Quân, Valorant, PUBG Mobile miễn phí.')">
    <meta name="keywords" content="@yield('meta_keywords', 'giải đấu esport, tổ chức giải đấu game, nền tảng esport Việt Nam, tạo giải đấu online, quản lý tournament, đăng ký giải đấu game, tìm đội esport, tham gia tournament, lập team chơi game, kết nối gamer Việt Nam, giải đấu game online miễn phí, GameOn esport, giải đấu Liên Quân Mobile, tournament Valorant, giải PUBG Mobile, giải đấu Free Fire')">
    <meta name="author" content="GameOn">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', __('app.name')) - {{ __('app.tagline') }}">
    <meta property="og:description" content="@yield('meta_description', 'GameOn - Nền tảng tổ chức giải đấu esport và kết nối gamer Việt Nam. Tạo giải đấu, lập team, tham gia tournament miễn phí.')">
    <meta property="og:image" content="{{ asset('logo_remove_bg.png') }}">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:site_name" content="GameOn">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('title', __('app.name')) - {{ __('app.tagline') }}">
    <meta name="twitter:description" content="@yield('meta_description', 'GameOn - Nền tảng tổ chức giải đấu esport và kết nối gamer Việt Nam.')">
    <meta name="twitter:image" content="{{ asset('logo_remove_bg.png') }}">
    
    <!-- Google Fonts - Rajdhani (Display) & Inter (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo_remove_bg.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo_remove_bg.png') }}">

    <!-- Critical CSS inline to prevent FOUC -->
    <style>
        /* Critical styles for immediate rendering */
        html {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f23 100%);
            height: 100%;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f23 100%);
            color: white;
            overflow-x: hidden;
            min-height: 100vh;
        }
        
        /* Hide content until CSS loads */
        .content-wrapper {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            margin-bottom: 3rem;
        }
        
        .content-wrapper.loaded {
            opacity: 1;
        }
        
        /* Basic layout to prevent layout shift */
        .modern-navbar {
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 30%, #16213e 70%, #0f0f23 100%);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 99999;
            padding: 0.75rem 0;
        }
        
        body {
            padding-top: 90px;
        }
        
        /* Profile specific critical styles */
        .modern-profile-container {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f23 100%);
            position: relative;
            overflow-x: hidden;
            padding-bottom: 0;
        }
        
        html, body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f23 100%) !important;
            background-attachment: fixed;
        }
        
        /* Adjust profile container when sidebar is present */
        body.has-admin-sidebar .modern-profile-container {
            margin-left: 280px;
            width: calc(100% - 280px);
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        body.has-admin-sidebar .admin-sidebar.collapsed ~ * .modern-profile-container {
            margin-left: 80px;
            width: calc(100% - 80px);
        }

        body.has-admin-sidebar .admin-sidebar.collapsed:hover ~ * .modern-profile-container {
            margin-left: 280px;
            width: calc(100% - 280px);
        }
        
        .profile-hero {
            position: relative;
            padding: 40px 0 80px;
            margin-top: 20px;
            margin-bottom: 40px;
        }
        
        .profile-hero .container {
            padding-left: 20px;
            padding-right: 20px;
        }
        
        /* Adjust profile hero padding when sidebar is present */
        body.has-admin-sidebar .profile-hero {
            padding-top: 90px;
        }
        
        body.has-admin-sidebar .profile-hero .container {
            padding-left: 20px;
            padding-right: 20px;
        }
        
        /* Adjust profile main content when sidebar is present */
        body.has-admin-sidebar .profile-main-content {
            margin-left: 0;
            padding-left: 20px;
            padding-right: 20px;
            padding-bottom: 0;
            max-width: 100%;
            width: 100%;
        }
        
        .profile-main-content {
            padding-bottom: 0;
        }
        
        /* Limit container width to prevent excessive empty space */
        body.has-admin-sidebar .profile-main-content .container {
            max-width: 1200px;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            padding-left: 20px;
            padding-right: 20px;
        }
        
        /* Also limit container when no sidebar */
        .profile-main-content .container {
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .profile-avatar-section {
            display: flex;
            align-items: center;
            gap: 40px;
            margin-bottom: 50px;
            flex-wrap: wrap;
        }
        
        .profile-avatar-img, .profile-avatar-placeholder {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3), 0 0 0 4px rgba(102, 126, 234, 0.2);
        }
        
        .profile-avatar-placeholder {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .avatar-initials {
            font-size: 48px;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
        }
        
        .profile-name {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin: 0 0 8px 0;
        }
        
        .profile-username {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.7);
            margin: 0 0 20px 0;
            font-weight: 500;
        }
        
        .role-badge-wrapper {
            margin-bottom: 25px;
        }
        
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .role-badge.super-admin {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.2) 0%, rgba(255, 193, 7, 0.2) 100%);
            color: #ffd700;
            border-color: rgba(255, 215, 0, 0.3);
        }
        
        .profile-actions {
            margin-top: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .profile-stats {
            display: flex;
            gap: 40px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            min-width: 120px;
        }
        
        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-modern {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .btn-modern.btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: rgba(102, 126, 234, 0.3);
        }
        
        .btn-modern.btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.9);
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        .info-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .info-card:last-child {
            margin-bottom: 0;
        }
        
        .card-header {
            padding: 25px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header-content {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }
        
        .card-icon {
            min-width: 60px;
            min-height: 60px;
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            padding: 16px;
            box-sizing: border-box;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
            flex-shrink: 0;
        }
        
        .card-icon i {
            display: block;
            line-height: 1;
            font-size: 20px;
        }
        
        .card-title h3 {
            color: white;
            font-size: 1.3rem;
            font-weight: 600;
            margin: 0;
        }
        
        .card-title p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin: 4px 0 0 0;
        }
        
        .card-content {
            padding: 30px;
            display: flex;
            flex-direction: column;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
            max-width: 100%;
            justify-content: start;
        }
        
        /* Better responsive grid layout - limit width to prevent excessive spacing */
        @media (min-width: 600px) and (max-width: 1199px) {
            .info-grid {
                grid-template-columns: repeat(2, 1fr);
                max-width: 800px;
            }
        }
        
        @media (min-width: 1200px) {
            .info-grid {
                grid-template-columns: repeat(3, 1fr);
                max-width: 1000px;
            }
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .info-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            color: white;
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.4;
        }
        
        .settings-menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .setting-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            text-decoration: none;
            color: inherit;
        }
        
        .setting-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-size: 16px;
            flex-shrink: 0;
        }
        
        .setting-title {
            color: white;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 2px;
        }
        
        .setting-description {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
        }
        
        .stat-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .stat-list-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .stat-list-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, rgba(72, 199, 116, 0.2) 0%, rgba(40, 167, 69, 0.2) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #48c774;
            font-size: 16px;
            flex-shrink: 0;
        }
        
        .stat-list-value {
            color: white;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 2px;
        }
        
        .stat-list-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Mobile responsive for profile */
        @media (max-width: 768px) {
            body.has-admin-sidebar .modern-profile-container {
                margin-left: 0;
            }
            
            body.has-admin-sidebar .profile-hero {
                padding-top: 90px;
            }
            
            .profile-avatar-section {
                flex-direction: column;
                text-align: center;
                gap: 30px;
            }
            
            .profile-avatar-img, .profile-avatar-placeholder {
                width: 120px;
                height: 120px;
            }
            
            .avatar-initials {
                font-size: 36px;
            }
            
            .profile-name {
                font-size: 2rem;
            }
            
            .profile-stats {
                gap: 15px;
            }
            
            .stat-item {
                min-width: 80px;
                padding: 15px;
            }
            
            .stat-number {
                font-size: 1.4rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .card-header {
                padding: 20px;
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .card-content {
                padding: 20px;
            }
        }
        
        @media (max-width: 991.98px) {
            body.has-admin-sidebar .modern-profile-container {
                margin-left: 0;
                width: 100%;
            }
            
            body.has-admin-sidebar .profile-main-content {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            .profile-main-content .container,
            body.has-admin-sidebar .profile-main-content .container {
                max-width: 100%;
                padding-left: 15px;
                padding-right: 15px;
            }
        }
        
        /* Loading screen to prevent FOUC */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f23 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 999999;
            transition: opacity 0.5s ease-out;
        }
        
        .loading-screen.hidden {
            opacity: 0;
            pointer-events: none;
            z-index: -1;
        }
        
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(102, 126, 234, 0.3);
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }
        
        .loading-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            font-weight: 500;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    </noscript>
    
    <!-- AOS Animation - Load after critical content -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        /* Modern Header Styles */
        /* ===== NAVBAR MỚI - ĐƠN GIẢN, GỌN GÀNG ===== */
        .gameon-navbar {
            background: #000022; /* Midnight */
            border-bottom: 1px solid rgba(0, 229, 255, 0.2);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            height: 64px;
        }

        .gameon-navbar .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.25rem;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .gameon-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: white;
        }

        .gameon-brand-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .gameon-brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .gameon-brand-text {
            display: flex;
            flex-direction: column;
        }

        .gameon-brand-name {
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            color: #FFFFFF;
            line-height: 1.1;
            text-shadow: 0 0 10px rgba(0, 229, 255, 0.4);
        }

        .gameon-brand-tagline {
            font-family: 'Inter', sans-serif;
            font-size: 0.625rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            line-height: 1.1;
        }

        .gameon-nav-links {
            display: none !important; /* Hidden - moved to dropdown */
        }

        .gameon-nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            color: #94a3b8;
            font-family: 'Inter', sans-serif;
            font-size: 0.9375rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .gameon-nav-link:hover {
            color: #00E5FF;
            background: rgba(0, 229, 255, 0.1);
        }

        .gameon-nav-link i {
            font-size: 1rem;
            width: 18px;
            text-align: center;
        }

        /* Notification Bell Styles */
        .notification-bell-btn {
            position: relative;
        }
        
        /* Cart Icon */
        .cart-icon-btn {
            position: relative;
            text-decoration: none;
        }
        
        .cart-badge {
            position: absolute;
            top: -4px;
            right: -6px;
            min-width: 18px;
            height: 18px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
            font-size: 10px;
            font-weight: 700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            box-shadow: 0 2px 8px rgba(34, 197, 94, 0.5);
        }
        
        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            min-width: 18px;
            height: 18px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            font-size: 10px;
            font-weight: 700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.5);
            animation: pulse-notification 2s infinite;
        }
        
        @keyframes pulse-notification {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 16px;
            border-bottom: 1px solid rgba(0, 229, 255, 0.1);
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .notification-item:hover {
            background: rgba(0, 229, 255, 0.1);
        }
        
        .notification-item.unread {
            background: rgba(0, 229, 255, 0.05);
        }
        
        .notification-item:last-child {
            border-bottom: none;
        }
        
        .notification-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            flex-shrink: 0;
            overflow: hidden;
        }
        
        .notification-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .notification-content {
            flex: 1;
            min-width: 0;
        }
        
        .notification-text {
            color: white;
            font-size: 13px;
            line-height: 1.4;
            margin-bottom: 4px;
        }
        
        .notification-text strong {
            color: #00E5FF;
        }
        
        .notification-time {
            color: #64748b;
            font-size: 11px;
        }
        
        /* Desktop Notification Dropdown */
        .notification-dropdown-desktop {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 340px;
            background: linear-gradient(180deg, #0d1b2a 0%, #000814 100%);
            border: 1px solid rgba(0, 229, 255, 0.25);
            border-radius: 16px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.6), 0 0 30px rgba(0, 229, 255, 0.1);
            z-index: 10000;
            overflow: hidden;
        }
        
        .notification-dropdown-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            background: linear-gradient(135deg, rgba(0, 0, 85, 0.4), rgba(0, 229, 255, 0.05));
            border-bottom: 1px solid rgba(0, 229, 255, 0.15);
        }
        
        .notification-dropdown-header span {
            color: #fff;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 1rem;
        }
        
        .notification-mark-read-btn {
            background: none;
            border: none;
            color: #00E5FF;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .notification-mark-read-btn:hover {
            color: #fff;
            text-decoration: underline;
        }
        
        .notification-dropdown-list {
            max-height: 380px;
            overflow-y: auto;
        }
        
        .notification-dropdown-list::-webkit-scrollbar {
            width: 4px;
        }
        
        .notification-dropdown-list::-webkit-scrollbar-thumb {
            background: rgba(0, 229, 255, 0.3);
            border-radius: 4px;
        }
        
        .notification-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            color: #64748b;
            gap: 12px;
        }
        
        .notification-empty-state i {
            font-size: 32px;
            opacity: 0.4;
            color: #00E5FF;
        }
        
        .notification-empty-state span {
            font-size: 14px;
        }

        .gameon-user-menu {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Language Dropdown Styles */
        #languageDropdownMenu.show,
        #languageDropdownMobileMenu.show {
            display: block !important;
        }
        
        .language-dropdown-container {
            background: linear-gradient(135deg, rgba(13, 27, 42, 0.98), rgba(0, 0, 34, 0.98));
            border: 1px solid rgba(0, 229, 255, 0.2);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.5), 0 0 20px rgba(0, 229, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .language-dropdown-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(0, 229, 255, 0.1);
            color: #fff;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .language-dropdown-header i {
            color: #00E5FF;
        }
        
        .language-dropdown-list {
            padding: 0.5rem;
        }
        
        .language-item {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 0.25rem;
        }
        
        .language-item:last-child {
            margin-bottom: 0;
        }
        
        .language-item:hover {
            background: rgba(0, 229, 255, 0.1);
        }
        
        .language-item.active {
            background: rgba(0, 229, 255, 0.15);
        }
        
        .language-flag {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .language-flag .flag-icon {
            font-size: 1.25rem;
        }
        
        .language-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
        }
        
        .language-name {
            color: #fff;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .language-code {
            color: #64748b;
            font-size: 0.75rem;
            text-transform: uppercase;
        }
        
        .language-item.active .language-name {
            color: #00E5FF;
        }
        
        .language-check {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00E5FF, #0099cc);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-size: 0.7rem;
        }

        .gameon-user-avatar {
            width: 36px;
            height: 36px;
            background: #000055;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #00E5FF;
            font-size: 1rem;
            box-shadow: 0 0 10px rgba(0, 229, 255, 0.4);
            border: 2px solid rgba(0, 229, 255, 0.3);
        }

        .gameon-user-info {
            display: flex;
            flex-direction: column;
        }

        .gameon-user-name {
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 0.9375rem;
            color: #FFFFFF;
            line-height: 1.2;
        }

        .gameon-user-role {
            font-family: 'Inter', sans-serif;
            font-size: 0.625rem;
            color: #94a3b8;
            text-transform: uppercase;
            line-height: 1.1;
        }

        .gameon-dropdown {
            background: #0d1b2a; /* Surface */
            border: 1px solid rgba(0, 229, 255, 0.2);
            border-radius: 12px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        }

        .gameon-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            color: #94a3b8;
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .gameon-dropdown-item:hover {
            background: rgba(0, 229, 255, 0.15);
            color: #00E5FF;
        }

        .gameon-dropdown-item i {
            width: 18px;
            text-align: center;
        }

        /* Mobile */
        @media (max-width: 991px) {
            .gameon-nav-links {
                display: none;
            }

            .gameon-user-info {
                display: none;
            }
        }
        
        /* ===== MOBILE SLIDE MENU ===== */
        .mobile-menu-toggle {
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 44px;
            height: 44px;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 8px;
            gap: 5px;
            border-radius: 8px;
            transition: background 0.3s ease;
            margin-right: 10px;
        }
        
        .mobile-menu-toggle:hover {
            background: rgba(0, 229, 255, 0.1);
        }
        
        .mobile-menu-toggle .hamburger-line {
            width: 24px;
            height: 2px;
            background: #00E5FF;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        .mobile-menu-toggle.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }
        
        .mobile-menu-toggle.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }
        
        .mobile-menu-toggle.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }
        
        @media (max-width: 1023px) {
            .mobile-menu-toggle {
                display: flex;
            }
        }
        
        /* Overlay */
        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .mobile-menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        /* Slide Menu */
        .mobile-slide-menu {
            position: fixed;
            top: 0;
            right: 0;
            width: 300px;
            max-width: 85vw;
            height: 100vh;
            background: linear-gradient(180deg, #000814 0%, #000022 50%, #0d1b2a 100%);
            z-index: 10001;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.5);
            border-left: 1px solid rgba(0, 229, 255, 0.2);
        }
        
        .mobile-slide-menu.active {
            transform: translateX(0);
        }
        
        /* Menu Header */
        .mobile-menu-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid rgba(0, 229, 255, 0.2);
            background: rgba(0, 0, 85, 0.3);
        }
        
        .mobile-menu-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .mobile-menu-brand img {
            width: 40px;
            height: 40px;
            max-width: 40px;
            max-height: 40px;
            object-fit: contain;
            flex-shrink: 0;
        }
        
        .mobile-menu-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* Mobile Notification */
        .mobile-notification-wrapper {
            position: relative;
        }
        
        .mobile-notification-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 229, 255, 0.1);
            border: 1px solid rgba(0, 229, 255, 0.3);
            border-radius: 8px;
            color: #00E5FF;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .mobile-notification-btn:hover {
            background: rgba(0, 229, 255, 0.2);
        }
        
        .mobile-notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 16px;
            height: 16px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            font-size: 9px;
            font-weight: 700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 3px;
        }
        
        .mobile-notification-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 280px;
            max-height: 350px;
            background: #0d1b2a;
            border: 1px solid rgba(0, 229, 255, 0.2);
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 100;
            overflow: hidden;
        }
        
        .mobile-notification-dropdown.show {
            display: block;
        }
        
        .mobile-notification-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-bottom: 1px solid rgba(0, 229, 255, 0.1);
            background: rgba(0, 0, 85, 0.3);
        }
        
        .mobile-notification-header span {
            color: white;
            font-weight: 600;
            font-size: 14px;
        }
        
        .mobile-mark-all-read {
            background: none;
            border: none;
            color: #00E5FF;
            font-size: 12px;
            cursor: pointer;
        }
        
        .mobile-notification-list {
            max-height: 280px;
            overflow-y: auto;
        }
        
        .mobile-notification-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
            color: #64748b;
            gap: 8px;
        }
        
        .mobile-notification-empty i {
            font-size: 24px;
            opacity: 0.5;
        }
        
        .mobile-notification-empty span {
            font-size: 13px;
        }
        
        .mobile-menu-close {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .mobile-menu-close:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.5);
            color: #ef4444;
        }
        
        /* Menu Content */
        .mobile-menu-content {
            flex: 1;
            overflow-y: auto;
            padding: 16px 0;
        }
        
        /* User Info */
        .mobile-user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            margin-bottom: 8px;
        }
        
        .mobile-user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00E5FF, #0099cc);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-size: 18px;
            overflow: hidden;
            border: 2px solid rgba(0, 229, 255, 0.3);
        }
        
        .mobile-user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .mobile-user-name {
            color: white;
            font-weight: 600;
            font-size: 16px;
        }
        
        .mobile-user-role {
            color: #00E5FF;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Menu Divider */
        .mobile-menu-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0, 229, 255, 0.3), transparent);
            margin: 12px 20px;
        }
        
        /* Section Title */
        .mobile-menu-section-title {
            color: #64748b;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 8px 20px;
        }
        
        /* Menu Nav */
        .mobile-menu-nav {
            display: flex;
            flex-direction: column;
        }
        
        .mobile-menu-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .mobile-menu-item:hover {
            background: rgba(0, 229, 255, 0.1);
            color: #00E5FF;
            border-left-color: #00E5FF;
        }
        
        .mobile-menu-item.active {
            background: rgba(0, 229, 255, 0.15);
            color: #00E5FF;
            border-left-color: #00E5FF;
        }
        
        .mobile-menu-item i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }
        
        .mobile-menu-item span {
            font-size: 15px;
            font-weight: 500;
        }
        
        /* Language Switcher */
        .mobile-language-switcher {
            display: flex;
            gap: 10px;
            padding: 8px 20px;
        }
        
        .mobile-lang-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .mobile-lang-btn:hover {
            background: rgba(0, 229, 255, 0.1);
            border-color: rgba(0, 229, 255, 0.3);
            color: #00E5FF;
        }
        
        .mobile-lang-btn.active {
            background: rgba(0, 229, 255, 0.2);
            border-color: #00E5FF;
            color: #00E5FF;
        }
        
        /* Auth Buttons */
        .mobile-auth-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 8px 20px;
        }
        
        .mobile-auth-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        
        .mobile-login-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .mobile-login-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .mobile-register-btn {
            background: linear-gradient(135deg, #00E5FF, #0099cc);
            border: none;
            color: #000;
        }
        
        .mobile-register-btn:hover {
            box-shadow: 0 0 20px rgba(0, 229, 255, 0.4);
            transform: translateY(-2px);
        }
        
        /* Logout */
        .mobile-logout-form {
            padding: 0;
        }
        
        .mobile-logout-btn {
            width: 100%;
            background: transparent;
            border: none;
            cursor: pointer;
            color: rgba(239, 68, 68, 0.8);
        }
        
        .mobile-logout-btn:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border-left-color: #ef4444;
        }
        
        /* Hide desktop elements on mobile */
        @media (max-width: 1023px) {
            .gameon-user-menu > .gameon-nav-link:not(.mobile-menu-toggle),
            .gameon-user-menu > #userMenuDropdown,
            .gameon-user-menu > #languageDropdown,
            .gameon-user-menu > .relative:not(:last-child) {
                display: none;
            }
        }
        
        /* Mobile Icon Buttons */
        .mobile-icon-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(0, 229, 255, 0.1);
            border: 1px solid rgba(0, 229, 255, 0.2);
            color: #00E5FF;
            transition: all 0.3s;
            position: relative;
            margin-left: 8px;
        }
        
        /* Hide mobile icons on desktop */
        @media (min-width: 1024px) {
            .mobile-icon-btn {
                display: none !important;
            }
        }
        
        .mobile-icon-btn:first-child {
            margin-left: 0;
        }
        
        .mobile-icon-btn:hover {
            background: rgba(0, 229, 255, 0.2);
            color: #fff;
        }
        
        .mobile-icon-btn i {
            font-size: 1rem;
        }
        
        .mobile-cart-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
        }

        /* Footer Styles - Comprehensive CSS to ensure proper display */
        footer {
            position: relative !important;
            overflow: hidden !important;
        }
        
        /* Remove underline from all footer links */
        footer a {
            text-decoration: none !important;
        }
        
        /* Remove bullet points from footer lists */
        footer ul {
            list-style: none !important;
            padding-left: 0 !important;
        }
        
        footer li {
            list-style: none !important;
        }
        
        footer.bg-midnight,
        footer[class*="bg-midnight"] {
            background-color: #000022 !important;
        }
        
        footer [class*="border-border"],
        footer .border-border {
            border-color: #1a237e !important;
        }
        
        footer.text-slate-400,
        footer [class*="text-slate-400"] {
            color: #94a3b8 !important;
        }
        
        footer [class*="text-white"],
        footer .text-white {
            color: #ffffff !important;
        }
        
        footer [class*="bg-void"],
        footer .bg-void {
            background-color: #000814 !important;
        }
        
        footer [class*="bg-brand"]:not([class*="bg-brand/"]),
        footer .bg-brand {
            background-color: #000055 !important;
        }
        
        footer [class*="text-neon"],
        footer .text-neon {
            color: #00E5FF !important;
        }
        
        footer [class*="border-neon"],
        footer .border-neon {
            border-color: #00E5FF !important;
        }
        
        /* Blur effect background */
        footer [class*="bg-brand/20"],
        footer [class*="bg-brand\/20"] {
            background-color: rgba(0, 0, 85, 0.2) !important;
        }
        
        /* Ensure font families work */
        footer [class*="font-display"],
        footer .font-display {
            font-family: 'Rajdhani', sans-serif !important;
        }
        
        footer [class*="font-body"],
        footer .font-body {
            font-family: 'Inter', sans-serif !important;
        }
        
        /* Grid and layout */
        footer .container {
            max-width: 1280px !important;
            margin-left: auto !important;
            margin-right: auto !important;
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }
        
        footer .grid {
            display: grid !important;
        }
        
        /* Spacing */
        footer .space-y-6 > * + * {
            margin-top: 1.5rem !important;
        }
        
        footer .space-y-3 > * + * {
            margin-top: 0.75rem !important;
        }
        
        footer .gap-12 {
            gap: 3rem !important;
        }
        
        footer .gap-4 {
            gap: 1rem !important;
        }
        
        footer .gap-3 {
            gap: 0.75rem !important;
        }
        
        footer .gap-2 {
            gap: 0.5rem !important;
        }
        
        footer .gap-1 {
            gap: 0.25rem !important;
        }
        
        /* Padding and margins */
        footer .pt-16 {
            padding-top: 4rem !important;
        }
        
        footer .pb-8 {
            padding-bottom: 2rem !important;
        }
        
        footer .mb-16 {
            margin-bottom: 4rem !important;
        }
        
        footer .mb-6 {
            margin-bottom: 1.5rem !important;
        }
        
        footer .mb-4 {
            margin-bottom: 1rem !important;
        }
        
        footer .px-6 {
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }
        
        footer .pt-8 {
            padding-top: 2rem !important;
        }
        
        /* Border */
        footer .border-t {
            border-top-width: 1px !important;
        }
        
        footer .border-l-4 {
            border-left-width: 4px !important;
        }
        
        footer .pl-3 {
            padding-left: 0.75rem !important;
        }
        
        /* Rounded */
        footer .rounded-lg {
            border-radius: 0.5rem !important;
        }
        
        footer .rounded-md {
            border-radius: 0.375rem !important;
        }
        
        footer .rounded {
            border-radius: 0.25rem !important;
        }
        
        /* Flexbox */
        footer .flex {
            display: flex !important;
        }
        
        footer .items-center {
            align-items: center !important;
        }
        
        footer .items-start {
            align-items: flex-start !important;
        }
        
        footer .justify-center {
            justify-content: center !important;
        }
        
        footer .justify-between {
            justify-content: space-between !important;
        }
        
        /* Text */
        footer .text-3xl {
            font-size: 1.875rem !important;
            line-height: 2.25rem !important;
        }
        
        footer .text-lg {
            font-size: 1.125rem !important;
            line-height: 1.75rem !important;
        }
        
        footer .text-sm {
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
        }
        
        footer .text-xs {
            font-size: 0.75rem !important;
            line-height: 1rem !important;
        }
        
        footer .font-bold {
            font-weight: 700 !important;
        }
        
        footer .font-semibold {
            font-weight: 600 !important;
        }
        
        footer .uppercase {
            text-transform: uppercase !important;
        }
        
        footer .tracking-wider {
            letter-spacing: 0.05em !important;
        }
        
        footer .leading-relaxed {
            line-height: 1.625 !important;
        }
        
        /* Width and height */
        footer .w-10 {
            width: 2.5rem !important;
        }
        
        footer .h-10 {
            height: 2.5rem !important;
        }
        
        footer .w-full {
            width: 100% !important;
        }
        
        footer .flex-1 {
            flex: 1 1 0% !important;
        }
        
        /* Position */
        footer .relative {
            position: relative !important;
        }
        
        footer .absolute {
            position: absolute !important;
        }
        
        footer .z-10 {
            z-index: 10 !important;
        }
        
        /* Blur */
        footer [class*="blur-\[100px\]"] {
            filter: blur(100px) !important;
        }
        
        /* Responsive */
        @media (min-width: 768px) {
            footer .md\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }
        
        @media (min-width: 1024px) {
            footer .lg\:grid-cols-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            }
        }

        /* Global Search - Modern UI */
        #searchBox {
            transform-origin: center center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 100000 !important;
        }
        .search-panel{backdrop-filter:blur(12px);background:rgba(255,255,255,.9);border:1px solid rgba(148,163,184,.25);border-radius:16px;box-shadow:0 18px 60px rgba(2,6,23,.25);width:560px;max-width:90vw;transform-origin:center center}
        .search-input-wrap{display:flex;align-items:center;gap:.5rem;padding:.6rem .75rem;border-bottom:1px solid rgba(148,163,184,.25)}
        .search-input{border:none;outline:none;width:100%;background:transparent;font-size:.95rem}
        .search-kbd{background:#0f172a;color:#e2e8f0;border-radius:6px;padding:.15rem .35rem;font-size:.7rem}
        .search-clear{border:none;background:transparent;color:#475569}
        .search-loading{width:16px;height:16px;border:2px solid #94a3b8;border-top-color:#6366f1;border-radius:50%;animation:spin .8s linear infinite;display:none}
        .search-results{max-height:360px;overflow:auto}
        .result-section-title{font-size:.75rem;color:#475569;background:#f8fafc;padding:.4rem .75rem;border-top:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0}
        .result-item{display:flex;align-items:center;gap:.6rem;padding:.55rem .75rem;text-decoration:none;color:#0f172a}
        .result-item:hover,.result-item.active-result{background:#eef2ff}
        .result-icon{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;flex:0 0 28px}
        .result-meta{font-size:.8rem;color:#64748b}
        .empty-state{padding:1rem;color:#64748b;text-align:center}
        @keyframes spin{to{transform:rotate(360deg)}}
        
        /* Mobile responsive for search box */
        @media (max-width: 768px) {
            .search-panel {
                width: calc(100vw - 20px) !important;
                max-width: calc(100vw - 20px) !important;
            }
            #searchBox {
                left: 10px !important;
                right: 10px !important;
                width: auto !important;
                min-width: auto !important;
            }
            .search-input-wrap {
                padding: 0.5rem 0.6rem;
                gap: 0.4rem;
            }
            .search-input {
                font-size: 0.9rem;
            }
            .search-results {
                max-height: 300px;
            }
        }

        /* Footer styles are now handled by Tailwind CSS classes */

        /* Body offset for fixed header */
        body {
            padding-top: 64px;
        }

        /* Remove padding-top on welcome page */
        body.welcome-page {
            padding-top: 0;
        }

        /* Admin Sidebar Styles - Deep Blue Design System */
        .admin-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: #000022; /* Midnight */
            border-right: 1px solid rgba(0, 229, 255, 0.15);
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        /* Collapsed sidebar - chỉ hiện icon */
        .admin-sidebar.collapsed {
            width: 80px;
        }

        .admin-sidebar.collapsed .sidebar-brand-text,
        .admin-sidebar.collapsed .menu-link span,
        .admin-sidebar.collapsed .menu-link-logout span,
        .admin-sidebar.collapsed .user-details,
        .admin-sidebar.collapsed .menu-submenu,
        .admin-sidebar.collapsed .menu-link::after {
            opacity: 0;
            width: 0;
            overflow: hidden;
            white-space: nowrap;
            transition: opacity 0.3s ease, width 0.4s ease;
            display: none;
        }

        /* Khi hover vào sidebar collapsed, hiện text */
        .admin-sidebar.collapsed:hover {
            width: 280px;
            z-index: 1001;
            box-shadow: 4px 0 40px rgba(0, 229, 255, 0.15);
        }

        .admin-sidebar.collapsed:hover .sidebar-brand-text,
        .admin-sidebar.collapsed:hover .menu-link span,
        .admin-sidebar.collapsed:hover .menu-link-logout span,
        .admin-sidebar.collapsed:hover .user-details,
        .admin-sidebar.collapsed:hover .menu-submenu,
        .admin-sidebar.collapsed:hover .menu-link::after {
            opacity: 1;
            width: auto;
            display: block;
        }

        .admin-sidebar.collapsed:hover .menu-link::after {
            display: inline-block;
        }

        .admin-sidebar.collapsed:hover .menu-link-logout {
            justify-content: flex-start !important;
            padding: 0.875rem 1.5rem !important;
        }

        .admin-sidebar.collapsed:hover .menu-link {
            justify-content: flex-start !important;
            padding: 0.875rem 1.5rem !important;
        }

        .admin-sidebar.collapsed:hover .sidebar-brand {
            justify-content: flex-start !important;
        }

        .admin-sidebar.collapsed:hover .brand-icon {
            margin-right: 1rem !important;
        }

        /* Đảm bảo icon luôn hiện */
        .admin-sidebar.collapsed .menu-link i {
            opacity: 1;
            width: auto;
            flex-shrink: 0;
        }

        .admin-sidebar.collapsed .brand-icon {
            display: none;
        }

        .admin-sidebar.collapsed:hover .brand-icon {
            display: flex;
        }

        /* Căn giữa icon khi collapsed */
        .admin-sidebar.collapsed .menu-link {
            justify-content: center;
            padding: 0.875rem 1rem;
        }

        .admin-sidebar.collapsed .menu-link-logout {
            justify-content: center !important;
            padding: 0.875rem 1rem !important;
            width: 100%;
        }

        .admin-sidebar.collapsed .menu-form {
            width: 100%;
        }

        .admin-sidebar.collapsed .menu-form button {
            width: 100%;
            justify-content: center;
        }

        .admin-sidebar.collapsed .sidebar-brand {
            justify-content: center;
            margin: 0 auto;
            width: 100%;
        }

        .admin-sidebar.collapsed .brand-icon {
            margin-right: 0 !important;
        }

        .admin-sidebar.collapsed .sidebar-header {
            padding: 1rem 0.5rem;
            justify-content: center;
            min-height: 60px;
        }

        .admin-sidebar.collapsed .sidebar-brand {
            display: none;
        }

        .admin-sidebar.collapsed:hover .sidebar-brand {
            display: flex;
        }

        .admin-sidebar.collapsed .sidebar-header .sidebar-toggle {
            display: none;
        }

        .admin-sidebar.collapsed .menu-divider {
            margin: 0.75rem 0.5rem;
        }

        .admin-sidebar.collapsed .sidebar-footer {
            padding: 1rem 0.5rem;
            align-items: center;
        }

        .admin-sidebar.collapsed .sidebar-user-info {
            justify-content: center;
        }

        .admin-sidebar.collapsed .sidebar-user-info .user-avatar {
            margin: 0 auto;
        }

        /* Ẩn text trong language switcher khi collapsed */
        .admin-sidebar.collapsed .language-switcher-sidebar button {
            padding: 0.5rem !important;
            width: 40px !important;
            height: 40px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
            margin: 0 auto;
        }

        .admin-sidebar.collapsed .language-switcher-sidebar {
            display: flex;
            justify-content: center;
        }

        .admin-sidebar.collapsed .language-switcher-sidebar button span {
            display: none;
        }

        .admin-sidebar.collapsed .language-switcher-sidebar button i {
            margin: 0 !important;
        }

        /* Hiện lại khi hover */
        .admin-sidebar.collapsed:hover .language-switcher-sidebar {
            display: block;
        }

        .admin-sidebar.collapsed:hover .language-switcher-sidebar button {
            padding: 0.375rem 0.75rem !important;
            width: 100% !important;
            height: auto !important;
            border-radius: 0.25rem !important;
        }

        .admin-sidebar.collapsed:hover .language-switcher-sidebar button span {
            display: inline;
        }

        .admin-sidebar.collapsed:hover .language-switcher-sidebar button i {
            margin-right: 0.5rem !important;
        }

        /* Điều chỉnh margin khi sidebar collapsed */
        body.has-admin-sidebar .admin-sidebar.collapsed ~ .content-wrapper,
        body.has-admin-sidebar .admin-sidebar.collapsed ~ main {
            margin-left: 80px;
            transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.has-admin-sidebar .admin-sidebar.collapsed:hover ~ .content-wrapper,
        body.has-admin-sidebar .admin-sidebar.collapsed:hover ~ main {
            margin-left: 280px;
        }

        /* Tooltip khi collapsed */
        .admin-sidebar.collapsed .menu-link {
            position: relative;
        }

        .admin-sidebar.collapsed .menu-link[title]:hover::before {
            content: attr(title);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 10px;
            padding: 0.5rem 0.75rem;
            background: #0d1b2a;
            color: #00E5FF;
            border: 1px solid rgba(0, 229, 255, 0.3);
            border-radius: 6px;
            white-space: nowrap;
            z-index: 1002;
            font-size: 0.875rem;
            pointer-events: none;
            box-shadow: 0 4px 20px rgba(0, 229, 255, 0.2);
        }

        /* Hide scrollbar for sidebar */
        .admin-sidebar::-webkit-scrollbar {
            width: 0px;
            display: none;
        }

        .admin-sidebar {
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(0, 229, 255, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white !important;
            flex: 1;
        }
        
        .brand-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }
        
        .brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .sidebar-toggle {
            background: rgba(0, 229, 255, 0.1);
            border: 1px solid rgba(0, 229, 255, 0.2);
            color: #00E5FF;
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background: rgba(0, 229, 255, 0.2);
            box-shadow: 0 0 15px rgba(0, 229, 255, 0.3);
        }

        .sidebar-user-info {
            padding: 1rem 0;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .sidebar-user-info .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #000055, #000077);
            border: 2px solid rgba(0, 229, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #00E5FF;
            font-size: 1.2rem;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 0 15px rgba(0, 229, 255, 0.2);
        }

        .sidebar-user-info .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-details {
            flex: 1;
            min-width: 0;
        }

        .user-details .user-name {
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            color: #FFFFFF;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-details .user-role {
            color: #00E5FF;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
        }
        
        .sidebar-nav::-webkit-scrollbar {
            width: 0px;
            display: none;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-item {
            margin-bottom: 0.25rem;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.875rem 1.5rem;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
        }

        .menu-link:hover {
            color: #00E5FF;
            background: rgba(0, 229, 255, 0.1);
        }

        .menu-link:hover i {
            color: #00E5FF;
            text-shadow: 0 0 10px rgba(0, 229, 255, 0.5);
        }

        .menu-item.active .menu-link {
            color: #00E5FF;
            background: rgba(0, 229, 255, 0.15);
            border-left: 3px solid #00E5FF;
        }

        .menu-item.active .menu-link i {
            color: #00E5FF;
            text-shadow: 0 0 10px rgba(0, 229, 255, 0.5);
        }

        .menu-item.active .menu-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #00E5FF;
            box-shadow: 0 0 10px rgba(0, 229, 255, 0.5);
        }

        .menu-link i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
            color: #94a3b8;
            transition: all 0.3s ease;
        }

        .menu-link-logout {
            color: #94a3b8 !important;
            background: transparent !important;
            border-radius: 0 !important;
            border: none !important;
        }

        .menu-link-logout:hover {
            color: #00E5FF !important;
            background: rgba(0, 229, 255, 0.1) !important;
            border-radius: 0 !important;
        }

        .menu-link-logout:hover i {
            color: #00E5FF;
            text-shadow: 0 0 10px rgba(0, 229, 255, 0.5);
        }
        
        .menu-form button.menu-link-logout,
        button.menu-link-logout,
        .menu-item button.menu-link-logout {
            border-radius: 0 !important;
            -webkit-border-radius: 0 !important;
            -moz-border-radius: 0 !important;
        }
        
        .menu-form button.menu-link-logout:hover,
        button.menu-link-logout:hover,
        .menu-item button.menu-link-logout:hover {
            border-radius: 0 !important;
            -webkit-border-radius: 0 !important;
            -moz-border-radius: 0 !important;
        }

        .menu-form {
            margin: 0;
        }

        .menu-divider {
            height: 1px;
            background: rgba(0, 229, 255, 0.15);
            margin: 0.75rem 1.5rem;
        }
        
        /* Sidebar dropdown menu styles */
        .menu-item.has-submenu {
            position: relative;
        }
        
        .menu-item.has-submenu > .menu-link {
            cursor: pointer;
        }
        
        .menu-item.has-submenu > .menu-link::after {
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-left: auto;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: #94a3b8;
        }
        
        .menu-item.has-submenu.open > .menu-link::after {
            transform: rotate(180deg);
            color: #00E5FF;
        }
        
        .menu-submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(0, 0, 0, 0.3);
        }
        
        .menu-item.has-submenu.open > .menu-submenu {
            max-height: 500px;
        }
        
        .menu-submenu .menu-link {
            padding-left: 3rem;
            padding-top: 0.6rem;
            padding-bottom: 0.6rem;
            font-size: 0.9rem;
        }
        
        .menu-submenu .menu-item.active .menu-link {
            background: rgba(0, 229, 255, 0.15);
            border-left: 3px solid #00E5FF;
            color: #00E5FF;
        }
        
        .menu-item.has-submenu.open > .menu-link {
            background: rgba(0, 229, 255, 0.1);
        }

        .sidebar-footer {
            position: sticky;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(0, 229, 255, 0.15);
            background: #000022;
            margin-top: auto;
            display: flex;
            flex-direction: column;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            min-height: 0;
        }

        .language-switcher-sidebar .btn {
            border-color: rgba(0, 229, 255, 0.3);
            color: #94a3b8;
            transition: all 0.3s ease;
        }

        .language-switcher-sidebar .btn:hover {
            background: rgba(0, 229, 255, 0.1);
            border-color: #00E5FF;
            color: #00E5FF;
        }

        /* Admin Top Bar */
        .admin-topbar {
            position: fixed;
            top: 0;
            left: 280px;
            transition: left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.has-admin-sidebar .admin-sidebar.collapsed ~ * .admin-topbar,
        .admin-sidebar.collapsed ~ .admin-topbar {
            left: 80px;
        }

        body.has-admin-sidebar .admin-sidebar.collapsed:hover ~ * .admin-topbar,
        .admin-sidebar.collapsed:hover ~ .admin-topbar {
            left: 280px;
        }

        .admin-topbar {
            position: fixed;
            top: 0;
            right: 0;
            height: 70px;
            background: #000022;
            border-bottom: 1px solid rgba(0, 229, 255, 0.15);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            z-index: 999;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
        }

        .sidebar-toggle-mobile {
            background: rgba(0, 229, 255, 0.1);
            border: 1px solid rgba(0, 229, 255, 0.2);
            color: #00E5FF;
            padding: 0.75rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 1rem;
        }

        .sidebar-toggle-mobile:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .topbar-search {
            flex: 1;
            display: flex;
            justify-content: flex-end;
        }

        /* Content Wrapper with Sidebar */
        .content-wrapper-with-sidebar {
            margin-left: 280px;
            transition: margin-left 0.3s ease;
        }

        body.has-admin-sidebar .admin-sidebar.collapsed ~ * .content-wrapper-with-sidebar {
            margin-left: 80px;
        }

        body.has-admin-sidebar .admin-sidebar.collapsed:hover ~ * .content-wrapper-with-sidebar {
            margin-left: 280px;
        }

        .content-wrapper-with-sidebar {
            padding-top: 70px;
        }

        /* Adjust body padding for admin */
        body.has-admin-sidebar {
            padding-top: 0;
        }

        /* Adjust main content margin when sidebar collapsed */
        body.has-admin-sidebar main,
        body.has-admin-sidebar .content-wrapper {
            margin-left: 280px;
            transition: margin-left 0.3s ease;
        }

        body.has-admin-sidebar .admin-sidebar.collapsed ~ main,
        body.has-admin-sidebar .admin-sidebar.collapsed ~ .content-wrapper {
            margin-left: 80px;
        }

        body.has-admin-sidebar .admin-sidebar.collapsed:hover ~ main,
        body.has-admin-sidebar .admin-sidebar.collapsed:hover ~ .content-wrapper {
            margin-left: 280px;
        }

        /* Sidebar Backdrop */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-backdrop.show {
            display: block;
            opacity: 1;
        }

        /* Mobile Responsive */
        @media (max-width: 991.98px) {
            .admin-sidebar,
            .admin-sidebar.collapsed,
            aside.admin-sidebar,
            body.has-admin-sidebar .admin-sidebar {
                display: none !important;
                visibility: hidden !important;
                width: 0 !important;
                opacity: 0 !important;
            }

            .admin-sidebar.show,
            body.has-admin-sidebar .admin-sidebar.show {
                display: flex !important;
                visibility: visible !important;
                width: 280px !important;
                opacity: 1 !important;
                transform: translateX(0);
                z-index: 10000;
            }

            .admin-topbar {
                left: 0 !important;
                right: 0 !important;
                width: 100% !important;
            }

            .content-wrapper-with-sidebar {
                margin-left: 0;
            }
            
            /* Fix: Remove margin-left on mobile for admin dashboard */
            body.has-admin-sidebar main,
            body.has-admin-sidebar .content-wrapper,
            body.has-admin-sidebar .dashboard-container,
            body.has-admin-sidebar .admin-sidebar.collapsed ~ main,
            body.has-admin-sidebar .admin-sidebar.collapsed ~ .content-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .sidebar-toggle-mobile {
                display: block !important;
            }
        }

        @media (min-width: 992px) {
            .sidebar-toggle-mobile {
                display: none !important;
            }
        }

        /* Global Toasts */
        #app-toast-container{position:fixed;top:1rem;right:1rem;z-index:1080;display:flex;flex-direction:column;gap:.5rem}
        .app-toast{border-radius:12px;padding:.75rem 1rem;color:#fff;display:flex;align-items:center;gap:.5rem;box-shadow:0 8px 24px rgba(0,0,0,.18)}
        .app-toast-success{background:linear-gradient(135deg,#22c55e,#16a34a)}
        .app-toast-error{background:linear-gradient(135deg,#ef4444,#dc2626)}
        .app-toast-info{background:linear-gradient(135deg,#3b82f6,#2563eb)}
        .app-toast-warning{background:linear-gradient(135deg,#f59e0b,#d97706)}

        /* Adjust for mobile */
        @media (max-width: 768px) {
            body {
                padding-top: 50px;
            }
            
            .modern-navbar {
                padding: 0.25rem 0;
            }
            
            .modern-navbar .container {
                padding-left: 10px;
                padding-right: 10px;
            }
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .modern-brand {
                padding: 0.25rem 0.5rem;
            }
            
            .brand-icon {
                width: 40px;
                height: 40px;
                margin-right: 0.75rem;
                font-size: 1.2rem;
            }
            
            .brand-name {
                font-size: 1.1rem;
            }
            
            .brand-tagline {
                font-size: 0.65rem;
            }
            
            .modern-nav-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.9rem;
            }
            
            .modern-user-dropdown {
                padding: 0.4rem 0.75rem;
            }
            
            .user-avatar {
                width: 30px;
                height: 30px;
            }

            .user-name {
                font-size: 0.85rem;
            }

            .user-role {
                font-size: 0.6rem;
            }

            /* Compact navbar trên mobile */
            .modern-navbar {
                padding: 0.5rem 0;
            }

            .modern-nav {
                flex-direction: column;
                gap: 0.25rem;
            }

            .modern-user-nav {
                flex-direction: column;
                gap: 0.25rem;
                margin-top: 0.5rem;
            }
            
            .social-links {
                justify-content: center;
            }
            
            /* Footer styles are now handled by Tailwind CSS */
            
            /* Ensure navbar collapse doesn't block content when closed */
            .navbar-collapse:not(.show) {
                pointer-events: none;
                height: 0;
                overflow: hidden;
                max-height: 0;
            }
            
            .navbar-collapse.show {
                pointer-events: auto;
                max-height: calc(100vh - 100px);
                overflow-y: auto;
            }
            
            /* Ensure navbar collapse doesn't overlay content */
            .navbar-collapse {
                position: relative;
                z-index: 99998;
                transition: max-height 0.3s ease;
            }
            
            /* Ensure main content is clickable */
            .content-wrapper {
                position: relative;
                z-index: 1;
            }
            
            /* Ensure hero section buttons are always clickable */
            .hero-section {
                position: relative;
                z-index: 1;
            }
            
            .hero-section .btn,
            .hero-section a {
                position: relative;
                z-index: 10;
                pointer-events: auto !important;
            }
        }
    </style>

    @stack('styles')

    <!-- jQuery must be loaded before inline page scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>

@php
    $isAdmin = Auth::check() && in_array(Auth::user()->user_role, ['admin', 'super_admin']);
    $showAdminSidebar = $isAdmin && !Request::is('/') && !Request::is('home');
@endphp

<body class="{{ Request::is('chat*') ? 'chat-page' : '' }} {{ $showAdminSidebar ? 'has-admin-sidebar' : '' }} {{ Request::is('/') || Request::is('welcome') ? 'welcome-page' : '' }}">
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-spinner"></div>
        <div class="loading-text">{{ __('app.common.loading') }}</div>
    </div>
    
    @if($showAdminSidebar)
        <!-- Sidebar Backdrop (Mobile) -->
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
        
        <!-- Admin Sidebar -->
        <aside class="admin-sidebar collapsed" id="adminSidebar">
            <div class="sidebar-header">
                <a href="{{ route('home') }}" class="sidebar-brand">
                    <div class="brand-icon">
                        <img src="{{ asset('logo_remove_bg.png') }}" alt="{{ __('app.name') }}" class="w-8 h-8 object-contain">
                    </div>
                </a>
                <button class="sidebar-toggle lg:hidden" id="sidebarToggle">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav class="sidebar-nav">
                <ul class="sidebar-menu">
                    <li class="menu-item {{ Request::is('dashboard*') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="menu-link" title="{{ __('app.nav.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>{{ __('app.nav.dashboard') }}</span>
                        </a>
                    </li>
                    <li class="menu-divider"></li>
                    <li class="menu-item has-submenu {{ Request::is('admin/tournaments*') || Request::is('admin/games*') || Request::is('admin/teams*') || Request::is('admin/users*') || Request::is('admin/honor*') || Request::is('admin/marketplace*') || Request::is('honor*') ? 'open' : '' }}" id="managerMenu">
                        <a href="#" class="menu-link" onclick="event.preventDefault(); toggleSubmenu('managerMenu');" title="Manager">
                            <i class="fas fa-briefcase"></i>
                            <span>Manager</span>
                        </a>
                        <ul class="menu-submenu">
                            <li class="menu-item {{ Request::is('admin/tournaments*') ? 'active' : '' }}">
                                <a href="{{ route('admin.tournaments.index') }}" class="menu-link" title="{{ __('app.profile.manage_tournaments') }}">
                                    <i class="fas fa-trophy"></i>
                                    <span>{{ __('app.profile.manage_tournaments') }}</span>
                                </a>
                            </li>
                            <li class="menu-item {{ Request::is('admin/games*') ? 'active' : '' }}">
                                <a href="{{ route('admin.games.index') }}" class="menu-link" title="{{ __('app.profile.manage_games') }}">
                                    <i class="fas fa-gamepad"></i>
                                    <span>{{ __('app.profile.manage_games') }}</span>
                                </a>
                            </li>
                            <li class="menu-item {{ Request::is('admin/teams*') ? 'active' : '' }}">
                                <a href="{{ route('admin.teams.index') }}" class="menu-link" title="{{ __('app.profile.manage_teams') }}">
                                    <i class="fas fa-users-cog"></i>
                                    <span>{{ __('app.profile.manage_teams') }}</span>
                                </a>
                            </li>
                            <li class="menu-item {{ Request::is('admin/users*') ? 'active' : '' }}">
                                <a href="{{ route('admin.users.index') }}" class="menu-link" title="{{ __('app.profile.manage_users') }}">
                                    <i class="fas fa-users"></i>
                                    <span>{{ __('app.profile.manage_users') }}</span>
                                </a>
                            </li>
                            @if(Auth::user()->isSuperAdmin())
                            <li class="menu-item {{ Request::is('admin/admins*') ? 'active' : '' }}">
                                <a href="{{ route('admin.admins.index') }}" class="menu-link" title="{{ __('app.nav.manage_admin') }}">
                                    <i class="fas fa-user-shield"></i>
                                    <span>{{ __('app.nav.manage_admin') }}</span>
                                </a>
                            </li>
                            @endif
                            <li class="menu-item {{ Request::is('admin/honor*') || Request::is('honor*') ? 'active' : '' }}">
                                <a href="{{ route('admin.honor.index') }}" class="menu-link" title="{{ __('app.honor.manage_title') }}">
                                    <i class="fas fa-trophy"></i>
                                    <span>{{ __('app.honor.manage_title') }}</span>
                                </a>
                            </li>
                            <li class="menu-item {{ Request::is('admin/marketplace*') ? 'active' : '' }}">
                                <a href="{{ route('admin.marketplace.index') }}" class="menu-link" title="{{ __('app.nav.manage_marketplace') }}">
                                    <i class="fas fa-store"></i>
                                    <span>{{ __('app.nav.manage_marketplace') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-divider"></li>
                    <li class="menu-item {{ Request::is('admin/system*') || Request::is('admin/settings*') ? 'active' : '' }}">
                        <a href="{{ route('admin.system.settings') }}" class="menu-link" title="Setting">
                            <i class="fas fa-sliders-h"></i>
                            <span>Setting</span>
                        </a>
                    </li>
                    <li class="menu-divider"></li>
                    <li class="menu-item">
                        <a href="{{ route('profile.show') }}" class="menu-link" title="{{ __('app.profile.personal_info') }}">
                            <i class="fas fa-id-card"></i>
                            <span>{{ __('app.profile.personal_info') }}</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('marketplace.inventory') }}" class="menu-link" title="{{ __('app.marketplace.inventory') }}">
                            <i class="fas fa-box"></i>
                            <span>{{ __('app.marketplace.inventory') }}</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <form method="POST" action="{{ route('auth.logout') }}" class="menu-form">
                            @csrf
                            <button type="submit" class="menu-link menu-link-logout" title="{{ __('app.auth.logout') }}">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>{{ __('app.auth.logout') }}</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user-info">
                    <div class="user-avatar">
                        @if(Auth::user()->avatar)
                            <img src="{{ get_avatar_url(Auth::user()->avatar) }}" alt="Avatar">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                    </div>
                    <div class="user-details">
                        <div class="user-name">{{ Auth::user()->name ?? 'User' }}</div>
                    </div>
                </div>
                <div class="language-switcher-sidebar">
                    <div class="relative">
                        <button class="w-full px-3 py-2 text-sm border border-[rgba(0,229,255,0.3)] rounded-lg text-white/90 hover:bg-[rgba(0,229,255,0.1)] hover:border-[#00E5FF] transition-all flex items-center justify-center gap-2" type="button" id="sidebarLanguageToggle" title="{{ strtoupper(app()->getLocale()) }}">
                            <i class="fas fa-globe text-[#00E5FF]"></i>
                            <span>{{ strtoupper(app()->getLocale()) }}</span>
                            <i class="fas fa-chevron-up text-xs ml-auto opacity-60"></i>
                        </button>
                        <div class="absolute bottom-full left-0 right-0 mb-2 hidden z-50" id="sidebarLanguageMenu">
                            <div class="language-dropdown-container">
                                <div class="language-dropdown-list">
                                    <a class="language-item language-switch {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="#" data-locale="en">
                                        <div class="language-flag">
                                            <span class="flag-icon">🇺🇸</span>
                                        </div>
                                        <div class="language-info">
                                            <span class="language-name">English</span>
                                            <span class="language-code">EN</span>
                                        </div>
                                        @if(app()->getLocale() === 'en')
                                        <div class="language-check">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        @endif
                                    </a>
                                    <a class="language-item language-switch {{ app()->getLocale() === 'vi' ? 'active' : '' }}" href="#" data-locale="vi">
                                        <div class="language-flag">
                                            <span class="flag-icon">🇻🇳</span>
                                        </div>
                                        <div class="language-info">
                                            <span class="language-name">Tiếng Việt</span>
                                            <span class="language-code">VI</span>
                                        </div>
                                        @if(app()->getLocale() === 'vi')
                                        <div class="language-check">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        @endif
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Admin Top Bar (Compact) -->
        <div class="admin-topbar">
            <button class="sidebar-toggle-mobile lg:hidden" id="sidebarToggleMobile">
                <i class="fas fa-bars"></i>
            </button>
            <div class="topbar-search">
                @auth
                <div id="navbarSearchWrapper" class="relative">
                    <button type="button" class="px-3 py-1.5 text-sm bg-white text-gray-800 rounded hover:bg-gray-100 transition-colors" id="navbarSearchToggle" title="{{ __('app.search.search') }}" aria-expanded="false">
                        <i class="fas fa-magnifying-glass"></i>
                    </button>
                    <div id="navbarSearchBox" class="absolute right-0 top-full mt-2" style="display:none; z-index: 100000;">
                        <div class="search-panel">
                            <div class="search-input-wrap">
                                <i class="fas fa-magnifying-glass text-gray-500"></i>
                                <input id="navbarSearchInput" class="search-input" placeholder="{{ __('app.search.search_users_teams_tournaments_games') }}" />
                                <div id="navbarSearchLoading" class="search-loading"></div>
                                <button id="navbarSearchClear" class="search-clear" title="{{ __('app.search.clear') }}"><i class="fas fa-xmark"></i></button>
                                <span class="ml-2 hidden md:inline text-gray-500" title="{{ __('app.search.shortcut') }}"><span class="search-kbd">/</span> <span class="search-kbd">Enter</span></span>
                            </div>
                            <div id="navbarSearchResults" class="search-results"></div>
                            <div class="p-2 border-top" id="navbarSearchFooter" style="display:none;">
                                <a id="navbarSearchSeeAll" class="block w-full px-3 py-1.5 text-sm border border-blue-500 text-blue-500 rounded hover:bg-blue-500 hover:text-white transition-colors text-center">{{ __('app.search.see_all_results') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    @else
        <!-- Navbar Mới - Đơn Giản -->
        <nav class="gameon-navbar">
            <div class="container">
                <!-- Brand -->
                <a href="{{ route('home') }}" class="gameon-brand">
                    <div class="gameon-brand-icon">
                        <img src="{{ asset('logo_remove_bg.png') }}" alt="{{ __('app.name') }}" class="w-8 h-8 object-contain">
                    </div>
                    <div class="gameon-brand-text">
                        <div class="gameon-brand-name">{{ __('app.name') }}</div>
                        <div class="gameon-brand-tagline">{{ __('app.tagline') }}</div>
                    </div>
                </a>

                <!-- Desktop: Nav Links - Hidden, moved to dropdown -->
                <div class="gameon-nav-links hidden">
                    @auth
                        @if(!Request::is('dashboard*'))
                        <a href="{{ route('dashboard') }}" class="gameon-nav-link">
                            <i class="fas fa-gauge-high"></i>
                            <span>{{ __('app.nav.dashboard') }}</span>
                        </a>
                        @endif
                        @if(Auth::user()->user_role === 'participant')
                        <a href="{{ route('teams.index') }}" class="gameon-nav-link">
                            <i class="fas fa-users"></i>
                            <span>{{ __('app.nav.my_teams') }}</span>
                        </a>
                        @endif
                        <a href="{{ route('chat.index') }}" class="gameon-nav-link">
                            <i class="fas fa-comments"></i>
                            <span>{{ __('app.nav.chat') }}</span>
                        </a>
                        <a href="{{ route('posts.index') }}" class="gameon-nav-link">
                            <i class="fas fa-newspaper"></i>
                            <span>{{ __('app.nav.posts') }}</span>
                        </a>
                        <a href="{{ route('marketplace.index') }}" class="gameon-nav-link">
                            <i class="fas fa-store"></i>
                            <span>Marketplace</span>
                        </a>
                        <a href="{{ route('profile.show') }}" class="gameon-nav-link">
                            <i class="fas fa-id-card"></i>
                            <span>{{ __('app.profile.personal_info') }}</span>
                        </a>
                    @endauth
                </div>

                <!-- Right Side: Language, Search, User -->
                <div class="gameon-user-menu">
                    <!-- Language Dropdown -->
                    <div class="relative" id="languageDropdown">
                        <button type="button" class="gameon-nav-link flex items-center gap-2" id="languageDropdownToggle" aria-expanded="false" aria-haspopup="true" title="{{ strtoupper(app()->getLocale()) }}" style="border: none; background: none; cursor: pointer; text-decoration: none; padding: 0.5rem 1rem;">
                            <i class="fas fa-globe"></i>
                            <span class="text-sm">{{ strtoupper(app()->getLocale()) }}</span>
                        </button>
                        <div class="absolute right-0 top-full mt-2 hidden z-[10000]" id="languageDropdownMenu" aria-labelledby="languageDropdownToggle" style="min-width: 200px;">
                            <div class="language-dropdown-container">
                                <div class="language-dropdown-header">
                                    <i class="fas fa-globe"></i>
                                    <span>{{ __('app.language.select_language') ?? 'Select Language' }}</span>
                                </div>
                                <div class="language-dropdown-list">
                                    <a class="language-item language-switch {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="#" data-locale="en">
                                        <div class="language-flag">
                                            <span class="flag-icon">🇺🇸</span>
                                        </div>
                                        <div class="language-info">
                                            <span class="language-name">English</span>
                                            <span class="language-code">EN</span>
                                        </div>
                                        @if(app()->getLocale() === 'en')
                                        <div class="language-check">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        @endif
                                    </a>
                                    <a class="language-item language-switch {{ app()->getLocale() === 'vi' ? 'active' : '' }}" href="#" data-locale="vi">
                                        <div class="language-flag">
                                            <span class="flag-icon">🇻🇳</span>
                                        </div>
                                        <div class="language-info">
                                            <span class="language-name">Tiếng Việt</span>
                                            <span class="language-code">VI</span>
                                        </div>
                                        @if(app()->getLocale() === 'vi')
                                        <div class="language-check">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        @endif
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search -->
                    @auth
                    <div class="relative" id="navbarSearchWrapper">
                        <button type="button" class="gameon-nav-link" id="navbarSearchToggle" style="border: none; background: none; cursor: pointer;" title="{{ __('app.search.search') }}">
                            <i class="fas fa-magnifying-glass"></i>
                        </button>
                        <div id="navbarSearchBox" class="absolute right-0 top-full mt-2" style="display:none; z-index: 100000;">
                            <div class="search-panel">
                                <div class="search-input-wrap">
                                    <i class="fas fa-magnifying-glass text-gray-500"></i>
                                    <input id="navbarSearchInput" class="search-input" placeholder="{{ __('app.search.search_users_teams_tournaments_games') }}" />
                                    <div id="navbarSearchLoading" class="search-loading"></div>
                                    <button id="navbarSearchClear" class="search-clear" title="{{ __('app.search.clear') }}"><i class="fas fa-xmark"></i></button>
                                </div>
                                <div id="navbarSearchResults" class="search-results"></div>
                            </div>
                        </div>
                    </div>
                    @endauth

                    <!-- Cart Icon -->
                    @auth
                    <a href="{{ route('marketplace.cart') }}" class="gameon-nav-link cart-icon-btn" style="position: relative;" title="{{ __('app.nav.cart') }}">
                        <i class="fas fa-shopping-cart"></i>
                        @php
                            $cartCount = session('cart') ? count(session('cart')) : 0;
                        @endphp
                        @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                        @endif
                    </a>
                    @endauth

                    <!-- Notification Bell -->
                    @auth
                    <div class="relative" id="notificationBell">
                        <button type="button" class="gameon-nav-link notification-bell-btn" id="notificationToggle" style="border: none; background: none; cursor: pointer; position: relative;" title="{{ __('app.notifications.title') }}">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge" id="notificationCount" style="display: none;">0</span>
                        </button>
                        <div id="notificationDropdown" class="notification-dropdown-desktop hidden">
                            <div class="notification-dropdown-header">
                                <span>{{ __('app.notifications.title') }}</span>
                                <button id="markAllRead" class="notification-mark-read-btn">{{ __('app.notifications.mark_all_read') }}</button>
                            </div>
                            <div id="notificationList" class="notification-dropdown-list">
                                <div class="notification-empty-state">
                                    <i class="fas fa-bell-slash"></i>
                                    <span>{{ __('app.notifications.no_notifications') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endauth

                    <!-- User Menu -->
                    @auth
                    <div class="relative" id="userMenuDropdown">
                        <a href="#" class="gameon-nav-link" id="userMenuToggle" style="text-decoration: none;">
                            <div class="gameon-user-avatar">
                                @if(Auth::user()->avatar)
                                    <img src="{{ get_avatar_url(Auth::user()->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                @else
                                    <i class="fas fa-user"></i>
                                @endif
                            </div>
                            <div class="gameon-user-info hidden lg:block">
                                <div class="gameon-user-name">{{ Auth::user()->name ?? 'User' }}</div>
                                <div class="gameon-user-role">{{ ucfirst(str_replace('_', ' ', Auth::user()->user_role ?? 'user')) }}</div>
                            </div>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="absolute right-0 top-full mt-2 hidden bg-[#0d1b2a] border border-[rgba(0,229,255,0.2)] rounded-lg py-2 min-w-[220px] z-[10000] shadow-lg" id="userMenuDropdownMenu" style="padding-left: 0; margin: 0; list-style: none;">
                            <!-- Main Navigation Items -->
                            @if(!Request::is('dashboard*'))
                            <li class="list-none">
                                <a href="{{ route('dashboard') }}" class="gameon-dropdown-item">
                                    <i class="fas fa-gauge-high"></i>
                                    <span>{{ __('app.nav.dashboard') }}</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->user_role === 'participant')
                            <li class="list-none">
                                <a href="{{ route('teams.index') }}" class="gameon-dropdown-item">
                                    <i class="fas fa-users"></i>
                                    <span>{{ __('app.nav.my_teams') }}</span>
                                </a>
                            </li>
                            @endif
                            <li class="list-none">
                                <a href="{{ route('chat.index') }}" class="gameon-dropdown-item">
                                    <i class="fas fa-comments"></i>
                                    <span>{{ __('app.nav.chat') }}</span>
                                </a>
                            </li>
                            <li class="list-none">
                                <a href="{{ route('posts.index') }}" class="gameon-dropdown-item">
                                    <i class="fas fa-newspaper"></i>
                                    <span>{{ __('app.nav.posts') }}</span>
                                </a>
                            </li>
                            <li class="list-none">
                                <a href="{{ route('marketplace.index') }}" class="gameon-dropdown-item">
                                    <i class="fas fa-store"></i>
                                    <span>Marketplace</span>
                                </a>
                            </li>
                            <li class="list-none">
                                <a href="{{ route('marketplace.inventory') }}" class="gameon-dropdown-item">
                                    <i class="fas fa-box"></i>
                                    <span>{{ __('app.marketplace.inventory') }}</span>
                                </a>
                            </li>
                            <li class="list-none">
                                <a href="{{ route('marketplace.orders') }}" class="gameon-dropdown-item">
                                    <i class="fas fa-receipt"></i>
                                    <span>{{ __('app.marketplace.order_history') }}</span>
                                </a>
                            </li>
                            <li class="list-none">
                                <a href="{{ route('profile.show') }}" class="gameon-dropdown-item">
                                    <i class="fas fa-id-card"></i>
                                    <span>{{ __('app.profile.personal_info') }}</span>
                                </a>
                            </li>
                            <li class="list-none">
                                <a href="{{ route('profile.settings') }}" class="gameon-dropdown-item">
                                    <i class="fas fa-cog"></i>
                                    <span>{{ __('app.profile.account_settings') }}</span>
                                </a>
                            </li>
                            
                            <!-- Admin Section -->
                            @if(Auth::user()->user_role === 'admin' || Auth::user()->user_role === 'super_admin')
                            <li class="list-none">
                                <hr class="border-t border-[rgba(0,229,255,0.2)] my-2">
                            </li>
                            <li class="list-none px-3 py-1">
                                <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('app.profile.admin_area') ?? 'Admin' }}</span>
                            </li>
                            <li class="list-none">
                                <a href="{{ route('admin.games.index') }}" class="gameon-dropdown-item">
                                    <i class="fas fa-gamepad"></i>
                                    <span>{{ __('app.profile.manage_games') }}</span>
                                </a>
                            </li>
                            <li class="list-none">
                                <a href="{{ route('admin.tournaments.index') }}" class="gameon-dropdown-item">
                                    <i class="fas fa-trophy"></i>
                                    <span>{{ __('app.profile.manage_tournaments') }}</span>
                                </a>
                            </li>
                            <li class="list-none">
                                <a href="{{ route('admin.teams.index') }}" class="gameon-dropdown-item">
                                    <i class="fas fa-users-gear"></i>
                                    <span>{{ __('app.profile.manage_teams') }}</span>
                                </a>
                            </li>
                            <li class="list-none">
                                <a href="{{ route('admin.users.index') }}" class="gameon-dropdown-item">
                                    <i class="fas fa-users"></i>
                                    <span>{{ __('app.profile.manage_users') }}</span>
                                </a>
                            </li>
                            @if(Auth::user()->isSuperAdmin())
                            <li class="list-none">
                                <a href="{{ route('admin.admins.index') }}" class="gameon-dropdown-item">
                                    <i class="fas fa-user-shield"></i>
                                    <span>{{ __('app.nav.manage_admin') }}</span>
                                </a>
                            </li>
                            @endif
                            @endif
                            
                            <!-- Logout -->
                            <li class="list-none">
                                <hr class="border-t border-[rgba(0,229,255,0.2)] my-2">
                            </li>
                            <li class="list-none">
                                <form method="POST" action="{{ route('auth.logout') }}">
                                    @csrf
                                    <button type="submit" class="gameon-dropdown-item w-full text-left">
                                        <i class="fas fa-right-from-bracket"></i>
                                        <span>{{ __('app.auth.logout') }}</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @else
                    <!-- Desktop: Login/Register buttons -->
                    <a href="{{ route('auth.login') }}" class="gameon-nav-link hidden lg:flex">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>{{ __('app.auth.login') }}</span>
                    </a>
                    <a href="{{ route('auth.register') }}" class="gameon-nav-link hidden lg:flex" style="background: rgba(0, 229, 255, 0.1); border: 1px solid rgba(0, 229, 255, 0.3);">
                        <i class="fas fa-user-plus"></i>
                        <span>{{ __('app.auth.register') }}</span>
                    </a>
                    @endauth
                    
                    <!-- Mobile Icons (Search, Cart) -->
                    <div class="flex items-center gap-3 lg:hidden">
                        @auth
                        <!-- Mobile Search -->
                        <button type="button" class="mobile-icon-btn" id="mobileSearchToggle" title="{{ __('app.search.search') }}">
                            <i class="fas fa-magnifying-glass"></i>
                        </button>
                        
                        <!-- Mobile Cart -->
                        <a href="{{ route('marketplace.cart') }}" class="mobile-icon-btn" style="position: relative;" title="{{ __('app.nav.cart') }}">
                            <i class="fas fa-shopping-cart"></i>
                            @php
                                $mobileCartCount = session('cart') ? count(session('cart')) : 0;
                            @endphp
                            @if($mobileCartCount > 0)
                            <span class="mobile-cart-badge">{{ $mobileCartCount }}</span>
                            @endif
                        </a>
                        @endauth
                    </div>
                    
                    <!-- Mobile: Hamburger Menu Button -->
                    <button class="mobile-menu-toggle lg:hidden" id="mobileMenuToggle" aria-label="Toggle menu">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Mobile Slide Menu Overlay -->
        <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
        
        <!-- Mobile Slide Menu -->
        <div class="mobile-slide-menu" id="mobileSlideMenu">
            <!-- Menu Header -->
            <div class="mobile-menu-header">
                <div class="mobile-menu-brand">
                    <img src="{{ asset('logo_remove_bg.png') }}" alt="{{ __('app.name') }}" class="w-10 h-10 object-contain">
                    <div>
                        <div class="text-white font-bold text-lg">{{ __('app.name') }}</div>
                        <div class="text-slate-400 text-xs">{{ __('app.tagline') }}</div>
                    </div>
                </div>
                <div class="mobile-menu-header-actions">
                    @auth
                    <!-- Notification Bell for Mobile -->
                    <div class="mobile-notification-wrapper">
                        <button class="mobile-notification-btn" id="mobileNotificationToggle">
                            <i class="fas fa-bell"></i>
                            <span class="mobile-notification-badge" id="mobileNotificationBadge" style="display: none;">0</span>
                        </button>
                        <div class="mobile-notification-dropdown" id="mobileNotificationDropdown">
                            <div class="mobile-notification-header">
                                <span>{{ __('app.notifications.title') }}</span>
                                <button id="mobileMarkAllRead" class="mobile-mark-all-read">{{ __('app.notifications.mark_all_read') }}</button>
                            </div>
                            <div class="mobile-notification-list" id="mobileNotificationList">
                                <div class="mobile-notification-empty">
                                    <i class="fas fa-bell-slash"></i>
                                    <span>{{ __('app.notifications.no_notifications') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endauth
                    <button class="mobile-menu-close" id="mobileMenuClose" aria-label="Close menu">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <!-- Menu Content -->
            <div class="mobile-menu-content">
                @auth
                <!-- User Info -->
                <div class="mobile-user-info">
                    <div class="mobile-user-avatar">
                        @if(Auth::user()->avatar)
                            <img src="{{ get_avatar_url(Auth::user()->avatar) }}" alt="Avatar">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                    </div>
                    <div class="mobile-user-details">
                        <div class="mobile-user-name">{{ Auth::user()->name ?? 'User' }}</div>
                        <div class="mobile-user-role">{{ ucfirst(str_replace('_', ' ', Auth::user()->user_role ?? 'user')) }}</div>
                    </div>
                </div>
                
                <div class="mobile-menu-divider"></div>
                
                <!-- Navigation Links -->
                <nav class="mobile-menu-nav">
                    <a href="{{ route('home') }}" class="mobile-menu-item {{ Request::is('/') || Request::is('home') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>{{ __('app.nav.home') }}</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="mobile-menu-item {{ Request::is('dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-gauge-high"></i>
                        <span>{{ __('app.nav.dashboard') }}</span>
                    </a>
                    @if(Route::has('tournaments.index'))
                    <a href="{{ route('tournaments.index') }}" class="mobile-menu-item {{ Request::is('tournaments*') ? 'active' : '' }}">
                        <i class="fas fa-trophy"></i>
                        <span>{{ __('app.nav.tournaments') }}</span>
                    </a>
                    @endif
                    @if(Auth::user()->user_role === 'participant' && Route::has('teams.index'))
                    <a href="{{ route('teams.index') }}" class="mobile-menu-item {{ Request::is('teams*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>{{ __('app.nav.my_teams') }}</span>
                    </a>
                    @endif
                    @if(Route::has('chat.index'))
                    <a href="{{ route('chat.index') }}" class="mobile-menu-item {{ Request::is('chat*') ? 'active' : '' }}">
                        <i class="fas fa-comments"></i>
                        <span>{{ __('app.nav.chat') }}</span>
                    </a>
                    @endif
                    @if(Route::has('posts.index'))
                    <a href="{{ route('posts.index') }}" class="mobile-menu-item {{ Request::is('posts*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper"></i>
                        <span>{{ __('app.nav.posts') }}</span>
                    </a>
                    @endif
                    @if(Route::has('marketplace.index'))
                    <a href="{{ route('marketplace.index') }}" class="mobile-menu-item {{ Request::is('marketplace') ? 'active' : '' }}">
                        <i class="fas fa-store"></i>
                        <span>Marketplace</span>
                    </a>
                    @endif
                    @if(Route::has('marketplace.inventory'))
                    <a href="{{ route('marketplace.inventory') }}" class="mobile-menu-item {{ Request::is('marketplace/inventory*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i>
                        <span>{{ __('app.marketplace.inventory') }}</span>
                    </a>
                    @endif
                    @if(Route::has('marketplace.orders'))
                    <a href="{{ route('marketplace.orders') }}" class="mobile-menu-item {{ Request::is('marketplace/orders*') ? 'active' : '' }}">
                        <i class="fas fa-receipt"></i>
                        <span>{{ __('app.marketplace.order_history') }}</span>
                    </a>
                    @endif
                    @if(Route::has('profile.show'))
                    <a href="{{ route('profile.show') }}" class="mobile-menu-item {{ Request::is('profile') ? 'active' : '' }}">
                        <i class="fas fa-id-card"></i>
                        <span>{{ __('app.profile.personal_info') }}</span>
                    </a>
                    @endif
                    @if(Route::has('profile.settings'))
                    <a href="{{ route('profile.settings') }}" class="mobile-menu-item {{ Request::is('profile/settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>{{ __('app.profile.account_settings') }}</span>
                    </a>
                    @endif
                </nav>
                
                @if(Auth::user()->user_role === 'admin' || Auth::user()->user_role === 'super_admin')
                <div class="mobile-menu-divider"></div>
                <div class="mobile-menu-section-title">Quản trị</div>
                <nav class="mobile-menu-nav">
                    <a href="{{ route('admin.games.index') }}" class="mobile-menu-item">
                        <i class="fas fa-gamepad"></i>
                        <span>{{ __('app.profile.manage_games') }}</span>
                    </a>
                    <a href="{{ route('admin.tournaments.index') }}" class="mobile-menu-item">
                        <i class="fas fa-trophy"></i>
                        <span>{{ __('app.profile.manage_tournaments') }}</span>
                    </a>
                    <a href="{{ route('admin.teams.index') }}" class="mobile-menu-item">
                        <i class="fas fa-users-gear"></i>
                        <span>{{ __('app.profile.manage_teams') }}</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="mobile-menu-item">
                        <i class="fas fa-users"></i>
                        <span>{{ __('app.profile.manage_users') }}</span>
                    </a>
                    @if(Auth::user()->isSuperAdmin())
                    <a href="{{ route('admin.admins.index') }}" class="mobile-menu-item">
                        <i class="fas fa-user-shield"></i>
                        <span>{{ __('app.nav.manage_admin') }}</span>
                    </a>
                    @endif
                </nav>
                @endif
                
                <div class="mobile-menu-divider"></div>
                
                <!-- Language Switcher -->
                <div class="mobile-menu-section-title">{{ __('app.language.language') }}</div>
                <div class="mobile-language-switcher">
                    <a href="#" class="mobile-lang-btn language-switch {{ app()->getLocale() === 'vi' ? 'active' : '' }}" data-locale="vi">
                        <i class="fas fa-flag"></i> VI
                    </a>
                    <a href="#" class="mobile-lang-btn language-switch {{ app()->getLocale() === 'en' ? 'active' : '' }}" data-locale="en">
                        <i class="fas fa-flag-usa"></i> EN
                    </a>
                </div>
                
                <div class="mobile-menu-divider"></div>
                
                <!-- Logout -->
                <form method="POST" action="{{ route('auth.logout') }}" class="mobile-logout-form">
                    @csrf
                    <button type="submit" class="mobile-menu-item mobile-logout-btn">
                        <i class="fas fa-right-from-bracket"></i>
                        <span>{{ __('app.auth.logout') }}</span>
                    </button>
                </form>
                
                @else
                <!-- Guest Menu -->
                <nav class="mobile-menu-nav">
                    <a href="/" class="mobile-menu-item {{ Request::is('/') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>{{ __('app.nav.home') }}</span>
                    </a>
                    @if(Route::has('tournaments.index'))
                    <a href="{{ route('tournaments.index') }}" class="mobile-menu-item {{ Request::is('tournaments*') ? 'active' : '' }}">
                        <i class="fas fa-trophy"></i>
                        <span>{{ __('app.nav.tournaments') }}</span>
                    </a>
                    @endif
                </nav>
                
                <div class="mobile-menu-divider"></div>
                
                <!-- Language Switcher -->
                <div class="mobile-menu-section-title">{{ __('app.language.language') }}</div>
                <div class="mobile-language-switcher">
                    <a href="#" class="mobile-lang-btn language-switch {{ app()->getLocale() === 'vi' ? 'active' : '' }}" data-locale="vi">
                        <i class="fas fa-flag"></i> VI
                    </a>
                    <a href="#" class="mobile-lang-btn language-switch {{ app()->getLocale() === 'en' ? 'active' : '' }}" data-locale="en">
                        <i class="fas fa-flag-usa"></i> EN
                    </a>
                </div>
                
                <div class="mobile-menu-divider"></div>
                
                <!-- Auth Buttons -->
                <div class="mobile-auth-buttons">
                    @if(Route::has('auth.login'))
                    <a href="{{ route('auth.login') }}" class="mobile-auth-btn mobile-login-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>{{ __('app.auth.login') }}</span>
                    </a>
                    @endif
                    @if(Route::has('auth.register'))
                    <a href="{{ route('auth.register') }}" class="mobile-auth-btn mobile-register-btn">
                        <i class="fas fa-user-plus"></i>
                        <span>{{ __('app.auth.register') }}</span>
                    </a>
                    @endif
                </div>
                @endauth
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="content-wrapper {{ $showAdminSidebar ? 'content-wrapper-with-sidebar' : '' }}">
        @if(session('success'))
        <div id="session-success-toast" style="position: fixed; top: 100px; left: 50%; transform: translateX(-50%); z-index: 9999; animation: slideDown 0.4s ease-out;">
            <div style="background: linear-gradient(135deg, rgba(0, 229, 255, 0.15) 0%, rgba(34, 197, 94, 0.15) 100%); border: 1px solid rgba(0, 229, 255, 0.5); color: #00E5FF; padding: 16px 24px; border-radius: 12px; display: flex; align-items: center; gap: 12px; box-shadow: 0 8px 32px rgba(0, 229, 255, 0.3), 0 0 20px rgba(0, 229, 255, 0.1); backdrop-filter: blur(10px); font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 500; min-width: 300px; max-width: 90vw;" role="alert">
                <div style="width: 32px; height: 32px; background: rgba(0, 229, 255, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-check" style="font-size: 14px;"></i>
                </div>
                <span style="flex: 1;">{{ session('success') }}</span>
                <button type="button" style="background: none; border: none; color: rgba(0, 229, 255, 0.7); cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; transition: color 0.2s ease;" onclick="this.closest('#session-success-toast').remove()" onmouseover="this.style.color='#00E5FF'" onmouseout="this.style.color='rgba(0, 229, 255, 0.7)'">
                    <i class="fas fa-times" style="font-size: 16px;"></i>
                </button>
            </div>
        </div>
        <style>
            @keyframes slideDown {
                from { opacity: 0; transform: translateX(-50%) translateY(-20px); }
                to { opacity: 1; transform: translateX(-50%) translateY(0); }
            }
        </style>
        <script>
            setTimeout(function() {
                var toast = document.getElementById('session-success-toast');
                if (toast) {
                    toast.style.animation = 'slideUp 0.3s ease-out forwards';
                    toast.insertAdjacentHTML('beforeend', '<style>@keyframes slideUp { to { opacity: 0; transform: translateX(-50%) translateY(-20px); } }</style>');
                    setTimeout(function() { toast.remove(); }, 300);
                }
            }, 5000);
        </script>
        @endif

        @if(session('error'))
        <div id="session-error-toast" style="position: fixed; top: 100px; left: 50%; transform: translateX(-50%); z-index: 9999; animation: slideDownError 0.4s ease-out;">
            <div style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.15) 100%); border: 1px solid rgba(239, 68, 68, 0.5); color: #f87171; padding: 16px 24px; border-radius: 12px; display: flex; align-items: center; gap: 12px; box-shadow: 0 8px 32px rgba(239, 68, 68, 0.3), 0 0 20px rgba(239, 68, 68, 0.1); backdrop-filter: blur(10px); font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 500; min-width: 300px; max-width: 90vw;" role="alert">
                <div style="width: 32px; height: 32px; background: rgba(239, 68, 68, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 14px;"></i>
                </div>
                <span style="flex: 1;">{{ session('error') }}</span>
                <button type="button" style="background: none; border: none; color: rgba(239, 68, 68, 0.7); cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; transition: color 0.2s ease;" onclick="this.closest('#session-error-toast').remove()" onmouseover="this.style.color='#f87171'" onmouseout="this.style.color='rgba(239, 68, 68, 0.7)'">
                    <i class="fas fa-times" style="font-size: 16px;"></i>
                </button>
            </div>
        </div>
        <style>
            @keyframes slideDownError {
                from { opacity: 0; transform: translateX(-50%) translateY(-20px); }
                to { opacity: 1; transform: translateX(-50%) translateY(0); }
            }
        </style>
        <script>
            setTimeout(function() {
                var toast = document.getElementById('session-error-toast');
                if (toast) {
                    toast.style.animation = 'slideUpError 0.3s ease-out forwards';
                    toast.insertAdjacentHTML('beforeend', '<style>@keyframes slideUpError { to { opacity: 0; transform: translateX(-50%) translateY(-20px); } }</style>');
                    setTimeout(function() { toast.remove(); }, 300);
                }
            }, 5000);
        </script>
        @endif

        @yield('content')
    </main>

    <!-- Global Toast Container -->
    <div id="app-toast-container"></div>

    <!-- Removed legacy Confirm Modal (use SweetAlert2 in JS wrapper) -->

    <!-- Modern Footer - Show for guests OR on index page for authenticated users -->
    @if(auth()->guest() || Request::is('/'))
    <footer style="background: linear-gradient(180deg, #000814 0%, #000022 100%); border-top: 1px solid rgba(0, 229, 255, 0.2); padding: 60px 0 30px 0; position: relative; overflow: hidden;">
        <!-- Background Glow Effects -->
        <div style="position: absolute; top: -100px; left: 10%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(0, 229, 255, 0.1) 0%, transparent 70%); pointer-events: none;"></div>
        <div style="position: absolute; bottom: -100px; right: 10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(0, 0, 85, 0.3) 0%, transparent 70%); pointer-events: none;"></div>
        
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px; position: relative; z-index: 10;">
            <!-- Main Footer Content -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 48px; margin-bottom: 48px;">
                
                <!-- Brand Section -->
                <div>
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                        <img src="{{ asset('logo_remove_bg.png') }}" alt="{{ __('app.name') }}" style="width: 48px; height: 48px; object-fit: contain;">
                        <span style="font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: 28px; color: white;">
                            GAME <span style="color: #00E5FF;">ON</span>
                        </span>
                    </div>
                    <p style="color: #94a3b8; font-size: 14px; line-height: 1.6; margin-bottom: 24px;">
                        {{ __('app.footer.professional_esports_management_platform') }}
                    </p>
                    <!-- Social Icons -->
                    <div style="display: flex; gap: 12px;">
                        <a href="#" style="width: 44px; height: 44px; border-radius: 12px; background: rgba(0, 229, 255, 0.1); border: 1px solid rgba(0, 229, 255, 0.3); display: flex; align-items: center; justify-content: center; color: #00E5FF; text-decoration: none; transition: all 0.3s ease;">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                        </a>
                        <a href="#" style="width: 44px; height: 44px; border-radius: 12px; background: rgba(88, 101, 242, 0.1); border: 1px solid rgba(88, 101, 242, 0.3); display: flex; align-items: center; justify-content: center; color: #5865F2; text-decoration: none; transition: all 0.3s ease;">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 00-.0785-.037 19.7363 19.7363 0 00-4.8852 1.515.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 01.0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 01.0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 01-.0066.1276 12.2986 12.2986 0 01-1.873.8914.0766.0766 0 00-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286zM8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.9555 2.4189-2.1569 2.4189zm7.9748 0c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9554-2.4189 2.1569-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.946 2.4189-2.1568 2.4189Z"/></svg>
                        </a>
                        <a href="#" style="width: 44px; height: 44px; border-radius: 12px; background: rgba(255, 0, 0, 0.1); border: 1px solid rgba(255, 0, 0, 0.3); display: flex; align-items: center; justify-content: center; color: #FF0000; text-decoration: none; transition: all 0.3s ease;">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    </div>
                </div>
                
                <!-- Features Section -->
                <div>
                    <h3 style="font-family: 'Rajdhani', sans-serif; font-weight: 700; color: white; font-size: 18px; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 24px; padding-left: 16px; border-left: 3px solid #00E5FF;">
                        {{ __('app.footer.features') }}
                    </h3>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 12px;">
                            <a href="{{ route('tournaments.index') }}" style="color: #94a3b8; text-decoration: none; font-size: 14px; transition: color 0.3s ease; display: flex; align-items: center; gap: 8px;">
                                <span style="color: #00E5FF;">›</span> {{ __('app.nav.tournaments') }}
                            </a>
                        </li>
                        <li style="margin-bottom: 12px;">
                            <a href="{{ route('teams.index') }}" style="color: #94a3b8; text-decoration: none; font-size: 14px; transition: color 0.3s ease; display: flex; align-items: center; gap: 8px;">
                                <span style="color: #00E5FF;">›</span> {{ __('app.nav.my_teams') }}
                            </a>
                        </li>
                        <li style="margin-bottom: 12px;">
                            <a href="{{ route('chat.index') }}" style="color: #94a3b8; text-decoration: none; font-size: 14px; transition: color 0.3s ease; display: flex; align-items: center; gap: 8px;">
                                <span style="color: #00E5FF;">›</span> {{ __('app.nav.chat') }}
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Support Section -->
                <div>
                    <h3 style="font-family: 'Rajdhani', sans-serif; font-weight: 700; color: white; font-size: 18px; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 24px; padding-left: 16px; border-left: 3px solid #00E5FF;">
                        {{ __('app.footer.support') }}
                    </h3>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 12px;">
                            <a href="#" style="color: #94a3b8; text-decoration: none; font-size: 14px; transition: color 0.3s ease; display: flex; align-items: center; gap: 8px;">
                                <span style="color: #00E5FF;">›</span> {{ __('app.footer.help_center') }}
                            </a>
                        </li>
                        <li style="margin-bottom: 12px;">
                            <a href="#" style="color: #94a3b8; text-decoration: none; font-size: 14px; transition: color 0.3s ease; display: flex; align-items: center; gap: 8px;">
                                <span style="color: #00E5FF;">›</span> {{ __('app.footer.contact') }}
                            </a>
                        </li>
                        <li style="margin-bottom: 12px;">
                            <a href="#" style="color: #94a3b8; text-decoration: none; font-size: 14px; transition: color 0.3s ease; display: flex; align-items: center; gap: 8px;">
                                <span style="color: #00E5FF;">›</span> {{ __('app.footer.bug_report') }}
                            </a>
                        </li>
                        <li style="margin-bottom: 12px;">
                            <a href="#" style="color: #94a3b8; text-decoration: none; font-size: 14px; transition: color 0.3s ease; display: flex; align-items: center; gap: 8px;">
                                <span style="color: #00E5FF;">›</span> {{ __('app.footer.faq') }}
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Newsletter Section -->
                <div>
                    <h3 style="font-family: 'Rajdhani', sans-serif; font-weight: 700; color: white; font-size: 18px; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 24px; padding-left: 16px; border-left: 3px solid #00E5FF;">
                        {{ __('app.footer.connect') }}
                    </h3>
                    <p style="color: #94a3b8; font-size: 14px; margin-bottom: 16px;">{{ __('app.footer.subscribe_newsletter') }}</p>
                    
                    <form style="display: flex; gap: 8px; margin-bottom: 20px;">
                        <input type="email" placeholder="{{ __('app.footer.your_email') }}" 
                            style="flex: 1; background: rgba(0, 8, 20, 0.8); border: 1px solid rgba(0, 229, 255, 0.2); border-radius: 10px; padding: 12px 16px; color: white; font-size: 14px; outline: none;">
                        <button type="submit" style="background: linear-gradient(135deg, #00E5FF, #0099cc); border: none; border-radius: 10px; padding: 12px 20px; color: #000; font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: 14px; text-transform: uppercase; cursor: pointer; transition: all 0.3s ease;">
                            {{ __('app.footer.send') }}
                        </button>
                    </form>
                    
                    <!-- App Download Buttons -->
                    <div style="display: flex; gap: 12px;">
                        <a href="#" style="flex: 1; background: rgba(0, 8, 20, 0.8); border: 1px solid rgba(0, 229, 255, 0.2); border-radius: 10px; padding: 10px 12px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; transition: all 0.3s ease;">
                            <svg width="20" height="20" fill="white" viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                            <div style="text-align: left;">
                                <div style="font-size: 10px; color: #94a3b8; line-height: 1;">Download on</div>
                                <div style="font-size: 13px; color: white; font-weight: 600; font-family: 'Rajdhani', sans-serif;">App Store</div>
                            </div>
                        </a>
                        <a href="#" style="flex: 1; background: rgba(0, 8, 20, 0.8); border: 1px solid rgba(0, 229, 255, 0.2); border-radius: 10px; padding: 10px 12px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; transition: all 0.3s ease;">
                            <svg width="18" height="18" fill="white" viewBox="0 0 24 24"><path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.53,12.9 20.18,13.18L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/></svg>
                            <div style="text-align: left;">
                                <div style="font-size: 10px; color: #94a3b8; line-height: 1;">Get it on</div>
                                <div style="font-size: 13px; color: white; font-weight: 600; font-family: 'Rajdhani', sans-serif;">Google Play</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div style="border-top: 1px solid rgba(0, 229, 255, 0.1); padding-top: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div style="color: #64748b; font-size: 13px;">
                    &copy; {{ date('Y') }} <span style="color: white; font-weight: 600;">Game On</span>. All rights reserved.
                </div>
                <div style="display: flex; gap: 24px;">
                    <a href="#" style="color: #64748b; text-decoration: none; font-size: 13px; transition: color 0.3s ease;">{{ __('app.footer.terms_of_service') }}</a>
                    <a href="#" style="color: #64748b; text-decoration: none; font-size: 13px; transition: color 0.3s ease;">{{ __('app.footer.privacy_policy') }}</a>
                    <a href="#" style="color: #64748b; text-decoration: none; font-size: 13px; transition: color 0.3s ease;">{{ __('app.footer.cookie_policy') }}</a>
                </div>
                <div style="color: #64748b; font-size: 13px; display: flex; align-items: center; gap: 6px;">
                    Made with <span style="color: #ef4444;">❤</span> by <span style="color: #00E5FF; font-weight: 600;">GameOn Team</span>
                </div>
            </div>
        </div>
        
        <!-- Responsive Styles -->
        <style>
            /* Footer mobile responsive - 2 columns layout */
            @media (max-width: 1024px) {
                footer > div > div:first-of-type {
                    grid-template-columns: repeat(2, 1fr) !important;
                    gap: 24px !important;
                }
            }
            @media (max-width: 640px) {
                /* Keep 2 columns for Features and Support on mobile */
                footer > div > div:first-of-type {
                    grid-template-columns: repeat(2, 1fr) !important;
                    gap: 16px !important;
                }
                /* Hide Brand and Newsletter sections on mobile */
                footer > div > div:first-of-type > div:first-child,
                footer > div > div:first-of-type > div:last-child {
                    display: none !important;
                }
                /* Adjust footer bottom */
                footer > div > div:last-of-type {
                    flex-direction: column !important;
                    text-align: center !important;
                    gap: 12px !important;
                }
                /* Smaller text on mobile */
                footer h3 {
                    font-size: 14px !important;
                    margin-bottom: 16px !important;
                }
                footer ul li {
                    margin-bottom: 8px !important;
                }
                footer ul li a {
                    font-size: 13px !important;
                }
            }
        </style>
    </footer>
    @endif

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Optimized loading control to prevent FOUC
        document.addEventListener('DOMContentLoaded', function() {
            // Check if this is a pagination request (has query parameters like users_page)
            const urlParams = new URLSearchParams(window.location.search);
            const isPaginationRequest = urlParams.has('users_page') || urlParams.has('page');
            
            // Hide loading screen immediately for pagination requests
            if (isPaginationRequest) {
                // Prevent browser from auto-scrolling to top
                if ('scrollRestoration' in history) {
                    history.scrollRestoration = 'manual';
                }
                
                const loadingScreen = document.getElementById('loadingScreen');
                if (loadingScreen) {
                    loadingScreen.style.display = 'none';
                    loadingScreen.style.zIndex = '-1';
                    loadingScreen.classList.add('hidden');
                }
                const contentWrapper = document.querySelector('.content-wrapper');
                if (contentWrapper) {
                    contentWrapper.classList.add('loaded');
                }
                return;
            }
            
            // Check if all critical resources are loaded
            const checkResourcesLoaded = () => {
                const stylesheets = document.querySelectorAll('link[rel="preload"][as="style"]');
                let loadedCount = 0;
                
                stylesheets.forEach(link => {
                    if (link.sheet || link.onload) {
                        loadedCount++;
                    }
                });
                
                // If most stylesheets are loaded or timeout reached, show content
                return loadedCount >= stylesheets.length * 0.8 || performance.now() > 2000;
            };
            
            const showContent = () => {
                const loadingScreen = document.getElementById('loadingScreen');
                if (loadingScreen) {
                    loadingScreen.classList.add('hidden');
                    // Force hide loading screen after transition
                    setTimeout(() => {
                        loadingScreen.style.display = 'none';
                        loadingScreen.style.zIndex = '-1';
                    }, 500);
                }
                
                // Show content
                const contentWrapper = document.querySelector('.content-wrapper');
                if (contentWrapper) {
                    contentWrapper.classList.add('loaded');
                }
            };
            
            // Check immediately and then periodically
            if (checkResourcesLoaded()) {
                showContent();
            } else {
                const checkInterval = setInterval(() => {
                    if (checkResourcesLoaded()) {
                        clearInterval(checkInterval);
                        showContent();
                    }
                }, 100);
                
                // Fallback timeout
                setTimeout(() => {
                    clearInterval(checkInterval);
                    showContent();
                }, 1500);
                
                // Emergency fallback - force hide loading screen after 3 seconds
                setTimeout(() => {
                    const loadingScreen = document.getElementById('loadingScreen');
                    if (loadingScreen) {
                        loadingScreen.style.display = 'none';
                        loadingScreen.style.zIndex = '-1';
                        loadingScreen.classList.add('hidden');
                    }
                }, 3000);
            }
        });
        
        AOS.init({ duration: 800, once: true });

        // Global notify API (namespaced). Will not override existing window.notify.
        window.esmNotify = function(type, message, options = {}){
            const id = 'toast-' + Date.now();
            const container = document.getElementById('app-toast-container');
            const div = document.createElement('div');
            const classes = {
                success: 'app-toast app-toast-success',
                error: 'app-toast app-toast-error',
                info: 'app-toast app-toast-info',
                warning: 'app-toast app-toast-warning'
            };
            div.className = classes[type] || classes.info;
            div.id = id;
            const icon = document.createElement('i');
            icon.className = `fas ${type==='success'?'fa-check-circle':type==='error'?'fa-exclamation-circle':type==='warning'?'fa-exclamation-triangle':'fa-info-circle'}`;
            const text = document.createElement('span');
            let msg = message;
            if (typeof msg === 'function') { msg = ''; }
            if (typeof msg !== 'string') {
                if (msg && typeof msg.message === 'string') msg = msg.message; else msg = String(msg ?? '');
            }
            if (msg.trim().startsWith('function(') || msg.trim().startsWith('(function')) { msg = ''; }
            text.textContent = msg;
            div.appendChild(icon);
            div.appendChild(text);
            container.appendChild(div);
            const duration = options.duration || 3000;
            setTimeout(()=>{ div.classList.add('fade'); div.style.opacity = '0'; }, duration-300);
            setTimeout(()=>{ div.remove(); }, duration);
        };
        if(!window.notify){ window.notify = window.esmNotify; }

        // Global confirm dialog API
        // Disable confirmation prompts globally: always resolve "true" without UI
        window.confirmDialog = function(){ return Promise.resolve(true); };

        // No bootstrap confirm modal anymore; SweetAlert2 is used.

        // Navbar search interactions (for all users)
        (function(){
            const toggle = document.getElementById('navbarSearchToggle');
            const box = document.getElementById('navbarSearchBox');
            const input = document.getElementById('navbarSearchInput');
            const results = document.getElementById('navbarSearchResults');
            const loading = document.getElementById('navbarSearchLoading');
            const clearBtn = document.getElementById('navbarSearchClear');
            const footer = document.getElementById('navbarSearchFooter');
            const seeAll = document.getElementById('navbarSearchSeeAll');
            if(!toggle) return;

            let originalParent = null;
            let isSearchOpen = false;
            
            const openBox = ()=>{
                if(!box) return;
                const toggleRect = toggle.getBoundingClientRect();
                if(!originalParent){ originalParent = box.parentElement; }
                if(box.parentElement !== document.body){ document.body.appendChild(box); }
                
                const isMobile = window.innerWidth <= 768;
                
                if(isMobile) {
                    box.style.position = 'fixed';
                    box.style.top = toggleRect.top + toggleRect.height + 10 + 'px';
                    box.style.left = '10px';
                    box.style.right = '10px';
                    box.style.width = 'auto';
                } else {
                    const searchBoxWidth = 560;
                    let leftPosition = toggleRect.left - searchBoxWidth - 10;
                    if(leftPosition < 10) leftPosition = toggleRect.right + 10;
                    if(leftPosition + searchBoxWidth > window.innerWidth - 10) {
                        leftPosition = (window.innerWidth - searchBoxWidth) / 2;
                    }
                    box.style.position = 'fixed';
                    box.style.top = toggleRect.top + 'px';
                    box.style.left = leftPosition + 'px';
                    box.style.right = 'auto';
                    box.style.width = '';
                }
                
                box.style.transform = 'scale(0.3)';
                box.style.opacity = '0';
                box.style.display = 'block';
                box.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                isSearchOpen = true;
                toggle.setAttribute('aria-expanded','true');
                
                setTimeout(() => {
                    box.style.transform = 'scale(1)';
                    box.style.opacity = '1';
                }, 10);
                setTimeout(()=>input && input.focus(), 350);
            };
            
            const closeBox = ()=>{ 
                if(!box) return;
                isSearchOpen = false;
                box.style.transform = 'scale(0.3)';
                box.style.opacity = '0';
                setTimeout(() => {
                    box.style.display = 'none';
                    toggle.setAttribute('aria-expanded','false'); 
                    if(originalParent && box.parentElement === document.body){ 
                        originalParent.appendChild(box); 
                    }
                }, 300);
            };

            toggle.addEventListener('click', function(e){
                e.preventDefault();
                e.stopPropagation();
                if(isSearchOpen) closeBox(); else openBox();
            });
            
            document.addEventListener('click', function(e){
                if(!box || !isSearchOpen) return;
                if(box.contains(e.target) || toggle.contains(e.target)) return;
                closeBox();
            });
            
            document.addEventListener('keydown', function(e){ 
                if(e.key === 'Escape' && isSearchOpen) closeBox();
                // Keyboard shortcut: '/' to focus search
                if(e.key === '/' && !e.ctrlKey && !e.metaKey && !e.altKey && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA'){
                    e.preventDefault(); openBox(); input && input.focus();
                }
            });

            let timer;
            function triggerSearch(q){
                if(loading) loading.style.display = 'inline-block';
                fetch(`{{ route('search') }}?q=${encodeURIComponent(q)}`)
                    .then(r=>r.json())
                    .then(renderResults)
                    .catch(()=>{})
                    .finally(()=>{ if(loading) loading.style.display = 'none'; });
            }
            
            input && input.addEventListener('input', function(){
                const q = this.value.trim();
                clearTimeout(timer);
                if(q === ''){ results.innerHTML=''; if(footer) footer.style.display='none'; return; }
                timer = setTimeout(()=>{ triggerSearch(q); }, 250);
            });

            clearBtn && clearBtn.addEventListener('click', function(){
                if(!input) return; input.value=''; results.innerHTML=''; if(footer) footer.style.display='none'; input.focus();
            });

            input && input.addEventListener('keydown', function(e){
                if(e.key === 'Enter'){
                    e.preventDefault();
                    const q = input.value.trim();
                    const first = results.querySelector('.result-item');
                    // Ctrl/Shift + Enter: open first result
                    if((e.ctrlKey || e.shiftKey) && first){
                        const href = first.getAttribute('href');
                        if(href && href !== '#') { window.location.href = href; return; }
                    }
                    if(q){ window.location.href = `{{ route('search.view') }}?q=${encodeURIComponent(q)}`; }
                }
            });

            function section(title, items, color){
                if(!items || items.length===0) return '';
                const links = items.map(it=>`
                    <a class="result-item" href="${it.url}">
                        <span class="result-icon" style="background:${color}"><i class="fas ${iconByType(it.type)}"></i></span>
                        <span>
                            <div class="fw-semibold">${escapeHtml(it.name)}</div>
                            <div class="result-meta">${it.type}</div>
                        </span>
                    </a>`).join('');
                return `<div class="result-section-title">${title}</div>${links}`;
            }
            
            function renderResults(data){
                const i18n = {
                    users: "{{ __('app.search.users') }}",
                    teams: "{{ __('app.search.teams') }}",
                    tournaments: "{{ __('app.search.tournaments') }}",
                    games: "{{ __('app.search.games') }}",
                    empty: "{{ __('app.search.no_results_found') }}"
                };
                const html =
                    section(i18n.users, data.users, '#60a5fa') +
                    section(i18n.teams, data.teams, '#22c55e') +
                    section(i18n.tournaments, data.tournaments, '#f59e0b') +
                    section(i18n.games, data.games, '#a78bfa');
                results.innerHTML = html || `<div class="empty-state"><i class="fas fa-search"></i> ${i18n.empty}</div>`;
                
                // Update footer "See all results"
                if(footer && seeAll){
                    const q = input ? input.value.trim() : '';
                    footer.style.display = q ? 'block' : 'none';
                    seeAll.setAttribute('href', `{{ route('search.view') }}?q=${encodeURIComponent(q)}`);
                }
                
                // Mark first item for Enter navigation
                results.querySelectorAll('.result-item').forEach((el, idx)=>{
                    el.classList.toggle('active-result', idx===0);
                });
            }

            function iconByType(type){
                return type==='user'?'fa-user':type==='team'?'fa-users':type==='tournament'?'fa-trophy':type==='game'?'fa-gamepad':'fa-circle';
            }
            
            function escapeHtml(str){
                return String(str).replace(/[&<>"]/g, s=>({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;"}[s]));
            }
        })();
        
        // Mobile Search Toggle
        (function(){
            const mobileSearchToggle = document.getElementById('mobileSearchToggle');
            const desktopSearchToggle = document.getElementById('navbarSearchToggle');
            
            if(mobileSearchToggle && desktopSearchToggle) {
                mobileSearchToggle.addEventListener('click', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    // Trigger the desktop search toggle click
                    desktopSearchToggle.click();
                });
            }
        })();


        // Admin Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('adminSidebar');
            const sidebarToggleMobile = document.getElementById('sidebarToggleMobile');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');
            
            function toggleSidebar() {
                if (sidebar) {
                    sidebar.classList.toggle('show');
                    if (sidebarBackdrop) {
                        sidebarBackdrop.classList.toggle('show');
                    }
                }
            }
            
            function closeSidebar() {
                if (sidebar) {
                    sidebar.classList.remove('show');
                    if (sidebarBackdrop) {
                        sidebarBackdrop.classList.remove('show');
                    }
                }
            }
            
            if (sidebarToggleMobile) {
                sidebarToggleMobile.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleSidebar();
                });
            }
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeSidebar();
                });
            }
            
            if (sidebarBackdrop) {
                sidebarBackdrop.addEventListener('click', function() {
                    closeSidebar();
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 992 && sidebar && sidebar.classList.contains('show')) {
                    if (!sidebar.contains(event.target) && 
                        sidebarToggleMobile && 
                        !sidebarToggleMobile.contains(event.target)) {
                        closeSidebar();
                    }
                }
            });
        });
        
        // Sidebar submenu toggle function
        function toggleSubmenu(menuId) {
            const menuItem = document.getElementById(menuId);
            if (menuItem) {
                menuItem.classList.toggle('open');
            }
        }
        
        // Auto-open submenu if any child is active
        document.addEventListener('DOMContentLoaded', function() {
            const submenuItems = document.querySelectorAll('.menu-item.has-submenu');
            submenuItems.forEach(function(item) {
                const activeChild = item.querySelector('.menu-submenu .menu-item.active');
                if (activeChild) {
                    item.classList.add('open');
                }
            });
        });
        
        // Mobile menu toggle functionality - REMOVED OLD CODE, using new slide menu instead
        
        // User Menu Dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuToggle = document.getElementById('userMenuToggle');
            const userMenuDropdown = document.getElementById('userMenuDropdownMenu');
            
            if (userMenuToggle && userMenuDropdown) {
                userMenuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const isOpen = userMenuDropdown.classList.contains('show');
                    
                    // Close all other dropdowns
                    closeAllDropdowns();
                    
                    // Toggle user menu
                    if (!isOpen) {
                        userMenuDropdown.classList.remove('hidden');
                        userMenuDropdown.classList.add('block', 'show');
                        userMenuToggle.setAttribute('aria-expanded', 'true');
                    } else {
                        userMenuDropdown.classList.add('hidden');
                        userMenuDropdown.classList.remove('block', 'show');
                        userMenuToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }
            
            // Sidebar Language Dropdown
            const sidebarLanguageToggle = document.getElementById('sidebarLanguageToggle');
            const sidebarLanguageMenu = document.getElementById('sidebarLanguageMenu');
            
            if (sidebarLanguageToggle && sidebarLanguageMenu) {
                sidebarLanguageToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const isOpen = sidebarLanguageMenu.classList.contains('show');
                    
                    // Close all other dropdowns
                    closeAllDropdowns();
                    
                    // Toggle sidebar language menu
                    if (!isOpen) {
                        sidebarLanguageMenu.classList.remove('hidden');
                        sidebarLanguageMenu.classList.add('block', 'show');
                    } else {
                        sidebarLanguageMenu.classList.add('hidden');
                        sidebarLanguageMenu.classList.remove('block', 'show');
                    }
                });
            }
            
            // Close all dropdowns function
            function closeAllDropdowns() {
                const allDropdowns = document.querySelectorAll('#languageDropdownMenu, #languageDropdownMobileMenu, #userMenuDropdownMenu, #sidebarLanguageMenu');
                const allToggles = document.querySelectorAll('#languageDropdownToggle, #languageDropdownMobileToggle, #userMenuToggle, #sidebarLanguageToggle');
                
                allDropdowns.forEach(function(menu) {
                    menu.classList.add('hidden');
                    menu.classList.remove('block', 'show');
                });
                
                allToggles.forEach(function(toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                });
            }
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                const languageDropdown = document.getElementById('languageDropdown');
                const languageDropdownMobile = document.getElementById('languageDropdownMobile');
                const userMenuDropdown = document.getElementById('userMenuDropdown');
                const sidebarLanguageDropdown = document.querySelector('.language-switcher-sidebar');
                
                if ((languageDropdown && !languageDropdown.contains(e.target)) || 
                    (languageDropdownMobile && !languageDropdownMobile.contains(e.target)) ||
                    (userMenuDropdown && !userMenuDropdown.contains(e.target)) ||
                    (sidebarLanguageDropdown && !sidebarLanguageDropdown.contains(e.target))) {
                    closeAllDropdowns();
                }
            });
        });
        
        // Language switcher functionality
        document.addEventListener('DOMContentLoaded', function() {
            const languageToggle = document.getElementById('languageDropdownToggle');
            const languageToggleMobile = document.getElementById('languageDropdownMobileToggle');
            const languageMenu = document.getElementById('languageDropdownMenu');
            const languageMenuMobile = document.getElementById('languageDropdownMobileMenu');
            
            // Function to toggle dropdown using Tailwind classes
            function toggleDropdown(menu, toggle) {
                if (!menu || !toggle) return;
                
                const isOpen = menu.classList.contains('show');
                
                // Close all dropdowns first
                closeLanguageDropdowns();
                
                // Toggle this dropdown using Tailwind classes
                if (!isOpen) {
                    menu.classList.remove('hidden');
                    menu.classList.add('block', 'show');
                    toggle.setAttribute('aria-expanded', 'true');
                } else {
                    menu.classList.add('hidden');
                    menu.classList.remove('block', 'show');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            }
            
            // Function to close dropdown using Tailwind classes
            function closeLanguageDropdowns() {
                const dropdowns = document.querySelectorAll('#languageDropdownMenu, #languageDropdownMobileMenu');
                const toggles = document.querySelectorAll('#languageDropdownToggle, #languageDropdownMobileToggle');
                
                dropdowns.forEach(function(menu) {
                    menu.classList.add('hidden');
                    menu.classList.remove('block', 'show');
                });
                
                toggles.forEach(function(toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                });
            }
            
            // Toggle dropdown when clicking button
            if (languageToggle && languageMenu) {
                languageToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleDropdown(languageMenu, languageToggle);
                });
            }
            
            if (languageToggleMobile && languageMenuMobile) {
                languageToggleMobile.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleDropdown(languageMenuMobile, languageToggleMobile);
                });
            }
            
            
            // Note: Close dropdown when clicking outside is handled in user menu dropdown section above
            
            const languageSwitches = document.querySelectorAll('.language-switch');
            
            languageSwitches.forEach(function(switchElement) {
                switchElement.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Close dropdown before switching
                    closeLanguageDropdowns();
                    
                    const locale = this.getAttribute('data-locale');
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    // Show loading state
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Switching...';
                    
                    fetch('{{ route("language.switch") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            locale: locale
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Reload page to apply new language
                            window.location.reload();
                        } else {
                            // Restore original text on error
                            this.innerHTML = originalText;
                            console.error('Language switch failed:', data.message);
                            alert('Có lỗi xảy ra khi chuyển đổi ngôn ngữ: ' + data.message);
                        }
                    })
                    .catch(error => {
                        // Restore original text on error
                        this.innerHTML = originalText;
                        console.error('Language switch error:', error);
                        
                        // Check if it's a JSON parsing error (likely HTML error page)
                        if (error.message.includes('Unexpected token')) {
                            alert('Server đang gặp vấn đề. Vui lòng thử lại sau.');
                        } else {
                            alert('Có lỗi xảy ra khi chuyển đổi ngôn ngữ. Vui lòng thử lại.');
                        }
                    });
                });
            });
        });
    </script>

    <!-- Mobile Slide Menu JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
            const mobileSlideMenu = document.getElementById('mobileSlideMenu');
            const mobileMenuClose = document.getElementById('mobileMenuClose');
            
            if (!mobileMenuToggle || !mobileSlideMenu) return;
            
            function openMobileMenu() {
                mobileMenuToggle.classList.add('active');
                mobileMenuOverlay.classList.add('active');
                mobileSlideMenu.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            
            function closeMobileMenu() {
                mobileMenuToggle.classList.remove('active');
                mobileMenuOverlay.classList.remove('active');
                mobileSlideMenu.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            // Toggle menu
            mobileMenuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                if (mobileSlideMenu.classList.contains('active')) {
                    closeMobileMenu();
                } else {
                    openMobileMenu();
                }
            });
            
            // Close button
            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', closeMobileMenu);
            }
            
            // Close on overlay click
            if (mobileMenuOverlay) {
                mobileMenuOverlay.addEventListener('click', closeMobileMenu);
            }
            
            // Close on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && mobileSlideMenu.classList.contains('active')) {
                    closeMobileMenu();
                }
            });
            
            // Close menu when clicking a link
            const menuLinks = mobileSlideMenu.querySelectorAll('a:not(.language-switch)');
            menuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    closeMobileMenu();
                });
            });
            
            // Handle swipe to close
            let touchStartX = 0;
            let touchEndX = 0;
            
            mobileSlideMenu.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });
            
            mobileSlideMenu.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                if (touchEndX - touchStartX > 50) {
                    closeMobileMenu();
                }
            }, { passive: true });
        });
    </script>

    <!-- User Status Check Script -->
    @auth
    <script>
        // Check user status on page load and sync with server
        (function() {
            const userStatus = '{{ Auth::user()->status }}';
            if (['suspended', 'banned', 'deleted'].includes(userStatus)) {
                sessionStorage.setItem('userRestricted', userStatus);
            } else {
                sessionStorage.removeItem('userRestricted');
            }
        })();
    </script>
    @endauth

    <!-- Notification Bell Script -->
    @auth
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Desktop elements
            const notificationToggle = document.getElementById('notificationToggle');
            const notificationDropdown = document.getElementById('notificationDropdown');
            const notificationCount = document.getElementById('notificationCount');
            const notificationList = document.getElementById('notificationList');
            const markAllReadBtn = document.getElementById('markAllRead');
            
            // Mobile elements
            const mobileNotificationToggle = document.getElementById('mobileNotificationToggle');
            const mobileNotificationDropdown = document.getElementById('mobileNotificationDropdown');
            const mobileNotificationBadge = document.getElementById('mobileNotificationBadge');
            const mobileNotificationList = document.getElementById('mobileNotificationList');
            const mobileMarkAllRead = document.getElementById('mobileMarkAllRead');
            
            let notifications = [];
            let unreadCount = 0;
            
            // Toggle desktop dropdown
            if (notificationToggle && notificationDropdown) {
                notificationToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    notificationDropdown.classList.toggle('hidden');
                });
                
                document.addEventListener('click', function(e) {
                    if (!notificationToggle.contains(e.target) && !notificationDropdown.contains(e.target)) {
                        notificationDropdown.classList.add('hidden');
                    }
                });
            }
            
            // Toggle mobile dropdown
            if (mobileNotificationToggle && mobileNotificationDropdown) {
                mobileNotificationToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    mobileNotificationDropdown.classList.toggle('show');
                });
                
                document.addEventListener('click', function(e) {
                    if (!mobileNotificationToggle.contains(e.target) && !mobileNotificationDropdown.contains(e.target)) {
                        mobileNotificationDropdown.classList.remove('show');
                    }
                });
            }
            
            // Mark all as read - Desktop
            if (markAllReadBtn) {
                markAllReadBtn.addEventListener('click', function() {
                    markAllAsRead();
                });
            }
            
            // Mark all as read - Mobile
            if (mobileMarkAllRead) {
                mobileMarkAllRead.addEventListener('click', function() {
                    markAllAsRead();
                });
            }
            
            function markAllAsRead() {
                fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        notifications.forEach(n => n.read = true);
                        unreadCount = 0;
                        updateBadge();
                        renderNotifications();
                    }
                })
                .catch(err => console.error('Error marking all as read:', err));
            }
            
            // Update badge (both desktop and mobile)
            function updateBadge() {
                const displayCount = unreadCount > 99 ? '99+' : unreadCount;
                
                // Desktop
                if (notificationCount) {
                    if (unreadCount > 0) {
                        notificationCount.textContent = displayCount;
                        notificationCount.style.display = 'flex';
                    } else {
                        notificationCount.style.display = 'none';
                    }
                }
                
                // Mobile
                if (mobileNotificationBadge) {
                    if (unreadCount > 0) {
                        mobileNotificationBadge.textContent = displayCount;
                        mobileNotificationBadge.style.display = 'flex';
                    } else {
                        mobileNotificationBadge.style.display = 'none';
                    }
                }
            }
            
            // Render notifications (both desktop and mobile)
            function renderNotifications() {
                const emptyHtml = `
                    <div class="notification-empty-state">
                        <i class="fas fa-bell-slash"></i>
                        <span>Không có thông báo mới</span>
                    </div>
                `;
                
                const itemsHtml = notifications.slice(0, 10).map(n => `
                    <div class="notification-item ${n.read ? '' : 'unread'}" data-id="${n.id}" data-url="${n.url || '#'}">
                        <div class="notification-avatar">
                            ${n.avatar ? `<img src="${n.avatar}" alt="">` : `<i class="fas fa-${n.icon || 'bell'}"></i>`}
                        </div>
                        <div class="notification-content">
                            <div class="notification-text">${n.message}</div>
                            <div class="notification-time">${n.time}</div>
                        </div>
                    </div>
                `).join('');
                
                // Desktop
                if (notificationList) {
                    notificationList.innerHTML = notifications.length === 0 ? emptyHtml : itemsHtml;
                    bindNotificationClicks(notificationList);
                }
                
                // Mobile
                if (mobileNotificationList) {
                    mobileNotificationList.innerHTML = notifications.length === 0 ? emptyHtml : itemsHtml;
                    bindNotificationClicks(mobileNotificationList);
                }
            }
            
            function bindNotificationClicks(container) {
                container.querySelectorAll('.notification-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const url = this.dataset.url;
                        const id = this.dataset.id;
                        
                        const notif = notifications.find(n => n.id == id);
                        if (notif && !notif.read) {
                            notif.read = true;
                            unreadCount = Math.max(0, unreadCount - 1);
                            updateBadge();
                        }
                        
                        if (url && url !== '#') {
                            window.location.href = url;
                        }
                    });
                });
            }
            
            // Add notification
            function addNotification(data) {
                // Check if notification is disabled for this conversation
                const conversationId = data.conversationId;
                if (conversationId && localStorage.getItem(`chat_notification_${conversationId}`) === 'disabled') {
                    // Still add to list but don't play sound or show browser notification
                    const notif = {
                        id: Date.now(),
                        message: data.message,
                        avatar: data.avatar || null,
                        icon: data.icon || 'comment',
                        url: data.url || '#',
                        time: 'Vừa xong',
                        read: false
                    };
                    notifications.unshift(notif);
                    if (notifications.length > 50) notifications.pop();
                    unreadCount++;
                    updateBadge();
                    renderNotifications();
                    return;
                }
                
                const notif = {
                    id: Date.now(),
                    message: data.message,
                    avatar: data.avatar || null,
                    icon: data.icon || 'comment',
                    url: data.url || '#',
                    time: 'Vừa xong',
                    read: false
                };
                
                notifications.unshift(notif);
                if (notifications.length > 50) notifications.pop();
                unreadCount++;
                updateBadge();
                renderNotifications();
                
                // Play notification sound
                try {
                    const audio = new Audio('/matchfound.mp3');
                    audio.volume = 0.85;
                    audio.play().catch(() => {});
                } catch (e) {}
                
                // Show browser notification if permitted
                if (Notification.permission === 'granted') {
                    new Notification('Tin nhắn mới', {
                        body: data.message.replace(/<[^>]*>/g, ''),
                        icon: data.avatar || '/logo_remove_bg.png'
                    });
                } else if (Notification.permission !== 'denied') {
                    Notification.requestPermission();
                }
            }
            
            // Listen for real-time notifications via Laravel Echo
            function setupEchoNotifications() {
                if (typeof window.Echo === 'undefined') {
                    setTimeout(setupEchoNotifications, 500);
                    return;
                }
                
                const userId = {{ Auth::id() }};
                
                window.Echo.private(`user.${userId}`)
                    .listen('.chat.message', (e) => {
                        // Don't notify if sender is current user
                        if (e.sender_id === userId) {
                            return;
                        }
                        
                        // Don't notify if on the same conversation page
                        if (window.location.pathname.includes(`/chat/conversation/${e.conversation_id}`)) {
                            return;
                        }
                        
                        addNotification({
                            conversationId: e.conversation_id,
                            message: `<strong>${e.sender_name}</strong> đã gửi tin nhắn: "${e.content.substring(0, 50)}${e.content.length > 50 ? '...' : ''}"`,
                            avatar: e.sender_avatar,
                            icon: 'comment',
                            url: `/chat/conversation/${e.conversation_id}`
                        });
                    })
                    .listen('.team.invitation', (e) => {
                        // New team invitation received
                        addNotification({
                            message: `<strong>${e.invitation.inviter.name}</strong> mời bạn tham gia đội <strong style="color:#f59e0b;">${e.invitation.team.name}</strong>`,
                            avatar: e.invitation.team.logo,
                            icon: 'users',
                            url: '/teams'
                        });
                        
                        // Show toast notification
                        showTeamInvitationToast(e.invitation);
                    })
                    .listen('.status.changed', (e) => {
                        // User status has been changed by admin
                        showUserStatusPopup(e.status, e.status_display, e.message);
                    });
            }
            
            // Show toast for team invitation
            function showTeamInvitationToast(invitation) {
                const toast = document.createElement('div');
                toast.className = 'team-invitation-toast';
                toast.innerHTML = `
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:45px;height:45px;background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-envelope" style="color:#fff;font-size:1.1rem;"></i>
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:600;color:#fff;margin-bottom:4px;">Lời mời tham gia đội</div>
                            <div style="font-size:0.85rem;color:#94a3b8;">
                                <strong style="color:#00E5FF;">${invitation.inviter.name}</strong> mời bạn tham gia đội <strong style="color:#f59e0b;">${invitation.team.name}</strong>
                            </div>
                        </div>
                    </div>
                    <a href="/teams" style="display:block;margin-top:12px;text-align:center;padding:8px 16px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:8px;color:#fff;text-decoration:none;font-weight:600;font-size:0.85rem;">
                        Xem lời mời
                    </a>
                `;
                toast.style.cssText = `
                    position:fixed;top:80px;right:20px;
                    background:linear-gradient(135deg,#0d1b2a,#000022);
                    border:1px solid rgba(245,158,11,0.5);
                    border-radius:16px;padding:1rem;
                    color:#fff;z-index:99999;
                    box-shadow:0 10px 40px rgba(0,0,0,0.5);
                    max-width:350px;
                    animation:toastSlideIn 0.3s ease;
                `;
                
                if (!document.getElementById('toast-animation-style')) {
                    const style = document.createElement('style');
                    style.id = 'toast-animation-style';
                    style.textContent = `
                        @keyframes toastSlideIn { from{transform:translateX(100%);opacity:0;} to{transform:translateX(0);opacity:1;} }
                        @keyframes toastSlideOut { from{transform:translateX(0);opacity:1;} to{transform:translateX(100%);opacity:0;} }
                    `;
                    document.head.appendChild(style);
                }
                
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.style.animation = 'toastSlideOut 0.3s ease forwards';
                    setTimeout(() => toast.remove(), 300);
                }, 8000);
            }
            
            // Show popup when user status is changed
            function showUserStatusPopup(status, statusDisplay, message) {
                // Remove existing popup if any
                const existingPopup = document.getElementById('user-status-popup');
                if (existingPopup) existingPopup.remove();
                
                const isRestricted = ['suspended', 'banned', 'deleted'].includes(status);
                const iconClass = isRestricted ? 'fa-exclamation-triangle' : 'fa-check-circle';
                const iconColor = isRestricted ? '#ef4444' : '#22c55e';
                const borderColor = isRestricted ? 'rgba(239,68,68,0.5)' : 'rgba(34,197,94,0.5)';
                
                const popup = document.createElement('div');
                popup.id = 'user-status-popup';
                popup.innerHTML = `
                    <div class="status-popup-overlay">
                        <div class="status-popup-content" style="border-color:${borderColor}">
                            <div class="status-popup-icon" style="background:${iconColor}20;color:${iconColor}">
                                <i class="fas ${iconClass}"></i>
                            </div>
                            <h3 class="status-popup-title">Trạng thái tài khoản: ${statusDisplay}</h3>
                            <p class="status-popup-message">${message}</p>
                            ${isRestricted ? '<p class="status-popup-note">Bạn vẫn có thể xem nội dung và chat với quản trị viên để được hỗ trợ.</p>' : ''}
                            <div class="status-popup-actions">
                                ${isRestricted ? '<a href="/chat" class="status-popup-btn-secondary"><i class="fas fa-comments"></i> Chat với Admin</a>' : ''}
                                <button class="status-popup-btn" onclick="closeStatusPopup(${isRestricted})">
                                    ${isRestricted ? 'Tôi đã hiểu' : 'Đóng'}
                                </button>
                            </div>
                        </div>
                    </div>
                    <style>
                        .status-popup-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.85);z-index:999999;display:flex;align-items:center;justify-content:center;animation:fadeIn 0.3s ease;backdrop-filter:blur(4px);}
                        .status-popup-content{background:linear-gradient(135deg,#0d1b2a,#1a1a2e);border:2px solid;border-radius:20px;padding:2rem;max-width:420px;text-align:center;box-shadow:0 25px 60px rgba(0,0,0,0.5);animation:slideUp 0.3s ease;}
                        .status-popup-icon{width:70px;height:70px;margin:0 auto 1.5rem;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:28px;}
                        .status-popup-title{color:#fff;font-family:'Rajdhani',sans-serif;font-size:1.4rem;margin:0 0 1rem;font-weight:700;}
                        .status-popup-message{color:#94a3b8;font-size:0.95rem;line-height:1.6;margin:0 0 1rem;}
                        .status-popup-note{color:#22c55e;font-size:0.85rem;margin:0 0 1.5rem;padding:0.75rem;background:rgba(34,197,94,0.1);border-radius:8px;border:1px solid rgba(34,197,94,0.2);}
                        .status-popup-actions{display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;}
                        .status-popup-btn{padding:0.75rem 1.5rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);border:none;border-radius:10px;color:white;font-weight:600;cursor:pointer;font-size:0.9rem;transition:all 0.3s ease;text-decoration:none;display:inline-flex;align-items:center;gap:0.5rem;}
                        .status-popup-btn:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(99,102,241,0.4);}
                        .status-popup-btn-secondary{padding:0.75rem 1.5rem;background:rgba(0,229,255,0.1);border:1px solid rgba(0,229,255,0.3);border-radius:10px;color:#00E5FF;font-weight:600;cursor:pointer;font-size:0.9rem;transition:all 0.3s ease;text-decoration:none;display:inline-flex;align-items:center;gap:0.5rem;}
                        .status-popup-btn-secondary:hover{background:rgba(0,229,255,0.2);color:#fff;}
                        @keyframes fadeIn{from{opacity:0}to{opacity:1}}
                        @keyframes slideUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
                    </style>
                `;
                document.body.appendChild(popup);
                
                // Store restricted status in session storage
                if (isRestricted) {
                    sessionStorage.setItem('userRestricted', status);
                } else {
                    sessionStorage.removeItem('userRestricted');
                }
            }
            
            window.closeStatusPopup = function(isRestricted) {
                const popup = document.getElementById('user-status-popup');
                if (popup) popup.remove();
                
                // If user was activated, reload to refresh permissions
                if (!isRestricted) {
                    window.location.reload();
                }
            };
            
            // Check if user is restricted on page load
            function checkUserRestriction() {
                const restrictedStatus = sessionStorage.getItem('userRestricted');
                if (restrictedStatus && ['suspended', 'banned', 'deleted'].includes(restrictedStatus)) {
                    // Block form submissions and button clicks (except chat with admin)
                    document.addEventListener('submit', function(e) {
                        if (!isAllowedAction(e.target)) {
                            blockRestrictedAction(e);
                        }
                    }, true);
                    document.addEventListener('click', function(e) {
                        const target = e.target.closest('button, a.btn, .btn-neon, input[type="submit"]');
                        if (target && !target.closest('#user-status-popup') && !isAllowedAction(target)) {
                            blockRestrictedAction(e);
                        }
                    }, true);
                }
            }
            
            // Check if action is allowed for restricted users
            function isAllowedAction(element) {
                // Always allow if on chat page
                if (window.location.pathname.startsWith('/chat')) {
                    return true;
                }
                
                // Allow navigation to chat
                const link = element.closest('a');
                if (link) {
                    const href = link.href || link.getAttribute('href') || '';
                    if (href.includes('/chat')) {
                        return true;
                    }
                }
                
                // Allow closing modals/popups
                if (element.closest('.modal-close, .btn-close, [data-dismiss], [data-bs-dismiss]')) {
                    return true;
                }
                
                return false;
            }
            
            function blockRestrictedAction(e) {
                const restrictedStatus = sessionStorage.getItem('userRestricted');
                if (!restrictedStatus) return;
                
                // Allow navigation links (not buttons styled as links)
                if (e.target.tagName === 'A' && !e.target.classList.contains('btn') && !e.target.classList.contains('btn-neon')) return;
                
                // Check if this is an allowed action
                if (isAllowedAction(e.target)) return;
                
                e.preventDefault();
                e.stopPropagation();
                
                const statusDisplay = restrictedStatus === 'suspended' ? 'Tạm khóa' : 
                                     restrictedStatus === 'banned' ? 'Cấm vĩnh viễn' : 'Đã xóa';
                const message = restrictedStatus === 'suspended' ? 
                    'Tài khoản của bạn đang bị tạm khóa. Bạn không thể thực hiện thao tác này.' :
                    'Tài khoản của bạn đã bị cấm. Bạn không thể thực hiện thao tác này.';
                    
                showUserStatusPopup(restrictedStatus, statusDisplay, message);
            }
            
            checkUserRestriction();
            
            setupEchoNotifications();
            
            // Load notifications from database
            loadNotificationsFromDB();
            
            // Function to load notifications from database
            function loadNotificationsFromDB() {
                fetch('/notifications', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.notifications) {
                        notifications = data.notifications.map(n => ({
                            id: n.id,
                            message: n.message,
                            avatar: n.data?.avatar || null,
                            icon: n.data?.icon || 'bell',
                            url: n.data?.url || '#',
                            time: n.time,
                            read: n.read
                        }));
                        unreadCount = notifications.filter(n => !n.read).length;
                        updateBadge();
                        renderNotifications();
                    }
                })
                .catch(err => console.error('Error loading notifications:', err));
            }
        });
    </script>
    @endauth

    @stack('scripts')
    
    @auth
    <!-- Notification Permission Request -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if browser supports notifications and hasn't been asked recently
        if (!('Notification' in window)) return;
        if (Notification.permission === 'granted') return;
        if (Notification.permission === 'denied') return;
        
        // Check if already asked in this session
        const lastAsked = localStorage.getItem('notificationAsked');
        const now = Date.now();
        if (lastAsked && (now - parseInt(lastAsked)) < 86400000) return; // Don't ask again for 24h
        
        // Show custom prompt after 2 seconds
        setTimeout(function() {
            showNotificationPrompt();
        }, 2000);
        
        function showNotificationPrompt() {
            const overlay = document.createElement('div');
            overlay.id = 'notification-prompt-overlay';
            overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:99999;display:flex;align-items:center;justify-content:center;animation:fadeIn 0.3s ease;';
            
            overlay.innerHTML = `
                <div style="background:linear-gradient(135deg,#0d1b2a,#1a1a2e);border:1px solid rgba(0,229,255,0.3);border-radius:20px;padding:2rem;max-width:400px;text-align:center;box-shadow:0 25px 60px rgba(0,0,0,0.5);animation:slideUp 0.3s ease;">
                    <div style="width:70px;height:70px;margin:0 auto 1.5rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-bell" style="font-size:28px;color:white;"></i>
                    </div>
                    <h3 style="color:#fff;font-family:'Rajdhani',sans-serif;font-size:1.5rem;margin-bottom:0.75rem;">Bật thông báo</h3>
                    <p style="color:#94a3b8;font-size:0.95rem;line-height:1.5;margin-bottom:1.5rem;">Nhận thông báo khi có tin nhắn mới hoặc cập nhật quan trọng từ GameOn!</p>
                    <div style="display:flex;gap:1rem;justify-content:center;">
                        <button id="notif-deny" style="padding:0.75rem 1.5rem;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);border-radius:10px;color:#94a3b8;font-weight:600;cursor:pointer;">Để sau</button>
                        <button id="notif-allow" style="padding:0.75rem 1.5rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);border:none;border-radius:10px;color:white;font-weight:600;cursor:pointer;box-shadow:0 4px 15px rgba(99,102,241,0.4);">Cho phép</button>
                    </div>
                </div>
                <style>
                    @keyframes fadeIn { from{opacity:0} to{opacity:1} }
                    @keyframes slideUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
                </style>
            `;
            
            document.body.appendChild(overlay);
            
            document.getElementById('notif-allow').onclick = function() {
                Notification.requestPermission().then(function(permission) {
                    if (permission === 'granted') {
                        new Notification('Thông báo đã được bật!', {
                            body: 'Bạn sẽ nhận thông báo khi có tin nhắn mới.',
                            icon: '/logo_remove_bg.png'
                        });
                    }
                });
                localStorage.setItem('notificationAsked', Date.now().toString());
                overlay.remove();
            };
            
            document.getElementById('notif-deny').onclick = function() {
                localStorage.setItem('notificationAsked', Date.now().toString());
                overlay.remove();
            };
            
            overlay.onclick = function(e) {
                if (e.target === overlay) {
                    localStorage.setItem('notificationAsked', Date.now().toString());
                    overlay.remove();
                }
            };
        }
    });
    </script>
    @endauth
</body>

</html>