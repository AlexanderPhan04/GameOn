<style>
.left-sidebar {
    position: fixed;
    left: 0;
    top: 70px;
    width: 280px;
    height: calc(100vh - 70px);
    background: rgba(13, 27, 42, 0.6);
    backdrop-filter: blur(10px);
    border-right: 1px solid rgba(0, 229, 255, 0.1);
    padding: 1rem 0.5rem;
    overflow-y: auto;
    z-index: 10;
}

.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.625rem 0.75rem;
    border-radius: 10px;
    color: #e2e8f0;
    text-decoration: none;
    font-family: 'Inter', sans-serif;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s ease;
    cursor: pointer;
    margin-bottom: 0.25rem;
}

.sidebar-item:hover {
    background: rgba(0, 229, 255, 0.1);
    color: #00E5FF;
}

.sidebar-item.active {
    background: rgba(0, 229, 255, 0.15);
    color: #00E5FF;
}

.sidebar-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
    background: rgba(0, 0, 85, 0.3);
    border: 1px solid rgba(0, 229, 255, 0.2);
}

.sidebar-item:hover .sidebar-icon {
    background: rgba(0, 229, 255, 0.2);
    border-color: rgba(0, 229, 255, 0.4);
}

.sidebar-text {
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.sidebar-divider {
    height: 1px;
    background: rgba(0, 229, 255, 0.1);
    margin: 0.75rem 0.5rem;
}

.sidebar-section-title {
    padding: 0.5rem 0.75rem;
    font-family: 'Rajdhani', sans-serif;
    font-size: 0.8rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 0.5rem;
}

/* Tournament Badge */
.tournament-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.125rem 0.375rem;
    background: rgba(34, 197, 94, 0.15);
    border-radius: 8px;
    font-size: 0.65rem;
    color: #4ade80;
    font-weight: 600;
    margin-left: auto;
}

.tournament-badge.registration {
    background: rgba(59, 130, 246, 0.15);
    color: #60a5fa;
}

/* Honor Rank Badge */
.honor-rank-badge {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 700;
    margin-left: auto;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: #000;
}

.honor-rank-badge.rank-2 {
    background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
}

.honor-rank-badge.rank-3 {
    background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
}

/* Scrollbar */
.left-sidebar::-webkit-scrollbar {
    width: 6px;
}

.left-sidebar::-webkit-scrollbar-track {
    background: rgba(0, 0, 85, 0.2);
}

.left-sidebar::-webkit-scrollbar-thumb {
    background: rgba(0, 229, 255, 0.3);
    border-radius: 10px;
}

.left-sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 229, 255, 0.5);
}

/* Responsive */
@media (max-width: 1200px) {
    .left-sidebar {
        display: none;
    }
}

/* Admin/Super Admin: Move sidebar to right */
@php
    $isAdmin = auth()->check() && in_array(auth()->user()->user_role, ['admin', 'super_admin']);
@endphp
@if($isAdmin)
    <style>
        .left-sidebar {
            left: auto !important;
            right: 0 !important;
            border-right: none !important;
            border-left: 1px solid rgba(0, 229, 255, 0.1) !important;
        }
    </style>
@endif
</style>

