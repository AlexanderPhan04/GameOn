@extends('layouts.app')

@section('title', __('app.profile.personal_info'))

@push('styles')
<style>
/* Additional profile-specific styles not covered by critical CSS */
.profile-loading {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 200px;
    color: rgba(255, 255, 255, 0.7);
}

.profile-loading i {
    font-size: 2rem;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Success button styles */
.btn-modern.btn-success {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    color: white;
    border-color: rgba(34, 197, 94, 0.3);
}

.btn-modern.btn-success:hover {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(34, 197, 94, 0.2);
    border-color: rgba(34, 197, 94, 0.4);
}
</style>
@endpush

@section('content')
<div class="modern-profile-container">
    <!-- Hero Section -->
    <div class="profile-hero">
        <div class="hero-background">
            <div class="animated-particles"></div>
            <div class="hero-overlay"></div>
        </div>
        
        <div class="container">
            <div class="hero-content">
                <!-- Profile Avatar & Basic Info -->
                <div class="profile-avatar-section">
                    <div class="avatar-wrapper">
                        @if($user->avatar)
                        <img src="{{ get_avatar_url($user->avatar) }}" alt="Avatar" class="profile-avatar-img">
                        @else
                        <div class="profile-avatar-placeholder">
                            <span class="avatar-initials">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                        </div>
                        @endif
                        <div class="avatar-status online"></div>
                    </div>
                    
                    <div class="profile-main-info">
                        <h1 class="profile-name">{{ $user->full_name ?: $user->name }}</h1>
                        @if($user->id_app)
                        <p class="profile-username">{{ $user->id_app }}</p>
                        @else
                        <p class="profile-username">{{ 'APP' . str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</p>
                        @endif
                        
                        <!-- Role Badge -->
                        <div class="role-badge-wrapper">
                            @if($user->user_role === 'super_admin')
                            <div class="role-badge super-admin">
                                <i class="fas fa-crown"></i>
                                <span>Super Administrator</span>
                            </div>
                            @elseif($user->user_role === 'admin')
                            <div class="role-badge admin">
                                <i class="fas fa-shield-alt"></i>
                                <span>Administrator</span>
                            </div>
                            @elseif($user->user_role === 'manager')
                            <div class="role-badge manager">
                                <i class="fas fa-users"></i>
                                <span>Team Manager</span>
                            </div>
                            @elseif($user->user_role === 'player')
                            <div class="role-badge player">
                                <i class="fas fa-gamepad"></i>
                                <span>Pro Player</span>
                            </div>
                            @else
                            <div class="role-badge viewer">
                                <i class="fas fa-eye"></i>
                                <span>Viewer</span>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="profile-actions">
                            <a href="{{ route('profile.edit') }}" class="btn-modern btn-primary">
                                <i class="fas fa-edit"></i>
                                <span>{{ __('app.common.edit') }} {{ __('app.profile.personal_info') }}</span>
                            </a>
                            <a href="{{ route('profile.change-password') }}" class="btn-modern btn-secondary">
                                <i class="fas fa-key"></i>
                                <span>{{ __('app.profile.change_password') }}</span>
                            </a>
                            
                            @if($user->canUpgradeToPlayer())
                            <a href="{{ route('player.upgrade') }}" class="btn-modern btn-success">
                                <i class="fas fa-gamepad"></i>
                                <span>{{ __('app.auth.become_player') }}</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container profile-main-content">
        @if(session('success'))
        <div class="alert-modern alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="alert-close" data-bs-dismiss="alert">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif

        <div class="row g-4">
            <!-- Personal Information -->
            <div class="col-12">
                <!-- Personal Details Card -->
                <div class="info-card">
                    <div class="card-header">
                        <div class="card-header-content">
                            <div class="card-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="card-title">
                                <h3>{{ __('app.profile.personal_info') }}</h3>
                                <p>{{ __('app.profile.personal_info_description') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-content">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-envelope"></i>
                                    <span>Email</span>
                                </div>
                                <div class="info-value">{{ $user->email }}</div>
                            </div>

                            @if($user->phone)
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-phone"></i>
                                    <span>{{ __('app.profile.phone') }}</span>
                                </div>
                                <div class="info-value">{{ $user->phone }}</div>
                            </div>
                            @endif

                            @if($user->date_of_birth)
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-birthday-cake"></i>
                                    <span>{{ __('app.profile.date_of_birth') }}</span>
                                </div>
                                <div class="info-value">
                                    {{ \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') }}
                                    <small class="age-text">({{ \Carbon\Carbon::parse($user->date_of_birth)->age }} {{ __('app.profile.years_old') }})</small>
                                </div>
                            </div>
                            @endif

                            @if($user->country)
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-flag"></i>
                                    <span>{{ __('app.profile.country') }}</span>
                                </div>
                                <div class="info-value">{{ $user->country }}</div>
                            </div>
                            @endif

                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-calendar-plus"></i>
                                    <span>{{ __('app.profile.joined') }}</span>
                                </div>
                                <div class="info-value">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>

                        @if($user->bio)
                        <div class="bio-section">
                            <div class="bio-header">
                                <i class="fas fa-quote-left"></i>
                                <h4>{{ __('app.profile.about_me') }}</h4>
                            </div>
                            <div class="bio-content">
                                {{ $user->bio }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                @if($user->user_role === 'player')
                <!-- Teams Section -->
                <div class="info-card">
                    <div class="card-header">
                        <div class="card-header-content">
                            <div class="card-icon teams">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-title">
                                <h3>{{ __('app.teams.my_teams') }}</h3>
                                <p>{{ __('app.profile.teams_description') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('teams.create') }}" class="btn-modern btn-small btn-primary">
                            <i class="fas fa-plus"></i>
                            <span>{{ __('app.teams.create_new_team') }}</span>
                        </a>
                    </div>
                    
                    <div class="card-content">
                        @forelse($user->teams as $team)
                        <div class="team-item">
                            <div class="team-logo">
                                @if($team->logo_url)
                                <img src="{{ $team->logo_url }}" alt="Team Logo">
                                @else
                                <div class="team-logo-placeholder">
                                    <span>{{ strtoupper(substr($team->name, 0, 2)) }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="team-info">
                                <h4 class="team-name">{{ $team->name }}</h4>
                                <div class="team-meta">
                                    @if($team->pivot->role === 'captain')
                                    <span class="team-role captain">
                                        <i class="fas fa-crown"></i>
                                        {{ __('app.teams.captain') }}
                                    </span>
                                    @else
                                    <span class="team-role member">
                                        <i class="fas fa-user"></i>
                                        {{ __('app.teams.members') }}
                                    </span>
                                    @endif
                                    <span class="team-joined">
                                        {{ __('app.profile.joined') }} {{ \Carbon\Carbon::parse($team->pivot->joined_at)->format('d/m/Y') }}
                                    </span>
                                </div>
                                @if($team->description)
                                <p class="team-description">{{ Str::limit($team->description, 100) }}</p>
                                @endif
                            </div>
                            
                            <div class="team-actions">
                                <a href="{{ route('teams.show', $team->id) }}" class="btn-modern btn-small btn-outline">
                                    <i class="fas fa-eye"></i>
                                    <span>{{ __('app.dashboard.view_details') }}</span>
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4>{{ __('app.profile.no_teams_joined') }}</h4>
                            <p>{{ __('app.profile.no_teams_description') }}</p>
                            <div class="empty-actions">
                                <a href="{{ route('teams.index') }}" class="btn-modern btn-primary">
                                    <i class="fas fa-search"></i>
                                    <span>{{ __('app.search.search') }} {{ __('app.teams.my_teams') }}</span>
                                </a>
                                <a href="{{ route('teams.create') }}" class="btn-modern btn-secondary">
                                    <i class="fas fa-plus"></i>
                                    <span>{{ __('app.teams.create_new_team') }}</span>
                                </a>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
                @endif

                <!-- Account Settings -->
                <div class="info-card">
                    <div class="card-header">
                        <div class="card-header-content">
                            <div class="card-icon settings">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="card-title">
                                <h3>{{ __('app.profile.account_settings') }}</h3>
                                <p>{{ __('app.profile.account_settings_description') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-content">
                        <div class="settings-menu">
                            <a href="{{ route('profile.edit') }}" class="setting-item">
                                <div class="setting-icon">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="setting-content">
                                    <div class="setting-title">{{ __('app.common.edit') }} {{ __('app.profile.personal_info') }}</div>
                                    <div class="setting-description">{{ __('app.profile.update_personal_info') }}</div>
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </a>

                            <a href="{{ route('profile.change-password') }}" class="setting-item">
                                <div class="setting-icon">
                                    <i class="fas fa-key"></i>
                                </div>
                                <div class="setting-content">
                                    <div class="setting-title">{{ __('app.profile.change_password') }}</div>
                                    <div class="setting-description">{{ __('app.profile.update_password_security') }}</div>
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </a>

                            <div class="setting-item">
                                <div class="setting-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="setting-content">
                                    <div class="setting-title">{{ __('app.profile.security') }}</div>
                                    <div class="setting-description">{{ __('app.profile.two_factor_auth') }}</div>
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </div>

                            <div class="setting-item">
                                <div class="setting-icon">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <div class="setting-content">
                                    <div class="setting-title">{{ __('app.profile.notifications') }}</div>
                                    <div class="setting-description">{{ __('app.profile.notification_settings') }}</div>
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
