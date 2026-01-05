@extends('layouts.app')

@section('title', 'Marketplace')

@push('styles')
<style>
    .marketplace-page {
        min-height: calc(100vh - 64px);
        background: linear-gradient(180deg, #000814 0%, #0d1b2a 100%);
        padding: 0;
    }
    
    /* Header */
    .marketplace-header {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(139, 92, 246, 0.15) 100%);
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        padding: 2.5rem 2rem;
    }
    
    .header-content {
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    
    .header-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.75rem;
        box-shadow: 0 8px 24px rgba(99, 102, 241, 0.4);
    }
    
    .header-text h1 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }
    
    .header-text p {
        color: #94a3b8;
        font-size: 0.95rem;
        margin: 0.25rem 0 0 0;
    }
    
    /* Main Content */
    .marketplace-content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    
    .filter-tab {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1.25rem;
        background: rgba(0, 0, 20, 0.6);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 12px;
        color: #94a3b8;
        font-size: 0.9rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .filter-tab:hover {
        background: rgba(0, 229, 255, 0.1);
        border-color: rgba(0, 229, 255, 0.3);
        color: #00E5FF;
    }
    
    .filter-tab.active {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
    }
    
    .filter-tab i {
        font-size: 0.85rem;
    }
    
    /* Products Grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    
    /* Product Card */
    .product-card {
        background: linear-gradient(135deg, rgba(13, 27, 42, 0.95), rgba(0, 0, 34, 0.95));
        border: 1px solid rgba(0, 229, 255, 0.12);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
    }
    
    .product-card:hover {
        transform: translateY(-6px);
        border-color: rgba(0, 229, 255, 0.4);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 30px rgba(0, 229, 255, 0.1);
    }
    
    .product-image {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: linear-gradient(135deg, #1e3a5f, #0d1b2a);
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.05);
    }
    
    .product-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }
    
    .product-badge.discount {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }
    
    .product-body {
        padding: 1.25rem;
    }
    
    .product-name {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 0.5rem 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .product-desc {
        color: #64748b;
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 42px;
    }
    
    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .product-price {
        display: flex;
        flex-direction: column;
    }
    
    .price-current {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: #22c55e;
    }
    
    .price-old {
        font-size: 0.8rem;
        color: #64748b;
        text-decoration: line-through;
    }
    
    .product-type {
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .product-type.theme {
        background: rgba(139, 92, 246, 0.15);
        border: 1px solid rgba(139, 92, 246, 0.3);
        color: #a78bfa;
    }
    
    .product-type.sticker {
        background: rgba(245, 158, 11, 0.15);
        border: 1px solid rgba(245, 158, 11, 0.3);
        color: #fbbf24;
    }
    
    .product-type.game_item {
        background: rgba(0, 229, 255, 0.15);
        border: 1px solid rgba(0, 229, 255, 0.3);
        color: #00E5FF;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(135deg, rgba(13, 27, 42, 0.5), rgba(0, 0, 34, 0.5));
        border: 1px solid rgba(0, 229, 255, 0.1);
        border-radius: 20px;
    }
    
    .empty-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
        border: 1px solid rgba(99, 102, 241, 0.2);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .empty-icon i {
        font-size: 2.5rem;
        color: #6366f1;
    }
    
    .empty-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.5rem;
    }
    
    .empty-desc {
        color: #94a3b8;
        font-size: 1rem;
    }
    
    /* Pagination */
    .pagination-wrapper {
        margin-top: 2.5rem;
        display: flex;
        justify-content: center;
    }
    
    .pagination-wrapper .pagination {
        display: flex;
        gap: 0.5rem;
    }
    
    .pagination-wrapper .page-item .page-link {
        background: rgba(0, 0, 20, 0.6);
        border: 1px solid rgba(0, 229, 255, 0.15);
        color: #94a3b8;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s;
    }
    
    .pagination-wrapper .page-item .page-link:hover {
        background: rgba(0, 229, 255, 0.1);
        border-color: rgba(0, 229, 255, 0.3);
        color: #00E5FF;
    }
    
    .pagination-wrapper .page-item.active .page-link {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-color: transparent;
        color: #fff;
    }
    
    @media (max-width: 768px) {
        .marketplace-header {
            padding: 1.5rem 1rem;
        }
        
        .header-content {
            flex-direction: column;
            text-align: center;
        }
        
        .marketplace-content {
            padding: 1.5rem 1rem;
        }
        
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="marketplace-page">
    <div class="marketplace-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-store"></i>
            </div>
            <div class="header-text">
                <h1>Marketplace</h1>
                <p>Mua giao diện, sticker, vật phẩm game và quyên góp cho người dùng</p>
            </div>
        </div>
    </div>
    
    <div class="marketplace-content">
        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <a href="{{ route('marketplace.index') }}" class="filter-tab {{ !request('type') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i>
                Tất cả
            </a>
            <a href="{{ route('marketplace.index', ['type' => 'theme']) }}" class="filter-tab {{ request('type') == 'theme' ? 'active' : '' }}">
                <i class="fas fa-palette"></i>
                Giao diện
            </a>
            <a href="{{ route('marketplace.index', ['type' => 'sticker']) }}" class="filter-tab {{ request('type') == 'sticker' ? 'active' : '' }}">
                <i class="fas fa-smile"></i>
                Sticker
            </a>
            <a href="{{ route('marketplace.index', ['type' => 'game_item']) }}" class="filter-tab {{ request('type') == 'game_item' ? 'active' : '' }}">
                <i class="fas fa-gamepad"></i>
                Vật phẩm
            </a>
        </div>
        
        <!-- Products Grid -->
        @if($products->count() > 0)
        <div class="products-grid">
            @foreach($products as $product)
            <a href="{{ route('marketplace.show', $product->id) }}" class="product-card">
                <div class="product-image">
                    @if($product->thumbnail)
                        <img src="{{ asset('uploads/' . $product->thumbnail) }}" alt="{{ $product->name }}">
                    @else
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-image" style="font-size: 3rem; color: rgba(0, 229, 255, 0.2);"></i>
                        </div>
                    @endif
                    
                    @if($product->is_featured)
                        <span class="product-badge">Nổi bật</span>
                    @elseif($product->hasDiscount())
                        <span class="product-badge discount">-{{ $product->discount_percent }}%</span>
                    @endif
                </div>
                
                <div class="product-body">
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <p class="product-desc">{{ $product->description ?: 'Chưa có mô tả' }}</p>
                    
                    <div class="product-footer">
                        <div class="product-price">
                            @if($product->hasDiscount())
                                <span class="price-old">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                            @endif
                            <span class="price-current">{{ number_format($product->current_price ?? $product->price, 0, ',', '.') }} đ</span>
                        </div>
                        <span class="product-type {{ $product->type }}">
                            @if($product->type == 'theme') Giao diện
                            @elseif($product->type == 'sticker') Sticker
                            @elseif($product->type == 'game_item') Vật phẩm
                            @else {{ ucfirst($product->type) }}
                            @endif
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($products->hasPages())
        <div class="pagination-wrapper">
            {{ $products->links() }}
        </div>
        @endif
        
        @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-box-open"></i>
            </div>
            <h3 class="empty-title">Chưa có sản phẩm nào</h3>
            <p class="empty-desc">Các sản phẩm sẽ sớm được cập nhật</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Real-time marketplace updates
    function setupMarketplaceUpdates() {
        if (typeof window.Echo === 'undefined') {
            setTimeout(setupMarketplaceUpdates, 500);
            return;
        }
        
        window.Echo.channel('marketplace')
            .listen('.product.updated', (e) => {
                if (e.action === 'created') {
                    showToast(`Sản phẩm mới: ${e.product.name}`, 'info');
                    // Optionally reload to show new product
                    setTimeout(() => location.reload(), 2000);
                } else if (e.action === 'updated') {
                    // Update product card if visible
                    updateProductCard(e.product);
                } else if (e.action === 'deactivated') {
                    // Remove product card when deactivated
                    removeProductCard(e.product.id);
                    showToast(`Sản phẩm "${e.product.name}" đã bị vô hiệu`, 'info');
                } else if (e.action === 'activated') {
                    // Reload to show activated product
                    showToast(`Sản phẩm "${e.product.name}" đã được kích hoạt`, 'success');
                    setTimeout(() => location.reload(), 2000);
                }
            });
        
        @auth
        // Listen for cart updates
        window.Echo.private('user.{{ Auth::id() }}')
            .listen('.cart.updated', (e) => {
                updateCartBadge(e.cart_count);
            });
        @endauth
    }
    
    function removeProductCard(productId) {
        const card = document.querySelector(`.product-card[href*="/product/${productId}"]`);
        if (card) {
            card.style.animation = 'fadeOutProduct 0.5s ease forwards';
            setTimeout(() => {
                card.remove();
                // Check if grid is empty
                const grid = document.querySelector('.products-grid');
                if (grid && grid.children.length === 0) {
                    location.reload();
                }
            }, 500);
        }
    }
    
    function updateProductCard(product) {
        const card = document.querySelector(`.product-card[href*="/product/${product.id}"]`);
        if (card) {
            const nameEl = card.querySelector('.product-name');
            const priceEl = card.querySelector('.price-current');
            
            if (nameEl) nameEl.textContent = product.name;
            if (priceEl) priceEl.textContent = formatPrice(product.current_price) + ' đ';
            
            // Highlight updated card
            card.style.animation = 'none';
            card.offsetHeight;
            card.style.animation = 'highlightProduct 1s ease';
        }
    }
    
    function updateCartBadge(count) {
        const badge = document.querySelector('.cart-badge');
        const cartBtn = document.querySelector('.cart-icon-btn');
        
        if (count > 0) {
            if (badge) {
                badge.textContent = count;
            } else if (cartBtn) {
                const newBadge = document.createElement('span');
                newBadge.className = 'cart-badge';
                newBadge.textContent = count;
                cartBtn.appendChild(newBadge);
            }
        } else if (badge) {
            badge.remove();
        }
    }
    
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }
    
    function showToast(message, type = 'success') {
        const existingToast = document.querySelector('.marketplace-toast');
        if (existingToast) existingToast.remove();
        
        const colors = {
            success: { border: 'rgba(34, 197, 94, 0.4)', bg: 'rgba(34, 197, 94, 0.2)', color: '#22c55e' },
            info: { border: 'rgba(0, 229, 255, 0.4)', bg: 'rgba(0, 229, 255, 0.2)', color: '#00E5FF' },
            error: { border: 'rgba(239, 68, 68, 0.4)', bg: 'rgba(239, 68, 68, 0.2)', color: '#ef4444' }
        };
        const c = colors[type] || colors.info;
        
        const toast = document.createElement('div');
        toast.className = 'marketplace-toast';
        toast.innerHTML = `
            <div style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 99999; animation: toastIn 0.3s ease;">
                <div style="background: linear-gradient(135deg, rgba(13, 27, 42, 0.98), rgba(0, 0, 34, 0.98)); border: 1px solid ${c.border}; border-radius: 12px; padding: 14px 20px; display: flex; align-items: center; gap: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.5);">
                    <div style="width: 32px; height: 32px; background: ${c.bg}; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: ${c.color};">
                        <i class="fas fa-${type === 'error' ? 'exclamation-circle' : type === 'info' ? 'info-circle' : 'check-circle'}"></i>
                    </div>
                    <span style="color: #fff; font-size: 14px; font-weight: 500;">${message}</span>
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'toastOut 0.3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    setupMarketplaceUpdates();
});
</script>
<style>
@keyframes highlightProduct {
    0% { box-shadow: 0 0 30px rgba(0, 229, 255, 0.5); }
    100% { box-shadow: none; }
}
@keyframes fadeOutProduct {
    0% { opacity: 1; transform: scale(1); }
    100% { opacity: 0; transform: scale(0.8); }
}
@keyframes toastIn { from { opacity: 0; transform: translateX(-50%) translateY(-20px); } to { opacity: 1; transform: translateX(-50%) translateY(0); } }
@keyframes toastOut { from { opacity: 1; transform: translateX(-50%) translateY(0); } to { opacity: 0; transform: translateX(-50%) translateY(-20px); } }
</style>
@endpush
