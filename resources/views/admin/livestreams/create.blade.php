@extends('layouts.app')

@section('title', 'Tạo Livestream')

@push('styles')
<style>
    .form-container { background: #000814; min-height: 100vh; padding-top: 80px; }
    .form-container .container { max-width: 800px; margin: 0 auto; padding: 1.5rem; }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #00E5FF;
        text-decoration: none;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }
    .back-link:hover { color: #FFFFFF; }

    .form-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        overflow: hidden;
    }

    .form-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
    }

    .form-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #ef4444;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-body { padding: 2rem; }

    .form-group { margin-bottom: 1.5rem; }

    .form-label {
        display: block;
        color: #e2e8f0;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-label .required { color: #ef4444; }

    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        color: #FFFFFF;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #00E5FF;
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.2);
    }

    .form-input::placeholder, .form-textarea::placeholder { color: #64748b; }
    .form-select option { background: #0d1b2a; color: #FFFFFF; }
    .form-textarea { min-height: 100px; resize: vertical; }

    .form-hint { color: #64748b; font-size: 0.8rem; margin-top: 0.4rem; }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 1rem;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        cursor: pointer;
    }

    .checkbox-group input[type="checkbox"] {
        width: 20px;
        height: 20px;
        accent-color: #00E5FF;
    }

    .checkbox-label { color: #e2e8f0; font-weight: 500; }

    .btn-submit {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-submit:hover {
        box-shadow: 0 0 30px rgba(239, 68, 68, 0.5);
        transform: translateY(-2px);
    }

    .error-text { color: #ef4444; font-size: 0.8rem; margin-top: 0.4rem; }

    .url-preview {
        margin-top: 1rem;
        padding: 1rem;
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        display: none;
    }

    .url-preview.show { display: block; }
    .url-preview-label { color: #00E5FF; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; }
    .url-preview-platform { color: #e2e8f0; display: flex; align-items: center; gap: 8px; }
    .url-preview-platform i.fa-youtube { color: #ef4444; }
    .url-preview-platform i.fa-facebook { color: #1877f2; }

    @media (max-width: 768px) {
        .form-row { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="container">
        <a href="{{ route('admin.livestreams.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>

        <div class="form-card">
            <div class="form-header">
                <h1 class="form-title">
                    <i class="fas fa-broadcast-tower"></i> Tạo Livestream mới
                </h1>
            </div>

            <div class="form-body">
                <form action="{{ route('admin.livestreams.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Tiêu đề <span class="required">*</span></label>
                        <input type="text" name="title" class="form-input" value="{{ old('title') }}" placeholder="VD: Giải đấu Valorant VCT 2026 - Vòng chung kết" required>
                        @error('title')<p class="error-text">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Link phát sóng <span class="required">*</span></label>
                        <input type="url" name="stream_url" id="streamUrl" class="form-input" value="{{ old('stream_url') }}" placeholder="https://youtube.com/watch?v=... hoặc https://facebook.com/.../videos/..." required>
                        <p class="form-hint">Hỗ trợ: YouTube (live, video), Facebook (live, video)</p>
                        @error('stream_url')<p class="error-text">{{ $message }}</p>@enderror

                        <div class="url-preview" id="urlPreview">
                            <div class="url-preview-label">Nền tảng phát hiện:</div>
                            <div class="url-preview-platform" id="urlPlatform"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-textarea" placeholder="Mô tả về livestream...">{{ old('description') }}</textarea>
                        @error('description')<p class="error-text">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Game</label>
                            <select name="game_id" class="form-select">
                                <option value="">-- Chọn game --</option>
                                @foreach($games as $game)
                                <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
                                    {{ $game->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Giải đấu</label>
                            <select name="tournament_id" class="form-select">
                                <option value="">-- Chọn giải đấu --</option>
                                @foreach($tournaments as $tournament)
                                <option value="{{ $tournament->id }}" {{ old('tournament_id') == $tournament->id ? 'selected' : '' }}>
                                    {{ $tournament->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Trạng thái <span class="required">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Đã lên lịch</option>
                                <option value="live" {{ old('status') == 'live' ? 'selected' : '' }}>Đang phát trực tiếp</option>
                                <option value="ended" {{ old('status') == 'ended' ? 'selected' : '' }}>Đã kết thúc</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Thời gian phát sóng</label>
                            <input type="datetime-local" name="scheduled_at" class="form-input" value="{{ old('scheduled_at') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ảnh thumbnail</label>
                        <input type="file" name="thumbnail" class="form-input" accept="image/*">
                        <p class="form-hint">Định dạng: JPG, PNG, GIF. Tối đa 2MB. Khuyến nghị: 1280x720</p>
                        @error('thumbnail')<p class="error-text">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="checkbox-group">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <span class="checkbox-label">Đánh dấu là nổi bật (hiển thị ở đầu trang)</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-broadcast-tower"></i> Tạo Livestream
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('streamUrl').addEventListener('input', function(e) {
        const url = e.target.value;
        const preview = document.getElementById('urlPreview');
        const platform = document.getElementById('urlPlatform');

        if (!url) {
            preview.classList.remove('show');
            return;
        }

        let detected = '';
        if (url.includes('youtube.com') || url.includes('youtu.be')) {
            detected = '<i class="fab fa-youtube"></i> YouTube';
        } else if (url.includes('facebook.com')) {
            detected = '<i class="fab fa-facebook"></i> Facebook';
        } else {
            detected = '<i class="fas fa-question-circle"></i> Không xác định';
        }

        platform.innerHTML = detected;
        preview.classList.add('show');
    });
</script>
@endpush
