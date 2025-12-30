@extends('layouts.app')

@section('title', __('app.nav.tournaments') . ' - ' . __('app.name'))

@section('meta_description', 'Khám phá và tham gia các giải đấu esport hấp dẫn trên GameOn. Giải đấu Liên Quân, Valorant, PUBG Mobile, Free Fire và nhiều game khác. Đăng ký miễn phí ngay!')

@section('meta_keywords', 'giải đấu esport, tournament game, giải đấu Liên Quân Mobile, giải Valorant Việt Nam, tournament PUBG Mobile, giải đấu Free Fire, đăng ký giải đấu game, thi đấu esport online')

@push('styles')
<style>
    .tournaments-container {
        background: #000814;
        min-height: 100vh;
    }
    
    /* Mobile responsive */
    @media (max-width: 991.98px) {
        .tournaments-container {
            margin-left: 0 !important;
            width: 100% !important;
        }
    }
    
    /* Hero Section */
    .tournaments-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .tournaments-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #00E5FF, transparent);
    }
    
    .hero-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 30px rgba(245, 158, 11, 0.3);
    }
    
    .hero-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #FFFFFF;
        margin: 0;
    }
    
    .hero-title span {
        color: #00E5FF;
        text-shadow: 0 0 20px rgba(0, 229, 255, 0.5);
    }
    
    .hero-subtitle {
        color: #94a3b8;
        font-size: 0.95rem;
        margin: 0;
    }
    
    .btn-neon {
        background: linear-gradient(135deg, #000055, #000077);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.4);
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    
    .btn-neon:hover {
        background: rgba(0, 229, 255, 0.15);
        box-shadow: 0 0 25px rgba(0, 229, 255, 0.4);
        color: #FFFFFF;
        transform: translateY(-2px);
    }
    
    /* Filter Card */
    .filter-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .filter-label {
        color: #94a3b8;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .filter-select,
    .filter-input {
        background: rgba(0, 229, 255, 0.05);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        color: #FFFFFF;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        width: 100%;
        transition: all 0.3s ease;
    }
    
    .filter-select:focus,
    .filter-input:focus {
        outline: none;
        border-color: #00E5FF;
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.2);
    }
    
    .filter-select option {
        background: #0d1b2a;
        color: #FFFFFF;
    }
    
    .btn-filter {
        background: linear-gradient(135deg, #00E5FF, #0099cc);
        color: #000;
        border: none;
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-filter:hover {
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.5);
        transform: translateY(-2px);
    }
    
    /* Quick Filters */
    .quick-filters {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
    }
    
    .filter-chip {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        background: rgba(0, 229, 255, 0.05);
        border: 1px solid rgba(0, 229, 255, 0.2);
        color: #94a3b8;
    }
    
    .filter-chip:hover {
        background: rgba(0, 229, 255, 0.1);
        color: #00E5FF;
        border-color: rgba(0, 229, 255, 0.4);
    }
    
    .filter-chip.active {
        background: linear-gradient(135deg, #00E5FF, #0099cc);
        color: #000;
        border-color: transparent;
    }
    
    /* Stats Summary */
    .stats-summary {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    
    .stat-badge {
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        padding: 0.5rem 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .stat-badge i {
        color: #00E5FF;
    }
    
    .stat-badge span {
        color: #FFFFFF;
        font-weight: 600;
    }
    
    .stat-badge small {
        color: #94a3b8;
    }
    
    /* Tournament Cards Grid */
    .tournaments-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }
    
    @media (max-width: 1280px) {
        .tournaments-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .tournaments-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* Tournament Card */
    .tournament-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s ease;
    }
    
    .tournament-card:hover {
        transform: translateY(-8px);
        border-color: rgba(0, 229, 255, 0.4);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 30px rgba(0, 229, 255, 0.15);
    }
    
    .tournament-banner {
        position: relative;
        height: 160px;
        overflow: hidden;
    }
    
    .tournament-banner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .tournament-banner-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #1a237e 0%, #000055 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .tournament-banner-placeholder i {
        font-size: 3rem;
        color: rgba(0, 229, 255, 0.3);
    }
    
    .tournament-status {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-draft {
        background: rgba(100, 116, 139, 0.9);
        color: #FFFFFF;
    }
    
    .status-registration_open {
        background: rgba(34, 197, 94, 0.9);
        color: #FFFFFF;
        box-shadow: 0 0 15px rgba(34, 197, 94, 0.5);
    }
    
    .status-ongoing {
        background: rgba(59, 130, 246, 0.9);
        color: #FFFFFF;
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
    }
    
    .status-completed {
        background: rgba(139, 92, 246, 0.9);
        color: #FFFFFF;
    }
    
    .status-cancelled {
        background: rgba(239, 68, 68, 0.9);
        color: #FFFFFF;
    }
    
    .tournament-game-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(0, 0, 0, 0.7);
        color: #00E5FF;
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .tournament-card-body {
        padding: 1.25rem;
    }
    
    .tournament-name {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #FFFFFF;
        margin: 0 0 0.5rem 0;
        line-height: 1.3;
    }
    
    .tournament-date {
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .tournament-date i {
        color: #00E5FF;
    }
    
    .tournament-meta {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    
    .meta-item {
        background: rgba(0, 229, 255, 0.05);
        border: 1px solid rgba(0, 229, 255, 0.1);
        border-radius: 12px;
        padding: 0.75rem;
        text-align: center;
    }
    
    .meta-value {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #00E5FF;
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    
    .meta-label {
        color: #64748b;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .tournament-description {
        color: #94a3b8;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .tournament-card-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        gap: 0.75rem;
    }
    
    .btn-card {
        flex: 1;
        padding: 0.7rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    
    .btn-view {
        background: rgba(0, 229, 255, 0.1);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.3);
    }
    
    .btn-view:hover {
        background: rgba(0, 229, 255, 0.2);
        color: #FFFFFF;
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.3);
    }
    
    .btn-register {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #FFFFFF;
        border: none;
    }
    
    .btn-register:hover {
        box-shadow: 0 0 20px rgba(34, 197, 94, 0.5);
        transform: translateY(-2px);
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 20px;
    }
    
    .empty-icon {
        width: 100px;
        height: 100px;
        background: rgba(0, 229, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    
    .empty-icon i {
        font-size: 2.5rem;
        color: #64748b;
    }
    
    .empty-title {
        color: #FFFFFF;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .empty-text {
        color: #64748b;
        font-size: 0.95rem;
        max-width: 400px;
        margin: 0 auto;
    }
    
    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }
    
    .pagination-wrapper .pagination {
        display: flex;
        gap: 0.5rem;
    }
    
    .pagination-wrapper .page-item .page-link {
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.2);
        color: #00E5FF;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }
    
    .pagination-wrapper .page-item .page-link:hover {
        background: rgba(0, 229, 255, 0.2);
        border-color: #00E5FF;
    }
    
    .pagination-wrapper .page-item.active .page-link {
        background: #00E5FF;
        color: #000;
        border-color: #00E5FF;
    }
    
    .pagination-wrapper .page-item.disabled .page-link {
        background: rgba(0, 229, 255, 0.05);
        color: #64748b;
        border-color: rgba(0, 229, 255, 0.1);
    }
</style>
@endpush

@section('content')
<div class="tournaments-container">
    <div class="max-w-7xl mx-auto px-4 py-6">
        
        <!-- Hero Section -->
        <div class="tournaments-hero">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="hero-icon">
                        <i class="fas fa-trophy text-2xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="hero-title"><span>{{ __('app.nav.tournaments') }}</span></h1>
                        <p class="hero-subtitle">{{ __('app.tournaments.public_tournaments_description') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-card">
            <form class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" method="GET" action="{{ route('tournaments.index') }}">
                <div>
                    <label class="filter-label">{{ __('app.search.search') }}</label>
                    <input type="text" class="filter-input" name="search" placeholder="{{ __('app.tournaments.search_placeholder') }}" value="{{ request('search') }}">
                </div>
                <div>
                    <label class="filter-label">Game</label>
                    <select class="filter-select" name="game_id">
                        <option value="">{{ __('app.tournaments.all_games') }}</option>
                        @foreach(\App\Models\GameManagement::orderBy('name')->get() as $game)
                        <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>{{ $game->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="filter-label">{{ __('app.teams.status') }}</label>
                    <select class="filter-select" name="status">
                        <option value="">{{ __('app.tournaments.all_statuses') }}</option>
                        <option value="registration_open" {{ request('status')=='registration_open' ? 'selected' : '' }}>{{ __('app.tournaments.registration_open') }}</option>
                        <option value="ongoing" {{ request('status')=='ongoing' ? 'selected' : '' }}>{{ __('app.tournaments.ongoing') }}</option>
                        <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>{{ __('app.tournaments.completed') }}</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-filter w-full">
                        <i class="fas fa-search mr-2"></i>{{ __('app.common.filter') }}
                    </button>
                </div>
            </form>
            
            <!-- Quick Filters -->
            <div class="quick-filters">
                @php($qs = request()->except('status'))
                <a class="filter-chip {{ !request('status') ? 'active' : '' }}" href="{{ route('tournaments.index', $qs) }}">
                    {{ __('app.common.all') }}
                </a>
                <a class="filter-chip {{ request('status')==='registration_open' ? 'active' : '' }}" href="{{ route('tournaments.index', array_merge($qs,['status'=>'registration_open'])) }}">
                    <i class="fas fa-door-open mr-1"></i> {{ __('app.tournaments.registration_open') }}
                </a>
                <a class="filter-chip {{ request('status')==='ongoing' ? 'active' : '' }}" href="{{ route('tournaments.index', array_merge($qs,['status'=>'ongoing'])) }}">
                    <i class="fas fa-play mr-1"></i> {{ __('app.tournaments.ongoing') }}
                </a>
                <a class="filter-chip {{ request('status')==='completed' ? 'active' : '' }}" href="{{ route('tournaments.index', array_merge($qs,['status'=>'completed'])) }}">
                    <i class="fas fa-flag-checkered mr-1"></i> {{ __('app.tournaments.completed') }}
                </a>
            </div>
        </div>
        
        <!-- Stats Summary -->
        <div class="stats-summary">
            <div class="stat-badge">
                <i class="fas fa-trophy"></i>
                <span>{{ $tournaments->total() }}</span>
                <small>{{ __('app.nav.tournaments') }}</small>
            </div>
            <div class="stat-badge">
                <i class="fas fa-door-open text-green-500"></i>
                <span>{{ $tournaments->where('status', 'registration_open')->count() }}</span>
                <small>{{ __('app.tournaments.registration_open') }}</small>
            </div>
            <div class="stat-badge">
                <i class="fas fa-play text-blue-500"></i>
                <span>{{ $tournaments->where('status', 'ongoing')->count() }}</span>
                <small>{{ __('app.tournaments.ongoing') }}</small>
            </div>
        </div>
        
        <!-- Tournaments Grid -->
        @if($tournaments->count() > 0)
        <div class="tournaments-grid">
            @foreach($tournaments as $t)
            <div class="tournament-card">
                <div class="tournament-banner">
                    @if($t->banner_url)
                    <img src="{{ $t->banner_url }}" alt="{{ $t->name }}">
                    @else
                    <div class="tournament-banner-placeholder">
                        <i class="fas fa-trophy"></i>
                    </div>
                    @endif
                    
                    <span class="tournament-status status-{{ $t->status }}">
                        {{ __('app.tournaments.' . $t->status) }}
                    </span>
                    
                    @if($t->game)
                    <span class="tournament-game-badge">
                        <i class="fas fa-gamepad"></i>
                        {{ $t->game->name }}
                    </span>
                    @endif
                </div>
                
                <div class="tournament-card-body">
                    <h3 class="tournament-name">{{ $t->name }}</h3>
                    
                    <div class="tournament-date">
                        <i class="far fa-calendar-alt"></i>
                        <span>{{ $t->getFormattedDateRange() }}</span>
                    </div>
                    
                    <div class="tournament-meta">
                        <div class="meta-item">
                            <div class="meta-value">{{ $t->max_participants ?? '-' }}</div>
                            <div class="meta-label">{{ __('app.tournaments.teams') }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-value">
                                @if(is_array($t->prize_structure) && isset($t->prize_structure['total']))
                                    {{ $t->prize_structure['total'] }}
                                @else
                                    -
                                @endif
                            </div>
                            <div class="meta-label">{{ __('app.tournaments.prize') }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-value">{{ $t->format_display ?? '-' }}</div>
                            <div class="meta-label">{{ __('app.tournaments.format') }}</div>
                        </div>
                    </div>
                    
                    @if($t->description)
                    <p class="tournament-description">{{ Str::limit($t->description, 100) }}</p>
                    @endif
                </div>
                
                <div class="tournament-card-footer">
                    <a href="{{ route('tournaments.show', $t->id) }}" class="btn-card btn-view">
                        <i class="fas fa-eye"></i>
                        <span>{{ __('app.common.view') }}</span>
                    </a>
                    @if($t->status === 'registration_open')
                    <a href="{{ route('tournaments.show', $t->id) }}#register" class="btn-card btn-register">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>{{ __('app.tournaments.register') }}</span>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $tournaments->withQueryString()->links() }}
        </div>
        @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <h3 class="empty-title">{{ __('app.tournaments.no_tournaments') }}</h3>
            <p class="empty-text">{{ __('app.tournaments.no_tournaments_description') }}</p>
        </div>
        @endif
        
    </div>
</div>
@endsection
