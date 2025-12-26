@extends('layouts.app')

@section('title', 'Thêm Game Mới')

@push('styles')
<style>
    /* Modern Create Game Styles */
    .game-create-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .game-create-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="%23ffffff06" points="0,1000 1000,0 1000,1000"/></svg>');
        pointer-events: none;
    }

    .modern-create-header {
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

    .modern-create-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
        background-size: 300% 100%;
        animation: gradientShift 3s ease infinite;
    }

    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .modern-create-title {
        font-weight: 700;
        font-size: 2rem;
        color: #2d3748;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .modern-action-btn {
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

    .action-back {
        background: #f7fafc;
        color: #4a5568;
        border-color: #e2e8f0;
    }

    .action-back:hover {
        background: #edf2f7;
        color: #2d3748;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
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

    .modern-card-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(226, 232, 240, 0.5);
    }

    .card-icon {
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

    .card-title {
        font-weight: 700;
        font-size: 1.25rem;
        color: #2d3748;
        margin: 0;
    }

    .modern-form-control {
        padding: 0.875rem 1.25rem;
        border: 2px solid rgba(226, 232, 240, 0.8);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.9);
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .modern-form-control:focus {
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        background: white;
        outline: none;
    }

    .modern-form-label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .modern-checkbox {
        transform: scale(1.2);
        accent-color: #4299e1;
    }

    .checkbox-group {
        background: rgba(247, 250, 252, 0.8);
        border-radius: 12px;
        padding: 1rem;
        border: 1px solid rgba(226, 232, 240, 0.5);
    }

    .checkbox-item {
        padding: 0.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        margin-bottom: 0.5rem;
    }

    .checkbox-item:hover {
        background: rgba(66, 153, 225, 0.1);
    }

    .modern-alert {
        background: rgba(245, 101, 101, 0.1);
        border: 1px solid rgba(245, 101, 101, 0.3);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        color: #e53e3e;
    }

    .modern-alert h6 {
        color: #c53030;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .modern-submit-group {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        margin-top: 2rem;
        animation: fadeInUp 0.8s ease-out;
        animation-delay: 0.4s;
        animation-fill-mode: both;
    }

    .modern-btn-primary {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        border: none;
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modern-btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
        color: white;
    }

    .modern-btn-secondary {
        background: #f7fafc;
        border: 2px solid #e2e8f0;
        color: #4a5568;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .modern-btn-secondary:hover {
        background: #edf2f7;
        color: #2d3748;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .file-upload-zone {
        border: 2px dashed rgba(66, 153, 225, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        background: rgba(66, 153, 225, 0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .file-upload-zone:hover {
        border-color: rgba(66, 153, 225, 0.5);
        background: rgba(66, 153, 225, 0.1);
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
        color: #4299e1;
        margin-bottom: 0.5rem;
    }

    .image-preview {
        max-width: 150px;
        max-height: 150px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-top: 1rem;
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .game-create-container {
            padding: 1rem 0;
        }

        .modern-create-header {
            padding: 1.5rem;
            margin: 0 1rem 1.5rem;
        }

        .modern-create-title {
            font-size: 1.5rem;
        }

        .modern-form-card {
            margin: 0 1rem 1.5rem;
        }

        .modern-submit-group {
            margin: 1.5rem 1rem 0;
        }
    }
</style>
@endpush

@section('content')
<div class="game-create-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Modern Header -->
                <div class="modern-create-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <h1 class="modern-create-title">
                            <i class="fas fa-plus-circle text-primary"></i>
                            Thêm Game Mới
                        </h1>
                        <a href="{{ route('admin.games.index') }}" class="modern-action-btn action-back">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </div>
                <!-- Error Alert -->
                @if ($errors->any())
                <div class="modern-alert">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Vui lòng kiểm tra lại:</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Left Column: Basic Information -->
                        <div class="col-md-6">
                            <div class="modern-form-card">
                                <div class="modern-card-header">
                                    <div class="card-icon icon-primary">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <h3 class="card-title">Thông tin cơ bản</h3>
                                </div>
                                
                                <!-- Tên Game -->
                                <div class="mb-3">
                                    <label for="name" class="modern-form-label">
                                        Tên Game <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control modern-form-control" id="name" name="name"
                                        value="{{ old('name') }}" required placeholder="Nhập tên game">
                                </div>

                                <!-- Thể loại -->
                                <div class="mb-3">
                                    <label for="genre" class="modern-form-label">Thể loại</label>
                                    <select class="form-select modern-form-control" id="genre" name="genre">
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
                                <div class="mb-3">
                                    <label for="publisher" class="modern-form-label">Nhà phát hành / Studio</label>
                                    <input type="text" class="form-control modern-form-control" id="publisher" name="publisher"
                                        value="{{ old('publisher') }}" placeholder="Ví dụ: Riot Games">
                                </div>

                                <!-- Ngày phát hành -->
                                <div class="mb-3">
                                    <label for="release_date" class="modern-form-label">Ngày phát hành</label>
                                    <input type="date" class="form-control modern-form-control" id="release_date" name="release_date"
                                        value="{{ old('release_date') }}">
                                </div>

                                <!-- Tình trạng -->
                                <div class="mb-3">
                                    <label for="status" class="modern-form-label">
                                        Tình trạng <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select modern-form-control" id="status" name="status" required>
                                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                                        <option value="discontinued" {{ old('status') === 'discontinued' ? 'selected' : '' }}>Ngừng phát triển</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Esports Information -->
                        <div class="col-md-6">
                            <div class="modern-form-card">
                                <div class="modern-card-header">
                                    <div class="card-icon icon-success">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <h3 class="card-title">Thông tin Esport</h3>
                                </div>

                                <!-- Hỗ trợ Esport -->
                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input modern-checkbox" type="checkbox" id="esport_support"
                                            name="esport_support" value="1" {{ old('esport_support') ? 'checked' : '' }}>
                                        <label class="form-check-label modern-form-label" for="esport_support">
                                            <strong>Hỗ trợ thi đấu Esport</strong>
                                        </label>
                                    </div>
                                </div>

                                <!-- Số lượng người chơi mỗi đội -->
                                <div class="mb-3">
                                    <label for="team_size" class="modern-form-label">Số lượng người chơi mỗi đội</label>
                                    <input type="text" class="form-control modern-form-control" id="team_size" name="team_size"
                                        placeholder="VD: 5v5, 1v1, 3v3" value="{{ old('team_size') }}">
                                    <div class="form-text text-muted mt-1">Ví dụ: 5v5, 1v1, 3v3, Solo</div>
                                </div>

                                <!-- Hình thức thi đấu -->
                                <div class="mb-3">
                                    <label class="modern-form-label">Hình thức thi đấu</label>
                                    <div class="checkbox-group">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="checkbox-item">
                                                    <input class="form-check-input modern-checkbox" type="checkbox" name="competition_formats[]"
                                                        value="Online" id="format_online">
                                                    <label class="form-check-label" for="format_online">Online</label>
                                                </div>
                                                <div class="checkbox-item">
                                                    <input class="form-check-input modern-checkbox" type="checkbox" name="competition_formats[]"
                                                        value="LAN" id="format_lan">
                                                    <label class="form-check-label" for="format_lan">LAN</label>
                                                </div>
                                                <div class="checkbox-item">
                                                    <input class="form-check-input modern-checkbox" type="checkbox" name="competition_formats[]"
                                                        value="Mobile" id="format_mobile">
                                                    <label class="form-check-label" for="format_mobile">Mobile</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="checkbox-item">
                                                    <input class="form-check-input modern-checkbox" type="checkbox" name="competition_formats[]"
                                                        value="Console" id="format_console">
                                                    <label class="form-check-label" for="format_console">Console</label>
                                                </div>
                                                <div class="checkbox-item">
                                                    <input class="form-check-input modern-checkbox" type="checkbox" name="competition_formats[]"
                                                        value="PC" id="format_pc">
                                                    <label class="form-check-label" for="format_pc">PC</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Các chế độ phổ biến -->
                                <div class="mb-3">
                                    <label class="modern-form-label">Các chế độ phổ biến</label>
                                    <div class="checkbox-group">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="checkbox-item">
                                                    <input class="form-check-input modern-checkbox" type="checkbox" name="game_modes[]"
                                                        value="Ranked" id="mode_ranked">
                                                    <label class="form-check-label" for="mode_ranked">Ranked</label>
                                                </div>
                                                <div class="checkbox-item">
                                                    <input class="form-check-input modern-checkbox" type="checkbox" name="game_modes[]"
                                                        value="Tournament" id="mode_tournament">
                                                    <label class="form-check-label" for="mode_tournament">Tournament</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="checkbox-item">
                                                    <input class="form-check-input modern-checkbox" type="checkbox" name="game_modes[]"
                                                        value="Custom Match" id="mode_custom">
                                                    <label class="form-check-label" for="mode_custom">Custom Match</label>
                                                </div>
                                                <div class="checkbox-item">
                                                    <input class="form-check-input modern-checkbox" type="checkbox" name="game_modes[]"
                                                        value="Casual" id="mode_casual">
                                                    <label class="form-check-label" for="mode_casual">Casual</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Media & Description Card -->
                    <div class="modern-form-card">
                        <div class="modern-card-header">
                            <div class="card-icon icon-warning">
                                <i class="fas fa-images"></i>
                            </div>
                            <h3 class="card-title">Media & Mô tả</h3>
                        </div>

                        <div class="row">
                            <!-- Logo Upload -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="modern-form-label">Logo / Icon Game</label>
                                    <div class="file-upload-zone">
                                        <input type="file" id="logo" name="logo" accept="image/*">
                                        <div class="file-upload-icon">
                                            <i class="fas fa-image"></i>
                                        </div>
                                        <div class="text-muted">
                                            <strong>Nhấp để chọn logo</strong><br>
                                            <small>PNG/JPG, 256x256px, dưới 2MB</small>
                                        </div>
                                        <div id="logo-preview-container"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Banner Upload -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="modern-form-label">Ảnh bìa (Banner)</label>
                                    <div class="file-upload-zone">
                                        <input type="file" id="banner" name="banner" accept="image/*">
                                        <div class="file-upload-icon">
                                            <i class="fas fa-panorama"></i>
                                        </div>
                                        <div class="text-muted">
                                            <strong>Nhấp để chọn banner</strong><br>
                                            <small>PNG/JPG, 1920x1080px, dưới 5MB</small>
                                        </div>
                                        <div id="banner-preview-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="modern-form-label">Mô tả ngắn</label>
                            <textarea class="form-control modern-form-control" id="description" name="description" rows="4"
                                placeholder="Tóm tắt về game, gameplay, đặc điểm nổi bật...">{{ old('description') }}</textarea>
                            <div class="form-text text-muted mt-1">Tối đa 1000 ký tự</div>
                        </div>

                        <!-- Official Website -->
                        <div class="mb-3">
                            <label for="official_website" class="modern-form-label">Link chính thức</label>
                            <input type="url" class="form-control modern-form-control" id="official_website" name="official_website"
                                placeholder="https://example.com" value="{{ old('official_website') }}">
                            <div class="form-text text-muted mt-1">Website chính thức, store page, wiki, etc.</div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="modern-submit-group">
                        <div class="d-flex justify-content-end gap-3 flex-wrap">
                            <a href="{{ route('admin.games.index') }}" class="modern-btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="modern-btn-primary">
                                <i class="fas fa-save me-2"></i>Lưu Game
                            </button>
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
    // Enhanced image preview with modern styling
    function createImagePreview(file, containerId) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';
        
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('img');
                preview.src = e.target.result;
                preview.className = 'image-preview';
                preview.style.cssText = 'max-width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-top: 1rem; animation: fadeIn 0.3s ease;';
                container.appendChild(preview);
                
                // Add remove button
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-danger mt-2';
                removeBtn.innerHTML = '<i class="fas fa-times"></i> Xóa';
                removeBtn.onclick = function() {
                    container.innerHTML = '';
                    document.getElementById(containerId.replace('-preview-container', '')).value = '';
                };
                container.appendChild(removeBtn);
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

    // Form validation enhancement
    $('form').on('submit', function(e) {
        let isValid = true;
        const requiredFields = ['name', 'status'];
        
        requiredFields.forEach(function(field) {
            const input = $(`#${field}`);
            if (!input.val().trim()) {
                input.addClass('is-invalid');
                isValid = false;
            } else {
                input.removeClass('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('.is-invalid').first().offset().top - 100
            }, 500);
        }
    });

    // Enhanced focus effects
    $('.modern-form-control').on('focus', function() {
        $(this).parent().addClass('focused');
    }).on('blur', function() {
        $(this).parent().removeClass('focused');
    });

    // Auto-resize textarea
    $('#description').on('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });

    // Character counter for description
    const maxChars = 1000;
    $('#description').on('input', function() {
        const currentLength = $(this).val().length;
        const remaining = maxChars - currentLength;
        
        let counterClass = 'text-muted';
        if (remaining < 100) counterClass = 'text-warning';
        if (remaining < 0) counterClass = 'text-danger';
        
        const counter = $(this).siblings('.form-text');
        counter.html(`<span class="${counterClass}">${remaining} ký tự còn lại</span>`);
    });
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .focused {
        transform: scale(1.01);
        transition: transform 0.2s ease;
    }
`;
document.head.appendChild(style);
</script>
@endpush