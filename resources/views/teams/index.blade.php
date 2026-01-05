@extends('layouts.app')

@section('title', 'Đội của tôi')

@push('styles')
<style>
    .teams-page {
        min-height: calc(100vh - 64px);
        background: linear-gradient(180deg, #000814 0%, #0d1b2a 100%);
        padding: 2rem;
    }
    
    .teams-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .teams-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .teams-title-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.5rem;
        box-shadow: 0 8px 24px rgba(99, 102, 241, 0.3);
    }
    
    .teams-title h1 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }
    
    .teams-title p {
        color: #94a3b8;
        font-size: 0.9rem;
        margin: 0;
    }
    
    .btn-create-team {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none;
        border-radius: 12px;
        color: #fff;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(99, 102, 241, 0.3);
    }
    
    .btn-create-team:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(99, 102, 241, 0.5);
        color: #fff;
    }
    
    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    }
    
    .section-title i {
        color: #f59e0b;
        font-size: 1.25rem;
    }
    
    .section-title.member i {
        color: #00E5FF;
    }
    
    .section-title h2 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.25rem;
        font-weight: 600;
        color: #fff;
        margin: 0;
    }
    
    .teams-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    
    .team-card {
        background: linear-gradient(135deg, rgba(13, 27, 42, 0.9), rgba(0, 0, 34, 0.9));
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .team-card:hover {
        transform: translateY(-4px);
        border-color: rgba(0, 229, 255, 0.4);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 30px rgba(0, 229, 255, 0.1);
    }
    
    .team-card.captain {
        border-color: rgba(245, 158, 11, 0.3);
    }
    
    .team-card.captain:hover {
        border-color: rgba(245, 158, 11, 0.5);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 30px rgba(245, 158, 11, 0.1);
    }
    
    .team-card-image {
        position: relative;
        height: 160px;
        overflow: hidden;
    }
    
    .team-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .team-card-image-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #1e3a5f, #0d1b2a);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .team-card-image-placeholder i {
        font-size: 3rem;
        color: rgba(0, 229, 255, 0.3);
    }
    
    .captain-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }
    
    .team-card-body {
        padding: 1.25rem;
    }
    
    .team-card-name {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .team-card-desc {
        color: #94a3b8;
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .team-card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        color: #64748b;
        font-size: 0.8rem;
    }
    
    .meta-item i {
        color: #00E5FF;
        font-size: 0.85rem;
    }
    
    .team-card-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .tag-game {
        background: linear-gradient(135deg, rgba(0, 229, 255, 0.15), rgba(0, 153, 204, 0.15));
        border: 1px solid rgba(0, 229, 255, 0.3);
        color: #00E5FF;
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .tag-status {
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .tag-status.active {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(22, 163, 74, 0.15));
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #22c55e;
    }
    
    .tag-status.inactive {
        background: rgba(100, 116, 139, 0.15);
        border: 1px solid rgba(100, 116, 139, 0.3);
        color: #94a3b8;
    }
    
    .tag-status.suspended {
        background: rgba(245, 158, 11, 0.15);
        border: 1px solid rgba(245, 158, 11, 0.3);
        color: #f59e0b;
    }
    
    .team-card-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        gap: 0.75rem;
    }
    
    .btn-team {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0.6rem 1rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-team-view {
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.3);
        color: #00E5FF;
    }
    
    .btn-team-view:hover {
        background: rgba(0, 229, 255, 0.2);
        color: #fff;
    }
    
    .btn-team-edit {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.3);
        color: #f59e0b;
    }
    
    .btn-team-edit:hover {
        background: rgba(245, 158, 11, 0.2);
        color: #fff;
    }
    
    .btn-team-leave {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }
    
    .btn-team-leave:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #fff;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(135deg, rgba(13, 27, 42, 0.5), rgba(0, 0, 34, 0.5));
        border: 1px solid rgba(0, 229, 255, 0.1);
        border-radius: 20px;
    }
    
    .empty-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
        border: 1px solid rgba(99, 102, 241, 0.2);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .empty-icon i {
        font-size: 2.5rem;
        color: #6366f1;
    }
    
    .empty-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.5rem;
    }
    
    .empty-desc {
        color: #94a3b8;
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }
    
    @media (max-width: 768px) {
        .teams-page {
            padding: 1rem;
        }
        
        .teams-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .teams-grid {
            grid-template-columns: 1fr;
        }
        
        .team-card-footer {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="teams-page">
    <div class="teams-header">
        <div class="teams-title">
            <div class="teams-title-icon">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h1>Đội của tôi</h1>
                <p>Quản lý và tham gia các đội esports</p>
            </div>
        </div>
        <a href="{{ route('teams.create') }}" class="btn-create-team">
            <i class="fas fa-plus"></i>
            Tạo đội mới
        </a>
    </div>

    @php
        $myTeamsAsCaptain = $teams->where('captain_id', Auth::id());
        $myTeamsAsMember = Auth::user()->teams->where('captain_id', '!=', Auth::id());
    @endphp

    @if($myTeamsAsCaptain->count() > 0)
    <div class="section-title">
        <i class="fas fa-crown"></i>
        <h2>Đội tôi làm đội trưởng</h2>
    </div>
    <div class="teams-grid">
        @foreach($myTeamsAsCaptain as $team)
        <div class="team-card captain">
            <div class="team-card-image">
                @if($team->logo_url)
                    <img src="{{ asset('uploads/' . $team->logo_url) }}" alt="{{ $team->name }}">
                @else
                    <div class="team-card-image-placeholder">
                        <i class="fas fa-users"></i>
                    </div>
                @endif
                <div class="captain-badge">
                    <i class="fas fa-crown"></i>
                    Đội trưởng
                </div>
            </div>
            <div class="team-card-body">
                <h3 class="team-card-name">{{ $team->name }}</h3>
                <p class="team-card-desc">{{ $team->description ?: 'Chưa có mô tả' }}</p>
                <div class="team-card-meta">
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        {{ $team->members->count() }}/{{ $team->max_members ?? '∞' }} thành viên
                    </div>
                </div>
                <div class="team-card-tags">
                    @if($team->game)
                        <span class="tag-game">{{ $team->game->name }}</span>
                    @endif
                    <span class="tag-status {{ $team->status }}">
                        @if($team->status === 'active') Hoạt động
                        @elseif($team->status === 'inactive') Không hoạt động
                        @elseif($team->status === 'suspended') Tạm khóa
                        @else Cấm
                        @endif
                    </span>
                </div>
            </div>
            <div class="team-card-footer">
                <a href="{{ route('teams.show', $team->id) }}" class="btn-team btn-team-view">
                    <i class="fas fa-eye"></i> Xem
                </a>
                <a href="{{ route('teams.edit', $team->id) }}" class="btn-team btn-team-edit">
                    <i class="fas fa-edit"></i> Sửa
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if($myTeamsAsMember->count() > 0)
    <div class="section-title member">
        <i class="fas fa-user-friends"></i>
        <h2>Đội tôi tham gia</h2>
    </div>
    <div class="teams-grid">
        @foreach($myTeamsAsMember as $team)
        <div class="team-card">
            <div class="team-card-image">
                @if($team->logo_url)
                    <img src="{{ asset('uploads/' . $team->logo_url) }}" alt="{{ $team->name }}">
                @else
                    <div class="team-card-image-placeholder">
                        <i class="fas fa-users"></i>
                    </div>
                @endif
            </div>
            <div class="team-card-body">
                <h3 class="team-card-name">{{ $team->name }}</h3>
                <p class="team-card-desc">{{ $team->description ?: 'Chưa có mô tả' }}</p>
                <div class="team-card-meta">
                    <div class="meta-item">
                        <i class="fas fa-crown"></i>
                        {{ $team->captain->name ?? 'Chưa có đội trưởng' }}
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        {{ $team->members->count() }}/{{ $team->max_members ?? '∞' }} thành viên
                    </div>
                </div>
                <div class="team-card-tags">
                    @if($team->game)
                        <span class="tag-game">{{ $team->game->name }}</span>
                    @endif
                    <span class="tag-status {{ $team->status }}">
                        @if($team->status === 'active') Hoạt động
                        @elseif($team->status === 'inactive') Không hoạt động
                        @elseif($team->status === 'suspended') Tạm khóa
                        @else Cấm
                        @endif
                    </span>
                </div>
            </div>
            <div class="team-card-footer">
                <a href="{{ route('teams.show', $team->id) }}" class="btn-team btn-team-view">
                    <i class="fas fa-eye"></i> Xem
                </a>
                <form action="{{ route('teams.leave', $team->id) }}" method="POST" style="flex: 1; display: flex;">
                    @csrf
                    <button type="submit" class="btn-team btn-team-leave" style="width: 100%;" onclick="return confirm('Bạn có chắc muốn rời khỏi đội này?')">
                        <i class="fas fa-sign-out-alt"></i> Rời đội
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if($myTeamsAsCaptain->count() == 0 && $myTeamsAsMember->count() == 0)
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-users"></i>
        </div>
        <h3 class="empty-title">Bạn chưa tham gia đội nào</h3>
        <p class="empty-desc">Tạo đội mới để bắt đầu hành trình esports của bạn!</p>
        <a href="{{ route('teams.create') }}" class="btn-create-team">
            <i class="fas fa-plus"></i>
            Tạo đội đầu tiên
        </a>
    </div>
    @endif
</div>
@endsection
