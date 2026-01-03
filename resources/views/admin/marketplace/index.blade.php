@extends('layouts.app')

@section('title', 'Quản lý Marketplace')

@push('styles')
<style>
    .marketplace-container { background: #000814; min-height: 100vh; }

    /* Hero Section */
    .marketplace-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(139, 92, 246, 0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .marketplace-hero::before {
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

    /* Alert */
    .alert-custom { border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 12px; }
    .alert-success-custom { background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: #22c55e; }
    .alert-error-custom { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; }

    /* Filter Card */
    .filter-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(139, 92, 246, 0.15);
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .filter-input, .filter-select {
        width: 100%;
        padding: 0.6rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(139, 92, 246, 0.2);
        border-radius: 8px;
        color: #FFFFFF;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }
    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: #8b5cf6;
        box-shadow: 0 0 10px rgba(139, 92, 246, 0.2);
    }
    .filter-input::placeholder { color: #64748b; }
    .filter-select option { background: #0d1b2a; color: #FFFFFF; }

    /* Table Card */
    .table-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(139, 92, 246, 0.15);
        border-radius: 16px;
        overflow: hidden;
    }
    .products-table { width: 100%; border-collapse: collapse; }
    .products-table th {
        background: rgba(139, 92, 246, 0.05);
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(139, 92, 246, 0.1);
    }
    .products-table td {
        padding: 1rem;
        color: #e2e8f0;
        font-size: 0.875rem;
        border-bottom: 1px solid rgba(139, 92, 246, 0.05);
        vertical-align: middle;
    }
    .products-table tbody tr { transition: all 0.3s ease; }
    .products-table tbody tr:hover { background: rgba(139, 92, 246, 0.05); }

    .product-id { color: #8b5cf6; font-weight: 600; }
    .product-thumb {
        width: 60px; height: 60px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid rgba(139, 92, 246, 0.3);
    }
    .product-thumb-placeholder {
        width: 60px; height: 60px;
        border-radius: 10px;
        background: linear-gradient(135deg, #1e1b4b, #312e81);
        display: flex; align-items: center; justify-content: center;
        border: 2px solid rgba(139, 92, 246, 0.3);
    }
    .product-thumb-placeholder i { color: #8b5cf6; font-size: 1.25rem; }
    .product-name { font-weight: 600; color: #FFFFFF; }

    /* Badges */
    .badge-custom { padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
    .badge-featured { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .badge-type { background: rgba(139, 92, 246, 0.2); color: #a78bfa; }
    .badge-active { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    .badge-inactive { background: rgba(100, 116, 139, 0.2); color: #94a3b8; }

    .price-original { color: #64748b; text-decoration: line-through; font-size: 0.8rem; }
    .price-current { color: #22c55e; font-weight: 700; font-size: 0.95rem; }
    .sold-count { color: #a78bfa; font-weight: 600; }

    /* Action Buttons */
    .btn-action { padding: 0.4rem 0.6rem; border-radius: 8px; font-size: 0.8rem; transition: all 0.3s ease; cursor: pointer; border: 1px solid transparent; background: transparent; }
    .btn-action-edit { color: #8b5cf6; border-color: rgba(139, 92, 246, 0.3); }
    .btn-action-edit:hover { background: rgba(139, 92, 246, 0.15); }
    .btn-action-toggle { color: #f59e0b; border-color: rgba(245, 158, 11, 0.3); }
    .btn-action-toggle:hover { background: rgba(245, 158, 11, 0.15); }
    .btn-action-activate { color: #22c55e; border-color: rgba(34, 197, 94, 0.3); }
    .btn-action-activate:hover { background: rgba(34, 197, 94, 0.15); }
    .btn-action-delete { color: #ef4444; border-color: rgba(239, 68, 68, 0.3); }
    .btn-action-delete:hover { background: rgba(239, 68, 68, 0.15); }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(139, 92, 246, 0.15);
        border-radius: 16px;
    }
    .empty-icon {
        width: 100px; height: 100px;
        background: rgba(139, 92, 246, 0.1);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.5rem;
    }
    .empty-icon i { font-size: 2.5rem; color: #8b5cf6; }
    .empty-title { color: #FFFFFF; font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem; }
    .empty-text { color: #64748b; font-size: 0.95rem; margin-bottom: 1.5rem; }

    .pagination-wrapper { padding: 1rem 1.5rem; border-top: 1px solid rgba(139, 92, 246, 0.1); display: flex; justify-content: center; }

    @media (max-width: 768px) {
        .marketplace-hero { padding: 1.25rem; }
        .hero-content { flex-direction: column; align-items: flex-start !important; gap: 1rem; }
        .btn-neon { width: 100%; justify-content: center; }
        .products-table { display: block; overflow-x: auto; }
    }
</style>
@endpush

@section('content')
<div class="marketplace-container">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Hero Section -->
        <div class="marketplace-hero">
            <div class="hero-content flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="hero-icon"><i class="fas fa-store"></i></div>
                    <div>
                        <h1 class="hero-title">Quản lý Marketplace</h1>
                        <p class="hero-subtitle">Quản lý sản phẩm, giao diện và vật phẩm</p>
                    </div>
                </div>
                <a href="{{ route('admin.marketplace.create') }}" class="btn-neon btn-neon-purple">
                    <i class="fas fa-plus"></i><span>Thêm sản phẩm</span>
                </a>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
        <div class="alert-custom alert-success-custom">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="alert-custom alert-error-custom">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <!-- Filter Card -->
        <div class="filter-card">
            <form method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <input type="text" name="search" class="filter-input" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                    </div>
                    <div>
                        <select name="type" class="filter-select">
                            <option value="">Tất cả loại</option>
                            <option value="theme" {{ request('type') == 'theme' ? 'selected' : '' }}>Giao diện</option>
                            <option value="sticker" {{ request('type') == 'sticker' ? 'selected' : '' }}>Sticker</option>
                            <option value="game_item" {{ request('type') == 'game_item' ? 'selected' : '' }}>Vật phẩm</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="btn-neon flex-1"><i class="fas fa-filter"></i><span>Lọc</span></button>
                        <a href="{{ route('admin.marketplace.index') }}" class="btn-neon"><i class="fas fa-times"></i></a>
                    </div>
                </div>
            </form>
        </div>

        @if($products->count() > 0)
        <!-- Products Table -->
        <div class="table-card">
            <div class="overflow-x-auto">
                <table class="products-table">
                    <thead>
                        <tr>
                            <th class="w-12">#</th>
                            <th class="w-20">Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Loại</th>
                            <th>Giá</th>
                            <th>Đã bán</th>
                            <th>Trạng thái</th>
                            <th class="w-32">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td><span class="product-id">{{ $product->id }}</span></td>
                            <td>
                                @if($product->thumbnail)
                                <img src="{{ asset('uploads/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="product-thumb">
                                @else
                                <div class="product-thumb-placeholder"><i class="fas fa-image"></i></div>
                                @endif
                            </td>
                            <td>
                                <span class="product-name">{{ $product->name }}</span>
                                @if($product->is_featured)
                                <span class="badge-custom badge-featured" style="margin-left: 0.5rem;">Nổi bật</span>
                                @endif
                            </td>
                            <td><span class="badge-custom badge-type">{{ ucfirst($product->type) }}</span></td>
                            <td>
                                @if($product->hasDiscount())
                                <div class="price-original">{{ number_format($product->price, 0, ',', '.') }} đ</div>
                                <div class="price-current">{{ number_format($product->current_price, 0, ',', '.') }} đ</div>
                                @else
                                <div class="price-current">{{ number_format($product->price, 0, ',', '.') }} đ</div>
                                @endif
                            </td>
                            <td><span class="sold-count">{{ $product->sold_count }}</span></td>
                            <td>
                                <span class="badge-custom {{ $product->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $product->is_active ? 'Hoạt động' : 'Vô hiệu' }}
                                </span>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.marketplace.edit', $product->id) }}" class="btn-action btn-action-edit" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn-action {{ $product->is_active ? 'btn-action-toggle' : 'btn-action-activate' }} toggle-status" 
                                            data-id="{{ $product->id }}" title="{{ $product->is_active ? 'Vô hiệu' : 'Kích hoạt' }}">
                                        <i class="fas fa-{{ $product->is_active ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                    <form action="{{ route('admin.marketplace.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-action-delete" title="Xóa">
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
            @if($products->hasPages())
            <div class="pagination-wrapper">
                {{ $products->links() }}
            </div>
            @endif
        </div>
        @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-box-open"></i></div>
            <h3 class="empty-title">Chưa có sản phẩm nào</h3>
            <p class="empty-text">Thêm sản phẩm đầu tiên để bắt đầu bán hàng!</p>
            <a href="{{ route('admin.marketplace.create') }}" class="btn-neon btn-neon-purple">
                <i class="fas fa-plus"></i><span>Thêm sản phẩm đầu tiên</span>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.toggle-status').forEach(btn => {
    btn.addEventListener('click', async function() {
        const productId = this.dataset.id;
        try {
            const response = await fetch(`/admin/marketplace/${productId}/toggle-status`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
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
@endpush
