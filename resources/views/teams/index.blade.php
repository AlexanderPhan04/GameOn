@extends('layouts.app')

@section('title', 'Đội của tôi')

@push('styles')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
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

    {{-- Pending Invitations --}}
    @if(isset($pendingInvitations) && $pendingInvitations->count() > 0)
    <div class="invitations-section" style="margin-bottom: 2rem;">
        <div class="section-title" style="border-color: rgba(245, 158, 11, 0.3);">
            <i class="fas fa-envelope" style="color: #f59e0b;"></i>
            <h2>Lời mời tham gia đội ({{ $pendingInvitations->count() }})</h2>
        </div>
        <div class="teams-grid">
            @foreach($pendingInvitations as $invitation)
            <div class="team-card" data-invitation-id="{{ $invitation->id }}" style="border-color: rgba(245, 158, 11, 0.3);">
                <div class="team-card-image">
                    @if($invitation->team->logo)
                        <img src="{{ $invitation->team->logo }}" alt="{{ $invitation->team->name }}">
                    @else
                        <div class="team-card-image-placeholder">
                            <i class="fas fa-users"></i>
                        </div>
                    @endif
                    <div class="captain-badge" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                        <i class="fas fa-envelope"></i>
                        Lời mời
                    </div>
                </div>
                <div class="team-card-body">
                    <h3 class="team-card-name">{{ $invitation->team->name }}</h3>
                    <p class="team-card-desc">
                        <i class="fas fa-user" style="color: #00E5FF;"></i>
                        Được mời bởi: <strong style="color: #00E5FF;">{{ $invitation->inviter->display_name }}</strong>
                    </p>
                    <div class="team-card-meta">
                        <div class="meta-item">
                            <i class="fas fa-users"></i>
                            {{ $invitation->team->members->count() ?? 0 }}/{{ $invitation->team->max_members ?? '∞' }} thành viên
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            {{ $invitation->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @if($invitation->team->game)
                    <div class="team-card-tags">
                        <span class="tag-game">{{ $invitation->team->game->name }}</span>
                    </div>
                    @endif
                </div>
                <div class="team-card-footer">
                    <button type="button" class="btn-team btn-team-view" style="background: rgba(34, 197, 94, 0.1); border-color: rgba(34, 197, 94, 0.3); color: #22c55e;" onclick="acceptInvitation({{ $invitation->id }})">
                        <i class="fas fa-check"></i> Chấp nhận
                    </button>
                    <button type="button" class="btn-team btn-team-leave" onclick="declineInvitation({{ $invitation->id }})">
                        <i class="fas fa-times"></i> Từ chối
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @php
        $myTeamsAsCaptain = $myTeams->where('captain_id', Auth::id());
        $myTeamsAsMember = $myTeams->where('captain_id', '!=', Auth::id());
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
                @if($team->logo)
                    <img src="{{ $team->logo }}" alt="{{ $team->name }}">
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
                @if($team->logo)
                    <img src="{{ $team->logo }}" alt="{{ $team->name }}">
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
                <form id="leaveTeamForm-{{ $team->id }}" action="{{ route('teams.leave', $team->id) }}" method="POST" style="flex: 1; display: flex;">
                    @csrf
                    <button type="button" class="btn-team btn-team-leave" style="width: 100%;" onclick="showLeaveTeamModal({{ $team->id }})">
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

@push('scripts')
<script>
// Custom Confirm Modal
let customConfirmCallback = null;
let leaveTeamId = null;

function showCustomConfirm(options) {
    // Remove existing modal if any
    const existing = document.getElementById('customConfirmModal');
    if (existing) existing.remove();
    
    customConfirmCallback = options.onConfirm || null;
    
    const modal = document.createElement('div');
    modal.id = 'customConfirmModal';
    modal.className = 'modal-overlay';
    modal.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.8);backdrop-filter:blur(4px);z-index:99999;display:flex;align-items:center;justify-content:center;animation:fadeIn 0.3s ease;';
    modal.innerHTML = `
        <div style="background:linear-gradient(145deg,#0d1b2a,#000022);border:1px solid rgba(0,229,255,0.3);border-radius:20px;width:100%;max-width:420px;overflow:hidden;animation:slideUp 0.3s ease;">
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid rgba(0,229,255,0.1);display:flex;align-items:center;justify-content:space-between;">
                <h5 style="color:#fff;font-size:1.1rem;font-weight:600;display:flex;align-items:center;gap:10px;margin:0;">
                    <i class="fas fa-exclamation-triangle" style="color:#f59e0b;"></i>
                    <span>${options.title || 'Xác nhận'}</span>
                </h5>
                <button type="button" onclick="closeCustomConfirm()" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:1rem;padding:5px;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div style="padding:1.5rem;text-align:center;">
                <div style="width:70px;height:70px;margin:0 auto 1.5rem;background:linear-gradient(135deg,rgba(239,68,68,0.2),rgba(239,68,68,0.1));border-radius:50%;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-${options.icon || 'exclamation-triangle'}" style="font-size:1.75rem;color:${options.iconColor || '#ef4444'};"></i>
                </div>
                <p style="color:#e2e8f0;font-size:1rem;margin-bottom:0.5rem;">${options.message || 'Bạn có chắc chắn?'}</p>
                <p style="color:#64748b;font-size:0.85rem;">${options.subMessage || ''}</p>
            </div>
            <div style="padding:1rem 1.5rem;border-top:1px solid rgba(0,229,255,0.1);display:flex;gap:1rem;justify-content:center;">
                <button type="button" onclick="closeCustomConfirm()" style="min-width:100px;background:linear-gradient(135deg,#000055,#000077);color:#00E5FF;border:1px solid rgba(0,229,255,0.4);padding:0.6rem 1.25rem;border-radius:10px;font-size:0.85rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:8px;">
                    <i class="fas fa-times"></i> Hủy
                </button>
                <button type="button" onclick="confirmCustomAction()" style="min-width:100px;background:linear-gradient(135deg,#000055,#000077);color:#ef4444;border:1px solid rgba(239,68,68,0.4);padding:0.6rem 1.25rem;border-radius:10px;font-size:0.85rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:8px;">
                    <i class="fas fa-check"></i> ${options.btnText || 'Xác nhận'}
                </button>
            </div>
        </div>
        <style>@keyframes fadeIn{from{opacity:0}to{opacity:1}}@keyframes slideUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}</style>
    `;
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeCustomConfirm();
    });
    
    document.body.appendChild(modal);
}

