@extends('layouts.app')

@section('title', config('app.name', __('app.name')))

@push('styles')
<style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(120deg, #f6f7ff 0%, #f3f7ff 40%, #f8fbff 100%);
        }

        .hero-section {
            position: relative;
            background: radial-gradient(1200px 600px at -10% -10%, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 60%),
                        linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #fff;
            display: flex;
            align-items: center;
            z-index: 1;
            margin-top: 0;
            padding-top: 90px;
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

        .orb.orb-1 { background: #4f46e5; top: 8%; left: -60px; }
        .orb.orb-2 { background: #f59e0b; bottom: 10%; right: 10%; width: 200px; height: 200px; }
        .orb.orb-3 { background: #22c55e; top: 30%; right: -60px; width: 220px; height: 220px; }

        .feature-card {
            border: none;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(102, 126, 234, 0.12);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 22px 50px rgba(0, 0, 0, 0.15);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
        }

        .btn-custom {
            border-radius: 25px;
            padding: 12px 32px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(0,0,0,0.12);
            position: relative;
            z-index: 10;
            pointer-events: auto;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(0,0,0,0.18);
        }
        
        /* Ensure buttons are clickable on mobile */
        @media (max-width: 768px) {
            .hero-section .btn-custom {
                z-index: 100;
                pointer-events: auto;
                position: relative;
            }
            
            .hero-section .d-flex.gap-3 {
                position: relative;
                z-index: 10;
            }
        }

        /* remove local footer overrides to match global footer */
    </style>
@endpush

@section('content')

    <section class="hero-section">
        <span class="orb orb-1"></span>
        <span class="orb orb-2"></span>
        <span class="orb orb-3"></span>
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6 text-white">
                    <h1 class="display-4 fw-bold mb-4">
                        {{ __('app.home.welcome_to') }}<br>
                        <span class="text-warning">{{ __('app.name') }}</span>
                    </h1>
                    <p class="lead mb-4">
                        {{ __('app.home.professional_esports_platform') }}<br>
                        {{ __('app.home.join_now_experience_professional_esports') }}
                    </p>
                    <div class="d-flex gap-3">
                        @guest
                        <a href="{{ route('auth.register') }}" class="btn btn-warning btn-lg btn-custom"><i class="fas fa-rocket me-2"></i>{{ __('app.home.get_started') }}</a>
                        <a href="{{ route('auth.login') }}" class="btn btn-outline-light btn-lg btn-custom"><i class="fas fa-sign-in-alt me-2"></i>{{ __('app.auth.login') }}</a>
                        @else
                        <a href="{{ route('dashboard') }}" class="btn btn-warning btn-lg btn-custom"><i class="fas fa-tachometer-alt me-2"></i>{{ __('app.home.go_to_dashboard') }}</a>
                        @endguest
                    </div>
                    <div class="mt-4 d-flex flex-wrap align-items-center gap-3 opacity-75">
                        <span class="badge bg-light text-dark px-3 py-2"><i class="fas fa-shield-alt me-2 text-success"></i>{{ __('app.home.secure_fast') }}</span>
                        <span class="badge bg-light text-dark px-3 py-2"><i class="fas fa-bolt me-2 text-warning"></i>{{ __('app.home.realtime') }}</span>
                        <span class="badge bg-light text-dark px-3 py-2"><i class="fas fa-cloud me-2 text-primary"></i>{{ __('app.home.safe_storage') }}</span>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="position-relative">
                        <i class="fas fa-trophy text-warning" style="font-size: 15rem; opacity: 0.12;"></i>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="fas fa-gamepad text-white" style="font-size: 8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Wave divider -->
    <div style="line-height:0"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" preserveAspectRatio="none" style="display:block"><path fill="#ffffff" d="M0,96L60,106.7C120,117,240,139,360,144C480,149,600,139,720,117.3C840,96,960,64,1080,64C1200,64,1320,96,1380,112L1440,128L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z"></path></svg></div>

    <section class="py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <div class="stat-number">{{ number_format($stats['total_users'] ?? 0) }}</div>
                    <p class="text-muted fs-5 mb-0">{{ __('app.home.registered_gamers') }}</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-number">{{ number_format($stats['total_teams'] ?? 0) }}</div>
                    <p class="text-muted fs-5 mb-0">{{ __('app.home.active_teams') }}</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-number">{{ number_format($stats['active_tournaments'] ?? 0) }}</div>
                    <p class="text-muted fs-5 mb-0">{{ __('app.home.tournaments') }}</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-number">100%</div>
                    <p class="text-muted fs-5 mb-0">{{ __('app.home.satisfaction') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="fw-bold mb-3">{{ __('app.home.featured_features') }}</h2>
                    <p class="text-muted mb-0">{{ __('app.home.discover_powerful_features') }}</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-users text-primary mb-3" style="font-size: 3rem;"></i>
                            <h5 class="card-title">{{ __('app.home.team_management') }}</h5>
                            <p class="card-text text-muted">{{ __('app.home.create_manage_teams') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-trophy text-warning mb-3" style="font-size: 3rem;"></i>
                            <h5 class="card-title">{{ __('app.home.tournament_organization') }}</h5>
                            <p class="card-text text-muted">{{ __('app.home.organize_tournaments') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-chart-line text-success mb-3" style="font-size: 3rem;"></i>
                            <h5 class="card-title">{{ __('app.home.detailed_statistics') }}</h5>
                            <p class="card-text text-muted">{{ __('app.home.track_performance_rankings') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Tournaments Section -->
    @if($stats['featured_tournaments']->count() > 0)
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold">{{ __('app.home.featured_tournaments') }}</h2>
                <p class="lead text-muted">{{ __('app.home.ongoing_upcoming_tournaments') }}</p>
            </div>
            <div class="row">
                @foreach($stats['featured_tournaments'] as $tournament)
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    @if($tournament->logo)
                                    <img src="{{ Storage::url($tournament->logo) }}" alt="{{ $tournament->name }}"
                                        class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                    <div class="bg-primary rounded d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px;">
                                        <i class="fas fa-trophy text-white"></i>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $tournament->name }}</h6>
                                    <small class="text-muted">{{ $tournament->game->name ?? 'Unknown Game' }}</small>
                                </div>
                            </div>
                            <p class="text-muted small">{{ Str::limit($tournament->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-{{ $tournament->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($tournament->status) }}
                                </span>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($tournament->start_date)->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('tournaments.index') }}" class="btn btn-primary btn-lg btn-custom">
                    <i class="fas fa-trophy me-2"></i>{{ __('app.home.view_all_tournaments') }}
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Top Teams Section -->
    @if($stats['top_teams']->count() > 0)
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold">{{ __('app.home.top_teams') }}</h2>
                <p class="lead text-muted">{{ __('app.home.outstanding_popular_teams') }}</p>
            </div>
            <div class="row">
                @foreach($stats['top_teams'] as $team)
                <div class="col-md-3 mb-4">
                    <div class="card feature-card h-100 text-center">
                        <div class="card-body">
                            <div class="mb-3">
                                @if($team->logo)
                                <img src="{{ Storage::url($team->logo) }}" alt="{{ $team->team_name }}"
                                    class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                <div class="bg-gradient-primary rounded-circle mx-auto d-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px;">
                                    <i class="fas fa-users text-white fa-lg"></i>
                                </div>
                                @endif
                            </div>
                            <h6 class="fw-bold mb-2">{{ $team->name }}</h6>
                            <p class="text-muted small mb-2">{{ Str::limit($team->description, 60) }}</p>
                            <div class="d-flex justify-content-center align-items-center">
                                <span class="badge bg-info">
                                    <i class="fas fa-users me-1"></i>
                                    {{ $team->active_members_count }} thành viên
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('players.index') }}" class="btn btn-success btn-lg btn-custom">
                    <i class="fas fa-users me-2"></i>{{ __('app.home.explore_gamers') }}
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- CTA with wave top -->
    <div style="line-height:0"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" preserveAspectRatio="none" style="display:block"><path fill="#0d6efd" d="M0,64L60,85.3C120,107,240,149,360,149.3C480,149,600,107,720,96C840,85,960,107,1080,117.3C1200,128,1320,128,1380,128L1440,128L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z"></path></svg></div>
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-4">{{ __('app.home.ready_enter_esports_world') }}</h2>
            <p class="lead mb-4">{{ __('app.home.join_professional_gaming_community') }}</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                @guest
                <a href="{{ route('auth.register') }}" class="btn btn-warning btn-lg btn-custom"><i class="fas fa-rocket me-2"></i>{{ __('app.home.register_free') }}</a>
                <a href="{{ route('auth.login') }}" class="btn btn-outline-light btn-lg btn-custom"><i class="fas fa-sign-in-alt me-2"></i>{{ __('app.auth.login') }}</a>
                @else
                <a href="{{ route('dashboard') }}" class="btn btn-warning btn-lg btn-custom"><i class="fas fa-tachometer-alt me-2"></i>{{ __('app.home.start_now') }}</a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Contributed By Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h3 class="fw-bold mb-4">
                        <i class="fas fa-heart text-danger me-2"></i>
                        {{ __('app.home.contributed_by', ['default' => 'Đóng góp bởi']) }}
                    </h3>
                    <div class="row g-4 justify-content-center">
                        @php
                            $contributors = [
                                [
                                    'name' => 'Alexander Phan',
                                    'description' => __('app.home.developer_description', ['default' => 'Phát triển và duy trì hệ thống']),
                                    'image' => 'AlexanderPhan.jpg',
                                    'icon' => 'fa-user-tie',
                                    'gradient' => 'bg-gradient-primary',
                                ],
                                [
                                    'name' => 'Đỗ Thế An',
                                    'description' => __('app.home.community_description', ['default' => 'Đóng góp từ cộng đồng người dùng']),
                                    'image' => 'DoTheAn.jpg',
                                    'icon' => 'fa-users',
                                    'gradient' => 'bg-gradient-success',
                                ],
                                [
                                    'name' => 'Lê Khánh An',
                                    'description' => __('app.home.contributors_description', ['default' => 'Các nhà phát triển và tester']),
                                    'image' => 'LeKhanhAn.jpg',
                                    'icon' => 'fa-code',
                                    'gradient' => 'bg-gradient-info',
                                ],
                            ];
                        @endphp
                        @foreach($contributors as $contributor)
                        <div class="col-md-6 col-lg-4">
                            <div class="card feature-card h-100 text-center p-4">
                                <div class="card-body">
                                    <div class="mb-3">
                                        @php
                                            $imagePath = asset('contributors/' . $contributor['image']);
                                            $imageExists = file_exists(public_path('contributors/' . $contributor['image']));
                                        @endphp
                                        @if($imageExists)
                                            <img src="{{ $imagePath }}" alt="{{ $contributor['name'] }}" 
                                                class="rounded-circle mx-auto d-block" 
                                                style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #667eea;">
                                        @else
                                            <div class="{{ $contributor['gradient'] }} rounded-circle mx-auto d-flex align-items-center justify-content-center"
                                                style="width: 80px; height: 80px;">
                                                <i class="fas {{ $contributor['icon'] }} text-white fa-2x"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <h6 class="fw-bold mb-2">{{ $contributor['name'] }}</h6>
                                    <p class="text-muted small mb-0">{{ $contributor['description'] }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <p class="text-muted mb-0">
                            <i class="fas fa-code-branch me-2"></i>
                            {{ __('app.home.made_with_love', ['default' => 'Được tạo với ❤️ bởi đội ngũ phát triển']) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection