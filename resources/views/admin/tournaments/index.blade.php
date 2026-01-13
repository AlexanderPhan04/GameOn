@extends('layouts.app')

@section('title', __('app.profile.manage_tournaments'))

@push('styles')
<style>
    .tournaments-container {
        background: #000814;
        min-height: 100vh;
    }
    
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
        padding: 1.5rem 2rem;
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
    
    .hero-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .hero-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .hero-icon {
        width: 60px;
        height: 60px;
        min-width: 60px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 25px rgba(245, 158, 11, 0.3);
    }
    
    .hero-icon i {
        font-size: 1.5rem;
        color: white;
    }
    
    .hero-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #00E5FF;
        margin: 0;
        text-shadow: 0 0 20px rgba(0, 229, 255, 0.5);
    }
    
    .hero-subtitle {
        color: #94a3b8;
        font-size: 0.9rem;
        margin: 0.25rem 0 0 0;
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
        white-space: nowrap;
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
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    
    .filter-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr auto;
        gap: 1rem;
        align-items: end;
    }
    
    @media (max-width: 1024px) {
        .filter-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
    
    @media (max-width: 640px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }
        
        .hero-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .btn-neon {
            width: 100%;
            justify-content: center;
        }
    }
    
    .filter-label {
        color: #94a3b8;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.4rem;
        display: block;
    }
    
    .filter-select,
    .filter-input {
        background: rgba(0, 229, 255, 0.05);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        color: #FFFFFF;
        padding: 0.6rem 1rem;
        font-size: 0.875rem;
        width: 100%;
        transition: all 0.3s ease;
        height: 44px;
        box-sizing: border-box;
    }
    
    .filter-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2300E5FF' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
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
    
    .filter-buttons {
        display: flex;
        gap: 0.5rem;
        align-items: center;
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
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 44px;
        box-sizing: border-box;
    }
    
    .btn-filter:hover {
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.5);
        transform: translateY(-2px);
    }
    
    .btn-reset {
        background: rgba(100, 116, 139, 0.2);
        color: #94a3b8;
        border: 1px solid rgba(100, 116, 139, 0.3);
        padding: 0.6rem 0.8rem;
        border-radius: 10px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 44px;
        width: 44px;
        box-sizing: border-box;
    }
    
    .btn-reset:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border-color: rgba(239, 68, 68, 0.3);
    }
</style>

