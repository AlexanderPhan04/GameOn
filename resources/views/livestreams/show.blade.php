@extends('layouts.app')

@section('title', $livestream->title)

@push('styles')
<style>
    .live-detail-container { background: #000814; min-height: 100vh; padding-top: 80px; }
    .live-detail-container .container { max-width: 1400px; margin: 0 auto; padding: 1.5rem; }

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

    .video-section {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .video-wrapper {
        background: #000;
        border-radius: 16px;
        overflow: hidden;
        aspect-ratio: 16/9;
    }

    .video-wrapper iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .video-info {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
        height: fit-content;
    }

    .status-live {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ef4444;
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }

    .status-live .dot {
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
        animation: blink 1s infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    .status-scheduled {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .status-ended {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(107, 114, 128, 0.2);
        color: #9ca3af;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .video-title {
        color: #FFFFFF;
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0 0 1rem 0;
        line-height: 1.4;
    }

    .video-meta {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        margin-bottom: 1rem;
    }

    .meta-row {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #94a3b8;
        font-size: 0.9rem;
    }

    .meta-row i { color: #00E5FF; width: 16px; text-align: center; }
    .meta-row i.fa-youtube { color: #ef4444; }
    .meta-row i.fa-facebook { color: #1877f2; }

    .video-desc {
        color: #94a3b8;
        font-size: 0.9rem;
        line-height: 1.6;
    }

    .game-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(0, 229, 255, 0.1);
        color: #00E5FF;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-top: 1rem;
        text-decoration: none;
    }

    .tournament-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-top: 0.5rem;
        text-decoration: none;
    }

    /* Related Streams */
    .related-section {
        margin-top: 2rem;
    }

    .section-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #00E5FF;
        margin: 0 0 1rem 0;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
    }

    .related-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 12px;
        overflow: hidden;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .related-card:hover {
        border-color: rgba(0, 229, 255, 0.4);
        transform: translateY(-3px);
    }

    .related-thumb {
        aspect-ratio: 16/9;
        background: linear-gradient(135deg, #1a1a2e, #16213e);
        position: relative;
    }

    .related-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .related-thumb-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-size: 1.5rem;
    }

    .related-info { padding: 0.75rem; }

    .related-title {
        color: #FFFFFF;
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @media (max-width: 1024px) {
        .video-section { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .related-grid { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 480px) {
        .related-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="live-detail-container">
    <div class="container">
        <a href="{{ route('livestreams.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>

        <div class="video-section">
            <div class="video-wrapper">
                <iframe 
                    src="{{ $livestream->embed_url }}" 
                    allowfullscreen 
                    allow="autoplay; encrypted-media; picture-in-picture"
                ></iframe>
            </div>

            <div class="video-info">
                @if($livestream->isLive())
                <div class="status-live"><span class="dot"></span> Đang phát trực tiếp</div>
                @elseif($livestream->isScheduled())
                <div class="status-scheduled"><i class="fas fa-clock"></i> Sắp diễn ra</div>
                @else
                <div class="status-ended"><i class="fas fa-check-circle"></i> Đã kết thúc</div>
                @endif

                <h1 class="video-title">{{ $livestream->title }}</h1>

                <div class="video-meta">
                    <div class="meta-row">
                        <i class="{{ $livestream->getPlatformIcon() }}"></i>
                        <span>{{ ucfirst($livestream->platform) }}</span>
                    </div>
                    <div class="meta-row">
                        <i class="fas fa-eye"></i>
                        <span>{{ number_format($livestream->view_count) }} lượt xem</span>
                    </div>
                    @if($livestream->isLive() && $livestream->started_at)
                    <div class="meta-row">
                        <i class="fas fa-clock"></i>
                        <span>Bắt đầu {{ $livestream->started_at->diffForHumans() }}</span>
                    </div>
                    @elseif($livestream->isScheduled() && $livestream->scheduled_at)
                    <div class="meta-row">
                        <i class="fas fa-calendar"></i>
                        <span>{{ $livestream->scheduled_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @elseif($livestream->ended_at)
                    <div class="meta-row">
                        <i class="fas fa-calendar-check"></i>
                        <span>Kết thúc {{ $livestream->ended_at->diffForHumans() }}</span>
                    </div>
                    @endif
                    <div class="meta-row">
                        <i class="fas fa-user"></i>
                        <span>{{ $livestream->creator->name ?? 'Admin' }}</span>
                    </div>
                </div>

                @if($livestream->description)
                <p class="video-desc">{{ $livestream->description }}</p>
                @endif

                @if($livestream->game)
                <a href="#" class="game-tag">
                    <i class="fas fa-gamepad"></i> {{ $livestream->game->name }}
                </a>
                @endif

                @if($livestream->tournament)
                <a href="{{ route('tournaments.show', $livestream->tournament) }}" class="tournament-tag">
                    <i class="fas fa-trophy"></i> {{ $livestream->tournament->name }}
                </a>
                @endif
            </div>
        </div>

        @if($related->count() > 0)
        <div class="related-section">
            <h2 class="section-title"><i class="fas fa-video"></i> Video liên quan</h2>
            <div class="related-grid">
                @foreach($related as $stream)
                <a href="{{ route('livestreams.show', $stream) }}" class="related-card">
                    <div class="related-thumb">
                        @if($stream->thumbnail)
                        <img src="{{ Storage::url($stream->thumbnail) }}" alt="{{ $stream->title }}">
                        @else
                        <div class="related-thumb-placeholder">
                            <i class="{{ $stream->getPlatformIcon() }}"></i>
                        </div>
                        @endif
                    </div>
                    <div class="related-info">
                        <h3 class="related-title">{{ $stream->title }}</h3>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
