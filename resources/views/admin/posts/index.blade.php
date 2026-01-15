@extends('layouts.app')

@section('title', __('app.posts.manage_posts'))

@push('styles')
<style>
    .posts-container { background: #000814; min-height: 100vh; }

    /* Hero Section */
    .posts-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .posts-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #00E5FF, transparent);
    }
    .hero-icon {
        width: 60px; height: 60px; min-width: 60px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 0 25px rgba(99, 102, 241, 0.3);
    }
    .hero-icon i { font-size: 1.5rem; color: white; }
    .hero-title { font-family: 'Rajdhani', sans-serif; font-size: 1.5rem; font-weight: 700; color: #00E5FF; margin: 0; }
    .hero-subtitle { color: #94a3b8; font-size: 0.9rem; margin: 0.25rem 0 0 0; }

    /* Stats Cards */
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
    .stat-icon.posts { background: rgba(99, 102, 241, 0.2); color: #818cf8; }
    .stat-icon.comments { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    .stat-icon.today { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .stat-icon.comments-today { background: rgba(0, 229, 255, 0.2); color: #00E5FF; }
    .stat-value { font-size: 1.5rem; font-weight: 700; color: #FFFFFF; }
    .stat-label { font-size: 0.8rem; color: #64748b; }

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
    .filter-input, .filter-select {
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
    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: #00E5FF;
        box-shadow: 0 0 10px rgba(0, 229, 255, 0.2);
    }
    .filter-input::placeholder { color: #64748b; }
    .filter-select option { background: #0d1b2a; color: #FFFFFF; }
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
    .posts-table { width: 100%; border-collapse: collapse; }
    .posts-table th {
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
    .posts-table td {
        padding: 1rem;
        color: #e2e8f0;
        font-size: 0.875rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.05);
        vertical-align: middle;
    }
    .posts-table tbody tr { transition: all 0.3s ease; }
    .posts-table tbody tr:hover { background: rgba(0, 229, 255, 0.05); }

    .user-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(0, 229, 255, 0.3); }
    .user-avatar-fallback {
        width: 40px; height: 40px; border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 700; font-size: 0.9rem;
    }
    .post-content { max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: #e2e8f0; }
    .post-media { display: flex; gap: 4px; }
    .post-media-thumb { width: 40px; height: 40px; border-radius: 6px; object-fit: cover; border: 1px solid rgba(0, 229, 255, 0.2); }
    .post-media-count { background: rgba(0, 229, 255, 0.2); color: #00E5FF; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; }

    .stat-badge { padding: 0.25rem 0.5rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; }
    .stat-badge-reactions { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
    .stat-badge-comments { background: rgba(34, 197, 94, 0.2); color: #22c55e; }

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
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .posts-hero { padding: 1.25rem; }
        .filter-grid { grid-template-columns: 1fr !important; }
        .posts-table { display: block; overflow-x: auto; }
    }
</style>
@endpush

@section('content')
<div class="posts-container">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Hero Section -->
        <div class="posts-hero">
            <div class="hero-content flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="hero-icon"><i class="fas fa-newspaper"></i></div>
                    <div>
                        <h1 class="hero-title">{{ __('app.posts.manage_posts') }}</h1>
                        <p class="hero-subtitle">Quản lý bài viết và bình luận của người dùng</p>
                    </div>
                </div>
                <div class="hero-buttons flex gap-3">
                    <a href="{{ route('admin.posts.comments') }}" class="btn-neon">
                        <i class="fas fa-comments"></i><span>Quản lý bình luận</span>
                    </a>
                    <button type="button" class="btn-neon btn-neon-danger" id="bulkDeleteBtn" disabled onclick="openBulkDeleteModal()">
                        <i class="fas fa-trash"></i><span>Xóa đã chọn</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon posts"><i class="fas fa-newspaper"></i></div>
                <div>
                    <div class="stat-value">{{ number_format($stats['total_posts']) }}</div>
                    <div class="stat-label">Tổng bài viết</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon comments"><i class="fas fa-comments"></i></div>
                <div>
                    <div class="stat-value">{{ number_format($stats['total_comments']) }}</div>
                    <div class="stat-label">Tổng bình luận</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon today"><i class="fas fa-calendar-day"></i></div>
                <div>
                    <div class="stat-value">{{ number_format($stats['posts_today']) }}</div>
                    <div class="stat-label">Bài viết hôm nay</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon comments-today"><i class="fas fa-comment-dots"></i></div>
                <div>
                    <div class="stat-value">{{ number_format($stats['comments_today']) }}</div>
                    <div class="stat-label">Bình luận hôm nay</div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="filter-card">
            <form method="GET" action="{{ route('admin.posts.index') }}" id="filterForm">
                <div class="filter-grid grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="filter-label">Tìm kiếm</label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-search input-icon"></i>
                            <input type="text" class="filter-input" name="search" value="{{ request('search') }}" placeholder="Nội dung, tên người dùng...">
                        </div>
                    </div>
                    <div>
                        <label class="filter-label">Từ ngày</label>
                        <input type="date" class="filter-input" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div>
                        <label class="filter-label">Đến ngày</label>
                        <input type="date" class="filter-input" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="btn-neon flex-1"><i class="fas fa-filter"></i><span>Lọc</span></button>
                        <a href="{{ route('admin.posts.index') }}" class="btn-neon"><i class="fas fa-times"></i></a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Posts Table -->
        <div class="table-card">
            <div class="overflow-x-auto">
                <table class="posts-table">
                    <thead>
                        <tr>
                            <th class="w-12"><input type="checkbox" id="selectAll" class="custom-checkbox"></th>
                            <th>Người đăng</th>
                            <th>Nội dung</th>
                            <th>Media</th>
                            <th>Reactions</th>
                            <th>Comments</th>
                            <th>Ngày đăng</th>
                            <th class="w-24">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                        <tr>
                            <td><input type="checkbox" class="custom-checkbox post-checkbox" value="{{ $post->id }}"></td>
                            <td>
                                <div class="flex items-center gap-3">
                                    @if($post->user && $post->user->avatar)
                                    <img src="{{ get_avatar_url($post->user->avatar) }}" class="user-avatar" alt="Avatar">
                                    @else
                                    <div class="user-avatar-fallback">{{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}</div>
                                    @endif
                                    <div>
                                        <div style="color: #FFFFFF; font-weight: 600;">{{ $post->user->name ?? 'Unknown' }}</div>
                                        <div style="color: #64748b; font-size: 0.75rem;">ID: {{ $post->user_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="post-content" title="{{ $post->content }}">
                                    {{ $post->content ?: '(Không có nội dung)' }}
                                </div>
                            </td>
                            <td>
                                @if($post->media->count() > 0)
                                <div class="post-media">
                                    @foreach($post->media->take(2) as $media)
                                    <img src="{{ Storage::url($media->path) }}" class="post-media-thumb" alt="Media">
                                    @endforeach
                                    @if($post->media->count() > 2)
                                    <span class="post-media-count">+{{ $post->media->count() - 2 }}</span>
                                    @endif
                                </div>
                                @else
                                <span style="color: #64748b;">-</span>
                                @endif
                            </td>
                            <td><span class="stat-badge stat-badge-reactions">{{ $post->reactions_count }}</span></td>
                            <td><span class="stat-badge stat-badge-comments">{{ $post->comments_count }}</span></td>
                            <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.posts.show', $post) }}" class="btn-action btn-action-view" title="Xem chi tiết"><i class="fas fa-eye"></i></a>
                                    <button type="button" class="btn-action btn-action-delete" onclick="confirmDelete({{ $post->id }})" title="Xóa"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fas fa-newspaper"></i></div>
                                    <h3 class="empty-title">Không có bài viết nào</h3>
                                    <p class="empty-text">Chưa có bài viết nào được đăng</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($posts->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination-info">Hiển thị {{ $posts->firstItem() }} - {{ $posts->lastItem() }} trong tổng số {{ $posts->total() }} bài viết</div>
                {{ $posts->links() }}
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
            <p>Bạn có chắc chắn muốn xóa bài viết này?</p>
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
            <h5 class="modal-title-custom"><i class="fas fa-trash mr-2" style="color: #ef4444;"></i>Xóa nhiều bài viết</h5>
            <button type="button" class="btn-action" onclick="closeBulkDeleteModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body-custom">
            <p>Bạn có chắc chắn muốn xóa <span id="selectedCount" style="color: #00E5FF; font-weight: bold;">0</span> bài viết đã chọn?</p>
            <p style="color: #ef4444; margin-top: 0.5rem;">Hành động này không thể hoàn tác!</p>
        </div>
        <div class="modal-footer-custom">
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeBulkDeleteModal()">Hủy</button>
            <form id="bulkDeleteForm" method="POST" action="{{ route('admin.posts.bulk-delete') }}">
                @csrf
                <input type="hidden" name="post_ids" id="bulkPostIds">
                <button type="submit" class="btn-modal btn-modal-danger">Xóa tất cả</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let selectedPosts = [];

    // Select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.post-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
        });
        updateSelectedPosts();
    });

    // Individual checkboxes
    document.querySelectorAll('.post-checkbox').forEach(cb => {
        cb.addEventListener('change', updateSelectedPosts);
    });

    function updateSelectedPosts() {
        selectedPosts = [];
        document.querySelectorAll('.post-checkbox:checked').forEach(cb => {
            selectedPosts.push(cb.value);
        });
        document.getElementById('bulkDeleteBtn').disabled = selectedPosts.length === 0;
        document.getElementById('selectedCount').textContent = selectedPosts.length;
    }

    function confirmDelete(postId) {
        document.getElementById('deleteForm').action = '/admin/posts/' + postId;
        document.getElementById('deleteModal').classList.add('active');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('active');
    }

    function openBulkDeleteModal() {
        if (selectedPosts.length === 0) return;
        document.getElementById('bulkPostIds').value = JSON.stringify(selectedPosts);
        document.getElementById('bulkDeleteModal').classList.add('active');
    }

    function closeBulkDeleteModal() {
        document.getElementById('bulkDeleteModal').classList.remove('active');
    }

    // Close modals on outside click
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    });
</script>
@endpush
