@extends('layouts.app')

@section('title', 'Quản lý Livestream')

@push('styles')
<style>
    .livestream-container { background: #000814; min-height: 100vh; padding-top: 80px; }
    .livestream-container .container { max-width: 1400px; margin: 0 auto; padding: 1.5rem; }

    .page-header {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #00E5FF;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .btn-create {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-create:hover {
        box-shadow: 0 0 25px rgba(239, 68, 68, 0.5);
        transform: translateY(-2px);
        color: white;
    }

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

    .stat-icon.total { background: rgba(99, 102, 241, 0.2); color: #818cf8; }
    .stat-icon.live { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
    .stat-icon.scheduled { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .stat-icon.ended { background: rgba(107, 114, 128, 0.2); color: #9ca3af; }

    .stat-value { font-size: 1.5rem; font-weight: 700; color: #FFFFFF; }
    .stat-label { font-size: 0.8rem; color: #64748b; }

    .table-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
    }

    .data-table { width: 100%; border-collapse: collapse; }

    .data-table th {
        background: rgba(0, 229, 255, 0.05);
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    }

    .data-table td {
        padding: 1rem;
        color: #e2e8f0;
        font-size: 0.875rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.05);
        vertical-align: middle;
    }

    .data-table tbody tr:hover { background: rgba(0, 229, 255, 0.05); }

    .stream-info { display: flex; align-items: center; gap: 12px; }

    .stream-thumb {
        width: 80px; height: 45px;
        border-radius: 8px;
        object-fit: cover;
        background: rgba(0, 0, 0, 0.3);
    }

    .stream-thumb-placeholder {
        width: 80px; height: 45px;
        border-radius: 8px;
        background: linear-gradient(135deg, #1a1a2e, #16213e);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
    }

    .stream-title { color: #FFFFFF; font-weight: 600; margin-bottom: 2px; }
    .stream-platform { font-size: 0.75rem; color: #64748b; }
    .stream-platform i.fa-youtube { color: #ef4444; }
    .stream-platform i.fa-facebook { color: #1877f2; }

    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-live { background: rgba(239, 68, 68, 0.2); color: #ef4444; animation: pulse 2s infinite; }
    .status-scheduled { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .status-ended { background: rgba(107, 114, 128, 0.2); color: #9ca3af; }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .btn-action {
        padding: 0.4rem 0.6rem;
        border-radius: 8px;
        font-size: 0.8rem;
        cursor: pointer;
        border: 1px solid transparent;
        background: transparent;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-action-edit { color: #00E5FF; border-color: rgba(0, 229, 255, 0.3); }
    .btn-action-edit:hover { background: rgba(0, 229, 255, 0.15); color: #00E5FF; }
    .btn-action-live { color: #ef4444; border-color: rgba(239, 68, 68, 0.3); }
    .btn-action-live:hover { background: rgba(239, 68, 68, 0.15); }
    .btn-action-end { color: #9ca3af; border-color: rgba(107, 114, 128, 0.3); }
    .btn-action-end:hover { background: rgba(107, 114, 128, 0.15); }
    .btn-action-delete { color: #ef4444; border-color: rgba(239, 68, 68, 0.3); }
    .btn-action-delete:hover { background: rgba(239, 68, 68, 0.15); }

    .empty-state { text-align: center; padding: 4rem 2rem; color: #64748b; }
    .empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .data-table { display: block; overflow-x: auto; }
    }
</style>
@endpush

@section('content')
<div class="livestream-container">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-broadcast-tower"></i> Quản lý Livestream
            </h1>
            <a href="{{ route('admin.livestreams.create') }}" class="btn-create">
                <i class="fas fa-plus"></i> Tạo Livestream
            </a>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total"><i class="fas fa-video"></i></div>
                <div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                    <div class="stat-label">Tổng số</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon live"><i class="fas fa-circle"></i></div>
                <div>
                    <div class="stat-value">{{ $stats['live'] }}</div>
                    <div class="stat-label">Đang phát</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon scheduled"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="stat-value">{{ $stats['scheduled'] }}</div>
                    <div class="stat-label">Đã lên lịch</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon ended"><i class="fas fa-stop-circle"></i></div>
                <div>
                    <div class="stat-value">{{ $stats['ended'] }}</div>
                    <div class="stat-label">Đã kết thúc</div>
                </div>
            </div>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Livestream</th>
                        <th>Game</th>
                        <th>Trạng thái</th>
                        <th>Lượt xem</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($livestreams as $stream)
                    <tr>
                        <td>
                            <div class="stream-info">
                                @if($stream->thumbnail)
                                <img src="{{ Storage::url($stream->thumbnail) }}" class="stream-thumb" alt="">
                                @else
                                <div class="stream-thumb-placeholder">
                                    <i class="{{ $stream->getPlatformIcon() }}"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="stream-title">{{ Str::limit($stream->title, 40) }}</div>
                                    <div class="stream-platform">
                                        <i class="{{ $stream->getPlatformIcon() }}"></i>
                                        {{ ucfirst($stream->platform) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $stream->game->name ?? '-' }}</td>
                        <td>
                            <span class="status-badge {{ $stream->getStatusBadgeClass() }}">
                                @if($stream->isLive())<i class="fas fa-circle mr-1"></i>@endif
                                {{ $stream->getStatusLabel() }}
                            </span>
                        </td>
                        <td>{{ number_format($stream->view_count) }}</td>
                        <td>{{ $stream->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.livestreams.edit', $stream) }}" class="btn-action btn-action-edit" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($stream->status !== 'live')
                                <form action="{{ route('admin.livestreams.toggle-status', [$stream, 'live']) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-action btn-action-live" title="Bắt đầu phát">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </form>
                                @endif
                                @if($stream->status === 'live')
                                <form action="{{ route('admin.livestreams.toggle-status', [$stream, 'ended']) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-action btn-action-end" title="Kết thúc">
                                        <i class="fas fa-stop"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.livestreams.destroy', $stream) }}" method="POST" style="display: inline;" onsubmit="return confirm('Xóa livestream này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-action-delete" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-video-slash"></i>
                                <p>Chưa có livestream nào</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($livestreams->hasPages())
            <div style="padding: 1rem; border-top: 1px solid rgba(0, 229, 255, 0.1);">
                {{ $livestreams->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
