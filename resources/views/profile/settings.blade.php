@extends('layouts.app')

@section('title', __('app.profile.account_settings') . ' - ' . __('app.name'))

@push('styles')
<style>
    .settings-container {
        background: #000814;
        min-height: 100vh;
    }

    /* Header */
    .settings-header {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .settings-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #00E5FF, #22c55e, transparent);
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .header-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 30px rgba(34, 197, 94, 0.3);
    }

    .header-icon i {
        font-size: 1.75rem;
        color: white;
    }

    .header-text h1 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #FFFFFF;
        margin: 0 0 0.25rem 0;
    }

    .header-text p {
        color: #94a3b8;
        font-size: 0.95rem;
        margin: 0;
    }

    .back-link {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.9rem;
        padding: 0.6rem 1rem;
        border-radius: 10px;
        border: 1px solid rgba(148, 163, 184, 0.2);
        transition: all 0.3s ease;
    }

    .back-link:hover {
        color: #00E5FF;
        border-color: rgba(0, 229, 255, 0.3);
        background: rgba(0, 229, 255, 0.05);
    }

    /* Alert */
    .alert-success {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        color: #22c55e;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* Settings Grid */
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }
        
        .header-content {
            flex-direction: column;
            text-align: center;
        }
        
        .back-link {
            margin-left: 0;
            margin-top: 1rem;
        }
    }

    /* Settings Card */
    .settings-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .settings-card:hover {
        border-color: rgba(0, 229, 255, 0.3);
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .settings-card-link {
        display: block;
        text-decoration: none;
        padding: 1.5rem;
    }

    .settings-card-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .card-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        flex-shrink: 0;
    }

    .icon-cyan {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }

    .icon-purple {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }

    .icon-green {
        background: linear-gradient(135deg, #22c55e, #16a34a);
    }

    .icon-amber {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .icon-red {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .icon-blue {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .card-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #FFFFFF;
        margin: 0 0 0.25rem 0;
    }

    .card-description {
        color: #64748b;
        font-size: 0.85rem;
        margin: 0;
        line-height: 1.5;
    }

    .card-arrow {
        margin-left: auto;
        color: #64748b;
        transition: all 0.3s ease;
    }

    .settings-card:hover .card-arrow {
        color: #00E5FF;
        transform: translateX(5px);
    }

    .card-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-badge.success {
        background: rgba(34, 197, 94, 0.15);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .status-badge.warning {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .status-badge.info {
        background: rgba(0, 229, 255, 0.15);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.3);
    }

    .status-badge.danger {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    /* Disabled Card */
    .settings-card.disabled {
        opacity: 0.6;
        pointer-events: none;
    }

    .settings-card.disabled .settings-card-link {
        cursor: not-allowed;
    }

    .coming-soon {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.6rem;
        background: rgba(100, 116, 139, 0.2);
        border: 1px solid rgba(100, 116, 139, 0.3);
        border-radius: 6px;
        font-size: 0.7rem;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Account Overview */
    .account-overview {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .overview-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    }

    .overview-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid rgba(0, 229, 255, 0.3);
    }

    .overview-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .overview-avatar-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.25rem;
    }

    .overview-info h3 {
        color: #FFFFFF;
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0 0 0.25rem 0;
    }

    .overview-info p {
        color: #64748b;
        font-size: 0.85rem;
        margin: 0;
    }

    .overview-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .stat-item {
        text-align: center;
        padding: 0.75rem;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }

    .stat-value {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #00E5FF;
    }

    .stat-label {
        color: #64748b;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    @media (max-width: 576px) {
        .overview-stats {
            grid-template-columns: 1fr;
        }
    }

    /* Danger Zone */
    .danger-zone {
        background: linear-gradient(145deg, rgba(239, 68, 68, 0.05) 0%, rgba(0, 0, 34, 0.95) 100%);
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
        margin-top: 2rem;
    }

    .danger-zone-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .danger-zone-header i {
        color: #ef4444;
        font-size: 1.25rem;
    }

    .danger-zone-header h3 {
        color: #ef4444;
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .danger-zone p {
        color: #94a3b8;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }

    .btn-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-danger:hover {
        background: rgba(239, 68, 68, 0.2);
        border-color: #ef4444;
    }
</style>
@endpush

@section('content')
<div class="settings-container">
    <div class="max-w-5xl mx-auto px-4 py-6">
        <!-- Header -->
        <div class="settings-header">
            <div class="header-content">
                <div class="header-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="header-text">
                    <h1>{{ __('app.profile.account_settings') }}</h1>
                    <p>{{ __('app.profile.account_settings_description') }}</p>
                </div>
                <a href="{{ route('profile.show') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    <span>{{ __('app.common.back') }}</span>
                </a>
            </div>
        </div>

        <!-- Alert -->
        @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- Account Overview -->
        <div class="account-overview">
            <div class="overview-header">
                <div class="overview-avatar">
                    @if($user->avatar)
                    <img src="{{ get_avatar_url($user->avatar) }}" alt="Avatar">
                    @else
                    <div class="overview-avatar-placeholder">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    @endif
                </div>
                <div class="overview-info">
                    <h3>{{ $user->full_name ?: $user->name }}</h3>
                    <p>{{ $user->email }}</p>
                </div>
            </div>
            <div class="overview-stats">
                <div class="stat-item">
                    <div class="stat-value">{{ (int) $user->created_at->diffInDays(now()) }}</div>
                    <div class="stat-label">{{ __('app.profile.activity_days') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $user->teams->count() ?? 0 }}</div>
                    <div class="stat-label">{{ __('app.profile.teams_joined') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">
                        @if($user->email_verified_at)
                        <i class="fas fa-check-circle text-green-400"></i>
                        @else
                        <i class="fas fa-times-circle text-red-400"></i>
                        @endif
                    </div>
                    <div class="stat-label">{{ __('app.profile.email_status') }}</div>
                </div>
            </div>
        </div>

        <!-- Settings Grid -->
        <div class="settings-grid">
            <!-- Edit Profile -->
            <div class="settings-card">
                <a href="{{ route('profile.edit') }}" class="settings-card-link">
                    <div class="settings-card-header">
                        <div class="card-icon icon-cyan">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div>
                            <h3 class="card-title">{{ __('app.common.edit') }} {{ __('app.profile.personal_info') }}</h3>
                            <p class="card-description">{{ __('app.profile.update_personal_info') }}</p>
                        </div>
                        <i class="fas fa-chevron-right card-arrow"></i>
                    </div>
                    <div class="card-status">
                        @if($user->full_name && $user->phone)
                        <span class="status-badge success">
                            <i class="fas fa-check-circle"></i>
                            {{ __('app.profile.profile_complete') ?? 'Hoàn thiện' }}
                        </span>
                        @else
                        <span class="status-badge warning">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ __('app.profile.profile_incomplete') ?? 'Chưa hoàn thiện' }}
                        </span>
                        @endif
                    </div>
                </a>
            </div>

            <!-- Change Password -->
            <div class="settings-card">
                <a href="{{ route('profile.change-password') }}" class="settings-card-link">
                    <div class="settings-card-header">
                        <div class="card-icon icon-purple">
                            <i class="fas fa-key"></i>
                        </div>
                        <div>
                            <h3 class="card-title">{{ __('app.profile.change_password') }}</h3>
                            <p class="card-description">{{ __('app.profile.update_password_security') }}</p>
                        </div>
                        <i class="fas fa-chevron-right card-arrow"></i>
                    </div>
                    <div class="card-status">
                        <span class="status-badge info">
                            <i class="fas fa-shield-alt"></i>
                            {{ __('app.profile.password_protected') ?? 'Được bảo vệ' }}
                        </span>
                    </div>
                </a>
            </div>

            <!-- Security -->
            <div class="settings-card disabled">
                <div class="settings-card-link">
                    <div class="settings-card-header">
                        <div class="card-icon icon-green">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h3 class="card-title">{{ __('app.profile.security') }}</h3>
                            <p class="card-description">{{ __('app.profile.two_factor_auth') }}</p>
                        </div>
                        <span class="coming-soon">
                            <i class="fas fa-clock"></i>
                            Coming Soon
                        </span>
                    </div>
                    <div class="card-status">
                        <span class="status-badge warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ __('app.profile.2fa_disabled') ?? '2FA chưa bật' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="settings-card disabled">
                <div class="settings-card-link">
                    <div class="settings-card-header">
                        <div class="card-icon icon-amber">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div>
                            <h3 class="card-title">{{ __('app.profile.notifications') }}</h3>
                            <p class="card-description">{{ __('app.profile.notification_settings') }}</p>
                        </div>
                        <span class="coming-soon">
                            <i class="fas fa-clock"></i>
                            Coming Soon
                        </span>
                    </div>
                    <div class="card-status">
                        <span class="status-badge success">
                            <i class="fas fa-check-circle"></i>
                            {{ __('app.profile.notifications_enabled') ?? 'Đã bật' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Privacy -->
            <div class="settings-card disabled">
                <div class="settings-card-link">
                    <div class="settings-card-header">
                        <div class="card-icon icon-blue">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div>
                            <h3 class="card-title">{{ __('app.profile.privacy') ?? 'Quyền riêng tư' }}</h3>
                            <p class="card-description">{{ __('app.profile.privacy_settings') ?? 'Quản lý ai có thể xem thông tin của bạn' }}</p>
                        </div>
                        <span class="coming-soon">
                            <i class="fas fa-clock"></i>
                            Coming Soon
                        </span>
                    </div>
                </div>
            </div>

            <!-- Connected Accounts -->
            <div class="settings-card disabled">
                <div class="settings-card-link">
                    <div class="settings-card-header">
                        <div class="card-icon" style="background: linear-gradient(135deg, #4285f4, #34a853);">
                            <i class="fas fa-link"></i>
                        </div>
                        <div>
                            <h3 class="card-title">{{ __('app.profile.connected_accounts') ?? 'Tài khoản liên kết' }}</h3>
                            <p class="card-description">{{ __('app.profile.manage_connected_accounts') ?? 'Quản lý các tài khoản đã liên kết' }}</p>
                        </div>
                        <span class="coming-soon">
                            <i class="fas fa-clock"></i>
                            Coming Soon
                        </span>
                    </div>
                    <div class="card-status">
                        @if($user->google_id)
                        <span class="status-badge success">
                            <i class="fab fa-google"></i>
                            Google
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="danger-zone">
            <div class="danger-zone-header">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>{{ __('app.profile.danger_zone') ?? 'Vùng nguy hiểm' }}</h3>
            </div>
            <p>{{ __('app.profile.danger_zone_desc') ?? 'Các hành động dưới đây không thể hoàn tác. Hãy cân nhắc kỹ trước khi thực hiện.' }}</p>
            <button type="button" class="btn-danger" disabled>
                <i class="fas fa-trash-alt"></i>
                <span>{{ __('app.profile.delete_account') ?? 'Xóa tài khoản' }}</span>
            </button>
        </div>
    </div>
</div>
@endsection
