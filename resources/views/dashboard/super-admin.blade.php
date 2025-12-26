@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@push('styles')
<style>
    /* Modern Dashboard Styles */
    .modern-dashboard-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        position: relative;
        overflow: hidden;
    }

    .modern-dashboard-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="%23ffffff06" points="0,1000 1000,0 1000,1000"/></svg>');
        pointer-events: none;
    }

    .modern-header-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 25px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
        animation: slideInDown 0.8s ease-out;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modern-header-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
        background-size: 300% 100%;
        animation: gradientShift 3s ease infinite;
    }

    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .admin-avatar {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .admin-avatar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0.1) 100%);
        animation: shimmer 2s ease-in-out infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .modern-title {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #2d3748, #4a5568);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .modern-subtitle {
        color: #718096;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .modern-time-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 20px;
        padding: 1.5rem;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
    }

    .modern-stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.8s ease-out;
        animation-fill-mode: both;
    }

    .modern-stat-card:nth-child(1) { animation-delay: 0.1s; }
    .modern-stat-card:nth-child(2) { animation-delay: 0.2s; }
    .modern-stat-card:nth-child(3) { animation-delay: 0.3s; }
    .modern-stat-card:nth-child(4) { animation-delay: 0.4s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modern-stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    .stat-icon-container {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .stat-icon-primary { background: linear-gradient(135deg, #667eea, #764ba2); }
    .stat-icon-success { background: linear-gradient(135deg, #48bb78, #38a169); }
    .stat-icon-info { background: linear-gradient(135deg, #4299e1, #3182ce); }
    .stat-icon-warning { background: linear-gradient(135deg, #ed8936, #dd6b20); }

    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        color: #2d3748;
        margin: 0;
        line-height: 1;
    }

    .stat-label {
        color: #718096;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .stat-change {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .stat-change.positive {
        background: rgba(72, 187, 120, 0.1);
        color: #38a169;
    }

    .stat-change.negative {
        background: rgba(245, 101, 101, 0.1);
        color: #e53e3e;
    }

    .modern-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        animation: fadeInUp 0.8s ease-out;
        animation-fill-mode: both;
    }

    .modern-card-header {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.5);
    }

    .modern-card-title {
        font-weight: 700;
        color: #2d3748;
        margin: 0;
        font-size: 1.25rem;
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .modern-dashboard-container {
            padding: 1rem 0;
        }

        .modern-header-card {
            padding: 1.5rem;
            margin: 0 1rem 1.5rem;
        }

        .modern-title {
            font-size: 1.8rem;
        }

        .stat-number {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="modern-dashboard-container">
    <div class="container-fluid py-4">
        <!-- Modern Header Card -->
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="modern-header-card">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center">
                                <div class="admin-avatar me-4">
                                    <i class="fas fa-crown fa-2x text-white"></i>
                                </div>
                                <div>
                                    <h1 class="modern-title">{{ __('app.dashboard.welcome') }}, {{ Auth::user()->display_name }}!</h1>
                                    <p class="modern-subtitle mb-2">{{ __('app.dashboard.admin_dashboard') }}</p>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-shield-alt me-2"></i>
                                        {{ __('app.dashboard.manage_users_teams_esports') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="modern-time-card">
                                <div class="text-center">
                                    <h2 class="text-primary mb-1" id="current-time">{{ now()->timezone('Asia/Ho_Chi_Minh')->format('H:i:s') }}</h2>
                                    <p class="text-muted mb-1" id="current-date">{{ now()->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y') }}</p>
                                    <small class="text-primary" id="current-day">{{ now()->timezone('Asia/Ho_Chi_Minh')->locale(app()->getLocale())->dayName }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Statistics Cards -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-11">
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                        <div class="modern-stat-card">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <p class="stat-label mb-2">
                                        <i class="fas fa-users me-2"></i>{{ __('app.dashboard.total_users') }}
                                    </p>
                                    <h2 class="stat-number">{{ number_format($stats['total_users']) }}</h2>
                                    <div class="d-flex align-items-center mt-2">
                                        <span class="stat-change positive">
                                            <i class="fas fa-arrow-up me-1"></i>+12%
                                        </span>
                                        <small class="text-muted ms-2">{{ __('app.dashboard.good_growth') }}</small>
                                    </div>
                                </div>
                                <div class="stat-icon-container stat-icon-primary">
                                    <i class="fas fa-users fa-2x text-white"></i>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-top">
                                <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
                                    <small class="text-primary fw-semibold">
                                        <i class="fas fa-external-link-alt me-1"></i>{{ __('app.dashboard.view_details') }}
                                    </small>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                        <div class="modern-stat-card">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <p class="stat-label mb-2">
                                        <i class="fas fa-user-shield me-2"></i>Admin
                                    </p>
                                    <h2 class="stat-number">{{ number_format($stats['total_admins']) }}</h2>
                                    <div class="d-flex align-items-center mt-2">
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>{{ $stats['total_admins'] > 0 ? __('app.dashboard.active') : __('app.common.no') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="stat-icon-container stat-icon-success">
                                    <i class="fas fa-user-shield fa-2x text-white"></i>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-top">
                                <a href="{{ route('admin.users.index') }}?role=admin" class="text-decoration-none">
                                    <small class="text-success fw-semibold">
                                        <i class="fas fa-cogs me-1"></i>{{ __('app.dashboard.manage_users') }}
                                    </small>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                        <div class="modern-stat-card">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <p class="stat-label mb-2">
                                        <i class="fas fa-gamepad me-2"></i>{{ __('app.dashboard.players') }}
                                    </p>
                                    <h2 class="stat-number">{{ number_format($stats['total_players']) }}</h2>
                                    <div class="d-flex align-items-center mt-2">
                                        <span class="badge bg-info">
                                            <i class="fas fa-trophy me-1"></i>{{ __('app.dashboard.competing') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="stat-icon-container stat-icon-info">
                                    <i class="fas fa-gamepad fa-2x text-white"></i>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-top">
                                <a href="{{ route('admin.users.index') }}?role=player" class="text-decoration-none">
                                    <small class="text-info fw-semibold">
                                        <i class="fas fa-users me-1"></i>{{ __('app.dashboard.view_details') }}
                                    </small>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                        <div class="modern-stat-card">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <p class="stat-label mb-2">
                                        <i class="fas fa-eye me-2"></i>{{ __('app.nav.players') }}
                                    </p>
                                    <h2 class="stat-number">{{ number_format($stats['total_viewers']) }}</h2>
                                    <div class="d-flex align-items-center mt-2">
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-broadcast-tower me-1"></i>{{ __('app.dashboard.active') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="stat-icon-container stat-icon-warning">
                                    <i class="fas fa-eye fa-2x text-white"></i>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-top">
                                <a href="{{ route('admin.users.index') }}?role=viewer" class="text-decoration-none">
                                    <small class="text-warning fw-semibold">
                                        <i class="fas fa-eye me-1"></i>{{ __('app.dashboard.view_details') }}
                                    </small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

        <!-- Data Analytics Section -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-11">
                <div class="row">
                    <!-- User Growth Chart -->
                    <div class="col-xl-6 col-lg-12 mb-4">
                        <div class="modern-card" style="animation-delay: 0.4s;">
                            <div class="modern-card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="modern-card-title">
                                        <i class="fas fa-chart-line text-primary me-2"></i>User Growth
                                    </h3>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Export</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="refreshUserGrowthChart()"><i class="fas fa-refresh me-2"></i>Refresh</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <canvas id="userGrowthChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Distribution Chart -->
                    <div class="col-xl-6 col-lg-12 mb-4">
                        <div class="modern-card" style="animation-delay: 0.45s;">
                            <div class="modern-card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="modern-card-title">
                                        <i class="fas fa-chart-bar text-primary me-2"></i>Activity Distribution
                                    </h3>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Export</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="refreshActivityChart()"><i class="fas fa-refresh me-2"></i>Refresh</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <canvas id="activityChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Analytics and Recent Activity Section -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-11">
                <div class="row align-items-stretch">
                    <!-- Role Distribution Chart -->
                    <div class="col-xl-4 col-lg-6 mb-4 d-flex">
                        <div class="modern-card w-100" style="animation-delay: 0.5s; display: flex; flex-direction: column;">
                            <div class="modern-card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="modern-card-title">
                                        <i class="fas fa-chart-pie text-primary me-2"></i>{{ __('app.dashboard.activity_statistics') }}
                                    </h3>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>{{ __('app.dashboard.view_report') }}</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="refreshRoleChart()"><i class="fas fa-refresh me-2"></i>{{ __('app.common.refresh') }}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 flex-grow-1">
                                @foreach($stats['user_roles_distribution'] as $role)
                                <div class="d-flex align-items-center mb-4 p-3 rounded-3 role-item" 
                                     style="background: rgba(102, 126, 234, 0.05); transition: all 0.3s ease; cursor: pointer;"
                                     onmouseover="this.style.background='rgba(102, 126, 234, 0.1)'; this.style.transform='translateX(5px)'"
                                     onmouseout="this.style.background='rgba(102, 126, 234, 0.05)'; this.style.transform='translateX(0)'">
                                    <div class="me-3">
                                        @switch($role->user_role)
                                        @case('super_admin')
                                        <div class="stat-icon-container stat-icon-warning" style="width: 50px; height: 50px;">
                                            <i class="fas fa-crown text-white"></i>
                                        </div>
                                        @break
                                        @case('admin')
                                        <div class="stat-icon-container stat-icon-success" style="width: 50px; height: 50px;">
                                            <i class="fas fa-user-shield text-white"></i>
                                        </div>
                                        @break
                                        @case('player')
                                        <div class="stat-icon-container stat-icon-info" style="width: 50px; height: 50px;">
                                            <i class="fas fa-gamepad text-white"></i>
                                        </div>
                                        @break
                                        @case('viewer')
                                        <div class="stat-icon-container stat-icon-primary" style="width: 50px; height: 50px;">
                                            <i class="fas fa-eye text-white"></i>
                                        </div>
                                        @break
                                        @default
                                        <div class="bg-secondary rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-question text-white"></i>
                                        </div>
                                        @endswitch
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-2 fw-bold">
                                            @switch($role->user_role)
                                            @case('super_admin')
                                            Super Admin
                                            @break
                                            @case('admin')
                                            Admin
                                            @break
                                            @case('player')
                                            Player
                                            @break
                                            @case('viewer')
                                            Viewer
                                            @break
                                            @default
                                            {{ ucfirst($role->user_role) }}
                                            @endswitch
                                        </h6>
                                        <div class="progress mb-1" style="height: 8px; border-radius: 10px;">
                                            <div class="progress-bar progress-bar-animated"
                                                data-width="{{ ($role->count / $stats['total_users']) * 100 }}"
                                                style="width: 0%; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 10px;"
                                                role="progressbar"></div>
                                        </div>
                                        <small class="text-muted">{{ number_format(($role->count / $stats['total_users']) * 100, 1) }}% {{ __('app.dashboard.of_total') }}</small>
                                    </div>
                                    <div class="text-end ms-3">
                                        <h4 class="mb-0 fw-bold text-primary">{{ $role->count }}</h4>
                                        <small class="text-muted">{{ __('app.dashboard.users') }}</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Recent Users -->
                    <div class="col-xl-8 col-lg-6 mb-4 d-flex" id="recentUsersSection">
                        <div class="modern-card w-100" style="animation-delay: 0.6s; display: flex; flex-direction: column;">
                            <div class="modern-card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="modern-card-title mb-0 d-flex align-items-center">
                                        <i class="fas fa-users text-primary me-2"></i>
                                        {{ __('app.dashboard.recent_users') }}
                                    </h3>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" onclick="refreshUsersList()">
                                            <i class="fas fa-sync-alt me-1"></i>{{ __('app.common.refresh') }}
                                        </button>
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-arrow-right me-1"></i>{{ __('app.dashboard.view_all') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 flex-grow-1 d-flex flex-column" id="recentUsersContent">
                                @include('dashboard.partials.recent-users', ['recent_users' => $stats['recent_users']])
                </div>
            </div>
        </div>
        
    </div>

</div>

<!-- Enhanced Custom Styles -->
<style>

    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%) !important;
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important;
    }

    .bg-gradient-secondary {
        background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%) !important;
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .progress {
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar {
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
    }

    .avatar-sm {
        font-size: 14px;
        font-weight: 600;
    }
</style>

<script>
    function manageTournaments() {
        Swal.fire({
            title: '{{ __("app.dashboard.manage_tournaments_title") }}',
            text: '{{ __("app.dashboard.manage_tournaments_text") }}',
            icon: 'info',
            confirmButtonText: '{{ __("app.dashboard.understood") }}',
            confirmButtonColor: '#667eea'
        });
    }

    function manageGames() {
        Swal.fire({
            title: '{{ __("app.dashboard.manage_games") }}',
            text: '{{ __("app.dashboard.manage_games_text") }}',
            icon: 'info',
            confirmButtonText: '{{ __("app.dashboard.understood") }}',
            confirmButtonColor: '#667eea'
        });
    }

    function systemSettings() {
        Swal.fire({
            title: '{{ __("app.dashboard.system_settings_title") }}',
            text: '{{ __("app.dashboard.system_settings_text") }}',
            icon: 'info',
            confirmButtonText: '{{ __("app.dashboard.understood") }}',
            confirmButtonColor: '#667eea'
        });
    }

    function systemLogs() {
        Swal.fire({
            title: '{{ __("app.dashboard.system_logs") }}',
            text: '{{ __("app.dashboard.system_logs_text") }}',
            icon: 'info',
            confirmButtonText: '{{ __("app.dashboard.understood") }}',
            confirmButtonColor: '#667eea'
        });
    }

    function createBackup() {
        Swal.fire({
            title: '{{ __("app.dashboard.backup_restore") }}',
            text: '{{ __("app.dashboard.backup_restore_text") }}',
            icon: 'info',
            confirmButtonText: '{{ __("app.dashboard.understood") }}',
            confirmButtonColor: '#667eea'
        });
    }

    function analyticsReport() {
        Swal.fire({
            title: '{{ __("app.dashboard.analytics_report") }}',
            text: '{{ __("app.dashboard.analytics_report_text") }}',
            icon: 'info',
            confirmButtonText: '{{ __("app.dashboard.understood") }}',
            confirmButtonColor: '#667eea'
        });
    }

    // Animate progress bars on page load
    document.addEventListener('DOMContentLoaded', function() {
        const progressBars = document.querySelectorAll('.progress-bar[data-width]');
        progressBars.forEach(function(bar) {
            const width = bar.getAttribute('data-width');
            setTimeout(function() {
                bar.style.width = width + '%';
            }, 500);
        });
    });

    function updateUserStatus(userId, status, action) {
        $.ajax({
            url: `{{ route('admin.users.update-status', ['user' => '__ID__']) }}`.replace('__ID__', userId),
            method: 'PATCH',
            data: {
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: '{{ __("app.common.success") }}!',
                        text: response.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload page to update UI
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: '{{ __("app.common.error") }}!',
                        text: response.message || '{{ __("app.dashboard.update_status_error") }}',
                        icon: 'error'
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = '{{ __("app.dashboard.connection_error") }}';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                Swal.fire({
                    title: 'Lỗi!',
                    text: errorMessage,
                    icon: 'error'
                });
            }
        });
    }

    // User management handlers (single consolidated binding)
    $(document).ready(function() {
        $(document).on('click', '.view-user-btn', function(e) {
            e.preventDefault();
            const userId = $(this).data('user-id');
            console.log('View user clicked:', userId);
            const url = `{{ route('admin.users.show', ['user' => '__ID__']) }}`.replace('__ID__', userId);
            window.open(url, '_blank');
        });

        $(document).on('click', '.edit-user-btn', function(e) {
            e.preventDefault();
            const userId = $(this).data('user-id');
            console.log('Edit user clicked:', userId);
            const url = `{{ route('admin.users.edit', ['user' => '__ID__']) }}`.replace('__ID__', userId);
            window.location.href = url;
        });

        $(document).on('click', '.change-status-btn', function(e) {
            e.preventDefault();
            const userId = $(this).data('user-id');
            const status = $(this).data('status');
            const action = $(this).data('action');

            console.log('Change status clicked:', {
                userId,
                status,
                action
            });

            // Close dropdown
            $(this).closest('.dropdown-menu').siblings('.dropdown-toggle').dropdown('hide');

            // Show confirmation with SweetAlert2
            Swal.fire({
                title: `{{ __("app.common.confirm") }} ${action}`,
                text: `{{ __("app.dashboard.confirm_action") }} ${action.toLowerCase()}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: status === 'banned' ? '#d33' : '#f39c12',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __("app.common.confirm") }}',
                cancelButtonText: '{{ __("app.common.cancel") }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateUserStatus(userId, status, action);
                }
            });
        });
    });

    function updateUserStatus(userId, status, action) {
        $.ajax({
            url: `{{ route('admin.users.update-status', ['user' => '__ID__']) }}`.replace('__ID__', userId),
            method: 'PATCH',
            data: {
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: '{{ __("app.common.success") }}!',
                        text: response.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload page to update UI
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: '{{ __("app.common.error") }}!',
                        text: response.message || '{{ __("app.dashboard.update_status_error") }}',
                        icon: 'error'
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = '{{ __("app.dashboard.connection_error") }}';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                Swal.fire({
                    title: 'Lỗi!',
                    text: errorMessage,
                    icon: 'error'
                });
            }
        });
    }

    // Animate progress bars on page load (with smooth transition)
    document.addEventListener('DOMContentLoaded', function() {
        const progressBars = document.querySelectorAll('.progress-bar[data-width]');
        progressBars.forEach(function(bar) {
            const width = bar.getAttribute('data-width');
            setTimeout(function() {
                bar.style.width = width + '%';
                bar.style.transition = 'width 1s ease-in-out';
            }, 300);
        });

        // Live clock update (Asia/Ho_Chi_Minh)
        function updateClock() {
            const now = new Date();
            const optionsDate = { timeZone: 'Asia/Ho_Chi_Minh', day: '2-digit', month: '2-digit', year: 'numeric' };
            const optionsTime = { timeZone: 'Asia/Ho_Chi_Minh', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            const optionsDay  = { timeZone: 'Asia/Ho_Chi_Minh', weekday: 'long' };

            // Get current locale from Laravel
            const currentLocale = '{{ app()->getLocale() }}' === 'vi' ? 'vi-VN' : 'en-US';

            const time = new Intl.DateTimeFormat(currentLocale, optionsTime).format(now);
            const date = new Intl.DateTimeFormat(currentLocale, optionsDate).format(now);
            const day  = new Intl.DateTimeFormat(currentLocale, optionsDay).format(now);

            const elTime = document.getElementById('current-time');
            const elDate = document.getElementById('current-date');
            const elDay  = document.getElementById('current-day');
            if (elTime) elTime.textContent = time;
            if (elDate) elDate.textContent = date;
            if (elDay)  elDay.textContent  = day;
        }
        updateClock();
        setInterval(updateClock, 1000);
    });
</script>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // User Growth Chart
    let userGrowthChart = null;
    function initUserGrowthChart() {
        const ctx = document.getElementById('userGrowthChart');
        if (!ctx) return;
        
        const ctx2d = ctx.getContext('2d');
        if (userGrowthChart) {
            userGrowthChart.destroy();
        }
        
        // Real data from backend
        const labels = @json($stats['user_growth_labels'] ?? []);
        const userGrowthData = @json($stats['user_growth_data'] ?? []);
        const data = {
            labels: labels,
            datasets: [{
                label: 'New Users',
                data: userGrowthData,
                borderColor: 'rgb(102, 126, 234)',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        };
        
        userGrowthChart = new Chart(ctx2d, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    
    // Activity Distribution Chart
    let activityChart = null;
    function initActivityChart() {
        const ctx = document.getElementById('activityChart');
        if (!ctx) return;
        
        const ctx2d = ctx.getContext('2d');
        if (activityChart) {
            activityChart.destroy();
        }
        
        // Real data from backend
        const data = {
            labels: ['Users', 'Teams', 'Tournaments', 'Games'],
            datasets: [{
                label: 'Count',
                data: [
                    {{ $stats['total_users'] ?? 0 }},
                    {{ $stats['total_teams'] ?? 0 }},
                    {{ $stats['active_tournaments'] ?? 0 }},
                    {{ $stats['total_games'] ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(102, 126, 234, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(168, 85, 247, 0.8)'
                ],
                borderColor: [
                    'rgb(102, 126, 234)',
                    'rgb(34, 197, 94)',
                    'rgb(245, 158, 11)',
                    'rgb(168, 85, 247)'
                ],
                borderWidth: 2
            }]
        };
        
        activityChart = new Chart(ctx2d, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    
    // Refresh functions
    function refreshUserGrowthChart() {
        initUserGrowthChart();
    }
    
    function refreshActivityChart() {
        initActivityChart();
    }
    
    
    // Initialize charts on page load
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            initUserGrowthChart();
            initActivityChart();
        }, 500);
        
        // AJAX Pagination for Recent Users
        const recentUsersContent = document.getElementById('recentUsersContent');
        if (recentUsersContent) {
            // Handle pagination links
            recentUsersContent.addEventListener('click', function(e) {
                const paginationLink = e.target.closest('.pagination a');
                if (paginationLink && paginationLink.href) {
                    e.preventDefault();
                    
                    const url = new URL(paginationLink.href);
                    const page = url.searchParams.get('users_page') || url.searchParams.get('page');
                    
                    // Show loading state
                    recentUsersContent.style.opacity = '0.5';
                    recentUsersContent.style.pointerEvents = 'none';
                    
                    // Fetch new content
                    fetch('{{ route("dashboard.recent-users") }}?users_page=' + (page || 1), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        recentUsersContent.innerHTML = html;
                        recentUsersContent.style.opacity = '1';
                        recentUsersContent.style.pointerEvents = 'auto';
                        
                        // Update URL without reload
                        if (window.history && window.history.pushState) {
                            const newUrl = new URL(window.location.href);
                            if (page) {
                                newUrl.searchParams.set('users_page', page);
                            } else {
                                newUrl.searchParams.delete('users_page');
                            }
                            window.history.pushState({}, '', newUrl);
                        }
                    })
                    .catch(error => {
                        console.error('Error loading users:', error);
                        recentUsersContent.style.opacity = '1';
                        recentUsersContent.style.pointerEvents = 'auto';
                    });
                }
            });
        }
        
        // Refresh users list function
        window.refreshUsersList = function() {
            const recentUsersContent = document.getElementById('recentUsersContent');
            if (recentUsersContent) {
                const url = new URL(window.location.href);
                const page = url.searchParams.get('users_page') || 1;
                
                recentUsersContent.style.opacity = '0.5';
                recentUsersContent.style.pointerEvents = 'none';
                
                fetch('{{ route("dashboard.recent-users") }}?users_page=' + page, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    recentUsersContent.innerHTML = html;
                    recentUsersContent.style.opacity = '1';
                    recentUsersContent.style.pointerEvents = 'auto';
                })
                .catch(error => {
                    console.error('Error refreshing users:', error);
                    recentUsersContent.style.opacity = '1';
                    recentUsersContent.style.pointerEvents = 'auto';
                });
            }
        };
    });
</script>
@endsection