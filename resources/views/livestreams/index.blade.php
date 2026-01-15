@extends('layouts.app')

@section('title', 'Live Stream')

@push('styles')
<style>
    .live-container { background: #000814; min-height: 100vh; padding-top: 80px; }
    .live-container .container { max-width: 1400px; margin: 0 auto; padding: 1.5rem; }

    .page-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .page-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 2.5rem;
        font-weight: 700;
        color: #FFFFFF;
        margin: 0 0 0.5rem 0;
    }

    .page-title i { color: #ef4444; animation: pulse 2s infinite; }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .page-subtitle { color: #94a3b8; font-size: 1.1rem; }

    .section-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #00E5FF;
        margin: 2rem 0 1rem 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title .live-dot {
        width: 12px;
        height: 12px;
        background: #ef4444;
        border-radius: 50%;
        animation: blink 1s infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    /* Featured Stream */
    .featured-stream {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 2px solid rgba(239, 68, 68, 0.4);
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .featured-badge {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .featured-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        padding: 1.5rem;
    }

    .featured-video {
        aspect-ratio: 16/9;
        background: #000;
        border-radius: 12px;
        overflow: hidden;
    }

    .featured-video iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .featured-info h2 {
        color: #FFFFFF;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 1rem 0;
    }

    .featured-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #94a3b8;
        font-size: 0.9rem;
    }

    .meta-item i { color: #00E5FF; }
    .meta-item i.fa-youtube { color: #ef4444; }
    .meta-item i.fa-facebook { color: #1877f2; }

    .featured-desc {
        color: #94a3b8;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .btn-watch {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        margin-top: 1rem;
        transition: all 0.3s ease;
    }

    .btn-watch:hover {
        box-shadow: 0 0 25px rgba(239, 68, 68, 0.5);
        transform: translateY(-2px);
        color: white;
    }

    /* Stream Grid */
    .stream-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .stream-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stream-card:hover {
        border-color: rgba(0, 229, 255, 0.4);
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .stream-card.live { border-color: rgba(239, 68, 68, 0.4); }

    .stream-thumb {
        position: relative;
        aspect-ratio: 16/9;
        background: linear-gradient(135deg, #1a1a2e, #16213e);
    }

    .stream-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .stream-thumb-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-size: 2rem;
    }

    .live-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #ef4444;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .live-badge .dot {
        width: 6px;
        height: 6px;
        background: white;
        border-radius: 50%;
        animation: blink 1s infinite;
    }

    .scheduled-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(245, 158, 11, 0.9);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .platform-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    .platform-badge i.fa-youtube { color: #ef4444; }
    .platform-badge i.fa-facebook { color: #1877f2; }

    .view-count {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .stream-info { padding: 1rem; }

    .stream-title {
        color: #FFFFFF;
        font-size: 1rem;
        font-weight: 600;
        margin: 0 0 0.5rem 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .stream-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #64748b;
        font-size: 0.8rem;
    }

    .stream-game {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .stream-game i { color: #00E5FF; }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #64748b;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    @media (max-width: 1024px) {
        .featured-content { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .page-title { font-size: 2rem; }
        .stream-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="live-container">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title"><i class="fas fa-broadcast-tower"></i> Live Stream</h1>
            <p class="page-subtitle">Xem trực tiếp các giải đấu và sự kiện esport</p>
        </div>

        @if($featured && $featured->isLive())
        <div class="featured-stream">
            <div class="featured-badge">
                <span class="live-dot" style="width: 8px; height: 8px; background: white; border-radius: 50%; animation: blink 1s infinite;"></span>
                Đang phát trực tiếp
            </div>
            <div class="featured-content">
                <div class="featured-video">
                    <iframe src="{{ $featured->embed_url }}" allowfullscreen allow="autoplay; encrypted-media"></iframe>
                </div>
                <div class="featured-info">
                    <h2>{{ $featured->title }}</h2>
                    <div class="featured-meta">
                        <span class="meta-item">
                            <i class="{{ $featured->getPlatformIcon() }}"></i>
                            {{ ucfirst($featured->platform) }}
                        </span>
                        @if($featured->game)
                        <span class="meta-item">
                            <i class="fas fa-gamepad"></i>
                            {{ $featured->game->name }}
                        </span>
                        @endif
                        <span class="meta-item">
                            <i class="fas fa-eye"></i>
                            {{ number_format($featured->view_count) }} lượt xem
                        </span>
                    </div>
                    @if($featured->description)
                    <p class="featured-desc">{{ Str::limit($featured->description, 200) }}</p>
                    @endif
                    <a href="{{ route('livestreams.show', $featured) }}" class="btn-watch">
                        <i class="fas fa-play"></i> Xem ngay
                    </a>
                </div>
            </div>
        </div>
        @endif

        @if($liveNow->count() > 0)
        <h2 class="section-title">
            <span class="live-dot"></span> Đang phát trực tiếp
        </h2>
        <div class="stream-grid">
            @foreach($liveNow as $stream)
            <a href="{{ route('livestreams.show', $stream) }}" class="stream-card live">
                <div class="stream-thumb">
                    @if($stream->thumbnail)
                    <img src="{{ Storage::url($stream->thumbnail) }}" alt="{{ $stream->title }}">
                    @else
                    <div class="stream-thumb-placeholder">
                        <i class="{{ $stream->getPlatformIcon() }}"></i>
                    </div>
                    @endif
                    <div class="live-badge"><span class="dot"></span> LIVE</div>
                    <div class="platform-badge"><i class="{{ $stream->getPlatformIcon() }}"></i></div>
                    <div class="view-count"><i class="fas fa-eye"></i> {{ number_format($stream->view_count) }}</div>
                </div>
                <div class="stream-info">
                    <h3 class="stream-title">{{ $stream->title }}</h3>
                    <div class="stream-meta">
                        @if($stream->game)
                        <span class="stream-game"><i class="fas fa-gamepad"></i> {{ $stream->game->name }}</span>
                        @else
                        <span></span>
                        @endif
                        <span>{{ $stream->started_at?->diffForHumans() }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif

        @if($upcoming->count() > 0)
        <h2 class="section-title"><i class="fas fa-clock"></i> Sắp diễn ra</h2>
        <div class="stream-grid">
            @foreach($upcoming as $stream)
            <a href="{{ route('livestreams.show', $stream) }}" class="stream-card">
                <div class="stream-thumb">
                    @if($stream->thumbnail)
                    <img src="{{ Storage::url($stream->thumbnail) }}" alt="{{ $stream->title }}">
                    @else
                    <div class="stream-thumb-placeholder">
                        <i class="{{ $stream->getPlatformIcon() }}"></i>
                    </div>
                    @endif
                    <div class="scheduled-badge">
                        <i class="fas fa-clock"></i> {{ $stream->scheduled_at?->format('d/m H:i') }}
                    </div>
                    <div class="platform-badge"><i class="{{ $stream->getPlatformIcon() }}"></i></div>
                </div>
                <div class="stream-info">
                    <h3 class="stream-title">{{ $stream->title }}</h3>
                    <div class="stream-meta">
                        @if($stream->game)
                        <span class="stream-game"><i class="fas fa-gamepad"></i> {{ $stream->game->name }}</span>
                        @else
                        <span></span>
                        @endif
                        <span>{{ $stream->scheduled_at?->diffForHumans() }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif

        @if($recent->count() > 0)
        <h2 class="section-title"><i class="fas fa-history"></i> Phát lại</h2>
        <div class="stream-grid">
            @foreach($recent as $stream)
            <a href="{{ route('livestreams.show', $stream) }}" class="stream-card">
                <div class="stream-thumb">
                    @if($stream->thumbnail)
                    <img src="{{ Storage::url($stream->thumbnail) }}" alt="{{ $stream->title }}">
                    @else
                    <div class="stream-thumb-placeholder">
                        <i class="{{ $stream->getPlatformIcon() }}"></i>
                    </div>
                    @endif
                    <div class="platform-badge"><i class="{{ $stream->getPlatformIcon() }}"></i></div>
                    <div class="view-count"><i class="fas fa-eye"></i> {{ number_format($stream->view_count) }}</div>
                </div>
                <div class="stream-info">
                    <h3 class="stream-title">{{ $stream->title }}</h3>
                    <div class="stream-meta">
                        @if($stream->game)
                        <span class="stream-game"><i class="fas fa-gamepad"></i> {{ $stream->game->name }}</span>
                        @else
                        <span></span>
                        @endif
                        <span>{{ $stream->ended_at?->diffForHumans() }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif

        @if($liveNow->count() == 0 && $upcoming->count() == 0 && $recent->count() == 0)
        <div class="empty-state">
            <i class="fas fa-video-slash"></i>
            <h3>Chưa có livestream nào</h3>
            <p>Hãy quay lại sau để xem các buổi phát sóng trực tiếp</p>
        </div>
        @endif
    </div>
</div>
@endsection
