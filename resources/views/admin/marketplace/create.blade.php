@extends('layouts.app')

@section('title', 'Thêm sản phẩm')

@push('styles')
<style>
    .marketplace-create-container { background: #000814; min-height: 100vh; }

    /* Hero Section */
    .create-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(139, 92, 246, 0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .create-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #8b5cf6, transparent);
    }
    .hero-icon {
        width: 60px; height: 60px; min-width: 60px;
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 0 25px rgba(139, 92, 246, 0.3);
    }
    .hero-icon i { font-size: 1.5rem; color: white; }
    .hero-title { font-family: 'Rajdhani', sans-serif; font-size: 1.5rem; font-weight: 700; color: #a78bfa; margin: 0; }
    .hero-subtitle { color: #94a3b8; font-size: 0.9rem; margin: 0.25rem 0 0 0; }

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
        gap: 8px;
        text-decoration: none;
    }
    .btn-neon:hover {
        background: rgba(0, 229, 255, 0.15);
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.4);
        color: #FFFFFF;
        transform: translateY(-2px);
    }
    .btn-neon-purple {
        background: linear-gradient(135deg, #5b21b6, #6d28d9);
        border-color: rgba(139, 92, 246, 0.4);
        color: #c4b5fd;
    }
    .btn-neon-purple:hover { box-shadow: 0 0 20px rgba(139, 92, 246, 0.4); color: #FFFFFF; }

    /* Form Card */
    .form-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(139, 92, 246, 0.15);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .form-card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(139, 92, 246, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .card-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; color: white;
    }
    .icon-purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .icon-cyan { background: linear-gradient(135deg, #06b6d4, #0891b2); }
    .icon-pink { background: linear-gradient(135deg, #ec4899, #db2777); }
    .icon-green { background: linear-gradient(135deg, #22c55e, #16a34a); }
    .card-title { font-family: 'Rajdhani', sans-serif; font-size: 1.1rem; font-weight: 700; color: #FFFFFF; margin: 0; }
    .form-card-body { padding: 1.5rem; overflow: hidden; }

    /* Form Controls */
    .form-group { margin-bottom: 1.25rem; }
    .form-label { display: block; color: #94a3b8; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; }
    .form-label .required { color: #ef4444; }
    .form-input, .form-select, .form-textarea {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        padding: 0.7rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(139, 92, 246, 0.2);
        border-radius: 10px;
        color: #FFFFFF;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #8b5cf6;
        box-shadow: 0 0 15px rgba(139, 92, 246, 0.2);
    }
    .form-input::placeholder, .form-textarea::placeholder { color: #64748b; }
    .form-select option { background: #0d1b2a; color: #FFFFFF; }
    .form-textarea { min-height: 100px; resize: vertical; }
    .form-hint { color: #64748b; font-size: 0.8rem; margin-top: 0.35rem; }
    .form-error { color: #ef4444; font-size: 0.8rem; margin-top: 0.35rem; }

    /* File Upload */
    .file-upload-zone {
        border: 2px dashed rgba(139, 92, 246, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        background: rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }
    .file-upload-zone:hover { border-color: rgba(139, 92, 246, 0.5); background: rgba(139, 92, 246, 0.05); }
    .file-upload-zone input[type="file"] { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
    .file-upload-icon { font-size: 2rem; color: #8b5cf6; margin-bottom: 0.5rem; }
    .file-upload-text { color: #94a3b8; font-size: 0.875rem; }
    .file-upload-text strong { color: #a78bfa; }

    /* Checkbox */
    .checkbox-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(139, 92, 246, 0.1); border-radius: 10px; transition: all 0.3s ease; cursor: pointer; margin-bottom: 0.75rem; }
    .checkbox-item:hover { background: rgba(139, 92, 246, 0.1); }
    .checkbox-input { width: 20px; height: 20px; accent-color: #8b5cf6; cursor: pointer; }
    .checkbox-label { color: #e2e8f0; font-size: 0.9rem; cursor: pointer; }

    /* Error Alert */
    .error-alert {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        color: #ef4444;
    }
    .error-alert ul { margin: 0; padding-left: 1.25rem; }
    .error-alert li { margin-bottom: 0.25rem; }

    /* Submit Section */
    .submit-section {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(139, 92, 246, 0.15);
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
    }

    @media (max-width: 768px) {
        .create-hero { padding: 1.25rem; }
        .hero-content { flex-direction: column; align-items: flex-start !important; gap: 1rem; }
        .btn-neon { width: 100%; justify-content: center; }
        .form-card-body { padding: 1rem; }
    }
</style>
@endpush

@section('content')
<div class="marketplace-create-container">
    <div class="max-w-5xl mx-auto px-4 py-6">
        <!-- Hero Section -->
        <div class="create-hero">
            <div class="hero-content flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="hero-icon"><i class="fas fa-plus"></i></div>
                    <div>
                        <h1 class="hero-title">Thêm sản phẩm mới</h1>
                        <p class="hero-subtitle">Tạo sản phẩm mới cho marketplace</p>
                    </div>
                </div>
                <a href="{{ route('admin.marketplace.index') }}" class="btn-neon">
                    <i class="fas fa-arrow-left"></i><span>Quay lại</span>
                </a>
            </div>
        </div>

        <!-- Error Alert -->
        @if($errors->any())
        <div class="error-alert">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.marketplace.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Main Info -->
                <div class="lg:col-span-2">
                    <!-- Basic Info Card -->
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="card-icon icon-purple"><i class="fas fa-info-circle"></i></div>
                            <h3 class="card-title">Thông tin cơ bản</h3>
                        </div>
                        <div class="form-card-body">
                            <div class="form-group">
                                <label class="form-label">Tên sản phẩm <span class="required">*</span></label>
                                <input type="text" name="name" class="form-input" value="{{ old('name') }}" required placeholder="Nhập tên sản phẩm">
                                @error('name')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mô tả</label>
                                <textarea name="description" class="form-textarea" placeholder="Mô tả chi tiết sản phẩm...">{{ old('description') }}</textarea>
                                @error('description')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Loại sản phẩm <span class="required">*</span></label>
                                    <select name="type" class="form-select" required id="productType">
                                        <option value="">Chọn loại</option>
                                        <option value="sticker" {{ old('type') == 'sticker' ? 'selected' : '' }}>Sticker Pack</option>
                                        <option value="tournament_ticket" {{ old('type') == 'tournament_ticket' ? 'selected' : '' }}>Vé giải đấu</option>
                                    </select>
                                    @error('type')<p class="form-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Danh mục <span class="required">*</span></label>
                                    <select name="category" class="form-select" required id="productCategory">
                                        <option value="">Chọn danh mục</option>
                                        <option value="sticker_pack" {{ old('category') == 'sticker_pack' ? 'selected' : '' }}>Bộ sticker</option>
                                        <option value="tournament_entry" {{ old('category') == 'tournament_entry' ? 'selected' : '' }}>Vé tham gia giải</option>
                                    </select>
                                    @error('category')<p class="form-error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            
                            <!-- Tournament Selection (for tournament_ticket type) -->
                            <div class="form-group" id="tournamentSection" style="display: none;">
                                <label class="form-label">Chọn giải đấu <span class="required">*</span></label>
                                <select name="tournament_id" class="form-select" id="tournamentSelect">
                                    <option value="">Chọn giải đấu</option>
                                    @foreach(\App\Models\Tournament::where('status', '!=', 'completed')->orderBy('created_at', 'desc')->get() as $tournament)
                                    <option value="{{ $tournament->id }}" {{ old('tournament_id') == $tournament->id ? 'selected' : '' }}>
                                        {{ $tournament->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('tournament_id')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Card -->
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="card-icon icon-green"><i class="fas fa-tags"></i></div>
                            <h3 class="card-title">Giá & Số lượng</h3>
                        </div>
                        <div class="form-card-body">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Giá (VNĐ) <span class="required">*</span></label>
                                    <input type="number" name="price" class="form-input" value="{{ old('price') }}" min="0" step="1000" required placeholder="0">
                                    @error('price')<p class="form-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Giá giảm (VNĐ)</label>
                                    <input type="number" name="discount_price" class="form-input" value="{{ old('discount_price') }}" min="0" step="1000" placeholder="0">
                                    @error('discount_price')<p class="form-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Số lượng</label>
                                    <input type="number" name="stock" class="form-input" value="{{ old('stock', -1) }}" min="-1" placeholder="-1">
                                    <p class="form-hint">-1 = không giới hạn</p>
                                    @error('stock')<p class="form-error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <!-- Right Column: Media & Settings -->
                <div>
                    <!-- Media Card -->
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="card-icon icon-pink"><i class="fas fa-images"></i></div>
                            <h3 class="card-title">Hình ảnh</h3>
                        </div>
                        <div class="form-card-body">
                            <div class="form-group">
                                <label class="form-label">Ảnh thumbnail</label>
                                <div class="file-upload-zone">
                                    <input type="file" name="thumbnail" accept="image/*">
                                    <div class="file-upload-icon"><i class="fas fa-image"></i></div>
                                    <div class="file-upload-text"><strong>Nhấp để chọn</strong><br><small>PNG, JPG (max 2MB)</small></div>
                                </div>
                                @error('thumbnail')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ảnh sản phẩm (nhiều ảnh)</label>
                                <div class="file-upload-zone">
                                    <input type="file" name="images[]" accept="image/*" multiple>
                                    <div class="file-upload-icon"><i class="fas fa-images"></i></div>
                                    <div class="file-upload-text"><strong>Chọn nhiều ảnh</strong><br><small>PNG, JPG (max 5MB mỗi ảnh)</small></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Card -->
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="card-icon icon-purple"><i class="fas fa-sliders-h"></i></div>
                            <h3 class="card-title">Cài đặt</h3>
                        </div>
                        <div class="form-card-body">
                            <label class="checkbox-item">
                                <input type="checkbox" name="is_active" value="1" class="checkbox-input" {{ old('is_active', true) ? 'checked' : '' }}>
                                <span class="checkbox-label"><i class="fas fa-check-circle" style="color: #22c55e; margin-right: 0.5rem;"></i>Kích hoạt sản phẩm</span>
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="is_featured" value="1" class="checkbox-input" {{ old('is_featured') ? 'checked' : '' }}>
                                <span class="checkbox-label"><i class="fas fa-star" style="color: #f59e0b; margin-right: 0.5rem;"></i>Sản phẩm nổi bật</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="submit-section">
                <div class="flex justify-end gap-3 flex-wrap">
                    <a href="{{ route('admin.marketplace.index') }}" class="btn-neon">
                        <i class="fas fa-times"></i><span>Hủy</span>
                    </a>
                    <button type="submit" class="btn-neon btn-neon-purple">
                        <i class="fas fa-save"></i><span>Lưu sản phẩm</span>
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
    const typeSelect = document.getElementById('productType');
    const categorySelect = document.getElementById('productCategory');
    const tournamentSection = document.getElementById('tournamentSection');
    const tournamentSelect = document.getElementById('tournamentSelect');
    
    function updateFormBasedOnType() {
        const type = typeSelect.value;
        
        // Show/hide tournament section
        if (type === 'tournament_ticket') {
            tournamentSection.style.display = 'block';
            tournamentSelect.required = true;
        } else {
            tournamentSection.style.display = 'none';
            tournamentSelect.required = false;
        }
        
        // Auto-select category based on type
        if (type === 'sticker') {
            categorySelect.value = 'sticker_pack';
        } else if (type === 'tournament_ticket') {
            categorySelect.value = 'tournament_entry';
        }
    }
    
    typeSelect.addEventListener('change', updateFormBasedOnType);
    
    // Initial check
    updateFormBasedOnType();
});
</script>
@endpush
