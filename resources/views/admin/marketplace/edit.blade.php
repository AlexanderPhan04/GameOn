@extends('layouts.app')

@section('title', 'Sửa sản phẩm')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Sửa sản phẩm: {{ $product->name }}
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.marketplace.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mô tả</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                              rows="4">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Loại sản phẩm <span class="text-danger">*</span></label>
                                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                                <option value="theme" {{ old('type', $product->type) == 'theme' ? 'selected' : '' }}>Giao diện</option>
                                                <option value="sticker" {{ old('type', $product->type) == 'sticker' ? 'selected' : '' }}>Sticker</option>
                                                <option value="game_item" {{ old('type', $product->type) == 'game_item' ? 'selected' : '' }}>Vật phẩm game</option>
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
                                                <option value="ui_theme" {{ old('category', $product->category) == 'ui_theme' ? 'selected' : '' }}>Giao diện UI</option>
                                                <option value="avatar_frame" {{ old('category', $product->category) == 'avatar_frame' ? 'selected' : '' }}>Khung avatar</option>
                                                <option value="sticker_pack" {{ old('category', $product->category) == 'sticker_pack' ? 'selected' : '' }}>Bộ sticker</option>
                                                <option value="emote" {{ old('category', $product->category) == 'emote' ? 'selected' : '' }}>Emote</option>
                                                <option value="weapon_skin" {{ old('category', $product->category) == 'weapon_skin' ? 'selected' : '' }}>Skin vũ khí</option>
                                                <option value="character_skin" {{ old('category', $product->category) == 'character_skin' ? 'selected' : '' }}>Skin nhân vật</option>
                                                <option value="currency" {{ old('category', $product->category) == 'currency' ? 'selected' : '' }}>Tiền tệ</option>
                                                <option value="other" {{ old('category', $product->category) == 'other' ? 'selected' : '' }}>Khác</option>
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
                                                   value="{{ old('price', $product->price) }}" min="0" step="1000" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Giá giảm (VNĐ)</label>
                                            <input type="number" name="discount_price" class="form-control @error('discount_price') is-invalid @enderror" 
                                                   value="{{ old('discount_price', $product->discount_price) }}" min="0" step="1000">
                                            @error('discount_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Số lượng (-1 = không giới hạn)</label>
                                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                                                   value="{{ old('stock', $product->stock) }}" min="-1">
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
                                        <option value="common" {{ old('rarity', $product->rarity) == 'common' ? 'selected' : '' }}>Thường</option>
                                        <option value="uncommon" {{ old('rarity', $product->rarity) == 'uncommon' ? 'selected' : '' }}>Không thường</option>
                                        <option value="rare" {{ old('rarity', $product->rarity) == 'rare' ? 'selected' : '' }}>Hiếm</option>
                                        <option value="epic" {{ old('rarity', $product->rarity) == 'epic' ? 'selected' : '' }}>Huyền thoại</option>
                                        <option value="legendary" {{ old('rarity', $product->rarity) == 'legendary' ? 'selected' : '' }}>Cực hiếm</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">URL Preview</label>
                                    <input type="url" name="preview_url" class="form-control" 
                                           value="{{ old('preview_url', $product->preview_url) }}" 
                                           placeholder="https://example.com/preview">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Game ID (nếu là vật phẩm game)</label>
                                    <input type="text" name="game_id" class="form-control" 
                                           value="{{ old('game_id', $product->game_id) }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Ảnh thumbnail</label>
                                    @if($product->thumbnail)
                                        <div class="mb-2">
                                            <img src="{{ asset('uploads/' . $product->thumbnail) }}" 
                                                 alt="{{ $product->name }}" 
                                                 style="max-width: 200px; border-radius: 8px;">
                                        </div>
                                    @endif
                                    <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" 
                                           accept="image/*">
                                    <small class="text-muted">Để trống nếu không muốn thay đổi</small>
                                    @error('thumbnail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Ảnh sản phẩm (nhiều ảnh)</label>
                                    @if($product->images && count($product->images) > 0)
                                        <div class="mb-2">
                                            @foreach($product->images as $image)
                                                <img src="{{ asset('uploads/' . $image) }}" 
                                                     alt="Product image" 
                                                     style="max-width: 100px; margin-right: 5px; border-radius: 4px;">
                                            @endforeach
                                        </div>
                                    @endif
                                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                                    <small class="text-muted">Để trống nếu không muốn thay đổi</small>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Kích hoạt
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" 
                                               {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            Sản phẩm nổi bật
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted">
                                        <strong>Đã bán:</strong> {{ $product->sold_count }}<br>
                                        <strong>Ngày tạo:</strong> {{ $product->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Cập nhật sản phẩm
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

