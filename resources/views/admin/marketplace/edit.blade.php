@extends('layouts.app')

@section('title', 'Sửa sản phẩm')

@push('styles')
<style>
    .marketplace-edit-container { background: #000814; min-height: 100vh; }

    /* Hero Section */
    .edit-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(139, 92, 246, 0.2);
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
        background: linear-gradient(90deg, transparent, #f59e0b, transparent);
    }
    .hero-icon {
        width: 60px; height: 60px; min-width: 60px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 0 25px rgba(245, 158, 11, 0.3);
    }
    .hero-icon i { font-size: 1.5rem; color: white; }
    .hero-title { font-family: 'Rajdhani', sans-serif; font-size: 1.5rem; font-weight: 700; color: #fbbf24; margin: 0; }
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
    .btn-neon-orange {
        background: linear-gradient(135deg, #b45309, #d97706);
        border-color: rgba(245, 158, 11, 0.4);
        color: #fef3c7;
    }
    .btn-neon-orange:hover { box-shadow: 0 0 20px rgba(245, 158, 11, 0.4); color: #FFFFFF; }

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
    .icon-orange { background: linear-gradient(135deg, #f59e0b, #d97706); }
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

    /* Current Image Preview */
    .current-image {
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1rem;
        border: 1px solid rgba(139, 92, 246, 0.2);
    }
    .current-image img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }
    .current-images-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .current-images-grid img {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid rgba(139, 92, 246, 0.2);
    }

    /* Checkbox */
    .checkbox-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(139, 92, 246, 0.1); border-radius: 10px; transition: all 0.3s ease; cursor: pointer; margin-bottom: 0.75rem; }
    .checkbox-item:hover { background: rgba(139, 92, 246, 0.1); }
    .checkbox-input { width: 20px; height: 20px; accent-color: #8b5cf6; cursor: pointer; }
    .checkbox-label { color: #e2e8f0; font-size: 0.9rem; cursor: pointer; }

    /* Stats Card */
    .stats-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(139, 92, 246, 0.1);
        border-radius: 10px;
        margin-bottom: 0.5rem;
    }
    .stats-label { color: #94a3b8; font-size: 0.85rem; }
    .stats-value { color: #a78bfa; font-weight: 600; font-size: 0.9rem; }

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
        .edit-hero { padding: 1.25rem; }
        .hero-content { flex-direction: column; align-items: flex-start !important; gap: 1rem; }
        .btn-neon { width: 100%; justify-content: center; }
        .form-card-body { padding: 1rem; }
        .current-images-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endpush

@section('content')
<div class="marketplace-edit-container">
    <div class="max-w-5xl mx-auto px-4 py-6">
        <!-- Hero Section -->
        <div class="edit-hero">
            <div class="hero-content flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="hero-icon"><i class="fas fa-edit"></i></div>
                    <div>
                        <h1 class="hero-title">Sửa sản phẩm</h1>
                        <p class="hero-subtitle">{{ $product->name }}</p>
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

        <form action="{{ route('admin.marketplace.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                                <input type="text" name="name" class="form-input" value="{{ old('name', $product->name) }}" required placeholder="Nhập tên sản phẩm">
                                @error('name')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mô tả</label>
                                <textarea name="description" class="form-textarea" placeholder="Mô tả chi tiết sản phẩm...">{{ old('description', $product->description) }}</textarea>
                                @error('description')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Loại sản phẩm <span class="required">*</span></label>
                                    <select name="type" class="form-select" required id="productType">
                                        <option value="sticker" {{ old('type', $product->type) == 'sticker' ? 'selected' : '' }}>Sticker Pack</option>
                                        <option value="tournament_ticket" {{ old('type', $product->type) == 'tournament_ticket' ? 'selected' : '' }}>Vé giải đấu</option>
                                    </select>
                                    @error('type')<p class="form-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Danh mục <span class="required">*</span></label>
                                    <select name="category" class="form-select" required id="productCategory">
                                        <option value="sticker_pack" {{ old('category', $product->category) == 'sticker_pack' ? 'selected' : '' }}>Bộ sticker</option>
                                        <option value="tournament_entry" {{ old('category', $product->category) == 'tournament_entry' ? 'selected' : '' }}>Vé tham gia giải</option>
                                    </select>
                                    @error('category')<p class="form-error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            
                            <!-- Tournament Selection -->
                            <div class="form-group" id="tournamentSection" style="{{ $product->type == 'tournament_ticket' ? '' : 'display: none;' }}">
                                <label class="form-label">Chọn giải đấu <span class="required">*</span></label>
                                <select name="tournament_id" class="form-select" id="tournamentSelect">
                                    <option value="">Chọn giải đấu</option>
                                    @foreach(\App\Models\Tournament::orderBy('created_at', 'desc')->get() as $tournament)
                                    <option value="{{ $tournament->id }}" {{ old('tournament_id', $product->tournament_id) == $tournament->id ? 'selected' : '' }}>
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
                                    <input type="number" name="price" class="form-input" value="{{ old('price', $product->price) }}" min="0" step="1000" required placeholder="0">
                                    @error('price')<p class="form-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Giá giảm (VNĐ)</label>
                                    <input type="number" name="discount_price" class="form-input" value="{{ old('discount_price', $product->discount_price) }}" min="0" step="1000" placeholder="0">
                                    @error('discount_price')<p class="form-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Số lượng</label>
                                    <input type="number" name="stock" class="form-input" value="{{ old('stock', $product->stock) }}" min="-1" placeholder="-1">
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
                                @if($product->thumbnail)
                                <div class="current-image">
                                    <img src="{{ asset('uploads/' . $product->thumbnail) }}" alt="{{ $product->name }}">
                                </div>
                                @endif
                                <div class="file-upload-zone">
                                    <input type="file" name="thumbnail" accept="image/*">
                                    <div class="file-upload-icon"><i class="fas fa-image"></i></div>
                                    <div class="file-upload-text"><strong>Nhấp để thay đổi</strong><br><small>Để trống nếu giữ nguyên</small></div>
                                </div>
                                @error('thumbnail')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ảnh sản phẩm</label>
                                @if($product->images && count($product->images) > 0)
                                <div class="current-images-grid">
                                    @foreach($product->images as $image)
                                    <img src="{{ asset('uploads/' . $image) }}" alt="Product image">
                                    @endforeach
                                </div>
                                @endif
                                <div class="file-upload-zone">
                                    <input type="file" name="images[]" accept="image/*" multiple>
                                    <div class="file-upload-icon"><i class="fas fa-images"></i></div>
                                    <div class="file-upload-text"><strong>Chọn nhiều ảnh</strong><br><small>Để trống nếu giữ nguyên</small></div>
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
                                <input type="checkbox" name="is_active" value="1" class="checkbox-input" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <span class="checkbox-label"><i class="fas fa-check-circle" style="color: #22c55e; margin-right: 0.5rem;"></i>Kích hoạt sản phẩm</span>
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="is_featured" value="1" class="checkbox-input" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                <span class="checkbox-label"><i class="fas fa-star" style="color: #f59e0b; margin-right: 0.5rem;"></i>Sản phẩm nổi bật</span>
                            </label>
                        </div>
                    </div>

                    <!-- Stats Card -->
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="card-icon icon-cyan"><i class="fas fa-chart-bar"></i></div>
                            <h3 class="card-title">Thống kê</h3>
                        </div>
                        <div class="form-card-body">
                            <div class="stats-item">
                                <span class="stats-label"><i class="fas fa-shopping-cart mr-2"></i>Đã bán</span>
                                <span class="stats-value">{{ number_format($product->sold_count) }}</span>
                            </div>
                            <div class="stats-item">
                                <span class="stats-label"><i class="fas fa-calendar mr-2"></i>Ngày tạo</span>
                                <span class="stats-value">{{ $product->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="stats-item">
                                <span class="stats-label"><i class="fas fa-clock mr-2"></i>Cập nhật</span>
                                <span class="stats-value">{{ $product->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
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
                    <button type="submit" class="btn-neon btn-neon-orange">
                        <i class="fas fa-save"></i><span>Cập nhật sản phẩm</span>
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
        
        if (type === 'tournament_ticket') {
            tournamentSection.style.display = 'block';
            tournamentSelect.required = true;
        } else {
            tournamentSection.style.display = 'none';
            tournamentSelect.required = false;
        }
        
        if (type === 'sticker') {
            categorySelect.value = 'sticker_pack';
        } else if (type === 'tournament_ticket') {
            categorySelect.value = 'tournament_entry';
        }
    }
    
    typeSelect.addEventListener('change', updateFormBasedOnType);
});
</script>
@endpush
