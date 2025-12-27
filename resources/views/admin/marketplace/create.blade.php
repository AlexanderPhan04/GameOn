@extends('layouts.app')

@section('title', 'Thêm sản phẩm')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-plus me-2"></i>Thêm sản phẩm mới
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.marketplace.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mô tả</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                              rows="4">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Loại sản phẩm <span class="text-danger">*</span></label>
                                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                                <option value="">Chọn loại</option>
                                                <option value="theme" {{ old('type') == 'theme' ? 'selected' : '' }}>Giao diện</option>
                                                <option value="sticker" {{ old('type') == 'sticker' ? 'selected' : '' }}>Sticker</option>
                                                <option value="game_item" {{ old('type') == 'game_item' ? 'selected' : '' }}>Vật phẩm game</option>
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                                <option value="">Chọn danh mục</option>
                                                <option value="ui_theme" {{ old('category') == 'ui_theme' ? 'selected' : '' }}>Giao diện UI</option>
                                                <option value="avatar_frame" {{ old('category') == 'avatar_frame' ? 'selected' : '' }}>Khung avatar</option>
                                                <option value="sticker_pack" {{ old('category') == 'sticker_pack' ? 'selected' : '' }}>Bộ sticker</option>
                                                <option value="emote" {{ old('category') == 'emote' ? 'selected' : '' }}>Emote</option>
                                                <option value="weapon_skin" {{ old('category') == 'weapon_skin' ? 'selected' : '' }}>Skin vũ khí</option>
                                                <option value="character_skin" {{ old('category') == 'character_skin' ? 'selected' : '' }}>Skin nhân vật</option>
                                                <option value="currency" {{ old('category') == 'currency' ? 'selected' : '' }}>Tiền tệ</option>
                                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Khác</option>
                                            </select>
                                            @error('category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Giá (VNĐ) <span class="text-danger">*</span></label>
                                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                                                   value="{{ old('price') }}" min="0" step="1000" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Giá giảm (VNĐ)</label>
                                            <input type="number" name="discount_price" class="form-control @error('discount_price') is-invalid @enderror" 
                                                   value="{{ old('discount_price') }}" min="0" step="1000">
                                            @error('discount_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Số lượng (-1 = không giới hạn)</label>
                                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                                                   value="{{ old('stock', -1) }}" min="-1">
                                            @error('stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Độ hiếm</label>
                                    <select name="rarity" class="form-select">
                                        <option value="">Không có</option>
                                        <option value="common" {{ old('rarity') == 'common' ? 'selected' : '' }}>Thường</option>
                                        <option value="uncommon" {{ old('rarity') == 'uncommon' ? 'selected' : '' }}>Không thường</option>
                                        <option value="rare" {{ old('rarity') == 'rare' ? 'selected' : '' }}>Hiếm</option>
                                        <option value="epic" {{ old('rarity') == 'epic' ? 'selected' : '' }}>Huyền thoại</option>
                                        <option value="legendary" {{ old('rarity') == 'legendary' ? 'selected' : '' }}>Cực hiếm</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">URL Preview</label>
                                    <input type="url" name="preview_url" class="form-control" value="{{ old('preview_url') }}" 
                                           placeholder="https://example.com/preview">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Game ID (nếu là vật phẩm game)</label>
                                    <input type="text" name="game_id" class="form-control" value="{{ old('game_id') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Ảnh thumbnail</label>
                                    <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" 
                                           accept="image/*">
                                    @error('thumbnail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Ảnh sản phẩm (nhiều ảnh)</label>
                                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Kích hoạt
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" 
                                               {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            Sản phẩm nổi bật
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Lưu sản phẩm
                            </button>
                            <a href="{{ route('admin.marketplace.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