function closeCustomConfirm() {
    const modal = document.getElementById('customConfirmModal');
    if (modal) {
        modal.style.animation = 'fadeOut 0.2s ease forwards';
        setTimeout(() => modal.remove(), 200);
    }
    customConfirmCallback = null;
}

function confirmCustomAction() {
    if (customConfirmCallback) {
        customConfirmCallback();
    }
    closeCustomConfirm();
}

function showLeaveTeamModal(teamId) {
    leaveTeamId = teamId;
    showCustomConfirm({
        title: 'Rời khỏi đội',
        message: 'Bạn có chắc chắn muốn rời khỏi đội này?',
        subMessage: 'Bạn sẽ cần được mời lại nếu muốn tham gia lại.',
        icon: 'sign-out-alt',
        iconColor: '#f59e0b',
        btnText: 'Rời đội',
        onConfirm: function() {
            const form = document.getElementById('leaveTeamForm-' + leaveTeamId);
            if (form) form.submit();
        }
    });
}

// Toast Notifications
function showErrorToast(message) {
    showToast(message, 'error');
}

function showSuccessToast(message) {
    showToast(message, 'success');
}

function showToast(message, type = 'info') {
    const colors = {
        error: { border: 'rgba(239,68,68,0.5)', icon: 'times-circle', iconColor: '#ef4444' },
        success: { border: 'rgba(34,197,94,0.5)', icon: 'check-circle', iconColor: '#22c55e' },
        info: { border: 'rgba(0,229,255,0.5)', icon: 'info-circle', iconColor: '#00E5FF' }
    };
    const config = colors[type] || colors.info;
    
    const toast = document.createElement('div');
    toast.style.cssText = `position:fixed;top:80px;right:20px;background:linear-gradient(135deg,#0d1b2a,#000022);border:1px solid ${config.border};border-radius:12px;padding:1rem 1.5rem;color:#fff;z-index:99999;box-shadow:0 10px 40px rgba(0,0,0,0.5);max-width:400px;animation:toastSlideIn 0.3s ease;display:flex;align-items:center;gap:12px;`;
    toast.innerHTML = `
        <i class="fas fa-${config.icon}" style="font-size:1.25rem;color:${config.iconColor};"></i>
        <span style="flex:1;font-size:0.9rem;">${message}</span>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;color:#64748b;cursor:pointer;padding:5px;"><i class="fas fa-times"></i></button>
    `;
    
    if (!document.getElementById('toast-animation-style')) {
        const style = document.createElement('style');
        style.id = 'toast-animation-style';
        style.textContent = `@keyframes toastSlideIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}@keyframes toastSlideOut{from{transform:translateX(0);opacity:1}to{transform:translateX(100%);opacity:0}}`;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'toastSlideOut 0.3s ease forwards';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

function acceptInvitation(invitationId) {
    fetch(`{{ url('team-invitations') }}/${invitationId}/accept`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                location.reload();
            }
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    });
}

