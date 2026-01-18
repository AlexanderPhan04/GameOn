@extends('layouts.app')

@section('title', 'Theo dõi')

@push('styles')
<style>
    .follow-page {
        background: linear-gradient(180deg, #000814 0%, #0d1b2a 100%);
        min-height: 100vh;
        padding: 2rem 1rem;
    }

    .follow-container {
        max-width: 1100px;
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-title i {
        color: #00E5FF;
    }

    /* Tabs */
    .follow-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        padding-bottom: 0.5rem;
    }

    .follow-tab {
        padding: 0.75rem 1.5rem;
        background: transparent;
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 12px 12px 0 0;
        color: #94a3b8;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .follow-tab:hover {
        background: rgba(0, 229, 255, 0.1);
        color: #00E5FF;
    }

    .follow-tab.active {
        background: rgba(0, 229, 255, 0.15);
        border-color: rgba(0, 229, 255, 0.4);
        color: #00E5FF;
    }

    .follow-tab .count {
        background: rgba(0, 229, 255, 0.2);
        padding: 0.125rem 0.5rem;
        border-radius: 10px;
        font-size: 0.85rem;
    }

    /* Tab Content */
    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* User Grid */
    .users-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
    }

    /* User Card */
    .user-card {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.12);
        border-radius: 16px;
        padding: 1.25rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .user-card:hover {
        border-color: rgba(0, 229, 255, 0.3);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3), 0 0 20px rgba(0, 229, 255, 0.1);
        transform: translateY(-2px);
    }

    .user-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        border: 2px solid rgba(0, 229, 255, 0.3);
        object-fit: cover;
        flex-shrink: 0;
    }

    .user-info {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        font-size: 1.1rem;
        color: #fff;
        margin: 0 0 0.25rem 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-name a {
        color: inherit;
        text-decoration: none;
    }

    .user-name a:hover {
        color: #00E5FF;
    }

    .user-meta {
        color: #64748b;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .user-meta i {
        color: #00E5FF;
        font-size: 0.75rem;
    }

    /* Follow Button */
    .btn-follow {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 229, 255, 0.4);
        background: rgba(0, 229, 255, 0.1);
        color: #00E5FF;
        display: flex;
        align-items: center;
        gap: 0.375rem;
        flex-shrink: 0;
    }

    .btn-follow:hover {
        background: rgba(0, 229, 255, 0.2);
        transform: scale(1.05);
    }

    .btn-follow.following {
        background: rgba(34, 197, 94, 0.15);
        border-color: rgba(34, 197, 94, 0.4);
        color: #22c55e;
    }

    .btn-follow.following:hover {
        background: rgba(239, 68, 68, 0.15);
        border-color: rgba(239, 68, 68, 0.4);
        color: #f87171;
    }

    /* Suggestions Section */
    .suggestions-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    }

    .suggestions-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .suggestions-title i {
        color: #f59e0b;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        background: rgba(0, 0, 85, 0.2);
        border-radius: 16px;
        border: 1px solid rgba(0, 229, 255, 0.1);
    }

    .empty-icon {
        font-size: 3rem;
        color: #64748b;
        margin-bottom: 1rem;
    }

    .empty-text {
        color: #94a3b8;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .empty-subtext {
        color: #64748b;
        font-size: 0.9rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .follow-tabs {
            flex-wrap: wrap;
        }
        .follow-tab {
            flex: 1;
            justify-content: center;
            font-size: 0.9rem;
            padding: 0.625rem 1rem;
        }
        .users-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="follow-page">
    <div class="follow-container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-user-friends"></i>
                Theo dõi
            </h1>
        </div>

        <!-- Tabs -->
        <div class="follow-tabs">
            <button class="follow-tab active" data-tab="following">
                <i class="fas fa-heart"></i>
                Đang theo dõi
                <span class="count">{{ $following->count() }}</span>
            </button>
            <button class="follow-tab" data-tab="followers">
                <i class="fas fa-users"></i>
                Người theo dõi
                <span class="count">{{ $followers->count() }}</span>
            </button>
            <button class="follow-tab" data-tab="suggestions">
                <i class="fas fa-user-plus"></i>
                Gợi ý
                <span class="count">{{ $suggestions->count() }}</span>
            </button>
        </div>

        <!-- Following Tab -->
        <div id="tab-following" class="tab-content active">
            @if($following->count() > 0)
                <div class="users-grid">
                    @foreach($following as $user)
                        <div class="user-card" id="user-card-{{ $user->id }}">
                            <img src="{{ $user->getDisplayAvatar() }}" alt="{{ $user->name }}" class="user-avatar">
                            <div class="user-info">
                                <h3 class="user-name">
                                    <a href="{{ route('profile.show-user', $user->profile?->id_app ?? $user->id) }}">
                                        {{ $user->profile?->full_name ?: $user->name }}
                                    </a>
                                </h3>
                                <div class="user-meta">
                                    <i class="fas fa-users"></i>
                                    <span>{{ $user->followers_count }} người theo dõi</span>
                                </div>
                            </div>
                            <button class="btn-follow following" onclick="toggleFollow({{ $user->id }}, this)">
                                <i class="fas fa-user-check"></i>
                                <span>Đang theo dõi</span>
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-heart"></i></div>
                    <p class="empty-text">Bạn chưa theo dõi ai</p>
                    <p class="empty-subtext">Khám phá và theo dõi những người dùng khác nhé!</p>
                </div>
            @endif
        </div>

        <!-- Followers Tab -->
        <div id="tab-followers" class="tab-content">
            @if($followers->count() > 0)
                <div class="users-grid">
                    @foreach($followers as $user)
                        <div class="user-card" id="user-card-{{ $user->id }}">
                            <img src="{{ $user->getDisplayAvatar() }}" alt="{{ $user->name }}" class="user-avatar">
                            <div class="user-info">
                                <h3 class="user-name">
                                    <a href="{{ route('profile.show-user', $user->profile?->id_app ?? $user->id) }}">
                                        {{ $user->profile?->full_name ?: $user->name }}
                                    </a>
                                </h3>
                                <div class="user-meta">
                                    <i class="fas fa-users"></i>
                                    <span>{{ $user->followers_count }} người theo dõi</span>
                                </div>
                            </div>
                            @php
                                $isFollowing = auth()->user()->isFollowing($user->id);
                            @endphp
                            <button class="btn-follow {{ $isFollowing ? 'following' : '' }}" onclick="toggleFollow({{ $user->id }}, this)">
                                <i class="fas {{ $isFollowing ? 'fa-user-check' : 'fa-user-plus' }}"></i>
                                <span>{{ $isFollowing ? 'Đang theo dõi' : 'Theo dõi lại' }}</span>
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-users"></i></div>
                    <p class="empty-text">Chưa có ai theo dõi bạn</p>
                    <p class="empty-subtext">Hãy hoạt động nhiều hơn để thu hút người theo dõi!</p>
                </div>
            @endif
        </div>

        <!-- Suggestions Tab -->
        <div id="tab-suggestions" class="tab-content">
            @if($suggestions->count() > 0)
                <div class="suggestions-header">
                    <h2 class="suggestions-title">
                        <i class="fas fa-lightbulb"></i>
                        Những người bạn có thể muốn theo dõi
                    </h2>
                </div>
                <div class="users-grid">
                    @foreach($suggestions as $user)
                        <div class="user-card" id="user-card-{{ $user->id }}">
                            <img src="{{ $user->getDisplayAvatar() }}" alt="{{ $user->name }}" class="user-avatar">
                            <div class="user-info">
                                <h3 class="user-name">
                                    <a href="{{ route('profile.show-user', $user->profile?->id_app ?? $user->id) }}">
                                        {{ $user->profile?->full_name ?: $user->name }}
                                    </a>
                                </h3>
                                <div class="user-meta">
                                    <i class="fas fa-users"></i>
                                    <span>{{ $user->followers_count }} người theo dõi</span>
                                </div>
                            </div>
                            <button class="btn-follow" onclick="toggleFollow({{ $user->id }}, this)">
                                <i class="fas fa-user-plus"></i>
                                <span>Theo dõi</span>
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-check-circle"></i></div>
                    <p class="empty-text">Bạn đã theo dõi tất cả mọi người!</p>
                    <p class="empty-subtext">Hãy quay lại sau để xem có ai mới không nhé.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Tab switching
document.querySelectorAll('.follow-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        // Remove active from all tabs
        document.querySelectorAll('.follow-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

        // Add active to clicked tab
        this.classList.add('active');
        const tabId = this.dataset.tab;
        document.getElementById('tab-' + tabId).classList.add('active');
    });
});

// Toggle follow
function toggleFollow(userId, btn) {
    btn.disabled = true;
    const icon = btn.querySelector('i');
    const text = btn.querySelector('span');
    const originalIcon = icon.className;
    const originalText = text.textContent;

    icon.className = 'fas fa-spinner fa-spin';
    text.textContent = 'Đang xử lý...';

    fetch(`/follow/toggle/${userId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.is_following) {
                btn.classList.add('following');
                icon.className = 'fas fa-user-check';
                text.textContent = 'Đang theo dõi';
            } else {
                btn.classList.remove('following');
                icon.className = 'fas fa-user-plus';
                text.textContent = 'Theo dõi';
            }
            // Update follower count in user meta
            const card = btn.closest('.user-card');
            const metaSpan = card.querySelector('.user-meta span');
            if (metaSpan && data.followers_count !== undefined) {
                metaSpan.textContent = data.followers_count + ' người theo dõi';
            }
        } else {
            alert(data.message || 'Có lỗi xảy ra');
            icon.className = originalIcon;
            text.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
        icon.className = originalIcon;
        text.textContent = originalText;
    })
    .finally(() => {
        btn.disabled = false;
    });
}
</script>
@endpush
@endsection
