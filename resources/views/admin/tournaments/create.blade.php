@extends('layouts.app')

@section('title', __('app.tournaments.create_new_tournament'))

@push('styles')
<style>
    .create-container {
        background: #000814;
        min-height: 100vh;
    }
    
    @media (max-width: 991.98px) {
        .create-container {
            margin-left: 0 !important;
            width: 100% !important;
        }
    }
    
    /* Hero Section */
    .create-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .create-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #22c55e, transparent);
    }
    
    .hero-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .hero-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .hero-icon {
        width: 60px;
        height: 60px;
        min-width: 60px;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 25px rgba(34, 197, 94, 0.3);
    }

    .hero-icon i {
        font-size: 1.5rem;
        color: white;
    }
    
    .hero-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #22c55e;
        margin: 0;
        text-shadow: 0 0 20px rgba(34, 197, 94, 0.5);
    }
    
    .hero-subtitle {
        color: #94a3b8;
        font-size: 0.9rem;
        margin: 0.25rem 0 0 0;
    }
    
    .btn-back {
        background: rgba(100, 116, 139, 0.2);
        color: #94a3b8;
        border: 1px solid rgba(100, 116, 139, 0.3);
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-back:hover {
        background: rgba(0, 229, 255, 0.1);
        color: #00E5FF;
        border-color: rgba(0, 229, 255, 0.3);
    }
    
    /* Error Alert */
    .error-alert {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }
    
    .error-alert h4 {
        color: #ef4444;
        font-size: 0.95rem;
        font-weight: 600;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .error-alert ul {
        margin: 0;
        padding-left: 1.25rem;
        color: #fca5a5;
        font-size: 0.85rem;
    }
    
    .error-alert li {
        margin-bottom: 0.25rem;
    }

    /* Tabs */
    .tabs-container {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        background: rgba(0, 229, 255, 0.03);
        padding: 0.5rem;
        border-radius: 12px;
        border: 1px solid rgba(0, 229, 255, 0.1);
    }
    
    .tab-btn {
        background: transparent;
        color: #94a3b8;
        border: none;
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }
    
    .tab-btn:hover {
        background: rgba(0, 229, 255, 0.1);
        color: #00E5FF;
    }
    
    .tab-btn.active {
        background: linear-gradient(135deg, #00E5FF, #0099cc);
        color: #000;
    }
    
    .tab-btn i {
        font-size: 0.9rem;
    }
    
    /* Form Card */
    .form-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 20px;
        padding: 2rem;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
    
    /* Form Elements */
    .form-group {
        margin-bottom: 1.25rem;
    }
    
    .form-label {
        display: block;
        color: #e2e8f0;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .form-label .required {
        color: #ef4444;
        margin-left: 2px;
    }
    
    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        background: rgba(0, 229, 255, 0.05);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        color: #FFFFFF;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }
    
    .form-input,
    .form-select {
        height: 48px;
    }
    
    .form-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2300E5FF' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
    }
    
    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #00E5FF;
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.2);
    }
    
    .form-select option {
        background: #0d1b2a;
        color: #FFFFFF;
    }
    
    .form-hint {
        color: #64748b;
        font-size: 0.8rem;
        margin-top: 0.35rem;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Radio Group */
    .radio-group {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .radio-item {
        flex: 1;
        min-width: 140px;
    }
    
    .radio-item input {
        display: none;
    }
    
    .radio-item label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0.75rem 1rem;
        background: rgba(0, 229, 255, 0.05);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        color: #94a3b8;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    
    .radio-item input:checked + label {
        background: rgba(0, 229, 255, 0.15);
        border-color: #00E5FF;
        color: #00E5FF;
    }
    
    .radio-item label:hover {
        background: rgba(0, 229, 255, 0.1);
        border-color: rgba(0, 229, 255, 0.4);
    }
    
    /* Dynamic List */
    .dynamic-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .dynamic-item {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    
    .dynamic-item input {
        flex: 1;
    }
    
    .btn-add,
    .btn-remove {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        flex-shrink: 0;
    }
    
    .btn-add {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    
    .btn-add:hover {
        background: rgba(34, 197, 94, 0.3);
    }
    
    .btn-remove {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .btn-remove:hover {
        background: rgba(239, 68, 68, 0.3);
    }
    
    /* Prize Row */
    .prize-row {
        display: grid;
        grid-template-columns: 1fr 2fr auto;
        gap: 0.5rem;
        align-items: center;
    }
    
    .prize-row input {
        height: 48px;
    }
    
    @media (max-width: 640px) {
        .prize-row {
            grid-template-columns: 1fr;
        }
    }
    
    /* Section Title */
    .section-title {
        color: #00E5FF;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.2);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Submit Buttons */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
    }
    
    .btn-cancel {
        background: rgba(100, 116, 139, 0.2);
        color: #94a3b8;
        border: 1px solid rgba(100, 116, 139, 0.3);
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-cancel:hover {
        background: rgba(100, 116, 139, 0.3);
        color: #FFFFFF;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #FFFFFF;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-submit:hover {
        box-shadow: 0 0 25px rgba(34, 197, 94, 0.4);
        transform: translateY(-2px);
    }
    
    /* File Input */
    .file-input-wrapper {
        position: relative;
    }
    
    .file-input {
        width: 100%;
        background: rgba(0, 229, 255, 0.05);
        border: 1px dashed rgba(0, 229, 255, 0.3);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        color: #94a3b8;
        cursor: pointer;
        height: 48px;
        box-sizing: border-box;
    }
    
    .file-input::file-selector-button {
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.3);
        border-radius: 8px;
        color: #00E5FF;
        padding: 0.35rem 0.75rem;
        margin-right: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }
    
    .file-input::file-selector-button:hover {
        background: rgba(0, 229, 255, 0.2);
    }
    
    /* Radio item height */
    .radio-item label {
        height: 48px;
        box-sizing: border-box;
    }
</style>
@endpush

@section('content')
<div class="create-container">
    <div class="max-w-5xl mx-auto px-4 py-6">
        
        <!-- Hero Section -->
        <div class="create-hero">
            <div class="hero-content">
                <div class="hero-left">
                    <div class="hero-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div>
                        <h1 class="hero-title">{{ __('app.tournaments.create_new_tournament') }}</h1>
                        <p class="hero-subtitle">Điền thông tin để tạo giải đấu mới</p>
                    </div>
                </div>
                <a href="{{ route('admin.tournaments.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    <span>Quay lại</span>
                </a>
            </div>
        </div>
        
        <!-- Error Alert -->
        @if ($errors->any())
        <div class="error-alert">
            <h4><i class="fas fa-exclamation-triangle"></i> Vui lòng kiểm tra lại:</h4>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Tabs Navigation -->
        <div class="tabs-container">
            <button type="button" class="tab-btn active" data-tab="basic">
                <i class="fas fa-info-circle"></i>
                <span>Thông tin cơ bản</span>
            </button>
            <button type="button" class="tab-btn" data-tab="datetime">
                <i class="fas fa-calendar"></i>
                <span>Thời gian & Địa điểm</span>
            </button>
            <button type="button" class="tab-btn" data-tab="structure">
                <i class="fas fa-sitemap"></i>
                <span>Cấu trúc & Luật</span>
            </button>
            <button type="button" class="tab-btn" data-tab="management">
                <i class="fas fa-users"></i>
                <span>Quản lý & Giải thưởng</span>
            </button>
            <button type="button" class="tab-btn" data-tab="display">
                <i class="fas fa-eye"></i>
                <span>Hiển thị</span>
            </button>
        </div>
        
        <!-- Form -->
        <form action="{{ route('admin.tournaments.store') }}" method="POST" enctype="multipart/form-data" id="tournamentForm" class="form-card">
            @csrf
            
            <!-- Tab 1: Thông tin cơ bản -->
            <div class="tab-content active" id="tab-basic">
                <div class="form-grid">
                    <div>
                        <div class="form-group">
                            <label class="form-label">Tên Tournament <span class="required">*</span></label>
                            <input type="text" class="form-input" name="name" value="{{ old('name') }}" required placeholder="VD: VCT Masters Shanghai 2024">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Game <span class="required">*</span></label>
                            <select class="form-select" name="game_id" required>
                                <option value="">-- Chọn Game --</option>
                                @foreach($games as $game)
                                <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>{{ $game->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Thể loại thi đấu <span class="required">*</span></label>
                            <select class="form-select" name="competition_type" required>
                                <option value="team" {{ old('competition_type', 'team') === 'team' ? 'selected' : '' }}>Đội tuyển</option>
                                <option value="individual" {{ old('competition_type') === 'individual' ? 'selected' : '' }}>Cá nhân</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Format đội hình</label>
                            <input type="text" class="form-input" name="format" value="{{ old('format') }}" placeholder="VD: 5v5, 1v1, 3v3">
                            <p class="form-hint">Quy mô đội thi đấu (tùy chọn)</p>
                        </div>
                    </div>
                    
                    <div>
                        <div class="form-group">
                            <label class="form-label">Logo Tournament</label>
                            <input type="file" class="file-input" name="logo" accept="image/*">
                            <p class="form-hint">Khuyến nghị: 256x256px, PNG/JPG, dưới 2MB</p>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Banner Tournament</label>
                            <input type="file" class="file-input" name="banner" accept="image/*">
                            <p class="form-hint">Khuyến nghị: 1920x400px, PNG/JPG, dưới 5MB</p>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Mô tả Tournament</label>
                    <textarea class="form-textarea" name="description" rows="4" placeholder="Giới thiệu về tournament, thể thức thi đấu, giải thưởng...">{{ old('description') }}</textarea>
                    <p class="form-hint">Tối đa 2000 ký tự</p>
                </div>
            </div>

            <!-- Tab 2: Thời gian & Địa điểm -->
            <div class="tab-content" id="tab-datetime">
                <div class="form-grid" style="grid-template-columns: repeat(3, 1fr);">
                    <div class="form-group">
                        <label class="form-label">Ngày bắt đầu <span class="required">*</span></label>
                        <input type="date" class="form-input" id="start_date" name="start_date" value="{{ old('start_date') }}" required min="{{ date('Y-m-d') }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Ngày kết thúc <span class="required">*</span></label>
                        <input type="date" class="form-input" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Giờ thi đấu dự kiến</label>
                        <input type="time" class="form-input" name="scheduled_time" value="{{ old('scheduled_time') }}">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Loại địa điểm <span class="required">*</span></label>
                    <div class="radio-group">
                        <div class="radio-item">
                            <input type="radio" name="location_type" id="loc_online" value="online" {{ old('location_type', 'online') === 'online' ? 'checked' : '' }}>
                            <label for="loc_online"><i class="fas fa-wifi"></i> Online</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" name="location_type" id="loc_lan" value="lan" {{ old('location_type') === 'lan' ? 'checked' : '' }}>
                            <label for="loc_lan"><i class="fas fa-network-wired"></i> LAN</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" name="location_type" id="loc_physical" value="physical" {{ old('location_type') === 'physical' ? 'checked' : '' }}>
                            <label for="loc_physical"><i class="fas fa-map-marker-alt"></i> Địa điểm cụ thể</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group" id="location_address_group" style="display: none;">
                    <label class="form-label">Địa chỉ cụ thể</label>
                    <textarea class="form-textarea" name="location_address" rows="2" placeholder="Nhập địa chỉ venue, tòa nhà, thành phố...">{{ old('location_address') }}</textarea>
                </div>
            </div>
            
            <!-- Tab 3: Cấu trúc & Luật -->
            <div class="tab-content" id="tab-structure">
                <div class="form-grid">
                    <div>
                        <div class="form-group">
                            <label class="form-label">Hình thức thi đấu <span class="required">*</span></label>
                            <select class="form-select" name="tournament_format" required>
                                <option value="single_elimination" {{ old('tournament_format', 'single_elimination') === 'single_elimination' ? 'selected' : '' }}>Single Elimination</option>
                                <option value="double_elimination" {{ old('tournament_format') === 'double_elimination' ? 'selected' : '' }}>Double Elimination</option>
                                <option value="round_robin" {{ old('tournament_format') === 'round_robin' ? 'selected' : '' }}>Round Robin</option>
                                <option value="swiss_system" {{ old('tournament_format') === 'swiss_system' ? 'selected' : '' }}>Swiss System</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Số lượng tham gia tối đa <span class="required">*</span></label>
                            <input type="number" class="form-input" name="max_participants" value="{{ old('max_participants', 16) }}" required min="2" max="1024">
                            <p class="form-hint">Số đội hoặc người chơi tối đa</p>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Số người dự bị mỗi đội</label>
                            <input type="number" class="form-input" name="substitute_players" value="{{ old('substitute_players', 0) }}" min="0" max="10">
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="section-title"><i class="fas fa-gavel"></i> Luật thi đấu chi tiết</h4>
                        
                        <div class="form-group">
                            <label class="form-label">Map Pool</label>
                            <input type="text" class="form-input" id="rules_map_pool" placeholder="VD: Dust2, Mirage, Inferno">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Best of Format</label>
                            <select class="form-select" id="rules_bo_format">
                                <option value="">-- Chọn --</option>
                                <option value="BO1">Best of 1 (BO1)</option>
                                <option value="BO3">Best of 3 (BO3)</option>
                                <option value="BO5">Best of 5 (BO5)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Luật khác</label>
                            <textarea class="form-textarea" id="rules_other" rows="2" placeholder="Các quy định khác..."></textarea>
                        </div>
                        <input type="hidden" id="rules_details" name="rules_details">
                    </div>
                </div>
            </div>

            <!-- Tab 4: Quản lý & Giải thưởng -->
            <div class="tab-content" id="tab-management">
                <div class="form-grid">
                    <div>
                        <div class="form-group">
                            <label class="form-label">Tên ban tổ chức</label>
                            <input type="text" class="form-input" name="organizer_name" value="{{ old('organizer_name') }}" placeholder="VD: Riot Games Vietnam">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Thông tin liên hệ</label>
                            <input type="text" class="form-input" name="organizer_contact" value="{{ old('organizer_contact') }}" placeholder="Email, phone, discord...">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Trọng tài / Admin</label>
                            <div class="dynamic-list" id="referees-container">
                                <div class="dynamic-item">
                                    <input type="text" class="form-input referees-input" placeholder="Tên trọng tài">
                                    <button type="button" class="btn-add" onclick="addReferee()"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            <input type="hidden" id="referees" name="referees">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Nhà tài trợ</label>
                            <div class="dynamic-list" id="sponsors-container">
                                <div class="dynamic-item">
                                    <input type="text" class="form-input sponsors-input" placeholder="Tên nhà tài trợ">
                                    <button type="button" class="btn-add" onclick="addSponsor()"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            <input type="hidden" id="sponsors" name="sponsors">
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="section-title"><i class="fas fa-trophy"></i> Cơ cấu giải thưởng</h4>
                        <div class="dynamic-list" id="prizes-container">
                            <div class="prize-row">
                                <input type="text" class="form-input prize-position" placeholder="Hạng (VD: 1st)">
                                <input type="text" class="form-input prize-reward" placeholder="Giải thưởng">
                                <button type="button" class="btn-add" onclick="addPrize()"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <input type="hidden" id="prize_structure" name="prize_structure">
                    </div>
                </div>
            </div>
            
            <!-- Tab 5: Hiển thị -->
            <div class="tab-content" id="tab-display">
                <div class="form-grid">
                    <div>
                        <div class="form-group">
                            <label class="form-label">Trạng thái <span class="required">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Chưa mở đăng ký</option>
                                <option value="registration_open" {{ old('status') === 'registration_open' ? 'selected' : '' }}>Đang đăng ký</option>
                                <option value="ongoing" {{ old('status') === 'ongoing' ? 'selected' : '' }}>Đang diễn ra</option>
                                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Đã kết thúc</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Hình thức tham gia <span class="required">*</span></label>
                            <select class="form-select" name="participation_type" required>
                                <option value="public" {{ old('participation_type', 'public') === 'public' ? 'selected' : '' }}>Mở công khai</option>
                                <option value="invite_only" {{ old('participation_type') === 'invite_only' ? 'selected' : '' }}>Chỉ mời</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Link phát sóng</label>
                            <input type="url" class="form-input" name="stream_link" value="{{ old('stream_link') }}" placeholder="https://twitch.tv/...">
                        </div>
                    </div>
                    
                    <div>
                        <div class="form-group">
                            <label class="form-label">Hashtags / Keywords</label>
                            <div class="dynamic-list" id="hashtags-container">
                                <div class="dynamic-item">
                                    <input type="text" class="form-input hashtags-input" placeholder="#hashtag hoặc keyword">
                                    <button type="button" class="btn-add" onclick="addHashtag()"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            <input type="hidden" id="hashtags" name="hashtags">
                            <p class="form-hint">Giúp tìm kiếm và marketing tournament</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Submit Buttons -->
            <div class="form-actions">
                <a href="{{ route('admin.tournaments.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i>
                    <span>Hủy</span>
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i>
                    <span>Tạo Tournament</span>
                </button>
            </div>
        </form>
        
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Remove active from all
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Add active to clicked
            this.classList.add('active');
            document.getElementById('tab-' + tabId).classList.add('active');
        });
    });
    
    // Location type change
    const locationRadios = document.querySelectorAll('input[name="location_type"]');
    const locationAddressGroup = document.getElementById('location_address_group');
    
    locationRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            locationAddressGroup.style.display = (this.value === 'physical' || this.value === 'lan') ? 'block' : 'none';
        });
    });
    
    // End date minimum
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
        document.querySelectorAll('#prizes-container .prize-row').forEach(row => {
            const position = row.querySelector('.prize-position')?.value?.trim();
            const reward = row.querySelector('.prize-reward')?.value?.trim();
            if (position && reward) prizes.push({ position, reward });
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

// Dynamic list functions
function addReferee() {
    const container = document.getElementById('referees-container');
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <input type="text" class="form-input referees-input" placeholder="Tên trọng tài">
        <button type="button" class="btn-remove" onclick="this.parentElement.remove()"><i class="fas fa-trash"></i></button>
    `;
    container.appendChild(div);
}

function addPrize() {
    const container = document.getElementById('prizes-container');
    const div = document.createElement('div');
    div.className = 'prize-row';
    div.innerHTML = `
        <input type="text" class="form-input prize-position" placeholder="Hạng (VD: 2nd)">
        <input type="text" class="form-input prize-reward" placeholder="Giải thưởng">
        <button type="button" class="btn-remove" onclick="this.parentElement.remove()"><i class="fas fa-trash"></i></button>
    `;
    container.appendChild(div);
}

function addSponsor() {
    const container = document.getElementById('sponsors-container');
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <input type="text" class="form-input sponsors-input" placeholder="Tên nhà tài trợ">
        <button type="button" class="btn-remove" onclick="this.parentElement.remove()"><i class="fas fa-trash"></i></button>
    `;
    container.appendChild(div);
}

function addHashtag() {
    const container = document.getElementById('hashtags-container');
    const div = document.createElement('div');
    div.className = 'dynamic-item';
    div.innerHTML = `
        <input type="text" class="form-input hashtags-input" placeholder="#hashtag hoặc keyword">
        <button type="button" class="btn-remove" onclick="this.parentElement.remove()"><i class="fas fa-trash"></i></button>
    `;
    container.appendChild(div);
}
</script>
@endpush
