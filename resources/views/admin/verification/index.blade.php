@extends('layouts.app')

@section('title', 'Xét duyệt Pro Gamer')

@push('styles')
<style>
    .verification-container { background: #000814; min-height: 100vh; }

    .verification-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .verification-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #f59e0b, transparent);
    }
    .hero-icon {
        width: 60px; height: 60px; min-width: 60px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 0 25px rgba(245, 158, 11, 0.3);
    }
    .hero-icon i { font-size: 1.5rem; color: white; }
    .hero-title { font-family: 'Rajdhani', sans-serif; font-size: 1.5rem; font-weight: 700; color: #00E5FF; margin: 0; }
    .hero-subtitle { color: #94a3b8; font-size: 0.9rem; margin: 0.25rem 0 0 0; }

    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
    .stat-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .stat-icon {
        width: 50px; height: 50px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
    }
    .stat-icon.total { background: rgba(99, 102, 241, 0.2); color: #818cf8; }
    .stat-icon.pending { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .stat-icon.approved { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    .stat-icon.rejected { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
    .stat-value { font-size: 1.5rem; font-weight: 700; color: #FFFFFF; }
    .stat-label { font-size: 0.8rem; color: #64748b; }

    .filter-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .filter-input, .filter-select {
        width: 100%;
        padding: 0.6rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 8px;
        color: #FFFFFF;
        font-size: 0.85rem;
    }
    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: #00E5FF;
    }
    .filter-select option { background: #0d1b2a; }
    .filter-label { color: #94a3b8; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem; display: block; }

    .btn-neon {
        background: linear-gradient(135deg, #000055, #000077);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.4);
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-neon:hover {
        background: rgba(0, 229, 255, 0.15);
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.4);
        color: #FFFFFF;
    }

    .table-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
    }
    .requests-table { width: 100%; border-collapse: collapse; }
    .requests-table th {
        background: rgba(0, 229, 255, 0.05);
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    }
    .requests-table td {
        padding: 1rem;
        color: #e2e8f0;
        font-size: 0.875rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.05);
        vertical-align: middle;
    }
    .requests-table tbody tr { transition: all 0.3s ease; }
    .requests-table tbody tr:hover { background: rgba(0, 229, 255, 0.05); }
    .requests-table tbody tr.pending-row { background: rgba(245, 158, 11, 0.05); }

    .user-info { display: flex; align-items: center; gap: 12px; }
    .user-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        object-fit: cover; border: 2px solid rgba(0, 229, 255, 0.3);
    }
    .user-avatar-fallback {
        width: 40px; height: 40px; border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 700;
    }
    .user-name { color: #FFFFFF; font-weight: 600; }
    .user-email { color: #64748b; font-size: 0.75rem; }

    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .status-pending { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .status-approved { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    .status-rejected { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
    .status-revoked { background: rgba(107, 114, 128, 0.2); color: #9ca3af; }

    .btn-action {
        padding: 0.4rem 0.6rem;
        border-radius: 8px;
        font-size: 0.8rem;
        cursor: pointer;
        border: 1px solid transparent;
        background: transparent;
        transition: all 0.3s ease;
    }
    .btn-action-view { color: #00E5FF; border-color: rgba(0, 229, 255, 0.3); }
    .btn-action-view:hover { background: rgba(0, 229, 255, 0.15); }
    .btn-action-approve { color: #22c55e; border-color: rgba(34, 197, 94, 0.3); }
    .btn-action-approve:hover { background: rgba(34, 197, 94, 0.15); }
    .btn-action-reject { color: #ef4444; border-color: rgba(239, 68, 68, 0.3); }
    .btn-action-reject:hover { background: rgba(239, 68, 68, 0.15); }

    .empty-state { text-align: center; padding: 4rem 2rem; color: #64748b; }
    .empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }

    .pagination-wrapper {
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
    }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .requests-table { display: block; overflow-x: auto; }
    }

    /* Confirm Modal Styles */
    .confirm-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(5px);
        z-index: 99999;
        align-items: center;
        justify-content: center;
    }

    .confirm-modal.show {
        display: flex;
    }

    .modal-content {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.3);
        border-radius: 20px;
        padding: 2rem;
        max-width: 420px;
        width: 90%;
        text-align: center;
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .modal-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #FFFFFF;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .modal-message {
        color: #94a3b8;
        font-size: 1rem;
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }

    .modal-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .modal-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
    }

    .modal-btn-cancel {
        background: rgba(255, 255, 255, 0.1);
        color: #94a3b8;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .modal-btn-cancel:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #FFFFFF;
    }

    .modal-btn-approve {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
    }

    .modal-btn-approve:hover {
        box-shadow: 0 0 25px rgba(34, 197, 94, 0.5);
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<!-- Confirm Modal -->
<div class="confirm-modal" id="confirmModal">
    <div class="modal-content">
        <h3 class="modal-title" id="modalTitle">
            <i class="fas fa-check-circle" style="color: #22c55e;"></i> Xác nhận duyệt
        </h3>
        <p class="modal-message" id="modalMessage">Bạn có chắc chắn muốn duyệt yêu cầu xác minh Pro Gamer này?</p>
        <div class="modal-buttons">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="closeConfirmModal()">
                <i class="fas fa-arrow-left"></i> Hủy
            </button>
            <button type="button" class="modal-btn modal-btn-approve" id="modalConfirmBtn" onclick="confirmApprove()">
                <i class="fas fa-check"></i> Duyệt
            </button>
        </div>
    </div>
</div>

<div class="verification-container">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="verification-hero">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="hero-icon"><i class="fas fa-certificate"></i></div>
                    <div>
                        <h1 class="hero-title">Xét duyệt Pro Gamer</h1>
                        <p class="hero-subtitle">Xem xét và duyệt yêu cầu xác minh Pro Gamer từ người dùng</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total"><i class="fas fa-file-alt"></i></div>
                <div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                    <div class="stat-label">Tổng yêu cầu</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pending"><i class="fas fa-hourglass-half"></i></div>
                <div>
                    <div class="stat-value">{{ $stats['pending'] }}</div>
                    <div class="stat-label">Đang chờ</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon approved"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-value">{{ $stats['approved'] }}</div>
                    <div class="stat-label">Đã duyệt</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon rejected"><i class="fas fa-times-circle"></i></div>
                <div>
                    <div class="stat-value">{{ $stats['rejected'] }}</div>
                    <div class="stat-label">Đã từ chối</div>
                </div>
            </div>
        </div>

        <div class="filter-card">
            <form method="GET" action="{{ route('admin.verification.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="filter-label">Tìm kiếm</label>
                        <input type="text" name="search" class="filter-input" value="{{ request('search') }}" placeholder="Tên, email, game...">
                    </div>
                    <div>
                        <label class="filter-label">Trạng thái</label>
                        <select name="status" class="filter-select">
                            <option value="">Tất cả</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Đã từ chối</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="btn-neon flex-1"><i class="fas fa-filter"></i> Lọc</button>
                        <a href="{{ route('admin.verification.index') }}" class="btn-neon"><i class="fas fa-times"></i></a>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-card">
            <div class="overflow-x-auto">
                <table class="requests-table">
                    <thead>
                        <tr>
                            <th>Người dùng</th>
                            <th>Game</th>
                            <th>IGN / Rank</th>
                            <th>Trạng thái</th>
                            <th>Ngày gửi</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                        <tr class="{{ $request->isPending() ? 'pending-row' : '' }}">
                            <td>
                                <div class="user-info">
                                    @if($request->user && $request->user->avatar)
                                    <img src="{{ get_avatar_url($request->user->avatar) }}" class="user-avatar" alt="Avatar">
                                    @else
                                    <div class="user-avatar-fallback">{{ strtoupper(substr($request->user->name ?? 'U', 0, 1)) }}</div>
                                    @endif
                                    <div>
                                        <div class="user-name">{{ $request->user->name ?? 'Unknown' }}</div>
                                        <div class="user-email">{{ $request->user->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $request->game_name }}</td>
                            <td>
                                <div>{{ $request->in_game_name }}</div>
                                @if($request->rank_tier)
                                <div style="color: #00E5FF; font-size: 0.8rem;">{{ $request->rank_tier }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge status-{{ $request->status }}">
                                    {{ $request->getStatusLabel() }}
                                </span>
                            </td>
                            <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.verification.show', $request) }}" class="btn-action btn-action-view" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($request->isPending())
                                    <form action="{{ route('admin.verification.approve', $request) }}" method="POST" style="display: inline;" id="approveForm{{ $request->id }}">
                                        @csrf
                                        <button type="button" class="btn-action btn-action-approve" title="Duyệt" onclick="showApproveModal({{ $request->id }})">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Không có yêu cầu nào</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($requests->hasPages())
            <div class="pagination-wrapper">
                {{ $requests->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentApproveFormId = null;

    function showApproveModal(requestId) {
        currentApproveFormId = requestId;
        document.getElementById('confirmModal').classList.add('show');
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.remove('show');
        currentApproveFormId = null;
    }

    function confirmApprove() {
        if (currentApproveFormId) {
            document.getElementById('approveForm' + currentApproveFormId).submit();
        }
    }

    // Close modal when clicking outside
    document.getElementById('confirmModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeConfirmModal();
        }
    });
</script>
@endpush
