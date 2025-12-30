@extends('layouts.app')

@section('title', __('app.players.title') . ' - ' . __('app.name'))

@push('styles')
<style>
    .players-container {
        background: #000814;
        min-height: 100vh;
    }
    
    /* Mobile responsive */
    @media (max-width: 991.98px) {
        .players-container {
            margin-left: 0 !important;
            width: 100% !important;
        }
    }
    
    /* Hero Section */
    .players-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .players-hero::before {
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
        background: linear-gradient(135deg, #000055, #000077);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(0, 229, 255, 0.4);
        box-shadow: 0 0 30px rgba(0, 229, 255, 0.3);
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
    
    /* Player Cards Grid */
    .players-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
    }
    
    @media (max-width: 1280px) {
        .players-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 992px) {
        .players-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 640px) {
        .players-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* Player Card */
    .player-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s ease;
    }
    
    .player-card:hover {
        transform: translateY(-8px);
        border-color: rgba(0, 229, 255, 0.4);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 30px rgba(0, 229, 255, 0.15);
    }
    
    .player-card-header {
        background: linear-gradient(135deg, rgba(0, 229, 255, 0.1) 0%, rgba(0, 0, 85, 0.3) 100%);
        padding: 1.5rem;
        text-align: center;
        position: relative;
    }
    
    .player-avatar-wrapper {
        position: relative;
        display: inline-block;
        margin-bottom: 1rem;
    }
    
    .player-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(0, 229, 255, 0.4);
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.3);
    }
    
    .player-avatar-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #000055, #000077);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid rgba(0, 229, 255, 0.4);
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.3);
    }
    
    .player-avatar-placeholder i {
        font-size: 2rem;
        color: #00E5FF;
    }
    
    .status-indicator {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 3px solid #0d1b2a;
    }
    
    .status-indicator.online {
        background: #22c55e;
        box-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
    }
    
    .status-indicator.offline {
        background: #64748b;
    }
    
    .status-indicator.away {
        background: #f59e0b;
        box-shadow: 0 0 10px rgba(245, 158, 11, 0.5);
    }
    
    .player-name {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: #FFFFFF;
        margin: 0 0 0.25rem 0;
    }
    
    .player-email {
        color: #64748b;
        font-size: 0.8rem;
        margin: 0;
        word-break: break-all;
    }
    
    .player-card-body {
        padding: 1.25rem;
    }
    
    .player-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
        margin-bottom: 1rem;
    }
    
    .badge-game {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #FFFFFF;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge-role {
        background: rgba(0, 229, 255, 0.15);
        color: #00E5FF;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid rgba(0, 229, 255, 0.3);
    }
    
    .player-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    
    .stat-item {
        background: rgba(0, 229, 255, 0.05);
        border: 1px solid rgba(0, 229, 255, 0.1);
        border-radius: 12px;
        padding: 0.75rem;
        text-align: center;
    }
    
    .stat-value {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    
    .stat-value.wins {
        color: #22c55e;
    }
    
    .stat-value.losses {
        color: #ef4444;
    }
    
    .stat-label {
        color: #64748b;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .winrate-section {
        margin-bottom: 1rem;
    }
    
    .winrate-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .winrate-label {
        color: #94a3b8;
        font-size: 0.8rem;
    }
    
    .winrate-value {
        color: #00E5FF;
        font-weight: 700;
        font-size: 0.9rem;
    }
    
    .winrate-bar {
        height: 6px;
        background: rgba(0, 229, 255, 0.1);
        border-radius: 3px;
        overflow: hidden;
    }
    
    .winrate-fill {
        height: 100%;
        background: linear-gradient(90deg, #22c55e, #00E5FF);
        border-radius: 3px;
        transition: width 0.5s ease;
    }
    
    .player-card-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        gap: 0.75rem;
    }
    
    .btn-card {
        flex: 1;
        padding: 0.6rem;
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
    
    .btn-edit {
        background: rgba(99, 102, 241, 0.1);
        color: #818cf8;
        border: 1px solid rgba(99, 102, 241, 0.3);
    }
    
    .btn-edit:hover {
        background: rgba(99, 102, 241, 0.2);
        color: #FFFFFF;
        box-shadow: 0 0 15px rgba(99, 102, 241, 0.3);
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
        width: 80px;
        height: 80px;
        background: rgba(0, 229, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    
    .empty-icon i {
        font-size: 2rem;
        color: #64748b;
    }
    
    .empty-title {
        color: #FFFFFF;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .empty-text {
        color: #64748b;
        font-size: 0.95rem;
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
<div class="players-container">
    <div class="max-w-7xl mx-auto px-4 py-6">
        
        <!-- Hero Section -->
        <div class="players-hero">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="hero-icon">
                        <i class="fas fa-users text-2xl text-[#00E5FF]"></i>
                    </div>
                    <div>
                        <h1 class="hero-title"><span>{{ __('app.players.title') }}</span></h1>
                        <p class="hero-subtitle">{{ __('app.players.subtitle') }}</p>
                    </div>
                </div>
                @if(Auth::check() && in_array(Auth::user()->user_role, ['admin', 'super_admin']))
                <a href="{{ route('players.create') }}" class="btn-neon">
                    <i class="fas fa-user-plus"></i>
                    <span>{{ __('app.players.add_player') }}</span>
                </a>
                @endif
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-card">
            <form class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4" method="GET" action="{{ route('players.index') }}">
                <div>
                    <label class="filter-label">{{ __('app.players.filter_game') }}</label>
                    <select class="filter-select" name="game">
                        <option value="">{{ __('app.players.all_games') }}</option>
                        @foreach($games ?? [] as $game)
                        <option value="{{ $game->id }}" {{ request('game') == $game->id ? 'selected' : '' }}>{{ $game->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="filter-label">{{ __('app.players.filter_rank') }}</label>
                    <select class="filter-select" name="rank">
                        <option value="">{{ __('app.players.all_ranks') }}</option>
                        <option value="bronze" {{ request('rank') == 'bronze' ? 'selected' : '' }}>Bronze</option>
                        <option value="silver" {{ request('rank') == 'silver' ? 'selected' : '' }}>Silver</option>
                        <option value="gold" {{ request('rank') == 'gold' ? 'selected' : '' }}>Gold</option>
                        <option value="platinum" {{ request('rank') == 'platinum' ? 'selected' : '' }}>Platinum</option>
                        <option value="diamond" {{ request('rank') == 'diamond' ? 'selected' : '' }}>Diamond</option>
                        <option value="master" {{ request('rank') == 'master' ? 'selected' : '' }}>Master</option>
                    </select>
                </div>
                <div>
                    <label class="filter-label">{{ __('app.players.filter_status') }}</label>
                    <select class="filter-select" name="status">
                        <option value="">{{ __('app.common.all') }}</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('app.common.active') }}</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('app.common.inactive') }}</option>
                    </select>
                </div>
                <div>
                    <label class="filter-label">{{ __('app.search.search') }}</label>
                    <input type="text" class="filter-input" name="search" placeholder="{{ __('app.players.search_placeholder') }}" value="{{ request('search') }}">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-filter w-full">
                        <i class="fas fa-search mr-2"></i>{{ __('app.common.filter') }}
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Stats Summary -->
        <div class="stats-summary">
            <div class="stat-badge">
                <i class="fas fa-users"></i>
                <span>{{ $players->total() ?? count($players) }}</span>
                <small>{{ __('app.players.total_players') }}</small>
            </div>
            <div class="stat-badge">
                <i class="fas fa-circle text-green-500 text-xs"></i>
                <span>{{ $activePlayers ?? 0 }}</span>
                <small>{{ __('app.common.active') }}</small>
            </div>
        </div>
        
        <!-- Players Grid -->
        @if($players->count() > 0)
        <div class="players-grid">
            @foreach($players as $player)
            <div class="player-card">
                <div class="player-card-header">
                    <div class="player-avatar-wrapper">
                        @if($player->avatar_url)
                        <img src="{{ $player->avatar_url }}" alt="{{ $player->display_name }}" class="player-avatar">
                        @else
                        <div class="player-avatar-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                        @endif
                        @php 
                            $statusClass = $player->status === 'active' ? 'online' : ($player->status === 'suspended' ? 'away' : 'offline'); 
                        @endphp
                        <span class="status-indicator {{ $statusClass }}"></span>
                    </div>
                    <h3 class="player-name">{{ $player->display_name }}</h3>
                    <p class="player-email">{{ $player->email }}</p>
                </div>
                
                <div class="player-card-body">
                    <div class="player-badges">
                        @if(optional($player->teams->first())->game)
                        <span class="badge-game">{{ $player->teams->first()->game->name }}</span>
                        @endif
                        <span class="badge-role">{{ __('app.players.player') }}</span>
                    </div>
                    
                    @php 
                        $wins = $player->wins ?? 0; 
                        $losses = $player->losses ?? 0; 
                        $matches = max(1, $wins + $losses); 
                        $winrate = round(($wins/$matches)*100, 1); 
                    @endphp
                    
                    <div class="player-stats">
                        <div class="stat-item">
                            <div class="stat-value wins">{{ $wins }}</div>
                            <div class="stat-label">{{ __('app.players.wins') }}</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value losses">{{ $losses }}</div>
                            <div class="stat-label">{{ __('app.players.losses') }}</div>
                        </div>
                    </div>
                    
                    <div class="winrate-section">
                        <div class="winrate-header">
                            <span class="winrate-label">{{ __('app.players.winrate') }}</span>
                            <span class="winrate-value">{{ $winrate }}%</span>
                        </div>
                        <div class="winrate-bar">
                            <div class="winrate-fill" style="width: {{ $winrate }}%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="player-card-footer">
                    <a href="{{ route('players.show', $player->id) }}" class="btn-card btn-view">
                        <i class="fas fa-eye"></i>
                        <span>{{ __('app.common.view') }}</span>
                    </a>
                    @if(Auth::check() && in_array(Auth::user()->user_role, ['admin', 'super_admin']))
                    <a href="{{ route('players.edit', $player->id) }}" class="btn-card btn-edit">
                        <i class="fas fa-edit"></i>
                        <span>{{ __('app.common.edit') }}</span>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $players->withQueryString()->links() }}
        </div>
        @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-user-slash"></i>
            </div>
            <h3 class="empty-title">{{ __('app.players.no_players_found') }}</h3>
            <p class="empty-text">{{ __('app.players.no_players_description') }}</p>
        </div>
        @endif
        
    </div>
</div>
@endsection
