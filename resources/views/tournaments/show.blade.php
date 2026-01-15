@extends('layouts.app')

@section('title', $tournament->name . ' - Giải đấu')

@push('styles')
<style>
    /* Hero */
    .tournament-hero {
        background: radial-gradient(900px 250px at 20% -40%, rgba(255,255,255,.6) 0%, rgba(255,255,255,0) 50%),
                    linear-gradient(135deg, #eef2ff 0%, #f5f3ff 100%);
        border: 1px solid rgba(99,102,241,.15);
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 10px 30px rgba(99,102,241,.08);
    }
    .hero-title { font-weight: 800; letter-spacing: .2px; }
    .hero-sub { color: #64748b; font-weight: 600; }
    .status-badge { border-radius: 999px; padding: 8px 14px; font-weight: 800; }
    .cta-btn { border-radius: 999px; font-weight: 700; padding: 10px 18px; }
    .cta-btn.primary-soft { background: linear-gradient(135deg, rgba(99,102,241,.15), rgba(139,92,246,.15)); border: 1px solid rgba(99,102,241,.3); color: #4338ca; }
    .cta-btn.primary-soft:hover { background: linear-gradient(135deg, rgba(99,102,241,.25), rgba(139,92,246,.25)); }

    /* Meta */
    .meta-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; }
    .meta-box { background: #fff; border: 1px solid rgba(0,0,0,.06); border-radius: 16px; padding: 16px 12px; text-align: center; box-shadow: 0 6px 18px rgba(2,6,23,.04); }
    .meta-icon { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; background: #eef2ff; color: #4f46e5; margin-bottom: 8px; }
    .meta-box h5 { margin: 0; font-weight: 900; }
    .meta-box small { color: #64748b; }

    /* Media */
    .banner { width: 100%; height: 320px; object-fit: cover; border-radius: 16px; border: 1px solid rgba(0,0,0,.06); box-shadow: 0 12px 30px rgba(2,6,23,.06); }

    /* Tabs */
    .tabs { display: flex; gap: 10px; flex-wrap: wrap; }
    .chip { padding: 10px 14px; border-radius: 12px; border: 1px solid rgba(0,0,0,.08); background: #fff; font-weight: 700; color: #334155; text-decoration: none; transition: all .2s ease; }
    .chip:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(2,6,23,.08); }
    .chip.active { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; border-color: transparent; }

    /* Cards */
    .card-elevated { border-radius: 16px; border: 1px solid rgba(0,0,0,.06); box-shadow: 0 10px 28px rgba(2,6,23,.06); }

    @media (max-width: 992px) {
        .meta-grid { grid-template-columns: repeat(2,1fr); }
        .banner { height: 220px; }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="tournament-hero mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h2 class="mb-1 hero-title"><i class="fas fa-trophy me-2 text-primary"></i>{{ $tournament->name }}</h2>
                <div class="hero-sub">
                    {{ $tournament->game->name ?? 'Không rõ game' }} • {{ $tournament->getFormattedDateRange() }}
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-{{ $tournament->status_color }} status-badge">{{ $tournament->status_label }}</span>
                @if($tournament->status === 'registration')
                <button class="btn cta-btn primary-soft"><i class="fas fa-user-plus me-1"></i>Đăng ký</button>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <img class="banner mb-3" src="{{ $tournament->banner_url ?? 'https://via.placeholder.com/1200x600?text=Tournament' }}" alt="banner">

            <div class="tabs mb-3">
                <a href="#overview" class="chip active">Tổng quan</a>
                <a href="#rules" class="chip">Luật lệ</a>
                <a href="#prizes" class="chip">Giải thưởng</a>
            </div>

            <div id="overview" class="card card-elevated mb-3">
                <div class="card-body">
                    <h5 class="fw-bold mb-2">Tổng quan</h5>
                    <div class="text-secondary">{{ $tournament->description ?? 'Chưa có mô tả chi tiết cho giải đấu này.' }}</div>
                </div>
            </div>

            <div id="rules" class="card card-elevated mb-3">
                <div class="card-body">
                    <h5 class="fw-bold mb-2">Luật lệ</h5>
                    <div class="text-secondary">{{ data_get($tournament->rules_details,'summary','Luật lệ sẽ được cập nhật.') }}</div>
                </div>
            </div>

            <div id="prizes" class="card card-elevated">
                <div class="card-body">
                    <h5 class="fw-bold mb-2">Giải thưởng</h5>
                    <div class="text-secondary">{{ is_array($tournament->prize_structure) ? json_encode($tournament->prize_structure) : ($tournament->prize_structure ?? 'Thông tin giải thưởng sẽ được cập nhật.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-elevated mb-3">
                <div class="card-body">
                    <div class="meta-grid">
                        <div class="meta-box">
                            <div class="meta-icon"><i class="fas fa-users"></i></div>
                            <h5>{{ $tournament->max_participants ?? '-' }}</h5>
                            <small>Đội tối đa</small>
                        </div>
                        <div class="meta-box">
                            <div class="meta-icon"><i class="fas fa-user-friends"></i></div>
                            <h5>{{ $tournament->competition_type_label }}</h5>
                            <small>Hình thức</small>
                        </div>
                        <div class="meta-box">
                            <div class="meta-icon"><i class="fas fa-sitemap"></i></div>
                            <h5>{{ $tournament->tournament_format_label }}</h5>
                            <small>Thể thức</small>
                        </div>
                        <div class="meta-box">
                            <div class="meta-icon"><i class="far fa-clock"></i></div>
                            <h5>{{ optional($tournament->start_date)->format('d/m') }}</h5>
                            <small>Bắt đầu</small>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('tournaments.index') }}" class="btn btn-outline-secondary w-100"><i class="fas fa-arrow-left me-1"></i>Quay lại danh sách</a>
        </div>
    </div>
</div>
@endsection


