@extends('layouts.app')

@section('title', 'Player Dashboard')

@section('content')
<div class="container-fluid mt-4">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-success text-white border-0 shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">
                                <i class="fas fa-gamepad me-2"></i>
                                Chào {{ Auth::user()->display_name }}!
                            </h2>
                            <p class="mb-0 opacity-75">Sẵn sàng chinh phục các giải đấu</p>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('profile.show') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-user me-1"></i>Xem Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- My Teams Section -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-users me-2"></i>Đội của tôi
                    </h5>
                    <a href="{{ route('teams.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Tạo đội mới
                    </a>
                </div>
                <div class="card-body">
                    @forelse($my_teams as $team)
                    <div class="team-card mb-3 p-3 border rounded-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                @if($team->logo_url)
                                <img src="{{ $team->logo_url }}" alt="Team Logo"
                                    class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px;">
                                    <i class="fas fa-users text-muted fa-lg"></i>
                                </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $team->name }}</h6>
                                <p class="mb-1 text-muted small">{{ Str::limit($team->description, 80) }}</p>
                                <div class="d-flex align-items-center">
                                    @if($team->pivot->role === 'captain')
                                    <span class="badge bg-warning text-dark me-2">
                                        <i class="fas fa-crown me-1"></i>Đội trưởng
                                    </span>
                                    @else
                                    <span class="badge bg-info me-2">
                                        <i class="fas fa-user me-1"></i>Thành viên
                                    </span>
                                    @endif
                                    <small class="text-muted">
                                        {{ $team->activeMembers->count() }}/{{ $team->max_members }} thành viên
                                    </small>
                                </div>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('teams.show', $team->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Xem
                                </a>
                                @if($team->pivot->role === 'captain')
                                <a href="{{ route('teams.edit', $team->id) }}" class="btn btn-outline-secondary btn-sm ms-1">
                                    <i class="fas fa-edit me-1"></i>Sửa
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-users fa-4x mb-3 opacity-25"></i>
                        <h5>Bạn chưa tham gia đội nào</h5>
                        <p>Tạo đội mới hoặc tìm kiếm đội để tham gia</p>
                        <div class="mt-3">
                            <a href="{{ route('teams.create') }}" class="btn btn-primary me-2">
                                <i class="fas fa-plus me-1"></i>Tạo đội mới
                            </a>
                            <a href="{{ route('teams.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-search me-1"></i>Tìm đội
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Thống kê
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="bg-primary rounded-3 text-white p-3">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <h4 class="mb-0">{{ $my_teams->count() }}</h4>
                                <small>Đội tham gia</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="bg-warning rounded-3 text-white p-3">
                                <i class="fas fa-crown fa-2x mb-2"></i>
                                <h4 class="mb-0">{{ $captain_teams->count() }}</h4>
                                <small>Đội làm đội trưởng</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-bolt me-2"></i>Thao tác nhanh
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('teams.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tạo đội mới
                        </a>
                        <a href="{{ route('teams.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-search me-2"></i>Tìm đội tham gia
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-edit me-2"></i>Cập nhật profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Team Invitations -->
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-envelope me-2"></i>Lời mời tham gia đội
                    </h6>
                </div>
                <div class="card-body">
                    @if(count($team_invitations) > 0)
                    @foreach($team_invitations as $invitation)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-0">{{ $invitation->team->name }}</h6>
                            <small class="text-muted">{{ $invitation->created_at->diffForHumans() }}</small>
                        </div>
                        <div>
                            <button class="btn btn-success btn-sm me-1">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-danger btn-sm">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-envelope-open fa-2x mb-2 opacity-25"></i>
                        <p class="mb-0">Không có lời mời nào</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .team-card {
        border: 1px solid #e3e6f0 !important;
        transition: all 0.3s ease;
    }

    .team-card:hover {
        border-color: #5a6c7d !important;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
    }

    .card {
        border-radius: 15px;
    }

    .bg-primary,
    .bg-warning {
        transition: transform 0.2s ease;
    }

    .bg-primary:hover,
    .bg-warning:hover {
        transform: scale(1.05);
    }
</style>
@endsection