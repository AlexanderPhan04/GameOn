@extends('layouts.app')

@section('title', 'Hồ sơ - ' . ($user->full_name ?: $user->name))

@push('styles')
<style>
    .profile-page {
        background: linear-gradient(180deg, #000814 0%, #0d1b2a 100%);
        min-height: 100vh;
    }
    
    /* Hero Section */
    .profile-hero {
        position: relative;
        padding: 3rem 1rem;
        overflow: hidden;
    }
    .hero-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #000055 0%, #0d1b2a 50%, #000814 100%);
    }
    .hero-particles {
        position: absolute;
        inset: 0;
        background-image: 
            radial-gradient(circle at 20% 30%, rgba(0, 229, 255, 0.1) 1px, transparent 1px),
            radial-gradient(circle at 80% 70%, rgba(0, 229, 255, 0.08) 1px, transparent 1px),
            radial-gradient(circle at 50% 50%, rgba(0, 229, 255, 0.05) 2px, transparent 2px);
        background-size: 60px 60px, 80px 80px, 100px 100px;
        animation: float 25s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(1deg); }
    }
    .hero-glow {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 600px;
        height: 400px;
        background: radial-gradient(ellipse, rgba(0, 229, 255, 0.15) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-content {
        position: relative;
        z-index: 10;
        max-width: 1100px;
        margin: 0 auto;
    }
    .hero-main {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 2rem;
    }
    .hero-left {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    /* Avatar */
    .avatar-wrapper {
        position: relative;
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 3px solid rgba(0, 229, 255, 0.4);
        box-shadow: 0 0 30px rgba(0, 229, 255, 0.3), 0 8px 32px rgba(0, 0, 0, 0.4);
        object-fit: cover;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .avatar-initials {
        font-family: 'Rajdhani', sans-serif;
        font-size: 2.5rem;
        font-weight: 700;
        color: #fff;
    }
    .avatar-status {
        position: absolute;
        bottom: 8px;
        right: 8px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 3px solid #000814;
    }
    .avatar-status.online { background: #10b981; box-shadow: 0 0 10px #10b981; }
    .avatar-status.offline { background: #64748b; }
    
    /* Profile Info */
    .profile-info {
        color: #fff;
    }
    .profile-name {
        font-family: 'Rajdhani', sans-serif;
        font-size: 2.25rem;
        font-weight: 700;
        margin: 0 0 0.25rem 0;
        text-shadow: 0 0 20px rgba(0, 229, 255, 0.3);
    }
    .profile-id {
        color: #94a3b8;
        font-family: 'Inter', sans-serif;
        font-size: 1rem;
        margin: 0 0 0.75rem 0;
    }
    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .role-badge.super-admin {
        background: linear-gradient(135deg, rgba(255, 215, 0, 0.2) 0%, rgba(255, 193, 7, 0.1) 100%);
        color: #ffd700;
        border: 1px solid rgba(255, 215, 0, 0.3);
        box-shadow: 0 0 15px rgba(255, 215, 0, 0.2);
    }
    .role-badge.admin {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(220, 38, 38, 0.1) 100%);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    .role-badge.participant {
        background: linear-gradient(135deg, rgba(0, 229, 255, 0.2) 0%, rgba(0, 153, 204, 0.1) 100%);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.3);
    }
    .role-badge.user {
        background: linear-gradient(135deg, rgba(148, 163, 184, 0.2) 0%, rgba(100, 116, 139, 0.1) 100%);
        color: #94a3b8;
        border: 1px solid rgba(148, 163, 184, 0.3);
    }
    
    /* Action Buttons */
    .hero-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .btn-primary {
        background: linear-gradient(135deg, #000055 0%, rgba(0, 229, 255, 0.2) 100%);
        border: 1px solid rgba(0, 229, 255, 0.4);
        color: #00E5FF;
        box-shadow: 0 4px 15px rgba(0, 229, 255, 0.2);
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, rgba(0, 229, 255, 0.15) 0%, #000055 100%);
        box-shadow: 0 6px 25px rgba(0, 229, 255, 0.35);
        transform: translateY(-2px);
        color: #00E5FF;
    }
    .btn-outline {
        background: transparent;
        border: 1px solid rgba(148, 163, 184, 0.3);
        color: #94a3b8;
    }
    .btn-outline:hover {
        border-color: rgba(0, 229, 255, 0.4);
        color: #00E5FF;
        background: rgba(0, 229, 255, 0.05);
    }

    /* Main Content */
    .profile-content {
        max-width: 1100px;
        margin: 0 auto;
        padding: 2rem 1rem;
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 1.5rem;
    }
    @media (max-width: 900px) {
        .profile-content {
            grid-template-columns: 1fr;
        }
    }
    
    /* Cards */
    .profile-card {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.12);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
        margin-bottom: 1.5rem;
    }
    .card-header {
        padding: 1rem 1.25rem;
        background: linear-gradient(135deg, rgba(0, 229, 255, 0.08) 0%, rgba(0, 0, 85, 0.2) 100%);
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .card-header-icon {
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
    .card-header-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
    }
    .card-body {
        padding: 1.25rem;
    }
    
    /* Info Items */
    .info-item {
        padding: 1rem 0;
        border-bottom: 1px solid rgba(0, 229, 255, 0.08);
    }
    .info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .info-item:first-child {
        padding-top: 0;
    }
    .info-label {
        font-size: 0.8rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.375rem;
        font-weight: 600;
    }
    .info-value {
        color: #e2e8f0;
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
    }
    
    /* Team Item */
    .team-item {
        padding: 1rem;
        background: rgba(0, 0, 85, 0.2);
        border: 1px solid rgba(0, 229, 255, 0.1);
        border-radius: 12px;
        margin-bottom: 0.75rem;
    }
    .team-item:last-child {
        margin-bottom: 0;
    }
    .team-name {
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        color: #fff;
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }
    .team-role {
        color: #00E5FF;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .team-joined {
        color: #64748b;
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    @media (max-width: 600px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
    .stat-card {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.12);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        border-color: rgba(0, 229, 255, 0.3);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3), 0 0 20px rgba(0, 229, 255, 0.1);
        transform: translateY(-3px);
    }
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #000055 0%, rgba(0, 229, 255, 0.2) 100%);
        border: 1px solid rgba(0, 229, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: #00E5FF;
        font-size: 1.25rem;
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.2);
    }
    .stat-value {
        font-family: 'Rajdhani', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.25rem;
    }
    .stat-label {
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2.5rem 1.5rem;
    }
    .empty-icon {
        font-size: 3rem;
        color: #64748b;
        opacity: 0.5;
        margin-bottom: 1rem;
    }
    .empty-text {
        color: #64748b;
        font-family: 'Inter', sans-serif;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-main {
            flex-direction: column;
            text-align: center;
        }
        .hero-left {
            flex-direction: column;
        }
        .profile-name {
            font-size: 1.75rem;
        }
        .hero-actions {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-page">
    <!-- Hero Section -->
    <div class="profile-hero">
        <div class="hero-bg"></div>
        <div class="hero-particles"></div>
        <div class="hero-glow"></div>
        
        <div class="hero-content">
            <div class="hero-main">
                <div class="hero-left">
                    <!-- Avatar -->
                    <div class="avatar-wrapper">
                        @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="Avatar" class="profile-avatar">
                        @else
                        <div class="profile-avatar">
                            <span class="avatar-initials">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                        </div>
                        @endif
                        <div class="avatar-status {{ $user->online_status === 'online' ? 'online' : 'offline' }}"></div>
                    </div>
                    
                    <!-- Info -->
                    <div class="profile-info">
                        <h1 class="profile-name">{{ $user->full_name ?: $user->name }}</h1>
                        <p class="profile-id">
                            @if($user->profile?->id_app)
                                {{ $user->profile->id_app }}
                            @else
                                {{ 'APP' . str_pad($user->id, 6, '0', STR_PAD_LEFT) }}
                            @endif
                        </p>
                        
                        <!-- Role Badge -->
                        @if($user->user_role === 'super_admin')
                        <div class="role-badge super-admin">
                            <i class="fas fa-crown"></i> Super Admin
                        </div>
                        @elseif($user->user_role === 'admin')
                        <div class="role-badge admin">
                            <i class="fas fa-shield-alt"></i> Admin
                        </div>
                        @elseif($user->user_role === 'participant')
                        <div class="role-badge participant">
                            <i class="fas fa-gamepad"></i> Participant
                        </div>
                        @else
                        <div class="role-badge user">
                            <i class="fas fa-user"></i> User
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="hero-actions">
                    <a href="#" class="btn-action btn-primary" data-user-id="{{ $user->id }}" onclick="startChatWithUser({{ $user->id }}); return false;">
                        <i class="fas fa-comment"></i> Nhắn tin
                    </a>
                    <button class="btn-action btn-outline" onclick="toggleFollow()">
                        <i class="fas fa-user-plus"></i> Theo dõi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="profile-content">
        <!-- Left Column -->
        <div class="profile-sidebar">
            <!-- Personal Info -->
            <div class="profile-card">
                <div class="card-header">
                    <div class="card-header-icon"><i class="fas fa-info-circle"></i></div>
                    <span class="card-header-title">Thông tin cá nhân</span>
                </div>
                <div class="card-body">
                    @if($user->profile?->bio)
                    <div class="info-item">
                        <div class="info-label">Giới thiệu</div>
                        <div class="info-value">{{ $user->profile->bio }}</div>
                    </div>
                    @endif
                    
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $user->email }}</div>
                    </div>
                    
                    @if($user->profile?->phone)
                    <div class="info-item">
                        <div class="info-label">Số điện thoại</div>
                        <div class="info-value">{{ $user->profile->phone }}</div>
                    </div>
                    @endif
                    
                    @if($user->profile?->country)
                    <div class="info-item">
                        <div class="info-label">Quốc gia</div>
                        <div class="info-value">{{ $user->profile->country }}</div>
                    </div>
                    @endif
                    
                    @if($user->profile?->date_of_birth)
                    <div class="info-item">
                        <div class="info-label">Ngày sinh</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($user->profile->date_of_birth)->format('d/m/Y') }}</div>
                    </div>
                    @endif
                    
                    <div class="info-item">
                        <div class="info-label">Tham gia</div>
                        <div class="info-value">{{ $user->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Teams -->
            @if($user->teams && $user->teams->count() > 0)
            <div class="profile-card">
                <div class="card-header">
                    <div class="card-header-icon"><i class="fas fa-users"></i></div>
                    <span class="card-header-title">Đội tham gia</span>
                </div>
                <div class="card-body">
                    @foreach($user->teams as $team)
                    <div class="team-item">
                        <div class="team-name">{{ $team->name }}</div>
                        <div class="team-role">{{ ucfirst($team->pivot->role ?? 'Member') }}</div>
                        @if($team->pivot->joined_at)
                        <div class="team-joined">Tham gia: {{ \Carbon\Carbon::parse($team->pivot->joined_at)->format('d/m/Y') }}</div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="profile-main">
            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-trophy"></i></div>
                    <div class="stat-value">0</div>
                    <div class="stat-label">Giải thắng</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-gamepad"></i></div>
                    <div class="stat-value">0</div>
                    <div class="stat-label">Game chơi</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-value">{{ $user->teams ? $user->teams->count() : 0 }}</div>
                    <div class="stat-label">Đội tham gia</div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="profile-card">
                <div class="card-header">
                    <div class="card-header-icon"><i class="fas fa-clock"></i></div>
                    <span class="card-header-title">Hoạt động gần đây</span>
                </div>
                <div class="card-body">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-history"></i></div>
                        <p class="empty-text">Chưa có hoạt động nào</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleFollow() {
    alert('Tính năng theo dõi sẽ được triển khai sau!');
}

function startChatWithUser(userId) {
    if (!userId) return;
    
    const chatBtn = document.querySelector('[data-user-id]');
    let originalContent = '';
    if (chatBtn) {
        originalContent = chatBtn.innerHTML;
        chatBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tạo...';
        chatBtn.style.pointerEvents = 'none';
    }
    
    fetch('{{ route("chat.start") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.redirect_url) {
            window.location.href = data.redirect_url;
        } else {
            if (chatBtn) {
                chatBtn.innerHTML = originalContent;
                chatBtn.style.pointerEvents = '';
            }
            alert('Không thể tạo cuộc trò chuyện. Vui lòng thử lại.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (chatBtn) {
            chatBtn.innerHTML = originalContent;
            chatBtn.style.pointerEvents = '';
        }
        alert('Có lỗi xảy ra khi tạo cuộc trò chuyện.');
    });
}
</script>
@endpush
@endsection
