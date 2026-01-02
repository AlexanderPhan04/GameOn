@extends('layouts.app')

@section('title', __('app.name'))

@push('styles')
<style>
    .hero-section {
        position: relative;
        background: radial-gradient(1200px 600px at -10% -10%, rgba(0, 229, 255, 0.1) 0%, rgba(0, 229, 255, 0) 60%),
                    linear-gradient(135deg, #000055 0%, #000022 50%, #000814 100%);
        min-height: 100vh;
        color: #ffffff;
        display: flex;
        align-items: center;
        z-index: 1;
        margin-top: 0;
        padding-top: 90px;
        overflow: hidden;
    }
    
    @media (max-width: 768px) {
        .hero-section {
            padding-top: 80px;
        }
    }

    .hero-section .orb {
        position: absolute;
        width: 280px;
        height: 280px;
        border-radius: 50%;
        filter: blur(40px);
        opacity: 0.35;
    }

    .orb.orb-1 { background: #00E5FF; top: 8%; left: -60px; }
    .orb.orb-2 { background: #000055; bottom: 10%; right: 10%; width: 200px; height: 200px; }
    .orb.orb-3 { background: #00E5FF; top: 30%; right: -60px; width: 220px; height: 220px; opacity: 0.2; }

    .feature-card {
        border: none;
        border-radius: 15px;
        background: #0d1b2a;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3), 0 0 20px rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.2);
        transition: all 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 22px 50px rgba(0, 0, 0, 0.4), 0 0 30px rgba(0, 229, 255, 0.2);
        border-color: rgba(0, 229, 255, 0.4);
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 700;
        color: #00E5FF;
        font-family: 'Rajdhani', sans-serif;
        text-shadow: 0 0 20px rgba(0, 229, 255, 0.5);
    }

    .neon-text {
        color: #00E5FF;
        text-shadow: 0 0 10px rgba(0, 229, 255, 0.5), 0 0 20px rgba(0, 229, 255, 0.3);
    }

    /* Joined Buttons Style */
    .joined-buttons-wrapper {
        display: inline-flex;
        box-shadow: 0 0 25px rgba(0, 229, 255, 0.3), 0 0 50px rgba(0, 229, 255, 0.2);
        border-radius: 9999px;
    }

    .btn-joined-primary {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #00E5FF; /* Neon */
        color: #000814; /* Void */
        font-family: 'Rajdhani', sans-serif;
        font-weight: 700;
        font-size: 1.125rem;
        padding: 1rem 2rem;
        border-radius: 9999px 0 0 9999px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-joined-primary:hover {
        background: #00ccdd;
        color: #000814;
    }

    .btn-joined-primary i {
        transition: transform 0.3s ease;
    }

    .btn-joined-primary:hover i {
        transform: rotate(12deg);
    }

    .btn-joined-secondary {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #000055; /* Deep Navy */
        color: #00E5FF; /* Neon */
        font-family: 'Rajdhani', sans-serif;
        font-weight: 700;
        font-size: 1.125rem;
        padding: 1rem 2rem;
        border-radius: 0 9999px 9999px 0;
        border: 2px solid #00E5FF;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-left: -2px;
    }

    .btn-joined-secondary:hover {
        background: #000077;
        color: #00E5FF;
    }

    .btn-joined-secondary i {
        color: #00E5FF;
        transition: transform 0.3s ease;
    }

    .btn-joined-secondary:hover i {
        transform: translateX(4px);
    }
    
    /* Development Team Section - Ensure spacing from footer and prevent overflow */
    section.bg-surface.mb-16 {
        margin-bottom: 4rem !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    
    @media (min-width: 768px) {
        section.bg-surface.mb-16 {
            margin-bottom: 5rem !important;
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }
    }
    
    @media (min-width: 1024px) {
        section.bg-surface.mb-16 {
            padding-left: 2rem !important;
            padding-right: 2rem !important;
        }
    }
    
    /* Ensure grid doesn't overflow and displays correctly */
    section.bg-surface .grid {
        max-width: 100% !important;
        margin-left: auto !important;
        margin-right: auto !important;
    }
    
    /* Force 4 columns on xl screens */
    @media (min-width: 1280px) {
        section.bg-surface .grid {
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        }
    }
    
    /* 2 columns on md screens */
    @media (min-width: 768px) and (max-width: 1279px) {
        section.bg-surface .grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    }
    
    /* Force social icons to be perfectly round */
    section.bg-surface .flex.gap-6 a {
        border-radius: 50% !important;
    }
</style>
@endpush

@section('content')

    <!-- Hero Banner với ảnh ESP -->
    <section class="relative overflow-hidden" style="margin-top: 64px; height: 85vh; min-height: 750px; max-height: 1300px; width: 100vw; position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw;">
        <div class="absolute inset-0 w-full h-full" style="width: 100vw; height: 100%;">
            <img src="{{ asset('esp.jpg') }}" alt="Esports Players" 
                 class="block absolute w-full h-full"
                 style="filter: brightness(0.3) contrast(1.2); width: 100vw; height: 100%; min-height: 100%; object-fit: cover; object-position: center 100%; bottom: 0; left: 0; z-index: 0;">
            <!-- Overlay để text dễ đọc hơn -->
            <div class="absolute inset-0 bg-gradient-to-b from-void via-void/70 to-void"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-void/80 via-transparent to-void/80"></div>
        </div>
        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-4 font-display" style="color: #FFFFFF; text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8), 0 0 20px rgba(0, 229, 255, 0.6), 0 0 40px rgba(0, 229, 255, 0.4); line-height: 1.2;">
                    {{ __('app.home.welcome_to') }}<br>
                    <span style="color: #00E5FF; text-shadow: 0 0 20px rgba(0, 229, 255, 0.8), 0 0 40px rgba(0, 229, 255, 0.6), 0 0 60px rgba(0, 229, 255, 0.4);">{{ __('app.name') }}</span>
                </h1>
                <p class="text-lg md:text-xl lg:text-2xl mb-8 font-body max-w-3xl mx-auto px-4" style="color: #FFFFFF; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.9), 0 0 10px rgba(0, 0, 0, 0.5); line-height: 1.6;">
                    {{ __('app.home.professional_esports_platform') }}<br>
                    {{ __('app.home.join_now_experience_professional_esports') }}
                </p>
                <div class="flex justify-center mb-8">
                    <div class="joined-buttons-wrapper">
                        @guest
                        <a href="{{ route('auth.register') }}" class="btn-joined-primary">
                            <i class="fas fa-rocket"></i>
                            <span>{{ __('app.home.get_started') }}</span>
                        </a>
                        <a href="{{ route('auth.login') }}" class="btn-joined-secondary">
                            <i class="fas fa-right-to-bracket"></i>
                            <span>{{ __('app.auth.login') }}</span>
                        </a>
                        @else
                        <a href="{{ route('posts.index') }}" class="btn-joined-primary" style="border-radius: 9999px;">
                            <i class="fas fa-bell"></i>
                            <span>You got something new?</span>
                        </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Wave divider -->
    <div style="line-height:0"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" preserveAspectRatio="none" style="display:block"><path fill="#0d1b2a" d="M0,96L60,106.7C120,117,240,139,360,144C480,149,600,139,720,117.3C840,96,960,64,1080,64C1200,64,1320,96,1380,112L1440,128L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z"></path></svg></div>

    <section class="py-16 bg-surface">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap justify-center items-center gap-8 lg:gap-12">
                <div class="text-center flex-1 min-w-[150px]">
                    <div class="stat-number">{{ number_format($stats['total_users'] ?? 0) }}</div>
                    <p class="text-text-muted text-lg mb-0 font-body mt-2">{{ __('app.home.registered_gamers') }}</p>
                </div>
                <div class="text-center flex-1 min-w-[150px]">
                    <div class="stat-number">{{ number_format($stats['total_teams'] ?? 0) }}</div>
                    <p class="text-text-muted text-lg mb-0 font-body mt-2">{{ __('app.home.active_teams') }}</p>
                </div>
                <div class="text-center flex-1 min-w-[150px]">
                    <div class="stat-number">{{ number_format($stats['active_tournaments'] ?? 0) }}</div>
                    <p class="text-text-muted text-lg mb-0 font-body mt-2">{{ __('app.home.tournaments') }}</p>
                </div>
                <div class="text-center flex-1 min-w-[150px]">
                    <div class="stat-number">100%</div>
                    <p class="text-text-muted text-lg mb-0 font-body mt-2">{{ __('app.home.satisfaction') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-void">
        <div class="container mx-auto px-4">
            <div class="text-center mb-20">
                <div class="max-w-3xl mx-auto">
                    <h2 class="text-4xl lg:text-5xl font-bold mb-6 font-display neon-text">{{ __('app.home.featured_features') }}</h2>
                    <p class="text-text-muted text-xl mb-0 font-body">{{ __('app.home.discover_powerful_features') }}</p>
                </div>
            </div>
            <div class="features-container">
                <style>
                .features-container {
                    display: grid;
                    grid-template-columns: 1fr;
                    gap: 2rem;
                    justify-items: center;
                    max-width: 72rem;
                    margin: 0 auto;
                }
                
                @media (min-width: 768px) {
                    .features-container {
                        grid-template-columns: repeat(3, 1fr);
                        gap: 2rem;
                    }
                }
                
                .feature-item {
                    width: 100%;
                    max-width: 320px;
                }
                </style>
                <div class="feature-item">
                    <div class="feature-card h-full text-center p-8">
                        <i class="fas fa-users text-neon mb-4 text-5xl" style="filter: drop-shadow(0 0 10px rgba(0, 229, 255, 0.5));"></i>
                        <h5 class="text-xl font-semibold mb-3 font-display text-text-main">{{ __('app.home.team_management') }}</h5>
                        <p class="text-text-muted font-body">{{ __('app.home.create_manage_teams') }}</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-card h-full text-center p-8">
                        <i class="fas fa-trophy text-neon mb-4 text-5xl" style="filter: drop-shadow(0 0 10px rgba(0, 229, 255, 0.5));"></i>
                        <h5 class="text-xl font-semibold mb-3 font-display text-text-main">{{ __('app.home.tournament_organization') }}</h5>
                        <p class="text-text-muted font-body">{{ __('app.home.organize_tournaments') }}</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-card h-full text-center p-8">
                        <i class="fas fa-chart-line text-neon mb-4 text-5xl" style="filter: drop-shadow(0 0 10px rgba(0, 229, 255, 0.5));"></i>
                        <h5 class="text-xl font-semibold mb-3 font-display text-text-main">{{ __('app.home.detailed_statistics') }}</h5>
                        <p class="text-text-muted font-body">{{ __('app.home.track_performance_rankings') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Tournaments Section -->
    @if($stats['featured_tournaments']->count() > 0)
    <section class="py-16 bg-surface">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl lg:text-5xl font-bold mb-4 font-display neon-text">{{ __('app.home.featured_tournaments') }}</h2>
                <p class="text-xl text-text-muted font-body">{{ __('app.home.ongoing_upcoming_tournaments') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($stats['featured_tournaments'] as $tournament)
                <div class="mb-4">
                    <div class="feature-card h-full">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="mr-3">
                                    @if($tournament->image_url)
                                    <img src="{{ Storage::url($tournament->image_url) }}" alt="{{ $tournament->name }}"
                                        class="rounded w-12 h-12 object-cover border border-neon/30">
                                    @else
                                    <div class="bg-deep-navy rounded w-12 h-12 flex items-center justify-center border border-neon/30">
                                        <i class="fas fa-trophy text-neon"></i>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-1 font-bold text-text-main font-display">{{ $tournament->name }}</h6>
                                    <small class="text-text-muted font-body">{{ $tournament->game->name ?? 'Unknown Game' }}</small>
                                </div>
                            </div>
                            <p class="text-text-muted text-sm mb-4 font-body">{{ Str::limit($tournament->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold font-display {{ $tournament->status === 'active' ? 'bg-neon/20 text-neon border border-neon/50' : 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/50' }}">
                                    {{ ucfirst($tournament->status) }}
                                </span>
                                <small class="text-text-muted font-body">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ \Carbon\Carbon::parse($tournament->start_date)->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-10">
                <a href="{{ route('tournaments.index') }}" class="btn-primary-custom font-semibold py-3 px-8 rounded-full font-display inline-block">
                    <i class="fas fa-trophy mr-2"></i>{{ __('app.home.view_all_tournaments') }}
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Top Teams Section -->
    @if($stats['top_teams']->count() > 0)
    <section class="py-16 bg-void">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl lg:text-5xl font-bold mb-4 font-display neon-text">{{ __('app.home.top_teams') }}</h2>
                <p class="text-xl text-text-muted font-body">{{ __('app.home.outstanding_popular_teams') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach($stats['top_teams'] as $team)
                <div class="mb-4">
                    <div class="feature-card h-full text-center">
                        <div class="p-6">
                            <div class="mb-4">
                                @if($team->logo)
                                <img src="{{ Storage::url($team->logo) }}" alt="{{ $team->team_name }}"
                                    class="rounded-full w-16 h-16 object-cover mx-auto border-2 border-neon/30">
                                @else
                                <div class="bg-gradient-to-r from-deep-navy to-neon rounded-full w-16 h-16 mx-auto flex items-center justify-center border-2 border-neon/50">
                                    <i class="fas fa-users text-white text-xl"></i>
                                </div>
                                @endif
                            </div>
                            <h6 class="font-bold mb-2 text-text-main font-display">{{ $team->name }}</h6>
                            <p class="text-text-muted text-sm mb-3 font-body">{{ Str::limit($team->description, 60) }}</p>
                            <div class="flex justify-center items-center">
                                <span class="px-3 py-1 bg-neon/20 text-neon rounded-full text-sm border border-neon/50 font-display">
                                    <i class="fas fa-users mr-1"></i>
                                    {{ $team->active_members_count }} thành viên
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-10">
                <a href="{{ route('search.view') }}" class="btn-primary-custom font-semibold py-3 px-8 rounded-full font-display inline-block">
                    <i class="fas fa-users mr-2"></i>{{ __('app.home.explore_gamers') }}
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Development Team Section -->
    <section class="pt-20 pb-32 bg-surface mb-16">
        <div class="container mx-auto px-4 md:px-6 lg:px-8 xl:px-12">
            <div class="text-center mb-12">
                <h2 class="font-display font-bold text-2xl sm:text-4xl md:text-5xl uppercase tracking-wider mb-2">
                    Đội Ngũ <span class="text-neon">Phát Triển</span>
                </h2>
                <div class="h-1 w-24 bg-gradient-to-r from-transparent via-brand to-transparent mx-auto"></div>
                <p class="text-text-muted mt-4 max-w-lg mx-auto font-body text-sm sm:text-base px-4">
                    Những người đứng sau sự thành công của hệ thống Game On.
                </p>
            </div>

            <!-- Team Grid - Responsive -->
            <div class="team-grid">
                <style>
                    .team-grid {
                        display: grid;
                        grid-template-columns: repeat(2, 1fr);
                        gap: 12px;
                        max-width: 1200px;
                        margin: 0 auto;
                        padding: 0 8px;
                    }
                    
                    @media (min-width: 640px) {
                        .team-grid {
                            grid-template-columns: repeat(2, 1fr);
                            gap: 20px;
                            padding: 0 16px;
                        }
                    }
                    
                    @media (min-width: 1024px) {
                        .team-grid {
                            grid-template-columns: repeat(4, 1fr);
                            gap: 24px;
                        }
                    }
                    
                    .team-card {
                        background: linear-gradient(135deg, rgba(30, 41, 59, 0.4), rgba(15, 23, 42, 0.6), rgba(2, 6, 23, 0.8));
                        backdrop-filter: blur(8px);
                        border: 1px solid rgba(71, 85, 105, 0.3);
                        border-radius: 16px;
                        padding: 16px;
                        transition: all 0.3s ease;
                    }
                    
                    @media (min-width: 640px) {
                        .team-card {
                            border-radius: 24px;
                            padding: 24px;
                        }
                    }
                    
                    .team-card:hover {
                        transform: translateY(-4px);
                        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
                    }
                    
                    .team-avatar {
                        width: 60px;
                        height: 60px;
                    }
                    
                    @media (min-width: 640px) {
                        .team-avatar {
                            width: 80px;
                            height: 80px;
                        }
                    }
                    
                    .team-name {
                        font-size: 14px;
                    }
                    
                    @media (min-width: 640px) {
                        .team-name {
                            font-size: 18px;
                        }
                    }
                    
                    .team-position {
                        font-size: 11px;
                    }
                    
                    @media (min-width: 640px) {
                        .team-position {
                            font-size: 14px;
                        }
                    }
                    
                    .team-role-badge {
                        font-size: 9px;
                        padding: 4px 8px;
                    }
                    
                    @media (min-width: 640px) {
                        .team-role-badge {
                            font-size: 11px;
                            padding: 6px 12px;
                        }
                    }
                    
                    .team-social {
                        width: 32px;
                        height: 32px;
                    }
                    
                    @media (min-width: 640px) {
                        .team-social {
                            width: 40px;
                            height: 40px;
                        }
                    }
                    
                    .team-social svg {
                        width: 14px;
                        height: 14px;
                    }
                    
                    @media (min-width: 640px) {
                        .team-social svg {
                            width: 18px;
                            height: 18px;
                        }
                    }
                </style>
                
                @php
                    $teamMembers = [
                        [
                            'name' => 'Alexander Phan',
                            'role' => 'Leader',
                            'position' => 'Project Manager',
                            'image' => 'AlexanderPhan.jpg',
                            'hover_color' => '#00E5FF',
                            'hover_shadow' => 'rgba(0, 229, 255, 0.15)',
                        ],
                        [
                            'name' => 'Đỗ Thế An',
                            'role' => 'Dev',
                            'position' => 'Backend Developer',
                            'image' => 'DoTheAn.jpg',
                            'hover_color' => '#a855f7',
                            'hover_shadow' => 'rgba(168, 85, 247, 0.15)',
                        ],
                        [
                            'name' => 'Lê Khánh An',
                            'role' => 'Dev',
                            'position' => 'Frontend Developer',
                            'image' => 'LeKhanhAn.jpg',
                            'hover_color' => '#eab308',
                            'hover_shadow' => 'rgba(234, 179, 8, 0.15)',
                        ],
                        [
                            'name' => 'Nguyễn Phương Anh',
                            'role' => 'UI/UX',
                            'position' => 'UI/UX Designer',
                            'image' => 'NguyenPhuongAnh.png',
                            'hover_color' => '#ec4899',
                            'hover_shadow' => 'rgba(236, 72, 153, 0.15)',
                        ],
                    ];
                @endphp

                @foreach($teamMembers as $index => $member)
                @php
                    $imagePath = asset('contributors/' . $member['image']);
                    $imageExists = file_exists(public_path('contributors/' . $member['image']));
                @endphp
                <div class="team-card">
                    <div class="flex flex-col items-center text-center">
                        <!-- Avatar Section -->
                        <div class="relative mb-3 sm:mb-4">
                            <div class="team-avatar relative" style="border-radius: 50%; padding: 2px; background: linear-gradient(135deg, {{ $member['hover_color'] }}, transparent, {{ $member['hover_color'] }});">
                                @if($imageExists)
                                    <img src="{{ $imagePath }}" alt="{{ $member['name'] }}" 
                                        style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; background: #1e293b;">
                                @else
                                    <div style="width: 100%; height: 100%; border-radius: 50%; background: #1e293b; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user text-lg sm:text-2xl" style="color: {{ $member['hover_color'] }};"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Role Badge -->
                            <div class="absolute -bottom-1 sm:-bottom-2 left-1/2 transform -translate-x-1/2">
                                <div class="team-role-badge rounded-md sm:rounded-lg font-bold uppercase tracking-wider shadow-lg"
                                     style="background: {{ $member['hover_color'] }}; color: #000; box-shadow: 0 0 15px {{ $member['hover_shadow'] }};">
                                    {{ $member['role'] }}
                                </div>
                            </div>
                        </div>

                        <!-- Name -->
                        <h3 class="team-name font-display font-bold text-white mb-1 sm:mb-2">
                            {{ $member['name'] === 'Alexander Phan' ? 'Phan Nhật Quân' : $member['name'] }}
                        </h3>
                        
                        <!-- Position -->
                        <p class="team-position text-slate-400 mb-2 sm:mb-4 font-body">
                            {{ $member['position'] }}
                        </p>
                        
                        <!-- Social Links -->
                        <div class="flex gap-2 sm:gap-3">
                            <a href="#" class="team-social flex items-center justify-center rounded-lg sm:rounded-xl bg-slate-800/30 border border-slate-600/30 text-slate-400 hover:text-white transition-all duration-300" style="--hover-color: {{ $member['hover_color'] }};">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                            </a>
                            <a href="#" class="team-social flex items-center justify-center rounded-lg sm:rounded-xl bg-slate-800/30 border border-slate-600/30 text-slate-400 hover:text-white transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
