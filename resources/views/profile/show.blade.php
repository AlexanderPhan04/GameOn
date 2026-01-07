@extends('layouts.app')

@section('title', __('app.profile.personal_info'))

@push('styles')
<style>
    .profile-container { background: #000814; min-height: 100vh; }

    /* Hero Section */
    .profile-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .profile-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #00E5FF, #8b5cf6, transparent);
    }

    /* Avatar */
    .avatar-section { display: flex; align-items: center; gap: 2rem; flex-wrap: wrap; }
    .avatar-wrapper { position: relative; }
    .avatar-img {
        width: 120px; height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(0, 229, 255, 0.3);
        box-shadow: 0 0 30px rgba(0, 229, 255, 0.2);
    }
    .avatar-placeholder {
        width: 120px; height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: flex; align-items: center; justify-content: center;
        border: 4px solid rgba(139, 92, 246, 0.3);
        box-shadow: 0 0 30px rgba(139, 92, 246, 0.2);
    }
    .avatar-initials { color: white; font-size: 2.5rem; font-weight: 700; }
    .avatar-status {
        position: absolute;
        bottom: 8px; right: 8px;
        width: 20px; height: 20px;
        background: #22c55e;
        border-radius: 50%;
        border: 3px solid #0d1b2a;
    }

    .profile-info { flex: 1; }
    .profile-name { font-family: 'Rajdhani', sans-serif; font-size: 2rem; font-weight: 700; color: #FFFFFF; margin: 0 0 0.25rem 0; }
    .profile-id { color: #64748b; font-size: 0.9rem; margin-bottom: 0.75rem; }

    /* Role Badge */
    .role-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.5rem 1rem; border-radius: 25px;
        font-size: 0.8rem; font-weight: 700; text-transform: uppercase;
        margin-bottom: 1rem;
    }
    .role-badge.super-admin { background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }
    .role-badge.admin { background: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); }
    .role-badge.participant { background: rgba(99, 102, 241, 0.2); color: #818cf8; border: 1px solid rgba(99, 102, 241, 0.3); }

    /* Buttons */
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
        gap: 0.5rem;
        text-decoration: none;
    }
    .btn-neon:hover {
        background: rgba(0, 229, 255, 0.15);
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.4);
        color: #FFFFFF;
        transform: translateY(-2px);
    }
    .btn-neon-secondary {
        background: rgba(100, 116, 139, 0.2);
        border-color: rgba(148, 163, 184, 0.3);
        color: #94a3b8;
    }
    .btn-neon-secondary:hover { background: rgba(148, 163, 184, 0.2); box-shadow: 0 0 15px rgba(148, 163, 184, 0.3); }
    .btn-neon-success {
        background: linear-gradient(135deg, #065f46, #047857);
        border-color: rgba(34, 197, 94, 0.4);
        color: #22c55e;
    }
    .btn-neon-success:hover { box-shadow: 0 0 20px rgba(34, 197, 94, 0.4); color: #FFFFFF; }
    .profile-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; }

    /* Cards */
    .profile-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .profile-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .card-header-left { display: flex; align-items: center; gap: 1rem; }
    .card-icon {
        width: 45px; height: 45px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; color: white;
    }
    .icon-cyan { background: linear-gradient(135deg, #06b6d4, #0891b2); }
    .icon-purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .card-title { font-family: 'Rajdhani', sans-serif; font-size: 1.15rem; font-weight: 700; color: #FFFFFF; margin: 0; }
    .card-subtitle { color: #64748b; font-size: 0.85rem; margin: 0.25rem 0 0 0; }
    .profile-card-body { padding: 1.5rem; }

    /* Info Grid */
    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; }
    .info-item { padding: 1rem; background: rgba(0, 0, 0, 0.2); border-radius: 12px; border: 1px solid rgba(0, 229, 255, 0.05); }
    .info-label { display: flex; align-items: center; gap: 0.5rem; color: #64748b; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; }
    .info-label i { color: #00E5FF; }
    .info-value { color: #FFFFFF; font-size: 0.95rem; font-weight: 500; }
    .info-value small { color: #64748b; margin-left: 0.5rem; }

    /* Bio Section */
    .bio-section { margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(0, 229, 255, 0.1); }
    .bio-header { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; }
    .bio-header i { color: #8b5cf6; }
    .bio-header h4 { color: #FFFFFF; font-size: 1rem; font-weight: 600; margin: 0; }
    .bio-content { color: #94a3b8; font-size: 0.9rem; line-height: 1.6; background: rgba(0, 0, 0, 0.2); padding: 1rem; border-radius: 10px; border-left: 3px solid #8b5cf6; }

    /* Team Item */
    .team-item {
        display: flex; align-items: center; gap: 1rem;
        padding: 1rem;
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(0, 229, 255, 0.05);
        border-radius: 12px;
        margin-bottom: 0.75rem;
    }
    .team-logo { width: 50px; height: 50px; border-radius: 10px; overflow: hidden; }
    .team-logo img { width: 100%; height: 100%; object-fit: cover; }
    .team-logo-placeholder { width: 100%; height: 100%; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; }
    .team-info { flex: 1; }
    .team-name { color: #FFFFFF; font-weight: 600; margin: 0 0 0.25rem 0; }
    .team-meta { display: flex; gap: 1rem; font-size: 0.8rem; }
    .team-role { display: flex; align-items: center; gap: 0.25rem; }
    .team-role.captain { color: #f59e0b; }
    .team-role.member { color: #94a3b8; }
    .team-joined { color: #64748b; }

    /* Empty State */
    .empty-state { text-align: center; padding: 3rem 2rem; }
    .empty-icon { width: 80px; height: 80px; background: rgba(0, 229, 255, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
    .empty-icon i { font-size: 2rem; color: #64748b; }
    .empty-state h4 { color: #FFFFFF; font-size: 1.1rem; margin-bottom: 0.5rem; }
    .empty-state p { color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem; }
    .empty-actions { display: flex; justify-content: center; gap: 0.75rem; flex-wrap: wrap; }

    /* Alert */
    .alert-success { background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; color: #22c55e; display: flex; align-items: center; gap: 0.75rem; }

    @media (max-width: 768px) {
        .avatar-section { flex-direction: column; text-align: center; }
        .profile-info { text-align: center; }
        .profile-actions { justify-content: center; }
        .info-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="profile-container">
    <div class="max-w-5xl mx-auto px-4 py-6">
        <!-- Hero Section -->
        <div class="profile-hero">
            <div class="avatar-section">
                <div class="avatar-wrapper">
                    @if($user->avatar)
                    <img src="{{ get_avatar_url($user->avatar) }}" alt="Avatar" class="avatar-img">
                    @else
                    <div class="avatar-placeholder">
                        <span class="avatar-initials">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                    </div>
                    @endif
                    <div class="avatar-status"></div>
                </div>
                
                <div class="profile-info">
                    <h1 class="profile-name">{{ $user->full_name ?: $user->name }}</h1>
                    <p class="profile-id">{{ $user->id_app ?: 'APP' . str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</p>
                    
                    <!-- Role Badge -->
                    @if($user->user_role === 'super_admin')
                    <div class="role-badge super-admin"><i class="fas fa-crown"></i><span>Super Administrator</span></div>
                    @elseif($user->user_role === 'admin')
                    <div class="role-badge admin"><i class="fas fa-shield-alt"></i><span>Administrator</span></div>
                    @else
                    <div class="role-badge participant">
                        <i class="fas fa-gamepad"></i>
                        <span>Participant</span>
                        @if($user->is_verified_gamer)
                        <i class="fas fa-check-circle text-cyan-400 ml-1" title="Verified Gamer"></i>
                        @endif
                    </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="profile-actions">
                        <a href="{{ route('profile.edit') }}" class="btn-neon">
                            <i class="fas fa-edit"></i><span>{{ __('app.common.edit') }} {{ __('app.profile.personal_info') }}</span>
                        </a>
                        <a href="{{ route('profile.change-password') }}" class="btn-neon btn-neon-secondary">
                            <i class="fas fa-key"></i><span>{{ __('app.profile.change_password') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert -->
        @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- Personal Information Card -->
        <div class="profile-card">
            <div class="profile-card-header">
                <div class="card-header-left">
                    <div class="card-icon icon-cyan"><i class="fas fa-user"></i></div>
                    <div>
                        <h3 class="card-title">{{ __('app.profile.personal_info') }}</h3>
                        <p class="card-subtitle">{{ __('app.profile.personal_info_description') }}</p>
                    </div>
                </div>
            </div>
            <div class="profile-card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-envelope"></i><span>Email</span></div>
                        <div class="info-value">{{ $user->email }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-calendar-plus"></i><span>{{ __('app.profile.joined') }}</span></div>
                        <div class="info-value">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    @if($user->phone)
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-phone"></i><span>{{ __('app.profile.phone') }}</span></div>
                        <div class="info-value">{{ $user->phone }}</div>
                    </div>
                    @endif
                    @if($user->date_of_birth)
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-birthday-cake"></i><span>{{ __('app.profile.date_of_birth') }}</span></div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') }}<small>({{ \Carbon\Carbon::parse($user->date_of_birth)->age }} {{ __('app.profile.years_old') }})</small></div>
                    </div>
                    @endif
                    @if($user->country)
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-flag"></i><span>{{ __('app.profile.country') }}</span></div>
                        <div class="info-value">{{ $user->country }}</div>
                    </div>
                    @endif
                </div>
                @if($user->bio)
                <div class="bio-section">
                    <div class="bio-header"><i class="fas fa-quote-left"></i><h4>{{ __('app.profile.about_me') }}</h4></div>
                    <div class="bio-content">{{ $user->bio }}</div>
                </div>
                @endif
            </div>
        </div>

        @if($user->user_role === 'participant')
        <!-- Teams Card -->
        <div class="profile-card">
            <div class="profile-card-header">
                <div class="card-header-left">
                    <div class="card-icon icon-purple"><i class="fas fa-users"></i></div>
                    <div>
                        <h3 class="card-title">{{ __('app.teams.my_teams') }}</h3>
                        <p class="card-subtitle">{{ __('app.profile.teams_description') }}</p>
                    </div>
                </div>
                <a href="{{ route('teams.create') }}" class="btn-neon"><i class="fas fa-plus"></i><span>{{ __('app.teams.create_new_team') }}</span></a>
            </div>
            <div class="profile-card-body">
                @forelse($user->teams as $team)
                <div class="team-item">
                    <div class="team-logo">
                        @if($team->logo_url)
                        <img src="{{ $team->logo_url }}" alt="Team Logo">
                        @else
                        <div class="team-logo-placeholder">{{ strtoupper(substr($team->name, 0, 2)) }}</div>
                        @endif
                    </div>
                    <div class="team-info">
                        <h4 class="team-name">{{ $team->name }}</h4>
                        <div class="team-meta">
                            @if($team->pivot->role === 'captain')
                            <span class="team-role captain"><i class="fas fa-crown"></i>{{ __('app.teams.captain') }}</span>
                            @else
                            <span class="team-role member"><i class="fas fa-user"></i>{{ __('app.teams.members') }}</span>
                            @endif
                            <span class="team-joined">{{ __('app.profile.joined') }} {{ \Carbon\Carbon::parse($team->pivot->joined_at)->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('teams.show', $team->id) }}" class="btn-neon btn-neon-secondary"><i class="fas fa-eye"></i></a>
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-users"></i></div>
                    <h4>{{ __('app.profile.no_teams_joined') }}</h4>
                    <p>{{ __('app.profile.no_teams_description') }}</p>
                    <div class="empty-actions">
                        <a href="{{ route('teams.index') }}" class="btn-neon"><i class="fas fa-search"></i><span>{{ __('app.search.search') }}</span></a>
                        <a href="{{ route('teams.create') }}" class="btn-neon btn-neon-secondary"><i class="fas fa-plus"></i><span>{{ __('app.teams.create_new_team') }}</span></a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
