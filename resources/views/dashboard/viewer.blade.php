@extends('layouts.app')

@section('title', 'Viewer Dashboard')

@section('content')
<div class="container-fluid mt-4">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-info text-white border-0 shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">
                                <i class="fas fa-eye me-2"></i>
                                Chào {{ Auth::user()->display_name }}!
                            </h2>
                            <p class="mb-0 opacity-75">Khám phá thế giới esports</p>
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
        <!-- Featured Tournaments -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-trophy me-2"></i>Giải đấu nổi bật
                    </h5>
                    <a href="#" class="btn btn-primary btn-sm">
                        <i class="fas fa-calendar me-1"></i>Xem tất cả
                    </a>
                </div>
                <div class="card-body">
                    @if(count($featured_tournaments) > 0)
                    @foreach($featured_tournaments as $tournament)
                    <div class="tournament-card mb-3 p-3 border rounded-3">
                        <div class="d-flex">
                            <div class="me-3">
                                <img src="{{ $tournament->image_url ?? '/images/default-tournament.png' }}"
                                    alt="Tournament" class="rounded"
                                    style="width: 80px; height: 80px; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $tournament->name }}</h6>
                                <p class="mb-2 text-muted small">{{ Str::limit($tournament->description, 100) }}</p>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success me-2">{{ $tournament->status }}</span>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $tournament->start_date }}
                                    </small>
                                    <small class="text-muted ms-3">
                                        <i class="fas fa-users me-1"></i>
                                        {{ $tournament->teams_count }} đội
                                    </small>
                                </div>
                            </div>
                            <div class="text-end">
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-trophy fa-4x mb-3 opacity-25"></i>
                        <h5>Chưa có giải đấu nào</h5>
                        <p>Các giải đấu esports sẽ được cập nhật sớm</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Matches -->
            <div class="card shadow mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-gamepad me-2"></i>Trận đấu gần đây
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($recent_matches) > 0)
                    @foreach($recent_matches as $match)
                    <div class="match-card d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                        <div class="d-flex align-items-center">
                            <div class="text-center me-3">
                                <img src="{{ $match->team1->logo_url }}" alt="Team 1"
                                    class="rounded" style="width: 40px; height: 40px;">
                                <div class="small mt-1">{{ $match->team1->name }}</div>
                            </div>
                            <div class="text-center mx-3">
                                <div class="h5 mb-0">{{ $match->team1_score }} - {{ $match->team2_score }}</div>
                                <small class="text-muted">{{ $match->played_at->format('d/m H:i') }}</small>
                            </div>
                            <div class="text-center ms-3">
                                <img src="{{ $match->team2->logo_url }}" alt="Team 2"
                                    class="rounded" style="width: 40px; height: 40px;">
                                <div class="small mt-1">{{ $match->team2->name }}</div>
                            </div>
                        </div>
                        <div>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-play me-1"></i>Xem replay
                            </a>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-gamepad fa-3x mb-3 opacity-25"></i>
                        <p>Chưa có trận đấu nào</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Popular Teams -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-fire me-2"></i>Đội phổ biến
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($popular_teams as $team)
                    <div class="d-flex align-items-center mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                        <div class="me-3">
                            @if($team->logo_url)
                            <img src="{{ $team->logo_url }}" alt="Team Logo"
                                class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="fas fa-users text-muted"></i>
                            </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $team->name }}</h6>
                            <small class="text-muted">
                                {{ $team->active_members_count }} thành viên
                                @if($team->captain)
                                • Đội trưởng: {{ $team->captain->display_name }}
                                @endif
                            </small>
                        </div>
                        <a href="{{ route('teams.show', $team->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-users fa-2x mb-2 opacity-25"></i>
                        <p class="mb-0">Chưa có đội nào</p>
                    </div>
                    @endforelse
                </div>
                @if($popular_teams->count() > 0)
                <div class="card-footer bg-white">
                    <a href="{{ route('teams.index') }}" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-users me-1"></i>Xem tất cả đội
                    </a>
                </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-rocket me-2"></i>Nâng cấp tài khoản
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-gamepad fa-3x text-primary mb-2"></i>
                        <h6>Trở thành Player</h6>
                        <p class="text-muted small">Tham gia đội, thi đấu và chinh phục giải đấu</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-up me-1"></i>Nâng cấp ngay
                    </a>
                </div>
            </div>

            <!-- Latest News -->
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-newspaper me-2"></i>Tin tức mới
                    </h6>
                </div>
                <div class="card-body">
                    <div class="news-item mb-3">
                        <h6 class="mb-1">Giải đấu mùa Đông 2025</h6>
                        <p class="small text-muted mb-2">Đăng ký tham gia giải đấu lớn nhất năm với tổng giải thưởng lên tới 100 triệu VNĐ...</p>
                        <small class="text-muted">2 ngày trước</small>
                    </div>
                    <hr>
                    <div class="news-item mb-3">
                        <h6 class="mb-1">Cập nhật tính năng mới</h6>
                        <p class="small text-muted mb-2">Hệ thống đã được cập nhật với nhiều tính năng mới hấp dẫn...</p>
                        <small class="text-muted">1 tuần trước</small>
                    </div>
                    <div class="text-center mt-3">
                        <a href="#" class="btn btn-outline-primary btn-sm">Xem thêm tin tức</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-info {
        background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
    }

    .tournament-card,
    .match-card {
        border: 1px solid #e3e6f0 !important;
        transition: all 0.3s ease;
    }

    .tournament-card:hover {
        border-color: #5a6c7d !important;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
    }

    .card {
        border-radius: 15px;
    }

    .news-item {
        border-left: 3px solid #007bff;
        padding-left: 15px;
    }
</style>
@endsection