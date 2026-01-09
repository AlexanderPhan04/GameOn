@extends('layouts.app')

@section('title', 'Tìm kiếm')

@push('styles')
<style>
    .search-page {
        background: linear-gradient(180deg, #000814 0%, #0d1b2a 100%);
        min-height: 100vh;
        padding: 2rem 1rem;
    }
    .search-container {
        max-width: 1100px;
        margin: 0 auto;
    }
    
    /* Page Header */
    .search-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .search-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 2.5rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.5rem;
        text-shadow: 0 0 30px rgba(0, 229, 255, 0.3);
    }
    .search-subtitle {
        color: #94a3b8;
        font-family: 'Inter', sans-serif;
    }
    
    /* Search Box */
    .search-box {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 229, 255, 0.05);
    }
    .search-form {
        display: flex;
        gap: 0.75rem;
    }
    .search-input {
        flex: 1;
        background: rgba(0, 0, 85, 0.3);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        color: #fff;
        font-family: 'Inter', sans-serif;
        font-size: 1rem;
        outline: none;
        transition: all 0.3s ease;
    }
    .search-input::placeholder {
        color: #64748b;
    }
    .search-input:focus {
        border-color: rgba(0, 229, 255, 0.5);
        box-shadow: 0 0 0 4px rgba(0, 229, 255, 0.1), 0 0 20px rgba(0, 229, 255, 0.1);
    }
    .search-btn {
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #000055 0%, rgba(0, 229, 255, 0.2) 100%);
        border: 1px solid rgba(0, 229, 255, 0.4);
        border-radius: 12px;
        color: #00E5FF;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .search-btn:hover {
        background: linear-gradient(135deg, rgba(0, 229, 255, 0.15) 0%, #000055 100%);
        box-shadow: 0 0 25px rgba(0, 229, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Layout */
    .search-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 1.5rem;
    }
    @media (max-width: 900px) {
        .search-layout {
            grid-template-columns: 1fr;
        }
    }
    
    /* Sidebar Filters */
    .filter-card {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
        position: sticky;
        top: 100px;
    }
    .filter-header {
        padding: 1rem 1.25rem;
        background: linear-gradient(135deg, rgba(0, 229, 255, 0.1) 0%, rgba(0, 0, 85, 0.3) 100%);
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    }
    .filter-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .filter-title i {
        color: #00E5FF;
    }
    .filter-list {
        padding: 0.5rem;
    }
    .filter-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.875rem 1rem;
        border-radius: 10px;
        color: #94a3b8;
        text-decoration: none;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        margin-bottom: 0.25rem;
    }
    .filter-item:hover {
        background: rgba(0, 229, 255, 0.08);
        color: #00E5FF;
    }
    .filter-item.active {
        background: linear-gradient(135deg, rgba(0, 229, 255, 0.15) 0%, rgba(0, 0, 85, 0.4) 100%);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.25);
    }
    .filter-item i {
        width: 20px;
        text-align: center;
        margin-right: 0.75rem;
    }
    .filter-item-left {
        display: flex;
        align-items: center;
    }
    .filter-badge {
        background: rgba(0, 229, 255, 0.15);
        color: #00E5FF;
        padding: 0.25rem 0.625rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .filter-item.active .filter-badge {
        background: rgba(0, 229, 255, 0.25);
    }

    /* Results Section */
    .results-section {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }
    
    /* Alert/Info Box */
    .info-box {
        background: linear-gradient(135deg, rgba(0, 229, 255, 0.1) 0%, rgba(0, 0, 85, 0.2) 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #94a3b8;
        font-family: 'Inter', sans-serif;
    }
    .info-box i {
        color: #00E5FF;
        font-size: 1.1rem;
    }
    .info-box.warning {
        background: linear-gradient(135deg, rgba(251, 191, 36, 0.1) 0%, rgba(0, 0, 85, 0.2) 100%);
        border-color: rgba(251, 191, 36, 0.25);
    }
    .info-box.warning i {
        color: #fbbf24;
    }
    
    /* Result Card */
    .result-card {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.12);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }
    .result-card:hover {
        border-color: rgba(0, 229, 255, 0.25);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3), 0 0 20px rgba(0, 229, 255, 0.05);
    }
    .result-header {
        padding: 1rem 1.25rem;
        background: linear-gradient(135deg, rgba(0, 229, 255, 0.08) 0%, rgba(0, 0, 85, 0.2) 100%);
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .result-header-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #000055 0%, rgba(0, 229, 255, 0.2) 100%);
        border: 1px solid rgba(0, 229, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #00E5FF;
    }
    .result-header-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
    }
    .result-list {
        padding: 0.5rem;
    }

    /* Result Item */
    .result-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-bottom: 0.25rem;
    }
    .result-item:hover {
        background: rgba(0, 229, 255, 0.08);
    }
    .result-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
        border: 2px solid rgba(0, 229, 255, 0.2);
        background-size: cover;
        background-position: center;
    }
    .result-avatar.team {
        border-radius: 12px;
        background: linear-gradient(135deg, #00E5FF 0%, #0099cc 100%);
    }
    .result-avatar.tournament {
        border-radius: 12px;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    .result-avatar.game {
        border-radius: 12px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    .result-info {
        flex: 1;
        min-width: 0;
    }
    .result-name {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 0.125rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .result-meta {
        font-size: 0.8rem;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .result-action {
        padding: 0.5rem 1rem;
        background: rgba(0, 0, 85, 0.4);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 8px;
        color: #00E5FF;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        opacity: 0;
        transition: all 0.2s ease;
    }
    .result-item:hover .result-action {
        opacity: 1;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }
    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    .empty-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.5rem;
    }
    .empty-desc {
        color: #64748b;
        font-family: 'Inter', sans-serif;
    }
</style>
@endpush

@section('content')
<div class="search-page">
    <div class="search-container">
        <!-- Header -->
        <div class="search-header">
            <h1 class="search-title">
                <i class="fas fa-search" style="color: #00E5FF;"></i> Tìm kiếm
            </h1>
            <p class="search-subtitle">Khám phá người chơi, đội, giải đấu và game</p>
        </div>

        <!-- Search Box -->
        <div class="search-box">
            <form method="get" action="{{ route('search.view') }}" class="search-form">
                <input class="search-input" type="text" name="q" value="{{ $q }}" 
                       placeholder="Tìm kiếm người dùng, đội, giải đấu, game..." autofocus>
                <button class="search-btn" type="submit">
                    <i class="fas fa-search"></i>
                    <span>Tìm kiếm</span>
                </button>
            </form>
        </div>

        <!-- Layout -->
        <div class="search-layout">
            <!-- Sidebar Filters -->
            <aside>
                <div class="filter-card">
                    <div class="filter-header">
                        <div class="filter-title">
                            <i class="fas fa-filter"></i> Bộ lọc
                        </div>
                    </div>
                    <div class="filter-list">
                        @php $current = request('type'); @endphp
                        
                        <a class="filter-item {{ !$current ? 'active' : '' }}" href="{{ route('search.view',['q'=>$q]) }}">
                            <span class="filter-item-left">
                                <i class="fas fa-globe"></i> Tất cả
                            </span>
                            <span class="filter-badge">{{ array_sum($counts) }}</span>
                        </a>
                        
                        <a class="filter-item {{ $current==='users' ? 'active' : '' }}" href="{{ route('search.view',['q'=>$q,'type'=>'users']) }}">
                            <span class="filter-item-left">
                                <i class="fas fa-user"></i> Người dùng
                            </span>
                            <span class="filter-badge">{{ $counts['users'] }}</span>
                        </a>
                        
                        <a class="filter-item {{ $current==='teams' ? 'active' : '' }}" href="{{ route('search.view',['q'=>$q,'type'=>'teams']) }}">
                            <span class="filter-item-left">
                                <i class="fas fa-users"></i> Đội
                            </span>
                            <span class="filter-badge">{{ $counts['teams'] }}</span>
                        </a>
                        
                        <a class="filter-item {{ $current==='tournaments' ? 'active' : '' }}" href="{{ route('search.view',['q'=>$q,'type'=>'tournaments']) }}">
                            <span class="filter-item-left">
                                <i class="fas fa-trophy"></i> Giải đấu
                            </span>
                            <span class="filter-badge">{{ $counts['tournaments'] }}</span>
                        </a>
                        
                        <a class="filter-item {{ $current==='games' ? 'active' : '' }}" href="{{ route('search.view',['q'=>$q,'type'=>'games']) }}">
                            <span class="filter-item-left">
                                <i class="fas fa-gamepad"></i> Game
                            </span>
                            <span class="filter-badge">{{ $counts['games'] }}</span>
                        </a>
                    </div>
                </div>
            </aside>

            <!-- Results Section -->
            <section class="results-section">
                @if($q === '')
                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <span>Nhập từ khóa để bắt đầu tìm kiếm người chơi, đội, giải đấu hoặc game.</span>
                    </div>
                    
                    <div class="result-card">
                        <div class="empty-state">
                            <div class="empty-icon">🔍</div>
                            <h3 class="empty-title">Bắt đầu tìm kiếm</h3>
                            <p class="empty-desc">Nhập tên người dùng, đội, giải đấu hoặc game bạn muốn tìm</p>
                        </div>
                    </div>
                @else
                    @php
                        $hasResults = false;
                        $blocks = [];
                        if($users->count()) {
                            $hasResults = true;
                            $blocks[] = [
                                'title' => 'Người dùng',
                                'icon' => 'fa-user',
                                'type' => 'user',
                                'items' => $users,
                                'link' => function($u) use ($user) { 
                                    return ($u->id == $user->id) ? route('profile.show') : route('profile.show-user', $u->id);
                                }
                            ];
                        }
                        if($teams->count()) {
                            $hasResults = true;
                            $blocks[] = [
                                'title' => 'Đội',
                                'icon' => 'fa-users',
                                'type' => 'team',
                                'items' => $teams,
                                'link' => function($t) { return route('teams.show', $t->id); }
                            ];
                        }
                        if($tournaments->count()) {
                            $hasResults = true;
                            $blocks[] = [
                                'title' => 'Giải đấu',
                                'icon' => 'fa-trophy',
                                'type' => 'tournament',
                                'items' => $tournaments,
                                'link' => function($t) { return route('tournaments.show', $t->id); }
                            ];
                        }
                        if($games->count()) {
                            $hasResults = true;
                            $blocks[] = [
                                'title' => 'Game',
                                'icon' => 'fa-gamepad',
                                'type' => 'game',
                                'items' => $games,
                                'link' => function($g) { return '#'; }
                            ];
                        }
                    @endphp

                    @if(!$hasResults)
                        <div class="info-box warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Không tìm thấy kết quả nào cho "<strong>{{ $q }}</strong>"</span>
                        </div>
                        
                        <div class="result-card">
                            <div class="empty-state">
                                <div class="empty-icon">😕</div>
                                <h3 class="empty-title">Không có kết quả</h3>
                                <p class="empty-desc">Thử tìm kiếm với từ khóa khác hoặc kiểm tra lại chính tả</p>
                            </div>
                        </div>
                    @else
                        @foreach($blocks as $b)
                        <div class="result-card">
                            <div class="result-header">
                                <div class="result-header-icon">
                                    <i class="fas {{ $b['icon'] }}"></i>
                                </div>
                                <span class="result-header-title">{{ $b['title'] }}</span>
                            </div>
                            <div class="result-list">
                                @foreach($b['items'] as $item)
                                <a class="result-item" href="{{ $b['link']($item) }}">
                                    @php
                                        $avatarUrl = null;
                                        $initials = '';
                                        $name = $item->name ?? $item->full_name ?? '';
                                        if($b['type'] === 'user') {
                                            $avatarUrl = get_avatar_url($item->avatar ?? null);
                                            $initials = strtoupper(substr($name, 0, 1));
                                        } else {
                                            $initials = strtoupper(substr($name, 0, 2));
                                        }
                                    @endphp
                                    <div class="result-avatar {{ $b['type'] }}" 
                                         @if($avatarUrl) style="background-image: url('{{ $avatarUrl }}')" @endif>
                                        @if(!$avatarUrl){{ $initials }}@endif
                                    </div>
                                    <div class="result-info">
                                        <div class="result-name">{{ $name }}</div>
                                        @if(isset($item->email))
                                        <div class="result-meta">{{ $item->email }}</div>
                                        @elseif(isset($item->description))
                                        <div class="result-meta">{{ Str::limit($item->description, 50) }}</div>
                                        @endif
                                    </div>
                                    <span class="result-action">Xem</span>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    @endif
                @endif
            </section>
        </div>
    </div>
</div>
@endsection