function declineInvitation(invitationId) {
    const capturedInvitationId = invitationId; // Capture in closure
    showCustomConfirm({
        title: 'Từ chối lời mời',
        message: 'Bạn có chắc chắn muốn từ chối lời mời này?',
        subMessage: 'Bạn sẽ cần được mời lại nếu muốn tham gia đội.',
        icon: 'times-circle',
        iconColor: '#ef4444',
        btnText: 'Từ chối',
        onConfirm: function() {
            executeDeclineInvitation(capturedInvitationId);
        }
    });
}

function executeDeclineInvitation(invitationId) {
    
    fetch(`{{ url('team-invitations') }}/${invitationId}/decline`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(r => {
        if (!r.ok) {
            throw new Error(`HTTP error! status: ${r.status}`);
        }
        return r.json();
    })
    .then(data => {
        if (data.success) {
            // Remove the invitation card from DOM
            const card = document.querySelector(`[data-invitation-id="${invitationId}"]`);
            if (card) {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    card.remove();
                    // Update invitation count
                    const countEl = document.querySelector('.invitations-section .section-title h2');
                    if (countEl) {
                        const remainingCards = document.querySelectorAll('[data-invitation-id]').length;
                        if (remainingCards === 0) {
                            document.querySelector('.invitations-section').remove();
                        } else {
                            countEl.textContent = `Lời mời tham gia đội (${remainingCards})`;
                        }
                    }
                }, 300);
            } else {
                location.reload();
            }
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error declining invitation:', error);
        showErrorToast('Có lỗi xảy ra khi từ chối lời mời: ' + error.message);
    });
}

// Realtime: Listen for new team invitations
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Echo !== 'undefined') {
        Echo.private(`user.{{ Auth::id() }}`)
            .listen('.team.invitation', (e) => {
                // Only add invitation card to DOM (toast is handled by global notification in layout)
                addInvitationCard(e.invitation);
            });
    }
});

