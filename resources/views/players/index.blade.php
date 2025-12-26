@extends('layouts.app')

@section('title', 'Người chơi - Esport Manager')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="players-hero">
                <div class="hero-inner d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="hero-title mb-1">
                            <i class="fas fa-users me-2"></i>
                            Danh sách Người chơi
                        </h1>
                        <p class="hero-subtitle mb-0">Khám phá và quản lý hồ sơ người chơi esports</p>
                    </div>
                    <a href="{{ route('players.create') }}" class="btn btn-hero-primary">
                        <i class="fas fa-user-plus me-2"></i> Thêm người chơi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card filter-card">
                <div class="card-body">
                    <form class="row g-3">
                        <div class="col-md-3">
                            <label for="game" class="form-label">Game chuyên môn</label>
                            <select class="form-select" id="game" name="game">
                                <option value="">Tất cả game</option>
                                <option value="lol">League of Legends</option>
                                <option value="csgo">CS:GO</option>
                                <option value="valorant">Valorant</option>
                                <option value="dota2">Dota 2</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="rank" class="form-label">Xếp hạng</label>
                            <select class="form-select" id="rank" name="rank">
                                <option value="">Tất cả rank</option>
                                <option value="bronze">Bronze</option>
                                <option value="silver">Silver</option>
                                <option value="gold">Gold</option>
                                <option value="platinum">Platinum</option>
                                <option value="diamond">Diamond</option>
                                <option value="master">Master</option>
                                <option value="challenger">Challenger</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Tất cả</option>
                                <option value="active">Hoạt động</option>
                                <option value="inactive">Không hoạt động</option>
                                <option value="banned">Bị cấm</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search" class="form-label">Tìm kiếm</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="search" name="search" placeholder="Tên người chơi...">
                                <button class="btn btn-primary" type="submit">Lọc</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Players Cards (DB) -->
    <div class="row g-4">
        @forelse($players as $player)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card h-100 shadow-sm player-card">
                <div class="card-body text-center">
                    <div class="avatar-wrapper mb-3">
                        <img src="{{ $player->avatar_url ?? 'https://via.placeholder.com/80x80?text=Avatar' }}" class="rounded-circle avatar" alt="Avatar" width="80" height="80">
                        @php $status = $player->status === 'active' ? 'online' : ($player->status === 'suspended' ? 'away' : 'offline'); @endphp
                        <span class="status-dot {{ $status }}" title="{{ $player->status_display_name ?? 'Trạng thái' }}"></span>
                    </div>
                    <h6 class="card-title">{{ $player->display_name }}</h6>
                    <p class="text-muted small mb-2">{{ $player->email }}</p>

                    <div class="mb-3">
                        @if(optional($player->teams->first())->game)
                            <span class="badge bg-primary me-1">{{ $player->teams->first()->game->name }}</span>
                        @endif
                        <span class="badge bg-secondary">Người chơi</span>
                    </div>

                    @php $wins = $player->wins ?? 0; $losses = $player->losses ?? 0; $matches = max(1, $wins + $losses); $winrate = round(($wins/$matches)*100,1); @endphp
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="mb-0 text-success">{{ $wins }}</h6>
                                <small class="text-muted">Thắng</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="mb-0 text-danger">{{ $losses }}</h6>
                            <small class="text-muted">Thua</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center small text-muted mb-1">
                            <span>Tỷ lệ thắng</span><span class="fw-semibold">{{ $winrate }}%</span>
                        </div>
                        <div class="progress progress-thin">
                            <div class="progress-bar bg-success" style="width: {{ $winrate }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent">
                    <div class="d-flex gap-2">
                        <a href="{{ route('players.show', $player->id) }}" class="btn btn-outline-primary btn-sm flex-fill">
                            <i class="fas fa-eye me-1"></i>Xem
                        </a>
                        <a href="{{ route('players.edit', $player->id) }}" class="btn btn-outline-secondary btn-sm flex-fill">
                            <i class="fas fa-edit me-1"></i>Sửa
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center text-muted py-5">
                <i class="fas fa-user-slash fa-2x mb-3"></i>
                <div>Không tìm thấy người chơi nào</div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-center">
            @isset($players)
                {{ $players->withQueryString()->links() }}
            @endisset
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
.players-hero{position:relative;border-radius:16px;overflow:hidden;background:linear-gradient(135deg,#0ea5e9,#6366f1);color:#fff}
.players-hero .hero-inner{padding:1.75rem}
.players-hero .hero-title{font-size:1.6rem;font-weight:700;text-shadow:0 2px 4px rgba(0,0,0,.2)}
.btn-hero-primary{background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.35);color:#fff;border-radius:999px;padding:.6rem 1.25rem}
.btn-hero-primary:hover{background:rgba(255,255,255,.25);color:#fff}

.filter-card .form-control,.filter-card .form-select{border:2px solid #e9ecef;border-radius:10px}
.filter-card .input-group-text{background:#f8f9fa;border:2px solid #e9ecef;border-right:none;border-radius:10px 0 0 10px}
.progress-thin{height:6px}
.avatar-wrapper{position:relative;display:inline-block}
.avatar{box-shadow:0 6px 16px rgba(0,0,0,.1)}
.status-dot{position:absolute;right:-2px;top:-2px;width:12px;height:12px;border-radius:50%;border:2px solid #fff}
.status-dot.online{background:#22c55e}
.status-dot.offline{background:#9ca3af}
.status-dot.away{background:#f59e0b}
.player-card{transition:transform .2s ease, box-shadow .2s ease}
.player-card:hover{transform:translateY(-6px);box-shadow:0 14px 30px rgba(0,0,0,.12)}
</style>
@endpush