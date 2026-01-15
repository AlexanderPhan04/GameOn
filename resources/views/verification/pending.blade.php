@extends('layouts.app')

@section('title', 'Yêu cầu đang chờ duyệt')

@push('styles')
<style>
    .pending-container { background: #000814; min-height: 100vh; padding: 2rem 0; }

    .pending-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(245, 158, 11, 0.3);
        border-radius: 20px;
        overflow: hidden;
        max-width: 600px;
        margin: 0 auto;
        text-align: center;
        padding: 3rem 2rem;
    }

    .pending-icon {
        width: 100px;
        height: 100px;
        background: rgba(245, 158, 11, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .pending-icon i { font-size: 3rem; color: #f59e0b; }

    .pending-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #f59e0b;
        margin-bottom: 1rem;
    }

    .pending-text {
        color: #94a3b8;
        font-size: 1rem;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .request-info {
        background: rgba(0, 0, 0, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: left;
        margin-bottom: 2rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .info-row:last-child { border-bottom: none; }
    .info-label { color: #64748b; font-size: 0.9rem; }
    .info-value { color: #e2e8f0; font-size: 0.9rem; font-weight: 500; }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.75rem 1.5rem;
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.3);
        border-radius: 10px;
        color: #00E5FF;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: rgba(0, 229, 255, 0.2);
        color: #FFFFFF;
    }
</style>
@endpush

@section('content')
<div class="pending-container">
    <div class="container">
        <div class="pending-card">
            <div class="pending-icon">
                <i class="fas fa-hourglass-half"></i>
            </div>

            <h1 class="pending-title">Yêu cầu đang chờ duyệt</h1>

            <p class="pending-text">
                Yêu cầu xác minh Pro Gamer của bạn đang được xem xét.<br>
                Chúng tôi sẽ thông báo kết quả trong thời gian sớm nhất.
            </p>

            <div class="request-info">
                <div class="info-row">
                    <span class="info-label">Game</span>
                    <span class="info-value">{{ $pendingRequest->game_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tên trong game</span>
                    <span class="info-value">{{ $pendingRequest->in_game_name }}</span>
                </div>
                @if($pendingRequest->rank_tier)
                <div class="info-row">
                    <span class="info-label">Rank/Tier</span>
                    <span class="info-value">{{ $pendingRequest->rank_tier }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Ngày gửi</span>
                    <span class="info-value">{{ $pendingRequest->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <a href="{{ route('profile.show') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Quay lại trang cá nhân
            </a>
        </div>
    </div>
</div>
@endsection
