<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('app.name')) - {{ __('app.tagline') }}</title>
    
    <!-- Google Fonts - Rajdhani (Display) & Inter (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">

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
        @if(Request::is('profile*'))
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
        @endif
        
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
    
    <!-- Preload critical resources -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('css/custom.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    @if(Request::is('profile*'))
    <link rel="preload" href="{{ asset('css/profile.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    @endif
    
    <!-- Fallback for browsers that don't support preload -->
    <noscript>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
        @if(Request::is('profile*'))
        <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
        @endif
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
            background: #000055; /* Deep Navy */
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #00E5FF; /* Neon */
            font-size: 1.25rem;
            box-shadow: 0 0 10px rgba(0, 229, 255, 0.4);
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
            display: flex;
            align-items: center;
            gap: 0.5rem;
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

        .gameon-user-menu {
            display: flex;
            align-items: center;
            gap: 0.5rem;
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

        /* Modern Footer Styles */
        .modern-footer {
            position: relative;
            margin-top: 0;
            padding: 1.5rem 0 1.5rem;
            color: white;
            overflow: hidden;
        }

        /* Compact footer for chat pages */
        .chat-page .modern-footer {
            padding: 2.5rem 0 2.5rem;
        }
        .chat-page .footer-title { margin-bottom: 1rem; }
        .chat-page .footer-links li { margin-bottom: 0.5rem; }
        .chat-page .social-links { gap: 0.75rem; margin-bottom: 1rem; }
        .chat-page .social-link { padding: 0.5rem 0.75rem; font-size: 0.85rem; }
        .chat-page .newsletter { margin-top: 1rem; }
        .chat-page .newsletter-form { padding: 0.4rem; }
        .chat-page .newsletter-input { padding: 0.5rem 0.75rem; font-size: 0.85rem; }
        .chat-page .newsletter-btn { width: 40px; height: 40px; }
        .chat-page .footer-bottom { margin-top: 1.5rem; padding-top: 1rem; }
        
        /* Remove footer margin on chat page */
        .chat-page .modern-footer {
            margin-top: 0;
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

        .footer-background {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
        }

        .footer-gradient {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 30%, #0f0f23 70%, #1a1a2e 100%);
        }

        .footer-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(118, 75, 162, 0.1) 0%, transparent 50%);
            background-size: 400px 400px;
            animation: float-pattern 20s ease-in-out infinite;
        }

        @keyframes float-pattern {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(-20px, -20px) rotate(1deg); }
            66% { transform: translate(20px, -10px) rotate(-1deg); }
        }

        .modern-footer .container {
            position: relative;
            z-index: 2;
        }

        .footer-brand {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: .5rem;
        }

        .footer-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .95rem;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .footer-brand-text .brand-title {
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-subtitle {
            color: #94a3b8;
            margin-bottom: 0;
            line-height: 1.5;
        }

        .footer-title {
            color: white;
            font-weight: 600;
            margin-bottom: .75rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .footer-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 30px;
            height: 2px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .footer-links a:hover {
            color: white;
            transform: translateX(5px);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-bottom: .75rem;
            flex-wrap: wrap;
        }

        .social-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.6rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 0.8rem;
        }

        .social-link:hover {
            transform: translateY(-2px);
            color: white;
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .social-link.facebook:hover { border-color: #1877f2; }
        .social-link.twitter:hover { border-color: #1da1f2; }
        .social-link.discord:hover { border-color: #5865f2; }
        .social-link.youtube:hover { border-color: #ff0000; }

        .newsletter { margin-top: .75rem; }

        .newsletter-text {
            color: #94a3b8;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .newsletter-form {
            display: flex;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.3rem;
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .newsletter-input {
            flex: 1;
            background: transparent;
            border: none;
            padding: 0.4rem 0.6rem;
            color: white;
            font-size: 0.8rem;
        }

        .newsletter-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .newsletter-input:focus {
            outline: none;
        }

        .newsletter-btn {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .newsletter-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .footer-bottom {
            margin-top: .75rem;
            padding-top: .75rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .copyright {
            color: #94a3b8;
            margin: 0;
            font-size: 0.9rem;
        }

        .footer-bottom-links {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .footer-bottom-links a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .footer-bottom-links a:hover {
            color: white;
        }

        /* Body offset for fixed header */
        body {
            padding-top: 64px;
        }

        /* Mobile menu */
        #mobileNav {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        #mobileNav.show {
            max-height: 500px;
        }
        
        /* Remove padding-top on welcome page */
        body.welcome-page {
            padding-top: 0;
        }

        /* Admin Sidebar Styles */
        .admin-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 30%, #16213e 70%, #0f0f23 100%);
            backdrop-filter: blur(25px);
            border-right: 1px solid rgba(102, 126, 234, 0.2);
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            overflow: hidden;
            transition: width 0.3s ease;
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
            transition: opacity 0.2s ease, width 0.3s ease;
            display: none;
        }

        /* Khi hover vào sidebar collapsed, hiện text */
        .admin-sidebar.collapsed:hover {
            width: 280px;
            z-index: 1001;
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.5);
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
        }

        /* Ẩn text và arrow trong language switcher khi collapsed */
        .admin-sidebar.collapsed .language-switcher-sidebar .dropdown-toggle::after {
            display: none;
        }

        .admin-sidebar.collapsed .language-switcher-sidebar .btn {
            padding: 0.5rem !important;
            width: 40px !important;
            height: 40px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
        }

        .admin-sidebar.collapsed .language-switcher-sidebar .btn span,
        .admin-sidebar.collapsed .language-switcher-sidebar .btn-text {
            display: none;
        }

        .admin-sidebar.collapsed .language-switcher-sidebar .btn i {
            margin: 0 !important;
        }

        /* Hiện lại khi hover */
        .admin-sidebar.collapsed:hover .language-switcher-sidebar .dropdown-toggle::after {
            display: inline-block;
        }

        .admin-sidebar.collapsed:hover .language-switcher-sidebar .btn {
            padding: 0.375rem 0.75rem !important;
            width: 100% !important;
            height: auto !important;
            border-radius: 0.25rem !important;
        }

        .admin-sidebar.collapsed:hover .language-switcher-sidebar .btn span,
        .admin-sidebar.collapsed:hover .language-switcher-sidebar .btn-text {
            display: inline;
        }

        .admin-sidebar.collapsed:hover .language-switcher-sidebar .btn i {
            margin-right: 0.5rem !important;
        }

        /* Điều chỉnh margin khi sidebar collapsed */
        body.has-admin-sidebar .admin-sidebar.collapsed ~ .content-wrapper,
        body.has-admin-sidebar .admin-sidebar.collapsed ~ main {
            margin-left: 80px;
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
            background: rgba(0, 0, 0, 0.9);
            color: white;
            border-radius: 6px;
            white-space: nowrap;
            z-index: 1002;
            font-size: 0.875rem;
            pointer-events: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
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
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
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

        .sidebar-toggle {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            overflow: hidden;
            flex-shrink: 0;
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
            font-weight: 700;
            color: white;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-details .user-role {
            color: #a1a9b8;
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
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            font-weight: 500;
        }

        .menu-link:hover {
            color: white;
            background: rgba(102, 126, 234, 0.15);
        }

        .menu-item.active .menu-link {
            color: white;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.3), rgba(118, 75, 162, 0.3));
            border-left: 3px solid #667eea;
        }

        .menu-item.active .menu-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .menu-link i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .menu-link-logout {
            color: rgba(255, 255, 255, 0.8) !important;
            background: transparent !important;
            border-radius: 0 !important;
            border: none !important;
        }

        .menu-link-logout:hover {
            color: white !important;
            background: rgba(102, 126, 234, 0.15) !important;
            border-radius: 0 !important;
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
            background: rgba(102, 126, 234, 0.2);
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
            transition: transform 0.3s ease;
        }
        
        .menu-item.has-submenu.open > .menu-link::after {
            transform: rotate(180deg);
        }
        
        .menu-submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: rgba(0, 0, 0, 0.2);
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
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.25), rgba(118, 75, 162, 0.25));
            border-left: 3px solid #667eea;
        }
        
        .menu-item.has-submenu.open > .menu-link {
            background: rgba(102, 126, 234, 0.1);
        }

        .sidebar-footer {
            position: sticky;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(102, 126, 234, 0.2);
            background: rgba(15, 15, 35, 0.95);
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
            border-color: rgba(255, 255, 255, 0.3);
            color: rgba(255, 255, 255, 0.9);
        }

        .language-switcher-sidebar .btn:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
        }

        /* Admin Top Bar */
        .admin-topbar {
            position: fixed;
            top: 0;
            left: 280px;
            transition: left 0.3s ease;
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
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 30%, #16213e 70%, #0f0f23 100%);
            backdrop-filter: blur(25px);
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar-toggle-mobile {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
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
            .admin-sidebar {
                transform: translateX(-100%);
                z-index: 1000;
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-topbar {
                left: 0;
            }

            .content-wrapper-with-sidebar {
                margin-left: 0;
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
            
            .footer-bottom-links {
                justify-content: center;
                margin-top: 1rem;
            }
            
            .modern-footer {
                margin-top: 0;
                padding: 1.5rem 0 1.5rem;
            }
            
            /* Compact footer on mobile */
            .modern-footer .container {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            .modern-footer .row {
                margin-bottom: 0;
            }
            
            .modern-footer .mb-4 {
                margin-bottom: 1rem !important;
            }
            
            /* Adjust column layout on mobile for better balance */
            @media (max-width: 576px) {
                .modern-footer .col-6:first-child,
                .modern-footer .col-6:last-child {
                    width: 100%;
                    max-width: 100%;
                    flex: 0 0 100%;
                }
                
                .modern-footer .col-6:nth-child(2),
                .modern-footer .col-6:nth-child(3) {
                    width: 50%;
                    max-width: 50%;
                    flex: 0 0 50%;
                }
            }
            
            .footer-brand {
                margin-bottom: 0.5rem !important;
                gap: 0.75rem;
            }
            
            .footer-logo {
                width: 35px;
                height: 35px;
                font-size: 0.85rem;
            }
            
            .footer-brand-text .brand-title {
                font-size: 1rem;
                margin-bottom: 0.25rem;
            }
            
            .brand-subtitle {
                font-size: 0.75rem;
                line-height: 1.3;
            }
            
            .footer-title {
                font-size: 0.9rem;
                margin-bottom: 0.5rem;
                padding-bottom: 0.25rem;
            }
            
            .footer-links li {
                margin-bottom: 0.5rem;
            }
            
            .footer-links a {
                font-size: 0.85rem;
            }
            
            .social-links {
                gap: 0.5rem;
                margin-bottom: 0.75rem;
            }
            
            .social-link {
                padding: 0.35rem 0.5rem;
                font-size: 0.75rem;
            }
            
            .social-link span {
                display: none;
            }
            
            .newsletter {
                margin-top: 0.5rem;
            }
            
            .newsletter-text {
                font-size: 0.8rem;
                margin-bottom: 0.75rem;
            }
            
            .newsletter-form {
                padding: 0.25rem;
            }
            
            .newsletter-input {
                padding: 0.4rem 0.5rem;
                font-size: 0.75rem;
            }
            
            .newsletter-btn {
                width: 30px;
                height: 30px;
                font-size: 0.75rem;
            }
            
            .footer-bottom {
                margin-top: 0.5rem;
                padding-top: 0.5rem;
            }
            
            .copyright {
                font-size: 0.8rem;
            }
            
            .footer-bottom-links {
                gap: 0.75rem;
                margin-top: 0.5rem;
            }
            
            .footer-bottom-links a {
                font-size: 0.8rem;
            }
            
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
                        <i class="fas fa-gamepad"></i>
                    </div>
                    <div class="brand-text">
                        <span class="brand-name">{{ __('app.name') }}</span>
                        <small class="brand-tagline">{{ __('app.tagline') }}</small>
                    </div>
                </a>
                <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
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
                    <li class="menu-item {{ Request::is('admin/players*') || Request::is('players*') ? 'active' : '' }}">
                        <a href="{{ route('players.index') }}" class="menu-link" title="{{ __('app.nav.players') }}">
                            <i class="fas fa-user-friends"></i>
                            <span>{{ __('app.nav.players') }}</span>
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
                            <li class="menu-item {{ Request::is('admin/honor*') || Request::is('honor*') ? 'active' : '' }}">
                                <a href="{{ route('admin.honor.index') }}" class="menu-link" title="{{ __('app.honor.manage_title') }}">
                                    <i class="fas fa-trophy"></i>
                                    <span>{{ __('app.honor.manage_title') }}</span>
                                </a>
                            </li>
                            <li class="menu-item {{ Request::is('admin/marketplace*') ? 'active' : '' }}">
                                <a href="{{ route('admin.marketplace.index') }}" class="menu-link" title="Quản lý Marketplace">
                                    <i class="fas fa-store"></i>
                                    <span>Quản lý Marketplace</span>
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
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-light w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown" title="{{ strtoupper(app()->getLocale()) }}">
                            <i class="fas fa-globe me-2"></i><span class="btn-text">{{ strtoupper(app()->getLocale()) }}</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item language-switch" href="#" data-locale="en">
                                <i class="fas fa-flag-usa me-2"></i>English
                            </a></li>
                            <li><a class="dropdown-item language-switch" href="#" data-locale="vi">
                                <i class="fas fa-flag me-2"></i>Tiếng Việt
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Admin Top Bar (Compact) -->
        <div class="admin-topbar">
            <button class="sidebar-toggle-mobile d-lg-none" id="sidebarToggleMobile">
                <i class="fas fa-bars"></i>
            </button>
            <div class="topbar-search">
                @auth
                <div id="header-search" class="position-relative">
                    <button type="button" class="btn btn-light btn-sm" id="searchToggle" title="{{ __('app.search.search') }}" aria-expanded="false">
                        <i class="fas fa-magnifying-glass"></i>
                    </button>
                    <div id="searchBox" class="position-absolute end-0 mt-2" style="display:none; z-index: 100000;">
                        <div class="search-panel">
                            <div class="search-input-wrap">
                                <i class="fas fa-magnifying-glass text-secondary"></i>
                                <input id="searchInput" class="search-input" placeholder="{{ __('app.search.search_users_teams_tournaments_games') }}" />
                                <div id="searchLoading" class="search-loading"></div>
                                <button id="searchClear" class="search-clear" title="{{ __('app.search.clear') }}"><i class="fas fa-xmark"></i></button>
                                <span class="ms-2 d-none d-md-inline text-secondary" title="{{ __('app.search.shortcut') }}"><span class="search-kbd">/</span> <span class="search-kbd">Enter</span></span>
                            </div>
                            <div id="searchResults" class="search-results"></div>
                            <div class="p-2 border-top" id="searchFooter" style="display:none;">
                                <a id="searchSeeAll" class="btn btn-sm btn-outline-primary w-100">{{ __('app.search.see_all_results') }}</a>
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
                        <i class="fas fa-gamepad"></i>
                    </div>
                    <div class="gameon-brand-text">
                        <div class="gameon-brand-name">{{ __('app.name') }}</div>
                        <div class="gameon-brand-tagline">{{ __('app.tagline') }}</div>
                    </div>
                </a>

                <!-- Desktop: Nav Links -->
                <div class="gameon-nav-links d-none d-lg-flex">
                    @auth
                        @if(!Request::is('dashboard*'))
                        <a href="{{ route('dashboard') }}" class="gameon-nav-link">
                            <i class="fas fa-gauge-high"></i>
                            <span>{{ __('app.nav.dashboard') }}</span>
                        </a>
                        @endif
                        @if(Auth::user()->user_role === 'player')
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
                    <!-- Language -->
                    <a href="#" class="gameon-nav-link language-switch d-none d-lg-flex" data-locale="en" title="English">
                        <i class="fas fa-flag-usa"></i>
                    </a>
                    <a href="#" class="gameon-nav-link language-switch d-none d-lg-flex" data-locale="vi" title="Tiếng Việt">
                        <i class="fas fa-flag"></i>
                    </a>

                    <!-- Search -->
                    @auth
                    <div class="position-relative d-none d-md-block">
                        <button type="button" class="gameon-nav-link" id="searchToggle" style="border: none; background: none; cursor: pointer;">
                            <i class="fas fa-magnifying-glass"></i>
                        </button>
                        <div id="searchBox" class="position-absolute end-0 mt-2" style="display:none; z-index: 100000;">
                            <div class="search-panel">
                                <div class="search-input-wrap">
                                    <i class="fas fa-magnifying-glass text-secondary"></i>
                                    <input id="searchInput" class="search-input" placeholder="{{ __('app.search.search_users_teams_tournaments_games') }}" />
                                    <div id="searchLoading" class="search-loading"></div>
                                    <button id="searchClear" class="search-clear" title="{{ __('app.search.clear') }}"><i class="fas fa-xmark"></i></button>
                                </div>
                                <div id="searchResults" class="search-results"></div>
                            </div>
                        </div>
                    </div>
                    @endauth

                    <!-- User Menu -->
                    @auth
                    <div class="dropdown">
                        <a href="#" class="gameon-nav-link dropdown-toggle" data-bs-toggle="dropdown" style="text-decoration: none;">
                            <div class="gameon-user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="gameon-user-info d-none d-lg-block">
                                <div class="gameon-user-name">{{ Auth::user()->name ?? 'User' }}</div>
                                <div class="gameon-user-role">{{ ucfirst(str_replace('_', ' ', Auth::user()->user_role ?? 'user')) }}</div>
                            </div>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu gameon-dropdown">
                            @if(Auth::user()->user_role === 'player')
                            <li><a href="{{ route('teams.index') }}" class="gameon-dropdown-item">
                                <i class="fas fa-users"></i>
                                <span>{{ __('app.nav.my_teams') }}</span>
                            </a></li>
                            @endif
                            @if(Auth::user()->user_role === 'admin' || Auth::user()->user_role === 'super_admin')
                            <li><a href="{{ route('admin.games.index') }}" class="gameon-dropdown-item">
                                <i class="fas fa-gamepad"></i>
                                <span>{{ __('app.profile.manage_games') }}</span>
                            </a></li>
                            <li><a href="{{ route('admin.tournaments.index') }}" class="gameon-dropdown-item">
                                <i class="fas fa-trophy"></i>
                                <span>{{ __('app.profile.manage_tournaments') }}</span>
                            </a></li>
                            <li><a href="{{ route('admin.teams.index') }}" class="gameon-dropdown-item">
                                <i class="fas fa-users-gear"></i>
                                <span>{{ __('app.profile.manage_teams') }}</span>
                            </a></li>
                            <li><a href="{{ route('admin.users.index') }}" class="gameon-dropdown-item">
                                <i class="fas fa-users"></i>
                                <span>{{ __('app.profile.manage_users') }}</span>
                            </a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('auth.logout') }}">
                                    @csrf
                                    <button type="submit" class="gameon-dropdown-item" style="width: 100%; border: none; background: none; text-align: left;">
                                        <i class="fas fa-right-from-bracket"></i>
                                        <span>{{ __('app.auth.logout') }}</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @else
                    <a href="{{ route('auth.login') }}" class="gameon-nav-link">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>{{ __('app.auth.login') }}</span>
                    </a>
                    <a href="{{ route('auth.register') }}" class="gameon-nav-link" style="background: rgba(0, 229, 255, 0.1); border: 1px solid rgba(0, 229, 255, 0.3);">
                        <i class="fas fa-user-plus"></i>
                        <span>{{ __('app.auth.register') }}</span>
                    </a>
                    @endauth

                    <!-- Mobile Menu Toggle -->
                    <button class="gameon-nav-link d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav" style="border: none; background: none; cursor: pointer;">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="collapse d-lg-none" id="mobileNav">
                <div style="background: #0d1b2a; border-top: 1px solid rgba(0, 229, 255, 0.2); padding: 1rem;">
                    @auth
                    <a href="{{ route('dashboard') }}" class="gameon-dropdown-item" style="display: block; margin-bottom: 0.5rem;">
                        <i class="fas fa-gauge-high"></i>
                        <span>{{ __('app.nav.dashboard') }}</span>
                    </a>
                    <a href="{{ route('chat.index') }}" class="gameon-dropdown-item" style="display: block; margin-bottom: 0.5rem;">
                        <i class="fas fa-comments"></i>
                        <span>{{ __('app.nav.chat') }}</span>
                    </a>
                    <a href="{{ route('posts.index') }}" class="gameon-dropdown-item" style="display: block; margin-bottom: 0.5rem;">
                        <i class="fas fa-newspaper"></i>
                        <span>{{ __('app.nav.posts') }}</span>
                    </a>
                    <a href="{{ route('marketplace.index') }}" class="gameon-dropdown-item" style="display: block; margin-bottom: 0.5rem;">
                        <i class="fas fa-store"></i>
                        <span>Marketplace</span>
                    </a>
                    <a href="{{ route('profile.show') }}" class="gameon-dropdown-item" style="display: block; margin-bottom: 0.5rem;">
                        <i class="fas fa-id-card"></i>
                        <span>{{ __('app.profile.personal_info') }}</span>
                    </a>
                    @endauth
                </div>
            </div>
        </nav>
    @endif

    <!-- Main Content -->
    <main class="content-wrapper {{ $showAdminSidebar ? 'content-wrapper-with-sidebar' : '' }}">
        @if(session('success'))
        <div class="container">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="container">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Global Toast Container -->
    <div id="app-toast-container"></div>

    <!-- Removed legacy Confirm Modal (use SweetAlert2 in JS wrapper) -->

    <!-- Modern Footer - Only show for guests or on welcome page -->
    @guest
        <footer class="modern-footer">
            <div class="footer-background">
                <div class="footer-gradient"></div>
                <div class="footer-pattern"></div>
            </div>
            
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-6 mb-4">
                        <div class="footer-brand">
                            <div class="footer-logo">
                                <i class="fas fa-gamepad"></i>
                            </div>
                            <div class="footer-brand-text">
                                <h5 class="brand-title">Game On</h5>
                                <p class="brand-subtitle">{{ __('app.footer.professional_esports_management_platform') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-2 col-md-6 col-6 mb-4">
                        <h6 class="footer-title">{{ __('app.footer.features') }}</h6>
                        <ul class="footer-links">
                            <li><a href="{{ route('tournaments.index') }}">{{ __('app.nav.tournaments') }}</a></li>
                            <li><a href="{{ route('teams.index') }}">{{ __('app.nav.my_teams') }}</a></li>
                            <li><a href="{{ route('players.index') }}">{{ __('app.nav.players') }}</a></li>
                            <li><a href="{{ route('chat.index') }}">{{ __('app.nav.chat') }}</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-lg-2 col-md-6 col-6 mb-4">
                        <h6 class="footer-title">{{ __('app.footer.support') }}</h6>
                        <ul class="footer-links">
                            <li><a href="#">{{ __('app.footer.help_center') }}</a></li>
                            <li><a href="#">{{ __('app.footer.contact') }}</a></li>
                            <li><a href="#">{{ __('app.footer.bug_report') }}</a></li>
                            <li><a href="#">{{ __('app.footer.faq') }}</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 col-6 mb-4">
                        <h6 class="footer-title">{{ __('app.footer.connect') }}</h6>
                        <div class="social-links">
                            <a href="#" class="social-link facebook">
                                <i class="fab fa-facebook-f"></i>
                                <span>Facebook</span>
                            </a>
                            <a href="#" class="social-link twitter">
                                <i class="fab fa-twitter"></i>
                                <span>Twitter</span>
                            </a>
                            <a href="#" class="social-link discord">
                                <i class="fab fa-discord"></i>
                                <span>Discord</span>
                            </a>
                            <a href="#" class="social-link youtube">
                                <i class="fab fa-youtube"></i>
                                <span>YouTube</span>
                            </a>
                        </div>
                        <div class="newsletter">
                            <p class="newsletter-text">{{ __('app.footer.subscribe_newsletter') }}</p>
                            <div class="newsletter-form">
                                <input type="email" placeholder="{{ __('app.footer.your_email') }}" class="newsletter-input">
                                <button class="newsletter-btn">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="footer-bottom">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p class="copyright">&copy; {{ date('Y') }} Game On. All rights reserved.</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="footer-bottom-links">
                                <a href="#">Privacy Policy</a>
                                <a href="#">Terms of Service</a>
                                <a href="#">Cookies</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    @else
        {{-- Show footer only on welcome page for authenticated users --}}
        @if(Request::is('/') || Request::is('welcome'))
            <footer class="modern-footer">
                <div class="footer-background">
                    <div class="footer-gradient"></div>
                    <div class="footer-pattern"></div>
                </div>
                
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-6 mb-4">
                            <div class="footer-brand">
                                <div class="footer-logo">
                                    <i class="fas fa-gamepad"></i>
                                </div>
                                <div class="footer-brand-text">
                                    <h5 class="brand-title">Game On</h5>
                                    <p class="brand-subtitle">{{ __('app.footer.professional_esports_management_platform') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-2 col-md-6 col-6 mb-4">
                            <h6 class="footer-title">{{ __('app.footer.features') }}</h6>
                            <ul class="footer-links">
                                <li><a href="{{ route('tournaments.index') }}">{{ __('app.nav.tournaments') }}</a></li>
                                <li><a href="{{ route('teams.index') }}">{{ __('app.nav.my_teams') }}</a></li>
                                <li><a href="{{ route('players.index') }}">{{ __('app.nav.players') }}</a></li>
                                <li><a href="{{ route('chat.index') }}">{{ __('app.nav.chat') }}</a></li>
                            </ul>
                        </div>
                        
                        <div class="col-lg-2 col-md-6 col-6 mb-4">
                            <h6 class="footer-title">{{ __('app.footer.support') }}</h6>
                            <ul class="footer-links">
                                <li><a href="#">{{ __('app.footer.help_center') }}</a></li>
                                <li><a href="#">{{ __('app.footer.contact') }}</a></li>
                                <li><a href="#">{{ __('app.footer.bug_report') }}</a></li>
                                <li><a href="#">{{ __('app.footer.faq') }}</a></li>
                            </ul>
                        </div>
                        
                        <div class="col-lg-4 col-md-6 col-6 mb-4">
                            <h6 class="footer-title">{{ __('app.footer.connect') }}</h6>
                            <div class="social-links">
                                <a href="#" class="social-link facebook">
                                    <i class="fab fa-facebook-f"></i>
                                    <span>Facebook</span>
                                </a>
                                <a href="#" class="social-link twitter">
                                    <i class="fab fa-twitter"></i>
                                    <span>Twitter</span>
                                </a>
                                <a href="#" class="social-link discord">
                                    <i class="fab fa-discord"></i>
                                    <span>Discord</span>
                                </a>
                                <a href="#" class="social-link youtube">
                                    <i class="fab fa-youtube"></i>
                                    <span>YouTube</span>
                                </a>
                            </div>
                            <div class="newsletter">
                                <p class="newsletter-text">{{ __('app.footer.subscribe_newsletter') }}</p>
                                <div class="newsletter-form">
                                    <input type="email" placeholder="{{ __('app.footer.your_email') }}" class="newsletter-input">
                                    <button class="newsletter-btn">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="footer-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <p class="copyright">&copy; {{ date('Y') }} Game On. All rights reserved.</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="footer-bottom-links">
                                    <a href="#">Privacy Policy</a>
                                    <a href="#">Terms of Service</a>
                                    <a href="#">Cookies</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        @endif
    @endguest

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

        // Header search interactions
        (function(){
            const toggle = document.getElementById('searchToggle');
            const box = document.getElementById('searchBox');
            const input = document.getElementById('searchInput');
            const results = document.getElementById('searchResults');
            const loading = document.getElementById('searchLoading');
            const clearBtn = document.getElementById('searchClear');
            const searchSeeAll = document.getElementById('searchSeeAll');
            if(!toggle) return;

            let originalParent = null;
            let isSearchOpen = false;
            
            const openBox = ()=>{
                if(!box) return;
                // Get toggle button position
                const toggleRect = toggle.getBoundingClientRect();
                const toggleLeft = toggleRect.left;
                const toggleTop = toggleRect.top;
                
                // Move to body to avoid any overflow/stacking issues
                if(!originalParent){ originalParent = box.parentElement; }
                if(box.parentElement !== document.body){ document.body.appendChild(box); }
                
                // Check if mobile (screen width <= 768px)
                const isMobile = window.innerWidth <= 768;
                
                if(isMobile) {
                    // On mobile: full width with margins
                    box.style.position = 'fixed';
                    box.style.top = toggleTop + toggleRect.height + 10 + 'px';
                    box.style.left = '10px';
                    box.style.right = '10px';
                    box.style.width = 'auto';
                    box.style.minWidth = 'auto';
                    box.style.transform = 'scale(0.3)';
                    box.style.opacity = '0';
                    box.style.display = 'block';
                    box.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                    
                    // Set flag immediately
                    isSearchOpen = true;
                    toggle.setAttribute('aria-expanded','true');
                    
                    // Animate to final position
                    setTimeout(() => {
                        box.style.transform = 'scale(1)';
                        box.style.opacity = '1';
                    }, 10);
                } else {
                    // Desktop: calculate position relative to toggle button
                    const searchBoxWidth = 560; // Default width from CSS
                    const spacing = 10; // Space between button and search box
                    let leftPosition = toggleLeft - searchBoxWidth - spacing;
                    
                    // Check if search box would overflow on the left, if so, position to the right
                    if(leftPosition < 10) {
                        leftPosition = toggleRect.right + spacing;
                        // If still doesn't fit, center it
                        if(leftPosition + searchBoxWidth > window.innerWidth - 10) {
                            leftPosition = (window.innerWidth - searchBoxWidth) / 2;
                        }
                    }
                    
                    // Set initial position at toggle button (small and transparent)
                    box.style.position = 'fixed';
                    box.style.top = toggleTop + 'px';
                    box.style.left = toggleLeft + 'px';
                    box.style.right = 'auto';
                    box.style.width = '';
                    box.style.minWidth = '';
                    box.style.transform = 'scale(0.3)';
                    box.style.opacity = '0';
                    box.style.display = 'block';
                    box.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                    
                    // Set flag immediately
                    isSearchOpen = true;
                    toggle.setAttribute('aria-expanded','true');
                    
                    // Animate to final position (left of toggle button)
                    setTimeout(() => {
                        box.style.top = toggleTop + 'px';
                        box.style.left = leftPosition + 'px';
                        box.style.transform = 'scale(1)';
                        box.style.opacity = '1';
                    }, 10);
                }
                
                setTimeout(()=>input && input.focus(), 350);
            }
            const closeBox = ()=>{ 
                if(!box) return;
                
                // Set flag immediately
                isSearchOpen = false;
                
                // Get toggle button position for closing animation
                const toggleRect = toggle.getBoundingClientRect();
                const toggleLeft = toggleRect.left;
                const toggleTop = toggleRect.top;
                
                // Check if mobile
                const isMobile = window.innerWidth <= 768;
                
                // Animate back to toggle button
                if(isMobile) {
                    box.style.transform = 'scale(0.3)';
                    box.style.opacity = '0';
                } else {
                    box.style.top = toggleTop + 'px';
                    box.style.left = toggleLeft + 'px';
                    box.style.transform = 'scale(0.3)';
                    box.style.opacity = '0';
                }
                
                setTimeout(() => {
                    box.style.display = 'none';
                    // Reset mobile-specific styles
                    if(isMobile) {
                        box.style.left = '';
                        box.style.right = '';
                        box.style.width = '';
                        box.style.minWidth = '';
                    }
                    toggle.setAttribute('aria-expanded','false'); 
                    if(originalParent && box.parentElement === document.body){ 
                        originalParent.appendChild(box); 
                    }
                }, 300);
            }

            // Flag to prevent document click from interfering with toggle
            let isToggling = false;
            
            // Helper function to check if box is actually visible
            const isBoxVisible = () => {
                if(!box) return false;
                const style = window.getComputedStyle(box);
                return style.display !== 'none' && style.opacity !== '0';
            };
            
            toggle && toggle.addEventListener('click', function(e){
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                if(!box) return;
                
                // Prevent document click from interfering
                isToggling = true;
                
                // Check actual state instead of just flag
                const currentlyOpen = isBoxVisible() || isSearchOpen;
                
                // Toggle search box based on current state
                if(currentlyOpen) {
                    closeBox();
                } else {
                    openBox();
                    // If already has query text, trigger a search immediately
                    if (input && input.value.trim() !== '') {
                        triggerSearch(input.value.trim());
                    }
                }
                
                // Reset flag after animation completes (longer timeout to ensure document click doesn't interfere)
                setTimeout(() => {
                    isToggling = false;
                }, 400);
            });
            
            // Close search box when clicking anywhere outside
            document.addEventListener('click', function(e){
                // Don't interfere if we're toggling
                if(isToggling) return;
                
                // Check if box is actually visible
                if(!box || !isBoxVisible() || !isSearchOpen) return;
                
                // Don't close if clicking inside search box
                if(box.contains(e.target)) return;
                
                // Don't close if clicking on the toggle button (handled separately above)
                const headerSearch = document.getElementById('header-search');
                if((toggle && toggle.contains(e.target)) || (headerSearch && headerSearch.contains(e.target))) {
                    return;
                }
                
                // Close for all other clicks
                closeBox();
            });
            
            // Close on Escape key
            document.addEventListener('keydown', function(e){ 
                if(e.key === 'Escape' && box && isSearchOpen){
                    closeBox();
                }
            });

            let lastQuery = '';
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
                if(q === ''){ results.innerHTML=''; return; }
                timer = setTimeout(()=>{ triggerSearch(q); }, 250);
            });

            clearBtn && clearBtn.addEventListener('click', function(){
                if(!input) return; input.value=''; results.innerHTML=''; input.focus();
            });

            // Keyboard shortcut: '/' to focus search
            document.addEventListener('keydown', function(e){
                if(e.key === '/' && !e.ctrlKey && !e.metaKey && !e.altKey){
                    e.preventDefault(); openBox(); input && input.focus();
                }
            });

            // Enter behavior: default -> full results page; hold Ctrl/Shift to open first result
            input && input.addEventListener('keydown', function(e){
                if(e.key === 'Enter'){
                    e.preventDefault();
                    const q = (input && input.value.trim()) || '';
                    const first = results.querySelector('.result-item, .list-group-item-action');
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
                results.innerHTML = html || `<div class=\"empty-state\"><i class=\"fas fa-search\"></i> ${i18n.empty}</div>`;
                const footer = document.getElementById('searchFooter');
                const seeAll = document.getElementById('searchSeeAll');
                if(footer && seeAll){
                    const q = input ? input.value.trim() : '';
                    footer.style.display = q ? 'block' : 'none';
                    seeAll.setAttribute('href', `{{ route('search.view') }}?q=${encodeURIComponent(q)}`);
                }
                // mark first item for Enter navigation
                const first = results.querySelector('.result-item');
                results.querySelectorAll('.result-item').forEach((el, idx)=>{
                    el.classList.toggle('active-result', idx===0);
                });
                
                // User results now redirect directly to profile pages
            }

            function iconByType(type){
                return type==='user'?'fa-user':type==='team'?'fa-users':type==='tournament'?'fa-trophy':type==='game'?'fa-gamepad':'fa-circle';
            }
            
            function escapeHtml(str){
                return String(str).replace(/[&<>"]/g, s=>({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;"}[s]));
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
        
        // Language switcher functionality
        document.addEventListener('DOMContentLoaded', function() {
            const languageSwitches = document.querySelectorAll('.language-switch');
            
            languageSwitches.forEach(function(switchElement) {
                switchElement.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const locale = this.getAttribute('data-locale');
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    // Show loading state
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Switching...';
                    
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

    @stack('scripts')
</body>

</html>