@extends('layouts.app')

@section('title', 'Tạo đội mới')

@push('styles')
<style>
    .create-team-page {
        min-height: calc(100vh - 64px);
        background: linear-gradient(180deg, #000814 0%, #0d1b2a 100%);
        padding: 2rem;
        display: flex;
        justify-content: center;
    }
    
    .create-team-container {
        width: 100%;
        max-width: 700px;
    }
    
    .create-team-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .header-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.5rem;
        box-shadow: 0 8px 24px rgba(99, 102, 241, 0.3);
    }
    
    .header-text h1 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }
    
    .header-text p {
        color: #94a3b8;
        font-size: 0.9rem;
        margin: 0;
    }
    
    .create-team-card {
        background: linear-gradient(135deg, rgba(13, 27, 42, 0.95), rgba(0, 0, 34, 0.95));
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 20px;
        overflow: hidden;
    }
    
    .card-body-custom {
        padding: 2rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label-custom {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #94a3b8;
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .form-label-custom i {
        color: #00E5FF;
        font-size: 0.85rem;
    }
    
    .form-label-custom .required {
        color: #ef4444;
    }
    
    .form-input {
        width: 100%;
        box-sizing: border-box;
        background: rgba(0, 0, 20, 0.6);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 12px;
        padding: 0.875rem 1rem;
        color: #fff;
        font-size: 0.95rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #00E5FF;
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.15);
    }
    
    .form-input::placeholder {
        color: #64748b;
    }
    
    .form-input.is-invalid {
        border-color: #ef4444;
    }
    
    .form-textarea {
        resize: none;
        min-height: 120px;
    }
    
    .form-select-custom {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2300E5FF' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
    }
    
    .form-select-custom option {
        background: #0d1b2a;
        color: #fff;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .form-hint {
        color: #64748b;
        font-size: 0.8rem;
        margin-top: 0.5rem;
    }
    
    .invalid-feedback-custom {
        color: #ef4444;
        font-size: 0.8rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    
    /* File Upload */
    .file-upload-wrapper {
        position: relative;
    }
    
    .file-upload-area {
        border: 2px dashed rgba(0, 229, 255, 0.3);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: rgba(0, 0, 20, 0.4);
    }
    
    .file-upload-area:hover {
        border-color: #00E5FF;
        background: rgba(0, 229, 255, 0.05);
    }
    
    .file-upload-area.has-file {
        border-color: #22c55e;
        background: rgba(34, 197, 94, 0.05);
    }
    
    .file-upload-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, rgba(0, 229, 255, 0.1), rgba(139, 92, 246, 0.1));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .file-upload-icon i {
        font-size: 1.5rem;
        color: #00E5FF;
    }
    
    .file-upload-text {
        color: #94a3b8;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .file-upload-text strong {
        color: #00E5FF;
    }
    
    .file-upload-hint {
        color: #64748b;
        font-size: 0.8rem;
    }
    
    .file-upload-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    
    .file-preview {
        margin-top: 1rem;
        display: none;
    }
    
    .file-preview.show {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem;
        background: rgba(0, 229, 255, 0.05);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
    }
    
    .file-preview img {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
    }
    
    .file-preview-info {
        flex: 1;
    }
    
    .file-preview-name {
        color: #fff;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .file-preview-size {
        color: #64748b;
        font-size: 0.75rem;
    }
    
    .file-preview-remove {
        width: 28px;
        height: 28px;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 6px;
        color: #ef4444;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .file-preview-remove:hover {
        background: rgba(239, 68, 68, 0.2);
    }
    
    /* Buttons */
    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
    }
    
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        color: #94a3b8;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-back:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }
    
    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 2rem;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none;
        border-radius: 12px;
        color: #fff;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(99, 102, 241, 0.3);
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(99, 102, 241, 0.5);
    }
    
    @media (max-width: 768px) {
        .create-team-page {
            padding: 1rem;
        }
        
        .card-body-custom {
            padding: 1.5rem;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column-reverse;
            gap: 1rem;
        }
        
        .btn-back, .btn-submit {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="create-team-page">
    <div class="create-team-container">
        <div class="create-team-header">
            <div class="header-icon">
                <i class="fas fa-plus"></i>
            </div>
            <div class="header-text">
                <h1>Tạo đội mới</h1>
                <p>Xây dựng đội esports của riêng bạn</p>
            </div>
        </div>
        
        <div class="create-team-card">
            <div class="card-body-custom">
                <form action="{{ route('teams.store') }}" method="POST" enctype="multipart/form-data" id="createTeamForm">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label-custom">
                            <i class="fas fa-signature"></i>
                            Tên đội <span class="required">*</span>
                        </label>
                        <input type="text" 
                               class="form-input @error('name') is-invalid @enderror" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="Nhập tên đội của bạn..."
                               required>
                        @error('name')
                        <div class="invalid-feedback-custom">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label-custom">
                            <i class="fas fa-align-left"></i>
                            Mô tả
                        </label>
                        <textarea class="form-input form-textarea @error('description') is-invalid @enderror" 
                                  name="description" 
                                  placeholder="Giới thiệu về đội của bạn...">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback-custom">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label-custom">
                                <i class="fas fa-gamepad"></i>
                                Game
                            </label>
                            <select class="form-input form-select-custom @error('game_id') is-invalid @enderror" name="game_id">
                                <option value="">-- Chọn game --</option>
                                @foreach($games as $game)
                                <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
                                    {{ $game->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('game_id')
                            <div class="invalid-feedback-custom">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label-custom">
                                <i class="fas fa-users"></i>
                                Số thành viên tối đa
                            </label>
                            <input type="number" 
                                   class="form-input @error('max_members') is-invalid @enderror" 
                                   name="max_members" 
                                   value="{{ old('max_members', 10) }}"
                                   min="2" 
                                   max="20"
                                   placeholder="10">
                            <div class="form-hint">Từ 2 đến 20 thành viên</div>
                            @error('max_members')
                            <div class="invalid-feedback-custom">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label-custom">
                            <i class="fas fa-image"></i>
                            Logo đội
                        </label>
                        <div class="file-upload-wrapper">
                            <div class="file-upload-area" id="uploadArea">
                                <div class="file-upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="file-upload-text">
                                    <strong>Click để chọn</strong> hoặc kéo thả file vào đây
                                </div>
                                <div class="file-upload-hint">JPG, PNG, GIF - Tối đa 2MB</div>
                                <input type="file" 
                                       class="file-upload-input" 
                                       name="logo" 
                                       id="logoInput"
                                       accept="image/*">
                            </div>
                            <div class="file-preview" id="filePreview">
                                <img src="" alt="Preview" id="previewImg">
                                <div class="file-preview-info">
                                    <div class="file-preview-name" id="fileName"></div>
                                    <div class="file-preview-size" id="fileSize"></div>
                                </div>
                                <button type="button" class="file-preview-remove" id="removeFile">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        @error('logo')
                        <div class="invalid-feedback-custom">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>
                    
                    <div class="form-actions">
                        <a href="{{ route('teams.index') }}" class="btn-back">
                            <i class="fas fa-arrow-left"></i>
                            Quay lại
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-plus"></i>
                            Tạo đội
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const logoInput = document.getElementById('logoInput');
    const uploadArea = document.getElementById('uploadArea');
    const filePreview = document.getElementById('filePreview');
    const previewImg = document.getElementById('previewImg');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeFile = document.getElementById('removeFile');
    
    logoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                filePreview.classList.add('show');
                uploadArea.classList.add('has-file');
            };
            reader.readAsDataURL(file);
        }
    });
    
    removeFile.addEventListener('click', function() {
        logoInput.value = '';
        filePreview.classList.remove('show');
        uploadArea.classList.remove('has-file');
        previewImg.src = '';
    });
    
    // Drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = '#00E5FF';
        this.style.background = 'rgba(0, 229, 255, 0.1)';
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.style.borderColor = '';
        this.style.background = '';
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = '';
        this.style.background = '';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            logoInput.files = files;
            logoInput.dispatchEvent(new Event('change'));
        }
    });
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
});
</script>
@endpush
