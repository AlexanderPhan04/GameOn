@extends('layouts.app')

@section('title', __('app.honor.page_title') ?? 'Honor System')

@push('styles')
<style>
    .honor-container {
        background: #000814;
        min-height: calc(100vh - 64px);
        padding: 1.5rem;
    }

    .honor-header {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .honor-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #00E5FF, #8b5cf6, transparent);
    }

    .honor-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 30px rgba(245, 158, 11, 0.3);
    }

    .honor-icon i {
        font-size: 1.75rem;
        color: white;
    }

    .honor-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #00E5FF;
        margin: 0;
    }

    .honor-subtitle {
        color: #94a3b8;
        font-size: 0.95rem;
        margin: 0.25rem 0 0 0;
    }

    /* Event Cards */
    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.25rem;
    }

    .event-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .event-card:hover {
        border-color: rgba(0, 229, 255, 0.4);
        transform: translateY(-4px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4), 0 0 20px rgba(0, 229, 255, 0.1);
    }

    .event-card.featured {
        border-color: rgba(245, 158, 11, 0.4);
        box-shadow: 0 0 30px rgba(245, 158, 11, 0.15);
    }

    .event-card.featured:hover {
        border-color: rgba(245, 158, 11, 0.6);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4), 0 0 30px rgba(245, 158, 11, 0.2);
    }

    .event-card-header {
        padding: 1.25rem;
        background: linear-gradient(135deg, rgba(0, 0, 85, 0.3), rgba(0, 229, 255, 0.05));
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .event-card.featured .event-card-header {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(217, 119, 6, 0.1));
        border-bottom-color: rgba(245, 158, 11, 0.2);
    }

    .event-type-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        flex-shrink: 0;
    }

    .event-type-icon.user {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
    }

    .event-type-icon.team {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .event-card-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }

    .event-card-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.25rem;
    }

    .event-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .event-badge.active {
        background: rgba(34, 197, 94, 0.15);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .event-badge.paused {
        background: rgba(100, 116, 139, 0.15);
        color: #94a3b8;
        border: 1px solid rgba(100, 116, 139, 0.3);
    }

    .event-badge.featured {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .event-card-body {
        padding: 1.25rem;
    }

    .event-description {
        color: #94a3b8;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .event-stats {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1rem;
    }

    .event-stat {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 0.85rem;
    }

    .event-stat i {
        color: #00E5FF;
    }

    .event-card-footer {
        padding: 1rem 1.25rem;
        background: rgba(0, 0, 20, 0.3);
        border-top: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        gap: 0.75rem;
    }

    .btn-vote {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-vote:hover {
        box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
        transform: translateY(-2px);
        color: white;
    }

    .btn-results {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.3);
        border-radius: 10px;
        color: #00E5FF;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-results:hover {
        background: rgba(0, 229, 255, 0.2);
        color: #fff;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 20px;
    }

    .empty-state-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1));
        border: 2px dashed rgba(245, 158, 11, 0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state-icon i {
        font-size: 2.5rem;
        color: #f59e0b;
        opacity: 0.6;
    }

    .empty-state-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.5rem;
    }

    .empty-state-text {
        color: #94a3b8;
        font-size: 1rem;
        max-width: 400px;
        margin: 0 auto;
    }

    /* Section Title */
    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    }

    .section-title i {
        color: #00E5FF;
    }

    .section-title span {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: #fff;
    }

    /* Alert */
    .honor-alert {
        padding: 1rem 1.25rem;
        border-radius: 12px;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .honor-alert.success {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #22c55e;
    }

    .honor-alert.error {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }

    @media (max-width: 768px) {
        .honor-container {
            padding: 1rem;
        }

        .honor-header {
            padding: 1.5rem;
        }

        .events-grid {
            grid-template-columns: 1fr;
        }

        .event-card-footer {
            flex-direction: column;
        }

        .btn-vote, .btn-results {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="honor-container">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="honor-header">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="honor-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <div>
                        <h1 class="honor-title">{{ __('app.honor.page_title') ?? 'Honor System' }}</h1>
                        <p class="honor-subtitle">{{ __('app.honor.page_subtitle') ?? 'Bình chọn và vinh danh những người chơi, đội tuyển xuất sắc' }}</p>
                    </div>
                </div>
                @auth
                    @if(auth()->user()->user_role === 'super_admin' || auth()->user()->user_role === 'admin')
                    <a href="{{ route('admin.honor.index') }}" class="btn-results">
                        <i class="fas fa-cog"></i>
                        <span>{{ __('app.honor.manage') ?? 'Quản lý' }}</span>
                    </a>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
        <div class="honor-alert success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="honor-alert error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <!-- Current/Featured Event -->
        @if($currentEvent)
        <div class="section-title">
            <i class="fas fa-star"></i>
            <span>{{ __('app.honor.current_event') ?? 'Sự kiện đang diễn ra' }}</span>
        </div>
        <div class="events-grid" style="margin-bottom: 2rem;">
            <div class="event-card featured" style="grid-column: 1 / -1; max-width: 100%;">
                <div class="event-card-header">
                    <div class="event-type-icon {{ $currentEvent->target_type }}">
                        <i class="fas fa-{{ $currentEvent->target_type === 'user' ? 'user' : 'users' }}"></i>
                    </div>
                    <div style="flex: 1;">
                        <h3 class="event-card-title">{{ $currentEvent->name }}</h3>
                        <div class="event-card-meta">
                            <span class="event-badge featured">
                                <i class="fas fa-fire"></i> {{ __('app.honor.featured') ?? 'Nổi bật' }}
                            </span>
                            @if($currentEvent->isCurrentlyRunning())
                            <span class="event-badge active">
                                <i class="fas fa-circle"></i> {{ __('app.honor.status_active') ?? 'Đang diễn ra' }}
                            </span>
                            @else
                            <span class="event-badge paused">
                                <i class="fas fa-pause"></i> {{ __('app.honor.status_paused') ?? 'Tạm dừng' }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="event-card-body">
                    @if($currentEvent->description)
                    <p class="event-description">{{ $currentEvent->description }}</p>
                    @endif
                    <div class="event-stats">
                        <div class="event-stat">
                            <i class="fas fa-vote-yea"></i>
                            <span>{{ $currentEvent->getTotalVotesCount() }} {{ __('app.honor.votes') ?? 'lượt bình chọn' }}</span>
                        </div>
                        <div class="event-stat">
                            <i class="fas fa-{{ $currentEvent->target_type === 'user' ? 'user' : 'users' }}"></i>
                            <span>{{ ucfirst($currentEvent->target_type) }}</span>
                        </div>
                    </div>
                </div>
                <div class="event-card-footer">
                    @if($currentEvent->isCurrentlyRunning() && auth()->check() && $currentEvent->canUserVote(auth()->user()))
                    <a href="{{ route('honor.show', $currentEvent) }}" class="btn-vote">
                        <i class="fas fa-vote-yea"></i>
                        <span>{{ __('app.honor.join_vote') ?? 'Tham gia bình chọn' }}</span>
                    </a>
                    @endif
                    <a href="{{ route('honor.results', $currentEvent) }}" class="btn-results">
                        <i class="fas fa-chart-bar"></i>
                        <span>{{ __('app.honor.view_results') ?? 'Xem kết quả' }}</span>
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- All Events -->
        @if($freeModeEvents->count() > 0)
        <div class="section-title">
            <i class="fas fa-list"></i>
            <span>{{ __('app.honor.all_events') ?? 'Tất cả sự kiện' }}</span>
        </div>
        <div class="events-grid">
            @foreach($freeModeEvents as $event)
            <div class="event-card">
                <div class="event-card-header">
                    <div class="event-type-icon {{ $event->target_type }}">
                        <i class="fas fa-{{ $event->target_type === 'user' ? 'user' : 'users' }}"></i>
                    </div>
                    <div>
                        <h3 class="event-card-title">{{ $event->name }}</h3>
                        <div class="event-card-meta">
                            @if($event->isCurrentlyRunning())
                            <span class="event-badge active">
                                <i class="fas fa-circle"></i> {{ __('app.honor.status_active') ?? 'Đang diễn ra' }}
                            </span>
                            @else
                            <span class="event-badge paused">
                                <i class="fas fa-pause"></i> {{ __('app.honor.status_paused') ?? 'Tạm dừng' }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="event-card-body">
                    @if($event->description)
                    <p class="event-description">{{ Str::limit($event->description, 120) }}</p>
                    @else
                    <p class="event-description" style="opacity: 0.5;">{{ __('app.honor.no_description') ?? 'Chưa có mô tả' }}</p>
                    @endif
                    <div class="event-stats">
                        <div class="event-stat">
                            <i class="fas fa-vote-yea"></i>
                            <span>{{ $event->getTotalVotesCount() }} {{ __('app.honor.votes') ?? 'lượt' }}</span>
                        </div>
                        <div class="event-stat">
                            <i class="fas fa-{{ $event->target_type === 'user' ? 'user' : 'users' }}"></i>
                            <span>{{ ucfirst($event->target_type) }}</span>
                        </div>
                    </div>
                </div>
                <div class="event-card-footer">
                    @if($event->isCurrentlyRunning() && auth()->check() && $event->canUserVote(auth()->user()))
                    <a href="{{ route('honor.show', $event) }}" class="btn-vote">
                        <i class="fas fa-vote-yea"></i>
                        <span>{{ __('app.honor.vote_btn') ?? 'Bình chọn' }}</span>
                    </a>
                    @endif
                    <a href="{{ route('honor.results', $event) }}" class="btn-results">
                        <i class="fas fa-chart-bar"></i>
                        <span>{{ __('app.honor.view_results') ?? 'Kết quả' }}</span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @elseif(!$currentEvent)
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <h3 class="empty-state-title">{{ __('app.honor.empty_title') ?? 'Chưa có sự kiện nào' }}</h3>
            <p class="empty-state-text">{{ __('app.honor.empty_desc_user') ?? 'Hiện tại chưa có sự kiện bình chọn nào. Hãy quay lại sau nhé!' }}</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Listen for realtime honor events
    if (typeof Echo !== 'undefined') {
        Echo.channel('honor')
            .listen('.event.created', (e) => {
                console.log('New honor event created:', e);
                // Reload page to show new event
                location.reload();
            })
            .listen('.event.updated', (e) => {
                console.log('Honor event updated:', e);
                // Update event card if exists
                updateEventCard(e.event);
            })
            .listen('.event.deleted', (e) => {
                console.log('Honor event deleted:', e);
                // Remove event card or reload
                location.reload();
            })
            .listen('.vote.cast', (e) => {
                console.log('Vote cast:', e);
                // Update vote count in event cards
                updateVoteCount(e.eventId, e.stats.event_total_votes);
            })
            .listen('.votes.reset', (e) => {
                console.log('Votes reset:', e);
                // Reset vote count
                updateVoteCount(e.eventId, 0);
            });
    }

    function updateEventCard(event) {
        // Find and update event card
        const cards = document.querySelectorAll('.event-card');
        cards.forEach(card => {
            const title = card.querySelector('.event-card-title');
            if (title && title.textContent.trim() === event.name) {
                // Update status badge
                const statusBadge = card.querySelector('.event-badge.active, .event-badge.paused');
                if (statusBadge) {
                    if (event.is_active) {
                        statusBadge.className = 'event-badge active';
                        statusBadge.innerHTML = '<i class="fas fa-circle"></i> {{ __("app.honor.status_active") ?? "Đang diễn ra" }}';
                    } else {
                        statusBadge.className = 'event-badge paused';
                        statusBadge.innerHTML = '<i class="fas fa-pause"></i> {{ __("app.honor.status_paused") ?? "Tạm dừng" }}';
                    }
                }
                // Update vote count
                const voteStat = card.querySelector('.event-stat');
                if (voteStat) {
                    voteStat.innerHTML = `<i class="fas fa-vote-yea"></i><span>${event.total_votes} {{ __("app.honor.votes") ?? "lượt" }}</span>`;
                }
            }
        });
    }

    function updateVoteCount(eventId, count) {
        // Update vote count in all event cards
        const voteStats = document.querySelectorAll('.event-stat');
        voteStats.forEach(stat => {
            if (stat.querySelector('.fa-vote-yea')) {
                const span = stat.querySelector('span');
                if (span) {
                    span.textContent = count + ' {{ __("app.honor.votes") ?? "lượt" }}';
                }
            }
        });
    }
});
</script>
@endpush
