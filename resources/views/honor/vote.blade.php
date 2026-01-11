@extends('layouts.app')

@section('title', 'Vote - ' . $honorEvent->name)

@push('styles')
<style>
    .vote-container {
        background: #000814;
        min-height: calc(100vh - 64px);
        padding: 1.5rem;
    }

    /* Header */
    .vote-header {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .vote-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #8b5cf6, #00E5FF, transparent);
    }

    .vote-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 25px rgba(139, 92, 246, 0.3);
    }

    .vote-icon i { font-size: 1.5rem; color: white; }
    .vote-title { font-family: 'Rajdhani', sans-serif; font-size: 1.5rem; font-weight: 700; color: #00E5FF; margin: 0; }
    .vote-subtitle { color: #94a3b8; font-size: 0.9rem; margin: 0.25rem 0 0 0; }
</style>
@endpush

@section('content')
<div class="vote-container">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="vote-header">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('honor.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="vote-icon">
                    <i class="fas fa-vote-yea"></i>
                </div>
                <div>
                    <h1 class="vote-title">{{ $honorEvent->name }}</h1>
                    <p class="vote-subtitle">{{ $honorEvent->description ?? 'Bình chọn cho ' . ucfirst($honorEvent->target_type) . ' yêu thích của bạn' }}</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Chế độ</span>
                    <span class="stat-value">{{ $honorEvent->mode === 'event' ? 'Sự kiện' : 'Tự do' }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-{{ $honorEvent->target_type === 'user' ? 'user' : ($honorEvent->target_type === 'team' ? 'users' : 'gamepad') }}"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Đối tượng</span>
                    <span class="stat-value">{{ ucfirst($honorEvent->target_type) }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-vote-yea"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Tổng vote</span>
                    <span class="stat-value" data-stat="total-votes">{{ $honorEvent->getTotalVotesCount() }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon gold"><i class="fas fa-star"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Trọng số của bạn</span>
                    <span class="stat-value">{{ $honorEvent->getWeightForRole(auth()->user()->user_role) }}x</span>
                </div>
            </div>
        </div>

        <!-- Current Vote Alert -->
        @if($userVote)
        <div class="current-vote-alert">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>Bạn đã vote cho: {{ $userVote->getVotedItemName() }}</strong>
                @if($userVote->comment)
                    <br><small>Bình luận: "{{ $userVote->comment }}"</small>
                @endif
                <br><small>Vote lúc: {{ $userVote->created_at->format('d/m/Y H:i:s') }}</small>
            </div>
        </div>
        @endif

        <!-- Section Title -->
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-list"></i>
                <span>Chọn {{ ucfirst($honorEvent->target_type) }} để vote</span>
            </div>
            <a href="{{ route('honor.results', $honorEvent) }}" class="btn-results">
                <i class="fas fa-chart-bar"></i>
                <span>Xem kết quả</span>
            </a>
        </div>

        <!-- Votable Items Grid -->
        @if($votableItems->count() > 0)
        <div class="vote-grid">
            @foreach($votableItems as $item)
            <div class="vote-card {{ $userVote && $userVote->voted_item_id == $item->id ? 'voted' : '' }}" data-item-id="{{ $item->id }}">
                <div class="vote-card-content">
                    @if($honorEvent->target_type === 'user')
                        <img src="{{ $item->getDisplayAvatar() }}" class="vote-avatar" alt="Avatar">
                        <h3 class="vote-name">{{ $item->display_name ?? $item->name }}</h3>
                        <span class="vote-role">{{ $item->is_verified_gamer ? 'Pro Gamer ✓' : 'Participant' }}</span>
                    @elseif($honorEvent->target_type === 'team')
                        <img src="{{ $item->logo ? asset('storage/' . $item->logo) : asset('images/default-team.png') }}" class="vote-avatar team" alt="Logo">
                        <h3 class="vote-name">{{ $item->name }}</h3>
                        @if($item->description)
                            <p class="vote-desc">{{ Str::limit($item->description, 60) }}</p>
                        @endif
                    @elseif($honorEvent->target_type === 'tournament')
                        <div class="vote-avatar-icon tournament">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3 class="vote-name">{{ $item->name }}</h3>
                        @if($item->prize_pool)
                            <span class="vote-prize">{{ number_format($item->prize_pool) }} VNĐ</span>
                        @endif
                    @elseif($honorEvent->target_type === 'game')
                        <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('images/default-game.png') }}" class="vote-avatar game" alt="Game">
                        <h3 class="vote-name">{{ $item->name }}</h3>
                        @if($item->description)
                            <p class="vote-desc">{{ Str::limit($item->description, 60) }}</p>
                        @endif
                    @endif
                </div>
                <button class="vote-btn" data-item-id="{{ $item->id }}" data-item-name="{{ $honorEvent->target_type === 'user' ? ($item->display_name ?? $item->name) : $item->name }}">
                    @if($userVote && $userVote->voted_item_id == $item->id)
                        <i class="fas fa-check"></i> Đã vote
                    @else
                        <i class="fas fa-vote-yea"></i> Vote
                    @endif
                </button>
            </div>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-inbox"></i></div>
            <h3 class="empty-title">Không có {{ $honorEvent->target_type }} nào để vote</h3>
            <p class="empty-text">Hãy quay lại sau!</p>
        </div>
        @endif
    </div>
</div>

<!-- Vote Modal -->
<div class="vote-modal-overlay" id="voteModal">
    <div class="vote-modal">
        <div class="vote-modal-header">
            <h3><i class="fas fa-vote-yea"></i> Xác nhận vote</h3>
            <button class="modal-close" id="closeModal"><i class="fas fa-times"></i></button>
        </div>
        <div class="vote-modal-body">
            <form id="voteForm">
                @csrf
                <input type="hidden" id="votedItemId" name="voted_item_id">
                
                <div class="modal-field">
                    <label>Bạn đang vote cho:</label>
                    <div id="votedItemName" class="voted-item-display"></div>
                </div>
                
                <div class="modal-field">
                    <label for="comment">Bình luận (tùy chọn)</label>
                    <textarea id="comment" name="comment" rows="3" placeholder="Nhập bình luận của bạn..."></textarea>
                </div>
                
                @if($honorEvent->allow_anonymous)
                <div class="modal-checkbox">
                    <input type="checkbox" id="is_anonymous" name="is_anonymous">
                    <label for="is_anonymous">Vote ẩn danh</label>
                </div>
                @endif
            </form>
        </div>
        <div class="vote-modal-footer">
            <button type="button" class="btn-cancel" id="cancelVote">Hủy</button>
            <button type="button" class="btn-confirm" id="confirmVote">
                <i class="fas fa-check"></i> Xác nhận vote
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-back {
        width: 40px; height: 40px;
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.3);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: #00E5FF;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-back:hover { background: rgba(0, 229, 255, 0.2); color: #fff; }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .stat-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .stat-icon {
        width: 45px; height: 45px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
    }
    .stat-icon.blue { background: rgba(0, 229, 255, 0.15); color: #00E5FF; }
    .stat-icon.purple { background: rgba(139, 92, 246, 0.15); color: #8b5cf6; }
    .stat-icon.green { background: rgba(34, 197, 94, 0.15); color: #22c55e; }
    .stat-icon.gold { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .stat-info { display: flex; flex-direction: column; }
    .stat-label { font-size: 0.75rem; color: #64748b; text-transform: uppercase; }
    .stat-value { font-size: 1.1rem; font-weight: 700; color: #fff; }

    /* Current Vote Alert */
    .current-vote-alert {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        color: #22c55e;
    }
    .current-vote-alert i { font-size: 1.25rem; margin-top: 2px; }
    .current-vote-alert small { color: #86efac; }

    /* Section Header */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    }
    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .section-title i { color: #00E5FF; }
    .section-title span { font-family: 'Rajdhani', sans-serif; font-size: 1.1rem; font-weight: 600; color: #fff; }
    .btn-results {
        display: flex; align-items: center; gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.3);
        border-radius: 8px;
        color: #00E5FF;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-results:hover { background: rgba(0, 229, 255, 0.2); color: #fff; }
</style>
@endpush

@push('styles')
<style>
    /* Vote Grid */
    .vote-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.25rem;
    }
    .vote-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .vote-card:hover {
        border-color: rgba(139, 92, 246, 0.4);
        transform: translateY(-4px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3), 0 0 20px rgba(139, 92, 246, 0.1);
    }
    .vote-card.voted {
        border-color: rgba(34, 197, 94, 0.5);
        box-shadow: 0 0 20px rgba(34, 197, 94, 0.15);
    }
    .vote-card-content {
        padding: 1.5rem;
        text-align: center;
    }
    .vote-avatar {
        width: 80px; height: 80px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 1rem;
        border: 3px solid rgba(139, 92, 246, 0.3);
    }
    .vote-avatar.team, .vote-avatar.game {
        border-radius: 12px;
    }
    .vote-avatar-icon {
        width: 80px; height: 80px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2rem;
    }
    .vote-avatar-icon.tournament {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    .vote-name {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 0.25rem 0;
    }
    .vote-role {
        font-size: 0.8rem;
        color: #8b5cf6;
    }
    .vote-desc {
        font-size: 0.8rem;
        color: #64748b;
        margin: 0.5rem 0 0 0;
        line-height: 1.4;
    }
    .vote-prize {
        font-size: 0.85rem;
        color: #22c55e;
        font-weight: 600;
    }
    .vote-btn {
        width: 100%;
        padding: 0.85rem;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .vote-btn:hover {
        background: linear-gradient(135deg, #8b5cf6, #a78bfa);
    }
    .vote-card.voted .vote-btn {
        background: linear-gradient(135deg, #22c55e, #16a34a);
    }
</style>
@endpush

@push('styles')
<style>
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
    }
    .empty-icon {
        width: 80px; height: 80px;
        background: rgba(100, 116, 139, 0.1);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.5rem;
    }
    .empty-icon i { font-size: 2rem; color: #64748b; }
    .empty-title { color: #fff; font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; }
    .empty-text { color: #64748b; }

    /* Modal */
    .vote-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 1rem;
    }
    .vote-modal-overlay.active { display: flex; }
    .vote-modal {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(139, 92, 246, 0.3);
        border-radius: 20px;
        width: 100%;
        max-width: 450px;
        overflow: hidden;
    }
    .vote-modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(139, 92, 246, 0.2);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .vote-modal-header h3 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #8b5cf6;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .modal-close {
        width: 36px; height: 36px;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 8px;
        color: #ef4444;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .modal-close:hover { background: rgba(239, 68, 68, 0.2); }
    .vote-modal-body { padding: 1.5rem; }
    .modal-field { margin-bottom: 1.25rem; }
    .modal-field label {
        display: block;
        font-size: 0.85rem;
        color: #94a3b8;
        margin-bottom: 0.5rem;
    }
    .voted-item-display {
        padding: 0.75rem 1rem;
        background: rgba(139, 92, 246, 0.1);
        border: 1px solid rgba(139, 92, 246, 0.3);
        border-radius: 10px;
        color: #fff;
        font-weight: 600;
    }
    .modal-field textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(0, 0, 20, 0.5);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        color: #fff;
        font-size: 0.9rem;
        resize: none;
        box-sizing: border-box;
    }
    .modal-field textarea:focus {
        outline: none;
        border-color: rgba(139, 92, 246, 0.5);
    }
    .modal-checkbox {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .modal-checkbox input[type="checkbox"] {
        width: 18px; height: 18px;
        accent-color: #8b5cf6;
    }
    .modal-checkbox label { color: #94a3b8; font-size: 0.9rem; cursor: pointer; }
    .vote-modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(139, 92, 246, 0.2);
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }
    .btn-cancel {
        padding: 0.65rem 1.25rem;
        background: rgba(100, 116, 139, 0.2);
        border: 1px solid rgba(100, 116, 139, 0.3);
        border-radius: 10px;
        color: #94a3b8;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-cancel:hover { background: rgba(100, 116, 139, 0.3); color: #fff; }
    .btn-confirm {
        padding: 0.65rem 1.5rem;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-confirm:hover { box-shadow: 0 0 20px rgba(139, 92, 246, 0.4); }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .vote-grid { grid-template-columns: repeat(2, 1fr); }
        .section-header { flex-direction: column; gap: 1rem; align-items: flex-start; }
    }
    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
        .vote-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const voteButtons = document.querySelectorAll('.vote-btn');
    const modal = document.getElementById('voteModal');
    const closeModal = document.getElementById('closeModal');
    const cancelVote = document.getElementById('cancelVote');
    const confirmVote = document.getElementById('confirmVote');
    const voteForm = document.getElementById('voteForm');
    
    // Open modal
    voteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            const itemName = this.dataset.itemName;
            
            document.getElementById('votedItemId').value = itemId;
            document.getElementById('votedItemName').textContent = itemName;
            modal.classList.add('active');
        });
    });
    
    // Close modal
    function hideModal() {
        modal.classList.remove('active');
        voteForm.reset();
    }
    closeModal.addEventListener('click', hideModal);
    cancelVote.addEventListener('click', hideModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) hideModal();
    });
    
    // Submit vote
    confirmVote.addEventListener('click', function() {
        const formData = new FormData(voteForm);
        confirmVote.disabled = true;
        confirmVote.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        
        fetch(`{{ route('honor.vote', $honorEvent) }}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                hideModal();
                showNotification('success', data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('error', data.message);
                confirmVote.disabled = false;
                confirmVote.innerHTML = '<i class="fas fa-check"></i> Xác nhận vote';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Có lỗi xảy ra khi vote!');
            confirmVote.disabled = false;
            confirmVote.innerHTML = '<i class="fas fa-check"></i> Xác nhận vote';
        });
    });

    // Realtime listeners
    if (typeof Echo !== 'undefined') {
        const eventId = {{ $honorEvent->id }};
        
        Echo.channel('honor.event.' + eventId)
            .listen('.vote.cast', (e) => {
                updateRealtimeStats(e.stats);
                showVoteNotification(e.vote);
            })
            .listen('.votes.reset', (e) => {
                showNotification('warning', 'Votes đã được reset!');
                setTimeout(() => location.reload(), 2000);
            });

        Echo.channel('honor')
            .listen('.event.updated', (e) => {
                if (e.event.id === eventId && !e.event.is_active) {
                    showNotification('warning', 'Đợt vote đã bị tạm dừng!');
                }
            });
    }

    function updateRealtimeStats(stats) {
        const el = document.querySelector('[data-stat="total-votes"]');
        if (el) {
            el.textContent = stats.event_total_votes;
            el.style.color = '#22c55e';
            setTimeout(() => el.style.color = '#fff', 1000);
        }
    }

    function showVoteNotification(vote) {
        const name = vote.is_anonymous ? 'Ai đó' : vote.voter_name;
        showNotification('info', `${name} vừa vote!`);
    }

    function showNotification(type, message) {
        const colors = { success: '#22c55e', error: '#ef4444', warning: '#f59e0b', info: '#00E5FF' };
        const icons = { success: 'check-circle', error: 'exclamation-circle', warning: 'exclamation-triangle', info: 'info-circle' };
        
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed; top: 20px; right: 20px; z-index: 10000;
            padding: 1rem 1.5rem; border-radius: 12px; min-width: 280px;
            background: rgba(13, 27, 42, 0.95); border: 1px solid ${colors[type]}40;
            color: ${colors[type]}; display: flex; align-items: center; gap: 12px;
            animation: slideIn 0.3s ease;
        `;
        toast.innerHTML = `<i class="fas fa-${icons[type]}"></i><span>${message}</span>`;
        document.body.appendChild(toast);
        setTimeout(() => { toast.style.animation = 'slideOut 0.3s ease'; setTimeout(() => toast.remove(), 300); }, 4000);
    }
});
</script>
<style>
@keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
@keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
</style>
@endpush
