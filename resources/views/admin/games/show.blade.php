@extends('layouts.app')

@section('title', 'Chi tiết Game')

@push('styles')
<style>
    .game-detail-container { background: #000814; min-height: 100vh; }
    @media (max-width: 991.98px) { .game-detail-container { margin-left: 0 !important; width: 100% !important; } }
    
    .detail-hero { background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%); border: 1px solid rgba(0, 229, 255, 0.2); border-radius: 20px; padding: 1.5rem 2rem; position: relative; overflow: hidden; margin-bottom: 1.5rem; }
    .detail-hero::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, transparent, #00E5FF, transparent); }
    .hero-content { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
    .hero-left { display: flex; align-items: center; gap: 1rem; }
    .hero-icon { width: 60px; height: 60px; min-width: 60px; background: linear-gradient(135deg, #6366f1, #4f46e5); border-radius: 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 25px rgba(99, 102, 241, 0.3); }
    .hero-icon i { font-size: 1.5rem; color: white; }
    .hero-title { font-family: 'Rajdhani', sans-serif; font-size: 1.5rem; font-weight: 700; color: #00E5FF; margin: 0; }
    .hero-subtitle { color: #94a3b8; font-size: 0.9rem; margin: 0.25rem 0 0 0; font-family: 'Inter', sans-serif; }
    .hero-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; }
    
    .btn-neon { background: linear-gradient(135deg, #000055, #000077); color: #00E5FF; border: 1px solid rgba(0, 229, 255, 0.4); padding: 0.75rem 1.5rem; border-radius: 12px; font-size: 0.9rem; font-weight: 600; font-family: 'Inter', sans-serif; transition: all 0.3s ease; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
    .btn-neon:hover { background: rgba(0, 229, 255, 0.15); box-shadow: 0 0 25px rgba(0, 229, 255, 0.4); color: #FFFFFF; transform: translateY(-2px); }
    .btn-neon.btn-secondary { background: rgba(100, 116, 139, 0.2); color: #94a3b8; border-color: rgba(100, 116, 139, 0.3); }
    .btn-neon.btn-secondary:hover { background: rgba(100, 116, 139, 0.3); color: #FFFFFF; }
    .btn-neon.btn-primary { background: rgba(99, 102, 241, 0.2); color: #818cf8; border-color: rgba(99, 102, 241, 0.4); }
    .btn-neon.btn-primary:hover { background: rgba(99, 102, 241, 0.3); color: #FFFFFF; }
    .btn-neon.btn-danger { background: rgba(239, 68, 68, 0.2); color: #ef4444; border-color: rgba(239, 68, 68, 0.4); }
    .btn-neon.btn-danger:hover { background: rgba(239, 68, 68, 0.3); color: #FFFFFF; }
    
    .game-banner-card { background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%); border: 1px solid rgba(0, 229, 255, 0.15); border-radius: 20px; overflow: hidden; margin-bottom: 1.5rem; }
    .banner-wrapper { position: relative; height: 280px; overflow: hidden; }
    .banner-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .game-banner-card:hover .banner-img { transform: scale(1.02); }
    .banner-placeholder { width: 100%; height: 100%; background: linear-gradient(135deg, #1a237e 0%, #000055 100%); display: flex; align-items: center; justify-content: center; }
    .banner-placeholder i { font-size: 4rem; color: rgba(0, 229, 255, 0.3); }
    .banner-badges { position: absolute; top: 15px; right: 15px; display: flex; gap: 10px; flex-wrap: wrap; }
    .status-badge { padding: 0.5rem 1rem; border-radius: 25px; font-size: 0.75rem; font-weight: 700; font-family: 'Inter', sans-serif; text-transform: uppercase; letter-spacing: 0.05em; }
    .status-active { background: rgba(34, 197, 94, 0.9); color: #FFFFFF; box-shadow: 0 0 15px rgba(34, 197, 94, 0.5); }
    .status-inactive { background: rgba(100, 116, 139, 0.9); color: #FFFFFF; }
    .status-esport { background: rgba(245, 158, 11, 0.9); color: #000; display: flex; align-items: center; gap: 5px; }
</style>
@endpush

@push('styles')
<style>
    .detail-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
    @media (max-width: 992px) { .detail-grid { grid-template-columns: 1fr; } }
    
    .detail-card { background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%); border: 1px solid rgba(0, 229, 255, 0.15); border-radius: 20px; overflow: hidden; transition: all 0.3s ease; }
    .detail-card:hover { border-color: rgba(0, 229, 255, 0.3); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3); }
    .detail-card.full-width { margin-bottom: 1.5rem; }
    
    .card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0, 229, 255, 0.1); display: flex; align-items: center; gap: 1rem; background: linear-gradient(135deg, rgba(0, 0, 85, 0.3), rgba(0, 229, 255, 0.05)); }
    .card-icon { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: white; flex-shrink: 0; }
    .card-icon.icon-primary { background: linear-gradient(135deg, #6366f1, #4f46e5); }
    .card-icon.icon-info { background: linear-gradient(135deg, #00E5FF, #0891b2); }
    .card-icon.icon-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .card-icon.icon-success { background: linear-gradient(135deg, #22c55e, #16a34a); }
    .card-title { font-family: 'Rajdhani', sans-serif; font-size: 1.15rem; font-weight: 700; color: #FFFFFF; margin: 0; }
    .card-body { padding: 1.5rem; }
    
    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
    @media (max-width: 768px) { .info-grid { grid-template-columns: 1fr; } }
    
    .info-item { display: flex; align-items: flex-start; gap: 12px; padding: 1rem; background: rgba(0, 229, 255, 0.03); border-radius: 12px; border: 1px solid rgba(0, 229, 255, 0.08); transition: all 0.3s ease; }
    .info-item:hover { background: rgba(0, 229, 255, 0.06); border-color: rgba(0, 229, 255, 0.15); }
    .info-icon { width: 36px; height: 36px; background: linear-gradient(135deg, #000055, #000077); border: 1px solid rgba(0, 229, 255, 0.3); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #00E5FF; font-size: 0.9rem; flex-shrink: 0; }
    .info-content { display: flex; flex-direction: column; gap: 4px; min-width: 0; }
    .info-label { font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; font-family: 'Inter', sans-serif; font-weight: 600; }
    .info-value { font-size: 0.95rem; color: #FFFFFF; font-family: 'Inter', sans-serif; font-weight: 500; }
    .info-meta { font-size: 0.8rem; color: #64748b; }
    .link-cyan { color: #00E5FF; text-decoration: none; transition: all 0.3s ease; }
    .link-cyan:hover { color: #FFFFFF; text-shadow: 0 0 10px rgba(0, 229, 255, 0.5); }
    
    .description-box { margin-top: 1.5rem; padding: 1.25rem; background: rgba(0, 229, 255, 0.03); border-radius: 12px; border-left: 4px solid #00E5FF; }
    .description-header { display: flex; align-items: center; gap: 8px; color: #00E5FF; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.75rem; font-family: 'Inter', sans-serif; }
    .description-text { color: #94a3b8; font-size: 0.9rem; line-height: 1.6; margin: 0; font-family: 'Inter', sans-serif; }
</style>
@endpush

@push('styles')
<style>
    .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
    .stat-item { text-align: center; padding: 1.25rem 1rem; background: rgba(0, 229, 255, 0.03); border-radius: 12px; border: 1px solid rgba(0, 229, 255, 0.08); transition: all 0.3s ease; }
    .stat-item:hover { background: rgba(0, 229, 255, 0.06); border-color: rgba(0, 229, 255, 0.2); transform: translateY(-3px); }
    .stat-icon { width: 45px; height: 45px; background: linear-gradient(135deg, #000055, #000077); border: 1px solid rgba(0, 229, 255, 0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; color: #00E5FF; font-size: 1rem; }
    .stat-info { display: flex; flex-direction: column; gap: 4px; }
    .stat-number { font-family: 'Rajdhani', sans-serif; font-size: 1.5rem; font-weight: 700; color: #00E5FF; }
    .stat-label { font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; font-family: 'Inter', sans-serif; }
    
    .management-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
    @media (max-width: 1200px) { .management-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .management-grid { grid-template-columns: 1fr; } }
    
    .code-badge { background: rgba(0, 229, 255, 0.1); color: #00E5FF; padding: 0.25rem 0.5rem; border-radius: 6px; font-family: monospace; font-size: 0.9rem; }
    .logo-preview { width: 50px; height: 50px; border-radius: 10px; overflow: hidden; border: 2px solid rgba(0, 229, 255, 0.3); margin-top: 0.5rem; }
    .logo-preview img { width: 100%; height: 100%; object-fit: cover; }
    
    .additional-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
    @media (max-width: 992px) { .additional-grid { grid-template-columns: 1fr; } }
    
    .tags-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 0.75rem; }
    .tag-item { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 0.75rem 1rem; background: linear-gradient(135deg, rgba(0, 0, 85, 0.3), rgba(0, 229, 255, 0.05)); border: 1px solid rgba(0, 229, 255, 0.2); border-radius: 10px; color: #00E5FF; font-size: 0.85rem; font-weight: 500; font-family: 'Inter', sans-serif; transition: all 0.3s ease; }
    .tag-item:hover { background: rgba(0, 229, 255, 0.1); border-color: rgba(0, 229, 255, 0.4); transform: translateY(-2px); }
    .tag-item.platform i { font-size: 1.1rem; }
    .text-muted { color: #64748b !important; }
</style>
@endpush

@push('styles')
<style>
    .custom-modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(4px); z-index: 99999; display: none; align-items: center; justify-content: center; padding: 1rem; }
    .custom-modal-overlay.active { display: flex; }
    .custom-modal { background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%); border: 1px solid rgba(0, 229, 255, 0.3); border-radius: 20px; width: 100%; max-width: 450px; overflow: hidden; animation: modalSlideIn 0.3s ease; }
    @keyframes modalSlideIn { from { opacity: 0; transform: translateY(-20px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .custom-modal-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0, 229, 255, 0.1); display: flex; align-items: center; justify-content: space-between; }
    .custom-modal-title { color: #FFFFFF; font-family: 'Rajdhani', sans-serif; font-size: 1.1rem; font-weight: 600; display: flex; align-items: center; gap: 10px; margin: 0; }
    .custom-modal-title i { color: #f59e0b; }
    .custom-modal-close { background: rgba(255, 255, 255, 0.1); border: none; color: #94a3b8; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; }
    .custom-modal-close:hover { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
    .custom-modal-body { padding: 1.5rem; color: #94a3b8; font-family: 'Inter', sans-serif; }
    .custom-modal-body strong { color: #00E5FF; }
    .warning-box { background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 10px; padding: 0.75rem 1rem; margin-top: 1rem; display: flex; align-items: flex-start; gap: 10px; font-size: 0.85rem; }
    .warning-box i { color: #f59e0b; margin-top: 2px; }
    .warning-box span { color: #fbbf24; }
    .custom-modal-footer { padding: 1rem 1.5rem; border-top: 1px solid rgba(0, 229, 255, 0.1); display: flex; gap: 0.75rem; justify-content: flex-end; }
    .btn-modal-cancel { background: rgba(100, 116, 139, 0.2); color: #94a3b8; border: 1px solid rgba(100, 116, 139, 0.3); padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; transition: all 0.3s ease; }
    .btn-modal-cancel:hover { background: rgba(100, 116, 139, 0.3); color: #FFFFFF; }
    .btn-modal-delete { background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.4); padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px; }
    .btn-modal-delete:hover { background: rgba(239, 68, 68, 0.3); color: #FFFFFF; }
    
    @media (max-width: 768px) {
        .hero-content { flex-direction: column; align-items: flex-start; }
        .hero-actions { width: 100%; }
        .btn-neon { flex: 1; justify-content: center; }
        .banner-wrapper { height: 200px; }
        .stats-grid { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush


@section('content')
<div class="game-detail-container">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="detail-hero">
            <div class="hero-content">
                <div class="hero-left">
                    <div class="hero-icon"><i class="fas fa-gamepad"></i></div>
                    <div>
                        <h1 class="hero-title">Chi tiết Game</h1>
                        <p class="hero-subtitle">Xem thông tin chi tiết game</p>
                    </div>
                </div>
                <div class="hero-actions">
                    <a href="{{ route('admin.games.index') }}" class="btn-neon btn-secondary"><i class="fas fa-arrow-left"></i><span>Quay lại</span></a>
                    <a href="{{ route('admin.games.edit', $game->id) }}" class="btn-neon btn-primary"><i class="fas fa-edit"></i><span>Chỉnh sửa</span></a>
                    <button type="button" class="btn-neon btn-danger" onclick="openDeleteModal({{ $game->id }}, '{{ $game->name }}')"><i class="fas fa-trash"></i><span>Xóa</span></button>
                </div>
            </div>
        </div>

        <div class="game-banner-card">
            <div class="banner-wrapper">
                @if($game->banner)
                <img src="{{ $game->banner }}" class="banner-img" alt="{{ $game->name }} Banner">
                @else
                <div class="banner-placeholder"><i class="fas fa-image"></i></div>
                @endif
                <div class="banner-badges">
                    <span class="status-badge {{ $game->status === 'active' ? 'status-active' : 'status-inactive' }}">{{ $game->status_label ?? ($game->status === 'active' ? 'Hoạt động' : 'Ngừng') }}</span>
                    @if($game->esport_support)
                    <span class="status-badge status-esport"><i class="fas fa-trophy"></i> Esports</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="detail-grid">
            <div class="detail-card">
                <div class="card-header">
                    <div class="card-icon icon-primary"><i class="fas fa-info-circle"></i></div>
                    <h3 class="card-title">Thông tin cơ bản</h3>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-gamepad"></i></div>
                            <div class="info-content"><span class="info-label">Tên Game</span><span class="info-value">{{ $game->name }}</span></div>
                        </div>
                        @if($game->genre)
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-tags"></i></div>
                            <div class="info-content"><span class="info-label">Thể loại</span><span class="info-value">{{ $game->genre }}</span></div>
                        </div>
                        @endif
                        @if($game->publisher)
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-building"></i></div>
                            <div class="info-content"><span class="info-label">Nhà phát hành</span><span class="info-value">{{ $game->publisher }}</span></div>
                        </div>
                        @endif
                        @if($game->release_date)
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                            <div class="info-content"><span class="info-label">Ngày phát hành</span><span class="info-value">{{ $game->release_date->format('d/m/Y') }}</span></div>
                        </div>
                        @endif
                        @if($game->team_size)
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-users"></i></div>
                            <div class="info-content"><span class="info-label">Đội hình</span><span class="info-value">{{ $game->team_size }}</span></div>
                        </div>
                        @endif
                        @if($game->official_website)
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-globe"></i></div>
                            <div class="info-content"><span class="info-label">Website chính thức</span><span class="info-value"><a href="{{ $game->official_website }}" target="_blank" class="link-cyan">Truy cập <i class="fas fa-external-link-alt"></i></a></span></div>
                        </div>
                        @endif
                    </div>
                    @if($game->description)
                    <div class="description-box">
                        <div class="description-header"><i class="fas fa-align-left"></i><span>Mô tả</span></div>
                        <p class="description-text">{{ $game->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="detail-card">
                <div class="card-header">
                    <div class="card-icon icon-info"><i class="fas fa-chart-line"></i></div>
                    <h3 class="card-title">Thống kê</h3>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <div class="stat-item"><div class="stat-icon"><i class="fas fa-eye"></i></div><div class="stat-info"><span class="stat-number">-</span><span class="stat-label">Lượt xem</span></div></div>
                        <div class="stat-item"><div class="stat-icon"><i class="fas fa-heart"></i></div><div class="stat-info"><span class="stat-number">-</span><span class="stat-label">Yêu thích</span></div></div>
                        <div class="stat-item"><div class="stat-icon"><i class="fas fa-users"></i></div><div class="stat-info"><span class="stat-number">{{ $game->teams_count ?? 0 }}</span><span class="stat-label">Đội tham gia</span></div></div>
                        <div class="stat-item"><div class="stat-icon"><i class="fas fa-trophy"></i></div><div class="stat-info"><span class="stat-number">{{ $game->tournaments_count ?? 0 }}</span><span class="stat-label">Giải đấu</span></div></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-card full-width">
            <div class="card-header">
                <div class="card-icon icon-warning"><i class="fas fa-cogs"></i></div>
                <h3 class="card-title">Thông tin quản lý</h3>
            </div>
            <div class="card-body">
                <div class="management-grid">
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-user-plus"></i></div>
                        <div class="info-content"><span class="info-label">Người tạo</span><span class="info-value">{{ $game->creator ? $game->creator->name : 'N/A' }}</span><span class="info-meta">{{ $game->created_at->format('d/m/Y H:i') }}</span></div>
                    </div>
                    @if($game->updater && $game->updated_at != $game->created_at)
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-user-edit"></i></div>
                        <div class="info-content"><span class="info-label">Cập nhật lần cuối</span><span class="info-value">{{ $game->updater->name }}</span><span class="info-meta">{{ $game->updated_at->format('d/m/Y H:i') }}</span></div>
                    </div>
                    @endif
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-hashtag"></i></div>
                        <div class="info-content"><span class="info-label">ID Game</span><span class="info-value"><code class="code-badge">#{{ $game->id }}</code></span></div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-image"></i></div>
                        <div class="info-content">
                            <span class="info-label">Logo</span>
                            @if($game->logo)
                            <div class="logo-preview"><img src="{{ $game->logo }}" alt="{{ $game->name }} Logo"></div>
                            @else
                            <span class="info-value text-muted">Chưa có logo</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="additional-grid">
            @if($game->game_modes && count($game->game_modes) > 0)
            <div class="detail-card">
                <div class="card-header"><div class="card-icon icon-warning"><i class="fas fa-cog"></i></div><h3 class="card-title">Chế độ chơi</h3></div>
                <div class="card-body">
                    <div class="tags-grid">
                        @foreach($game->game_modes as $mode)
                        <div class="tag-item"><i class="fas fa-play-circle"></i><span>{{ $mode }}</span></div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if($game->platforms && count($game->platforms) > 0)
            <div class="detail-card">
                <div class="card-header"><div class="card-icon icon-info"><i class="fas fa-desktop"></i></div><h3 class="card-title">Nền tảng</h3></div>
                <div class="card-body">
                    <div class="tags-grid">
                        @foreach($game->platforms as $platform)
                        <div class="tag-item platform">
                            @switch($platform)
                                @case('PC') <i class="fas fa-desktop"></i> @break
                                @case('Console') <i class="fas fa-gamepad"></i> @break
                                @case('Mobile') <i class="fas fa-mobile-alt"></i> @break
                                @default <i class="fas fa-laptop"></i>
                            @endswitch
                            <span>{{ $platform }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if($game->esport_support)
            <div class="detail-card">
                <div class="card-header"><div class="card-icon icon-success"><i class="fas fa-trophy"></i></div><h3 class="card-title">Thông tin Esports</h3></div>
                <div class="card-body">
                    @if($game->competition_formats && count($game->competition_formats) > 0)
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-medal"></i></div>
                        <div class="info-content"><span class="info-label">Hình thức thi đấu</span><span class="info-value">{{ implode(', ', $game->competition_formats) }}</span></div>
                    </div>
                    @else
                    <p class="text-muted">Hỗ trợ Esports</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="custom-modal-overlay" id="deleteModal">
    <div class="custom-modal">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title"><i class="fas fa-exclamation-triangle"></i>Xác nhận xóa</h5>
            <button type="button" class="custom-modal-close" onclick="closeDeleteModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="custom-modal-body">
            <p>Bạn có chắc chắn muốn xóa game <strong id="deleteGameName"></strong>?</p>
            <div class="warning-box"><i class="fas fa-info-circle"></i><span>Hành động này không thể hoàn tác. Tất cả dữ liệu liên quan sẽ bị xóa.</span></div>
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="btn-modal-cancel" onclick="closeDeleteModal()">Hủy</button>
            <form id="deleteForm" method="POST" style="display: inline;">@csrf @method('DELETE')<button type="submit" class="btn-modal-delete"><i class="fas fa-trash"></i> Xóa game</button></form>
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
function closeDeleteModal() { document.getElementById('deleteModal').classList.remove('active'); }
document.getElementById('deleteModal').addEventListener('click', function(e) { if (e.target === this) closeDeleteModal(); });
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeDeleteModal(); });
</script>
@endpush
