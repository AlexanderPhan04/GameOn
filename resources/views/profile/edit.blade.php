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
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('uploads/' . auth()->user()->avatar) }}" alt="Avatar" id="avatar-preview-img">
                            @else
                                <div class="avatar-preview-placeholder" id="avatar-placeholder">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <img src="" alt="Avatar" id="avatar-preview-img" style="display: none;">
                            @endif
                        </div>
                        <div>
                            <label class="avatar-upload-btn">
                                <i class="fa-solid fa-upload"></i>
                                Chọn ảnh
                                <input type="file" name="avatar" accept="image/*" class="hidden" id="avatar-input">
                            </label>
                            <p class="text-xs text-gray-500 mt-2">PNG, JPG tối đa 2MB</p>
                        </div>
                    </div>
                    @error('avatar')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
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
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-input" value="{{ old('username', auth()->user()->username) }}" placeholder="@username">
                            @error('username')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
                            <input type="text" name="favorite_game" class="form-input" value="{{ old('favorite_game', auth()->user()->favorite_game) }}" placeholder="VD: Valorant, LMHT...">
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
                        <label class="form-label">Discord</label>
                        <div class="social-input-group">
                            <div class="social-icon"><i class="fa-brands fa-discord"></i></div>
                            <input type="text" name="discord" class="form-input" value="{{ old('discord', auth()->user()->discord) }}" placeholder="username#0000">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Facebook</label>
                        <div class="social-input-group">
                            <div class="social-icon"><i class="fa-brands fa-facebook-f"></i></div>
                            <input type="url" name="facebook" class="form-input" value="{{ old('facebook', auth()->user()->facebook) }}" placeholder="https://facebook.com/...">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">YouTube</label>
                        <div class="social-input-group">
                            <div class="social-icon"><i class="fa-brands fa-youtube"></i></div>
                            <input type="url" name="youtube" class="form-input" value="{{ old('youtube', auth()->user()->youtube) }}" placeholder="https://youtube.com/...">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Twitch</label>
                        <div class="social-input-group">
                            <div class="social-icon"><i class="fa-brands fa-twitch"></i></div>
                            <input type="url" name="twitch" class="form-input" value="{{ old('twitch', auth()->user()->twitch) }}" placeholder="https://twitch.tv/...">
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
    // Avatar preview
    document.getElementById('avatar-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('avatar-preview-img');
                const placeholder = document.getElementById('avatar-placeholder');
                img.src = e.target.result;
                img.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
