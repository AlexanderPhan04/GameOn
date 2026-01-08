@extends('layouts.app')

@section('title', 'Chỉnh sửa Game: ' . $game->name)

@push('styles')
<style>
    .game-edit-container {
        background: #000814;
        min-height: 100vh;
        padding: 1.5rem;
    }
    .game-edit-container * {
        box-sizing: border-box;
    }

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
    .hero-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .hero-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .hero-icon {
        width: 60px; height: 60px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
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
    .hero-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    /* Buttons */
    .btn-neon {
        background: linear-gradient(135deg, #000055, #000077);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.4);
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-size: 0.85rem;
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
    .btn-neon-primary:hover {
        box-shadow: 0 0 20px rgba(139, 92, 246, 0.4);
        color: #FFFFFF;
    }
    .btn-neon-success {
        background: linear-gradient(135deg, #059669, #10b981);
        border-color: rgba(16, 185, 129, 0.4);
        color: #FFFFFF;
    }
    .btn-neon-success:hover {
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
        color: #FFFFFF;
    }

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
    .card-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: white;
    }
    .icon-cyan { background: linear-gradient(135deg, #06b6d4, #0891b2); }
    .icon-purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .icon-orange { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .icon-green { background: linear-gradient(135deg, #10b981, #059669); }
    .form-card-header h3 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: #FFFFFF;
        margin: 0;
    }
    .form-card-body {
        padding: 1.5rem;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 1.25rem;
    }
    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        color: #94a3b8;
        margin-bottom: 0.5rem;
    }
    .form-label i {
        color: #00E5FF;
        width: 16px;
    }
    .form-input {
        width: 100%;
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
    .form-input::placeholder {
        color: #64748b;
    }
    .form-input.is-invalid {
        border-color: #ef4444;
    }
    textarea.form-input {
        min-height: 100px;
        resize: vertical;
    }
    select.form-input {
        cursor: pointer;
    }

    /* File Upload */
    .file-upload-zone {
        border: 2px dashed rgba(0, 229, 255, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: rgba(0, 0, 20, 0.4);
    }
    .file-upload-zone:hover {
        border-color: #00E5FF;
        background: rgba(0, 229, 255, 0.05);
    }
    .file-upload-zone i {
        font-size: 2rem;
        color: #00E5FF;
        margin-bottom: 0.5rem;
    }
    .file-upload-zone p {
        color: #94a3b8;
        margin: 0.25rem 0;
        font-size: 0.85rem;
    }
    .file-upload-zone input[type="file"] {
        display: none;
    }
    .file-preview {
        margin-top: 1rem;
        padding: 0.75rem;
        background: rgba(0, 229, 255, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .file-preview img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid rgba(0, 229, 255, 0.3);
    }
    .file-preview-info {
        flex: 1;
    }
    .file-preview-info strong {
        color: #FFFFFF;
        font-size: 0.9rem;
    }
    .file-preview-info small {
        color: #64748b;
        display: block;
    }

    /* Checkbox */
    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(0, 0, 20, 0.4);
        padding: 0.6rem 1rem;
        border-radius: 8px;
        border: 1px solid rgba(0, 229, 255, 0.1);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .checkbox-item:hover {
        border-color: rgba(0, 229, 255, 0.3);
        background: rgba(0, 229, 255, 0.05);
    }
    .checkbox-item.checked {
        border-color: #00E5FF;
        background: rgba(0, 229, 255, 0.1);
    }
    .checkbox-item input[type="checkbox"] {
        accent-color: #00E5FF;
        width: 16px;
        height: 16px;
    }
    .checkbox-item span {
        color: #FFFFFF;
        font-size: 0.85rem;
    }

    /* Error Alert */
    .error-alert {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }
    .error-alert h6 {
        color: #ef4444;
        font-weight: 600;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .error-alert ul {
        margin: 0;
        padding-left: 1.25rem;
        color: #fca5a5;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
        padding: 1.5rem;
        background: linear-gradient(145deg, #0d1b2a, #000022);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        margin-top: 1.5rem;
    }

    /* Grid */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    .form-grid .form-group {
        margin-bottom: 0;
    }
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .hero-content {
            flex-direction: column;
            align-items: flex-start;
        }
        .form-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="game-edit-container">
    <div class="max-w-5xl mx-auto">
        <!-- Hero Section -->
        <div class="edit-hero">
            <div class="hero-content">
                <div class="hero-left">
                    <div class="hero-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <h1 class="hero-title">Chỉnh sửa Game</h1>
                        <p class="hero-subtitle">{{ $game->name }}</p>
                    </div>
                </div>
                <div class="hero-actions">
                    <a href="{{ route('admin.games.show', $game->id) }}" class="btn-neon btn-neon-primary">
                        <i class="fas fa-eye"></i> Xem chi tiết
                    </a>
                    <a href="{{ route('admin.games.index') }}" class="btn-neon">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
        <div class="error-alert">
            <h6><i class="fas fa-exclamation-triangle"></i> Vui lòng kiểm tra lại:</h6>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.games.update', $game->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div>
                    <!-- Basic Info -->
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="card-icon icon-cyan">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <h3>Thông tin cơ bản</h3>
                        </div>
                        <div class="form-card-body">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-gamepad"></i> Tên Game *
                                </label>
                                <input type="text" name="name" class="form-input @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $game->name) }}" required placeholder="Nhập tên game...">
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-tags"></i> Thể loại
                                    </label>
                                    <input type="text" name="genre" class="form-input" 
                                           value="{{ old('genre', $game->genre) }}" placeholder="VD: MOBA, FPS...">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-building"></i> Nhà phát hành
                                    </label>
                                    <input type="text" name="publisher" class="form-input" 
                                           value="{{ old('publisher', $game->publisher) }}" placeholder="Nhập nhà phát hành...">
                                </div>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-calendar"></i> Ngày phát hành
                                    </label>
                                    <input type="date" name="release_date" class="form-input" 
                                           value="{{ old('release_date', $game->release_date?->format('Y-m-d')) }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-toggle-on"></i> Trạng thái *
                                    </label>
                                    <select name="status" class="form-input" required>
                                        <option value="active" {{ old('status', $game->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="discontinued" {{ old('status', $game->status) == 'discontinued' ? 'selected' : '' }}>Ngừng hoạt động</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-globe"></i> Website chính thức
                                </label>
                                <input type="url" name="official_website" class="form-input" 
                                       value="{{ old('official_website', $game->official_website) }}" placeholder="https://...">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-align-left"></i> Mô tả
                                </label>
                                <textarea name="description" class="form-input" rows="4" 
                                          placeholder="Mô tả về game...">{{ old('description', $game->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <!-- Images -->
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="card-icon icon-orange">
                                <i class="fas fa-images"></i>
                            </div>
                            <h3>Hình ảnh</h3>
                        </div>
                        <div class="form-card-body">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-image"></i> Logo Game
                                </label>
                                <div class="file-upload-zone" onclick="document.getElementById('logo').click()">
                                    <input type="file" id="logo" name="logo" accept="image/*">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p><strong>Chọn logo game</strong></p>
                                    <p>PNG, JPG, GIF - Tối đa 2MB</p>
                                </div>
                                @if($game->logo)
                                <div class="file-preview">
                                    <img src="{{ $game->logo }}" alt="Logo">
                                    <div class="file-preview-info">
                                        <strong>Logo hiện tại</strong>
                                        <small>Chọn file mới để thay đổi</small>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-panorama"></i> Banner Game
                                </label>
                                <div class="file-upload-zone" onclick="document.getElementById('banner').click()">
                                    <input type="file" id="banner" name="banner" accept="image/*">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p><strong>Chọn banner game</strong></p>
                                    <p>PNG, JPG, GIF - Tối đa 5MB - Tỷ lệ 16:9</p>
                                </div>
                                @if($game->banner)
                                <div class="file-preview">
                                    <img src="{{ $game->banner }}" alt="Banner">
                                    <div class="file-preview-info">
                                        <strong>Banner hiện tại</strong>
                                        <small>Chọn file mới để thay đổi</small>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Esports -->
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="card-icon icon-green">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h3>Thông tin Esports</h3>
                        </div>
                        <div class="form-card-body">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-medal"></i> Hỗ trợ Esports
                                </label>
                                <div class="checkbox-group">
                                    <label class="checkbox-item {{ old('esport_support', $game->is_esport_supported) ? 'checked' : '' }}">
                                        <input type="hidden" name="esport_support" value="0">
                                        <input type="checkbox" name="esport_support" value="1" 
                                               {{ old('esport_support', $game->is_esport_supported) ? 'checked' : '' }}
                                               onchange="this.parentElement.classList.toggle('checked', this.checked)">
                                        <span>Game hỗ trợ thi đấu Esports</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-users"></i> Kích thước đội
                                </label>
                                <input type="text" name="team_size" class="form-input" 
                                       value="{{ old('team_size', $game->format_metadata['team_size'] ?? '') }}" 
                                       placeholder="VD: 5v5, 1v1, 3v3...">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-gamepad"></i> Chế độ chơi
                                </label>
                                @php
                                    $modes = ['Ranked', 'Tournament', 'Custom', 'Training', 'Casual'];
                                    $selectedModes = old('game_modes', $game->format_metadata['game_modes'] ?? []);
                                @endphp
                                <div class="checkbox-group">
                                    @foreach($modes as $mode)
                                    <label class="checkbox-item {{ in_array($mode, $selectedModes) ? 'checked' : '' }}">
                                        <input type="checkbox" name="game_modes[]" value="{{ $mode }}"
                                               {{ in_array($mode, $selectedModes) ? 'checked' : '' }}
                                               onchange="this.parentElement.classList.toggle('checked', this.checked)">
                                        <span>{{ $mode }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-list"></i> Định dạng thi đấu
                                </label>
                                @php
                                    $formats = ['Single Elimination', 'Double Elimination', 'Round Robin', 'Swiss', 'League'];
                                    $selectedFormats = old('competition_formats', $game->format_metadata['competition_formats'] ?? []);
                                @endphp
                                <div class="checkbox-group">
                                    @foreach($formats as $format)
                                    <label class="checkbox-item {{ in_array($format, $selectedFormats) ? 'checked' : '' }}">
                                        <input type="checkbox" name="competition_formats[]" value="{{ $format }}"
                                               {{ in_array($format, $selectedFormats) ? 'checked' : '' }}
                                               onchange="this.parentElement.classList.toggle('checked', this.checked)">
                                        <span>{{ $format }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-neon btn-neon-success">
                    <i class="fas fa-save"></i> Lưu thay đổi
                </button>
                <a href="{{ route('admin.games.index') }}" class="btn-neon">
                    <i class="fas fa-times"></i> Hủy bỏ
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // File upload preview
    document.getElementById('logo').addEventListener('change', function(e) {
        previewFile(this, 'logo');
    });
    
    document.getElementById('banner').addEventListener('change', function(e) {
        previewFile(this, 'banner');
    });

    function previewFile(input, type) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const zone = input.closest('.file-upload-zone');
                let preview = zone.nextElementSibling;
                
                if (!preview || !preview.classList.contains('file-preview')) {
                    preview = document.createElement('div');
                    preview.className = 'file-preview';
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <div class="file-preview-info">
                            <strong>File mới được chọn</strong>
                            <small>${input.files[0].name}</small>
                        </div>
                    `;
                    zone.parentNode.appendChild(preview);
                } else {
                    preview.querySelector('img').src = e.target.result;
                    preview.querySelector('strong').textContent = 'File mới được chọn';
                    preview.querySelector('small').textContent = input.files[0].name;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
