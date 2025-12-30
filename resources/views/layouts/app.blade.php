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

        /* Language Dropdown Styles - using Tailwind, minimal custom CSS */
        #languageDropdownMenu.show,
        #languageDropdownMobileMenu.show {
            display: block !important;
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

        /* Ẩn text trong language switcher khi collapsed */
        .admin-sidebar.collapsed .language-switcher-sidebar button {
            padding: 0.5rem !important;
            width: 40px !important;
            height: 40px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
        }

        .admin-sidebar.collapsed .language-switcher-sidebar button span {
            display: none;
        }

        .admin-sidebar.collapsed .language-switcher-sidebar button i {
            margin: 0 !important;
        }

        /* Hiện lại khi hover */
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
                    <div class="brand-text">
                        <span class="brand-name">{{ __('app.name') }}</span>
                        <small class="brand-tagline">{{ __('app.tagline') }}</small>
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
                    <div class="relative">
                        <button class="w-full px-3 py-1.5 text-sm border border-white/30 rounded text-white/90 hover:bg-white/10 hover:border-white/50 transition-all flex items-center justify-center gap-2" type="button" id="sidebarLanguageToggle" title="{{ strtoupper(app()->getLocale()) }}">
                            <i class="fas fa-globe mr-2"></i><span>{{ strtoupper(app()->getLocale()) }}</span>
                        </button>
                        <ul class="absolute bottom-full left-0 right-0 mb-2 hidden bg-[#0d1b2a] border border-[rgba(0,229,255,0.2)] rounded-lg py-2 shadow-lg" id="sidebarLanguageMenu">
                            <li class="list-none">
                                <a class="language-switch flex items-center gap-2 px-4 py-2 text-white no-underline hover:bg-[rgba(0,229,255,0.15)] transition-all" href="#" data-locale="en">
                                    <i class="fas fa-flag-usa mr-2 text-[#00E5FF]"></i>English
                                </a>
                            </li>
                            <li class="list-none">
                                <a class="language-switch flex items-center gap-2 px-4 py-2 text-white no-underline hover:bg-[rgba(0,229,255,0.15)] transition-all" href="#" data-locale="vi">
                                    <i class="fas fa-flag mr-2 text-[#00E5FF]"></i>Tiếng Việt
                                </a>
                            </li>
                        </ul>
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
                <div id="header-search" class="relative">
                    <button type="button" class="px-3 py-1.5 text-sm bg-white text-gray-800 rounded hover:bg-gray-100 transition-colors" id="searchToggle" title="{{ __('app.search.search') }}" aria-expanded="false">
                        <i class="fas fa-magnifying-glass"></i>
                    </button>
                    <div id="searchBox" class="absolute right-0 top-full mt-2" style="display:none; z-index: 100000;">
                        <div class="search-panel">
                            <div class="search-input-wrap">
                                <i class="fas fa-magnifying-glass text-gray-500"></i>
                                <input id="searchInput" class="search-input" placeholder="{{ __('app.search.search_users_teams_tournaments_games') }}" />
                                <div id="searchLoading" class="search-loading"></div>
                                <button id="searchClear" class="search-clear" title="{{ __('app.search.clear') }}"><i class="fas fa-xmark"></i></button>
                                <span class="ml-2 hidden md:inline text-gray-500" title="{{ __('app.search.shortcut') }}"><span class="search-kbd">/</span> <span class="search-kbd">Enter</span></span>
                            </div>
                            <div id="searchResults" class="search-results"></div>
                            <div class="p-2 border-top" id="searchFooter" style="display:none;">
                                <a id="searchSeeAll" class="block w-full px-3 py-1.5 text-sm border border-blue-500 text-blue-500 rounded hover:bg-blue-500 hover:text-white transition-colors text-center">{{ __('app.search.see_all_results') }}</a>
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

                <!-- Desktop: Nav Links -->
                <div class="gameon-nav-links hidden lg:flex">
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
                    <!-- Language Dropdown -->
                    <div class="relative" id="languageDropdown">
                        <button type="button" class="gameon-nav-link" id="languageDropdownToggle" aria-expanded="false" aria-haspopup="true" title="{{ strtoupper(app()->getLocale()) }}" style="border: none; background: none; cursor: pointer; text-decoration: none; padding: 0.5rem 1rem;">
                            <i class="fas fa-globe"></i>
                        </button>
                        <ul class="absolute right-0 top-full mt-1 hidden bg-[#0d1b2a] border border-[rgba(0,229,255,0.2)] rounded-lg py-2 min-w-[180px] z-[10000] shadow-lg" id="languageDropdownMenu" aria-labelledby="languageDropdownToggle">
                            <li class="list-none">
                                <a class="language-switch flex items-center gap-3 px-5 py-3 text-white no-underline transition-all hover:bg-[rgba(0,229,255,0.15)] hover:text-[#00E5FF]" href="#" data-locale="en">
                                    <i class="fas fa-flag-usa text-[#00E5FF]"></i>
                                    <span>English</span>
                                    @if(app()->getLocale() === 'en')
                                        <i class="fas fa-check ml-auto text-[#00E5FF]"></i>
                                    @endif
                                </a>
                            </li>
                            <li class="list-none">
                                <a class="language-switch flex items-center gap-3 px-5 py-3 text-white no-underline transition-all hover:bg-[rgba(0,229,255,0.15)] hover:text-[#00E5FF]" href="#" data-locale="vi">
                                    <i class="fas fa-flag text-[#00E5FF]"></i>
                                    <span>Tiếng Việt</span>
                                    @if(app()->getLocale() === 'vi')
                                        <i class="fas fa-check ml-auto text-[#00E5FF]"></i>
                                    @endif
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Search -->
                    @auth
                    <div class="relative hidden md:block">
                        <button type="button" class="gameon-nav-link" id="searchToggle" style="border: none; background: none; cursor: pointer;">
                            <i class="fas fa-magnifying-glass"></i>
                        </button>
                        <div id="searchBox" class="absolute right-0 top-full mt-2" style="display:none; z-index: 100000;">
                            <div class="search-panel">
                                <div class="search-input-wrap">
                                    <i class="fas fa-magnifying-glass text-gray-500"></i>
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
                    <div class="relative" id="userMenuDropdown">
                        <a href="#" class="gameon-nav-link" id="userMenuToggle" style="text-decoration: none;">
                            <div class="gameon-user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="gameon-user-info hidden lg:block">
                                <div class="gameon-user-name">{{ Auth::user()->name ?? 'User' }}</div>
                                <div class="gameon-user-role">{{ ucfirst(str_replace('_', ' ', Auth::user()->user_role ?? 'user')) }}</div>
                            </div>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="absolute right-0 top-full mt-2 hidden bg-[#0d1b2a] border border-[rgba(0,229,255,0.2)] rounded-lg py-2 min-w-[200px] z-[10000] shadow-lg" id="userMenuDropdownMenu">
                            @if(Auth::user()->user_role === 'player')
                            <li class="list-none">
                                <a href="{{ route('teams.index') }}" class="gameon-dropdown-item">
                                    <i class="fas fa-users"></i>
                                    <span>{{ __('app.nav.my_teams') }}</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->user_role === 'admin' || Auth::user()->user_role === 'super_admin')
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
                            @endif
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
                <button class="mobile-menu-close" id="mobileMenuClose" aria-label="Close menu">
                    <i class="fas fa-times"></i>
                </button>
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
                        <span>Trang chủ</span>
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
                    @if(Auth::user()->user_role === 'player' && Route::has('teams.index'))
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
                    <a href="{{ route('marketplace.index') }}" class="mobile-menu-item {{ Request::is('marketplace*') ? 'active' : '' }}">
                        <i class="fas fa-store"></i>
                        <span>Marketplace</span>
                    </a>
                    @endif
                    @if(Route::has('profile.show'))
                    <a href="{{ route('profile.show') }}" class="mobile-menu-item {{ Request::is('profile*') ? 'active' : '' }}">
                        <i class="fas fa-id-card"></i>
                        <span>{{ __('app.profile.personal_info') }}</span>
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
                </nav>
                @endif
                
                <div class="mobile-menu-divider"></div>
                
                <!-- Language Switcher -->
                <div class="mobile-menu-section-title">Ngôn ngữ</div>
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
                        <span>Trang chủ</span>
                    </a>
                    @if(Route::has('tournaments.index'))
                    <a href="{{ route('tournaments.index') }}" class="mobile-menu-item {{ Request::is('tournaments*') ? 'active' : '' }}">
                        <i class="fas fa-trophy"></i>
                        <span>{{ __('app.nav.tournaments') }}</span>
                    </a>
                    @endif
                    @if(Route::has('players.index'))
                    <a href="{{ route('players.index') }}" class="mobile-menu-item {{ Request::is('players*') ? 'active' : '' }}">
                        <i class="fas fa-gamepad"></i>
                        <span>{{ __('app.nav.players') }}</span>
                    </a>
                    @endif
                </nav>
                
                <div class="mobile-menu-divider"></div>
                
                <!-- Language Switcher -->
                <div class="mobile-menu-section-title">Ngôn ngữ</div>
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
        <div class="container mx-auto px-4">
            <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
                <span>{{ session('success') }}</span>
                <button type="button" class="text-green-400 hover:text-green-300 ml-4" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="container mx-auto px-4">
            <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
                <span>{{ session('error') }}</span>
                <button type="button" class="text-red-400 hover:text-red-300 ml-4" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
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
                                <a href="{{ route('players.index') }}" style="color: #94a3b8; text-decoration: none; font-size: 14px; transition: color 0.3s ease; display: flex; align-items: center; gap: 8px;">
                                    <span style="color: #00E5FF;">›</span> {{ __('app.nav.players') }}
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
    @else
        {{-- Show footer only on welcome page for authenticated users --}}
        @if(Request::is('/') || Request::is('welcome'))
            <footer class="bg-midnight border-t border-border pt-24 pb-16 text-slate-400 font-body relative overflow-hidden">
                <!-- Background Blur Effect -->
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand/20 rounded-full blur-[100px] pointer-events-none"></div>
                
                <div class="container mx-auto px-6 relative z-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                        <!-- Brand Section -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('logo_remove_bg.png') }}" alt="{{ __('app.name') }}" class="w-10 h-10 object-contain">
                                <span class="font-display font-bold text-3xl text-white tracking-wider">
                                    GAME <span class="text-neon">ON</span>
                                </span>
                                </div>
                            <p class="text-sm leading-relaxed text-white">
                                {{ __('app.footer.professional_esports_management_platform') }}
                            </p>
                            <div class="flex gap-4">
                                <a href="#" class="w-10 h-10 rounded-lg bg-void border border-border flex items-center justify-center hover:bg-brand hover:text-white hover:border-neon transition-all duration-300 group">
                                    <i class="fab fa-facebook-f text-white group-hover:scale-110 transition-transform"></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-lg bg-void border border-border flex items-center justify-center hover:bg-[#5865F2] hover:text-white hover:border-[#5865F2] transition-all duration-300 group">
                                    <i class="fab fa-discord text-white group-hover:scale-110 transition-transform"></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-lg bg-void border border-border flex items-center justify-center hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-300 group">
                                    <i class="fab fa-youtube text-white group-hover:scale-110 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Features Section -->
                        <div>
                            <h3 class="font-display font-bold text-white text-lg uppercase tracking-wider mb-6 border-l-4 border-neon pl-3">
                                {{ __('app.footer.features') }}
                            </h3>
                            <ul class="space-y-3">
                                <li>
                                    <a href="{{ route('tournaments.index') }}" class="text-white hover:text-neon transition-colors flex items-center gap-2">
                                        <i class="fas fa-angle-right text-xs text-slate-400"></i>
                                        {{ __('app.nav.tournaments') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('teams.index') }}" class="text-white hover:text-neon transition-colors flex items-center gap-2">
                                        <i class="fas fa-angle-right text-xs text-slate-400"></i>
                                        {{ __('app.nav.my_teams') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('players.index') }}" class="text-white hover:text-neon transition-colors flex items-center gap-2">
                                        <i class="fas fa-angle-right text-xs text-slate-400"></i>
                                        {{ __('app.nav.players') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('chat.index') }}" class="text-white hover:text-neon transition-colors flex items-center gap-2">
                                        <i class="fas fa-angle-right text-xs text-slate-400"></i>
                                        {{ __('app.nav.chat') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Support Section -->
                        <div>
                            <h3 class="font-display font-bold text-white text-lg uppercase tracking-wider mb-6 border-l-4 border-neon pl-3">
                                {{ __('app.footer.support') }}
                            </h3>
                            <ul class="space-y-3">
                                <li>
                                    <a href="#" class="text-white hover:text-neon transition-colors">
                                        {{ __('app.footer.help_center') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="text-white hover:text-neon transition-colors">
                                        {{ __('app.footer.contact') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="text-white hover:text-neon transition-colors">
                                        {{ __('app.footer.bug_report') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="text-white hover:text-neon transition-colors">
                                        {{ __('app.footer.faq') }}
                                    </a>
                                </li>
                            </ul>
                            </div>
                        
                        <!-- Newsletter Section -->
                        <div>
                            <h3 class="font-display font-bold text-white text-lg uppercase tracking-wider mb-6 border-l-4 border-neon pl-3">
                                {{ __('app.footer.connect') }}
                            </h3>
                            <p class="text-sm mb-4 text-white">{{ __('app.footer.subscribe_newsletter') }}</p>
                            
                            <div class="relative mb-6">
                                <form>
                                    <input type="email" placeholder="{{ __('app.footer.your_email') }}" 
                                        class="w-full bg-void border border-border rounded-lg py-3 px-4 text-white placeholder:text-slate-400 focus:outline-none focus:border-neon transition-colors text-sm pr-20">
                                    <button type="submit" class="absolute right-1 top-1 bottom-1 bg-brand hover:bg-[#1a237e] text-white px-4 rounded-md font-display font-bold uppercase text-xs tracking-wide transition-colors">
                                        {{ __('app.footer.send') }}
                                    </button>
                                </form>
                                </div>
                            
                            <!-- App Download Buttons -->
                            <div class="flex gap-3">
                                <button class="flex-1 bg-void border border-border hover:border-white rounded p-2 flex items-center justify-center gap-2 transition-colors">
                                    <i class="fab fa-apple text-xl text-white"></i>
                                    <div class="text-left leading-none">
                                        <div class="text-[10px] text-white">Download on</div>
                                        <div class="text-xs font-bold text-white">App Store</div>
                            </div>
                                </button>
                                <button class="flex-1 bg-void border border-border hover:border-white rounded p-2 flex items-center justify-center gap-2 transition-colors">
                                    <i class="fab fa-google-play text-lg text-white"></i>
                                    <div class="text-left leading-none">
                                        <div class="text-[10px] text-white">Get it on</div>
                                        <div class="text-xs font-bold text-white">Google Play</div>
                        </div>
                                </button>
                    </div>
                            </div>
                                </div>
                    
                    <!-- Footer Bottom -->
                    <div class="border-t border-border pt-8 pb-8 flex flex-row flex-wrap justify-between items-center gap-4 text-xs text-white">
                        <div>
                            &copy; {{ date('Y') }} <span class="font-bold">Game On</span>. All rights reserved.
                            </div>
                        <div class="flex gap-6">
                            <a href="#" class="hover:text-white transition-colors">{{ __('app.footer.terms_of_service') }}</a>
                            <a href="#" class="hover:text-white transition-colors">{{ __('app.footer.privacy_policy') }}</a>
                            <a href="#" class="hover:text-white transition-colors">{{ __('app.footer.cookie_policy') }}</a>
                        </div>
                        <div class="flex items-center gap-1">
                            Made with <i class="fas fa-heart text-red-500 animate-pulse"></i> by <span class="text-neon font-bold">GameOn Team</span>
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
        
        // Mobile menu toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileNav = document.getElementById('mobileNav');
            
            if (mobileMenuToggle && mobileNav) {
                mobileMenuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Toggle mobile menu using Tailwind classes
                    mobileNav.classList.toggle('hidden');
                    mobileNav.classList.toggle('block');
                    
                    // Update aria-expanded
                    const isExpanded = !mobileNav.classList.contains('hidden');
                    mobileMenuToggle.setAttribute('aria-expanded', isExpanded);
                });
                
                // Close mobile menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!mobileNav.classList.contains('hidden') && 
                        !mobileNav.contains(e.target) && 
                        !mobileMenuToggle.contains(e.target)) {
                        mobileNav.classList.add('hidden');
                        mobileNav.classList.remove('block');
                        mobileMenuToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });
        
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

    @stack('scripts')
</body>

</html>