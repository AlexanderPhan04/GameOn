@extends('layouts.app')

@section('title', 'Thêm Game Mới')

@push('styles')
<style>
    .game-create-container {
        background: #000814;
        min-height: 100vh;
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
        background: linear-gradient(90deg, transparent, #00E5FF, transparent);
    }

    .hero-icon {
        width: 60px;
        height: 60px;
        min-width: 60px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 25px rgba(99, 102, 241, 0.3);
    }

    .hero-icon i {
        font-size: 1.5rem;
        color: white;
    }

    .hero-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #00E5FF;
        margin: 0;
    }

    .hero-subtitle {
        color: #94a3b8;
        font-size: 0.9rem;
        margin: 0.25rem 0 0 0;
    }

    .btn-neon {
        background: linear-gradient(135deg, #000055, #000077);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.4);
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-neon:hover {
        background: rgba(0, 229, 255, 0.15);
        box-shadow: 0 0 25px rgba(0, 229, 255, 0.4);
        color: #FFFFFF;
        transform: translateY(-2px);
    }

    .btn-neon-primary {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #FFFFFF;
        border: 1px solid rgba(99, 102, 241, 0.4);
    }

    .btn-neon-primary:hover {
        box-shadow: 0 0 25px rgba(99, 102, 241, 0.5);
        color: #FFFFFF;
    }

    /* Form Card */
    .form-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .form-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .card-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
    }

    .icon-primary { background: linear-gradient(135deg, #6366f1, #4f46e5); }
    .icon-success { background: linear-gradient(135deg, #22c55e, #16a34a); }
    .icon-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }

    .card-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #FFFFFF;
        margin: 0;
    }

    .form-card-body {
        padding: 1.5rem;
    }

    /* Form Controls */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        color: #94a3b8;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .form-label .required {
        color: #ef4444;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        color: #FFFFFF;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #00E5FF;
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.2);
        background: rgba(0, 0, 0, 0.5);
    }

    .form-input::placeholder {
        color: #64748b;
    }

    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        color: #FFFFFF;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .form-select:focus {
        outline: none;
        border-color: #00E5FF;
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.2);
    }

    .form-select option {
        background: #0d1b2a;
        color: #FFFFFF;
    }

    .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        color: #FFFFFF;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        resize: vertical;
        min-height: 100px;
    }

    .form-textarea:focus {
        outline: none;
        border-color: #00E5FF;
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.2);
    }

    .form-hint {
        color: #64748b;
        font-size: 0.8rem;
        margin-top: 0.35rem;
    }

    /* Checkbox Group */
    .checkbox-group {
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(0, 229, 255, 0.1);
        border-radius: 12px;
        padding: 1rem;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .checkbox-item:hover {
        background: rgba(0, 229, 255, 0.1);
    }

    .checkbox-input {
        width: 18px;
        height: 18px;
        accent-color: #00E5FF;
        cursor: pointer;
    }

    .checkbox-label {
        color: #94a3b8;
        font-size: 0.875rem;
        cursor: pointer;
    }

    /* Switch Toggle */
    .switch-container {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(0, 229, 255, 0.1);
        border-radius: 12px;
    }

    .switch {
        position: relative;
        width: 50px;
        height: 26px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .switch-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(100, 116, 139, 0.3);
        border-radius: 26px;
        transition: 0.3s;
    }

    .switch-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background: #64748b;
        border-radius: 50%;
        transition: 0.3s;
    }

    .switch input:checked + .switch-slider {
        background: rgba(0, 229, 255, 0.3);
    }

    .switch input:checked + .switch-slider:before {
        transform: translateX(24px);
        background: #00E5FF;
        box-shadow: 0 0 10px rgba(0, 229, 255, 0.5);
    }

    .switch-label {
        color: #FFFFFF;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* File Upload */
    .file-upload-zone {
        border: 2px dashed rgba(0, 229, 255, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        background: rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        position: relative;
        cursor: pointer;
    }

    .file-upload-zone:hover {
        border-color: rgba(0, 229, 255, 0.5);
        background: rgba(0, 229, 255, 0.05);
    }

    .file-upload-zone input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .file-upload-icon {
        font-size: 2rem;
        color: #00E5FF;
        margin-bottom: 0.5rem;
    }

    .file-upload-text {
        color: #94a3b8;
        font-size: 0.875rem;
    }

    .file-upload-text strong {
        color: #00E5FF;
    }

    .image-preview {
        max-width: 150px;
        max-height: 150px;
        border-radius: 12px;
        border: 2px solid rgba(0, 229, 255, 0.3);
        margin-top: 1rem;
    }

    /* Error Alert */
    .error-alert {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        color: #ef4444;
    }

    .error-alert-title {
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .error-alert ul {
        margin: 0;
        padding-left: 1.25rem;
    }

    .error-alert li {
        margin-bottom: 0.25rem;
    }

    /* Submit Section */
    .submit-section {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 20px;
        padding: 1.25rem 1.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .create-hero {
            padding: 1.25rem;
        }

        .hero-content {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 1rem;
        }

        .btn-neon {
            width: 100%;
            justify-content: center;
        }

        .form-card-body {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="game-create-container">
    <div class="max-w-6xl mx-auto px-4 py-6">
        <!-- Hero Header -->
        <div class="create-hero">
            <div class="hero-content flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="hero-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div>
                        <h1 class="hero-title">Thêm Game Mới</h1>
                        <p class="hero-subtitle">Tạo game mới cho hệ thống giải đấu</p>
                    </div>
                </div>
                <a href="{{ route('admin.games.index') }}" class="btn-neon">
                    <i class="fas fa-arrow-left"></i>
                    <span>Quay lại</span>
                </a>
            </div>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
        <div class="error-alert">
            <div class="error-alert-title">
                <i class="fas fa-exclamation-triangle"></i>
                Vui lòng kiểm tra lại:
            </div>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column: Basic Information -->
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="card-icon icon-primary">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h3 class="card-title">Thông tin cơ bản</h3>
                    </div>
                    <div class="form-card-body">
                        <!-- Tên Game -->
                        <div class="form-group">
                            <label class="form-label">
                                Tên Game <span class="required">*</span>
                            </label>
                            <input type="text" name="name" class="form-input" 
                                value="{{ old('name') }}" required placeholder="Nhập tên game">
                        </div>

                        <!-- Thể loại -->
                        <div class="form-group">
                            <label class="form-label">Thể loại</label>
                            <select name="genre" class="form-select">
                                <option value="">-- Chọn thể loại --</option>
                                <option value="MOBA" {{ old('genre') === 'MOBA' ? 'selected' : '' }}>MOBA</option>
                                <option value="FPS" {{ old('genre') === 'FPS' ? 'selected' : '' }}>FPS</option>
                                <option value="Battle Royale" {{ old('genre') === 'Battle Royale' ? 'selected' : '' }}>Battle Royale</option>
                                <option value="Sports" {{ old('genre') === 'Sports' ? 'selected' : '' }}>Sports</option>
                                <option value="Fighting" {{ old('genre') === 'Fighting' ? 'selected' : '' }}>Fighting</option>
                                <option value="Racing" {{ old('genre') === 'Racing' ? 'selected' : '' }}>Racing</option>
                                <option value="Strategy" {{ old('genre') === 'Strategy' ? 'selected' : '' }}>Strategy</option>
                                <option value="RPG" {{ old('genre') === 'RPG' ? 'selected' : '' }}>RPG</option>
                                <option value="Others" {{ old('genre') === 'Others' ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>

                        <!-- Nhà phát hành -->
                        <div class="form-group">
                            <label class="form-label">Nhà phát hành / Studio</label>
                            <input type="text" name="publisher" class="form-input"
                                value="{{ old('publisher') }}" placeholder="Ví dụ: Riot Games">
                        </div>

                        <!-- Ngày phát hành -->
                        <div class="form-group">
                            <label class="form-label">Ngày phát hành</label>
                            <input type="date" name="release_date" class="form-input"
                                value="{{ old('release_date') }}">
                        </div>

                        <!-- Tình trạng -->
                        <div class="form-group">
                            <label class="form-label">
                                Tình trạng <span class="required">*</span>
                            </label>
                            <select name="status" class="form-select" required>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                                <option value="discontinued" {{ old('status') === 'discontinued' ? 'selected' : '' }}>Ngừng phát triển</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Esports Information -->
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="card-icon icon-success">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3 class="card-title">Thông tin Esport</h3>
                    </div>
                    <div class="form-card-body">
                        <!-- Hỗ trợ Esport -->
                        <div class="form-group">
                            <div class="switch-container">
                                <label class="switch">
                                    <input type="checkbox" name="esport_support" value="1" {{ old('esport_support') ? 'checked' : '' }}>
                                    <span class="switch-slider"></span>
                                </label>
                                <span class="switch-label">Hỗ trợ thi đấu Esport</span>
                            </div>
                        </div>

                        <!-- Số lượng người chơi mỗi đội -->
                        <div class="form-group">
                            <label class="form-label">Số lượng người chơi mỗi đội</label>
                            <input type="text" name="team_size" class="form-input"
                                placeholder="VD: 5v5, 1v1, 3v3" value="{{ old('team_size') }}">
                            <p class="form-hint">Ví dụ: 5v5, 1v1, 3v3, Solo</p>
                        </div>

                        <!-- Hình thức thi đấu -->
                        <div class="form-group">
                            <label class="form-label">Hình thức thi đấu</label>
                            <div class="checkbox-group">
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="competition_formats[]" value="Online" class="checkbox-input">
                                        <span class="checkbox-label">Online</span>
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="competition_formats[]" value="Console" class="checkbox-input">
                                        <span class="checkbox-label">Console</span>
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="competition_formats[]" value="LAN" class="checkbox-input">
                                        <span class="checkbox-label">LAN</span>
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="competition_formats[]" value="PC" class="checkbox-input">
                                        <span class="checkbox-label">PC</span>
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="competition_formats[]" value="Mobile" class="checkbox-input">
                                        <span class="checkbox-label">Mobile</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Các chế độ phổ biến -->
                        <div class="form-group">
                            <label class="form-label">Các chế độ phổ biến</label>
                            <div class="checkbox-group">
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="game_modes[]" value="Ranked" class="checkbox-input">
                                        <span class="checkbox-label">Ranked</span>
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="game_modes[]" value="Custom Match" class="checkbox-input">
                                        <span class="checkbox-label">Custom Match</span>
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="game_modes[]" value="Tournament" class="checkbox-input">
                                        <span class="checkbox-label">Tournament</span>
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="game_modes[]" value="Casual" class="checkbox-input">
                                        <span class="checkbox-label">Casual</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media & Description Card -->
            <div class="form-card">
                <div class="form-card-header">
                    <div class="card-icon icon-warning">
                        <i class="fas fa-images"></i>
                    </div>
                    <h3 class="card-title">Media & Mô tả</h3>
                </div>
                <div class="form-card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Logo Upload -->
                        <div class="form-group">
                            <label class="form-label">Logo / Icon Game</label>
                            <div class="file-upload-zone">
                                <input type="file" id="logo" name="logo" accept="image/*">
                                <div class="file-upload-icon">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div class="file-upload-text">
                                    <strong>Nhấp để chọn logo</strong><br>
                                    <small>PNG/JPG, 256x256px, dưới 2MB</small>
                                </div>
                                <div id="logo-preview-container"></div>
                            </div>
                        </div>

                        <!-- Banner Upload -->
                        <div class="form-group">
                            <label class="form-label">Ảnh bìa (Banner)</label>
                            <div class="file-upload-zone">
                                <input type="file" id="banner" name="banner" accept="image/*">
                                <div class="file-upload-icon">
                                    <i class="fas fa-panorama"></i>
                                </div>
                                <div class="file-upload-text">
                                    <strong>Nhấp để chọn banner</strong><br>
                                    <small>PNG/JPG, 1920x1080px, dưới 5MB</small>
                                </div>
                                <div id="banner-preview-container"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label class="form-label">Mô tả ngắn</label>
                        <textarea name="description" class="form-textarea" rows="4"
                            placeholder="Tóm tắt về game, gameplay, đặc điểm nổi bật...">{{ old('description') }}</textarea>
                        <p class="form-hint">Tối đa 1000 ký tự</p>
                    </div>

                    <!-- Official Website -->
                    <div class="form-group">
                        <label class="form-label">Link chính thức</label>
                        <input type="url" name="official_website" class="form-input"
                            placeholder="https://example.com" value="{{ old('official_website') }}">
                        <p class="form-hint">Website chính thức, store page, wiki, etc.</p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="submit-section">
                <div class="flex justify-end gap-3 flex-wrap">
                    <a href="{{ route('admin.games.index') }}" class="btn-neon">
                        <i class="fas fa-times"></i>
                        <span>Hủy</span>
                    </a>
                    <button type="submit" class="btn-neon btn-neon-primary">
                        <i class="fas fa-save"></i>
                        <span>Lưu Game</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview function
    function createImagePreview(file, containerId) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';
        
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.createElement('div');
                wrapper.className = 'mt-4 text-center';
                
                const preview = document.createElement('img');
                preview.src = e.target.result;
                preview.className = 'image-preview inline-block';
                wrapper.appendChild(preview);
                
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'block mx-auto mt-2 px-3 py-1 text-sm bg-red-500/20 text-red-400 border border-red-500/30 rounded-lg hover:bg-red-500/30 transition-all';
                removeBtn.innerHTML = '<i class="fas fa-times mr-1"></i> Xóa';
                removeBtn.onclick = function() {
                    container.innerHTML = '';
                    document.getElementById(containerId.replace('-preview-container', '')).value = '';
                };
                wrapper.appendChild(removeBtn);
                
                container.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        }
    }

    // Logo upload handler
    document.getElementById('logo').addEventListener('change', function(e) {
        createImagePreview(e.target.files[0], 'logo-preview-container');
    });

    // Banner upload handler
    document.getElementById('banner').addEventListener('change', function(e) {
        createImagePreview(e.target.files[0], 'banner-preview-container');
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const nameInput = document.querySelector('input[name="name"]');
        if (!nameInput.value.trim()) {
            e.preventDefault();
            nameInput.focus();
            nameInput.style.borderColor = '#ef4444';
            nameInput.style.boxShadow = '0 0 15px rgba(239, 68, 68, 0.3)';
        }
    });

    // Reset border on focus
    document.querySelectorAll('.form-input, .form-select').forEach(function(input) {
        input.addEventListener('focus', function() {
            this.style.borderColor = '';
            this.style.boxShadow = '';
        });
    });
});
</script>
@endpush
