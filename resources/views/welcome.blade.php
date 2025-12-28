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
            <div class="flex flex-wrap justify-center items-stretch gap-6 lg:gap-8">
                <div class="flex-1 min-w-[280px] max-w-[350px]">
                    <div class="feature-card h-full text-center p-8">
                        <i class="fas fa-users text-neon mb-4 text-5xl" style="filter: drop-shadow(0 0 10px rgba(0, 229, 255, 0.5));"></i>
                        <h5 class="text-xl font-semibold mb-3 font-display text-text-main">{{ __('app.home.team_management') }}</h5>
                        <p class="text-text-muted font-body">{{ __('app.home.create_manage_teams') }}</p>
                    </div>
                </div>
                <div class="flex-1 min-w-[280px] max-w-[350px]">
                    <div class="feature-card h-full text-center p-8">
                        <i class="fas fa-trophy text-neon mb-4 text-5xl" style="filter: drop-shadow(0 0 10px rgba(0, 229, 255, 0.5));"></i>
                        <h5 class="text-xl font-semibold mb-3 font-display text-text-main">{{ __('app.home.tournament_organization') }}</h5>
                        <p class="text-text-muted font-body">{{ __('app.home.organize_tournaments') }}</p>
                    </div>
                </div>
                <div class="flex-1 min-w-[280px] max-w-[350px]">
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

    <!-- Contributed By Section -->
    <section class="py-20 bg-surface">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl lg:text-5xl font-bold mb-4 font-display neon-text">
                    <i class="fas fa-heart text-red-500 mr-3" style="filter: drop-shadow(0 0 10px rgba(239, 68, 68, 0.5));"></i>
                    {{ __('app.home.contributed_by', ['default' => 'Đóng góp bởi']) }}
                </h2>
                <p class="text-text-muted text-lg font-body max-w-2xl mx-auto">
                    {{ __('app.home.made_with_love', ['default' => 'Được tạo với ❤️ bởi đội ngũ phát triển']) }}
                </p>
            </div>
            <div class="flex flex-wrap justify-center items-stretch gap-6 lg:gap-8 max-w-7xl mx-auto">
                @php
                    $contributors = [
                        [
                            'name' => 'Alexander Phan',
                            'description' => __('app.home.developer_description', ['default' => 'Phát triển và duy trì hệ thống']),
                            'image' => 'AlexanderPhan.jpg',
                            'icon' => 'fa-user-tie',
                            'gradient' => 'from-deep-navy to-neon',
                        ],
                        [
                            'name' => 'Đỗ Thế An',
                            'description' => __('app.home.community_description', ['default' => 'Đóng góp từ cộng đồng người dùng']),
                            'image' => 'DoTheAn.jpg',
                            'icon' => 'fa-users',
                            'gradient' => 'from-green-500 to-neon',
                        ],
                        [
                            'name' => 'Lê Khánh An',
                            'description' => __('app.home.contributors_description', ['default' => 'Các nhà phát triển và tester']),
                            'image' => 'LeKhanhAn.jpg',
                            'icon' => 'fa-code',
                            'gradient' => 'from-neon to-blue-400',
                        ],
                        [
                            'name' => 'Nguyễn Phương Anh',
                            'description' => __('app.home.designer_description', ['default' => 'Thiết kế giao diện và trải nghiệm người dùng']),
                            'image' => 'NguyenPhuongAnh.png',
                            'icon' => 'fa-palette',
                            'gradient' => 'from-purple-500 to-neon',
                        ],
                    ];
                @endphp
                @foreach($contributors as $contributor)
                <div class="group flex-1 min-w-[200px] max-w-[250px]">
                    <div class="feature-card h-full text-center p-6 relative overflow-hidden">
                        <!-- Background glow effect -->
                        <div class="absolute inset-0 bg-gradient-to-br from-neon/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <div class="relative z-10">
                            <div class="mb-6 flex justify-center">
                                @php
                                    $imagePath = asset('contributors/' . $contributor['image']);
                                    $imageExists = file_exists(public_path('contributors/' . $contributor['image']));
                                @endphp
                                @if($imageExists)
                                    <div class="relative">
                                        <img src="{{ $imagePath }}" alt="{{ $contributor['name'] }}" 
                                            class="rounded-full mx-auto block w-24 h-24 object-cover border-4 border-neon/50 group-hover:border-neon transition-all duration-300"
                                            style="box-shadow: 0 0 20px rgba(0, 229, 255, 0.3);">
                                        <div class="absolute inset-0 rounded-full bg-neon/0 group-hover:bg-neon/10 transition-all duration-300"></div>
                                    </div>
                                @else
                                    <div class="bg-gradient-to-r {{ $contributor['gradient'] }} rounded-full mx-auto flex items-center justify-center w-24 h-24 border-4 border-neon/50 group-hover:border-neon transition-all duration-300"
                                         style="box-shadow: 0 0 20px rgba(0, 229, 255, 0.3);">
                                        <i class="fas {{ $contributor['icon'] }} text-white text-4xl"></i>
                                    </div>
                                @endif
                            </div>
                            <h6 class="font-bold mb-3 text-text-main font-display text-xl group-hover:text-neon transition-colors duration-300">
                                {{ $contributor['name'] }}
                            </h6>
                            <p class="text-text-muted text-sm mb-0 font-body leading-relaxed">
                                {{ $contributor['description'] }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA with wave top -->
    <div style="line-height:0"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" preserveAspectRatio="none" style="display:block"><path fill="#000055" d="M0,64L60,85.3C120,107,240,149,360,149.3C480,149,600,107,720,96C840,85,960,107,1080,117.3C1200,128,1320,128,1380,128L1440,128L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z"></path></svg></div>
    <section class="py-16 bg-deep-navy text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl lg:text-5xl font-bold mb-6 font-display neon-text">{{ __('app.home.ready_enter_esports_world') }}</h2>
            <p class="text-xl mb-8 text-text-muted font-body">{{ __('app.home.join_professional_gaming_community') }}</p>
            <div class="flex justify-center gap-4 flex-wrap">
                @guest
                <a href="{{ route('auth.register') }}" class="btn-primary-custom font-semibold py-4 px-8 rounded-full font-display inline-block">
                    <i class="fas fa-rocket mr-2"></i>{{ __('app.home.register_free') }}
                </a>
                <a href="{{ route('auth.login') }}" class="btn-neon font-semibold py-4 px-8 rounded-full font-display inline-block">
                    <i class="fas fa-sign-in-alt mr-2"></i>{{ __('app.auth.login') }}
                </a>
                @else
                <a href="{{ route('dashboard') }}" class="btn-primary-custom font-semibold py-4 px-8 rounded-full font-display inline-block">
                    <i class="fas fa-tachometer-alt mr-2"></i>{{ __('app.home.start_now') }}
                </a>
                @endguest
            </div>
        </div>
    </section>
@endsection
