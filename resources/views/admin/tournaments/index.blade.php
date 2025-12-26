@extends('layouts.app')

@section('title', __('app.profile.manage_tournaments'))

@push('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Hero Section -->
            <div class="hero-section mb-5" data-aos="fade-up">
                <div class="hero-background"></div>
                <div class="hero-content">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h1 class="hero-title">
                                <i class="fas fa-trophy hero-icon"></i>
                            {{ __('app.profile.manage_tournaments') }}
                            </h1>
                            <p class="hero-subtitle">{{ __('app.profile.manage_tournaments_description') }}</p>
                        </div>
                        <a href="{{ route('admin.tournaments.create') }}" class="btn btn-hero-primary">
                            <i class="fas fa-plus me-2"></i>
                            {{ __('app.tournaments.create_new_tournament') }}
                        </a>
                    </div>
                    </div>
                </div>

            <!-- Advanced Filters -->
            <div class="card filter-card mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card-body">
                    <form method="GET" class="filter-form">
                        <div class="row g-4">
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-search me-2"></i>{{ __('app.search.search') }}
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                            <input type="text" class="form-control" name="search"
                                            value="{{ request('search') }}" placeholder="{{ __('app.tournaments.search_placeholder') }}">
                                    </div>
                                </div>
                        </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-gamepad me-2"></i>{{ __('app.nav.tournaments') }}
                                    </label>
                            <select class="form-select" name="game_id">
                                <option value="">{{ __('app.tournaments.all_games') }}</option>
                                @foreach($games as $game)
                                <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>
                                    {{ $game->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-flag me-2"></i>{{ __('app.teams.status') }}
                                    </label>
                            <select class="form-select" name="status">
                                <option value="">{{ __('app.tournaments.all_statuses') }}</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>{{ __('app.tournaments.draft') }}</option>
                                <option value="registration_open" {{ request('status') === 'registration_open' ? 'selected' : '' }}>{{ __('app.tournaments.registration_open') }}</option>
                                <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>{{ __('app.tournaments.ongoing') }}</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('app.tournaments.completed') }}</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('app.tournaments.cancelled') }}</option>
                            </select>
                        </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-fill">
                                            <i class="fas fa-search me-1"></i>{{ __('app.search.search') }}
                            </button>
                            <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times"></i>
                            </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tournaments Grid -->
            <div class="row">
                @forelse($tournaments as $tournament)
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="tournament-card-modern">
                        <!-- Card Header with Banner -->
                        <div class="tournament-header">
                            @if($tournament->banner_url)
                            <img src="{{ $tournament->banner_url }}" class="tournament-banner" alt="{{ $tournament->name }}">
                            @else
                            <div class="tournament-banner-placeholder">
                                <i class="fas fa-trophy"></i>
                            </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="status-badge status-{{ $tournament->status }}">
                                <i class="fas fa-circle"></i>
                                {{ $tournament->status_label }}
                            </div>

                            <!-- Game Logo -->
                            @if($tournament->logo_url)
                            <div class="game-logo">
                                <img src="{{ $tournament->logo_url }}" alt="Game Logo">
                            </div>
                            @endif

                            <!-- Quick Actions -->
                            <div class="quick-actions">
                                <button class="action-btn view-btn" data-tournament-id="{{ $tournament->id }}" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn edit-btn" data-tournament-id="{{ $tournament->id }}" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn delete-btn delete-tournament-btn" 
                                    data-tournament-id="{{ $tournament->id }}" 
                                    data-tournament-name="{{ $tournament->name }}" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="tournament-content">
                            <!-- Title & Game -->
                            <div class="tournament-title-section">
                                <h3 class="tournament-title">{{ $tournament->name }}</h3>
                                <div class="tournament-game">
                                    <i class="fas fa-gamepad"></i>
                                    <span>{{ $tournament->game->name ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <!-- Meta Information -->
                            <div class="tournament-meta">
                                <div class="meta-item">
                                    <div class="meta-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="meta-content">
                                        <span class="meta-label">{{ __('app.tournaments.competition_type') }}</span>
                                        <span class="meta-value">{{ $tournament->competition_type_label }}</span>
                                    </div>
                                </div>
                                
                                <div class="meta-item">
                                    <div class="meta-icon">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <div class="meta-content">
                                        <span class="meta-label">{{ __('app.tournaments.max_participants') }}</span>
                                        <span class="meta-value">{{ $tournament->max_participants }} {{ __('app.tournaments.teams') }}</span>
                                    </div>
                                </div>
                                
                                <div class="meta-item">
                                    <div class="meta-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="meta-content">
                                        <span class="meta-label">{{ __('app.tournaments.format') }}</span>
                                        <span class="meta-value">{{ $tournament->location_type_label }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Date & Time -->
                            <div class="tournament-datetime">
                                <i class="fas fa-calendar-alt"></i>
                                <span>{{ $tournament->formatted_date_range }}</span>
                            </div>

                            <!-- Description -->
                            @if($tournament->description)
                            <div class="tournament-description">
                                <p>{{ Str::limit($tournament->description, 120) }}</p>
                            </div>
                            @endif

                            <!-- Creator Info -->
                            <div class="tournament-creator">
                                <div class="creator-info">
                                    <i class="fas fa-user-plus"></i>
                                    <span>{{ __('app.tournaments.created_by') }}: <strong>{{ $tournament->creator ? $tournament->creator->name : 'N/A' }}</strong></span>
                                </div>
                                <div class="creation-date">
                                    {{ $tournament->created_at->format('d/m/Y') }}
                        </div>
                            </div>
                        </div>

                        <!-- Card Footer Actions -->
                        <div class="tournament-footer">
                            <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-view">
                                <i class="fas fa-eye me-2"></i>{{ __('app.dashboard.view_details') }}
                            </a>
                            <a href="{{ route('admin.tournaments.edit', $tournament->id) }}" class="btn btn-edit">
                                <i class="fas fa-edit me-2"></i>{{ __('app.common.edit') }}
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12" data-aos="fade-up">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3 class="empty-title">{{ __('app.tournaments.no_tournaments') }}</h3>
                        <p class="empty-description">{{ __('app.tournaments.no_tournaments_description') }}</p>
                        <a href="{{ route('admin.tournaments.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>{{ __('app.tournaments.create_new_tournament') }}
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($tournaments->hasPages())
            <div class="pagination-wrapper" data-aos="fade-up">
                {{ $tournaments->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h5 class="modal-title">{{ __('app.tournaments.confirm_delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="modal-message">
                    {{ __('app.tournaments.confirm_delete_message') }} <strong id="deleteTournamentName"></strong>?
                </p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>{{ __('app.common.warning') }}:</strong> {{ __('app.tournaments.delete_warning') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>{{ __('app.common.cancel') }}
                </button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fas fa-trash me-2"></i>{{ __('app.tournaments.delete_tournament') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Delete functionality
        const deleteButtons = document.querySelectorAll('.delete-tournament-btn');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

        deleteButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const tournamentId = this.getAttribute('data-tournament-id');
                const tournamentName = this.getAttribute('data-tournament-name');

                document.getElementById('deleteTournamentName').textContent = tournamentName;
                deleteModal.show();

                // Store tournament ID for deletion
                document.getElementById('confirmDelete').setAttribute('data-tournament-id', tournamentId);
            });
        });

        document.getElementById('confirmDelete').addEventListener('click', function() {
            const tournamentId = this.getAttribute('data-tournament-id');

            if (!tournamentId) return;

            // Show loading state
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("app.tournaments.deleting") }}';
            this.disabled = true;

            fetch(`/admin/tournaments/${tournamentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success notification
                        showNotification('{{ __("app.tournaments.delete_success") }}', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification('{{ __("app.tournaments.delete_error") }}', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('{{ __("app.tournaments.delete_error") }}', 'error');
                })
                .finally(() => {
                    // Reset button state
                    this.innerHTML = '<i class="fas fa-trash me-2"></i>{{ __("app.tournaments.delete_tournament") }}';
                    this.disabled = false;
                });
        });

        // Quick action buttons
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tournamentId = this.getAttribute('data-tournament-id');
                window.location.href = `/admin/tournaments/${tournamentId}`;
            });
        });

        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tournamentId = this.getAttribute('data-tournament-id');
                window.location.href = `/admin/tournaments/${tournamentId}/edit`;
            });
        });

        // Notification function
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    });
</script>
@endpush

@push('styles')
<style>
/* Hero Section */
.hero-section {
    position: relative;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 2rem;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="rgba(255,255,255,0.1)"/><stop offset="100%" stop-color="rgba(255,255,255,0)"/></radialGradient></defs><circle cx="200" cy="200" r="300" fill="url(%23a)"/><circle cx="800" cy="300" r="200" fill="url(%23a)"/><circle cx="400" cy="700" r="250" fill="url(%23a)"/></svg>');
    opacity: 0.3;
}

.hero-content {
    position: relative;
    z-index: 2;
    padding: 3rem 2rem;
    color: white;
}

.hero-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.hero-icon {
    color: #ffd700;
    margin-right: 1rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.hero-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
}

.btn-hero-primary {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.btn-hero-primary:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

/* Filter Card */
.filter-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    background: white;
}

.filter-form .form-group {
    margin-bottom: 0;
}

.filter-form .form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.filter-form .form-control,
.filter-form .form-select {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.filter-form .form-control:focus,
.filter-form .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.filter-form .input-group-text {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-right: none;
    border-radius: 10px 0 0 10px;
}

/* Tournament Cards */
.tournament-card-modern {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.tournament-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

.tournament-header {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.tournament-banner {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.tournament-card-modern:hover .tournament-banner {
    transform: scale(1.05);
}

.tournament-banner-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
    opacity: 0.8;
}

.status-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.status-badge i {
    font-size: 0.6rem;
    margin-right: 0.5rem;
}

.status-draft { background: rgba(108, 117, 125, 0.9); color: white; }
.status-registration_open { background: rgba(40, 167, 69, 0.9); color: white; }
.status-ongoing { background: rgba(255, 193, 7, 0.9); color: #000; }
.status-completed { background: rgba(23, 162, 184, 0.9); color: white; }
.status-cancelled { background: rgba(220, 53, 69, 0.9); color: white; }

.game-logo {
    position: absolute;
    bottom: -30px;
    left: 1rem;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 4px solid white;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.game-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.quick-actions {
    position: absolute;
    top: 1rem;
    right: 1rem;
    display: flex;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.tournament-card-modern:hover .quick-actions {
    opacity: 1;
}

.action-btn {
    width: 35px;
    height: 35px;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.view-btn { background: rgba(23, 162, 184, 0.9); }
.edit-btn { background: rgba(0, 123, 255, 0.9); }
.delete-btn { background: rgba(220, 53, 69, 0.9); }

.action-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}

.tournament-content {
    padding: 2rem 1.5rem 1rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.tournament-title-section {
    margin-bottom: 1.5rem;
}

.tournament-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.tournament-game {
    display: flex;
    align-items: center;
    color: #6c757d;
    font-size: 0.9rem;
}

.tournament-game i {
    margin-right: 0.5rem;
    color: #667eea;
}

.tournament-meta {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.meta-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.meta-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.meta-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 1rem;
    font-size: 0.9rem;
}

.meta-content {
    flex: 1;
}

.meta-label {
    display: block;
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.meta-value {
    display: block;
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9rem;
}

.tournament-datetime {
    display: flex;
    align-items: center;
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    padding: 0.5rem 0;
}

.tournament-datetime i {
    margin-right: 0.5rem;
    color: #667eea;
}

.tournament-description {
    margin-bottom: 1.5rem;
    flex: 1;
}

.tournament-description p {
    color: #6c757d;
    font-size: 0.9rem;
    line-height: 1.5;
    margin: 0;
}

.tournament-creator {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
    margin-bottom: 1rem;
}

.creator-info {
    display: flex;
    align-items: center;
    color: #6c757d;
    font-size: 0.9rem;
}

.creator-info i {
    margin-right: 0.5rem;
    color: #667eea;
}

.creation-date {
    color: #6c757d;
    font-size: 0.8rem;
}

.tournament-footer {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    display: flex;
    gap: 0.75rem;
}

.btn-view, .btn-edit {
    flex: 1;
    padding: 0.75rem 1rem;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
    border: none;
    font-size: 0.9rem;
}

.btn-view {
    background: #17a2b8;
    color: white;
}

.btn-view:hover {
    background: #138496;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
}

.btn-edit {
    background: #007bff;
    color: white;
}

.btn-edit:hover {
    background: #0056b3;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.empty-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1.5rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 1rem;
}

.empty-description {
    color: #adb5bd;
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 3rem;
}

.pagination {
    justify-content: center;
}

.page-link {
    border: none;
    color: #667eea;
    padding: 0.75rem 1rem;
    margin: 0 0.25rem;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
}

.page-item.active .page-link {
    background: #667eea;
    border-color: #667eea;
}

/* Modal */
.modern-modal {
    border: none;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.modern-modal .modal-header {
    border-bottom: 1px solid #e9ecef;
    padding: 1.5rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 20px 20px 0 0;
}

.modal-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.5rem;
}

.modern-modal .modal-title {
    margin: 0;
    font-weight: 600;
}

.modern-modal .modal-body {
    padding: 2rem;
}

.modal-message {
    font-size: 1.1rem;
    margin-bottom: 1.5rem;
    color: #495057;
}

.modern-modal .modal-footer {
    border-top: 1px solid #e9ecef;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 0 0 20px 20px;
}

/* Notifications */
.notification {
    position: fixed;
    top: 2rem;
    right: 2rem;
    background: white;
    padding: 1rem 1.5rem;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    z-index: 9999;
    transform: translateX(400px);
    transition: transform 0.3s ease;
}

.notification.show {
    transform: translateX(0);
}

.notification-success {
    border-left: 4px solid #28a745;
}

.notification-error {
    border-left: 4px solid #dc3545;
}

.notification i {
    font-size: 1.2rem;
}

.notification-success i {
    color: #28a745;
}

.notification-error i {
    color: #dc3545;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-content {
        padding: 2rem 1rem;
    }
    
    .tournament-meta {
        grid-template-columns: 1fr;
    }
    
    .tournament-footer {
        flex-direction: column;
    }
    
    .quick-actions {
        opacity: 1;
    }
    
    .notification {
        right: 1rem;
        left: 1rem;
        transform: translateY(-100px);
    }
    
    .notification.show {
        transform: translateY(0);
    }
    }
</style>
@endpush
@endsection