@extends('layouts.app')

@section('title', 'Đăng ký Pro Gamer')

@push('styles')
<style>
    .verification-container { background: #000814; min-height: 100vh; padding: 2rem 0; }

    .verification-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        overflow: hidden;
        max-width: 700px;
        margin: 0 auto;
    }

    .card-header-custom {
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, rgba(0, 229, 255, 0.1), rgba(102, 126, 234, 0.1));
        border-bottom: 1px solid rgba(0, 229, 255, 0.15);
    }

    .card-header-custom h1 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #00E5FF;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header-custom p {
        color: #94a3b8;
        margin: 0.5rem 0 0 0;
        font-size: 0.9rem;
    }

    .card-body-custom { padding: 2rem; }

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
        width: 100% !important;
        padding: 0.75rem 1rem !important;
        background: rgba(0, 0, 0, 0.3) !important;
        border: 1px solid rgba(0, 229, 255, 0.2) !important;
        border-radius: 10px !important;
        color: #FFFFFF !important;
        font-size: 0.95rem !important;
        transition: all 0.3s ease;
        box-sizing: border-box !important;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none !important;
        border-color: #00E5FF !important;
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.2) !important;
        background: rgba(0, 0, 0, 0.4) !important;
    }

    .form-input::placeholder, .form-textarea::placeholder { color: #64748b !important; }
    .form-select option { background: #0d1b2a !important; color: #FFFFFF !important; }
    .form-textarea { min-height: 100px !important; resize: vertical !important; }

    .form-hint {
        color: #64748b;
        font-size: 0.8rem;
        margin-top: 0.4rem;
    }

    .file-upload {
        border: 2px dashed rgba(0, 229, 255, 0.4) !important;
        border-radius: 10px !important;
        padding: 2rem !important;
        text-align: center !important;
        cursor: pointer !important;
        transition: all 0.3s ease;
        background: rgba(0, 0, 0, 0.2) !important;
        display: block !important;
    }

    .file-upload:hover {
        border-color: #00E5FF !important;
        background: rgba(0, 229, 255, 0.1) !important;
    }

    .file-upload i { font-size: 2rem !important; color: #00E5FF !important; margin-bottom: 0.5rem !important; display: block !important; }
    .file-upload p { color: #94a3b8 !important; margin: 0 !important; font-size: 0.9rem !important; }
    .file-upload input { display: none !important; }

    .btn-submit {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #00E5FF, #0099cc);
        border: none;
        border-radius: 12px;
        color: #000;
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
        box-shadow: 0 0 30px rgba(0, 229, 255, 0.5);
        transform: translateY(-2px);
    }

    .alert-rejected {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .alert-rejected h4 {
        color: #ef4444;
        font-size: 0.95rem;
        font-weight: 600;
        margin: 0 0 0.5rem 0;
    }

    .alert-rejected p {
        color: #fca5a5;
        font-size: 0.85rem;
        margin: 0;
    }

    .info-box {
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .info-box h4 {
        color: #00E5FF;
        font-size: 0.95rem;
        font-weight: 600;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-box ul {
        color: #94a3b8;
        font-size: 0.85rem;
        margin: 0;
        padding-left: 1.25rem;
    }

    .info-box li { margin-bottom: 0.25rem; }

    .error-text { color: #ef4444; font-size: 0.8rem; margin-top: 0.4rem; }
</style>
@endpush

@section('content')
<div class="verification-container">
    <div class="container">
        <div class="verification-card">
            <div class="card-header-custom">
                <h1><i class="fas fa-certificate"></i> Đăng ký Pro Gamer</h1>
                <p>Xác minh tài khoản để nhận huy hiệu Pro Gamer và các đặc quyền đặc biệt</p>
            </div>

            <div class="card-body-custom">
                @if($lastRequest && $lastRequest->isRejected())
                <div class="alert-rejected">
                    <h4><i class="fas fa-exclamation-circle"></i> Yêu cầu trước đã bị từ chối</h4>
                    <p><strong>Lý do:</strong> {{ $lastRequest->admin_note ?? 'Không có lý do cụ thể' }}</p>
                </div>
                @endif

                <div class="info-box">
                    <h4><i class="fas fa-info-circle"></i> Yêu cầu để được xác minh</h4>
                    <ul>
                        <li>Có rank/tier cao trong game (Diamond+, Immortal+, Master+, etc.)</li>
                        <li>Có thành tích thi đấu (giải đấu, tournament)</li>
                        <li>Cung cấp bằng chứng xác thực (screenshot, link profile)</li>
                    </ul>
                </div>

                <form action="{{ route('verification.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Game chính <span class="required">*</span></label>
                        <select name="game_name" class="form-select" required style="width: 100%; padding: 0.75rem 1rem; background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(0, 229, 255, 0.3); border-radius: 10px; color: #FFFFFF; font-size: 0.95rem;">
                            <option value="">-- Chọn game --</option>
                            @foreach($games as $game)
                            <option value="{{ $game->name }}" {{ old('game_name') == $game->name ? 'selected' : '' }}>
                                {{ $game->name }}
                            </option>
                            @endforeach
                            <option value="other" {{ old('game_name') == 'other' ? 'selected' : '' }}>Khác</option>
                        </select>
                        @error('game_name')<p class="error-text">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tên trong game (IGN) <span class="required">*</span></label>
                        <input type="text" name="in_game_name" class="form-input" value="{{ old('in_game_name') }}" placeholder="VD: ProPlayer#1234" required style="width: 100%; padding: 0.75rem 1rem; background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(0, 229, 255, 0.3); border-radius: 10px; color: #FFFFFF; font-size: 0.95rem;">
                        @error('in_game_name')<p class="error-text">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">ID trong game</label>
                        <input type="text" name="in_game_id" class="form-input" value="{{ old('in_game_id') }}" placeholder="VD: 123456789" style="width: 100%; padding: 0.75rem 1rem; background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(0, 229, 255, 0.3); border-radius: 10px; color: #FFFFFF; font-size: 0.95rem;">
                        <p class="form-hint">ID số hoặc mã định danh trong game (nếu có)</p>
                        @error('in_game_id')<p class="error-text">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rank/Tier hiện tại</label>
                        <input type="text" name="rank_tier" class="form-input" value="{{ old('rank_tier') }}" placeholder="VD: Immortal 2, Challenger, Radiant" style="width: 100%; padding: 0.75rem 1rem; background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(0, 229, 255, 0.3); border-radius: 10px; color: #FFFFFF; font-size: 0.95rem;">
                        @error('rank_tier')<p class="error-text">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Thành tích</label>
                        <textarea name="achievements" class="form-textarea" placeholder="Liệt kê các thành tích của bạn:&#10;- Top 100 Radiant Season X&#10;- Vô địch giải ABC 2024&#10;- MVP giải XYZ" style="width: 100%; padding: 0.75rem 1rem; background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(0, 229, 255, 0.3); border-radius: 10px; color: #FFFFFF; font-size: 0.95rem; min-height: 100px;">{{ old('achievements') }}</textarea>
                        @error('achievements')<p class="error-text">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Link chứng minh</label>
                        <textarea name="proof_links" class="form-textarea" placeholder="Dán các link profile game, video highlight, bài báo:&#10;https://tracker.gg/valorant/profile/...&#10;https://youtube.com/..." style="width: 100%; padding: 0.75rem 1rem; background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(0, 229, 255, 0.3); border-radius: 10px; color: #FFFFFF; font-size: 0.95rem; min-height: 100px;">{{ old('proof_links') }}</textarea>
                        <p class="form-hint">Mỗi link một dòng</p>
                        @error('proof_links')<p class="error-text">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ảnh chứng minh</label>
                        <label class="file-upload" id="fileUploadLabel" style="display: block; border: 2px dashed rgba(0, 229, 255, 0.4); border-radius: 10px; padding: 2rem; text-align: center; cursor: pointer; background: rgba(0, 0, 0, 0.2);">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #00E5FF; display: block; margin-bottom: 0.5rem;"></i>
                            <p id="fileUploadText" style="color: #94a3b8; margin: 0; font-size: 0.9rem;">Click để tải lên ảnh screenshot rank, profile...</p>
                            <input type="file" name="proof_image" id="proofImage" accept="image/*" style="display: none;">
                        </label>
                        <p class="form-hint">Định dạng: JPG, PNG, GIF. Tối đa 5MB</p>
                        @error('proof_image')<p class="error-text">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Thông tin bổ sung</label>
                        <textarea name="additional_info" class="form-textarea" placeholder="Bất kỳ thông tin nào khác bạn muốn chia sẻ..." style="width: 100%; padding: 0.75rem 1rem; background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(0, 229, 255, 0.3); border-radius: 10px; color: #FFFFFF; font-size: 0.95rem; min-height: 100px;">{{ old('additional_info') }}</textarea>
                        @error('additional_info')<p class="error-text">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i>
                        Gửi yêu cầu xác minh
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('proofImage').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        if (fileName) {
            document.getElementById('fileUploadText').textContent = fileName;
            document.getElementById('fileUploadLabel').style.borderColor = '#00E5FF';
        }
    });
</script>
@endpush
