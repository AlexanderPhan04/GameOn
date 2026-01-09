@extends('layouts.app')

@section('title', 'Chỉnh sửa Game')

@push('styles')
<style>
    /* Modern Edit Game Styles */
    .game-edit-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .game-edit-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="%23ffffff06" points="0,1000 1000,0 1000,1000"/></svg>');
        pointer-events: none;
    }

    .modern-edit-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
        animation: slideInDown 0.6s ease-out;
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

    .modern-edit-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #ed8936, #f093fb, #f5576c, #4facfe);
        background-size: 300% 100%;
        animation: gradientShift 3s ease infinite;
    }

    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .modern-edit-title {
        font-weight: 700;
        font-size: 2rem;
        color: #2d3748;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .modern-nav-group {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .modern-nav-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
        text-decoration: none;
    }

    .nav-show {
        background: linear-gradient(135deg, #4299e1, #3182ce);
        color: white;
    }

    .nav-back {
        background: #f7fafc;
        color: #4a5568;
        border-color: #e2e8f0;
    }

    .modern-nav-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        color: inherit;
    }

    .nav-show:hover { color: white; }
    .nav-back:hover {
        background: #edf2f7;
        color: #2d3748;
    }

    .modern-form-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.8s ease-out;
        animation-fill-mode: both;
    }

    .modern-form-card:nth-child(1) { animation-delay: 0.1s; }
    .modern-form-card:nth-child(2) { animation-delay: 0.2s; }
    .modern-form-card:nth-child(3) { animation-delay: 0.3s; }

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

    .modern-form-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(226, 232, 240, 0.5);
    }

    .form-header-icon {
        width: 50px;
        height: 50px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.5rem;
        color: white;
    }

    .icon-primary { background: linear-gradient(135deg, #667eea, #764ba2); }
    .icon-success { background: linear-gradient(135deg, #48bb78, #38a169); }
    .icon-warning { background: linear-gradient(135deg, #ed8936, #dd6b20); }
    .icon-info { background: linear-gradient(135deg, #4299e1, #3182ce); }

    .form-header-title {
        font-weight: 700;
        font-size: 1.25rem;
        color: #2d3748;
        margin: 0;
    }

    .modern-form-group {
        margin-bottom: 1.5rem;
    }

    .modern-form-label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modern-form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: rgba(247, 250, 252, 0.7);
        transition: all 0.3s ease;
        resize: vertical;
    }

    .modern-form-control:focus {
        outline: none;
        border-color: #4299e1;
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    }

    .modern-form-control.is-invalid {
        border-color: #f56565;
        background: rgba(254, 226, 226, 0.5);
    }

    .modern-select {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        padding-right: 2.5rem;
    }

    .modern-checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .modern-checkbox-item {
        display: flex;
        align-items: center;
        background: rgba(247, 250, 252, 0.7);
        padding: 0.75rem 1rem;
        border-radius: 10px;
        border: 2px solid rgba(226, 232, 240, 0.5);
        transition: all 0.3s ease;
        cursor: pointer;
        min-width: 120px;
    }

    .modern-checkbox-item:hover {
        background: rgba(237, 242, 247, 0.9);
        border-color: #4299e1;
    }

    .modern-checkbox-item.checked {
        background: rgba(66, 153, 225, 0.1);
        border-color: #4299e1;
        color: #2d3748;
    }

    .modern-checkbox {
        margin-right: 0.5rem;
        transform: scale(1.2);
    }

    .modern-file-input {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
        background: rgba(247, 250, 252, 0.7);
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .file-input-wrapper:hover {
        background: rgba(237, 242, 247, 0.9);
        border-color: #4299e1;
    }

    .file-input-wrapper.has-file {
        background: rgba(66, 153, 225, 0.1);
        border-color: #4299e1;
        border-style: solid;
    }

    .file-input-wrapper input[type="file"] {
        position: absolute;
        left: -9999px;
    }

    .file-upload-icon {
        font-size: 2rem;
        color: #a0aec0;
        margin-bottom: 0.5rem;
    }

    .file-upload-text {
        color: #4a5568;
        font-weight: 500;
    }

    .file-upload-subtext {
        color: #718096;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .file-preview {
        margin-top: 1rem;
        padding: 1rem;
        background: rgba(237, 242, 247, 0.7);
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .file-preview img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid rgba(226, 232, 240, 0.5);
    }

    .modern-error-alert {
        background: rgba(254, 226, 226, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(245, 101, 101, 0.3);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #f56565;
    }

    .modern-error-alert h6 {
        color: #c53030;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .modern-error-alert ul {
        margin: 0;
        color: #9b2c2c;
    }

    .modern-form-actions {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-top: 2rem;
        text-align: center;
        animation: fadeInUp 0.8s ease-out 0.4s both;
    }

    .modern-submit-btn {
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
        border: none;
        padding: 1rem 3rem;
        border-radius: 15px;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
        margin: 0 0.5rem;
    }

    .modern-submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(72, 187, 120, 0.4);
        color: white;
    }

    .modern-cancel-btn {
        background: #f7fafc;
        color: #4a5568;
        border: 2px solid #e2e8f0;
        padding: 1rem 2rem;
        border-radius: 15px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        margin: 0 0.5rem;
    }

    .modern-cancel-btn:hover {
        background: #edf2f7;
        color: #2d3748;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .game-edit-container {
            padding: 1rem 0;
        }

        .modern-edit-header {
            padding: 1.5rem;
            margin: 0 1rem 1.5rem;
        }

        .modern-edit-title {
            font-size: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .modern-nav-group {
            flex-direction: column;
            width: 100%;
        }

        .modern-nav-btn {
            width: 100%;
            text-align: center;
            justify-content: center;
        }

        .modern-form-card {
            margin: 0 1rem 1.5rem;
        }

        .modern-checkbox-group {
            grid-template-columns: 1fr;
        }

        .modern-form-actions {
            margin: 2rem 1rem 0;
            flex-direction: column;
        }

        .modern-submit-btn,
        .modern-cancel-btn {
            width: 100%;
            margin: 0.5rem 0;
        }
    }

    /* Loading animations */
    .loading {
        position: relative;
    }

    .loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: inherit;
    }

    .loading::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid #e2e8f0;
        border-top-color: #4299e1;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        z-index: 10;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="game-edit-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-10">
                <!-- Modern Header -->
                <div class="modern-edit-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <h1 class="modern-edit-title">
                            <i class="fas fa-edit text-warning"></i>
                            <span>Chỉnh sửa Game: {{ $game->name }}</span>
                        </h1>
                        <div class="modern-nav-group">
                            <a href="{{ route('admin.games.show', $game->id) }}" class="modern-nav-btn nav-show">
                                <i class="fas fa-eye me-2"></i>Xem chi tiết
                            </a>
                            <a href="{{ route('admin.games.index') }}" class="modern-nav-btn nav-back">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Error Alert -->
                @if ($errors->any())
                <div class="modern-error-alert">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Vui lòng kiểm tra lại:</h6>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <!-- Form bắt đầu -->
                <form action="{{ route('admin.games.update', $game->id) }}" method="POST" enctype="multipart/form-data" id="gameEditForm">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Left Column: Basic Info -->
                        <div class="col-lg-6">
                            <!-- Basic Information Card -->
                            <div class="modern-form-card">
                                <div class="modern-form-header">
                                    <div class="form-header-icon icon-primary">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <h3 class="form-header-title">Thông tin cơ bản</h3>
                                </div>

                                <div class="modern-form-group">
                                    <label for="name" class="modern-form-label">
                                        <i class="fas fa-gamepad text-primary"></i>
                                        Tên Game *
                                    </label>
                                    <input type="text" 
                                           class="modern-form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $game->name) }}" 
                                           placeholder="Nhập tên game..." 
                                           required>
                                </div>

                                <div class="modern-form-group">
                                    <label for="genre" class="modern-form-label">
                                        <i class="fas fa-tags text-info"></i>
                                        Thể loại
                                    </label>
                                    <input type="text" 
                                           class="modern-form-control {{ $errors->has('genre') ? 'is-invalid' : '' }}" 
                                           id="genre" 
                                           name="genre" 
                                           value="{{ old('genre', $game->genre) }}" 
                                           placeholder="VD: MOBA, FPS, Battle Royale...">
                                </div>

                                <div class="modern-form-group">
                                    <label for="publisher" class="modern-form-label">
                                        <i class="fas fa-building text-success"></i>
                                        Nhà phát hành
                                    </label>
                                    <input type="text" 
                                           class="modern-form-control {{ $errors->has('publisher') ? 'is-invalid' : '' }}" 
                                           id="publisher" 
                                           name="publisher" 
                                           value="{{ old('publisher', $game->publisher) }}" 
                                           placeholder="Nhập tên nhà phát hành...">
                                </div>

                                <div class="modern-form-group">
                                    <label for="release_date" class="modern-form-label">
                                        <i class="fas fa-calendar text-warning"></i>
                                        Ngày phát hành
                                    </label>
                                    <input type="date" 
                                           class="modern-form-control {{ $errors->has('release_date') ? 'is-invalid' : '' }}" 
                                           id="release_date" 
                                           name="release_date" 
                                           value="{{ old('release_date', $game->release_date ? $game->release_date->format('Y-m-d') : '') }}">
                                </div>

                                <div class="modern-form-group">
                                    <label for="official_website" class="modern-form-label">
                                        <i class="fas fa-globe text-info"></i>
                                        Website chính thức
                                    </label>
                                    <input type="url" 
                                           class="modern-form-control {{ $errors->has('official_website') ? 'is-invalid' : '' }}" 
                                           id="official_website" 
                                           name="official_website" 
                                           value="{{ old('official_website', $game->official_website) }}" 
                                           placeholder="https://...">
                                </div>

                                <div class="modern-form-group">
                                    <label for="description" class="modern-form-label">
                                        <i class="fas fa-align-left text-primary"></i>
                                        Mô tả Game
                                    </label>
                                    <textarea class="modern-form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" 
                                              id="description" 
                                              name="description" 
                                              rows="4" 
                                              placeholder="Mô tả chi tiết về game...">{{ old('description', $game->description) }}</textarea>
                                </div>

                                <div class="modern-form-group">
                                    <label for="status" class="modern-form-label">
                                        <i class="fas fa-toggle-on text-success"></i>
                                        Trạng thái
                                    </label>
                                    <select class="modern-form-control modern-select {{ $errors->has('status') ? 'is-invalid' : '' }}" 
                                            id="status" 
                                            name="status" 
                                            required>
                                        <option value="">Chọn trạng thái</option>
                                        <option value="active" {{ old('status', $game->status) === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                                        <option value="inactive" {{ old('status', $game->status) === 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                                        <option value="development" {{ old('status', $game->status) === 'development' ? 'selected' : '' }}>Đang phát triển</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Images Card -->
                            <div class="modern-form-card">
                                <div class="modern-form-header">
                                    <div class="form-header-icon icon-warning">
                                        <i class="fas fa-images"></i>
                                    </div>
                                    <h3 class="form-header-title">Hình ảnh</h3>
                                </div>

                                <div class="modern-form-group">
                                    <label for="logo" class="modern-form-label">
                                        <i class="fas fa-image text-primary"></i>
                                        Logo Game
                                    </label>
                                    <div class="modern-file-input">
                                        <div class="file-input-wrapper" onclick="document.getElementById('logo').click()">
                                            <input type="file" id="logo" name="logo" accept="image/*" style="display: none;">
                                            <div class="file-upload-icon">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                            </div>
                                            <div class="file-upload-text">Chọn logo game</div>
                                            <div class="file-upload-subtext">PNG, JPG, GIF tối đa 2MB</div>
                                        </div>
                                        @if($game->logo_url)
                                        <div class="file-preview">
                                            <img src="{{ $game->logo_url }}" alt="Current Logo">
                                            <div>
                                                <strong>Logo hiện tại</strong>
                                                <div class="text-muted small">Chọn file mới để thay đổi</div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="modern-form-group">
                                    <label for="banner" class="modern-form-label">
                                        <i class="fas fa-panorama text-info"></i>
                                        Banner Game
                                    </label>
                                    <div class="modern-file-input">
                                        <div class="file-input-wrapper" onclick="document.getElementById('banner').click()">
                                            <input type="file" id="banner" name="banner" accept="image/*" style="display: none;">
                                            <div class="file-upload-icon">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                            </div>
                                            <div class="file-upload-text">Chọn banner game</div>
                                            <div class="file-upload-subtext">PNG, JPG, GIF tối đa 5MB - Tỷ lệ 16:9</div>
                                        </div>
                                        @if($game->banner_url)
                                        <div class="file-preview">
                                            <img src="{{ $game->banner_url }}" alt="Current Banner">
                                            <div>
                                                <strong>Banner hiện tại</strong>
                                                <div class="text-muted small">Chọn file mới để thay đổi</div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Esports Info -->
                        <div class="col-lg-6">
                            <!-- Esports Information Card -->
                            <div class="modern-form-card">
                                <div class="modern-form-header">
                                    <div class="form-header-icon icon-success">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <h3 class="form-header-title">Thông tin Esports</h3>
                                </div>

                                <div class="modern-form-group">
                                    <label class="modern-form-label">
                                        <i class="fas fa-medal text-warning"></i>
                                        Hỗ trợ thi đấu Esports
                                    </label>
                                    <div class="modern-checkbox-group">
                                        <label class="modern-checkbox-item {{ old('esport_support', $game->esport_support) ? 'checked' : '' }}">
                                            <input type="hidden" name="esport_support" value="0">
                                            <input type="checkbox" 
                                                   class="modern-checkbox" 
                                                   name="esport_support" 
                                                   value="1" 
                                                   {{ old('esport_support', $game->esport_support) ? 'checked' : '' }}
                                                   onchange="toggleEsportsFields(this)">
                                            <span>Game hỗ trợ Esports</span>
                                        </label>
                                    </div>
                                </div>

                                <div id="esportsFields" style="display: {{ old('esport_support', $game->esport_support) ? 'block' : 'none' }}">
                                    <div class="modern-form-group">
                                        <label for="team_size" class="modern-form-label">
                                            <i class="fas fa-users text-info"></i>
                                            Đội hình
                                        </label>
                                        <input type="text" 
                                               class="modern-form-control {{ $errors->has('team_size') ? 'is-invalid' : '' }}" 
                                               id="team_size" 
                                               name="team_size" 
                                               value="{{ old('team_size', $game->team_size) }}" 
                                               placeholder="VD: 5v5, 1v1, 3v3...">
                                    </div>

                                    <div class="modern-form-group">
                                        <label class="modern-form-label">
                                            <i class="fas fa-gamepad text-primary"></i>
                                            Chế độ chơi
                                        </label>
                                        <div class="modern-checkbox-group">
                                            @php
                                                $availableModes = ['Ranked', 'Tournament', 'Custom', 'Training', 'Casual'];
                                                $selectedModes = old('game_modes', $game->game_modes ?? []);
                                            @endphp
                                            @foreach($availableModes as $mode)
                                            <label class="modern-checkbox-item {{ in_array($mode, $selectedModes) ? 'checked' : '' }}">
                                                <input type="checkbox" 
                                                       class="modern-checkbox" 
                                                       name="game_modes[]" 
                                                       value="{{ $mode }}" 
                                                       {{ in_array($mode, $selectedModes) ? 'checked' : '' }}>
                                                <span>{{ $mode }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="modern-form-group">
                                        <label class="modern-form-label">
                                            <i class="fas fa-desktop text-success"></i>
                                            Hình thức thi đấu
                                        </label>
                                        <div class="modern-checkbox-group">
                                            @php
                                                $availableFormats = ['Online', 'Offline', 'Hybrid', 'LAN'];
                                                $selectedFormats = old('competition_formats', $game->competition_formats ?? []);
                                            @endphp
                                            @foreach($availableFormats as $format)
                                            <label class="modern-checkbox-item {{ in_array($format, $selectedFormats) ? 'checked' : '' }}">
                                                <input type="checkbox" 
                                                       class="modern-checkbox" 
                                                       name="competition_formats[]" 
                                                       value="{{ $format }}" 
                                                       {{ in_array($format, $selectedFormats) ? 'checked' : '' }}>
                                                <span>{{ $format }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Platform Information Card -->
                            <div class="modern-form-card">
                                <div class="modern-form-header">
                                    <div class="form-header-icon icon-info">
                                        <i class="fas fa-laptop"></i>
                                    </div>
                                    <h3 class="form-header-title">Nền tảng hỗ trợ</h3>
                                </div>

                                <div class="modern-form-group">
                                    <label class="modern-form-label">
                                        <i class="fas fa-desktop text-primary"></i>
                                        Nền tảng chơi game
                                    </label>
                                    <div class="modern-checkbox-group">
                                        @php
                                            $availablePlatforms = ['PC', 'Console', 'Mobile', 'Web'];
                                            $selectedPlatforms = old('platforms', $game->platforms ?? []);
                                        @endphp
                                        @foreach($availablePlatforms as $platform)
                                        <label class="modern-checkbox-item {{ in_array($platform, $selectedPlatforms) ? 'checked' : '' }}">
                                            <input type="checkbox" 
                                                   class="modern-checkbox" 
                                                   name="platforms[]" 
                                                   value="{{ $platform }}" 
                                                   {{ in_array($platform, $selectedPlatforms) ? 'checked' : '' }}>
                                            @switch($platform)
                                                @case('PC')
                                                    <i class="fas fa-desktop me-2 text-primary"></i>
                                                @break
                                                @case('Console')
                                                    <i class="fas fa-gamepad me-2 text-success"></i>
                                                @break
                                                @case('Mobile')
                                                    <i class="fas fa-mobile-alt me-2 text-warning"></i>
                                                @break
                                                @case('Web')
                                                    <i class="fas fa-globe me-2 text-info"></i>
                                                @break
                                            @endswitch
                                            <span>{{ $platform }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="modern-form-actions">
                        <div class="d-flex justify-content-center flex-wrap gap-2">
                            <button type="submit" class="modern-submit-btn" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Cập nhật Game
                            </button>
                            <a href="{{ route('admin.games.show', $game->id) }}" class="modern-cancel-btn">
                                <i class="fas fa-times me-2"></i>Hủy bỏ
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Modern file input handling
    $('input[type="file"]').change(function() {
        const wrapper = $(this).closest('.file-input-wrapper');
        if (this.files && this.files[0]) {
            wrapper.addClass('has-file');
            const fileName = this.files[0].name;
            wrapper.find('.file-upload-text').text(fileName);
            wrapper.find('.file-upload-subtext').text('File đã được chọn - Click để thay đổi');
        }
    });
    
    // Checkbox styling
    $('.modern-checkbox').change(function() {
        const item = $(this).closest('.modern-checkbox-item');
        if (this.checked) {
            item.addClass('checked');
        } else {
            item.removeClass('checked');
        }
    });
    
    // Form submission handling
    $('#gameEditForm').submit(function() {
        const submitBtn = $('#submitBtn');
        submitBtn.prop('disabled', true)
               .html('<i class="fas fa-spinner fa-spin me-2"></i>Đang cập nhật...')
               .addClass('loading');
    });
    
    // Show modern notification
    function showModernNotification(message, type = 'info', duration = 4000) {
        const notification = $(`
            <div class="modern-notification notification-${type} show">
                <div class="d-flex align-items-start">
                    <div class="notification-icon me-3">
                        <i class="fas ${getNotificationIcon(type)}"></i>
                    </div>
                    <div class="notification-content flex-grow-1">
                        <div class="notification-message">${message}</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-2" onclick="closeNotification(this)"></button>
                </div>
            </div>
        `);
        
        $('.modern-notifications-container').append(notification);
        
        setTimeout(() => {
            notification.removeClass('show').addClass('hide');
            setTimeout(() => notification.remove(), 300);
        }, duration);
    }
    
    function getNotificationIcon(type) {
        const icons = {
            'success': 'fa-check-circle',
            'error': 'fa-times-circle',
            'warning': 'fa-exclamation-triangle',
            'info': 'fa-info-circle'
        };
        return icons[type] || icons['info'];
    }
});

// Toggle esports fields
function toggleEsportsFields(checkbox) {
    const esportsFields = document.getElementById('esportsFields');
    if (checkbox.checked) {
        esportsFields.style.display = 'block';
        $(checkbox).closest('.modern-checkbox-item').addClass('checked');
    } else {
            esportsFields.style.display = 'none';
    }    
}
</script>

<!-- Modern Notifications Container -->
<div class="modern-notifications-container"></div>
@endpush
                                        