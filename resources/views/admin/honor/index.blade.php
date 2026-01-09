@extends('layouts.app')

@section('title', __('app.honor.manage_title'))

@push('styles')
<style>
    .honor-container { background: #000814; min-height: 100vh; }

    /* Hero Section */
    .honor-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .honor-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #f59e0b, transparent);
    }
    .hero-icon {
        width: 60px; height: 60px; min-width: 60px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 0 25px rgba(245, 158, 11, 0.3);
    }
    .hero-icon i { font-size: 1.5rem; color: white; }
    .hero-title { font-family: 'Rajdhani', sans-serif; font-size: 1.5rem; font-weight: 700; color: #f59e0b; margin: 0; }
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
    .btn-neon-gold {
        background: linear-gradient(135deg, #92400e, #b45309);
        border-color: rgba(245, 158, 11, 0.4);
        color: #fbbf24;
    }
    .btn-neon-gold:hover { box-shadow: 0 0 20px rgba(245, 158, 11, 0.4); color: #FFFFFF; }

    /* Alert */
    .alert-custom {
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .alert-success-custom { background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: #22c55e; }
    .alert-error-custom { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; }

    /* Table Card */
    .table-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
    }
    .honor-table { width: 100%; border-collapse: collapse; }
    .honor-table th {
        background: rgba(245, 158, 11, 0.05);
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(245, 158, 11, 0.1);
    }
    .honor-table td {
        padding: 1rem;
        color: #e2e8f0;
        font-size: 0.875rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.05);
        vertical-align: middle;
    }
    .honor-table tbody tr { transition: all 0.3s ease; }
    .honor-table tbody tr:hover { background: rgba(245, 158, 11, 0.05); }

    .event-name { font-weight: 600; color: #FFFFFF; }
    .event-desc { color: #64748b; font-size: 0.8rem; margin-top: 0.2rem; }
    .event-id { color: #f59e0b; font-weight: 600; }

    /* Badges */
    .badge-custom { padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
    .badge-event { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .badge-free { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    .badge-target { background: rgba(0, 229, 255, 0.15); color: #00E5FF; }
    .badge-active { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    .badge-paused { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .badge-off { background: rgba(100, 116, 139, 0.2); color: #94a3b8; }

    .time-info { font-size: 0.8rem; color: #94a3b8; }
    .time-info i { color: #f59e0b; margin-right: 4px; }
    .time-unlimited { color: #22c55e; }

    .votes-count { text-align: center; }
    .votes-number { font-size: 1.25rem; font-weight: 700; color: #f59e0b; }
    .votes-label { font-size: 0.75rem; color: #64748b; }

    /* Action Buttons */
    .btn-action { padding: 0.4rem 0.6rem; border-radius: 8px; font-size: 0.8rem; transition: all 0.3s ease; cursor: pointer; border: 1px solid transparent; background: transparent; }
    .btn-action-view { color: #00E5FF; border-color: rgba(0, 229, 255, 0.3); }
    .btn-action-view:hover { background: rgba(0, 229, 255, 0.15); }
    .btn-action-edit { color: #f59e0b; border-color: rgba(245, 158, 11, 0.3); }
    .btn-action-edit:hover { background: rgba(245, 158, 11, 0.15); }
    .btn-action-toggle { color: #94a3b8; border-color: rgba(148, 163, 184, 0.3); }
    .btn-action-toggle:hover { background: rgba(148, 163, 184, 0.15); color: #FFFFFF; }
    .btn-action-play { color: #22c55e; border-color: rgba(34, 197, 94, 0.3); }
    .btn-action-play:hover { background: rgba(34, 197, 94, 0.15); }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(245, 158, 11, 0.15);
        border-radius: 16px;
    }
    .empty-icon {
        width: 100px; height: 100px;
        background: rgba(245, 158, 11, 0.1);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.5rem;
    }
    .empty-icon i { font-size: 2.5rem; color: #f59e0b; }
    .empty-title { color: #FFFFFF; font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem; }
    .empty-text { color: #64748b; font-size: 0.95rem; margin-bottom: 1.5rem; }

    .pagination-wrapper { padding: 1rem 1.5rem; border-top: 1px solid rgba(245, 158, 11, 0.1); display: flex; justify-content: center; }

    @media (max-width: 768px) {
        .honor-hero { padding: 1.25rem; }
        .hero-content { flex-direction: column; align-items: flex-start !important; gap: 1rem; }
        .btn-neon { width: 100%; justify-content: center; }
        .honor-table { display: block; overflow-x: auto; }
    }
</style>
@endpush

@section('content')
<div class="honor-container">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Hero Section -->
        <div class="honor-hero">
            <div class="hero-content flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="hero-icon"><i class="fas fa-trophy"></i></div>
                    <div>
                        <h1 class="hero-title">{{ __('app.honor.manage_title') }}</h1>
                        <p class="hero-subtitle">Quản lý các sự kiện vinh danh và bình chọn</p>
                    </div>
                </div>
                <a href="{{ route('admin.honor.create') }}" class="btn-neon btn-neon-gold">
                    <i class="fas fa-plus"></i><span>{{ __('app.honor.create_event') }}</span>
                </a>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
        <div class="alert-custom alert-success-custom">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="alert-custom alert-error-custom">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        @if($events->count() > 0)
        <!-- Honor Events Table -->
        <div class="table-card">
            <div class="overflow-x-auto">
                <table class="honor-table">
                    <thead>
                        <tr>
                            <th class="w-12">#</th>
                            <th>{{ __('app.honor.col_event_name') }}</th>
                            <th>{{ __('app.honor.col_mode') }}</th>
                            <th>{{ __('app.honor.col_target') }}</th>
                            <th>{{ __('app.honor.col_time') }}</th>
                            <th>{{ __('app.honor.col_status') }}</th>
                            <th>{{ __('app.honor.col_votes') }}</th>
                            <th class="w-32">{{ __('app.honor.col_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr>
                            <td><span class="event-id">{{ $event->id }}</span></td>
                            <td>
                                <div class="event-name">{{ $event->name }}</div>
                                @if($event->description)
                                <div class="event-desc">{{ Str::limit($event->description, 50) }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge-custom {{ $event->mode === 'event' ? 'badge-event' : 'badge-free' }}">
                                    {{ $event->mode === 'event' ? __('app.honor.mode_event') : __('app.honor.mode_free') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-custom badge-target">{{ ucfirst($event->target_type) }}</span>
                            </td>
                            <td>
                                <div class="time-info">
                                    @if($event->start_time)
                                    <div><i class="fas fa-play"></i>{{ $event->start_time->format('d/m/Y H:i') }}</div>
                                    @endif
                                    @if($event->end_time)
                                    <div><i class="fas fa-stop"></i>{{ $event->end_time->format('d/m/Y H:i') }}</div>
                                    @else
                                    <div class="time-unlimited"><i class="fas fa-infinity"></i>{{ __('app.honor.time_unlimited') }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($event->isCurrentlyRunning())
                                <span class="badge-custom badge-active">{{ __('app.honor.status_active') }}</span>
                                @else
                                <span class="badge-custom {{ $event->is_active ? 'badge-paused' : 'badge-off' }}">
                                    {{ $event->is_active ? __('app.honor.status_paused') : __('app.honor.status_off') }}
                                </span>
                                @endif
                            </td>
                            <td>
                                <div class="votes-count">
                                    <div class="votes-number">{{ $event->getTotalVotesCount() }}</div>
                                    <div class="votes-label">{{ __('app.honor.votes') }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.honor.show', $event) }}" class="btn-action btn-action-view" title="{{ __('app.honor.view_detail') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.honor.edit', $event) }}" class="btn-action btn-action-edit" title="{{ __('app.honor.edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn-action {{ $event->is_active ? 'btn-action-toggle' : 'btn-action-play' }}" 
                                            onclick="toggleStatus({{ $event->id }})" title="{{ $event->is_active ? __('app.honor.turn_off') : __('app.honor.turn_on') }}">
                                        <i class="fas fa-{{ $event->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($events->hasPages())
            <div class="pagination-wrapper">
                {{ $events->links() }}
            </div>
            @endif
        </div>
        @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-trophy"></i></div>
            <h3 class="empty-title">{{ __('app.honor.empty_title') }}</h3>
            <p class="empty-text">{{ __('app.honor.empty_desc') }}</p>
            <a href="{{ route('admin.honor.create') }}" class="btn-neon btn-neon-gold">
                <i class="fas fa-plus"></i><span>{{ __('app.honor.create_event') }}</span>
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Hidden Forms -->
<form id="toggleForm" method="POST" style="display: none;">@csrf @method('PATCH')</form>
<form id="resetForm" method="POST" style="display: none;">@csrf @method('DELETE')</form>
<form id="deleteForm" method="POST" style="display: none;">@csrf @method('DELETE')</form>
@endsection

@push('scripts')
<script>
function toggleStatus(eventId) {
    if (confirm('{{ __("app.honor.confirm_toggle") }}')) {
        const form = document.getElementById('toggleForm');
        form.action = `/admin/honor/${eventId}/toggle`;
        form.submit();
    }
}
function resetVotes(eventId) {
    if (confirm('{{ __("app.honor.confirm_reset") }}')) {
        const form = document.getElementById('resetForm');
        form.action = `/admin/honor/${eventId}/reset`;
        form.submit();
    }
}
function deleteEvent(eventId) {
    if (confirm('{{ __("app.honor.confirm_delete") }}')) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/honor/${eventId}`;
        form.submit();
    }
}
</script>
@endpush
