@extends('layouts.app')

@section('title', 'Trạng thái xác minh Pro Gamer')

@push('styles')
<style>
    .status-container { background: #000814; min-height: 100vh; padding: 2rem 0; }

    .status-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        overflow: hidden;
        max-width: 800px;
        margin: 0 auto;
    }

    .card-header-custom {
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, rgba(0, 229, 255, 0.1), rgba(102, 126, 234, 0.1));
        border-bottom: 1px solid rgba(0, 229, 255, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header-custom h1 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #00E5FF;
        margin: 0;
    }

    .btn-new {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.6rem 1.25rem;
        background: linear-gradient(135deg, #00E5FF, #0099cc);
        border: none;
        border-radius: 10px;
        color: #000;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .btn-new:hover {
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.5);
        color: #000;
    }

    .card-body-custom { padding: 1.5rem 2rem; }

    .request-item {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
    }

    .request-item:last-child { margin-bottom: 0; }

    .request-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .request-game {
        color: #FFFFFF;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-pending { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .status-approved { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    .status-rejected { background: rgba(239, 68, 68, 0.2); color: #ef4444; }

    .request-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .detail-label { color: #64748b; font-size: 0.8rem; }
    .detail-value { color: #e2e8f0; font-size: 0.9rem; }

    .admin-note {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-top: 1rem;
    }

    .admin-note.approved {
        background: rgba(34, 197, 94, 0.1);
        border-color: rgba(34, 197, 94, 0.2);
    }

    .admin-note-label {
        color: #ef4444;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .admin-note.approved .admin-note-label { color: #22c55e; }

    .admin-note-text { color: #e2e8f0; font-size: 0.9rem; }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #64748b;
    }

    .empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }
    .empty-state p { margin-bottom: 1.5rem; }

    @media (max-width: 768px) {
        .request-details { grid-template-columns: 1fr; }
        .card-header-custom { flex-direction: column; gap: 1rem; align-items: flex-start; }
    }
</style>
@endpush

@section('content')
<div class="status-container">
    <div class="container">
        <div class="status-card">
            <div class="card-header-custom">
                <h1><i class="fas fa-certificate mr-2"></i> Trạng thái xác minh Pro Gamer</h1>
                @if(!Auth::user()->is_verified_gamer && !$requests->where('status', 'pending')->count())
                <a href="{{ route('verification.create') }}" class="btn-new">
                    <i class="fas fa-plus"></i> Gửi yêu cầu mới
                </a>
                @endif
            </div>

            <div class="card-body-custom">
                @if(Auth::user()->is_verified_gamer)
                <div class="request-item" style="border-color: rgba(34, 197, 94, 0.3); background: rgba(34, 197, 94, 0.1);">
                    <div style="text-align: center; padding: 1rem;">
                        <i class="fas fa-check-circle" style="font-size: 3rem; color: #22c55e; margin-bottom: 1rem;"></i>
                        <h3 style="color: #22c55e; margin-bottom: 0.5rem;">Bạn đã là Pro Gamer!</h3>
                        <p style="color: #94a3b8; margin: 0;">Tài khoản của bạn đã được xác minh.</p>
                    </div>
                </div>
                @endif

                @forelse($requests as $request)
                <div class="request-item">
                    <div class="request-header">
                        <span class="request-game">{{ $request->game_name }}</span>
                        <span class="status-badge status-{{ $request->status }}">
                            {{ $request->getStatusLabel() }}
                        </span>
                    </div>

                    <div class="request-details">
                        <div class="detail-item">
                            <span class="detail-label">Tên trong game</span>
                            <span class="detail-value">{{ $request->in_game_name }}</span>
                        </div>
                        @if($request->rank_tier)
                        <div class="detail-item">
                            <span class="detail-label">Rank/Tier</span>
                            <span class="detail-value">{{ $request->rank_tier }}</span>
                        </div>
                        @endif
                        <div class="detail-item">
                            <span class="detail-label">Ngày gửi</span>
                            <span class="detail-value">{{ $request->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($request->reviewed_at)
                        <div class="detail-item">
                            <span class="detail-label">Ngày xét duyệt</span>
                            <span class="detail-value">{{ $request->reviewed_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                    </div>

                    @if($request->admin_note)
                    <div class="admin-note {{ $request->isApproved() ? 'approved' : '' }}">
                        <div class="admin-note-label">
                            <i class="fas fa-comment-alt"></i>
                            {{ $request->isApproved() ? 'Ghi chú từ Admin' : 'Lý do từ chối' }}
                        </div>
                        <div class="admin-note-text">{{ $request->admin_note }}</div>
                    </div>
                    @endif
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-file-alt"></i>
                    <p>Bạn chưa gửi yêu cầu xác minh nào.</p>
                    <a href="{{ route('verification.create') }}" class="btn-new">
                        <i class="fas fa-plus"></i> Gửi yêu cầu đầu tiên
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
