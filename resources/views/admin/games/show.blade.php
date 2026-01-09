@extends('layouts.app')

@section('title', 'Chi tiết Game')

@push('styles')
<style>
    /* Modern Game Detail Styles */
    .game-detail-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .game-detail-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="%23ffffff06" points="0,1000 1000,0 1000,1000"/></svg>');
        pointer-events: none;
    }

    .modern-detail-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
        animation: slideInDown 0.6s ease-out;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modern-detail-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
        background-size: 300% 100%;
        animation: gradientShift 3s ease infinite;
    }

    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .modern-detail-title {
        font-weight: 700;
        font-size: 2rem;
        color: #2d3748;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .modern-action-group {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .modern-action-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
        text-decoration: none;
    }

    .action-back {
        background: #f7fafc;
        color: #4a5568;
        border-color: #e2e8f0;
    }

    .action-edit {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        color: white;
    }

    .action-delete {
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        color: white;
    }

    .modern-action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .action-back:hover {
        background: #edf2f7;
        color: #2d3748;
    }

    .action-edit:hover, .action-delete:hover {
        color: white;
    }

    .modern-banner-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        position: relative;
        animation: fadeInUp 0.8s ease-out;
        z-index: 1;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .banner-container {
        position: relative;
        overflow: hidden;
        border-radius: 20px 20px 0 0;
    }

    .game-banner-img {
        height: 350px;
        object-fit: cover;
        width: 100%;
        transition: transform 0.5s ease;
    }

    .modern-banner-card:hover .game-banner-img {
        transform: scale(1.02);
    }

    .banner-overlay {
        position: absolute;
        top: 0;
        right: 0;
        padding: 1.5rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .modern-status-badge {
        padding: 0.75rem 1.25rem;
        border-radius: 25px;
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .status-active {
        background: rgba(72, 187, 120, 0.9);
        color: white;
        border-color: rgba(255, 255, 255, 0.5);
    }

    .status-inactive {
        background: rgba(113, 128, 150, 0.9);
        color: white;
        border-color: rgba(255, 255, 255, 0.5);
    }

    .status-esports {
        background: rgba(255, 193, 7, 0.9);
        color: #1a202c;
        border-color: rgba(255, 255, 255, 0.7);
    }

    .modern-info-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: visible;
        animation: fadeInUp 0.8s ease-out;
        animation-fill-mode: both;
        z-index: 2;
    }

    .modern-info-card:nth-child(1) { animation-delay: 0.1s; }
    .modern-info-card:nth-child(2) { animation-delay: 0.2s; }
    .modern-info-card:nth-child(3) { animation-delay: 0.3s; }

    .modern-card-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(226, 232, 240, 0.5);
    }

    .card-icon {
        width: 50px;
        height: 50px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.5rem;
        color: white;
    }

    .icon-primary { background: linear-gradient(135deg, #667eea, #764ba2); }
    .icon-success { background: linear-gradient(135deg, #48bb78, #38a169); }
    .icon-warning { background: linear-gradient(135deg, #ed8936, #dd6b20); }
    .icon-info { background: linear-gradient(135deg, #4299e1, #3182ce); }

    .card-title {
        font-weight: 700;
        font-size: 1.25rem;
        color: #2d3748;
        margin: 0;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: rgba(247, 250, 252, 0.7);
        border-radius: 12px;
        border: 1px solid rgba(226, 232, 240, 0.5);
        transition: all 0.3s ease;
    }

    .info-item:hover {
        background: rgba(237, 242, 247, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.1rem;
        color: white;
    }

    .info-content h6 {
        font-weight: 600;
        color: #4a5568;
        margin: 0;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .info-content p {
        font-weight: 600;
        color: #2d3748;
        margin: 0.25rem 0 0;
        font-size: 1rem;
    }

    .game-description {
        background: rgba(247, 250, 252, 0.7);
        border-radius: 16px;
        padding: 1.5rem;
        border-left: 4px solid #4299e1;
        font-size: 1rem;
        line-height: 1.6;
        color: #4a5568;
    }

    .platforms-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .platform-item {
        text-align: center;
        padding: 1rem;
        background: rgba(247, 250, 252, 0.7);
        border-radius: 12px;
        border: 1px solid rgba(226, 232, 240, 0.5);
        transition: all 0.3s ease;
    }

    .platform-item:hover {
        background: rgba(237, 242, 247, 0.9);
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .platform-item i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: #4299e1;
    }

    .modes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .mode-item {
        text-align: center;
        padding: 1rem;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        border-radius: 12px;
        border: 1px solid rgba(102, 126, 234, 0.2);
        transition: all 0.3s ease;
    }

    .mode-item:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2));
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.15);
    }

    /* Modern Modal */
    .modern-modal .modal-content {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .modern-modal-header {
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        color: white;
        border-radius: 20px 20px 0 0;
        border: none;
        padding: 1.5rem;
    }

    .modern-modal-title {
        font-weight: 700;
        margin: 0;
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .game-detail-container {
            padding: 1rem 0;
        }

        .modern-detail-header {
            padding: 1.5rem;
            margin: 0 1rem 1.5rem;
        }

        .modern-detail-title {
            font-size: 1.5rem;
        }

        .modern-action-group {
            flex-direction: column;
            width: 100%;
        }

        .modern-action-btn {
            width: 100%;
            justify-content: center;
        }

        .modern-info-card {
            margin: 0 1rem 1.5rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .platforms-grid {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        }

        .modes-grid {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }
    }

    /* Modern notification system */
    .modern-notifications-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        max-width: 400px;
        pointer-events: none;
    }

    .modern-notification {
        display: flex;
        align-items: flex-start;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transform: translateX(400px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: auto;
        max-width: 400px;
    }

    .modern-notification.show {
        transform: translateX(0);
        opacity: 1;
    }

    .modern-notification.hide {
        transform: translateX(400px);
        opacity: 0;
    }

    .notification-success { border-left: 4px solid #48bb78; }
    .notification-error { border-left: 4px solid #f56565; }
    .notification-warning { border-left: 4px solid #ed8936; }
    .notification-info { border-left: 4px solid #4299e1; }

    /* Additional styles for show page */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-top: 1rem;
    }

    .stat-item {
        text-align: center;
        padding: 1.5rem 1rem;
        background: linear-gradient(135deg, rgba(66, 153, 225, 0.1), rgba(159, 122, 234, 0.1));
        border-radius: 16px;
        border: 1px solid rgba(66, 153, 225, 0.2);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .stat-item:hover {
        background: linear-gradient(135deg, rgba(66, 153, 225, 0.2), rgba(159, 122, 234, 0.2));
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(66, 153, 225, 0.15);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4299e1, #9f7aea);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: white;
        font-size: 1.3rem;
    }

    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2d3748;
        margin: 0;
    }

    .stat-label {
        color: #718096;
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0.5rem 0 0;
    }

    .management-info .info-item {
        margin-bottom: 0;
        height: 100%;
    }

    .management-info .info-item:last-child {
        margin-bottom: 0;
    }

    .game-logo-preview {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid rgba(226, 232, 240, 0.5);
    }

    .game-logo-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Loading animation */
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    .loading {
        animation: pulse 1.5s ease-in-out infinite;
    }
</style>
@endpush

@section('content')
<div class="game-detail-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Modern Header -->
                <div class="modern-detail-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <h1 class="modern-detail-title">
                            <i class="fas fa-gamepad text-primary"></i>
                            Chi tiết Game
                        </h1>
                        <div class="modern-action-group">
                            <a href="{{ route('admin.games.index') }}" class="modern-action-btn action-back">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                            <a href="{{ route('admin.games.edit', $game->id) }}" class="modern-action-btn action-edit">
                                <i class="fas fa-edit me-2"></i>Chỉnh sửa
                            </a>
                            <button class="modern-action-btn action-delete" id="delete-game-btn" data-game-id="{{ $game->id }}" data-game-name="{{ $game->name }}">
                                <i class="fas fa-trash me-2"></i>Xóa
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Game Banner -->
                <div class="modern-banner-card">
                    <div class="banner-container">
                        <img src="{{ $game->banner_url }}" class="game-banner-img" alt="{{ $game->name }} Banner">
                        
                        <!-- Status Overlay -->
                        <div class="banner-overlay">
                            <span class="modern-status-badge {{ $game->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                {{ $game->status_label }}
                            </span>
                            @if($game->esport_support)
                            <span class="modern-status-badge status-esports">
                                <i class="fas fa-trophy me-1"></i>Esports
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Clear spacing before content -->
                <div style="height: 20px;"></div>

                <!-- First Row: Main Info + Statistics -->
                <div class="row">
                    <!-- Left Column: Basic Information -->
                    <div class="col-lg-8">
                        <div class="modern-info-card">
                            <div class="modern-card-header">
                                <div class="card-icon icon-primary">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <h3 class="card-title">Thông tin cơ bản</h3>
                            </div>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-icon icon-primary">
                                        <i class="fas fa-gamepad"></i>
                                    </div>
                                    <div class="info-content">
                                        <h6>Tên Game</h6>
                                        <p>{{ $game->name }}</p>
                                    </div>
                                </div>

                                @if($game->genre)
                                <div class="info-item">
                                    <div class="info-icon icon-info">
                                        <i class="fas fa-tags"></i>
                                    </div>
                                    <div class="info-content">
                                        <h6>Thể loại</h6>
                                        <p>{{ $game->genre }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($game->publisher)
                                <div class="info-item">
                                    <div class="info-icon icon-success">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div class="info-content">
                                        <h6>Nhà phát hành</h6>
                                        <p>{{ $game->publisher }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($game->release_date)
                                <div class="info-item">
                                    <div class="info-icon icon-warning">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="info-content">
                                        <h6>Ngày phát hành</h6>
                                        <p>{{ $game->release_date->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($game->team_size)
                                <div class="info-item">
                                    <div class="info-icon icon-info">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="info-content">
                                        <h6>Đội hình</h6>
                                        <p>{{ $game->team_size }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($game->official_website)
                                <div class="info-item">
                                    <div class="info-icon icon-primary">
                                        <i class="fas fa-globe"></i>
                                    </div>
                                    <div class="info-content">
                                        <h6>Website chính thức</h6>
                                        <p><a href="{{ $game->official_website }}" target="_blank" class="text-decoration-none">Truy cập</a></p>
                                    </div>
                                </div>
                                @endif
                            </div>

                            @if($game->description)
                            <div class="game-description mt-3">
                                <h6 class="text-primary mb-2"><i class="fas fa-align-left me-2"></i>Mô tả</h6>
                                {{ $game->description }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right Column: Statistics -->
                    <div class="col-lg-4">
                        <div class="modern-info-card">
                            <div class="modern-card-header">
                                <div class="card-icon icon-info">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h3 class="card-title">Thống kê</h3>
                            </div>
                            
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h4 class="stat-number">-</h4>
                                        <p class="stat-label">Lượt xem</p>
                                    </div>
                                </div>
                                
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h4 class="stat-number">-</h4>
                                        <p class="stat-label">Yêu thích</p>
                                    </div>
                                </div>
                                
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h4 class="stat-number">{{ $game->teams_count ?? 0 }}</h4>
                                        <p class="stat-label">Đội tham gia</p>
                                    </div>
                                </div>
                                
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h4 class="stat-number">{{ $game->tournaments_count ?? 0 }}</h4>
                                        <p class="stat-label">Giải đấu</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Second Row: Management Info (Full Width) -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="modern-info-card">
                            <div class="modern-card-header">
                                <div class="card-icon icon-warning">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <h3 class="card-title">Thông tin quản lý</h3>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <div class="info-item">
                                        <div class="info-icon icon-success">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                        <div class="info-content">
                                            <h6>Người tạo</h6>
                                            <p>{{ $game->creator ? $game->creator->name : 'N/A' }}</p>
                                            <small class="text-muted">{{ $game->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>
                                </div>

                                @if($game->updater && $game->updated_at != $game->created_at)
                                <div class="col-lg-3 col-md-6">
                                    <div class="info-item">
                                        <div class="info-icon icon-warning">
                                            <i class="fas fa-user-edit"></i>
                                        </div>
                                        <div class="info-content">
                                            <h6>Cập nhật lần cuối</h6>
                                            <p>{{ $game->updater->name }}</p>
                                            <small class="text-muted">{{ $game->updated_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="col-lg-3 col-md-6">
                                    <div class="info-item">
                                        <div class="info-icon icon-info">
                                            <i class="fas fa-hashtag"></i>
                                        </div>
                                        <div class="info-content">
                                            <h6>ID Game</h6>
                                            <p><code>#{{ $game->id }}</code></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="info-item">
                                        <div class="info-icon icon-primary">
                                            <i class="fas fa-image"></i>
                                        </div>
                                        <div class="info-content">
                                            <h6>Logo</h6>
                                            @if($game->logo_url)
                                            <div class="game-logo-preview">
                                                <img src="{{ $game->logo_url }}" alt="{{ $game->name }} Logo">
                                            </div>
                                            @else
                                            <p class="text-muted">Chưa có logo</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Third Row: Game Modes, Platforms, Esports Info -->
                <div class="row mt-3">
                    <!-- Game Modes -->
                    @if($game->game_modes && count($game->game_modes) > 0)
                    <div class="col-lg-4">
                        <div class="modern-info-card h-100">
                            <div class="modern-card-header">
                                <div class="card-icon icon-warning">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <h3 class="card-title">Chế độ chơi</h3>
                            </div>
                            <div class="modes-grid">
                                @foreach($game->game_modes as $mode)
                                <div class="mode-item">
                                    <i class="fas fa-play-circle text-primary mb-2" style="font-size: 1.5rem;"></i>
                                    <div class="fw-semibold">{{ $mode }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Platforms -->
                    @if($game->platforms && count($game->platforms) > 0)
                    <div class="col-lg-4">
                        <div class="modern-info-card h-100">
                            <div class="modern-card-header">
                                <div class="card-icon icon-info">
                                    <i class="fas fa-desktop"></i>
                                </div>
                                <h3 class="card-title">Nền tảng</h3>
                            </div>
                            <div class="platforms-grid">
                                @foreach($game->platforms as $platform)
                                <div class="platform-item">
                                    @switch($platform)
                                        @case('PC')
                                            <i class="fas fa-desktop"></i>
                                        @break
                                        @case('Console')
                                            <i class="fas fa-gamepad"></i>
                                        @break
                                        @case('Mobile')
                                            <i class="fas fa-mobile-alt"></i>
                                        @break
                                        @default
                                            <i class="fas fa-laptop"></i>
                                    @endswitch
                                    <div class="fw-semibold mt-1">{{ $platform }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Esports Info -->
                    @if($game->esport_support)
                    <div class="col-lg-4">
                        <div class="modern-info-card h-100">
                            <div class="modern-card-header">
                                <div class="card-icon icon-success">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <h3 class="card-title">Thông tin Esports</h3>
                            </div>
                            
                            <div class="info-grid">
                                @if($game->competition_formats && count($game->competition_formats) > 0)
                                <div class="info-item">
                                    <div class="info-icon icon-warning">
                                        <i class="fas fa-medal"></i>
                                    </div>
                                    <div class="info-content">
                                        <h6>Hình thức thi đấu</h6>
                                        <p>{{ implode(', ', $game->competition_formats) }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade modern-modal" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modern-modal-header">
                <h5 class="modern-modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Xác nhận xóa
                </h5>
            </div>
            <div class="modal-body p-4">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <p class="mb-3 lead">Bạn có chắc chắn muốn xóa game <strong id="deleteGameName"></strong>?</p>
                    <p class="text-warning small">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Hành động này không thể hoàn tác. Game sẽ bị xóa vĩnh viễn khỏi hệ thống.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Hủy
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-2"></i>Xóa Game
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modern Notifications Container -->
<div class="modern-notifications-container"></div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Modern notification functions
    function showModernNotification(message, type = 'info', duration = 4000) {
        const container = $('.modern-notifications-container');
        
        const notification = $(`
            <div class="modern-notification notification-${type}">
                <div class="d-flex align-items-start">
                    <div class="notification-icon me-3">
                        <i class="fas ${getNotificationIcon(type)}"></i>
                    </div>
                    <div class="notification-content flex-grow-1">
                        <div class="notification-message">${message}</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-2" onclick="closeNotification(this)"></button>
                </div>
            </div>
        `);
        
        container.append(notification);
        
        // Show notification
        setTimeout(() => notification.addClass('show'), 100);
        
        // Auto hide
        setTimeout(() => {
            closeNotification(notification.find('.btn-close')[0]);
        }, duration);
    }
    
    function closeNotification(element) {
        const notification = $(element).closest('.modern-notification');
        notification.removeClass('show').addClass('hide');
        setTimeout(() => notification.remove(), 300);
    }
    
    function getNotificationIcon(type) {
        const icons = {
            'success': 'fa-check-circle',
            'error': 'fa-times-circle',
            'warning': 'fa-exclamation-triangle',
            'info': 'fa-info-circle'
        };
        return icons[type] || icons['info'];
    }
    
    // Delete game functionality
    $('#delete-game-btn').click(function() {
        const gameName = $(this).data('game-name');
        $('#deleteGameName').text(gameName);
        $('#deleteModal').modal('show');
    });
    
    $('#confirmDeleteBtn').click(function() {
        const gameId = $('#delete-game-btn').data('game-id');
        const gameName = $('#delete-game-btn').data('game-name');
        const $btn = $(this);
        
        // Add loading state
        $btn.prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin me-2"></i>Đang xóa...');
        
        // Create form and submit
        const form = $('<form>', {
            method: 'POST',
            action: `{{ route('admin.games.index') }}/${gameId}`
        });
        
        form.append('@csrf');
        form.append('@method("DELETE")');
        
        $('body').append(form);
        
        // Simulate API call (replace with actual AJAX if needed)
        setTimeout(() => {
            form.submit();
        }, 1000);
    });
    
    // Smooth scroll for anchors
    $('a[href^="#"]').click(function(e) {
        e.preventDefault();
        const target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
        }
    });
    
    // Image loading error handling
    $('img').on('error', function() {
        $(this).attr('src', '/images/placeholder-game.jpg');
    });
    
    // Enhance tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endpush