<style>
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
        height: 140px;
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
        font-size: 2.5rem;
        color: rgba(0, 229, 255, 0.3);
    }
    
    .tournament-status {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 0.35rem 0.7rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-draft { background: rgba(100, 116, 139, 0.9); color: #FFFFFF; }
    .status-registration { background: rgba(34, 197, 94, 0.9); color: #FFFFFF; box-shadow: 0 0 15px rgba(34, 197, 94, 0.5); }
    .status-ongoing { background: rgba(59, 130, 246, 0.9); color: #FFFFFF; box-shadow: 0 0 15px rgba(59, 130, 246, 0.5); }
    .status-completed { background: rgba(139, 92, 246, 0.9); color: #FFFFFF; }
    .status-cancelled { background: rgba(239, 68, 68, 0.9); color: #FFFFFF; }
    
    .tournament-game-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 0.35rem 0.7rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        background: rgba(0, 0, 0, 0.7);
        color: #00E5FF;
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .tournament-card-body {
        padding: 1.25rem;
    }
    
    .tournament-name {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #FFFFFF;
        margin: 0 0 0.5rem 0;
        line-height: 1.3;
    }
    
    .tournament-date {
        color: #64748b;
        font-size: 0.8rem;
        margin-bottom: 0.75rem;
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
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }
    
    .meta-item {
        background: rgba(0, 229, 255, 0.05);
        border: 1px solid rgba(0, 229, 255, 0.1);
        border-radius: 10px;
        padding: 0.5rem;
        text-align: center;
    }
    
    .meta-value {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: #00E5FF;
        line-height: 1;
        margin-bottom: 0.2rem;
    }
    
    .meta-label {
        color: #64748b;
        font-size: 0.6rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .tournament-description {
        color: #94a3b8;
        font-size: 0.8rem;
        line-height: 1.4;
        margin-bottom: 0.75rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .tournament-creator {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0.75rem;
        background: rgba(0, 229, 255, 0.03);
        border-radius: 8px;
        font-size: 0.75rem;
        color: #64748b;
    }
    
    .tournament-creator i {
        color: #00E5FF;
        margin-right: 5px;
    }
    
    .tournament-card-footer {
        padding: 0.75rem 1.25rem;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-card {
        flex: 1;
        padding: 0.5rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        cursor: pointer;
    }
    
    .btn-view {
        background: rgba(0, 229, 255, 0.1);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.3);
    }
    
    .btn-view:hover {
        background: rgba(0, 229, 255, 0.2);
        color: #FFFFFF;
    }
    
    .btn-edit {
        background: rgba(99, 102, 241, 0.1);
        color: #818cf8;
        border: 1px solid rgba(99, 102, 241, 0.3);
    }
    
    .btn-edit:hover {
        background: rgba(99, 102, 241, 0.2);
        color: #FFFFFF;
    }
    
    .btn-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .btn-delete:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #FFFFFF;
    }
</style>

<style>
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
        margin-bottom: 1.5rem;
    }
    
    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }
    
    /* Custom Modal (No Bootstrap) */
    .custom-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(4px);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    
    .custom-modal-overlay.active {
        display: flex;
    }
    
    .custom-modal {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.3);
        border-radius: 20px;
        width: 100%;
        max-width: 450px;
        overflow: hidden;
        animation: modalSlideIn 0.3s ease;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .custom-modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .custom-modal-title {
        color: #FFFFFF;
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .custom-modal-title i {
        color: #f59e0b;
    }
    
    .custom-modal-close {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: #94a3b8;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .custom-modal-close:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }
    
    .custom-modal-body {
        padding: 1.5rem;
        color: #94a3b8;
    }
    
    .custom-modal-body p {
        margin: 0 0 1rem 0;
    }
    
    .custom-modal-body strong {
        color: #00E5FF;
    }
    
    .warning-box {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.3);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 0.85rem;
    }
    
    .warning-box i {
        color: #f59e0b;
        margin-top: 2px;
    }
    
    .warning-box span {
        color: #fbbf24;
    }
    
    .custom-modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }
    
    .btn-modal-cancel {
        background: rgba(100, 116, 139, 0.2);
        color: #94a3b8;
        border: 1px solid rgba(100, 116, 139, 0.3);
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-modal-cancel:hover {
        background: rgba(100, 116, 139, 0.3);
        color: #FFFFFF;
    }
    
    .btn-modal-delete {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.4);
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-modal-delete:hover {
        background: rgba(239, 68, 68, 0.3);
        color: #FFFFFF;
        box-shadow: 0 0 20px rgba(239, 68, 68, 0.3);
    }
    
    .btn-modal-delete:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<div class="tournaments-container">
    <div class="max-w-7xl mx-auto px-4 py-6">
        
        <!-- Hero Section -->
        <div class="tournaments-hero">
            <div class="hero-content">
                <div class="hero-left">
                    <div class="hero-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div>
                        <h1 class="hero-title">{{ __('app.profile.manage_tournaments') }}</h1>
                        <p class="hero-subtitle">{{ __('app.profile.manage_tournaments_description') }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.tournaments.create') }}" class="btn-neon">
                    <i class="fas fa-plus"></i>
                    <span>{{ __('app.tournaments.create_new_tournament') }}</span>
                </a>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-card">
            <form method="GET" action="{{ route('admin.tournaments.index') }}">
                <div class="filter-grid">
                    <div>
                        <label class="filter-label">{{ __('app.search.search') }}</label>
                        <input type="text" class="filter-input" name="search" placeholder="{{ __('app.tournaments.search_placeholder') }}" value="{{ request('search') }}">
                    </div>
                    <div>
                        <label class="filter-label">Game</label>
                        <select class="filter-select" name="game_id">
                            <option value="">{{ __('app.tournaments.all_games') }}</option>
                            @foreach($games as $game)
                            <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>{{ $game->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="filter-label">{{ __('app.teams.status') }}</label>
                        <select class="filter-select" name="status">
                            <option value="">{{ __('app.tournaments.all_statuses') }}</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>{{ __('app.tournaments.draft') }}</option>
                            <option value="registration" {{ request('status') === 'registration' ? 'selected' : '' }}>{{ __('app.tournaments.registration') }}</option>
                            <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>{{ __('app.tournaments.ongoing') }}</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('app.tournaments.completed') }}</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('app.tournaments.cancelled') }}</option>
                        </select>
                    </div>
                    <div class="filter-buttons">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-search"></i>
                            {{ __('app.common.filter') }}
                        </button>
                        <a href="{{ route('admin.tournaments.index') }}" class="btn-reset">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tournaments Grid -->
        @if($tournaments->count() > 0)
        <div class="tournaments-grid">
            @foreach($tournaments as $tournament)
            <div class="tournament-card">
                <div class="tournament-banner">
                    @if($tournament->banner_url)
                    <img src="{{ $tournament->banner_url }}" alt="{{ $tournament->name }}">
                    @else
                    <div class="tournament-banner-placeholder">
                        <i class="fas fa-trophy"></i>
                    </div>
                    @endif
                    
                    <span class="tournament-status status-{{ $tournament->status }}">
                        {{ $tournament->status_label ?? __('app.tournaments.' . $tournament->status) }}
                    </span>
                    
                    @if($tournament->game)
                    <span class="tournament-game-badge">
                        <i class="fas fa-gamepad"></i>
                        {{ $tournament->game->name }}
                    </span>
                    @endif
                </div>
                
                <div class="tournament-card-body">
                    <h3 class="tournament-name">{{ $tournament->name }}</h3>
                    
                    <div class="tournament-date">
                        <i class="far fa-calendar-alt"></i>
                        <span>{{ $tournament->formatted_date_range ?? $tournament->getFormattedDateRange() }}</span>
                    </div>
                    
                    <div class="tournament-meta">
                        <div class="meta-item">
                            <div class="meta-value">{{ $tournament->max_participants ?? '-' }}</div>
                            <div class="meta-label">{{ __('app.tournaments.teams') }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-value">{{ $tournament->competition_type_label ?? '-' }}</div>
                            <div class="meta-label">Loại</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-value">{{ $tournament->location_type_label ?? '-' }}</div>
                            <div class="meta-label">{{ __('app.tournaments.format') }}</div>
                        </div>
                    </div>
                    
                    @if($tournament->description)
                    <p class="tournament-description">{{ Str::limit($tournament->description, 80) }}</p>
                    @endif
                    
                    <div class="tournament-creator">
                        <span><i class="fas fa-user"></i>{{ $tournament->creator ? $tournament->creator->name : 'N/A' }}</span>
                        <span>{{ $tournament->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
                
                <div class="tournament-card-footer">
                    <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn-card btn-view">
                        <i class="fas fa-eye"></i>
                        <span>Xem</span>
                    </a>
                    <a href="{{ route('admin.tournaments.edit', $tournament->id) }}" class="btn-card btn-edit">
                        <i class="fas fa-edit"></i>
                        <span>Sửa</span>
                    </a>
                    <button class="btn-card btn-delete" onclick="openDeleteModal('{{ $tournament->id }}', '{{ $tournament->name }}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($tournaments->hasPages())
        <div class="pagination-wrapper">
            {{ $tournaments->appends(request()->query())->links() }}
        </div>
        @endif
        @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <h3 class="empty-title">{{ __('app.tournaments.no_tournaments') }}</h3>
            <p class="empty-text">{{ __('app.tournaments.no_tournaments_description') }}</p>
            <a href="{{ route('admin.tournaments.create') }}" class="btn-neon">
                <i class="fas fa-plus"></i>
                <span>{{ __('app.tournaments.create_new_tournament') }}</span>
            </a>
        </div>
        @endif
        
    </div>
</div>

<!-- Custom Delete Modal (No Bootstrap) -->
<div class="custom-modal-overlay" id="deleteModalOverlay">
    <div class="custom-modal">
        <div class="custom-modal-header">
            <h3 class="custom-modal-title">
                <i class="fas fa-exclamation-triangle"></i>
                {{ __('app.tournaments.confirm_delete') }}
            </h3>
            <button class="custom-modal-close" onclick="closeDeleteModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="custom-modal-body">
            <p>{{ __('app.tournaments.confirm_delete_message') }} <strong id="deleteTournamentName"></strong>?</p>
            <div class="warning-box">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ __('app.tournaments.delete_warning') }}</span>
            </div>
        </div>
        <div class="custom-modal-footer">
            <button class="btn-modal-cancel" onclick="closeDeleteModal()">
                {{ __('app.common.cancel') }}
            </button>
            <button class="btn-modal-delete" id="confirmDeleteBtn" onclick="confirmDelete()">
                <i class="fas fa-trash"></i>
                {{ __('app.tournaments.delete_tournament') }}
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentTournamentId = null;

function openDeleteModal(id, name) {
    currentTournamentId = id;
    document.getElementById('deleteTournamentName').textContent = name;
    document.getElementById('deleteModalOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModalOverlay').classList.remove('active');
    document.body.style.overflow = '';
    currentTournamentId = null;
}

function confirmDelete() {
    if (!currentTournamentId) return;
    
    const btn = document.getElementById('confirmDeleteBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("app.tournaments.deleting") }}';
    btn.disabled = true;
    
    fetch(`/admin/tournaments/${currentTournamentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeDeleteModal();
            location.reload();
        } else {
            alert('{{ __("app.tournaments.delete_error") }}');
            btn.innerHTML = '<i class="fas fa-trash"></i> {{ __("app.tournaments.delete_tournament") }}';
            btn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ __("app.tournaments.delete_error") }}');
        btn.innerHTML = '<i class="fas fa-trash"></i> {{ __("app.tournaments.delete_tournament") }}';
        btn.disabled = false;
    });
}

// Close modal when clicking outside
document.getElementById('deleteModalOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endpush
