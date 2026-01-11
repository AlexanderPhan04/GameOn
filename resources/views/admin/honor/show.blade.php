@extends('layouts.app')

@section('title', 'Chi tiết đợt vote - ' . $honorEvent->name)

@push('styles')
<style>
    .honor-detail-container {
        background: #000814;
        min-height: calc(100vh - 64px);
    }

    /* Header Card */
    .detail-header {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(245, 158, 11, 0.2);
        border-radius: 16px;
        position: relative;
        overflow: hidden;
    }

    .detail-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, #f59e0b, #00E5FF, transparent);
    }

    .header-icon {
        width: 50px;
        height: 50px;
        min-width: 50px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
    }

    /* Buttons */
    .btn-back {
        width: 36px;
        height: 36px;
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.3);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #00E5FF;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: rgba(0, 229, 255, 0.2);
        color: #fff;
    }

    .btn-action {
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border: 1px solid transparent;
    }

    .btn-edit {
        background: rgba(245, 158, 11, 0.15);
        border-color: rgba(245, 158, 11, 0.3);
        color: #f59e0b;
    }

    .btn-edit:hover {
        background: rgba(245, 158, 11, 0.25);
        color: #fbbf24;
    }

    .btn-toggle {
        background: rgba(100, 116, 139, 0.15);
        border-color: rgba(100, 116, 139, 0.3);
        color: #94a3b8;
    }

    .btn-toggle:hover {
        background: rgba(100, 116, 139, 0.25);
        color: #fff;
    }

    .btn-reset {
        background: rgba(239, 68, 68, 0.15);
        border-color: rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }

    .btn-reset:hover {
        background: rgba(239, 68, 68, 0.25);
        color: #f87171;
    }

    /* Main Content Card */
    .content-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.12);
        border-radius: 16px;
        overflow: hidden;
    }

    /* Stats Row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1px;
        background: rgba(0, 229, 255, 0.1);
    }

    .stat-item {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        padding: 1.25rem;
        text-align: center;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .stat-icon.cyan { background: rgba(0, 229, 255, 0.15); color: #00E5FF; }
    .stat-icon.green { background: rgba(34, 197, 94, 0.15); color: #22c55e; }
    .stat-icon.amber { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .stat-icon.purple { background: rgba(168, 85, 247, 0.15); color: #a855f7; }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        display: block;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Info Sections */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1px;
        background: rgba(0, 229, 255, 0.1);
    }

    .info-section {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        padding: 1.25rem;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #f59e0b;
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(245, 158, 11, 0.15);
    }

    .info-list {
        display: flex;
        flex-direction: column;
        gap: 0.625rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.875rem;
    }

    .info-item-label {
        color: #94a3b8;
    }

    .info-item-value {
        color: #e2e8f0;
        font-weight: 500;
    }

    /* Badges */
    .badge {
        padding: 0.25rem 0.625rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-amber { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .badge-green { background: rgba(34, 197, 94, 0.2); color: #22c55e; }

    /* Settings Grid */
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .setting-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0.75rem;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 8px;
        font-size: 0.8rem;
    }

    .setting-label { color: #94a3b8; }

    .setting-check {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
    }

    .setting-check.on { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    .setting-check.off { background: rgba(239, 68, 68, 0.2); color: #ef4444; }

    /* Weight Display */
    .weight-display {
        display: flex;
        gap: 0.75rem;
        padding: 0.75rem;
        background: rgba(245, 158, 11, 0.05);
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .weight-item {
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: #94a3b8;
    }

    .weight-value {
        color: #f59e0b;
        font-weight: 700;
    }

    /* Action Links */
    .action-links {
        display: flex;
        gap: 0.5rem;
    }

    .action-link {
        flex: 1;
        padding: 0.625rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        transition: all 0.3s ease;
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.25);
        color: #00E5FF;
    }

    .action-link:hover {
        background: rgba(0, 229, 255, 0.2);
        color: #fff;
    }

    .action-link.purple {
        background: rgba(168, 85, 247, 0.1);
        border-color: rgba(168, 85, 247, 0.25);
        color: #a855f7;
    }

    .action-link.purple:hover {
        background: rgba(168, 85, 247, 0.2);
        color: #c084fc;
    }

    .text-green { color: #22c55e !important; }
    .text-amber { color: #f59e0b !important; }

    @media (max-width: 768px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
        .info-grid {
            grid-template-columns: 1fr;
        }
        .settings-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .stats-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="honor-detail-container">
    <div class="max-w-5xl mx-auto px-4 py-6">
        <!-- Header -->
        <div class="detail-header p-4 mb-5">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.honor.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </a>
                    <div class="header-icon">
                        <i class="fas fa-trophy text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-amber-500 font-['Rajdhani']">{{ $honorEvent->name }}</h1>
                        <p class="text-slate-500 text-xs">{{ $honorEvent->description ?? 'Chi tiết đợt vote' }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.honor.edit', $honorEvent) }}" class="btn-action btn-edit">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                    <button type="button" class="btn-action btn-toggle" onclick="toggleStatus({{ $honorEvent->id }})">
                        <i class="fas fa-{{ $honorEvent->is_active ? 'pause' : 'play' }}"></i>
                        {{ $honorEvent->is_active ? 'Tắt' : 'Bật' }}
                    </button>
                    <button type="button" class="btn-action btn-reset" onclick="resetVotes({{ $honorEvent->id }})">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="content-card">
            <!-- Stats Row -->
            <div class="stats-row">
                <div class="stat-item">
                    <div class="stat-icon cyan"><i class="fas fa-vote-yea"></i></div>
                    <span class="stat-value" data-stat="total-votes">{{ $stats['total_votes'] }}</span>
                    <span class="stat-label">Tổng vote</span>
                </div>
                <div class="stat-item">
                    <div class="stat-icon green"><i class="fas fa-balance-scale"></i></div>
                    <span class="stat-value">{{ number_format($stats['total_weighted_votes'], 1) }}</span>
                    <span class="stat-label">Trọng số</span>
                </div>
                <div class="stat-item">
                    <div class="stat-icon amber"><i class="fas fa-{{ $honorEvent->target_type === 'user' ? 'user' : 'users' }}"></i></div>
                    <span class="stat-value">{{ ucfirst($honorEvent->target_type) }}</span>
                    <span class="stat-label">Đối tượng</span>
                </div>
                <div class="stat-item">
                    <div class="stat-icon purple"><i class="fas fa-{{ $honorEvent->isCurrentlyRunning() ? 'check-circle' : 'pause-circle' }}"></i></div>
                    <span class="stat-value {{ $honorEvent->isCurrentlyRunning() ? 'text-green' : 'text-amber' }}" style="font-size: 1rem;">
                        {{ $honorEvent->isCurrentlyRunning() ? 'Đang chạy' : ($honorEvent->is_active ? 'Tạm dừng' : 'Tắt') }}
                    </span>
                    <span class="stat-label">Trạng thái</span>
                </div>
            </div>

            <!-- Info Grid -->
            <div class="info-grid">
                <!-- Thông tin cơ bản -->
                <div class="info-section">
                    <div class="section-title">
                        <i class="fas fa-info-circle"></i>
                        <span>Thông tin cơ bản</span>
                    </div>
                    <div class="info-list">
                        <div class="info-item">
                            <span class="info-item-label">Chế độ</span>
                            <span class="badge {{ $honorEvent->mode === 'event' ? 'badge-amber' : 'badge-green' }}">
                                {{ $honorEvent->mode === 'event' ? 'Sự kiện' : 'Tự do' }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-item-label">Tạo bởi</span>
                            <span class="info-item-value">{{ $honorEvent->creator->name ?? 'Unknown' }}</span>
                        </div>
                        @if($honorEvent->start_time)
                        <div class="info-item">
                            <span class="info-item-label">Bắt đầu</span>
                            <span class="info-item-value">{{ $honorEvent->start_time->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                        <div class="info-item">
                            <span class="info-item-label">Kết thúc</span>
                            @if($honorEvent->end_time)
                                <span class="info-item-value">{{ $honorEvent->end_time->format('d/m/Y H:i') }}</span>
                            @else
                                <span class="info-item-value text-green">Không giới hạn</span>
                            @endif
                        </div>
                        <div class="info-item">
                            <span class="info-item-label">Ngày tạo</span>
                            <span class="info-item-value">{{ $honorEvent->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Cài đặt vote -->
                <div class="info-section">
                    <div class="section-title">
                        <i class="fas fa-cog"></i>
                        <span>Cài đặt vote</span>
                    </div>
                    <div class="settings-grid">
                        <div class="setting-item">
                            <span class="setting-label">Participant</span>
                            <span class="setting-check {{ $honorEvent->allow_participant_vote ? 'on' : 'off' }}">
                                <i class="fas fa-{{ $honorEvent->allow_participant_vote ? 'check' : 'times' }}"></i>
                            </span>
                        </div>
                        <div class="setting-item">
                            <span class="setting-label">Admin</span>
                            <span class="setting-check {{ $honorEvent->allow_admin_vote ? 'on' : 'off' }}">
                                <i class="fas fa-{{ $honorEvent->allow_admin_vote ? 'check' : 'times' }}"></i>
                            </span>
                        </div>
                        <div class="setting-item">
                            <span class="setting-label">Ẩn danh</span>
                            <span class="setting-check {{ $honorEvent->allow_anonymous ? 'on' : 'off' }}">
                                <i class="fas fa-{{ $honorEvent->allow_anonymous ? 'check' : 'times' }}"></i>
                            </span>
                        </div>
                        <div class="setting-item">
                            <span class="setting-label">Hoạt động</span>
                            <span class="setting-check {{ $honorEvent->is_active ? 'on' : 'off' }}">
                                <i class="fas fa-{{ $honorEvent->is_active ? 'check' : 'times' }}"></i>
                            </span>
                        </div>
                    </div>
                    <div class="weight-display">
                        <div class="weight-item">
                            <span>Participant</span>
                            <span class="weight-value">{{ $honorEvent->participant_weight }}x</span>
                        </div>
                        <div class="weight-item">
                            <span>Admin</span>
                            <span class="weight-value">{{ $honorEvent->admin_weight }}x</span>
                        </div>
                    </div>
                    <div class="action-links">
                        <a href="{{ route('honor.results', $honorEvent) }}" class="action-link">
                            <i class="fas fa-chart-bar"></i> Kết quả
                        </a>
                        <a href="{{ route('honor.show', $honorEvent) }}" class="action-link purple">
                            <i class="fas fa-vote-yea"></i> Trang vote
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Forms -->
<form id="toggleForm" method="POST" style="display: none;">@csrf @method('PATCH')</form>
<form id="resetForm" method="POST" style="display: none;">@csrf @method('DELETE')</form>
@endsection

@push('scripts')
<script>
function toggleStatus(eventId) {
    if (confirm('Bạn có chắc muốn thay đổi trạng thái?')) {
        const form = document.getElementById('toggleForm');
        form.action = `/admin/honor/${eventId}/toggle`;
        form.submit();
    }
}

function resetVotes(eventId) {
    if (confirm('Bạn có chắc muốn reset tất cả votes? Hành động này không thể hoàn tác!')) {
        const form = document.getElementById('resetForm');
        form.action = `/admin/honor/${eventId}/reset`;
        form.submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    if (typeof Echo !== 'undefined') {
        Echo.channel('honor.{{ $honorEvent->id }}')
            .listen('.vote.cast', (e) => {
                const el = document.querySelector('[data-stat="total-votes"]');
                if (el) {
                    el.textContent = e.stats.event_total_votes;
                    el.style.transform = 'scale(1.2)';
                    el.style.color = '#22c55e';
                    setTimeout(() => {
                        el.style.transform = 'scale(1)';
                        el.style.color = '#fff';
                    }, 300);
                }
            });
    }
});
</script>
@endpush
