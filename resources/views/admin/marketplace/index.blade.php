@extends('layouts.app')

@section('title', 'Quản lý Marketplace')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-store me-2"></i>Quản lý Marketplace
                    </h6>
                    <a href="{{ route('admin.marketplace.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Thêm sản phẩm
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filters -->
                    <form method="GET" class="mb-3">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="type" class="form-select">
                                    <option value="">Tất cả loại</option>
                                    <option value="theme" {{ request('type') == 'theme' ? 'selected' : '' }}>Giao diện</option>
                                    <option value="sticker" {{ request('type') == 'sticker' ? 'selected' : '' }}>Sticker</option>
                                    <option value="game_item" {{ request('type') == 'game_item' ? 'selected' : '' }}>Vật phẩm</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Lọc</button>
                                <a href="{{ route('admin.marketplace.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    @if($products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="15%">Hình ảnh</th>
                                        <th width="20%">Tên sản phẩm</th>
                                        <th width="10%">Loại</th>
                                        <th width="10%">Giá</th>
                                        <th width="8%">Đã bán</th>
                                        <th width="8%">Trạng thái</th>
                                        <th width="15%">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>
                                                @if($product->thumbnail)
                                                    <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                                         alt="{{ $product->name }}" 
                                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                                @else
                                                    <div style="width: 60px; height: 60px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $product->name }}</strong>
                                                @if($product->is_featured)
                                                    <span class="badge bg-warning">Nổi bật</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($product->type) }}</span>
                                            </td>
                                            <td>
                                                @if($product->hasDiscount())
                                                    <span class="text-muted text-decoration-line-through">{{ number_format($product->price, 0, ',', '.') }} đ</span><br>
                                                    <strong class="text-success">{{ number_format($product->current_price, 0, ',', '.') }} đ</strong>
                                                @else
                                                    <strong class="text-success">{{ number_format($product->price, 0, ',', '.') }} đ</strong>
                                                @endif
                                            </td>
                                            <td>{{ $product->sold_count }}</td>
                                            <td>
                                                <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $product->is_active ? 'Hoạt động' : 'Vô hiệu' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.marketplace.edit', $product->id) }}" 
                                                       class="btn btn-sm btn-primary" title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm {{ $product->is_active ? 'btn-warning' : 'btn-success' }} toggle-status" 
                                                            data-id="{{ $product->id }}" 
                                                            title="{{ $product->is_active ? 'Vô hiệu' : 'Kích hoạt' }}">
                                                        <i class="fas fa-{{ $product->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                    </button>
                                                    <form action="{{ route('admin.marketplace.destroy', $product->id) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có sản phẩm nào</p>
                            <a href="{{ route('admin.marketplace.create') }}" class="btn btn-primary">Thêm sản phẩm đầu tiên</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.toggle-status').forEach(btn => {
    btn.addEventListener('click', async function() {
        const productId = this.dataset.id;
        try {
            const response = await fetch(`/admin/marketplace/${productId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            });
            const data = await response.json();
            if (data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra');
            }
        } catch (error) {
            alert('Có lỗi xảy ra');
        }
    });
});
</script>
@endsection

