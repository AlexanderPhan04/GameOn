@extends('layouts.app')

@section('title', 'Marketplace')

@section('content')
<div class="container py-4">
    <style>
        .marketplace-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
        }
        .product-card {
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            background: white;
        }
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.1);
        }
        .product-thumbnail {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #f1f5f9;
        }
        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ef4444;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .price-tag {
            font-size: 1.25rem;
            font-weight: 700;
            color: #10b981;
        }
        .old-price {
            text-decoration: line-through;
            color: #94a3b8;
            font-size: 0.9rem;
        }
        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .filter-tab {
            padding: 0.5rem 1rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
        }
        .filter-tab:hover, .filter-tab.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
    </style>

    <div class="marketplace-header">
        <h1 class="mb-2"><i class="fas fa-store me-2"></i>Marketplace</h1>
        <p class="mb-0">Mua giao diện, sticker, vật phẩm game và quyên góp cho người dùng</p>
    </div>

    <!-- Filters -->
    <div class="filter-tabs">
        <a href="{{ route('marketplace.index') }}" class="filter-tab {{ !request('type') ? 'active' : '' }}">
            Tất cả
        </a>
        <a href="{{ route('marketplace.index', ['type' => 'theme']) }}" class="filter-tab {{ request('type') == 'theme' ? 'active' : '' }}">
            <i class="fas fa-palette me-1"></i>Giao diện
        </a>
        <a href="{{ route('marketplace.index', ['type' => 'sticker']) }}" class="filter-tab {{ request('type') == 'sticker' ? 'active' : '' }}">
            <i class="fas fa-smile me-1"></i>Sticker
        </a>
        <a href="{{ route('marketplace.index', ['type' => 'game_item']) }}" class="filter-tab {{ request('type') == 'game_item' ? 'active' : '' }}">
            <i class="fas fa-gamepad me-1"></i>Vật phẩm
        </a>
    </div>

    <!-- Products Grid -->
    <div class="row g-4">
        @forelse($products as $product)
        <div class="col-md-4 col-lg-3">
            <div class="product-card h-100 position-relative">
                @if($product->is_featured)
                <span class="product-badge">Nổi bật</span>
                @endif
                
                <a href="{{ route('marketplace.show', $product->id) }}" class="text-decoration-none">
                    <img src="{{ $product->thumbnail ? asset('uploads/' . $product->thumbnail) : 'https://via.placeholder.com/300x200?text=' . urlencode($product->name) }}" 
                         alt="{{ $product->name }}" 
                         class="product-thumbnail">
                    
                    <div class="p-3">
                        <h5 class="mb-2 text-dark">{{ $product->name }}</h5>
                        <p class="text-muted small mb-2" style="min-height: 40px;">
                            {{ Str::limit($product->description, 60) }}
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                @if($product->hasDiscount())
                                    <span class="old-price">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                                    <div class="price-tag">{{ number_format($product->current_price, 0, ',', '.') }} đ</div>
                                @else
                                    <div class="price-tag">{{ number_format($product->price, 0, ',', '.') }} đ</div>
                                @endif
                            </div>
                            <span class="badge bg-secondary">{{ ucfirst($product->type) }}</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">Chưa có sản phẩm nào</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
