@extends('layouts.app')

@section('title', 'Giải đấu - Game On')

@push('styles')
<style>
    /* Page hero */
    .tournaments-hero {
        background: linear-gradient(135deg, #eff6ff 0%, #eef2ff 50%, #f5f3ff 100%);
        border-radius: 18px;
        padding: 20px 24px;
        border: 1px solid rgba(99,102,241,.15);
        box-shadow: 0 10px 30px rgba(99,102,241,.08);
        margin-bottom: 18px;
    }
    .tournaments-hero h1 { margin: 0; font-weight: 800; letter-spacing: .2px; }
    .subtext { color: #64748b; font-weight: 600; }

    /* Filter card */
    .filter-card .card { border: 1px solid rgba(0,0,0,.06); border-radius: 16px; }
    .filter-card .form-label { font-weight: 600; color: #334155; }
    .filter-card .form-select, .filter-card .form-control { border-radius: 12px; }

    /* Quick filters */
    .quick-filters { display: flex; gap: 10px; flex-wrap: wrap; }
    .chip { padding: 8px 14px; border-radius: 999px; border: 1px solid rgba(0,0,0,.08); background: #ffffff; font-weight: 700; color: #334155; text-decoration: none; transition: all .2s ease; }
    .chip:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(2,6,23,.08); }
    .chip.active { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; border-color: transparent; }

    /* Tournament card */
    .tournament-card { transition: transform .25s ease, box-shadow .25s ease; border: 1px solid rgba(0,0,0,.06); border-radius: 16px; overflow: hidden; animation: fadeUp .35s ease both; }
    .tournament-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(2,6,23,.1); }
    .tournament-card .card-header { border-bottom: 0; padding: 14px 16px; font-weight: 700; display: flex; align-items: center; justify-content: space-between; }
    .tournament-card .status-pill { background: rgba(255,255,255,.9); border: 1px solid rgba(0,0,0,.06); padding: 4px 10px; border-radius: 999px; font-size: .75rem; font-weight: 700; }
    .tournament-card .banner { height: 140px; object-fit: cover; border-radius: 12px; width: 100%; background: #f8fafc; }
    .tournament-card .meta { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; text-align: center; }
    .tournament-card .meta .meta-box { background: #f8fafc; border: 1px solid rgba(0,0,0,.06); border-radius: 12px; padding: 10px; }
    .tournament-card .meta h6 { margin: 0; font-weight: 800; }
    .tournament-card .meta small { color: #64748b; }
    .tournament-card .card-text { color: #475569; }
    .tournament-card .info-tag { background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.4); padding: 4px 10px; border-radius: 999px; color: #fff; font-size: .75rem; margin-left: 6px; }
    .tournament-card .btn { border-radius: 12px; font-weight: 700; }
    .tournament-card .btn-primary-soft { background: linear-gradient(135deg, rgba(99,102,241,.15), rgba(139,92,246,.15)); border: 1px solid rgba(99,102,241,.3); color: #4338ca; }
    .tournament-card .btn-primary-soft:hover { background: linear-gradient(135deg, rgba(99,102,241,.25), rgba(139,92,246,.25)); }

    /* Empty alert */
    .empty-alert { border-radius: 14px; }

    @keyframes fadeUp { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endpush

@section('content')
<div class="container">
    <div class="tournaments-hero">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h1 class="h3">
                <i class="fas fa-trophy me-2 text-primary"></i>
                Danh sách Giải đấu
            </h1>
            <div class="subtext">
                Xem các giải đấu công khai đang mở đăng ký, diễn ra hoặc đã kết thúc
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4 filter-card">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form class="row g-3" method="GET" action="{{ route('tournaments.index') }}">
                        <div class="col-md-4">
                            <label for="game" class="form-label">Game</label>
                            <select class="form-select" id="game" name="game_id" onchange="this.form.submit()">
                                <option value="">Tất cả game</option>
                                @foreach(\App\Models\GameManagement::orderBy('name')->get() as $game)
                                <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>{{ $game->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select" id="status" name="status" onchange="this.form.submit()">
                                <option value="">Tất cả trạng thái</option>
                                <option value="registration_open" {{ request('status')=='registration_open' ? 'selected' : '' }}>Đang đăng ký</option>
                                <option value="ongoing" {{ request('status')=='ongoing' ? 'selected' : '' }}>Đang diễn ra</option>
                                <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Đã kết thúc</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="search" class="form-label">Tìm kiếm</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search" name="search" placeholder="Tên giải đấu..." value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Quick filters -->
                    <div class="quick-filters mt-3">
                        @php($qs = request()->except('status'))
                        <a class="chip {{ request('status')==='' ? 'active' : '' }}" href="{{ route('tournaments.index', $qs) }}">Tất cả</a>
                        <a class="chip {{ request('status')==='registration_open' ? 'active' : '' }}" href="{{ route('tournaments.index', array_merge($qs,['status'=>'registration_open'])) }}">Đang đăng ký</a>
                        <a class="chip {{ request('status')==='ongoing' ? 'active' : '' }}" href="{{ route('tournaments.index', array_merge($qs,['status'=>'ongoing'])) }}">Đang diễn ra</a>
                        <a class="chip {{ request('status')==='completed' ? 'active' : '' }}" href="{{ route('tournaments.index', array_merge($qs,['status'=>'completed'])) }}">Đã kết thúc</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tournaments Grid -->
    <div class="row g-4">
        @forelse($tournaments as $t)
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm tournament-card">
                <div class="card-header bg-{{ $t->status_color }} text-white">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0">{{ $t->name }}</h6>
                            @if($t->game)
                            <span class="info-tag ms-2"><i class="fas fa-gamepad me-1"></i>{{ $t->game->name }}</span>
                            @endif
                        </div>
                        <div class="small opacity-90">
                            <i class="far fa-calendar me-1"></i>{{ $t->getFormattedDateRange() }}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        @if($t->banner_url)
                        <img src="{{ $t->banner_url }}" class="banner" alt="Tournament">
                        @else
                        <img src="https://via.placeholder.com/600x300?text=Tournament" class="banner" alt="Tournament">
                        @endif
                    </div>
                    <div class="meta mb-3">
                        <div class="meta-box">
                            <h6>{{ $t->max_participants ?? '-' }}</h6>
                            <small><i class="fas fa-users me-1"></i>Đội</small>
                        </div>
                        <div class="meta-box">
                            <h6>{{ is_array($t->prize_structure) && isset($t->prize_structure['total']) ? $t->prize_structure['total'] : '-' }}</h6>
                            <small><i class="fas fa-crown me-1"></i>Giải thưởng</small>
                        </div>
                        <div class="meta-box">
                            <h6>{{ optional($t->start_date)->format('d/m') }}</h6>
                            <small><i class="far fa-clock me-1"></i>Bắt đầu</small>
                        </div>
                    </div>
                    <p class="card-text">{{ Str::limit($t->description, 140) }}</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('tournaments.show', $t->id) }}" class="btn btn-primary-soft w-100">
                        <i class="fas fa-eye me-1"></i>Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info empty-alert">Chưa có giải đấu nào phù hợp.</div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12">
            {{ $tournaments->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection