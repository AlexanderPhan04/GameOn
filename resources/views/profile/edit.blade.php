@extends('layouts.app')

@section('title', 'Chỉnh sửa thông tin cá nhân')

@push('styles')
<style>
    .profile-edit-container { background: #000814; min-height: 100vh; padding: 1.5rem; }

    /* Hero Section */
    .edit-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .edit-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #00E5FF, #8b5cf6, transparent);
    }
    .hero-icon {
        width: 60px; height: 60px; min-width: 60px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 0 25px rgba(99, 102, 241, 0.3);
    }
    .hero-icon i { font-size: 1.5rem; color: white; }
    .hero-title { font-family: 'Rajdhani', sans-serif; font-size: 1.5rem; font-weight: 700; color: #00E5FF; margin: 0; }
    .hero-subtitle { color: #94a3b8; font-size: 0.9rem; margin: 0.25rem 0 0 0; }

    /* Form Card */
    .form-card {
        background: linear-gradient(145deg, #0d1b2a, #000022);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .form-card-header {
        background: linear-gradient(135deg, rgba(0, 0, 85, 0.5), rgba(0, 0, 34, 0.5));
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .form-card-header i { color: #00E5FF; font-size: 1.1rem; }
    .form-card-header h3 { font-family: 'Rajdhani', sans-serif; font-size: 1.1rem; font-weight: 600; color: #FFFFFF; margin: 0; }
    .form-card-body { padding: 1.5rem; }

    /* Form Elements */
    .form-group { margin-bottom: 1.25rem; }
    .form-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 500;
        color: #94a3b8;
        margin-bottom: 0.5rem;
    }
    .form-input {
        width: 100%;
        box-sizing: border-box;
        background: rgba(0, 0, 20, 0.6);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        color: #FFFFFF;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .form-input:focus {
        outline: none;
        border-color: #00E5FF;
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.2);
        background: rgba(0, 0, 40, 0.6);
    }
    .form-input::placeholder { color: #64748b; }
    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }

    /* Avatar Upload */
    .avatar-upload {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    .avatar-preview {
        width: 100px; height: 100px;
        border-radius: 50%;
        border: 3px solid rgba(0, 229, 255, 0.3);
        overflow: hidden;
        position: relative;
        background: #0d1b2a;
    }
    .avatar-preview img {
        width: 100%; height: 100%;
        object-fit: cover;
    }
    .avatar-preview-placeholder {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        color: #64748b;
        font-size: 2.5rem;
    }
    .avatar-upload-btn {
        background: linear-gradient(135deg, #000055, #000077);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.3);
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .avatar-upload-btn:hover {
        background: rgba(0, 229, 255, 0.15);
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.3);
    }

    /* Buttons */
    .btn-neon {
        background: linear-gradient(135deg, #000055, #000077);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.4);
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-size: 0.9rem;
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
    .btn-neon-primary {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-color: rgba(139, 92, 246, 0.4);
        color: #FFFFFF;
    }
    .btn-neon-primary:hover { box-shadow: 0 0 20px rgba(139, 92, 246, 0.4); }
    .btn-neon-danger {
        background: linear-gradient(135deg, #dc2626, #ef4444);
        border-color: rgba(239, 68, 68, 0.4);
        color: #FFFFFF;
    }
    .btn-neon-danger:hover { box-shadow: 0 0 20px rgba(239, 68, 68, 0.4); }

    /* Social Links */
    .social-input-group {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .social-icon {
        width: 40px; height: 40px;
        background: rgba(0, 0, 40, 0.6);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: #94a3b8;
        font-size: 1.1rem;
    }
    .social-input-group .form-input { flex: 1; }

    /* Grid */
    .form-grid { 
        display: grid; 
        grid-template-columns: repeat(2, 1fr); 
        gap: 1.5rem; 
    }
    .form-grid .form-group {
        margin-bottom: 0;
    }
    @media (max-width: 768px) { 
        .form-grid { 
            grid-template-columns: 1fr; 
            gap: 1rem;
        } 
    }

    /* Alert */
    .alert-success {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #10b981;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* Avatar Modal */
    .avatar-modal {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .avatar-modal-backdrop {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(5px);
    }
    .avatar-modal-content {
        position: relative;
        background: linear-gradient(145deg, #0d1b2a, #000022);
        border: 1px solid rgba(0, 229, 255, 0.3);
        border-radius: 20px;
        width: 90%;
        max-width: 550px;
        max-height: 85vh;
        overflow: hidden;
        box-shadow: 0 0 50px rgba(0, 229, 255, 0.2);
    }
    .avatar-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.15);
        background: linear-gradient(135deg, rgba(0, 0, 85, 0.5), rgba(0, 0, 34, 0.5));
    }
    .avatar-modal-header h3 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.2rem;
        font-weight: 600;
        color: #00E5FF;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .avatar-modal-close {
        background: none;
        border: none;
        color: #64748b;
        font-size: 1.25rem;
        cursor: pointer;
        transition: color 0.3s;
    }
    .avatar-modal-close:hover { color: #ef4444; }

    /* Avatar Tabs */
    .avatar-tabs {
        display: flex;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        background: rgba(0, 0, 20, 0.3);
    }
    .avatar-tab {
        flex: 1;
        padding: 1rem;
        background: none;
        border: none;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border-bottom: 2px solid transparent;
    }
    .avatar-tab:hover { color: #94a3b8; background: rgba(0, 229, 255, 0.05); }
    .avatar-tab.active {
        color: #00E5FF;
        border-bottom-color: #00E5FF;
        background: rgba(0, 229, 255, 0.1);
    }

    /* Tab Content */
    .avatar-tab-content {
        display: none;
        padding: 1.5rem;
        max-height: 350px;
        overflow-y: auto;
    }
    .avatar-tab-content.active { display: block; }

    /* Upload Zone */
    .upload-zone {
        border: 2px dashed rgba(0, 229, 255, 0.3);
        border-radius: 16px;
        padding: 2.5rem;
        text-align: center;
        transition: all 0.3s;
    }
    .upload-zone:hover, .upload-zone.dragover {
        border-color: #00E5FF;
        background: rgba(0, 229, 255, 0.05);
    }
    .upload-zone i { font-size: 3rem; color: #00E5FF; margin-bottom: 1rem; }
    .upload-zone p { color: #94a3b8; margin: 0.5rem 0; }
    .upload-btn {
        display: inline-block;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        padding: 0.6rem 1.5rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 0.5rem;
    }
    .upload-btn:hover { box-shadow: 0 0 20px rgba(139, 92, 246, 0.4); }
    .upload-hint { font-size: 0.8rem; color: #64748b; margin-top: 1rem !important; }
    .upload-preview {
        position: relative;
        display: flex;
        justify-content: center;
    }
    .upload-preview img {
        width: 150px; height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #00E5FF;
    }
    .remove-preview {
        position: absolute;
        top: 0; right: calc(50% - 85px);
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 50%;
        width: 32px; height: 32px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Social Avatars */
    .social-avatars { display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap; }
    .social-avatar-option {
        text-align: center;
        cursor: pointer;
        padding: 1rem;
        border-radius: 16px;
        border: 2px solid transparent;
        transition: all 0.3s;
    }
    .social-avatar-option:hover { border-color: rgba(0, 229, 255, 0.3); background: rgba(0, 229, 255, 0.05); }
    .social-avatar-option.selected { border-color: #00E5FF; background: rgba(0, 229, 255, 0.15); }
    .social-avatar-option img {
        width: 80px; height: 80px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 0.5rem;
    }
    .social-label {
        color: #94a3b8;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
    }
    .social-hint, .system-hint {
        text-align: center;
        color: #64748b;
        font-size: 0.8rem;
        margin-top: 1.5rem;
    }

    /* System Avatars */
    .system-avatars {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    .system-avatar-option {
        cursor: pointer;
        border-radius: 12px;
        border: 2px solid transparent;
        overflow: hidden;
        transition: all 0.3s;
        aspect-ratio: 1;
    }
    .system-avatar-option:hover { border-color: rgba(0, 229, 255, 0.4); transform: scale(1.05); }
    .system-avatar-option.selected { border-color: #00E5FF; box-shadow: 0 0 15px rgba(0, 229, 255, 0.4); }
    .system-avatar-option img { width: 100%; height: 100%; object-fit: cover; }

    /* Modal Footer */
    .avatar-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(0, 229, 255, 0.15);
        background: rgba(0, 0, 20, 0.3);
    }
    .btn-cancel {
        background: rgba(100, 116, 139, 0.2);
        color: #94a3b8;
        border: 1px solid rgba(100, 116, 139, 0.3);
        padding: 0.6rem 1.25rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .btn-cancel:hover { background: rgba(100, 116, 139, 0.3); }
    .btn-confirm {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        border: none;
        padding: 0.6rem 1.25rem;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
    }
    .btn-confirm:hover { box-shadow: 0 0 20px rgba(139, 92, 246, 0.4); }
</style>
@endpush

@section('content')
<div class="profile-edit-container">
    <div class="max-w-4xl mx-auto">
        <!-- Hero Section -->
        <div class="edit-hero">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="hero-icon">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    <div>
                        <h1 class="hero-title">Chỉnh sửa hồ sơ</h1>
                        <p class="hero-subtitle">Cập nhật thông tin cá nhân của bạn</p>
                    </div>
                </div>
                <a href="{{ route('profile.show', auth()->user()) }}" class="btn-neon">
                    <i class="fa-solid fa-arrow-left"></i>
                    Quay lại
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert-success">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Avatar Section -->
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fa-solid fa-camera"></i>
                    <h3>Ảnh đại diện</h3>
                </div>
                <div class="form-card-body">
                    <div class="avatar-upload">
                        <div class="avatar-preview">
                            <img src="{{ auth()->user()->getDisplayAvatar() }}" alt="Avatar" id="avatar-preview-img">
                        </div>
                        <div>
                            <button type="button" class="avatar-upload-btn" id="open-avatar-modal">
                                <i class="fa-solid fa-edit"></i>
                                Chọn ảnh
                            </button>
                            <p class="text-xs text-gray-500 mt-2">Chọn từ thư viện hoặc tải lên</p>
                        </div>
                    </div>
                    <!-- Hidden inputs for form submission -->
                    <input type="file" name="avatar" accept="image/*" class="hidden" id="avatar-input">
                    <input type="hidden" name="reset_to_google_avatar" id="reset-google-avatar" value="">
                    <input type="hidden" name="system_avatar" id="system-avatar-input" value="">
                    @error('avatar')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Avatar Selection Modal -->
            <div id="avatar-modal" class="avatar-modal" style="display: none;">
                <div class="avatar-modal-backdrop" onclick="closeAvatarModal()"></div>
                <div class="avatar-modal-content">
                    <div class="avatar-modal-header">
                        <h3><i class="fa-solid fa-image"></i> Chọn ảnh đại diện</h3>
                        <button type="button" class="avatar-modal-close" onclick="closeAvatarModal()">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    
                    <!-- Tabs -->
                    <div class="avatar-tabs">
                        <button type="button" class="avatar-tab active" data-tab="upload">
                            <i class="fa-solid fa-upload"></i> Tải lên
                        </button>
                        @if(auth()->user()->profile?->google_avatar)
                        <button type="button" class="avatar-tab" data-tab="social">
                            <i class="fab fa-google"></i> Mạng xã hội
                        </button>
                        @endif
                        <button type="button" class="avatar-tab" data-tab="system">
                            <i class="fa-solid fa-images"></i> Hệ thống
                        </button>
                    </div>

                    <!-- Tab Content: Upload -->
                    <div class="avatar-tab-content active" id="tab-upload">
                        <div class="upload-zone" id="upload-zone">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <p>Kéo thả ảnh vào đây hoặc</p>
                            <label class="upload-btn">
                                Chọn từ máy tính
                                <input type="file" accept="image/*" id="modal-file-input" class="hidden">
                            </label>
                            <p class="upload-hint">PNG, JPG tối đa 2MB</p>
                        </div>
                        <div id="upload-preview" class="upload-preview" style="display: none;">
                            <img src="" alt="Preview" id="upload-preview-img">
                            <button type="button" class="remove-preview" onclick="removeUploadPreview()">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tab Content: Social Media -->
                    @if(auth()->user()->profile?->google_avatar)
                    <div class="avatar-tab-content" id="tab-social">
                        <div class="social-avatars">
                            <div class="social-avatar-option" data-type="google">
                                <img src="{{ auth()->user()->profile->google_avatar }}" alt="Google Avatar">
                                <div class="social-label">
                                    <i class="fab fa-google"></i> Google
                                </div>
                            </div>
                        </div>
                        <p class="social-hint">Chọn avatar từ tài khoản mạng xã hội đã liên kết</p>
                    </div>
                    @endif

                    <!-- Tab Content: System Avatars -->
                    <div class="avatar-tab-content" id="tab-system">
                        <div class="system-avatars">
                            @for($i = 1; $i <= 6; $i++)
                            <div class="system-avatar-option" data-avatar="system/avatar_{{ $i }}.png">
                                <img src="{{ asset('images/system-avatars/avatar_' . $i . '.png') }}" alt="Avatar {{ $i }}">
                            </div>
                            @endfor
                        </div>
                        <p class="system-hint">Chọn từ bộ sưu tập avatar của hệ thống</p>
                    </div>

                    <!-- Modal Footer -->
                    <div class="avatar-modal-footer">
                        <button type="button" class="btn-cancel" onclick="closeAvatarModal()">Hủy</button>
                        <button type="button" class="btn-confirm" onclick="confirmAvatarSelection()">
                            <i class="fa-solid fa-check"></i> Xác nhận
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="form-card-header">
                    <i class="fa-solid fa-user"></i>
                    <h3>Thông tin cơ bản</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Tên hiển thị *</label>
                            <input type="text" name="name" class="form-input" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">ID App</label>
                            <input type="text" class="form-input" value="{{ auth()->user()->profile?->id_app ?? 'Chưa có' }}" readonly style="opacity: 0.7; cursor: not-allowed;">
                            <p class="text-xs text-gray-500 mt-1">Mã định danh hệ thống, không thể thay đổi</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Giới thiệu bản thân</label>
                        <textarea name="bio" class="form-input form-textarea" placeholder="Viết vài dòng về bản thân...">{{ old('bio', auth()->user()->bio) }}</textarea>
                        @error('bio')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="form-card-header">
                    <i class="fa-solid fa-gamepad"></i>
                    <h3>Thông tin Gaming</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Game yêu thích</label>
                            <select name="favorite_game" class="form-input game-select" id="game-select">
                                <option value="">-- Chọn game --</option>
                                @foreach($games as $game)
                                <option value="{{ $game->name }}" 
                                    data-image="{{ $game->logo_url }}"
                                    {{ old('favorite_game', auth()->user()->favorite_game) == $game->name ? 'selected' : '' }}>
                                    {{ $game->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rank hiện tại</label>
                            <input type="text" name="current_rank" class="form-input" value="{{ old('current_rank', auth()->user()->current_rank) }}" placeholder="VD: Diamond, Master...">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Team/Clan</label>
                        <input type="text" name="team" class="form-input" value="{{ old('team', auth()->user()->team) }}" placeholder="Tên team hoặc clan của bạn">
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="form-card-header">
                    <i class="fa-solid fa-link"></i>
                    <h3>Liên kết mạng xã hội</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label class="form-label">Google</label>
                        <div class="social-input-group">
                            <div class="social-icon"><i class="fa-brands fa-google"></i></div>
                            @if(auth()->user()->google_id)
                                <input type="text" class="form-input" value="{{ auth()->user()->google_email ?? auth()->user()->email }}" readonly style="opacity: 0.7; cursor: not-allowed;">
                                <span style="color: #10b981; font-size: 0.85rem; display: flex; align-items: center; gap: 0.3rem; margin-left: 0.75rem;">
                                    <i class="fa-solid fa-check-circle"></i> Đã liên kết
                                </span>
                            @else
                                <input type="text" class="form-input" value="Chưa liên kết" readonly style="opacity: 0.5; cursor: not-allowed;">
                                <a href="{{ route('google.redirect') }}" class="btn-neon" style="margin-left: 0.75rem; padding: 0.5rem 1rem; font-size: 0.8rem;">
                                    <i class="fa-brands fa-google"></i> Liên kết
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="form-card-header">
                    <i class="fa-solid fa-lock"></i>
                    <h3>Đổi mật khẩu</h3>
                </div>
                <div class="form-card-body">
                    <p class="text-sm text-gray-400 mb-4">Để trống nếu không muốn thay đổi mật khẩu</p>
                    <div class="form-group">
                        <label class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" class="form-input" placeholder="••••••••">
                        @error('current_password')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Mật khẩu mới</label>
                            <input type="password" name="password" class="form-input" placeholder="••••••••">
                            @error('password')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" name="password_confirmation" class="form-input" placeholder="••••••••">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="btn-neon btn-neon-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Lưu thay đổi
                </button>
                <a href="{{ route('profile.show', auth()->user()) }}" class="btn-neon">
                    <i class="fa-solid fa-xmark"></i>
                    Hủy bỏ
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Avatar Modal State
    let selectedType = null; // 'upload', 'social', 'system'
    let selectedFile = null;
    let selectedSystemAvatar = null;

    // Open/Close Modal
    document.getElementById('open-avatar-modal').addEventListener('click', function() {
        document.getElementById('avatar-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    });

    function closeAvatarModal() {
        document.getElementById('avatar-modal').style.display = 'none';
        document.body.style.overflow = '';
        resetModalState();
    }

    function resetModalState() {
        selectedType = null;
        selectedFile = null;
        selectedSystemAvatar = null;
        document.getElementById('upload-zone').style.display = 'block';
        document.getElementById('upload-preview').style.display = 'none';
        document.querySelectorAll('.social-avatar-option, .system-avatar-option').forEach(el => el.classList.remove('selected'));
    }

    // Tab Switching
    document.querySelectorAll('.avatar-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.dataset.tab;
            
            document.querySelectorAll('.avatar-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.avatar-tab-content').forEach(c => c.classList.remove('active'));
            
            this.classList.add('active');
            document.getElementById('tab-' + targetTab).classList.add('active');
        });
    });

    // File Upload
    const modalFileInput = document.getElementById('modal-file-input');
    const uploadZone = document.getElementById('upload-zone');
    
    modalFileInput.addEventListener('change', handleFileSelect);
    
    // Drag and drop
    uploadZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    uploadZone.addEventListener('dragleave', function() {
        this.classList.remove('dragover');
    });
    uploadZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileSelect({ target: { files: files } });
        }
    });

    function handleFileSelect(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            selectedFile = file;
            selectedType = 'upload';
            selectedSystemAvatar = null;
            
            // Clear other selections
            document.querySelectorAll('.social-avatar-option, .system-avatar-option').forEach(el => el.classList.remove('selected'));
            
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('upload-preview-img').src = e.target.result;
                document.getElementById('upload-zone').style.display = 'none';
                document.getElementById('upload-preview').style.display = 'flex';
            };
            reader.readAsDataURL(file);
        }
    }

    function removeUploadPreview() {
        selectedFile = null;
        selectedType = null;
        document.getElementById('upload-zone').style.display = 'block';
        document.getElementById('upload-preview').style.display = 'none';
        modalFileInput.value = '';
    }

    // Social Avatar Selection
    document.querySelectorAll('.social-avatar-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.social-avatar-option, .system-avatar-option').forEach(el => el.classList.remove('selected'));
            this.classList.add('selected');
            selectedType = 'social';
            selectedFile = null;
            selectedSystemAvatar = null;
            removeUploadPreview();
        });
    });

    // System Avatar Selection
    document.querySelectorAll('.system-avatar-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.social-avatar-option, .system-avatar-option').forEach(el => el.classList.remove('selected'));
            this.classList.add('selected');
            selectedType = 'system';
            selectedSystemAvatar = this.dataset.avatar;
            selectedFile = null;
            removeUploadPreview();
        });
    });

    // Confirm Selection
    function confirmAvatarSelection() {
        const avatarPreview = document.getElementById('avatar-preview-img');
        const avatarInput = document.getElementById('avatar-input');
        const resetGoogleInput = document.getElementById('reset-google-avatar');
        const systemAvatarInput = document.getElementById('system-avatar-input');
        
        // Clear all inputs first
        resetGoogleInput.value = '';
        systemAvatarInput.value = '';
        
        if (selectedType === 'upload' && selectedFile) {
            // Transfer file to form input
            const dt = new DataTransfer();
            dt.items.add(selectedFile);
            avatarInput.files = dt.files;
            
            // Update preview
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
            };
            reader.readAsDataURL(selectedFile);
            
        } else if (selectedType === 'social') {
            resetGoogleInput.value = '1';
            const socialImg = document.querySelector('.social-avatar-option.selected img');
            if (socialImg) {
                avatarPreview.src = socialImg.src;
            }
            
        } else if (selectedType === 'system' && selectedSystemAvatar) {
            systemAvatarInput.value = selectedSystemAvatar;
            const systemImg = document.querySelector('.system-avatar-option.selected img');
            if (systemImg) {
                avatarPreview.src = systemImg.src;
            }
        }
        
        closeAvatarModal();
    }
</script>
@endpush
