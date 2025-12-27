@extends('layouts.app')

@section('title', 'Chỉnh sửa thông tin cá nhân')

@push('styles')
<style>
    .modern-profile-container {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f23 100%);
        min-height: calc(100vh - 90px);
        padding: 2rem 0;
        position: relative;
        overflow: hidden;
    }
    
    html, body {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f23 100%) !important;
        background-attachment: fixed;
    }
    
    .modern-profile-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></svg>') repeat;
        background-size: 50px 50px;
        animation: float-bg 20s ease-in-out infinite;
    }
    
    @keyframes float-bg {
        0%, 100% { transform: translate(0, 0); }
        50% { transform: translate(-20px, -20px); }
    }
    
    .modern-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 25px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        position: relative;
        z-index: 2;
    }
    
    .modern-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f23 100%);
        color: white;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .modern-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(102, 126, 234, 0.3) 0%, transparent 70%);
        animation: pulse-glow 4s ease-in-out infinite;
    }
    
    @keyframes pulse-glow {
        0%, 100% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.1); opacity: 0.6; }
    }
    
    .header-content {
        position: relative;
        z-index: 2;
    }
    
    .modern-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
        background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .back-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 15px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .back-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .modern-readonly-field {
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 1rem 1.25rem;
        position: relative;
        overflow: hidden;
        color: white;
    }
    
    .modern-readonly-field::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, #ed8936, #dd6b20);
    }
    
    .modern-readonly-field {
        cursor: help;
        transition: all 0.3s ease;
    }
    
    .modern-readonly-field:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    .modern-readonly-field small {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    @keyframes tooltipFadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    @keyframes tooltipFadeOut {
        from {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
        to {
            opacity: 0;
            transform: translateY(-10px) scale(0.95);
        }
    }
    
    @keyframes securityAlertSlide {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .modern-body {
        padding: 2.5rem;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .avatar-section {
        text-align: center;
        margin-bottom: 3rem;
        position: relative;
    }
    
    /* Container styles - Center align and full width */
    .modern-profile-container {
        padding-left: 0 !important;
        padding-right: 0 !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
    }
    
    /* Default container styling (no sidebar) */
    .modern-profile-container .container.profile-main-content {
        max-width: 1200px !important;
        margin-left: auto !important;
        margin-right: auto !important;
        padding-left: 20px !important;
        padding-right: 20px !important;
        width: 100% !important;
    }
    
    /* When sidebar is present, adjust the outer container */
    body.has-admin-sidebar .modern-profile-container {
        margin-left: 280px !important;
        width: calc(100% - 280px) !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    
    /* Center the inner container within the available space when sidebar is present */
    body.has-admin-sidebar .modern-profile-container .container.profile-main-content,
    body.has-admin-sidebar .modern-profile-container .profile-main-content.container {
        max-width: 1200px !important;
        margin-left: auto !important;
        margin-right: auto !important;
        padding-left: 20px !important;
        padding-right: 20px !important;
        width: 100% !important;
        display: block !important;
    }
    
    /* Override app.blade.php rules that might conflict */
    body.has-admin-sidebar .profile-main-content {
        max-width: 1200px !important;
        width: 100% !important;
        margin-left: auto !important;
        margin-right: auto !important;
        padding-left: 20px !important;
        padding-right: 20px !important;
    }
    
    /* Ensure rows and columns don't have extra margins */
    .modern-profile-container .profile-main-content .row {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    
    .modern-profile-container .profile-main-content .col-12 {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    
    .avatar-container {
        position: relative;
        display: inline-block;
        margin-bottom: 1rem;
    }
    
    .avatar-preview {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: 5px solid transparent;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) padding-box,
                    linear-gradient(135deg, #667eea, #764ba2) border-box;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
        transition: all 0.3s ease;
    }
    
    .avatar-preview:hover {
        transform: scale(1.05);
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.4);
    }
    
    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }
    
    .avatar-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-radius: 50%;
    }
    
    .camera-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: 3px solid white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: white;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .camera-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.6);
    }
    
    .form-section {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
        border-radius: 20px;
        padding: 0;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .form-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 25px 30px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    }
    
    .form-section .card-content {
        padding: 30px;
    }
    
    .section-icon {
        min-width: 50px;
        min-height: 50px;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        padding: 12px;
        box-sizing: border-box;
    }
    
    .section-icon i {
        display: block;
        line-height: 1;
    }
    
    .modern-form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .modern-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }
    
    .modern-input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.05);
        color: white;
    }
    
    .modern-input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    
    .modern-input:focus {
        outline: none;
        border-color: #667eea;
        background: rgba(255, 255, 255, 0.1);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        transform: translateY(-1px);
        color: white;
    }
    
    .modern-input.is-invalid {
        border-color: #ef4444;
        background: #fef2f2;
    }
    
    .modern-select {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        font-size: 0.95rem;
        background: rgba(255, 255, 255, 0.05);
        color: white;
        transition: all 0.3s ease;
    }
    
    .modern-select:focus {
        outline: none;
        border-color: #667eea;
        background: rgba(255, 255, 255, 0.1);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        color: white;
    }
    
    .modern-select option {
        background: #1a1a2e;
        color: white;
    }
    
    .modern-textarea {
        width: 100%;
        padding: 1rem;
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        font-size: 0.95rem;
        background: rgba(255, 255, 255, 0.05);
        color: white;
        transition: all 0.3s ease;
        resize: vertical;
        min-height: 120px;
    }
    
    .modern-textarea::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    
    .modern-textarea:focus {
        outline: none;
        border-color: #667eea;
        background: rgba(255, 255, 255, 0.1);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        color: white;
    }
    
    .google-link-card {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border: 2px solid #e5e7eb;
        border-radius: 15px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .google-linked {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        border-color: #22c55e;
    }
    
    .google-unlinked {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-color: #f59e0b;
    }
    
    .modern-btn {
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    
    .btn-primary-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        color: white;
    }
    
    .btn-secondary-modern {
        background: #f8fafc;
        color: #64748b;
        border: 2px solid #e2e8f0;
    }
    
    .btn-secondary-modern:hover {
        background: #e2e8f0;
        color: #475569;
        transform: translateY(-1px);
    }
    
    .btn-danger-modern {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
    }
    
    .btn-danger-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.6);
        color: white;
    }
    
    .btn-google {
        background: linear-gradient(135deg, #4285f4 0%, #34a853 25%, #fbbc05 50%, #ea4335 100%);
        color: white;
        border: none;
        padding: 0.875rem 1.75rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(66, 133, 244, 0.3);
        position: relative;
        overflow: hidden;
        min-width: 180px;
        justify-content: center;
    }
    
    .btn-google::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: all 0.6s;
    }
    
    .btn-google:hover::before {
        left: 100%;
    }
    
    .btn-google:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(66, 133, 244, 0.4);
        color: white;
    }
    
    .btn-google-unlink {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        min-width: 140px;
        justify-content: center;
    }
    
    .btn-google-unlink:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
        color: white;
        background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
    }
    
    .google-icon {
        width: 18px;
        height: 18px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }
    
    .google-status-card {
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        min-height: 140px;
    }
    
    .google-linked-card {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.2) 0%, rgba(16, 185, 129, 0.15) 100%);
        border-color: rgba(34, 197, 94, 0.3);
        box-shadow: 0 4px 15px rgba(34, 197, 94, 0.2);
    }
    
    .google-linked-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(34, 197, 94, 0.1) 25%, transparent 25%, transparent 75%, rgba(34, 197, 94, 0.1) 75%);
        background-size: 20px 20px;
        opacity: 0.3;
        animation: move-stripes 20s linear infinite;
    }
    
    @keyframes move-stripes {
        0% { transform: translateX(-20px); }
        100% { transform: translateX(20px); }
    }
    
    .google-unlinked-card {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(217, 119, 6, 0.15) 100%);
        border-color: rgba(245, 158, 11, 0.3);
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.2);
    }
    
    .google-status-card .text-muted {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    .google-status-card .text-warning {
        color: #fbbf24 !important;
    }
    
    .google-status-card .text-success {
        color: #34d399 !important;
    }
    
    .google-status-content {
        position: relative;
        z-index: 2;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .btn-success-modern {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
    }
    
    .btn-success-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.6);
        color: white;
    }
    
    .alert-modern {
        border-radius: 15px;
        padding: 1rem 1.5rem;
        border: none;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
    }
    
    .alert-success-modern {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        color: #166534;
        box-shadow: 0 4px 15px rgba(34, 197, 94, 0.2);
    }
    
    .alert-danger-modern {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.2);
    }
    
    .button-group {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
    }
    
    .help-text {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.7);
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .invalid-feedback {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    @media (max-width: 991.98px) {
        /* On mobile/tablet, sidebar is hidden, so reset margins */
        body.has-admin-sidebar .modern-profile-container {
            margin-left: 0 !important;
            width: 100% !important;
        }
    }
    
    @media (max-width: 768px) {
        .modern-profile-container {
            padding: 1rem 0;
        }
        
        .modern-body {
            padding: 1.5rem;
        }
        
        .modern-header {
            padding: 1.5rem;
        }
        
        .form-section {
            padding: 1.5rem;
        }
        
        .button-group {
            flex-direction: column;
        }
        
        .modern-btn {
            justify-content: center;
        }
        
        .modern-profile-container .container.profile-main-content,
        body.has-admin-sidebar .modern-profile-container .container.profile-main-content {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }
    }
</style>
@endpush

@section('content')
<div class="modern-profile-container">
    <!-- Main Content -->
    <div class="container profile-main-content">
        @if(session('success'))
        <div class="alert-modern alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="alert-close" data-bs-dismiss="alert">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert-modern alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="alert-close" data-bs-dismiss="alert">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif

        <div class="row g-4" style="margin-left: 0; margin-right: 0;">
            <div class="col-12" style="padding-left: 0; padding-right: 0;">
                <!-- Edit Profile Header Card -->
                <div class="info-card">
                    <div class="card-header">
                        <div class="card-header-content">
                            <div class="card-icon">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <div class="card-title">
                                <h3>Chỉnh sửa thông tin cá nhân</h3>
                                <p>Cập nhật thông tin cá nhân của bạn</p>
                            </div>
                        </div>
                        <a href="{{ route('profile.show') }}" class="btn-modern btn-small btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            <span>Quay lại</span>
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
                            @csrf
                            @method('PUT')

                    <!-- Avatar Section -->
                    <div class="info-card">
                        <div class="card-header">
                            <div class="card-header-content">
                                <div class="card-icon">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div class="card-title">
                                    <h3>Ảnh đại diện</h3>
                                    <p>Cập nhật ảnh đại diện của bạn</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-content">
                            <div class="avatar-section" style="text-align: center; margin-bottom: 2rem;">
                                <div class="avatar-container" style="position: relative; display: inline-block; margin-bottom: 1rem;">
                                    @if($user->avatar_url)
                                    <div class="avatar-preview" id="avatarPreview" style="width: 140px; height: 140px; border-radius: 50%; border: 5px solid transparent; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) padding-box, linear-gradient(135deg, #667eea, #764ba2) border-box; position: relative; overflow: hidden; box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);">
                                        <img src="{{ $user->avatar_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                    </div>
                                    @else
                                    <div class="avatar-preview" id="avatarPreview" style="width: 140px; height: 140px; border-radius: 50%; border: 5px solid transparent; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) padding-box, linear-gradient(135deg, #667eea, #764ba2) border-box; position: relative; overflow: hidden; box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);">
                                        <div class="avatar-placeholder" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-radius: 50%;">
                                            <i class="fas fa-user" style="font-size: 3rem; color: #94a3b8;"></i>
                                        </div>
                                    </div>
                                    @endif

                                    <label for="avatar" class="camera-btn" style="position: absolute; bottom: 5px; right: 5px; width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: 3px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: white; font-size: 1rem; transition: all 0.3s ease; box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                </div>

                                <input type="file" class="d-none @error('avatar') is-invalid @enderror" id="avatar" name="avatar" accept="image/*">

                                @error('avatar')
                                <div class="invalid-feedback d-block" style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror

                                <div class="help-text" style="font-size: 0.875rem; color: rgba(255, 255, 255, 0.7); margin-top: 0.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Chọn ảnh có kích thước nhỏ hơn 2MB (JPG, PNG, GIF)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information Section -->
                    <div class="info-card">
                        <div class="card-header">
                            <div class="card-header-content">
                                <div class="card-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="card-title">
                                    <h3>Thông tin cá nhân</h3>
                                    <p>Cập nhật thông tin cá nhân của bạn</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-content">

                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Name -->
                                        <div class="modern-form-group">
                                            <label class="modern-label">
                                                <i class="fas fa-user text-primary"></i>
                                                <span>Tên hiển thị <span class="text-danger">*</span></span>
                                            </label>
                                            <input type="text"
                                                class="modern-input @error('name') is-invalid @enderror"
                                                id="name"
                                                name="name"
                                                value="{{ old('name', $user->name) }}"
                                                placeholder="Nhập tên hiển thị"
                                                required>
                                            @error('name')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i>
                                                <span>{{ $message }}</span>
                                            </div>
                                            @enderror
                                        </div>

                                        <!-- Full Name -->
                                        <div class="modern-form-group">
                                            <label class="modern-label">
                                                <i class="fas fa-id-card text-success"></i>
                                                <span>Họ và tên đầy đủ</span>
                                            </label>
                                            <input type="text"
                                                class="modern-input @error('full_name') is-invalid @enderror"
                                                id="full_name"
                                                name="full_name"
                                                value="{{ old('full_name', $user->full_name) }}"
                                                placeholder="Nhập họ và tên đầy đủ">
                                            @error('full_name')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i>
                                                <span>{{ $message }}</span>
                                            </div>
                                            @enderror
                                        </div>

                                        <!-- Email -->
                                        <div class="modern-form-group">
                                            <label class="modern-label">
                                                <i class="fas fa-envelope text-info"></i>
                                                <span>Email <span class="text-danger">*</span></span>
                                            </label>
                                            <input type="email"
                                                class="modern-input @error('email') is-invalid @enderror"
                                                id="email"
                                                name="email"
                                                value="{{ old('email', $user->email) }}"
                                                placeholder="Nhập địa chỉ email"
                                                required>
                                            @error('email')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i>
                                                <span>{{ $message }}</span>
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Date of Birth -->
                                        <div class="modern-form-group">
                                            <label class="modern-label">
                                                <i class="fas fa-calendar-alt text-info"></i>
                                                <span>Ngày sinh</span>
                                            </label>
                                            <input type="date"
                                                class="modern-input @error('date_of_birth') is-invalid @enderror"
                                                id="date_of_birth"
                                                name="date_of_birth"
                                                value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}">
                                            @error('date_of_birth')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i>
                                                <span>{{ $message }}</span>
                                            </div>
                                            @enderror
                                        </div>

                                        <!-- Country -->
                                        <div class="modern-form-group">
                                            <label class="modern-label">
                                                <i class="fas fa-globe text-primary"></i>
                                                <span>Quốc gia</span>
                                            </label>
                                            <select class="modern-select @error('country') is-invalid @enderror" id="country" name="country">
                                                <option value="">Chọn quốc gia</option>
                                                <option value="Việt Nam" {{ old('country', $user->country) == 'Việt Nam' ? 'selected' : '' }}>Việt Nam</option>
                                                <option value="United States" {{ old('country', $user->country) == 'United States' ? 'selected' : '' }}>United States</option>
                                                <option value="China" {{ old('country', $user->country) == 'China' ? 'selected' : '' }}>China</option>
                                                <option value="Japan" {{ old('country', $user->country) == 'Japan' ? 'selected' : '' }}>Japan</option>
                                                <option value="South Korea" {{ old('country', $user->country) == 'South Korea' ? 'selected' : '' }}>South Korea</option>
                                                <option value="Thailand" {{ old('country', $user->country) == 'Thailand' ? 'selected' : '' }}>Thailand</option>
                                                <option value="Singapore" {{ old('country', $user->country) == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                                                <option value="Malaysia" {{ old('country', $user->country) == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                                <option value="Philippines" {{ old('country', $user->country) == 'Philippines' ? 'selected' : '' }}>Philippines</option>
                                                <option value="Indonesia" {{ old('country', $user->country) == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                            </select>
                                            @error('country')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i>
                                                <span>{{ $message }}</span>
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Application Settings Section -->
                            <div class="form-section">
                                <div class="section-title">
                                    <div class="section-icon">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                    <span>Cài đặt ứng dụng</span>
                                </div>

                                <div class="card-content">
                                    <div class="row">
                                    <div class="col-md-6">
                                        <!-- ID App -->
                                        <div class="modern-form-group">
                                            <label class="modern-label">
                                                <i class="fas fa-at text-warning"></i>
                                                <span>ID ứng dụng <span class="text-danger">*</span></span>
                                            </label>
                                            <input type="text"
                                                class="modern-input @error('id_app') is-invalid @enderror"
                                                id="id_app"
                                                name="id_app"
                                                value="{{ old('id_app', $user->id_app ?: '@' . strtolower(str_replace(' ', '', $user->name)) . rand(1000, 9999)) }}"
                                                readonly
                                                style="background-color: #f1f5f9; color: #1a202c !important;">
                                            @error('id_app')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i>
                                                <span>{{ $message }}</span>
                                            </div>
                                            @enderror
                                            <div class="help-text">
                                                <i class="fas fa-info-circle"></i>
                                                <span>ID ứng dụng được tự động tạo và không thể thay đổi</span>
                                            </div>
                                        </div>

                                        @if(Auth::user()->isAdmin())
                                        <!-- User Role (for admin only) -->
                                        <div class="modern-form-group">
                                            <label class="modern-label">
                                                <i class="fas fa-user-tag text-danger"></i>
                                                <span>Vai trò</span>
                                            </label>
                                            
                                            @if(Auth::user()->isSuperAdmin() && Auth::user()->id === $user->id)
                                                <!-- Super Admin cannot change their own role -->
                                                <div class="modern-readonly-field">
                                                    <i class="fas fa-crown text-warning me-2"></i>
                                                    <strong>Super Admin</strong>
                                                    <small class="text-muted d-block mt-1">
                                                        <i class="fas fa-lock me-1"></i>
                                                        Bạn không thể thay đổi vai trò của chính mình
                                                    </small>
                                                </div>
                                                <input type="hidden" name="user_role" value="{{ $user->user_role }}">
                                            @else
                                                <select class="modern-select @error('user_role') is-invalid @enderror" id="user_role" name="user_role">
                                                    <option value="viewer" {{ old('user_role', $user->user_role) == 'viewer' ? 'selected' : '' }}>Viewer</option>
                                                    <option value="player" {{ old('user_role', $user->user_role) == 'player' ? 'selected' : '' }}>Player</option>
                                                    <option value="admin" {{ old('user_role', $user->user_role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                                    @if(Auth::user()->isSuperAdmin() && $user->user_role === 'super_admin')
                                                        <option value="super_admin" selected>Super Admin</option>
                                                    @endif
                                                </select>
                                                @if(Auth::user()->isSuperAdmin() && Auth::user()->id !== $user->id && $user->isSuperAdmin())
                                                    <small class="text-warning d-block mt-1">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Cảnh báo: Bạn đang chỉnh sửa tài khoản Super Admin khác
                                                    </small>
                                                @endif
                                            @endif
                                            
                                            @error('user_role')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i>
                                                <span>{{ $message }}</span>
                                            </div>
                                            @enderror
                                        </div>
                                        @endif
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Google Account Link -->
                                        <div class="modern-form-group">
                                            <label class="modern-label">
                                                <i class="fab fa-google text-danger"></i>
                                                <span>Liên kết Google</span>
                                            </label>
                                            @if($user->google_id)
                                            <div class="google-status-card google-linked-card">
                                                <div class="google-status-content">
                                                    <!-- Header với icon và status -->
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="me-3">
                                                            <div class="d-flex align-items-center justify-content-center" 
                                                                 style="width: 50px; height: 50px; background: linear-gradient(135deg, #4285f4, #34a853); border-radius: 50%; color: white; font-size: 1.2rem;">
                                                                <i class="fab fa-google"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center mb-1">
                                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                                <span class="text-success fw-bold fs-6">Đã liên kết thành công</span>
                                                            </div>
                                                            <small class="text-muted">
                                                                <i class="fas fa-envelope me-1"></i>
                                                                <strong>Email:</strong> {{ $user->google_email ?? $user->email }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Button area -->
                                                    <div class="text-center">
                                                        <a href="{{ route('auth.google.unlink') }}"
                                                            class="btn-google-unlink"
                                                            onclick="return confirm('Bạn có chắc chắn muốn hủy liên kết tài khoản Google?')">
                                                            <i class="fas fa-unlink"></i>
                                                            <span>Hủy liên kết</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="help-text">
                                                <i class="fas fa-shield-alt text-success"></i>
                                                <span>Tài khoản được bảo mật với Google. Có thể đăng nhập bằng Google hoặc email/mật khẩu.</span>
                                            </div>
                                            @else
                                            <div class="google-status-card google-unlinked-card">
                                                <div class="google-status-content">
                                                    <!-- Header với icon và status -->
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="me-3">
                                                            <div class="d-flex align-items-center justify-content-center" 
                                                                 style="width: 50px; height: 50px; background: #f59e0b; border-radius: 50%; color: white; font-size: 1.2rem;">
                                                                <i class="fas fa-exclamation-triangle"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="mb-1">
                                                                <span class="text-warning fw-bold fs-6">Chưa liên kết với Google</span>
                                                            </div>
                                                            <small class="text-muted">
                                                                Liên kết để đăng nhập nhanh chóng và an toàn hơn
                                                            </small>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Button area -->
                                                    <div class="text-center">
                                                        <a href="{{ route('auth.google.link') }}" class="btn-google">
                                                            <div class="google-icon">
                                                                <i class="fab fa-google" style="color: #4285f4;"></i>
                                                            </div>
                                                            <span>Liên kết với Google</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="help-text">
                                                <i class="fas fa-info-circle text-primary"></i>
                                                <span>Liên kết với Google để có thêm lớp bảo mật và đăng nhập dễ dàng hơn.</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>

                            <!-- Bio Section -->
                            <div class="form-section">
                                <div class="section-title">
                                    <div class="section-icon">
                                        <i class="fas fa-comment-alt"></i>
                                    </div>
                                    <span>Giới thiệu bản thân</span>
                                </div>

                                <div class="card-content">
                                    <div class="modern-form-group">
                                    <label class="modern-label">
                                        <i class="fas fa-pen text-secondary"></i>
                                        <span>Mô tả về bản thân</span>
                                    </label>
                                    <textarea class="modern-textarea @error('bio') is-invalid @enderror"
                                        id="bio"
                                        name="bio"
                                        placeholder="Viết vài dòng giới thiệu về bản thân...">{{ old('bio', $user->bio) }}</textarea>
                                    @error('bio')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                    <div class="help-text">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Tối đa 1000 ký tự</span>
                                    </div>
                                </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="button-group" style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                                <a href="{{ route('profile.show') }}" class="btn-modern btn-secondary">
                                    <i class="fas fa-times"></i>
                                    <span>Hủy</span>
                                </a>
                                <button type="submit" class="btn-modern btn-primary" id="submitBtn">
                                    <i class="fas fa-save"></i>
                                    <span>Lưu thay đổi</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Avatar preview functionality
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatarPreview');

        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Check file size (2MB limit)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File quá lớn! Vui lòng chọn file nhỏ hơn 2MB.');
                    this.value = '';
                    return;
                }

                // Check file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Định dạng file không được hỗ trợ! Vui lòng chọn file JPG, PNG hoặc GIF.');
                    this.value = '';
                    return;
                }

                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.innerHTML = `<img src="${e.target.result}" alt="Avatar">`;
                };
                reader.readAsDataURL(file);
            }
        });

        // Form submission handler
        const form = document.getElementById('profileForm');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function(e) {
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Đang lưu...</span>';
            submitBtn.disabled = true;
            
            // Add loading class for visual feedback
            submitBtn.classList.add('loading');
        });

        // Input focus effects
        const inputs = document.querySelectorAll('.modern-input, .modern-select, .modern-textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });

        // Character count for bio
        const bioTextarea = document.getElementById('bio');
        if (bioTextarea) {
            const charCount = document.createElement('div');
            charCount.className = 'char-count text-muted';
            charCount.style.fontSize = '0.875rem';
            charCount.style.textAlign = 'right';
            charCount.style.marginTop = '0.5rem';
            
            function updateCharCount() {
                const current = bioTextarea.value.length;
                const max = 1000;
                charCount.textContent = `${current}/${max} ký tự`;
                
                if (current > max) {
                    charCount.style.color = '#ef4444';
                } else if (current > max * 0.8) {
                    charCount.style.color = '#f59e0b';
                } else {
                    charCount.style.color = '#6b7280';
                }
            }
            
            bioTextarea.addEventListener('input', updateCharCount);
            bioTextarea.parentElement.appendChild(charCount);
            updateCharCount();
        }

        // Smooth animations
        const sections = document.querySelectorAll('.form-section');
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            },
            { threshold: 0.1 }
        );

        sections.forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(20px)';
            section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(section);
        });

        // Super Admin role protection
        @if(Auth::user()->isSuperAdmin() && Auth::user()->id === $user->id)
        // Show tooltip on disabled role field
        const readonlyField = document.querySelector('.modern-readonly-field');
        if (readonlyField) {
            readonlyField.addEventListener('click', function() {
                // Show a modern tooltip
                const tooltip = document.createElement('div');
                tooltip.className = 'super-admin-tooltip';
                tooltip.innerHTML = `
                    <i class="fas fa-shield-alt me-2"></i>
                    <strong>Bảo vệ Super Admin</strong><br>
                    <small>Vai trò Super Admin không thể tự thay đổi để đảm bảo bảo mật hệ thống</small>
                `;
                tooltip.style.cssText = `
                    position: absolute;
                    background: linear-gradient(135deg, #1a202c, #2d3748);
                    color: white;
                    padding: 1rem;
                    border-radius: 12px;
                    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
                    z-index: 1000;
                    max-width: 250px;
                    font-size: 0.85rem;
                    line-height: 1.4;
                    animation: tooltipFadeIn 0.3s ease;
                `;
                
                const rect = readonlyField.getBoundingClientRect();
                tooltip.style.top = (rect.bottom + 10) + 'px';
                tooltip.style.left = rect.left + 'px';
                
                document.body.appendChild(tooltip);
                
                setTimeout(() => {
                    if (tooltip.parentNode) {
                        tooltip.style.animation = 'tooltipFadeOut 0.3s ease';
                        setTimeout(() => tooltip.remove(), 300);
                    }
                }, 3000);
                
                // Remove on click anywhere
                document.addEventListener('click', function removeTooltip(e) {
                    if (!tooltip.contains(e.target)) {
                        if (tooltip.parentNode) {
                            tooltip.remove();
                        }
                        document.removeEventListener('click', removeTooltip);
                    }
                }, true);
            });
        }
        @endif

        // Form submission protection
        document.querySelector('form').addEventListener('submit', function(e) {
            @if(Auth::user()->isSuperAdmin() && Auth::user()->id === $user->id)
                // Double check on form submit - prevent any tampering
                const roleInput = document.querySelector('input[name="user_role"]');
                if (roleInput && roleInput.value !== 'super_admin') {
                    e.preventDefault();
                    
                    // Show security warning
                    const alert = document.createElement('div');
                    alert.className = 'security-alert';
                    alert.innerHTML = `
                        <div class="alert-content">
                            <i class="fas fa-shield-alt text-danger me-2"></i>
                            <strong>Cảnh báo bảo mật!</strong><br>
                            <small>Phát hiện cố gắng thay đổi vai trò Super Admin. Hành động bị từ chối.</small>
                        </div>
                    `;
                    alert.style.cssText = `
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        background: linear-gradient(135deg, #fed7d7, #feb2b2);
                        border: 2px solid #fc8181;
                        color: #742a2a;
                        padding: 1rem 1.5rem;
                        border-radius: 15px;
                        box-shadow: 0 10px 30px rgba(239, 68, 68, 0.3);
                        z-index: 9999;
                        max-width: 300px;
                        animation: securityAlertSlide 0.5s ease;
                    `;
                    
                    document.body.appendChild(alert);
                    
                    setTimeout(() => {
                        alert.style.animation = 'securityAlertSlide 0.5s ease reverse';
                        setTimeout(() => alert.remove(), 500);
                    }, 4000);
                    
                    return false;
                }
            @endif
        });

        // Auto-save functionality (optional)
        let autoSaveTimeout;
        const autoSaveInputs = document.querySelectorAll('input:not([type="file"]), select, textarea');
        
        autoSaveInputs.forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    // Show auto-save indicator
                    const indicator = document.createElement('div');
                    indicator.className = 'auto-save-indicator';
                    indicator.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i>Đã lưu tự động';
                    indicator.style.cssText = `
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        background: white;
                        padding: 0.75rem 1rem;
                        border-radius: 8px;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                        font-size: 0.875rem;
                        z-index: 9999;
                        opacity: 0;
                        transition: opacity 0.3s ease;
                    `;
                    
                    document.body.appendChild(indicator);
                    
                    // Fade in
                    setTimeout(() => indicator.style.opacity = '1', 10);
                    
                    // Remove after 2 seconds
                    setTimeout(() => {
                        indicator.style.opacity = '0';
                        setTimeout(() => indicator.remove(), 300);
                    }, 2000);
                }, 2000);
            });
        });
    });
</script>
@endpush
@endsection