@extends('layouts.app')

@section('title', __('app.profile.manage_games'))

@push('styles')
<style>
    /* Modern Game Management Styles */
    .games-management-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .games-management-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="%23ffffff08" points="0,1000 1000,0 1000,1000"/></svg>');
        pointer-events: none;
    }

    .modern-page-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
    }

    .modern-page-header::before {
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

    .modern-page-title {
        font-weight: 700;
        font-size: 2rem;
        color: #2d3748;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .modern-add-btn {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 16px;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);
        position: relative;
        overflow: hidden;
    }

    .modern-add-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(79, 172, 254, 0.5);
        color: white;
    }

    .modern-add-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .modern-add-btn:hover::before {
        left: 100%;
    }

    .modern-alert {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: none;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
        animation: slideInDown 0.5s ease-out;
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

    .modern-alert-success {
        border-left: 4px solid #48bb78;
        color: #2f855a;
    }

    .modern-alert-success::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #48bb78, #68d391);
    }

    .modern-game-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        position: relative;
        animation: fadeInUp 0.6s ease-out;
        animation-fill-mode: both;
    }

    .modern-game-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
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

    .game-banner-container {
        position: relative;
        overflow: hidden;
        border-radius: 16px 16px 0 0;
    }

    .game-banner {
        height: 220px;
        object-fit: cover;
        width: 100%;
        transition: transform 0.5s ease;
    }

    .modern-game-card:hover .game-banner {
        transform: scale(1.05);
    }

    .modern-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .badge-active {
        background: rgba(72, 187, 120, 0.9);
        color: white;
    }

    .badge-inactive {
        background: rgba(113, 128, 150, 0.9);
        color: white;
    }

    .badge-esports {
        background: rgba(255, 193, 7, 0.9);
        color: #1a202c;
    }

    .modern-card-body {
        padding: 1.5rem;
    }

    .game-logo {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 12px;
        border: 3px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .game-title {
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
    }

    .game-genre {
        color: #718096;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .game-info {
        margin: 1rem 0;
        flex-grow: 1;
    }

    .game-info-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
        color: #4a5568;
    }

    .game-info-item i {
        width: 20px;
        text-align: center;
        margin-right: 0.5rem;
    }

    .modern-btn-group {
        display: flex;
        gap: 0.5rem;
        margin-top: auto;
    }

    .modern-action-btn {
        flex: 1;
        padding: 0.75rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .btn-view {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .btn-edit {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        color: white;
    }

    .modern-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .btn-view:hover { color: white; }
    .btn-edit:hover { color: white; }
    .btn-delete:hover { color: white; }

    .modern-card-footer {
        background: rgba(247, 250, 252, 0.8);
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(226, 232, 240, 0.5);
        font-size: 0.8rem;
        color: #718096;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .empty-state-icon {
        font-size: 4rem;
        color: #cbd5e0;
        margin-bottom: 2rem;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .modern-modal {
        backdrop-filter: blur(10px);
    }

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

    .modern-modal-body {
        padding: 2rem;
    }

    .modern-modal-footer {
        padding: 1.5rem;
        border: none;
        background: rgba(247, 250, 252, 0.8);
        border-radius: 0 0 20px 20px;
    }

    .modern-btn-secondary {
        background: #f7fafc;
        color: #4a5568;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .modern-btn-secondary:hover {
        background: #edf2f7;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .modern-btn-danger {
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .modern-btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(238, 90, 82, 0.4);
        color: white;
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

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .games-management-container {
            padding: 1rem 0;
        }

        .modern-page-header {
            padding: 1.5rem;
            margin: 0 1rem 1rem;
        }

        .modern-page-title {
            font-size: 1.5rem;
        }

        .modern-add-btn {
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
        }

        .modern-game-card {
            margin: 0 1rem 1.5rem;
        }

        .modern-btn-group {
            flex-direction: column;
        }
    }

    /* Animation delays for cards */
    .modern-game-card:nth-child(1) { animation-delay: 0.1s; }
    .modern-game-card:nth-child(2) { animation-delay: 0.2s; }
    .modern-game-card:nth-child(3) { animation-delay: 0.3s; }
    .modern-game-card:nth-child(4) { animation-delay: 0.4s; }
    .modern-game-card:nth-child(5) { animation-delay: 0.5s; }
    .modern-game-card:nth-child(6) { animation-delay: 0.6s; }
</style>
@endpush

@section('content')
<div class="games-management-container">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Modern Header -->
                <div class="modern-page-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h1 class="modern-page-title">
                            <i class="fas fa-gamepad text-primary"></i>
                            {{ __('app.profile.manage_games') }}
                        </h1>
                        <a href="{{ route('admin.games.create') }}" class="modern-add-btn text-decoration-none">
                            <i class="fas fa-plus me-2"></i>
                            {{ __('app.games.add_new_game') }}
                        </a>
                    </div>
                </div>

                <!-- Success Alert -->
                @if(session('success'))
                <div class="modern-alert modern-alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-3 text-success" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong>{{ __('app.common.success') }}!</strong>
                            <div>{{ session('success') }}</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Games Grid -->
                @if($games->count() > 0)
                <div class="row">
                    @foreach($games as $game)
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                        <div class="modern-game-card h-100">
                            <!-- Banner -->
                            <div class="game-banner-container position-relative">
                                <img src="{{ $game->banner_url }}" class="game-banner" alt="{{ $game->name }} Banner">
                                
                                <!-- Status Badge -->
                                <span class="position-absolute top-0 end-0 m-3 modern-badge {{ $game->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $game->status_label }}
                                </span>
                                
                                <!-- Esport Support Badge -->
                                @if($game->esport_support)
                                <span class="position-absolute top-0 start-0 m-3 modern-badge badge-esports">
                                    <i class="fas fa-trophy me-1"></i>Esports
                                </span>
                                @endif
                            </div>

                            <div class="modern-card-body d-flex flex-column">
                                <!-- Logo và Title -->
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $game->logo_url }}" class="game-logo me-3" alt="{{ $game->name }} Logo">
                                    <div>
                                        <h5 class="game-title">{{ $game->name }}</h5>
                                        <small class="game-genre">{{ $game->genre ?: __('app.games.not_categorized') }}</small>
                                    </div>
                                </div>

                                <!-- Game Info -->
                                <div class="game-info">
                                    @if($game->publisher)
                                    <div class="game-info-item">
                                        <i class="fas fa-building text-primary"></i>
                                        {{ __('app.games.publisher') }}: {{ $game->publisher }}
                                    </div>
                                    @endif

                                    @if($game->team_size)
                                    <div class="game-info-item">
                                        <i class="fas fa-users text-success"></i>
                                        {{ __('app.games.team_size') }}: {{ $game->team_size }}
                                    </div>
                                    @endif

                                    @if($game->release_date)
                                    <div class="game-info-item">
                                        <i class="fas fa-calendar text-info"></i>
                                        {{ __('app.games.release_date') }}: {{ $game->release_date->format('d/m/Y') }}
                                    </div>
                                    @endif

                                    @if($game->description)
                                    <p class="card-text small text-muted mt-2">
                                        {{ Str::limit($game->description, 100) }}
                                    </p>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="modern-btn-group">
                                    <a href="{{ route('admin.games.show', $game->id) }}" class="modern-action-btn btn-view text-decoration-none">
                                        <i class="fas fa-eye me-1"></i> {{ __('app.common.view') }}
                                    </a>
                                    <a href="{{ route('admin.games.edit', $game->id) }}" class="modern-action-btn btn-edit text-decoration-none">
                                        <i class="fas fa-edit me-1"></i> {{ __('app.common.edit') }}
                                    </a>
                                    <button class="modern-action-btn btn-delete delete-game-btn" data-game-id="{{ $game->id }}" data-game-name="{{ $game->name }}">
                                        <i class="fas fa-trash me-1"></i> {{ __('app.common.delete') }}
                                    </button>
                                </div>
                            </div>

                            <!-- Footer Info -->
                            <div class="modern-card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-user-plus me-1"></i>
                                        {{ $game->creator ? $game->creator->name : 'N/A' }}
                                    </span>
                                    <span>{{ $game->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $games->links() }}
                </div>

                @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-gamepad"></i>
                    </div>
                    <h4 class="text-muted mb-3">{{ __('app.games.no_games') }}</h4>
                    <p class="text-muted mb-4">{{ __('app.games.no_games_description') }}</p>
                    <a href="{{ route('admin.games.create') }}" class="modern-add-btn text-decoration-none">
                        <i class="fas fa-plus me-2"></i>{{ __('app.games.add_new_game') }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modern Delete Confirmation Modal -->
<div class="modal fade modern-modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modern-modal-header">
                <h5 class="modal-title modern-modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ __('app.games.confirm_delete') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modern-modal-body">
                <div class="text-center">
                    <div class="mb-4">
                        <i class="fas fa-trash-alt text-danger" style="font-size: 3rem; opacity: 0.7;"></i>
                    </div>
                    <h6 class="mb-3">{{ __('app.games.confirm_delete_message') }} <strong id="deleteGameName" class="text-primary"></strong>?</h6>
                    <div class="alert alert-warning border-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>{{ __('app.common.warning') }}:</strong> {{ __('app.games.delete_warning') }}
                    </div>
                </div>
            </div>
            <div class="modal-footer modern-modal-footer">
                <button type="button" class="modern-btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>{{ __('app.common.cancel') }}
                </button>
                <button type="button" class="modern-btn-danger" id="confirmDelete">
                    <i class="fas fa-trash me-2"></i>{{ __('app.games.delete_game') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Modern Notification System
    function showModernNotification(title, message, type = 'info', duration = 5000) {
        const container = document.getElementById('notificationsContainer');
        const notification = document.createElement('div');
        
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };

        const colors = {
            success: '#48bb78',
            error: '#f56565',
            warning: '#ed8936',
            info: '#4299e1'
        };

        notification.className = `modern-notification notification-${type}`;
        notification.innerHTML = `
            <div class="me-3" style="color: ${colors[type]};">
                <i class="${icons[type]}" style="font-size: 1.25rem;"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-semibold text-dark" style="font-size: 0.95rem;">${title}</div>
                <div class="text-muted" style="font-size: 0.85rem;">${message}</div>
            </div>
            <button type="button" class="btn-close btn-sm ms-3" onclick="closeNotification(this.parentElement)"></button>
        `;

        container.appendChild(notification);

        // Trigger show animation
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        // Auto hide
        if (duration > 0) {
            setTimeout(() => {
                closeNotification(notification);
            }, duration);
        }

        return notification;
    }

    function closeNotification(notification) {
        notification.classList.remove('show');
        notification.classList.add('hide');
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.parentElement.removeChild(notification);
            }
        }, 300);
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-game-btn');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const confirmDeleteBtn = document.getElementById('confirmDelete');

        // Delete button handlers
        deleteButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const gameId = this.getAttribute('data-game-id');
                const gameName = this.getAttribute('data-game-name');

                document.getElementById('deleteGameName').textContent = gameName;
                deleteModal.show();

                // Store game ID for deletion
                confirmDeleteBtn.setAttribute('data-game-id', gameId);
            });
        });

        // Confirm delete handler
        confirmDeleteBtn.addEventListener('click', function() {
            const gameId = this.getAttribute('data-game-id');
            
            if (!gameId) {
                showModernNotification('{{ __("app.common.error") }}', '{{ __("app.games.game_id_not_found") }}', 'error');
                return;
            }

            // Update button to loading state
            const originalHtml = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("app.games.deleting") }}';
            this.disabled = true;

            fetch(`/admin/games/${gameId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        deleteModal.hide();
                        showModernNotification(
                            '{{ __("app.games.delete_success") }}',
                            '{{ __("app.games.delete_success_message") }}',
                            'success'
                        );
                        
                        // Reload page after short delay for better UX
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        throw new Error(data.message || '{{ __("app.games.delete_error") }}');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showModernNotification(
                        '{{ __("app.games.delete_error_title") }}',
                        error.message || '{{ __("app.games.delete_error_message") }}',
                        'error'
                    );
                })
                .finally(() => {
                    // Reset button state
                    this.innerHTML = originalHtml;
                    this.disabled = false;
                });
        });

        // Welcome notification for modern UI
        setTimeout(() => {
            showModernNotification(
                '{{ __("app.games.ui_updated") }}',
                '{{ __("app.games.ui_updated_message") }}',
                'success',
                4000
            );
        }, 800);

        // Add smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';

        // Animate cards on scroll (if Intersection Observer is supported)
        if ('IntersectionObserver' in window) {
            const cardObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationDelay = '0s';
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            });

            document.querySelectorAll('.modern-game-card').forEach(card => {
                card.style.animationPlayState = 'paused';
                cardObserver.observe(card);
            });
        }
    });

    // Add some interactive enhancements
    document.addEventListener('mouseover', function(e) {
        if (e.target.matches('.modern-action-btn')) {
            e.target.style.transform = 'translateY(-2px) scale(1.02)';
        }
    });

    document.addEventListener('mouseout', function(e) {
        if (e.target.matches('.modern-action-btn')) {
            e.target.style.transform = '';
        }
    });
</script>
@endsection