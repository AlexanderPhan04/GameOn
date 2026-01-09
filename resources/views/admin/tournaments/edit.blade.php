@extends('layouts.app')

@section('title', 'Chỉnh sửa Tournament')

@push('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
.edit-hero{position:relative;border-radius:16px;overflow:hidden;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;margin-bottom:1.25rem}
.edit-hero .hero-inner{position:relative;z-index:2;padding:1.75rem}
.edit-hero .hero-title{font-weight:700;font-size:1.6rem;margin:0;text-shadow:0 2px 4px rgba(0,0,0,.25)}
.edit-hero .hero-actions .btn{backdrop-filter:blur(8px);border-radius:999px}
.edit-hero::after{content:"";position:absolute;inset:0;background:url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 500"><defs><radialGradient id="g" cx="50%" cy="50%"><stop offset="0%" stop-color="rgba(255,255,255,0.2)"/><stop offset="100%" stop-color="rgba(255,255,255,0)"/></radialGradient></defs><circle cx="200" cy="120" r="180" fill="url(%23g)"/><circle cx="800" cy="160" r="160" fill="url(%23g)"/></svg>');opacity:.35}
.card-modern{border:0;border-radius:14px;box-shadow:0 6px 24px rgba(0,0,0,.08)}
.form-control,.form-select{border:2px solid #e9ecef;border-radius:10px;padding:.7rem 1rem}
.form-control:focus,.form-select:focus{border-color:#667eea;box-shadow:0 0 0 .2rem rgba(102,126,234,.2)}
.nav-tabs .nav-link{border:none;border-bottom:3px solid transparent;font-weight:600;color:#6c757d}
.nav-tabs .nav-link.active{color:#4f46e5;border-bottom-color:#4f46e5;background-color:transparent}
.image-preview{display:flex;gap:12px;align-items:center;margin-bottom:.5rem}
.image-preview img{border-radius:12px;box-shadow:0 6px 16px rgba(0,0,0,.15)}
.help-hint{font-size:.85rem;color:#6c757d}
.preview-box{display:flex;align-items:center;justify-content:center;background:#f8f9fa;border-radius:12px;box-shadow:0 6px 16px rgba(0,0,0,.08);color:#c0c4cc}
.preview-box.logo{width:80px;height:80px}
.preview-box.banner{width:240px;height:90px}
.preview-box img{width:100%;height:100%;object-fit:cover;border-radius:inherit}
.preview-box.logo i{font-size:26px}
.preview-box.banner i{font-size:28px}
.sticky-actions{position:sticky;bottom:0;z-index:5;background:#fff;padding:12px;border-top:1px solid #eef1f4;display:flex;justify-content:flex-end;gap:.5rem;border-radius:0 0 14px 14px;box-shadow:0 -6px 18px rgba(0,0,0,.05)}
@media (max-width:768px){.edit-hero .hero-title{font-size:1.25rem}}
</style>
@endpush

@php
// Decode JSON fields
$rules_details = is_string($tournament->rules_details) ? json_decode($tournament->rules_details, true) : $tournament->rules_details;
$referees = is_string($tournament->referees) ? json_decode($tournament->referees, true) : $tournament->referees;
$prize_structure = is_string($tournament->prize_structure) ? json_decode($tournament->prize_structure, true) : $tournament->prize_structure;
$sponsors = is_string($tournament->sponsors) ? json_decode($tournament->sponsors, true) : $tournament->sponsors;
$hashtags = is_string($tournament->hashtags) ? json_decode($tournament->hashtags, true) : $tournament->hashtags;
@endphp

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card card-modern border-0">
                <div class="edit-hero" data-aos="fade-up">
                    <div class="hero-inner d-flex align-items-center justify-content-between">
                        <h2 class="hero-title mb-0">
                            <i class="fas fa-edit me-2 text-warning"></i>Chỉnh sửa Tournament: {{ $tournament->name }}
                        </h2>
                        <div class="hero-actions d-flex gap-2">
                            <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-eye me-1"></i> Xem chi tiết
                            </a>
                            <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-exclamation-triangle me-1"></i>Vui lòng kiểm tra lại:</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('admin.tournaments.update', $tournament->id) }}" method="POST" enctype="multipart/form-data" id="tournamentForm">
                        @csrf
                        @method('PUT')

                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs mb-4" id="tournamentTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button">
                                    <i class="fas fa-info-circle me-1"></i>Thông tin cơ bản
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="datetime-tab" data-bs-toggle="tab" data-bs-target="#datetime" type="button">
                                    <i class="fas fa-calendar me-1"></i>Thời gian & Địa điểm
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="structure-tab" data-bs-toggle="tab" data-bs-target="#structure" type="button">
                                    <i class="fas fa-sitemap me-1"></i>Cấu trúc & Luật
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="management-tab" data-bs-toggle="tab" data-bs-target="#management" type="button">
                                    <i class="fas fa-users me-1"></i>Quản lý & Phần thưởng
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="display-tab" data-bs-toggle="tab" data-bs-target="#display" type="button">
                                    <i class="fas fa-eye me-1"></i>Hệ thống & Hiển thị
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="tournamentTabsContent">

                            <!-- Tab 1: Thông tin cơ bản -->
                            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Tên Tournament -->
                                        <div class="mb-3">
                                            <label for="name" class="form-label">
                                                Tên Tournament <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name', $tournament->name) }}" required>
                                        </div>

                                        <!-- Game -->
                                        <div class="mb-3">
                                            <label for="game_id" class="form-label">
                                                Game <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="game_id" name="game_id" required>
                                                <option value="">-- Chọn Game --</option>
                                                @foreach($games as $game)
                                                <option value="{{ $game->id }}" {{ old('game_id', $tournament->game_id) == $game->id ? 'selected' : '' }}>
                                                    {{ $game->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Thể loại -->
                                        <div class="mb-3">
                                            <label for="competition_type" class="form-label">
                                                Thể loại thi đấu <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="competition_type" name="competition_type" required>
                                                <option value="team" {{ old('competition_type', $tournament->competition_type) === 'team' ? 'selected' : '' }}>Đội tuyển</option>
                                                <option value="individual" {{ old('competition_type', $tournament->competition_type) === 'individual' ? 'selected' : '' }}>Cá nhân</option>
                                            </select>
                                        </div>

                                        <!-- Format -->
                                        <div class="mb-3">
                                            <label for="format" class="form-label">Format đội hình</label>
                                            <input type="text" class="form-control" id="format" name="format"
                                                value="{{ old('format', $tournament->format) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Logo hiện tại và upload mới -->
                                        <div class="mb-3">
                                            <label for="logo" class="form-label">Logo Tournament</label>
                                            <div class="image-preview">
                                                <div id="logoPreview" class="preview-box logo">
                                                    @if($tournament->logo_url)
                                                        <img src="{{ $tournament->logo_url }}" alt="logo">
                                                    @else
                                                        <i class="fas fa-shield-alt"></i>
                                                    @endif
                                                </div>
                                            </div>
                                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                            <div class="help-hint">Khuyến nghị: 256x256px, dưới 2MB</div>
                                        </div>

                                        <!-- Banner hiện tại và upload mới -->
                                        <div class="mb-3">
                                            <label for="banner" class="form-label">Banner Tournament</label>
                                            <div class="image-preview">
                                                <div id="bannerPreview" class="preview-box banner">
                                                    @if($tournament->banner_url)
                                                        <img src="{{ $tournament->banner_url }}" alt="banner">
                                                    @else
                                                        <i class="fas fa-image"></i>
                                                    @endif
                                                </div>
                                            </div>
                                            <input type="file" class="form-control" id="banner" name="banner" accept="image/*">
                                            <div class="help-hint">Khuyến nghị: 1920x400px, dưới 5MB</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mô tả -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả Tournament</label>
                                    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $tournament->description) }}</textarea>
                                </div>
                            </div>

                            <!-- Tab 2: Thời gian & Địa điểm -->
                            <div class="tab-pane fade" id="datetime" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="start_date" class="form-label">
                                                Ngày bắt đầu <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" class="form-control" id="start_date" name="start_date"
                                                value="{{ old('start_date', $tournament->start_date ? $tournament->start_date->format('Y-m-d') : '') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="end_date" class="form-label">
                                                Ngày kết thúc <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" class="form-control" id="end_date" name="end_date"
                                                value="{{ old('end_date', $tournament->end_date ? $tournament->end_date->format('Y-m-d') : '') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="scheduled_time" class="form-label">Giờ thi đấu dự kiến</label>
                                            <input type="time" class="form-control" id="scheduled_time" name="scheduled_time"
                                                value="{{ old('scheduled_time', $tournament->scheduled_time ? $tournament->scheduled_time->format('H:i') : '') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Loại địa điểm <span class="text-danger">*</span>
                                    </label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="location_type"
                                                    id="location_online" value="online" {{ old('location_type', $tournament->location_type) === 'online' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="location_online">
                                                    <i class="fas fa-wifi me-1"></i>Online
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="location_type"
                                                    id="location_lan" value="lan" {{ old('location_type', $tournament->location_type) === 'lan' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="location_lan">
                                                    <i class="fas fa-network-wired me-1"></i>LAN
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="location_type"
                                                    id="location_physical" value="physical" {{ old('location_type', $tournament->location_type) === 'physical' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="location_physical">
                                                    <i class="fas fa-map-marker-alt me-1"></i>Địa điểm cụ thể
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @php
                                $showAddress = in_array(old('location_type', $tournament->location_type), ['physical', 'lan']);
                                @endphp
                                <div class="mb-3 {{ $showAddress ? '' : 'd-none' }}" id="location_address_group">
                                    <label for="location_address" class="form-label">Địa chỉ cụ thể</label>
                                    <textarea class="form-control" id="location_address" name="location_address" rows="2">{{ old('location_address', $tournament->location_address) }}</textarea>
                                </div>
                            </div>

                            <!-- Tab 3: Cấu trúc & Luật (tương tự create nhưng với dữ liệu có sẵn) -->
                            <div class="tab-pane fade" id="structure" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tournament_format" class="form-label">
                                                Hình thức thi đấu <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="tournament_format" name="tournament_format" required>
                                                <option value="single_elimination" {{ old('tournament_format', $tournament->tournament_format) === 'single_elimination' ? 'selected' : '' }}>Single Elimination</option>
                                                <option value="double_elimination" {{ old('tournament_format', $tournament->tournament_format) === 'double_elimination' ? 'selected' : '' }}>Double Elimination</option>
                                                <option value="round_robin" {{ old('tournament_format', $tournament->tournament_format) === 'round_robin' ? 'selected' : '' }}>Round Robin</option>
                                                <option value="swiss_system" {{ old('tournament_format', $tournament->tournament_format) === 'swiss_system' ? 'selected' : '' }}>Swiss System</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="max_participants" class="form-label">
                                                Số lượng tham gia tối đa <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" class="form-control" id="max_participants" name="max_participants"
                                                value="{{ old('max_participants', $tournament->max_participants) }}" required min="2" max="1024">
                                        </div>

                                        <div class="mb-3">
                                            <label for="substitute_players" class="form-label">Số người dự bị mỗi đội</label>
                                            <input type="number" class="form-control" id="substitute_players" name="substitute_players"
                                                value="{{ old('substitute_players', $tournament->substitute_players) }}" min="0" max="10">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Luật thi đấu chi tiết</label>
                                            <div class="mb-2">
                                                <label for="rules_map_pool" class="form-label small">Map Pool</label>
                                                <input type="text" class="form-control form-control-sm" id="rules_map_pool"
                                                    value="{{ $rules_details['map_pool'] ?? '' }}" name="rules_details[map_pool]">
                                            </div>
                                            <div class="mb-2">
                                                <label for="rules_bo_format" class="form-label small">Best of Format</label>
                                                <select class="form-select form-select-sm" id="rules_bo_format" name="rules_details[bo_format]">
                                                    <option value="">-- Chọn --</option>
                                                    <option value="BO1" {{ ($rules_details['bo_format'] ?? '') === 'BO1' ? 'selected' : '' }}>Best of 1 (BO1)</option>
                                                    <option value="BO3" {{ ($rules_details['bo_format'] ?? '') === 'BO3' ? 'selected' : '' }}>Best of 3 (BO3)</option>
                                                    <option value="BO5" {{ ($rules_details['bo_format'] ?? '') === 'BO5' ? 'selected' : '' }}>Best of 5 (BO5)</option>
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <label for="rules_other" class="form-label small">Luật khác</label>
                                                <textarea class="form-control form-control-sm" id="rules_other" name="rules_details[other]" rows="2">{{ $rules_details['other'] ?? '' }}</textarea>
                                            </div>
                                            <input type="hidden" id="rules_details" name="rules_details">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 4 & 5 tương tự create nhưng pre-populate data -->
                            <div class="tab-pane fade" id="management" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="organizer_name" class="form-label">Tên ban tổ chức</label>
                                            <input type="text" class="form-control" id="organizer_name" name="organizer_name"
                                                value="{{ old('organizer_name', $tournament->organizer_name) }}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="organizer_contact" class="form-label">Thông tin liên hệ</label>
                                            <input type="text" class="form-control" id="organizer_contact" name="organizer_contact"
                                                value="{{ old('organizer_contact', $tournament->organizer_contact) }}">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Trọng tài / Admin</label>
                                            <div id="referees-container">
                                                @if($referees)
                                                @foreach($referees as $referee)
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control referees-input" value="{{ $referee }}">
                                                    <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                @endforeach
                                                @endif
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control referees-input" placeholder="Tên trọng tài">
                                                    <button type="button" class="btn btn-success" onclick="addReferee()">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="hidden" id="referees" name="referees">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Cơ cấu giải thưởng</label>
                                            <div id="prizes-container">
                                                @if($prize_structure)
                                                @foreach($prize_structure as $prize)
                                                <div class="row mb-2">
                                                    <div class="col-4">
                                                        <input type="text" class="form-control prize-position" value="{{ $prize['position'] }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="text" class="form-control prize-reward" value="{{ $prize['reward'] }}">
                                                    </div>
                                                    <div class="col-2">
                                                        <button type="button" class="btn btn-danger w-100" onclick="this.parentElement.parentElement.remove()">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @endif
                                                <div class="row mb-2">
                                                    <div class="col-4">
                                                        <input type="text" class="form-control prize-position" placeholder="Hạng">
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="text" class="form-control prize-reward" placeholder="Giải thưởng">
                                                    </div>
                                                    <div class="col-2">
                                                        <button type="button" class="btn btn-success w-100" onclick="addPrize()">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" id="prize_structure" name="prize_structure">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Nhà tài trợ</label>
                                            <div id="sponsors-container">
                                                @if($sponsors)
                                                @foreach($sponsors as $sponsor)
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control sponsors-input" value="{{ $sponsor }}">
                                                    <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                @endforeach
                                                @endif
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control sponsors-input" placeholder="Tên nhà tài trợ">
                                                    <button type="button" class="btn btn-success" onclick="addSponsor()">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="hidden" id="sponsors" name="sponsors">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="display" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">
                                                Trạng thái <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="draft" {{ old('status', $tournament->status) === 'draft' ? 'selected' : '' }}>Chưa mở đăng ký</option>
                                                <option value="registration_open" {{ old('status', $tournament->status) === 'registration_open' ? 'selected' : '' }}>Đang đăng ký</option>
                                                <option value="ongoing" {{ old('status', $tournament->status) === 'ongoing' ? 'selected' : '' }}>Đang diễn ra</option>
                                                <option value="completed" {{ old('status', $tournament->status) === 'completed' ? 'selected' : '' }}>Đã kết thúc</option>
                                                <option value="cancelled" {{ old('status', $tournament->status) === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="participation_type" class="form-label">
                                                Hình thức tham gia <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="participation_type" name="participation_type" required>
                                                <option value="public" {{ old('participation_type', $tournament->participation_type) === 'public' ? 'selected' : '' }}>Mở công khai</option>
                                                <option value="invite_only" {{ old('participation_type', $tournament->participation_type) === 'invite_only' ? 'selected' : '' }}>Chỉ mời</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="stream_link" class="form-label">Link phát sóng</label>
                                            <input type="url" class="form-control" id="stream_link" name="stream_link"
                                                value="{{ old('stream_link', $tournament->stream_link) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Hashtags / Keywords</label>
                                            <div id="hashtags-container">
                                                @if($hashtags)
                                                @foreach($hashtags as $hashtag)
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control hashtags-input" value="{{ $hashtag }}">
                                                    <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                @endforeach
                                                @endif
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control hashtags-input" placeholder="#hashtag hoặc keyword">
                                                    <button type="button" class="btn btn-success" onclick="addHashtag()">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="hidden" id="hashtags" name="hashtags">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-1"></i>Cập nhật Tournament
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({ duration: 700, once: true });

        // Live preview for logo & banner
        const logoInput = document.getElementById('logo');
        const logoPreviewBox = document.getElementById('logoPreview');
        const bannerInput = document.getElementById('banner');
        const bannerPreviewBox = document.getElementById('bannerPreview');

        function bindPreview(input, previewBox){
            if(!input || !previewBox) return;
            input.addEventListener('change', function(){
                const file = this.files && this.files[0];
                if(!file) return;
                const reader = new FileReader();
                reader.onload = e => {
                    // Replace icon with image element
                    previewBox.innerHTML = '';
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    previewBox.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
        bindPreview(logoInput, logoPreviewBox);
        bindPreview(bannerInput, bannerPreviewBox);
        // Handle location type change
        const locationRadios = document.querySelectorAll('input[name="location_type"]');
        const locationAddressGroup = document.getElementById('location_address_group');

        locationRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'physical' || this.value === 'lan') {
                    locationAddressGroup.style.display = 'block';
                } else {
                    locationAddressGroup.style.display = 'none';
                }
            });
        });

        // Handle end date minimum
        document.getElementById('start_date').addEventListener('change', function() {
            document.getElementById('end_date').min = this.value;
        });

        // Form submission - collect dynamic arrays
        document.getElementById('tournamentForm').addEventListener('submit', function() {
            // Collect rules details
            const rulesDetails = {
                map_pool: document.getElementById('rules_map_pool').value,
                bo_format: document.getElementById('rules_bo_format').value,
                other: document.getElementById('rules_other').value
            };
            document.getElementById('rules_details').value = JSON.stringify(rulesDetails);

            // Collect referees
            const referees = [];
            document.querySelectorAll('#referees-container .referees-input').forEach(input => {
                if (input.value.trim()) referees.push(input.value.trim());
            });
            document.getElementById('referees').value = JSON.stringify(referees);

            // Collect prizes
            const prizes = [];
            document.querySelectorAll('#prizes-container .row').forEach(row => {
                const position = row.querySelector('.prize-position')?.value?.trim();
                const reward = row.querySelector('.prize-reward')?.value?.trim();
                if (position && reward) {
                    prizes.push({
                        position,
                        reward
                    });
                }
            });
            document.getElementById('prize_structure').value = JSON.stringify(prizes);

            // Collect sponsors
            const sponsors = [];
            document.querySelectorAll('#sponsors-container .sponsors-input').forEach(input => {
                if (input.value.trim()) sponsors.push(input.value.trim());
            });
            document.getElementById('sponsors').value = JSON.stringify(sponsors);

            // Collect hashtags
            const hashtags = [];
            document.querySelectorAll('#hashtags-container .hashtags-input').forEach(input => {
                if (input.value.trim()) hashtags.push(input.value.trim());
            });
            document.getElementById('hashtags').value = JSON.stringify(hashtags);
        });
    });

    // Dynamic list functions (same as create)
    function addReferee() {
        const container = document.getElementById('referees-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
        <input type="text" class="form-control referees-input" placeholder="Tên trọng tài">
        <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">
            <i class="fas fa-trash"></i>
        </button>
    `;
        container.appendChild(div);
    }

    function addPrize() {
        const container = document.getElementById('prizes-container');
        const div = document.createElement('div');
        div.className = 'row mb-2';
        div.innerHTML = `
        <div class="col-4">
            <input type="text" class="form-control prize-position" placeholder="Hạng">
        </div>
        <div class="col-6">
            <input type="text" class="form-control prize-reward" placeholder="Giải thưởng">
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-danger w-100" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
        container.appendChild(div);
    }

    function addSponsor() {
        const container = document.getElementById('sponsors-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
        <input type="text" class="form-control sponsors-input" placeholder="Tên nhà tài trợ">
        <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">
            <i class="fas fa-trash"></i>
        </button>
    `;
        container.appendChild(div);
    }

    function addHashtag() {
        const container = document.getElementById('hashtags-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
        <input type="text" class="form-control hashtags-input" placeholder="#hashtag hoặc keyword">
        <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">
            <i class="fas fa-trash"></i>
        </button>
    `;
        container.appendChild(div);
    }
</script>
@endpush
@endsection