function addInvitationCard(invitation) {
    // Check if invitations section exists, if not create it
    let invitationsSection = document.querySelector('.invitations-section');
    
    if (!invitationsSection) {
        // Create the section - use .teams-page instead of .teams-container
        const mainContent = document.querySelector('.teams-page');
        if (!mainContent) return;
        
        // Find the header to insert after it
        const header = mainContent.querySelector('.teams-header');
        
        invitationsSection = document.createElement('div');
        invitationsSection.className = 'invitations-section';
        invitationsSection.style.marginBottom = '2rem';
        invitationsSection.innerHTML = `
            <div class="section-title" style="border-color: rgba(245, 158, 11, 0.3);">
                <i class="fas fa-envelope" style="color: #f59e0b;"></i>
                <h2>Lời mời tham gia đội (0)</h2>
            </div>
            <div class="teams-grid"></div>
        `;
        
        // Insert after header
        if (header && header.nextSibling) {
            mainContent.insertBefore(invitationsSection, header.nextSibling);
        } else {
            mainContent.insertBefore(invitationsSection, mainContent.firstChild);
        }
        
        // Also hide empty state if exists
        const emptyState = mainContent.querySelector('.empty-state');
        if (emptyState) {
            emptyState.style.display = 'none';
        }
    }
    
    const teamsGrid = invitationsSection.querySelector('.teams-grid');
    if (!teamsGrid) return;
    
    // Check if card already exists
    if (document.querySelector(`[data-invitation-id="${invitation.id}"]`)) return;
    
    // Create new card
    const card = document.createElement('div');
    card.className = 'team-card';
    card.setAttribute('data-invitation-id', invitation.id);
    card.style.borderColor = 'rgba(245, 158, 11, 0.3)';
    card.style.animation = 'fadeInUp 0.5s ease';
    
    const logoHtml = invitation.team.logo 
        ? `<img src="${invitation.team.logo}" alt="${invitation.team.name}">`
        : `<div class="team-card-image-placeholder"><i class="fas fa-users"></i></div>`;
    
    const inviterName = invitation.inviter.display_name || invitation.inviter.name || 'Ai đó';
    const memberCount = invitation.team.members_count || '?';
    const maxMembers = invitation.team.max_members || '∞';
    const gameName = invitation.team.game ? invitation.team.game.name : null;
    
    card.innerHTML = `
        <div class="team-card-image">
            ${logoHtml}
            <div class="captain-badge" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                <i class="fas fa-envelope"></i>
                Lời mời
            </div>
        </div>
        <div class="team-card-body">
            <h3 class="team-card-name">${invitation.team.name}</h3>
            <p class="team-card-desc">
                <i class="fas fa-user" style="color: #00E5FF;"></i>
                Được mời bởi: <strong style="color: #00E5FF;">${inviterName}</strong>
            </p>
            <div class="team-card-meta">
                <div class="meta-item">
                    <i class="fas fa-users"></i>
                    ${memberCount}/${maxMembers} thành viên
                </div>
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    Vừa xong
                </div>
            </div>
            ${gameName ? `<div class="team-card-tags"><span class="tag-game">${gameName}</span></div>` : ''}
        </div>
        <div class="team-card-footer">
            <button type="button" class="btn-team btn-team-view" style="background: rgba(34, 197, 94, 0.1); border-color: rgba(34, 197, 94, 0.3); color: #22c55e;" onclick="acceptInvitation(${invitation.id})">
                <i class="fas fa-check"></i> Chấp nhận
            </button>
            <button type="button" class="btn-team btn-team-leave" onclick="declineInvitation(${invitation.id})">
                <i class="fas fa-times"></i> Từ chối
            </button>
        </div>
    `;
    
    // Add to beginning of grid
    teamsGrid.insertBefore(card, teamsGrid.firstChild);
    
    // Update count
    updateInvitationCount();
}

function updateInvitationCount() {
    const countEl = document.querySelector('.invitations-section .section-title h2');
    if (countEl) {
        const count = document.querySelectorAll('[data-invitation-id]').length;
        countEl.textContent = `Lời mời tham gia đội (${count})`;
    }
}
</script>
@endpush
@endsection
