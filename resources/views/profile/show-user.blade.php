@extends('layouts.app')

@section('title', 'Hồ sơ - ' . ($user->full_name ?: $user->name))

@section('content')
<div class="modern-profile-container">
    <!-- Hero Section -->
    <div class="profile-hero">
        <div class="hero-background">
            <div class="animated-particles"></div>
            <div class="hero-overlay"></div>
        </div>
        
        <div class="container">
            <div class="hero-content">
                <!-- Profile Avatar & Basic Info -->
                <div class="profile-avatar-section">
                    <div class="avatar-wrapper">
                        @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="Avatar" class="profile-avatar-img">
                        @else
                        <div class="profile-avatar-placeholder">
                            <span class="avatar-initials">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                        </div>
                        @endif
                        <div class="avatar-status online"></div>
                    </div>
                    
                    <div class="profile-main-info">
                        <h1 class="profile-name">{{ $user->full_name ?: $user->name }}</h1>
                        @if($user->id_app)
                        <p class="profile-username">{{ $user->id_app }}</p>
                        @else
                        <p class="profile-username">{{ 'APP' . str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</p>
                        @endif
                        
                        <!-- Role Badge -->
                        <div class="role-badge-wrapper">
                            @if($user->user_role === 'super_admin')
                            <div class="role-badge super-admin">
                                <i class="fas fa-crown"></i>
                                <span>Super Administrator</span>
                            </div>
                            @elseif($user->user_role === 'admin')
                            <div class="role-badge admin">
                                <i class="fas fa-shield-alt"></i>
                                <span>Administrator</span>
                            </div>
                            @elseif($user->user_role === 'manager')
                            <div class="role-badge manager">
                                <i class="fas fa-user-tie"></i>
                                <span>Manager</span>
                            </div>
                            @elseif($user->user_role === 'player')
                            <div class="role-badge player">
                                <i class="fas fa-gamepad"></i>
                                <span>Player</span>
                            </div>
                            @else
                            <div class="role-badge user">
                                <i class="fas fa-user"></i>
                                <span>User</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="profile-actions">
                    <a href="{{ route('chat.start') }}" class="btn btn-primary btn-lg" data-user-id="{{ $user->id }}">
                        <i class="fas fa-comment"></i>
                        <span>Nhắn tin</span>
                    </a>
                    <button class="btn btn-outline-primary btn-lg" onclick="toggleFollow()">
                        <i class="fas fa-user-plus"></i>
                        <span>Theo dõi</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <!-- Left Column - Profile Info -->
            <div class="col-lg-4">
                <div class="profile-card">
                    <div class="card-header">
                        <h3><i class="fas fa-info-circle"></i> Thông tin cá nhân</h3>
                    </div>
                    <div class="card-body">
                        @if($user->bio)
                        <div class="info-item">
                            <label>Giới thiệu</label>
                            <p>{{ $user->bio }}</p>
                        </div>
                        @endif
                        
                        @if($user->email)
                        <div class="info-item">
                            <label>Email</label>
                            <p>{{ $user->email }}</p>
                        </div>
                        @endif
                        
                        @if($user->phone)
                        <div class="info-item">
                            <label>Số điện thoại</label>
                            <p>{{ $user->phone }}</p>
                        </div>
                        @endif
                        
                        @if($user->country)
                        <div class="info-item">
                            <label>Quốc gia</label>
                            <p>{{ $user->country }}</p>
                        </div>
                        @endif
                        
                        @if($user->date_of_birth)
                        <div class="info-item">
                            <label>Ngày sinh</label>
                            <p>{{ \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') }}</p>
                        </div>
                        @endif
                        
                        <div class="info-item">
                            <label>Tham gia</label>
                            <p>{{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Teams Section -->
                @if($user->teams && $user->teams->count() > 0)
                <div class="profile-card">
                    <div class="card-header">
                        <h3><i class="fas fa-users"></i> Đội tham gia</h3>
                    </div>
                    <div class="card-body">
                        @foreach($user->teams as $team)
                        <div class="team-item">
                            <div class="team-info">
                                <h4>{{ $team->name }}</h4>
                                <p class="team-role">{{ ucfirst($team->pivot->role) }}</p>
                                <p class="team-joined">Tham gia: {{ \Carbon\Carbon::parse($team->pivot->joined_at)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Right Column - Activity & Stats -->
            <div class="col-lg-8">
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="stat-content">
                                <h3>0</h3>
                                <p>Giải thắng</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-gamepad"></i>
                            </div>
                            <div class="stat-content">
                                <h3>0</h3>
                                <p>Game chơi</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ $user->teams ? $user->teams->count() : 0 }}</h3>
                                <p>Đội tham gia</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="profile-card">
                    <div class="card-header">
                        <h3><i class="fas fa-clock"></i> Hoạt động gần đây</h3>
                    </div>
                    <div class="card-body">
                        <div class="empty-state">
                            <i class="fas fa-history"></i>
                            <p>Chưa có hoạt động nào</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.modern-profile-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.profile-hero {
    position: relative;
    padding: 60px 0;
    color: white;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.animated-particles {
    position: absolute;
    width: 100%;
    height: 100%;
    background-image: 
        radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 1px, transparent 1px),
        radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 1px, transparent 1px),
        radial-gradient(circle at 40% 80%, rgba(255,255,255,0.1) 1px, transparent 1px);
    background-size: 50px 50px, 80px 80px, 60px 60px;
    animation: float 20s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.2);
}

.hero-content {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 30px;
}

.profile-avatar-section {
    display: flex;
    align-items: center;
    gap: 30px;
}

.avatar-wrapper {
    position: relative;
}

.profile-avatar-img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(255,255,255,0.3);
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
}

.profile-avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 4px solid rgba(255,255,255,0.3);
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
}

.avatar-initials {
    font-size: 36px;
    font-weight: bold;
    color: white;
}

.avatar-status {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 3px solid white;
}

.avatar-status.online {
    background: #10b981;
}

.profile-main-info h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 10px 0;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.profile-username {
    font-size: 1.2rem;
    opacity: 0.9;
    margin: 0 0 20px 0;
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    backdrop-filter: blur(10px);
}

.role-badge.super-admin {
    background: rgba(255, 215, 0, 0.2);
    color: #ffd700;
    border: 1px solid rgba(255, 215, 0, 0.3);
}

.role-badge.admin {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.role-badge.manager {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.role-badge.player {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.role-badge.user {
    background: rgba(156, 163, 175, 0.2);
    color: #9ca3af;
    border: 1px solid rgba(156, 163, 175, 0.3);
}

.profile-actions {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.profile-actions .btn {
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.profile-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.profile-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 20px 25px;
    border-bottom: 1px solid #e2e8f0;
}

.card-header h3 {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-body {
    padding: 25px;
}

.info-item {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f1f5f9;
}

.info-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.info-item label {
    display: block;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 5px;
    font-size: 0.9rem;
}

.info-item p {
    margin: 0;
    color: #1e293b;
    font-size: 1rem;
}

.team-item {
    padding: 15px;
    background: #f8fafc;
    border-radius: 12px;
    margin-bottom: 15px;
}

.team-item:last-child {
    margin-bottom: 0;
}

.team-info h4 {
    margin: 0 0 5px 0;
    color: #1e293b;
    font-size: 1.1rem;
}

.team-role {
    color: #667eea;
    font-weight: 500;
    margin: 0 0 5px 0;
    font-size: 0.9rem;
}

.team-joined {
    color: #64748b;
    margin: 0;
    font-size: 0.85rem;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    color: white;
    font-size: 24px;
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 5px 0;
}

.stat-content p {
    color: #64748b;
    margin: 0;
    font-weight: 500;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #64748b;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.5;
}

.empty-state p {
    margin: 0;
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .hero-content {
        flex-direction: column;
        text-align: center;
    }
    
    .profile-avatar-section {
        flex-direction: column;
        text-align: center;
    }
    
    .profile-main-info h1 {
        font-size: 2rem;
    }
    
    .profile-actions {
        justify-content: center;
    }
}
</style>

<script>
function toggleFollow() {
    // TODO: Implement follow functionality
    alert('Tính năng theo dõi sẽ được triển khai sau!');
}

// Handle chat button click
document.addEventListener('DOMContentLoaded', function() {
    const chatBtn = document.querySelector('[data-user-id]');
    if (chatBtn) {
        chatBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.getAttribute('data-user-id');
            startChatWithUser(userId);
        });
    }
});

function startChatWithUser(userId) {
    if (!userId) return;
    
    // Show loading state
    const chatBtn = document.querySelector('[data-user-id]');
    if (chatBtn) {
        const originalContent = chatBtn.innerHTML;
        chatBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Đang tạo cuộc trò chuyện...</span>';
        chatBtn.disabled = true;
    }
    
    // Make AJAX request to start conversation
    fetch('{{ route("chat.start") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            user_id: userId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.redirect_url) {
            // Redirect to chat
            window.location.href = data.redirect_url;
        } else {
            // Show error and restore button
            if (chatBtn) {
                chatBtn.innerHTML = originalContent;
                chatBtn.disabled = false;
            }
            alert('Không thể tạo cuộc trò chuyện. Vui lòng thử lại.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Show error and restore button
        if (chatBtn) {
            chatBtn.innerHTML = originalContent;
            chatBtn.disabled = false;
        }
        alert('Có lỗi xảy ra khi tạo cuộc trò chuyện.');
    });
}
</script>
@endsection
