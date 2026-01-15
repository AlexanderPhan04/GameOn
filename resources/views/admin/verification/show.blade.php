@extends('layouts.app')

@section('title', 'Chi tiết yêu cầu xác minh')

@push('styles')
<style>
    .detail-container { 
        background: #000814; 
        min-height: 100vh; 
        padding: 2rem 0; 
        padding-top: 80px; /* Thêm padding-top để tránh bị navbar che */
    }

    .detail-container .max-w-6xl {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
        box-sizing: border-box;
        overflow-x: hidden;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #00E5FF;
        text-decoration: none;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }
    .back-link:hover { color: #FFFFFF; }

    .detail-grid { 
        display: grid; 
        grid-template-columns: 2fr 1fr; 
        gap: 1.5rem;
        max-width: 100%;
    }

    .detail-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        overflow: hidden;
    }

    .card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #00E5FF;
        margin: 0;
    }

    .card-body { padding: 1.5rem; }

    .user-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .user-avatar {
        width: 70px; height: 70px; border-radius: 50%;
        object-fit: cover; border: 3px solid rgba(0, 229, 255, 0.3);
    }

    .user-avatar-fallback {
        width: 70px; height: 70px; border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 700; font-size: 1.5rem;
    }

    .user-name { color: #FFFFFF; font-size: 1.25rem; font-weight: 700; }
    .user-email { color: #64748b; font-size: 0.9rem; }

    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; }

    .info-item { margin-bottom: 1rem; }
    .info-label { color: #64748b; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.25rem; text-transform: uppercase; }
    .info-value { color: #e2e8f0; font-size: 0.95rem; }

    .info-text {
        background: rgba(0, 0, 0, 0.3);
        border-radius: 8px;
        padding: 1rem;
        color: #e2e8f0;
        font-size: 0.9rem;
        white-space: pre-wrap;
        line-height: 1.6;
    }

    .proof-image {
        max-width: 100%;
        border-radius: 12px;
        border: 1px solid rgba(0, 229, 255, 0.2);
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .proof-image:hover { transform: scale(1.02); }

    .proof-links a {
        display: block;
        color: #00E5FF;
        text-decoration: none;
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        word-break: break-all;
    }

    .proof-links a:hover { color: #FFFFFF; }
    .proof-links a:last-child { border-bottom: none; }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .status-pending { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .status-approved { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    .status-rejected { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
    .status-revoked { background: rgba(107, 114, 128, 0.2); color: #9ca3af; }

    .action-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem;
        position: sticky;
        top: 100px;
        box-sizing: border-box;
        max-width: 100%;
    }

    .action-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #00E5FF;
        margin-bottom: 1rem;
    }

    .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(0, 229, 255, 0.2);
        box-sizing: border-box;
        border-radius: 10px;
        color: #FFFFFF;
        font-size: 0.9rem;
        min-height: 100px;
        resize: vertical;
        margin-bottom: 1rem;
    }

    .form-textarea:focus {
        outline: none;
        border-color: #00E5FF;
    }

    .btn-approve {
        width: 100%;
        padding: 0.875rem;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .btn-approve:hover { box-shadow: 0 0 25px rgba(34, 197, 94, 0.5); }

    .btn-reject {
        width: 100%;
        padding: 0.875rem;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-reject:hover { box-shadow: 0 0 25px rgba(239, 68, 68, 0.5); }

    .history-item {
        background: rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 0.75rem;
    }

    .history-item:last-child { margin-bottom: 0; }

    .reviewed-info {
        background: rgba(0, 229, 255, 0.1);
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .reviewed-info p { margin: 0.25rem 0; color: #94a3b8; font-size: 0.9rem; }
    .reviewed-info strong { color: #e2e8f0; }

    @media (max-width: 1024px) {
        .detail-grid { grid-template-columns: 1fr; }
        .action-card { position: static; }
        .detail-container .max-w-6xl {
            padding: 0 0.75rem;
        }
    }

    @media (max-width: 768px) {
        .info-grid { grid-template-columns: 1fr; }
        .detail-container {
            padding-top: 70px;
        }
        .detail-container .max-w-6xl {
            padding: 0 0.5rem;
        }
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

    .modal-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
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

    .modal-btn-reject {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .modal-btn-reject:hover {
        box-shadow: 0 0 25px rgba(239, 68, 68, 0.5);
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<!-- Confirm Modal -->
<div class="confirm-modal" id="confirmModal">
    <div class="modal-content">
        <h3 class="modal-title" id="modalTitle">
            <i class="fas fa-question-circle" style="color: #00E5FF;"></i> Xác nhận
        </h3>
        <p class="modal-message" id="modalMessage">Bạn có chắc chắn muốn thực hiện hành động này?</p>
        <div class="modal-buttons">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="closeConfirmModal()">
                <i class="fas fa-arrow-left"></i> Hủy
            </button>
            <button type="button" class="modal-btn modal-btn-approve" id="modalConfirmBtn" onclick="confirmAction()">
                <i class="fas fa-check"></i> Xác nhận
            </button>
        </div>
    </div>
</div>

<div class="detail-container">
    <div class="max-w-6xl mx-auto px-4">
        <a href="{{ route('admin.verification.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>

        <div class="detail-grid">
            <div>
                <div class="detail-card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fas fa-user mr-2"></i> Thông tin người dùng</h2>
                        <span class="status-badge status-{{ $request->status }}">{{ $request->getStatusLabel() }}</span>
                    </div>
                    <div class="card-body">
                        <div class="user-header">
                            @if($request->user && $request->user->avatar)
                            <img src="{{ get_avatar_url($request->user->avatar) }}" class="user-avatar" alt="Avatar">
                            @else
                            <div class="user-avatar-fallback">{{ strtoupper(substr($request->user->name ?? 'U', 0, 1)) }}</div>
                            @endif
                            <div>
                                <div class="user-name">{{ $request->user->name ?? 'Unknown' }}</div>
                                <div class="user-email">{{ $request->user->email ?? '' }}</div>
                                @if($request->user->is_verified_gamer)
                                <span style="color: #22c55e; font-size: 0.8rem;"><i class="fas fa-check-circle"></i> Đã là Pro Gamer</span>
                                @endif
                            </div>
                        </div>

                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Game</div>
                                <div class="info-value">{{ $request->game_name }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Tên trong game (IGN)</div>
                                <div class="info-value">{{ $request->in_game_name }}</div>
                            </div>
                            @if($request->in_game_id)
                            <div class="info-item">
                                <div class="info-label">ID trong game</div>
                                <div class="info-value">{{ $request->in_game_id }}</div>
                            </div>
                            @endif
                            @if($request->rank_tier)
                            <div class="info-item">
                                <div class="info-label">Rank/Tier</div>
                                <div class="info-value" style="color: #00E5FF; font-weight: 600;">{{ $request->rank_tier }}</div>
                            </div>
                            @endif
                            <div class="info-item">
                                <div class="info-label">Ngày gửi</div>
                                <div class="info-value">{{ $request->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>

                        @if($request->achievements)
                        <div class="info-item" style="margin-top: 1rem;">
                            <div class="info-label">Thành tích</div>
                            <div class="info-text">{{ $request->achievements }}</div>
                        </div>
                        @endif

                        @if($request->proof_links)
                        <div class="info-item" style="margin-top: 1rem;">
                            <div class="info-label">Link chứng minh</div>
                            <div class="proof-links">
                                @foreach(explode("\n", $request->proof_links) as $link)
                                    @if(trim($link))
                                    <a href="{{ trim($link) }}" target="_blank" rel="noopener">
                                        <i class="fas fa-external-link-alt mr-2"></i>{{ trim($link) }}
                                    </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($request->proof_image)
                        <div class="info-item" style="margin-top: 1rem;">
                            <div class="info-label">Ảnh chứng minh</div>
                            <a href="{{ Storage::url($request->proof_image) }}" target="_blank">
                                <img src="{{ Storage::url($request->proof_image) }}" class="proof-image" alt="Proof">
                            </a>
                        </div>
                        @endif

                        @if($request->additional_info)
                        <div class="info-item" style="margin-top: 1rem;">
                            <div class="info-label">Thông tin bổ sung</div>
                            <div class="info-text">{{ $request->additional_info }}</div>
                        </div>
                        @endif

                        @if($request->reviewed_at)
                        <div class="reviewed-info">
                            <p><strong>Người xét duyệt:</strong> {{ $request->reviewer->name ?? 'Unknown' }}</p>
                            <p><strong>Thời gian:</strong> {{ $request->reviewed_at->format('d/m/Y H:i') }}</p>
                            @if($request->admin_note)
                            <p><strong>Ghi chú:</strong> {{ $request->admin_note }}</p>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                @if($history->count() > 0)
                <div class="detail-card" style="margin-top: 1.5rem;">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fas fa-history mr-2"></i> Lịch sử yêu cầu</h2>
                    </div>
                    <div class="card-body">
                        @foreach($history as $item)
                        <div class="history-item">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <span style="color: #e2e8f0; font-weight: 600;">{{ $item->game_name }}</span>
                                <span class="status-badge status-{{ $item->status }}" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">
                                    {{ $item->getStatusLabel() }}
                                </span>
                            </div>
                            <div style="color: #64748b; font-size: 0.8rem;">
                                {{ $item->created_at->format('d/m/Y') }} - {{ $item->in_game_name }}
                            </div>
                            @if($item->admin_note)
                            <div style="color: #94a3b8; font-size: 0.85rem; margin-top: 0.5rem;">
                                <i class="fas fa-comment-alt mr-1"></i> {{ $item->admin_note }}
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div>
                @if($request->isPending())
                <div class="action-card">
                    <h3 class="action-title"><i class="fas fa-gavel mr-2"></i> Xét duyệt</h3>

                    <form action="{{ route('admin.verification.approve', $request) }}" method="POST" id="approveForm">
                        @csrf
                        <textarea name="admin_note" class="form-textarea" placeholder="Ghi chú (tùy chọn)..." style="width: 100%; padding: 0.75rem 1rem; background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(0, 229, 255, 0.3); border-radius: 10px; color: #FFFFFF; font-size: 0.9rem; min-height: 80px;"></textarea>
                        <button type="button" class="btn-approve" onclick="showConfirmModal('approve')">
                            <i class="fas fa-check mr-2"></i> Duyệt yêu cầu
                        </button>
                    </form>

                    <form action="{{ route('admin.verification.reject', $request) }}" method="POST" id="rejectForm">
                        @csrf
                        <textarea name="admin_note" class="form-textarea" id="rejectNote" placeholder="Lý do từ chối (bắt buộc)..." required style="width: 100%; padding: 0.75rem 1rem; background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(0, 229, 255, 0.3); border-radius: 10px; color: #FFFFFF; font-size: 0.9rem; min-height: 80px;"></textarea>
                        <button type="button" class="btn-reject" onclick="showConfirmModal('reject')">
                            <i class="fas fa-times mr-2"></i> Từ chối
                        </button>
                    </form>
                </div>
                @else
                <div class="action-card">
                    <h3 class="action-title"><i class="fas fa-info-circle mr-2"></i> Trạng thái</h3>
                    <p style="color: #94a3b8; text-align: center; padding: 1rem 0;">
                        Yêu cầu này đã được xử lý.
                    </p>
                    
                    @if($request->user && $request->user->is_verified_gamer)
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(0, 229, 255, 0.1);">
                        <h4 style="color: #ef4444; font-size: 0.95rem; font-weight: 600; margin-bottom: 0.75rem;">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Thu hồi Pro Gamer
                        </h4>
                        <form action="{{ route('admin.verification.revoke', $request->user_id) }}" method="POST" id="revokeForm">
                            @csrf
                            <textarea name="reason" class="form-textarea" id="revokeReason" placeholder="Lý do thu hồi (bắt buộc)..." required style="width: 100%; padding: 0.75rem 1rem; background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 10px; color: #FFFFFF; font-size: 0.9rem; min-height: 80px;"></textarea>
                            <button type="button" class="btn-reject" onclick="showConfirmModal('revoke')" style="margin-top: 0.5rem;">
                                <i class="fas fa-ban mr-2"></i> Thu hồi Pro Gamer
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentAction = '';

    function showConfirmModal(action) {
        currentAction = action;
        const modal = document.getElementById('confirmModal');
        const title = document.getElementById('modalTitle');
        const message = document.getElementById('modalMessage');
        const confirmBtn = document.getElementById('modalConfirmBtn');

        if (action === 'approve') {
            title.innerHTML = '<i class="fas fa-check-circle" style="color: #22c55e;"></i> Xác nhận duyệt';
            message.textContent = 'Bạn có chắc chắn muốn duyệt yêu cầu xác minh Pro Gamer này?';
            confirmBtn.className = 'modal-btn modal-btn-approve';
            confirmBtn.innerHTML = '<i class="fas fa-check"></i> Duyệt';
            confirmBtn.style.display = 'inline-flex';
            modal.classList.add('show');
        } else if (action === 'reject') {
            const rejectNote = document.getElementById('rejectNote');
            if (rejectNote && !rejectNote.value.trim()) {
                showErrorModal('Vui lòng nhập lý do từ chối!');
                rejectNote.focus();
                return;
            }
            title.innerHTML = '<i class="fas fa-times-circle" style="color: #ef4444;"></i> Xác nhận từ chối';
            message.textContent = 'Bạn có chắc chắn muốn từ chối yêu cầu xác minh này?';
            confirmBtn.className = 'modal-btn modal-btn-reject';
            confirmBtn.innerHTML = '<i class="fas fa-times"></i> Từ chối';
            confirmBtn.style.display = 'inline-flex';
            modal.classList.add('show');
        } else if (action === 'revoke') {
            const revokeReason = document.getElementById('revokeReason');
            if (revokeReason && !revokeReason.value.trim()) {
                showErrorModal('Vui lòng nhập lý do thu hồi!');
                revokeReason.focus();
                return;
            }
            title.innerHTML = '<i class="fas fa-ban" style="color: #ef4444;"></i> Xác nhận thu hồi';
            message.textContent = 'Bạn có chắc chắn muốn thu hồi trạng thái Pro Gamer của người dùng này? Hành động này không thể hoàn tác.';
            confirmBtn.className = 'modal-btn modal-btn-reject';
            confirmBtn.innerHTML = '<i class="fas fa-ban"></i> Thu hồi';
            confirmBtn.style.display = 'inline-flex';
            modal.classList.add('show');
        }
    }

    function showErrorModal(errorMessage) {
        const modal = document.getElementById('confirmModal');
        const title = document.getElementById('modalTitle');
        const message = document.getElementById('modalMessage');
        const confirmBtn = document.getElementById('modalConfirmBtn');

        title.innerHTML = '<i class="fas fa-exclamation-triangle" style="color: #f59e0b;"></i> Thông báo';
        message.textContent = errorMessage;
        confirmBtn.style.display = 'none';
        modal.classList.add('show');
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.remove('show');
    }

    function confirmAction() {
        if (currentAction === 'approve') {
            document.getElementById('approveForm').submit();
        } else if (currentAction === 'reject') {
            document.getElementById('rejectForm').submit();
        } else if (currentAction === 'revoke') {
            document.getElementById('revokeForm').submit();
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
