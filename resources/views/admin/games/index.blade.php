@extends('layouts.app')

@section('title', __('app.profile.manage_games'))

@push('styles')
<style>
    .games-container {
        background: #000814;
        min-height: 100vh;
    }
    
    @media (max-width: 991.98px) {
        .games-container {
            margin-left: 0 !important;
            width: 100% !important;
        }
    }
    
    /* Hero Section */
    .games-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .games-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #00E5FF, transparent);
    }
    
    .hero-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .hero-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .hero-icon {
        width: 60px;
        height: 60px;
        min-width: 60px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 25px rgba(99, 102, 241, 0.3);
    }
    
    .hero-icon i {
        font-size: 1.5rem;
        color: white;
    }
    
    .hero-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #00E5FF;
        margin: 0;
    }
    
    .hero-subtitle {
        color: #94a3b8;
        font-size: 0.9rem;
        margin: 0.25rem 0 0 0;
    }

    .btn-neon {
        background: linear-gradient(135deg, #000055, #000077);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.4);
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-size: 0.9rem;
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
        box-shadow: 0 0 25px rgba(0, 229, 255, 0.4);
        color: #FFFFFF;
        transform: translateY(-2px);
    }
    
    /* Success Alert */
    .success-alert {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #22c55e;
    }
    
    .success-alert i {
        font-size: 1.25rem;
    }
    
    /* Games Grid */
    .games-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }
    
    @media (max-width: 1280px) {
        .games-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .games-grid {
            grid-template-columns: 1fr;
        }
        
        .hero-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .btn-neon {
            width: 100%;
            justify-content: center;
        }
    }
    
    /* Game Card */
    .game-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s ease;
    }
    
    .game-card:hover {
        transform: translateY(-8px);
        border-color: rgba(0, 229, 255, 0.4);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 30px rgba(0, 229, 255, 0.15);
    }
    
    .game-banner {
        position: relative;
        height: 160px;
        overflow: hidden;
    }
    
    .game-banner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .game-card:hover .game-banner img {
        transform: scale(1.05);
    }
    
    .game-banner-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #1a237e 0%, #000055 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .game-banner-placeholder i {
        font-size: 3rem;
        color: rgba(0, 229, 255, 0.3);
    }

    .game-status {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .status-active {
        background: rgba(34, 197, 94, 0.9);
        color: #FFFFFF;
        box-shadow: 0 0 15px rgba(34, 197, 94, 0.5);
    }
    
    .status-inactive {
        background: rgba(100, 116, 139, 0.9);
        color: #FFFFFF;
    }
    
    .game-esport-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        background: rgba(245, 158, 11, 0.9);
        color: #000;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .game-card-body {
        padding: 1.25rem;
    }
    
    .game-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .game-logo {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid rgba(0, 229, 255, 0.3);
        flex-shrink: 0;
    }
    
    .game-logo-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, #000055, #000077);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(0, 229, 255, 0.3);
        flex-shrink: 0;
    }
    
    .game-logo-placeholder i {
        color: #00E5FF;
        font-size: 1.25rem;
    }
    
    .game-name {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #FFFFFF;
        margin: 0;
    }
    
    .game-genre {
        color: #64748b;
        font-size: 0.8rem;
        margin: 0.25rem 0 0 0;
    }
    
    .game-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .game-info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.8rem;
        color: #94a3b8;
    }
    
    .game-info-item i {
        color: #00E5FF;
        width: 16px;
        text-align: center;
    }
    
    .game-description {
        color: #64748b;
        font-size: 0.8rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .game-card-footer {
        padding: 0.75rem 1.25rem;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-card {
        flex: 1;
        padding: 0.5rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        cursor: pointer;
        border: none;
    }
    
    .btn-view {
        background: rgba(0, 229, 255, 0.1);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.3);
    }
    
    .btn-view:hover {
        background: rgba(0, 229, 255, 0.2);
        color: #FFFFFF;
    }
    
    .btn-edit {
        background: rgba(99, 102, 241, 0.1);
        color: #818cf8;
        border: 1px solid rgba(99, 102, 241, 0.3);
    }
    
    .btn-edit:hover {
        background: rgba(99, 102, 241, 0.2);
        color: #FFFFFF;
    }
    
    .btn-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .btn-delete:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #FFFFFF;
    }
    
    .game-meta {
        padding: 0.5rem 1.25rem;
        background: rgba(0, 229, 255, 0.03);
        font-size: 0.75rem;
        color: #64748b;
        display: flex;
        justify-content: space-between;
    }
    
    .game-meta i {
        color: #00E5FF;
        margin-right: 4px;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 20px;
    }
    
    .empty-icon {
        width: 100px;
        height: 100px;
        background: rgba(0, 229, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    
    .empty-icon i {
        font-size: 2.5rem;
        color: #64748b;
    }
    
    .empty-title {
        color: #FFFFFF;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .empty-text {
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
    }
    
    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }

    /* Custom Modal */
    .custom-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(4px);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    
    .custom-modal-overlay.active {
        display: flex;
    }
    
    .custom-modal {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.3);
        border-radius: 20px;
        width: 100%;
        max-width: 450px;
        overflow: hidden;
        animation: modalSlideIn 0.3s ease;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .custom-modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .custom-modal-title {
        color: #FFFFFF;
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .custom-modal-title i {
        color: #f59e0b;
    }
    
    .custom-modal-close {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: #94a3b8;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .custom-modal-close:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }
    
    .custom-modal-body {
        padding: 1.5rem;
        color: #94a3b8;
    }
    
    .custom-modal-body strong {
        color: #00E5FF;
    }
    
    .warning-box {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.3);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        margin-top: 1rem;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 0.85rem;
    }
    
    .warning-box i {
        color: #f59e0b;
        margin-top: 2px;
    }
    
    .warning-box span {
        color: #fbbf24;
    }
    
    .custom-modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }
    
    .btn-modal-cancel {
        background: rgba(100, 116, 139, 0.2);
        color: #94a3b8;
        border: 1px solid rgba(100, 116, 139, 0.3);
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-modal-cancel:hover {
        background: rgba(100, 116, 139, 0.3);
        color: #FFFFFF;
    }
    
    .btn-modal-delete {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.4);
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-modal-delete:hover {
        background: rgba(239, 68, 68, 0.3);
        color: #FFFFFF;
    }
    
    .btn-modal-delete:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<div class="games-container">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Hero Section -->
        <div class="games-hero">
            <div class="hero-content">
                <div class="hero-left">
                    <div class="hero-icon">
                        <i class="fas fa-gamepad"></i>
                    </div>
                    <div>
                        <h1 class="hero-title">{{ __('app.profile.manage_games') }}</h1>
                        <p class="hero-subtitle">Quản lý danh sách game trong hệ thống</p>
                    </div>
                </div>
                <a href="{{ route('admin.games.create') }}" class="btn-neon">
                    <i class="fas fa-plus"></i>
                    <span>Thêm Game Mới</span>
                </a>
            </div>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
        <div class="success-alert">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- Games Grid -->
        @if(isset($games) && $games->count() > 0)
        <div class="games-grid">
            @foreach($games as $game)
            <div class="game-card">
                <!-- Banner -->
                <div class="game-banner">
                    @if($game->banner)
                    <img src="{{ $game->banner }}" alt="{{ $game->name }}">
                    @else
                    <div class="game-banner-placeholder">
                        <i class="fas fa-image"></i>
                    </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <span class="game-status {{ $game->status === 'active' ? 'status-active' : 'status-inactive' }}">
                        {{ $game->status === 'active' ? 'Hoạt động' : 'Ngừng' }}
                    </span>
                    
                    <!-- Esport Badge -->
                    @if($game->esport_support)
                    <span class="game-esport-badge">
                        <i class="fas fa-trophy"></i> Esport
                    </span>
                    @endif
                </div>

                <!-- Card Body -->
                <div class="game-card-body">
                    <div class="game-header">
                        @if($game->logo)
                        <img src="{{ $game->logo }}" alt="{{ $game->name }}" class="game-logo">
                        @else
                        <div class="game-logo-placeholder">
                            <i class="fas fa-gamepad"></i>
                        </div>
                        @endif
                        <div>
                            <h3 class="game-name">{{ $game->name }}</h3>
                            @if($game->genre)
                            <p class="game-genre">{{ $game->genre }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="game-info">
                        @if($game->publisher)
                        <div class="game-info-item">
                            <i class="fas fa-building"></i>
                            <span>{{ $game->publisher }}</span>
                        </div>
                        @endif
                        @if($game->team_size)
                        <div class="game-info-item">
                            <i class="fas fa-users"></i>
                            <span>{{ $game->team_size }}</span>
                        </div>
                        @endif
                    </div>

                    @if($game->description)
                    <p class="game-description">{{ $game->description }}</p>
                    @endif
                </div>

                <!-- Meta Info -->
                <div class="game-meta">
                    <span><i class="fas fa-calendar"></i>{{ $game->created_at->format('d/m/Y') }}</span>
                    @if($game->tournaments_count ?? false)
                    <span><i class="fas fa-trophy"></i>{{ $game->tournaments_count }} giải đấu</span>
                    @endif
                </div>

                <!-- Card Footer -->
                <div class="game-card-footer">
                    <a href="{{ route('admin.games.show', $game) }}" class="btn-card btn-view">
                        <i class="fas fa-eye"></i> Xem
                    </a>
                    <a href="{{ route('admin.games.edit', $game) }}" class="btn-card btn-edit">
                        <i class="fas fa-edit"></i> Sửa
                    </a>
                    <button type="button" class="btn-card btn-delete" onclick="openDeleteModal({{ $game->id }}, '{{ $game->name }}')">
                        <i class="fas fa-trash"></i> Xóa
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($games->hasPages())
        <div class="pagination-wrapper">
            {{ $games->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-gamepad"></i>
            </div>
            <h3 class="empty-title">Chưa có game nào</h3>
            <p class="empty-text">Thêm game đầu tiên để bắt đầu!</p>
            <a href="{{ route('admin.games.create') }}" class="btn-neon">
                <i class="fas fa-plus"></i>
                <span>Thêm Game Mới</span>
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<div class="custom-modal-overlay" id="deleteModal">
    <div class="custom-modal">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">
                <i class="fas fa-exclamation-triangle"></i>
                Xác nhận xóa
            </h5>
            <button type="button" class="custom-modal-close" onclick="closeDeleteModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="custom-modal-body">
            <p>Bạn có chắc chắn muốn xóa game <strong id="deleteGameName"></strong>?</p>
            <div class="warning-box">
                <i class="fas fa-info-circle"></i>
                <span>Hành động này không thể hoàn tác. Tất cả dữ liệu liên quan sẽ bị xóa.</span>
            </div>
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="btn-modal-cancel" onclick="closeDeleteModal()">Hủy</button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modal-delete">
                    <i class="fas fa-trash"></i> Xóa game
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openDeleteModal(gameId, gameName) {
    document.getElementById('deleteGameName').textContent = gameName;
    document.getElementById('deleteForm').action = '/admin/games/' + gameId;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}

// Close modal on overlay click
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endpush
