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
            <div class="text-center mb-12">
                <div class="max-w-3xl mx-auto">
                    <h2 class="text-4xl lg:text-5xl font-bold mb-4 font-display neon-text">{{ __('app.home.featured_features') }}</h2>
                    <p class="text-text-muted text-xl mb-0 font-body">{{ __('app.home.discover_powerful_features') }}</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-16 justify-items-center">
                <div class="w-full max-w-[350px]">
                    <div class="feature-card h-full text-center p-8">
                        <i class="fas fa-users text-neon mb-4 text-5xl" style="filter: drop-shadow(0 0 10px rgba(0, 229, 255, 0.5));"></i>
                        <h5 class="text-xl font-semibold mb-3 font-display text-text-main">{{ __('app.home.team_management') }}</h5>
                        <p class="text-text-muted font-body">{{ __('app.home.create_manage_teams') }}</p>
                    </div>
                </div>
                <div class="w-full max-w-[350px]">
                    <div class="feature-card h-full text-center p-8">
                        <i class="fas fa-trophy text-neon mb-4 text-5xl" style="filter: drop-shadow(0 0 10px rgba(0, 229, 255, 0.5));"></i>
                        <h5 class="text-xl font-semibold mb-3 font-display text-text-main">{{ __('app.home.tournament_organization') }}</h5>
                        <p class="text-text-muted font-body">{{ __('app.home.organize_tournaments') }}</p>
                    </div>
                </div>
                <div class="w-full max-w-[350px]">
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
                <a href="{{ route('players.index') }}" class="btn-primary-custom font-semibold py-3 px-8 rounded-full font-display inline-block">
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
                <h2 class="font-display font-bold text-4xl md:text-5xl uppercase tracking-wider mb-2">
                    Đội Ngũ <span class="text-neon">Phát Triển</span>
                </h2>
                <div class="h-1 w-24 bg-gradient-to-r from-transparent via-brand to-transparent mx-auto"></div>
                <p class="text-text-muted mt-4 max-w-lg mx-auto font-body">
                    Những người đứng sau sự thành công của hệ thống Game On.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8 max-w-7xl mx-auto">
                @php
                    $teamMembers = [
                        [
                            'name' => 'Alexander Phan',
                            'role' => 'Leader',
                            'position' => 'Project Manager',
                            'image' => 'AlexanderPhan.jpg',
                            'hover_color' => '#00E5FF',
                            'hover_shadow' => 'rgba(0, 229, 255, 0.15)',
                            'gradient_to' => '#00E5FF',
                            'gradient_bg' => 'rgba(0, 229, 255, 0.05)',
                            'role_bg' => '#000055',
                            'role_text' => '#00E5FF',
                            'role_border' => '#1a237e',
                            'text_hover' => '#00E5FF',
                        ],
                        [
                            'name' => 'Đỗ Thế An',
                            'role' => 'Dev',
                            'position' => 'Backend Developer',
                            'image' => 'DoTheAn.jpg',
                            'hover_color' => '#a855f7',
                            'hover_shadow' => 'rgba(168, 85, 247, 0.15)',
                            'gradient_to' => '#a855f7',
                            'gradient_bg' => 'rgba(168, 85, 247, 0.05)',
                            'role_bg' => '#2d0a4e',
                            'role_text' => '#a78bfa',
                            'role_border' => '#581c87',
                            'text_hover' => '#a78bfa',
                        ],
                        [
                            'name' => 'Lê Khánh An',
                            'role' => 'Dev',
                            'position' => 'Frontend Developer',
                            'image' => 'LeKhanhAn.jpg',
                            'hover_color' => '#eab308',
                            'hover_shadow' => 'rgba(234, 179, 8, 0.15)',
                            'gradient_to' => '#eab308',
                            'gradient_bg' => 'rgba(234, 179, 8, 0.05)',
                            'role_bg' => '#422006',
                            'role_text' => '#facc15',
                            'role_border' => '#854d0e',
                            'text_hover' => '#facc15',
                        ],
                        [
                            'name' => 'Nguyễn Phương Anh',
                            'role' => 'UI/UX',
                            'position' => 'UI/UX Designer',
                            'image' => 'NguyenPhuongAnh.png',
                            'hover_color' => '#ec4899',
                            'hover_shadow' => 'rgba(236, 72, 153, 0.15)',
                            'gradient_to' => '#ec4899',
                            'gradient_bg' => 'rgba(236, 72, 153, 0.05)',
                            'role_bg' => '#500724',
                            'role_text' => '#f9a8d4',
                            'role_border' => '#831843',
                            'text_hover' => '#f9a8d4',
                        ],
                    ];
                @endphp

                @foreach($teamMembers as $index => $member)
                @php
                    $imagePath = asset('contributors/' . $member['image']);
                    $imageExists = file_exists(public_path('contributors/' . $member['image']));
                @endphp
                <div class="group relative bg-surface border border-border rounded-2xl p-6 transition-all duration-300 hover:-translate-y-2 {{ $index === 0 ? 'hover:border-neon' : ($index === 1 ? 'hover:border-purple-500' : ($index === 2 ? 'hover:border-yellow-500' : 'hover:border-pink-500')) }} hover:shadow-[0_0_30px_{{ str_replace(['rgba(', ')', ' '], ['', '', '_'], $member['hover_shadow']) }}]"
                     style="border-color: #1a237e;"
                     onmouseover="this.style.borderColor='{{ $member['hover_color'] }}'; this.style.boxShadow='0 0 30px {{ $member['hover_shadow'] }}';"
                     onmouseout="this.style.borderColor='#1a237e'; this.style.boxShadow='none';">
                    <div class="absolute inset-0 bg-gradient-to-b opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl" 
                         style="background: linear-gradient(to bottom, {{ $member['gradient_bg'] }}, transparent);"></div>
                    
                    <div class="relative z-10 flex flex-col items-center text-center">
                        <div class="relative mb-4">
                            <div class="w-24 h-24 rounded-full p-1 overflow-hidden" style="background: linear-gradient(to top right, #1a237e, {{ $member['gradient_to'] }}); width: 96px; height: 96px; min-width: 96px; min-height: 96px; max-width: 96px; max-height: 96px;">
                                @if($imageExists)
                                    <img src="{{ $imagePath }}" alt="{{ $member['name'] }}" 
                                        class="rounded-full object-cover border-4 border-surface"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div class="w-full h-full rounded-full bg-void flex items-center justify-center border-4 border-surface">
                                        <i class="fas fa-user text-4xl" style="color: {{ $member['gradient_to'] }};"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 text-xs font-bold px-2 py-0.5 rounded border font-display uppercase tracking-widest whitespace-nowrap" 
                                 style="background-color: {{ $member['role_bg'] }}; color: {{ $member['role_text'] }}; border-color: {{ $member['role_border'] }};">
                                {{ $member['role'] }}
                            </div>
                        </div>

                        <h3 class="font-display font-bold text-xl text-white transition-colors mb-1 {{ $index === 0 ? 'group-hover:text-neon' : ($index === 1 ? 'group-hover:text-purple-400' : ($index === 2 ? 'group-hover:text-yellow-400' : 'group-hover:text-pink-400')) }}" 
                            style="color: white;"
                            onmouseover="this.style.color='{{ $member['text_hover'] }}';"
                            onmouseout="this.style.color='white';">
                            {{ $member['name'] }}
                        </h3>
                        <p class="text-sm text-text-muted mb-4 font-body">
                            {{ $member['position'] }}
                        </p>
                        
                        <!-- Social Links -->
                        <div class="flex gap-6">
                            @if($index === 0)
                                <!-- Leader: GitHub & Facebook -->
                                <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full bg-void border border-border text-slate-400 hover:text-white hover:border-neon hover:bg-brand transition-all" style="border-radius: 50% !important;">
                                    <i class="fab fa-github"></i>
                                </a>
                                <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full bg-void border border-border text-slate-400 hover:text-blue-500 hover:border-blue-500 hover:bg-void transition-all" style="border-radius: 50% !important;">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            @elseif($index === 1)
                                <!-- Backend Dev: GitHub & LinkedIn -->
                                <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full bg-void border border-border hover:bg-purple-900 hover:text-white transition-all" style="border-radius: 50% !important;">
                                    <i class="fab fa-github"></i>
                                </a>
                                <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full bg-void border border-border hover:bg-blue-600 hover:text-white transition-all" style="border-radius: 50% !important;">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            @elseif($index === 2)
                                <!-- Frontend Dev: GitHub & Instagram -->
                                <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full bg-void border border-border hover:bg-yellow-900 hover:text-white transition-all" style="border-radius: 50% !important;">
                                    <i class="fab fa-github"></i>
                                </a>
                                <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full bg-void border border-border hover:bg-pink-600 hover:text-white transition-all" style="border-radius: 50% !important;">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            @else
                                <!-- UI/UX: Email -->
                                <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full bg-void border border-border hover:bg-pink-900 hover:text-white transition-all" style="border-radius: 50% !important;">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
