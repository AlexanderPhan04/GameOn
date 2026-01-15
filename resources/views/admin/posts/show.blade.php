@extends('layouts.app')

@section('title', 'Chi tiết bài viết #' . $post->id)

@push('styles')
<style>
    .post-detail-container { background: #000814; min-height: 100vh; }

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

    .post-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .post-header {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .post-author {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .author-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(0, 229, 255, 0.3);
    }

    .author-avatar-fallback {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
    }

    .author-name { color: #FFFFFF; font-weight: 600; font-size: 1rem; }
    .author-meta { color: #64748b; font-size: 0.8rem; }

    .post-content {
        padding: 1.5rem;
        color: #e2e8f0;
        font-size: 1rem;
        line-height: 1.6;
        white-space: pre-wrap;
    }

    .post-media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        padding: 0 1.5rem 1.5rem;
    }

    .post-media-item {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(0, 229, 255, 0.2);
    }

    .post-media-item img,
    .post-media-item video {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .post-stats {
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        gap: 2rem;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #94a3b8;
        font-size: 0.9rem;
    }

    .stat-item i { color: #00E5FF; }

    .post-actions {
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        gap: 1rem;
    }

    .btn-action {
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

    .btn-delete {
        background: linear-gradient(135deg, #7f1d1d, #991b1b);
        border: 1px solid rgba(239, 68, 68, 0.4);
        color: #ef4444;
    }
    .btn-delete:hover {
        box-shadow: 0 0 20px rgba(239, 68, 68, 0.4);
        color: #FFFFFF;
    }

    /* Comments Section */
    .comments-section {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        overflow: hidden;
    }

    .comments-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .comments-title {
        color: #00E5FF;
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
    }

    .comment-item {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.05);
        transition: background 0.3s ease;
    }

    .comment-item:hover { background: rgba(0, 229, 255, 0.03); }
    .comment-item:last-child { border-bottom: none; }

    .comment-author {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .comment-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(0, 229, 255, 0.2);
    }

    .comment-avatar-fallback {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.8rem;
    }

    .comment-author-name { color: #FFFFFF; font-weight: 600; font-size: 0.9rem; }
    .comment-time { color: #64748b; font-size: 0.75rem; }
    .comment-content { color: #e2e8f0; font-size: 0.9rem; line-height: 1.5; margin-left: 48px; }

    .comment-actions {
        margin-left: 48px;
        margin-top: 0.5rem;
    }

    .btn-comment-delete {
        background: transparent;
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-comment-delete:hover {
        background: rgba(239, 68, 68, 0.15);
    }

    .reply-item {
        margin-left: 48px;
        padding: 0.75rem;
        background: rgba(0, 229, 255, 0.03);
        border-radius: 8px;
        margin-top: 0.5rem;
    }

    .reply-item .comment-author { margin-bottom: 0.25rem; }
    .reply-item .comment-content { margin-left: 40px; }
    .reply-item .comment-actions { margin-left: 40px; }

    .empty-comments {
        padding: 3rem;
        text-align: center;
        color: #64748b;
    }

    .empty-comments i { font-size: 2rem; margin-bottom: 1rem; opacity: 0.5; }

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
</style>
@endpush

@section('content')
<div class="post-detail-container">
    <div class="max-w-4xl mx-auto px-4 py-6">
        <a href="{{ route('admin.posts.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách bài viết
        </a>

        <!-- Post Card -->
        <div class="post-card">
            <div class="post-header">
                <div class="post-author">
                    @if($post->user && $post->user->avatar)
                    <img src="{{ get_avatar_url($post->user->avatar) }}" class="author-avatar" alt="Avatar">
                    @else
                    <div class="author-avatar-fallback">{{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}</div>
                    @endif
                    <div>
                        <div class="author-name">{{ $post->user->name ?? 'Unknown' }}</div>
                        <div class="author-meta">
                            ID: {{ $post->user_id }} • {{ $post->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            @if($post->content)
            <div class="post-content">{{ $post->content }}</div>
            @endif

            @if($post->media->count() > 0)
            <div class="post-media-grid">
                @foreach($post->media as $media)
                <div class="post-media-item">
                    @if(Str::startsWith($media->type, 'video'))
                    <video controls>
                        <source src="{{ Storage::url($media->path) }}" type="{{ $media->type }}">
                    </video>
                    @else
                    <img src="{{ Storage::url($media->path) }}" alt="Media">
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            <div class="post-stats">
                <div class="stat-item">
                    <i class="fas fa-heart"></i>
                    <span>{{ $post->reactions->count() }} reactions</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-comment"></i>
                    <span>{{ $post->comments->count() }} bình luận</span>
                </div>
                @if($post->sharedPost)
                <div class="stat-item">
                    <i class="fas fa-share"></i>
                    <span>Chia sẻ từ bài viết #{{ $post->shared_post_id }}</span>
                </div>
                @endif
            </div>

            <div class="post-actions">
                <button type="button" class="btn-action btn-delete" onclick="confirmDeletePost()">
                    <i class="fas fa-trash"></i> Xóa bài viết
                </button>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="comments-section">
            <div class="comments-header">
                <h3 class="comments-title">
                    <i class="fas fa-comments mr-2"></i>
                    Bình luận ({{ $post->comments->count() }})
                </h3>
            </div>

            @if($post->comments->count() > 0)
                @foreach($post->comments as $comment)
                <div class="comment-item">
                    <div class="comment-author">
                        @if($comment->user && $comment->user->avatar)
                        <img src="{{ get_avatar_url($comment->user->avatar) }}" class="comment-avatar" alt="Avatar">
                        @else
                        <div class="comment-avatar-fallback">{{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}</div>
                        @endif
                        <div>
                            <span class="comment-author-name">{{ $comment->user->name ?? 'Unknown' }}</span>
                            <span class="comment-time">• {{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="comment-content">{{ $comment->content }}</div>
                    <div class="comment-actions">
                        <button type="button" class="btn-comment-delete" onclick="confirmDeleteComment({{ $comment->id }})">
                            <i class="fas fa-trash"></i> Xóa
                        </button>
                    </div>

                    @foreach($comment->replies as $reply)
                    <div class="reply-item">
                        <div class="comment-author">
                            @if($reply->user && $reply->user->avatar)
                            <img src="{{ get_avatar_url($reply->user->avatar) }}" class="comment-avatar" alt="Avatar" style="width: 28px; height: 28px;">
                            @else
                            <div class="comment-avatar-fallback" style="width: 28px; height: 28px; font-size: 0.7rem;">{{ strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}</div>
                            @endif
                            <div>
                                <span class="comment-author-name">{{ $reply->user->name ?? 'Unknown' }}</span>
                                <span class="comment-time">• {{ $reply->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="comment-content">{{ $reply->content }}</div>
                        <div class="comment-actions">
                            <button type="button" class="btn-comment-delete" onclick="confirmDeleteComment({{ $reply->id }})">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            @else
            <div class="empty-comments">
                <i class="fas fa-comment-slash"></i>
                <p>Chưa có bình luận nào</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Post Modal -->
<div class="modal-overlay" id="deletePostModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <h5 class="modal-title-custom"><i class="fas fa-exclamation-triangle mr-2" style="color: #ef4444;"></i>Xác nhận xóa bài viết</h5>
            <button type="button" class="btn-action" onclick="closeDeletePostModal()" style="background: transparent; border: none; color: #94a3b8;"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body-custom">
            <p>Bạn có chắc chắn muốn xóa bài viết này?</p>
            <p style="color: #ef4444; margin-top: 0.5rem;">Tất cả bình luận và media sẽ bị xóa. Hành động này không thể hoàn tác!</p>
        </div>
        <div class="modal-footer-custom">
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeDeletePostModal()">Hủy</button>
            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modal btn-modal-danger">Xóa bài viết</button>
            </form>
        </div>
    </div>
</div>

<!-- Delete Comment Modal -->
<div class="modal-overlay" id="deleteCommentModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <h5 class="modal-title-custom"><i class="fas fa-exclamation-triangle mr-2" style="color: #ef4444;"></i>Xác nhận xóa bình luận</h5>
            <button type="button" class="btn-action" onclick="closeDeleteCommentModal()" style="background: transparent; border: none; color: #94a3b8;"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body-custom">
            <p>Bạn có chắc chắn muốn xóa bình luận này?</p>
            <p style="color: #ef4444; margin-top: 0.5rem;">Hành động này không thể hoàn tác!</p>
        </div>
        <div class="modal-footer-custom">
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeDeleteCommentModal()">Hủy</button>
            <form id="deleteCommentForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modal btn-modal-danger">Xóa bình luận</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDeletePost() {
        document.getElementById('deletePostModal').classList.add('active');
    }

    function closeDeletePostModal() {
        document.getElementById('deletePostModal').classList.remove('active');
    }

    function confirmDeleteComment(commentId) {
        document.getElementById('deleteCommentForm').action = '/admin/posts/comments/' + commentId;
        document.getElementById('deleteCommentModal').classList.add('active');
    }

    function closeDeleteCommentModal() {
        document.getElementById('deleteCommentModal').classList.remove('active');
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
