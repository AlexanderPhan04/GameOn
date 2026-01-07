@extends('layouts.app')

@section('title', $team->name)

@push('styles')
<style>
    .team-container { background: #000814; min-height: 100vh; padding: 2rem 0; }
    
    /* Force proper two-column layout */
    .team-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    @media (min-width: 992px) {
        .team-layout {
            grid-template-columns: 2fr 1fr;
        }
    }
    .team-layout-left, .team-layout-right {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    /* Hero Section */
    .team-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .team-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #00E5FF, transparent);
    }

    .team-logo {
        width: 120px; height: 120px;
        border-radius: 20px;
        object-fit: cover;
        border: 3px solid rgba(0, 229, 255, 0.4);
        box-shadow: 0 0 30px rgba(0, 229, 255, 0.2);
    }
    .team-logo-fallback {
        width: 120px; height: 120px;
        border-radius: 20px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex; align-items: center; justify-content: center;
        border: 3px solid rgba(0, 229, 255, 0.4);
        box-shadow: 0 0 30px rgba(99, 102, 241, 0.3);
    }
    .team-logo-fallback i { font-size: 3rem; color: white; }

    .team-name {
        font-family: 'Rajdhani', sans-serif;
        font-size: 2.5rem; font-weight: 700;
        color: #FFFFFF; margin: 0;
        text-shadow: 0 0 20px rgba(0, 229, 255, 0.3);
    }
    .team-captain {
        color: #f59e0b; font-size: 1rem;
        display: flex; align-items: center; gap: 8px;
        margin-top: 0.5rem;
    }
    .team-captain i { color: #fbbf24; }

    /* Action Buttons */
    .btn-neon {
        background: linear-gradient(135deg, #000055, #000077);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.4);
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-size: 0.85rem; font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none;
    }
    .btn-neon:hover {
        background: rgba(0, 229, 255, 0.15);
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.4);
        color: #FFFFFF;
        transform: translateY(-2px);
    }
    .btn-neon-danger { border-color: rgba(239, 68, 68, 0.4); color: #ef4444; }
    .btn-neon-danger:hover { background: rgba(239, 68, 68, 0.15); box-shadow: 0 0 20px rgba(239, 68, 68, 0.4); }
    .btn-neon-success { border-color: rgba(34, 197, 94, 0.4); color: #22c55e; }
    .btn-neon-success:hover { background: rgba(34, 197, 94, 0.15); box-shadow: 0 0 20px rgba(34, 197, 94, 0.4); }
    .btn-neon-warning { border-color: rgba(245, 158, 11, 0.4); color: #f59e0b; }
    .btn-neon-warning:hover { background: rgba(245, 158, 11, 0.15); box-shadow: 0 0 20px rgba(245, 158, 11, 0.4); }
    .btn-neon-sm { padding: 0.4rem 0.8rem; font-size: 0.8rem; }

    /* Info Card */
    .info-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .info-card-title {
        color: #00E5FF;
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem; font-weight: 600;
        margin-bottom: 1rem;
        display: flex; align-items: center; gap: 10px;
    }
    .info-card-content { color: #94a3b8; line-height: 1.7; }

    /* Stats Grid */
    .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
    .stat-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    .stat-card:hover { border-color: rgba(0, 229, 255, 0.4); transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3); }
    .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
    .stat-icon.members { background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(99, 102, 241, 0.1)); color: #818cf8; }
    .stat-icon.date { background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(34, 197, 94, 0.1)); color: #22c55e; }
    .stat-icon.game { background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(245, 158, 11, 0.1)); color: #f59e0b; }
    .stat-icon.status { background: linear-gradient(135deg, rgba(0, 229, 255, 0.2), rgba(0, 229, 255, 0.1)); color: #00E5FF; }
    .stat-icon i { font-size: 1.25rem; }
    .stat-value { font-family: 'Rajdhani', sans-serif; font-size: 1.5rem; font-weight: 700; color: #FFFFFF; margin-bottom: 0.25rem; }
    .stat-label { color: #64748b; font-size: 0.85rem; }

    /* Members Card */
    .members-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
    }
    .members-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex; align-items: center; justify-content: space-between;
    }
    .members-title {
        color: #FFFFFF;
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem; font-weight: 600;
        display: flex; align-items: center; gap: 10px;
    }
    .members-count {
        background: rgba(0, 229, 255, 0.15);
        color: #00E5FF;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem; font-weight: 600;
    }
    .member-item {
        padding: 0.875rem 1.5rem;
        display: flex; align-items: center; justify-content: space-between;
        border-bottom: 1px solid rgba(0, 229, 255, 0.05);
        transition: all 0.3s ease;
    }
    .member-item:last-child { border-bottom: none; }
    .member-item:hover { background: rgba(0, 229, 255, 0.05); }
    .member-avatar {
        width: 40px; height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(0, 229, 255, 0.3);
    }
    .member-avatar-fallback {
        width: 40px; height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 700; font-size: 0.9rem;
    }
    .member-info { margin-left: 0.75rem; flex: 1; }
    .member-name { color: #FFFFFF; font-weight: 600; font-size: 0.9rem; }
    .member-id { color: #64748b; font-size: 0.75rem; }
    .badge-captain {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(245, 158, 11, 0.1));
        color: #fbbf24;
        padding: 0.3rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem; font-weight: 600;
        display: flex; align-items: center; gap: 4px;
    }
    .badge-member {
        background: rgba(100, 116, 139, 0.2);
        color: #94a3b8;
        padding: 0.3rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem; font-weight: 600;
    }
    .member-actions { display: flex; gap: 0.5rem; margin-left: 0.5rem; }
    .btn-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        background: transparent;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.8rem;
    }
    .btn-icon-danger { color: #ef4444; }
    .btn-icon-danger:hover { background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); }

    /* Chat Card */
    .chat-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
    }
    .chat-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex; align-items: center; justify-content: space-between;
    }
    .chat-title {
        color: #FFFFFF;
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem; font-weight: 600;
        display: flex; align-items: center; gap: 10px;
    }
    .chat-messages {
        height: 300px;
        overflow-y: auto;
        padding: 1rem 1.5rem;
    }
    .chat-message {
        display: flex; gap: 0.75rem;
        margin-bottom: 1rem;
    }
    .chat-message-avatar {
        width: 36px; height: 36px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }
    .chat-message-avatar-fallback {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 600; font-size: 0.8rem;
        flex-shrink: 0;
    }
    .chat-message-content { flex: 1; }
    .chat-message-header { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem; }
    .chat-message-name { color: #00E5FF; font-weight: 600; font-size: 0.85rem; }
    .chat-message-time { color: #64748b; font-size: 0.75rem; }
    .chat-message-text { color: #e2e8f0; font-size: 0.9rem; line-height: 1.5; }
    .chat-input-wrapper {
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
        display: flex; gap: 0.75rem;
    }
    .chat-input {
        flex: 1;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        color: #FFFFFF;
        font-size: 0.9rem;
        outline: none;
        transition: all 0.3s ease;
    }
    .chat-input:focus { border-color: #00E5FF; box-shadow: 0 0 10px rgba(0, 229, 255, 0.2); }
    .chat-input::placeholder { color: #64748b; }
    .chat-empty {
        text-align: center;
        padding: 3rem 1rem;
        color: #64748b;
    }
    .chat-empty i { font-size: 2rem; margin-bottom: 0.75rem; display: block; opacity: 0.5; }

    /* Modal */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(4px);
        z-index: 99999;
        display: none;
        align-items: center; justify-content: center;
        padding: 1rem;
    }
    .modal-overlay.active { display: flex; }
    .modal-content-custom {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.3);
        border-radius: 20px;
        width: 100%;
        max-width: 500px;
        max-height: 80vh;
        overflow: hidden;
    }
    .modal-header-custom {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex; align-items: center; justify-content: space-between;
    }
    .modal-title-custom { color: #FFFFFF; font-size: 1.1rem; font-weight: 600; }
    .modal-body-custom { padding: 1.5rem; color: #94a3b8; max-height: 400px; overflow-y: auto; }
    .modal-footer-custom {
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
        display: flex; gap: 0.75rem; justify-content: flex-end;
    }

    /* Search Input */
    .search-input-wrapper { position: relative; margin-bottom: 1rem; }
    .search-input {
        width: 100%;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        color: #FFFFFF;
        font-size: 0.9rem;
        outline: none;
    }
    .search-input:focus { border-color: #00E5FF; }
    .search-input::placeholder { color: #64748b; }
    .search-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #64748b; }

    /* User List in Modal */
    .user-list { max-height: 300px; overflow-y: auto; }
    .user-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.75rem;
        border-radius: 10px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .user-item:hover { background: rgba(0, 229, 255, 0.1); }
    .user-item-info { display: flex; align-items: center; gap: 0.75rem; }
    .user-item-avatar {
        width: 40px; height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    .user-item-avatar-fallback {
        width: 40px; height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 600;
    }
    .user-item-name { color: #FFFFFF; font-weight: 600; font-size: 0.9rem; }
    .user-item-email { color: #64748b; font-size: 0.8rem; }

    /* Join Card */
    .action-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
    }
    .action-title { color: #FFFFFF; font-family: 'Rajdhani', sans-serif; font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem; }
    .action-text { color: #64748b; font-size: 0.9rem; margin-bottom: 1rem; }

    @media (max-width: 768px) {
        .team-hero { padding: 1.5rem; }
        .team-logo, .team-logo-fallback { width: 80px; height: 80px; }
        .team-name { font-size: 1.75rem; }
        .stats-grid { grid-template-columns: 1fr; }
        .chat-messages { height: 250px; }
    }
</style>
@endpush

@section('content')
<div class="team-container">
    <div class="container-fluid px-4" style="max-width: 1400px; margin: 0 auto;">
        <!-- Hero Section -->
        <div class="team-hero">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
                <div class="d-flex align-items-center gap-4">
                    @if($team->logo_url)
                    <img src="{{ $team->logo_url }}" class="team-logo" alt="{{ $team->name }}">
                    @else
                    <div class="team-logo-fallback">
                        <i class="fas fa-users"></i>
                    </div>
                    @endif
                    <div>
                        <h1 class="team-name">{{ $team->name }}</h1>
                        <div class="team-captain">
                            <i class="fas fa-crown"></i>
                            <span>{{ $team->captain->display_name ?? 'Chưa có đội trưởng' }}</span>
                        </div>
                    </div>
                </div>
                @can('update', $team)
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('teams.edit', $team->id) }}" class="btn-neon">
                        <i class="fas fa-edit"></i>
                        <span>Chỉnh sửa</span>
                    </a>
                    <form action="{{ route('teams.destroy', $team->id) }}" method="POST" 
                          onsubmit="return confirm('Bạn có chắc muốn xóa đội này?')" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-neon btn-neon-danger">
                            <i class="fas fa-trash"></i>
                            <span>Xóa đội</span>
                        </button>
                    </form>
                </div>
                @endcan
            </div>
        </div>

        <div class="team-layout">
            <div class="team-layout-left">
                <!-- Description -->
                @if($team->description)
                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fas fa-info-circle"></i>
                        Mô tả đội
                    </div>
                    <div class="info-card-content">{{ $team->description }}</div>
                </div>
                @endif

                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon members"><i class="fas fa-users"></i></div>
                        <div class="stat-value">{{ $team->members->count() }}/{{ $team->max_members ?? 10 }}</div>
                        <div class="stat-label">Thành viên</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon date"><i class="fas fa-calendar-alt"></i></div>
                        <div class="stat-value">{{ $team->created_at->format('d/m/Y') }}</div>
                        <div class="stat-label">Ngày thành lập</div>
                    </div>
                    @if($team->game)
                    <div class="stat-card">
                        <div class="stat-icon game"><i class="fas fa-gamepad"></i></div>
                        <div class="stat-value">{{ $team->game->name }}</div>
                        <div class="stat-label">Game</div>
                    </div>
                    @endif
                    <div class="stat-card">
                        <div class="stat-icon status"><i class="fas fa-shield-alt"></i></div>
                        <div class="stat-value" style="text-transform: capitalize;">{{ $team->status }}</div>
                        <div class="stat-label">Trạng thái</div>
                    </div>
                </div>

                <!-- Team Chat -->
                @auth
                @php $isMember = $team->members->where('id', Auth::id())->first(); @endphp
                @if($isMember)
                <div class="chat-card">
                    <div class="chat-header">
                        <div class="chat-title">
                            <i class="fas fa-comments"></i>
                            Chat nhóm
                        </div>
                    </div>
                    <div class="chat-messages" id="chatMessages">
                        <div class="chat-empty">
                            <i class="fas fa-comments"></i>
                            <p>Chưa có tin nhắn nào.<br>Hãy bắt đầu cuộc trò chuyện!</p>
                        </div>
                    </div>
                    <div class="chat-input-wrapper">
                        <input type="text" class="chat-input" id="chatInput" placeholder="Nhập tin nhắn..." maxlength="500">
                        <button type="button" class="btn-neon btn-neon-success" onclick="sendMessage()">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
                @endif
                @endauth
            </div>

            <div class="team-layout-right">
                <!-- Members List -->
                <div class="members-card">
                    <div class="members-header">
                        <div class="members-title">
                            <i class="fas fa-users"></i>
                            Thành viên
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="members-count">{{ $team->members->count() }}</span>
                            @can('update', $team)
                            <button type="button" class="btn-neon btn-neon-sm btn-neon-success" onclick="openAddMemberModal()">
                                <i class="fas fa-user-plus"></i>
                            </button>
                            @endcan
                        </div>
                    </div>
                    
                    @if($team->members->count() > 0)
                        @foreach($team->members as $member)
                        <div class="member-item">
                            <div class="d-flex align-items-center" style="flex: 1; min-width: 0;">
                                @if($member->avatar)
                                <img src="{{ get_avatar_url($member->avatar) }}" class="member-avatar" alt="{{ $member->display_name }}">
                                @else
                                <div class="member-avatar-fallback">{{ strtoupper(substr($member->name, 0, 1)) }}</div>
                                @endif
                                <div class="member-info">
                                    <div class="member-name">{{ $member->display_name }}</div>
                                    <div class="member-id">{{ $member->app_id }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @if($member->id === $team->captain_id)
                                <span class="badge-captain"><i class="fas fa-crown"></i> Đội trưởng</span>
                                @else
                                <span class="badge-member">Thành viên</span>
                                @can('update', $team)
                                <button type="button" class="btn-icon btn-icon-danger" 
                                        onclick="removeMember({{ $member->id }}, '{{ $member->display_name }}')" 
                                        title="Xóa thành viên">
                                    <i class="fas fa-user-minus"></i>
                                </button>
                                @endcan
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="chat-empty" style="padding: 2rem;">
                        <i class="fas fa-user-friends"></i>
                        <p>Chưa có thành viên nào</p>
                    </div>
                    @endif
                </div>

                <!-- Join/Leave Card -->
                @auth
                @php $isMember = $team->members->where('id', Auth::id())->first(); @endphp
                @if(Auth::user()->user_role === 'participant')
                <div class="action-card">
                    @if(!$isMember)
                    <div class="action-title">Tham gia đội</div>
                    <div class="action-text">Bạn có muốn trở thành thành viên của đội này?</div>
                    <form action="{{ route('teams.join', $team->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-neon btn-neon-success" style="width: 100%; justify-content: center;">
                            <i class="fas fa-user-plus"></i>
                            <span>Tham gia ngay</span>
                        </button>
                    </form>
                    @else
                    <div class="action-title">Bạn đã là thành viên</div>
                    <div class="action-text">Bạn đang là một phần của đội này</div>
                    @if($team->captain_id !== Auth::id())
                    <form action="{{ route('teams.leave', $team->id) }}" method="POST"
                          onsubmit="return confirm('Bạn có chắc muốn rời khỏi đội?')">
                        @csrf
                        <button type="submit" class="btn-neon btn-neon-danger" style="width: 100%; justify-content: center;">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Rời khỏi đội</span>
                        </button>
                    </form>
                    @else
                    <p style="color: #64748b; font-size: 0.85rem; margin-top: 0.5rem;">
                        <i class="fas fa-info-circle"></i> Đội trưởng không thể rời đội
                    </p>
                    @endif
                    @endif
                </div>
                @endif
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- Add Member Modal -->
<div class="modal-overlay" id="addMemberModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <h5 class="modal-title-custom"><i class="fas fa-user-plus me-2"></i>Thêm thành viên</h5>
            <button type="button" class="btn-icon" onclick="closeAddMemberModal()" style="color: #94a3b8;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body-custom">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="searchUserInput" placeholder="Tìm kiếm người dùng..." oninput="searchUsers()">
            </div>
            <div class="user-list" id="userSearchResults">
                <div class="chat-empty" style="padding: 2rem;">
                    <i class="fas fa-search"></i>
                    <p>Nhập tên hoặc email để tìm kiếm</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Remove Member Confirmation Modal -->
<div class="modal-overlay" id="removeMemberModal">
    <div class="modal-content-custom" style="max-width: 400px;">
        <div class="modal-header-custom">
            <h5 class="modal-title-custom"><i class="fas fa-user-minus me-2"></i>Xóa thành viên</h5>
            <button type="button" class="btn-icon" onclick="closeRemoveMemberModal()" style="color: #94a3b8;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body-custom">
            <p>Bạn có chắc chắn muốn xóa <strong id="removeMemberName" style="color: #00E5FF;"></strong> khỏi đội?</p>
        </div>
        <div class="modal-footer-custom">
            <button type="button" class="btn-neon" onclick="closeRemoveMemberModal()">Hủy</button>
            <button type="button" class="btn-neon btn-neon-danger" id="confirmRemoveBtn">Xóa</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const teamId = {{ $team->id }};
let removeMemberId = null;

// Add Member Modal
function openAddMemberModal() {
    document.getElementById('addMemberModal').classList.add('active');
    document.getElementById('searchUserInput').value = '';
    document.getElementById('userSearchResults').innerHTML = `
        <div class="chat-empty" style="padding: 2rem;">
            <i class="fas fa-search"></i>
            <p>Nhập tên hoặc email để tìm kiếm</p>
        </div>
    `;
}

function closeAddMemberModal() {
    document.getElementById('addMemberModal').classList.remove('active');
}

let searchTimeout;
function searchUsers() {
    clearTimeout(searchTimeout);
    const query = document.getElementById('searchUserInput').value.trim();
    
    if (query.length < 2) {
        document.getElementById('userSearchResults').innerHTML = `
            <div class="chat-empty" style="padding: 2rem;">
                <i class="fas fa-search"></i>
                <p>Nhập ít nhất 2 ký tự để tìm kiếm</p>
            </div>
        `;
        return;
    }
    
    searchTimeout = setTimeout(() => {
        fetch(`{{ url('teams') }}/${teamId}/search-users?q=${encodeURIComponent(query)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.users && data.users.length > 0) {
                let html = '';
                data.users.forEach(user => {
                    const avatar = user.avatar 
                        ? `<img src="${user.avatar}" class="user-item-avatar">`
                        : `<div class="user-item-avatar-fallback">${user.name.charAt(0).toUpperCase()}</div>`;
                    html += `
                        <div class="user-item" onclick="addMember(${user.id})">
                            <div class="user-item-info">
                                ${avatar}
                                <div>
                                    <div class="user-item-name">${user.name}</div>
                                    <div class="user-item-email">${user.email}</div>
                                </div>
                            </div>
                            <button class="btn-neon btn-neon-sm btn-neon-success">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    `;
                });
                document.getElementById('userSearchResults').innerHTML = html;
            } else {
                document.getElementById('userSearchResults').innerHTML = `
                    <div class="chat-empty" style="padding: 2rem;">
                        <i class="fas fa-user-slash"></i>
                        <p>Không tìm thấy người dùng</p>
                    </div>
                `;
            }
        });
    }, 300);
}

function addMember(userId) {
    fetch(`{{ url('teams') }}/${teamId}/add-member`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    });
}

// Remove Member Modal
function removeMember(memberId, memberName) {
    removeMemberId = memberId;
    document.getElementById('removeMemberName').textContent = memberName;
    document.getElementById('removeMemberModal').classList.add('active');
}

function closeRemoveMemberModal() {
    document.getElementById('removeMemberModal').classList.remove('active');
    removeMemberId = null;
}

document.getElementById('confirmRemoveBtn').addEventListener('click', function() {
    if (!removeMemberId) return;
    
    fetch(`{{ url('teams') }}/${teamId}/remove-member`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ user_id: removeMemberId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    });
    
    closeRemoveMemberModal();
});

// Chat Functions
function sendMessage() {
    const input = document.getElementById('chatInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    fetch(`{{ url('teams') }}/${teamId}/chat`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message: message })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            // Message will be added via realtime, but load as fallback
            if (typeof Echo === 'undefined') {
                loadMessages();
            }
        }
    });
}

function loadMessages() {
    fetch(`{{ url('teams') }}/${teamId}/chat`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        const container = document.getElementById('chatMessages');
        if (data.messages && data.messages.length > 0) {
            let html = '';
            data.messages.forEach(msg => {
                html += renderMessage(msg);
            });
            container.innerHTML = html;
            container.scrollTop = container.scrollHeight;
        }
    });
}

function renderMessage(msg) {
    const avatar = msg.user.avatar 
        ? `<img src="${msg.user.avatar}" class="chat-message-avatar">`
        : `<div class="chat-message-avatar-fallback">${msg.user.name.charAt(0).toUpperCase()}</div>`;
    return `
        <div class="chat-message" data-id="${msg.id}">
            ${avatar}
            <div class="chat-message-content">
                <div class="chat-message-header">
                    <span class="chat-message-name">${msg.user.name}</span>
                    <span class="chat-message-time">${msg.time}</span>
                </div>
                <div class="chat-message-text">${msg.message}</div>
            </div>
        </div>
    `;
}

function appendMessage(msg) {
    const container = document.getElementById('chatMessages');
    // Remove empty state if exists
    const emptyState = container.querySelector('.chat-empty');
    if (emptyState) {
        emptyState.remove();
    }
    // Check if message already exists
    if (container.querySelector(`[data-id="${msg.id}"]`)) {
        return;
    }
    container.insertAdjacentHTML('beforeend', renderMessage(msg));
    container.scrollTop = container.scrollHeight;
}

// Enter key to send message
document.getElementById('chatInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') sendMessage();
});

// Load messages and setup realtime on page load
@auth
@if($team->members->where('id', Auth::id())->first())
document.addEventListener('DOMContentLoaded', function() {
    loadMessages();
    
    // Setup Laravel Echo for realtime
    if (typeof Echo !== 'undefined') {
        Echo.private(`team.${teamId}`)
            .listen('.message.sent', (e) => {
                appendMessage(e.message);
            })
            .listen('.member.changed', (e) => {
                // Reload page when member is added or removed
                location.reload();
            });
    } else {
        // Fallback: poll every 10 seconds if Echo not available
        setInterval(loadMessages, 10000);
    }
});
@endif
@endauth

// Close modals on overlay click
document.getElementById('addMemberModal').addEventListener('click', function(e) {
    if (e.target === this) closeAddMemberModal();
});
document.getElementById('removeMemberModal').addEventListener('click', function(e) {
    if (e.target === this) closeRemoveMemberModal();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddMemberModal();
        closeRemoveMemberModal();
    }
});
</script>
@endpush
