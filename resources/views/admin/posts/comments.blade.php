@extends('layouts.app')

@section('title', 'Quản lý bình luận')

@push('styles')
<style>
    .comments-container { background: #000814; min-height: 100vh; }

    /* Hero Section */
    .comments-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .comments-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #22c55e, transparent);
    }
    .hero-icon {
        width: 60px; height: 60px; min-width: 60px;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 0 25px rgba(34, 197, 94, 0.3);
    }
    .hero-icon i { font-size: 1.5rem; color: white; }
    .hero-title { font-family: 'Rajdhani', sans-serif; font-size: 1.5rem; font-weight: 700; color: #00E5FF; margin: 0; }
    .hero-subtitle { color: #94a3b8; font-size: 0.9rem; margin: 0.25rem 0 0 0; }

    .btn-neon {
        background: linear-gradient(135deg, #000055, #000077);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.4);
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .btn-neon:hover {
        background: rgba(0, 229, 255, 0.15);
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.4);
        color: #FFFFFF;
        transform: translateY(-2px);
    }
    .btn-neon-danger {
        background: linear-gradient(135deg, #7f1d1d, #991b1b);
        border-color: rgba(239, 68, 68, 0.4);
        color: #ef4444;
    }
    .btn-neon-danger:hover { box-shadow: 0 0 20px rgba(239, 68, 68, 0.4); }

    /* Filter Card */
    .filter-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .filter-label { color: #94a3b8; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem; display: block; }
    .filter-input {
        width: 100%;
        padding: 0.6rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 8px;
        color: #FFFFFF;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }
    .filter-input:focus {
        outline: none;
        border-color: #00E5FF;
        box-shadow: 0 0 10px rgba(0, 229, 255, 0.2);
    }
    .filter-input::placeholder { color: #64748b; }
    .input-icon-wrapper { position: relative; }
    .input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 0.85rem; }
    .input-icon-wrapper .filter-input { padding-left: 2.5rem; }

    /* Table Card */
    .table-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
    }
    .comments-table { width: 100%; border-collapse: collapse; }
    .comments-table th {
        background: rgba(0, 229, 255, 0.05);
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    }
    .comments-table td {
        padding: 1rem;
        color: #e2e8f0;
        font-size: 0.875rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.05);
        vertical-align: middle;
    }
    .comments-table tbody tr { transition: all 0.3s ease; }
    .comments-table tbody tr:hover { background: rgba(0, 229, 255, 0.05); }

    .user-avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(0, 229, 255, 0.3); }
    .user-avatar-fallback {
        width: 36px; height: 36px; border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 700; font-size: 0.8rem;
    }
    .comment-content { max-width: 350px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: #e2e8f0; }
    .post-link { color: #00E5FF; text-decoration: none; }
    .post-link:hover { text-decoration: underline; }

    /* Action Buttons */
    .btn-action { padding: 0.4rem 0.6rem; border-radius: 8px; font-size: 0.8rem; transition: all 0.3s ease; cursor: pointer; border: 1px solid transparent; background: transparent; }
    .btn-action-view { color: #00E5FF; border-color: rgba(0, 229, 255, 0.3); }
    .btn-action-view:hover { background: rgba(0, 229, 255, 0.15); }
    .btn-action-delete { color: #ef4444; border-color: rgba(239, 68, 68, 0.3); }
    .btn-action-delete:hover { background: rgba(239, 68, 68, 0.15); }

    /* Empty State */
    .empty-state { text-align: center; padding: 4rem 2rem; }
    .empty-icon { width: 80px; height: 80px; background: rgba(0, 229, 255, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
    .empty-icon i { font-size: 2rem; color: #64748b; }
    .empty-title { color: #FFFFFF; font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; }
    .empty-text { color: #64748b; font-size: 0.9rem; }

    .custom-checkbox { width: 18px; height: 18px; accent-color: #00E5FF; cursor: pointer; }
    .pagination-wrapper { padding: 1rem 1.5rem; border-top: 1px solid rgba(0, 229, 255, 0.1); display: flex; justify-content: space-between; align-items: center; }
    .pagination-info { color: #64748b; font-size: 0.85rem; }

    /* Modal */
    .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(4px); z-index: 99999; display: none; align-items: center; justify-content: center; padding: 1rem; }
    .modal-overlay.active { display: flex; }
    .modal-content-custom { background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%); border: 1px solid rgba(0, 229, 255, 0.3); border-radius: 20px; width: 100%; max-width: 450px; overflow: hidden; }
    .modal-header-custom { padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0, 229, 255, 0.1); display: flex; align-items: center; justify-content: space-between; }
    .modal-title-custom { color: #FFFFFF; font-size: 1.1rem; font-weight: 600; }
    .modal-body-custom { padding: 1.5rem; color: #94a3b8; }
    .modal-footer-custom { padding: 1rem 1.5rem; border-top: 1px solid rgba(0, 229, 255, 0.1); display: flex; gap: 0.75rem; justify-content: flex-end; }
    .btn-modal { padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; }
    .btn-modal-cancel { background: rgba(100, 116, 139, 0.2); color: #94a3b8; border: 1px solid rgba(100, 116, 139, 0.3); }
    .btn-modal-cancel:hover { background: rgba(100, 116, 139, 0.3); color: #FFFFFF; }
    .btn-modal-danger { background: linear-gradient(135deg, #dc2626, #b91c1c); color: #FFFFFF; border: none; }
    .btn-modal-danger:hover { box-shadow: 0 0 20px rgba(239, 68, 68, 0.4); }

    @media (max-width: 768px) {
        .comments-hero { padding: 1.25rem; }
        .filter-grid { grid-template-columns: 1fr !important; }
        .comments-table { display: block; overflow-x: auto; }
    }
</style>
@endpush

@section('content')
<div class="comments-container">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Hero Section -->
        <div class="comments-hero">
            <div class="hero-content flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="hero-icon"><i class="fas fa-comments"></i></div>
                    <div>
                        <h1 class="hero-title">Quản lý bình luận</h1>
                        <p class="hero-subtitle">Xem và quản lý tất cả bình luận của người dùng</p>
                    </div>
                </div>
                <div class="hero-buttons flex gap-3">
                    <a href="{{ route('admin.posts.index') }}" class="btn-neon">
                        <i class="fas fa-arrow-left"></i><span>Quay lại bài viết</span>
                    </a>
                    <button type="button" class="btn-neon btn-neon-danger" id="bulkDeleteBtn" disabled onclick="openBulkDeleteModal()">
                        <i class="fas fa-trash"></i><span>Xóa đã chọn</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="filter-card">
            <form method="GET" action="{{ route('admin.posts.comments') }}" id="filterForm">
                <div class="filter-grid grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="filter-label">Tìm kiếm</label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-search input-icon"></i>
                            <input type="text" class="filter-input" name="search" value="{{ request('search') }}" placeholder="Nội dung, tên người dùng...">
                        </div>
                    </div>
                    <div>
                        <label class="filter-label">ID Bài viết</label>
                        <input type="number" class="filter-input" name="post_id" value="{{ request('post_id') }}" placeholder="Nhập ID bài viết">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="btn-neon flex-1"><i class="fas fa-filter"></i><span>Lọc</span></button>
                        <a href="{{ route('admin.posts.comments') }}" class="btn-neon"><i class="fas fa-times"></i></a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Comments Table -->
        <div class="table-card">
            <div class="overflow-x-auto">
                <table class="comments-table">
                    <thead>
                        <tr>
                            <th class="w-12"><input type="checkbox" id="selectAll" class="custom-checkbox"></th>
                            <th>Người bình luận</th>
                            <th>Nội dung</th>
                            <th>Bài viết</th>
                            <th>Ngày tạo</th>
                            <th class="w-24">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comments as $comment)
                        <tr>
                            <td><input type="checkbox" class="custom-checkbox comment-checkbox" value="{{ $comment->id }}"></td>
                            <td>
                                <div class="flex items-center gap-3">
                                    @if($comment->user && $comment->user->avatar)
                                    <img src="{{ get_avatar_url($comment->user->avatar) }}" class="user-avatar" alt="Avatar">
                                    @else
                                    <div class="user-avatar-fallback">{{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}</div>
                                    @endif
                                    <div>
                                        <div style="color: #FFFFFF; font-weight: 600;">{{ $comment->user->name ?? 'Unknown' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="comment-content" title="{{ $comment->content }}">
                                    {{ $comment->content }}
                                </div>
                            </td>
                            <td>
                                @if($comment->post)
                                <a href="{{ route('admin.posts.show', $comment->post) }}" class="post-link">
                                    Bài viết #{{ $comment->post_id }}
                                </a>
                                <div style="color: #64748b; font-size: 0.75rem;">{{ Str::limit($comment->post->content, 30) }}</div>
                                @else
                                <span style="color: #64748b;">Đã xóa</span>
                                @endif
                            </td>
                            <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="flex gap-2">
                                    <button type="button" class="btn-action btn-action-delete" onclick="confirmDelete({{ $comment->id }})" title="Xóa"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fas fa-comments"></i></div>
                                    <h3 class="empty-title">Không có bình luận nào</h3>
                                    <p class="empty-text">Chưa có bình luận nào được đăng</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($comments->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination-info">Hiển thị {{ $comments->firstItem() }} - {{ $comments->lastItem() }} trong tổng số {{ $comments->total() }} bình luận</div>
                {{ $comments->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <h5 class="modal-title-custom"><i class="fas fa-exclamation-triangle mr-2" style="color: #ef4444;"></i>Xác nhận xóa</h5>
            <button type="button" class="btn-action" onclick="closeDeleteModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body-custom">
            <p>Bạn có chắc chắn muốn xóa bình luận này?</p>
            <p style="color: #ef4444; margin-top: 0.5rem;">Hành động này không thể hoàn tác!</p>
        </div>
        <div class="modal-footer-custom">
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeDeleteModal()">Hủy</button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modal btn-modal-danger">Xóa</button>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div class="modal-overlay" id="bulkDeleteModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <h5 class="modal-title-custom"><i class="fas fa-trash mr-2" style="color: #ef4444;"></i>Xóa nhiều bình luận</h5>
            <button type="button" class="btn-action" onclick="closeBulkDeleteModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body-custom">
            <p>Bạn có chắc chắn muốn xóa <span id="selectedCount" style="color: #00E5FF; font-weight: bold;">0</span> bình luận đã chọn?</p>
            <p style="color: #ef4444; margin-top: 0.5rem;">Hành động này không thể hoàn tác!</p>
        </div>
        <div class="modal-footer-custom">
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeBulkDeleteModal()">Hủy</button>
            <form id="bulkDeleteForm" method="POST" action="{{ route('admin.posts.comments.bulk-delete') }}">
                @csrf
                <input type="hidden" name="comment_ids" id="bulkCommentIds">
                <button type="submit" class="btn-modal btn-modal-danger">Xóa tất cả</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let selectedComments = [];

    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.comment-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
        });
        updateSelectedComments();
    });

    document.querySelectorAll('.comment-checkbox').forEach(cb => {
        cb.addEventListener('change', updateSelectedComments);
    });

    function updateSelectedComments() {
        selectedComments = [];
        document.querySelectorAll('.comment-checkbox:checked').forEach(cb => {
            selectedComments.push(cb.value);
        });
        document.getElementById('bulkDeleteBtn').disabled = selectedComments.length === 0;
        document.getElementById('selectedCount').textContent = selectedComments.length;
    }

    function confirmDelete(commentId) {
        document.getElementById('deleteForm').action = '/admin/posts/comments/' + commentId;
        document.getElementById('deleteModal').classList.add('active');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('active');
    }

    function openBulkDeleteModal() {
        if (selectedComments.length === 0) return;
        document.getElementById('bulkCommentIds').value = JSON.stringify(selectedComments);
        document.getElementById('bulkDeleteModal').classList.add('active');
    }

    function closeBulkDeleteModal() {
        document.getElementById('bulkDeleteModal').classList.remove('active');
    }

    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    });
</script>
@endpush
