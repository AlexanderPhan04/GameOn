@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Enhanced Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 20px;">
                <div class="card-body text-white py-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-4">
                                    @if(Auth::user()->avatar)
                                    <img src="{{ get_avatar_url(Auth::user()->avatar) }}" alt="Avatar"
                                        class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover; border: 3px solid rgba(255,255,255,0.3);">
                                    @else
                                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                        <i class="fas fa-user-shield fa-2x text-white"></i>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <h1 class="mb-2 fw-bold">{{ __('app.dashboard.welcome') }}, {{ Auth::user()->display_name }}!</h1>
                                    <h5 class="mb-0 opacity-75">{{ __('app.dashboard.admin_dashboard') }}</h5>
                                </div>
                            </div>
                            <p class="mb-0 opacity-75">{{ __('app.dashboard.manage_users_teams_esports') }}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="bg-white bg-opacity-20 rounded-3 p-3 backdrop-blur">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <i class="fas fa-clock me-2"></i>
                                    <span class="fw-bold">{{ __('app.dashboard.current_time') }}</span>
                                </div>
                                <h3 class="mb-1 fw-bold">{{ now()->format('H:i') }}</h3>
                                <p class="mb-1">{{ now()->format('d/m/Y') }}</p>
                                <small class="opacity-75">{{ now()->locale(app()->getLocale())->dayName }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px;">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase mb-2">
                                <i class="fas fa-users me-2"></i>{{ __('app.dashboard.total_users') }}
                            </h6>
                            <h2 class="mb-0 fw-bold">{{ number_format($stats['total_users']) }}</h2>
                            <div class="mt-2">
                                <small class="text-white-50">
                                    <i class="fas fa-chart-line me-1"></i>{{ __('app.dashboard.active') }}
                                </small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white bg-opacity-10 border-0">
                    <a href="{{ route('admin.users.index') }}" class="text-white text-decoration-none">
                        <small>{{ __('app.dashboard.view_details') }} <i class="fas fa-arrow-right ms-1"></i></small>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 15px;">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase mb-2">
                                <i class="fas fa-users me-2"></i>{{ __('app.dashboard.participants') }}
                            </h6>
                            <h2 class="mb-0 fw-bold">{{ number_format($stats['total_participants']) }}</h2>
                            <div class="mt-2">
                                <small class="text-white-50">
                                    <i class="fas fa-trophy me-1"></i>{{ __('app.dashboard.competing') }}
                                </small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white bg-opacity-10 border-0">
                    <a href="{{ route('admin.users.index') }}?role=participant" class="text-white text-decoration-none">
                        <small>{{ __('app.dashboard.view_details') }} <i class="fas fa-arrow-right ms-1"></i></small>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 15px;">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase mb-2">
                                <i class="fas fa-users-cog me-2"></i>{{ __('app.dashboard.total_teams') }}
                            </h6>
                            <h2 class="mb-0 fw-bold">{{ number_format($stats['total_teams']) }}</h2>
                            <div class="mt-2">
                                <small class="text-white-50">
                                    <i class="fas fa-chart-bar me-1"></i>{{ __('app.dashboard.participating_tournaments') }}
                                </small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-users-cog fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white bg-opacity-10 border-0">
                    <a href="{{ route('teams.index') }}" class="text-white text-decoration-none">
                        <small>{{ __('app.dashboard.manage_teams') }} <i class="fas fa-arrow-right ms-1"></i></small>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 15px;">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase mb-2">
                                <i class="fas fa-chart-line me-2"></i>{{ __('app.dashboard.active_teams') }}
                            </h6>
                            <h2 class="mb-0 fw-bold">{{ number_format($stats['active_teams']) }}</h2>
                            <div class="mt-2">
                                <small class="text-white-50">
                                    <i class="fas fa-arrow-up me-1"></i>{{ __('app.dashboard.good_growth') }}
                                </small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-chart-line fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white bg-opacity-10 border-0">
                    <a href="{{ route('teams.index') }}?status=active" class="text-white text-decoration-none">
                        <small>{{ __('app.dashboard.view_report') }} <i class="fas fa-arrow-right ms-1"></i></small>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Main Content Section -->
    <div class="row mb-4">
        <!-- Recent Users with Modern Design -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-0 pb-0">
                    <h5 class="m-0 fw-bold text-primary">
                        <i class="fas fa-user-plus me-2"></i>{{ __('app.dashboard.recent_users') }}
                    </h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>{{ __('app.dashboard.view_all') }}
                    </a>
                </div>
                <div class="card-body">
                    @forelse($stats['recent_users'] as $user)
                    <div class="d-flex align-items-center mb-3 p-3 rounded-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}"
                        style="background: rgba(79, 172, 254, 0.05);">
                        <div class="me-3">
                            @if($user->avatar)
                            <img src="{{ get_avatar_url($user->avatar) }}" alt="Avatar"
                                class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; background: linear-gradient(45deg, #4facfe, #00f2fe);">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">{{ $user->display_name }}</h6>
                            <div class="d-flex align-items-center">
                                <small class="text-muted me-3">{{ $user->email }}</small>
                                <span class="badge {{ $user->user_role === 'super_admin' ? 'bg-dark' : ($user->user_role === 'admin' ? 'bg-danger' : ($user->user_role === 'participant' ? 'bg-success' : 'bg-info')) }}">
                                    @if($user->user_role === 'super_admin')
                                    <i class="fas fa-crown me-1"></i>Super Admin
                                    @elseif($user->user_role === 'admin')
                                    <i class="fas fa-user-shield me-1"></i>Admin
                                    @elseif($user->user_role === 'participant')
                                    <i class="fas fa-gamepad me-1"></i>Participant
                                    @else
                                    <i class="fas fa-user me-1"></i>{{ ucfirst($user->user_role) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="mb-1">
                                <small class="text-muted fw-bold">{{ $user->created_at->format('d/m/Y') }}</small>
                            </div>
                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-users fa-3x mb-3 opacity-25"></i>
                        <h6>{{ __('app.dashboard.no_new_users') }}</h6>
                        <p class="mb-0">{{ __('app.dashboard.recent_registrations_will_show_here') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Enhanced Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-white border-0">
                    <h5 class="m-0 fw-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>{{ __('app.dashboard.quick_actions') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action border-0 px-0 py-3 rounded-3 mb-2"
                            style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="rounded-circle p-2" style="background: linear-gradient(135deg, #667eea, #764ba2); width: 50px; height: 50px;">
                                        <i class="fas fa-user-plus text-white fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ __('app.dashboard.manage_users') }}</h6>
                                    <small class="text-muted">{{ __('app.dashboard.add_edit_delete_user_accounts') }}</small>
                                </div>
                                <div>
                                    <i class="fas fa-arrow-right text-muted"></i>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('teams.index') }}" class="list-group-item list-group-item-action border-0 px-0 py-3 rounded-3 mb-2"
                            style="background: linear-gradient(135deg, rgba(79, 172, 254, 0.1), rgba(0, 242, 254, 0.1));">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="rounded-circle p-2" style="background: linear-gradient(135deg, #4facfe, #00f2fe); width: 50px; height: 50px;">
                                        <i class="fas fa-users text-white fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ __('app.dashboard.manage_teams') }}</h6>
                                    <small class="text-muted">{{ __('app.dashboard.view_manage_competing_teams') }}</small>
                                </div>
                                <div>
                                    <i class="fas fa-arrow-right text-muted"></i>
                                </div>
                            </div>
                        </a>

                        <a href="#" onclick="manageTournaments()" class="list-group-item list-group-item-action border-0 px-0 py-3 rounded-3 mb-2"
                            style="background: linear-gradient(135deg, rgba(240, 147, 251, 0.1), rgba(245, 87, 108, 0.1));">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="rounded-circle p-2" style="background: linear-gradient(135deg, #f093fb, #f5576c); width: 50px; height: 50px;">
                                        <i class="fas fa-trophy text-white fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ __('app.dashboard.manage_tournaments') }}</h6>
                                    <small class="text-muted">{{ __('app.dashboard.create_manage_esports_tournaments') }}</small>
                                </div>
                                <div>
                                    <i class="fas fa-arrow-right text-muted"></i>
                                </div>
                            </div>
                        </a>

                        <a href="#" onclick="systemSettings()" class="list-group-item list-group-item-action border-0 px-0 py-3 rounded-3"
                            style="background: linear-gradient(135deg, rgba(250, 112, 154, 0.1), rgba(254, 225, 64, 0.1));">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="rounded-circle p-2" style="background: linear-gradient(135deg, #fa709a, #fee140); width: 50px; height: 50px;">
                                        <i class="fas fa-cog text-white fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ __('app.dashboard.system_settings') }}</h6>
                                    <small class="text-muted">{{ __('app.dashboard.configure_customize_system') }}</small>
                                </div>
                                <div>
                                    <i class="fas fa-arrow-right text-muted"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Analytics Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-white border-0">
                    <h5 class="m-0 fw-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>{{ __('app.dashboard.activity_statistics') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            <div class="p-3 rounded-3" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));">
                                <i class="fas fa-chart-line fa-2x text-primary mb-2"></i>
                                <h4 class="fw-bold text-primary mb-1">{{ $stats['total_users'] ?? 0 }}</h4>
                                <p class="text-muted mb-0">{{ __('app.dashboard.total_users') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="p-3 rounded-3" style="background: linear-gradient(135deg, rgba(79, 172, 254, 0.1), rgba(0, 242, 254, 0.1));">
                                <i class="fas fa-users fa-2x text-info mb-2"></i>
                                <h4 class="fw-bold text-info mb-1">{{ $stats['total_teams'] ?? 0 }}</h4>
                                <p class="text-muted mb-0">{{ __('app.dashboard.total_competing_teams') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="p-3 rounded-3" style="background: linear-gradient(135deg, rgba(240, 147, 251, 0.1), rgba(245, 87, 108, 0.1));">
                                <i class="fas fa-trophy fa-2x text-warning mb-2"></i>
                                <h4 class="fw-bold text-warning mb-1">0</h4>
                                <p class="text-muted mb-0">{{ __('app.dashboard.ongoing_tournaments') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="p-3 rounded-3" style="background: linear-gradient(135deg, rgba(250, 112, 154, 0.1), rgba(254, 225, 64, 0.1));">
                                <i class="fas fa-users fa-2x text-success mb-2"></i>
                                <h4 class="fw-bold text-success mb-1">{{ $stats['total_participants'] ?? 0 }}</h4>
                                <p class="text-muted mb-0">{{ __('app.dashboard.active_participants') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Custom Styles -->
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
    }

    .card {
        border-radius: 15px !important;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .list-group-item {
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        transform: translateX(5px);
    }

    .badge-sm {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .text-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .avatar-gradient {
        background: linear-gradient(45deg, #4facfe, #00f2fe);
    }

    .progress {
        height: 6px;
        border-radius: 3px;
    }

    .progress-bar {
        background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
    }
</style>

<script>
    function manageTournaments() {
        Swal.fire({
            title: '{{ __("app.dashboard.manage_tournaments_title") }}',
            text: '{{ __("app.dashboard.manage_tournaments_text") }}',
            icon: 'info',
            confirmButtonText: '{{ __("app.dashboard.understood") }}',
            confirmButtonColor: '#4facfe'
        });
    }

    function systemSettings() {
        Swal.fire({
            title: '{{ __("app.dashboard.system_settings_title") }}',
            text: '{{ __("app.dashboard.system_settings_text") }}',
            icon: 'info',
            confirmButtonText: '{{ __("app.dashboard.understood") }}',
            confirmButtonColor: '#4facfe'
        });
    }

    // Auto-refresh dashboard every 5 minutes
    setInterval(function() {
        if (document.visibilityState === 'visible') {
            location.reload();
        }
    }, 300000);

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
</script>
@endsection