<div class="left-sidebar">
    <ul class="sidebar-menu">
        <!-- User Profile -->
        @auth
        <li>
            <a href="{{ route('profile.show') }}" class="sidebar-item">
                <div class="sidebar-icon" style="background-image: url('{{ auth()->user()->getDisplayAvatar() }}'); background-size: cover; background-position: center;"></div>
                <span class="sidebar-text">{{ auth()->user()->name }}</span>
            </a>
        </li>
        @endauth

        <!-- Main Menu Items -->
        <li>
            <a href="{{ route('search.view') }}" class="sidebar-item">
                <div class="sidebar-icon">
                    <i class="fas fa-user-friends" style="color: #60a5fa;"></i>
                </div>
                <span class="sidebar-text">Bạn bè</span>
            </a>
        </li>

        <li>
            <a href="{{ route('teams.index') }}" class="sidebar-item">
                <div class="sidebar-icon">
                    <i class="fas fa-users" style="color: #8b5cf6;"></i>
                </div>
                <span class="sidebar-text">Đội</span>
            </a>
        </li>

        <li>
            <a href="{{ route('marketplace.index') }}" class="sidebar-item">
                <div class="sidebar-icon">
                    <i class="fas fa-store" style="color: #10b981;"></i>
                </div>
                <span class="sidebar-text">Marketplace</span>
            </a>
        </li>

        <div class="sidebar-divider"></div>

        <!-- Tournaments Section -->
        <div class="sidebar-section-title">
            <i class="fas fa-trophy"></i> Giải đấu đang diễn ra
        </div>

        @if($ongoingTournaments && $ongoingTournaments->count() > 0)
            @foreach($ongoingTournaments as $tournament)
                <li>
                    <a href="{{ route('tournaments.show', $tournament) }}" class="sidebar-item">
                        <div class="sidebar-icon">
                            @if($tournament->game && $tournament->game->image_url)
                                <img src="{{ $tournament->game->image_url }}" alt="{{ $tournament->game->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            @else
                                <i class="fas fa-gamepad" style="color: #f59e0b;"></i>
                            @endif
                        </div>
                        <span class="sidebar-text">{{ Str::limit($tournament->name, 20) }}</span>
                        <span class="tournament-badge {{ $tournament->status }}">
                            @if($tournament->status === 'ongoing')
                                <i class="fas fa-circle" style="font-size: 0.5rem;"></i> Live
                            @else
                                <i class="fas fa-clock"></i> Mở
                            @endif
                        </span>
                    </a>
                </li>
            @endforeach
            <li>
                <a href="{{ route('tournaments.index') }}" class="sidebar-item" style="font-size: 0.85rem; color: #00E5FF;">
                    <div class="sidebar-icon" style="background: transparent; border: none;">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <span class="sidebar-text">Xem tất cả giải đấu</span>
                </a>
            </li>
        @else
            <li class="sidebar-item" style="opacity: 0.5; cursor: default; font-size: 0.85rem;">
                <div class="sidebar-icon">
                    <i class="fas fa-trophy" style="color: #64748b;"></i>
                </div>
                <span class="sidebar-text">Chưa có giải đấu</span>
            </li>
        @endif

        <div class="sidebar-divider"></div>

        <!-- Top Honor Section -->
        <div class="sidebar-section-title">
            <i class="fas fa-award"></i> Top vinh danh
        </div>

        @if($topHonorUsers && $topHonorUsers->count() > 0)
            @foreach($topHonorUsers as $index => $honorVote)
                @php
                    $user = $honorVote->votedUser;
                    $rank = $index + 1;
                    $rankClass = $rank <= 3 ? "rank-{$rank}" : '';
                @endphp
                @if($user)
                    <li>
                        <a href="{{ route('profile.show-user', $user->id) }}" class="sidebar-item">
                            <div class="sidebar-icon" style="background-image: url('{{ $user->getDisplayAvatar() }}'); background-size: cover; background-position: center;"></div>
                            <span class="sidebar-text">{{ Str::limit($user->name, 18) }}</span>
                            <span class="honor-rank-badge {{ $rankClass }}">
                                @if($rank === 1)
                                    👑
                                @else
                                    {{ $rank }}
                                @endif
                            </span>
                        </a>
                    </li>
                @endif
            @endforeach
            <li>
                <a href="{{ route('honor.index') }}" class="sidebar-item" style="font-size: 0.85rem; color: #00E5FF;">
                    <div class="sidebar-icon" style="background: transparent; border: none;">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <span class="sidebar-text">Xem bảng xếp hạng</span>
                </a>
            </li>
        @else
            <li class="sidebar-item" style="opacity: 0.5; cursor: default; font-size: 0.85rem;">
                <div class="sidebar-icon">
                    <i class="fas fa-award" style="color: #64748b;"></i>
                </div>
                <span class="sidebar-text">Chưa có dữ liệu</span>
            </li>
        @endif
    </ul>
</div>
