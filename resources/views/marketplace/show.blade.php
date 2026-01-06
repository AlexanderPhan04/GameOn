@extends('layouts.app')

@section('title', $product->name . ' - Marketplace')

@push('styles')
<style>
    .product-detail-page {
        min-height: calc(100vh - 64px);
        background: linear-gradient(180deg, #000814 0%, #0d1b2a 100%);
        padding: 2rem;
    }
    
    .product-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    /* Breadcrumb */
    .breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 2rem;
        font-size: 0.9rem;
    }
    
    .breadcrumb-nav a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .breadcrumb-nav a:hover {
        color: #00E5FF;
    }
    
    .breadcrumb-nav span {
        color: #64748b;
    }
    
    .breadcrumb-nav .current {
        color: #fff;
    }
    
    /* Main Content */
    .product-main {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }
    
    /* Image Section */
    .product-image-section {
        position: relative;
    }
    
    .product-image-wrapper {
        background: linear-gradient(135deg, rgba(13, 27, 42, 0.95), rgba(0, 0, 34, 0.95));
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 24px;
        overflow: hidden;
        aspect-ratio: 4/3;
    }
    
    .product-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .product-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #1e3a5f, #0d1b2a);
    }
    
    .product-image-placeholder i {
        font-size: 5rem;
        color: rgba(0, 229, 255, 0.2);
    }
    
    .product-badges {
        position: absolute;
        top: 1rem;
        left: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .badge-featured {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
    }
    
    .badge-discount {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
    }
    
    /* Info Section */
    .product-info-section {
        display: flex;
        flex-direction: column;
    }
    
    .product-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 2.25rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 1rem 0;
        line-height: 1.2;
    }
    
    .product-price-box {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(22, 163, 74, 0.1));
        border: 1px solid rgba(34, 197, 94, 0.2);
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    
    .price-label {
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
    }
    
    .price-row {
        display: flex;
        align-items: baseline;
        gap: 1rem;
    }
    
    .price-current {
        font-family: 'Rajdhani', sans-serif;
        font-size: 2.5rem;
        font-weight: 700;
        color: #22c55e;
    }
    
    .price-old {
        font-size: 1.25rem;
        color: #64748b;
        text-decoration: line-through;
    }
    
    .price-save {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .product-description {
        color: #94a3b8;
        font-size: 1rem;
        line-height: 1.7;
        margin-bottom: 1.5rem;
    }
    
    .product-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    
    .tag {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .tag-type {
        background: rgba(139, 92, 246, 0.15);
        border: 1px solid rgba(139, 92, 246, 0.3);
        color: #a78bfa;
    }
    
    .tag-category {
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.3);
        color: #00E5FF;
    }
    
    .tag-rarity {
        background: rgba(245, 158, 11, 0.15);
        border: 1px solid rgba(245, 158, 11, 0.3);
        color: #fbbf24;
    }
    
    /* Owned Alert */
    .owned-alert {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(22, 163, 74, 0.1));
        border: 1px solid rgba(34, 197, 94, 0.3);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #22c55e;
        font-weight: 500;
    }
    
    .owned-alert i {
        font-size: 1.25rem;
    }
    
    /* Add to Cart Form */
    .cart-form {
        background: linear-gradient(135deg, rgba(13, 27, 42, 0.8), rgba(0, 0, 34, 0.8));
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        padding: 1.5rem;
        margin-top: auto;
    }
    
    .quantity-group {
        margin-bottom: 1.25rem;
    }
    
    .quantity-label {
        color: #94a3b8;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .quantity-input-wrapper {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .quantity-btn {
        width: 40px;
        height: 40px;
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.3);
        border-radius: 10px;
        color: #00E5FF;
        font-size: 1.25rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .quantity-btn:hover {
        background: rgba(0, 229, 255, 0.2);
    }
    
    .quantity-input {
        width: 80px;
        height: 40px;
        background: rgba(0, 0, 20, 0.6);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        color: #fff;
        font-size: 1rem;
        text-align: center;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
    }
    
    .quantity-input:focus {
        outline: none;
        border-color: #00E5FF;
    }
    
    .btn-add-cart {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none;
        border-radius: 12px;
        color: #fff;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(99, 102, 241, 0.4);
    }
    
    .btn-add-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(99, 102, 241, 0.5);
    }
    
    .btn-add-cart:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    /* Related Products */
    .related-section {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
    }
    
    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    
    .section-title i {
        color: #00E5FF;
    }
    
    .section-title h2 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }
    
    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.25rem;
    }
    
    .related-card {
        background: linear-gradient(135deg, rgba(13, 27, 42, 0.95), rgba(0, 0, 34, 0.95));
        border: 1px solid rgba(0, 229, 255, 0.12);
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .related-card:hover {
        transform: translateY(-4px);
        border-color: rgba(0, 229, 255, 0.3);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
    }
    
    .related-card-image {
        height: 140px;
        overflow: hidden;
        background: linear-gradient(135deg, #1e3a5f, #0d1b2a);
    }
    
    .related-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .related-card-body {
        padding: 1rem;
    }
    
    .related-card-name {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: #fff;
        margin: 0 0 0.5rem 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .related-card-price {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #22c55e;
    }
    
    /* Toast */
    .toast-notification {
        position: fixed;
        top: 80px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 99999;
        animation: toastSlideIn 0.3s ease;
    }
    
    .toast-content {
        background: linear-gradient(135deg, rgba(13, 27, 42, 0.98), rgba(0, 0, 34, 0.98));
        border: 1px solid rgba(34, 197, 94, 0.4);
        border-radius: 12px;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.5), 0 0 20px rgba(34, 197, 94, 0.2);
        backdrop-filter: blur(10px);
    }
    
    .toast-icon {
        width: 32px;
        height: 32px;
        background: rgba(34, 197, 94, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #22c55e;
    }
    
    .toast-message {
        color: #fff;
        font-size: 14px;
        font-weight: 500;
    }
    
    @keyframes toastSlideIn {
        from { opacity: 0; transform: translateX(-50%) translateY(-20px); }
        to { opacity: 1; transform: translateX(-50%) translateY(0); }
    }
    
    @keyframes toastSlideOut {
        from { opacity: 1; transform: translateX(-50%) translateY(0); }
        to { opacity: 0; transform: translateX(-50%) translateY(-20px); }
    }
    
    @media (max-width: 968px) {
        .product-main {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
    }
    
    @media (max-width: 768px) {
        .product-detail-page {
            padding: 1rem;
        }
        
        .product-title {
            font-size: 1.75rem;
        }
        
        .price-current {
            font-size: 2rem;
        }
        
        .related-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="product-detail-page">
    <div class="product-container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb-nav">
            <a href="{{ route('marketplace.index') }}"><i class="fas fa-store"></i> Marketplace</a>
            <span>/</span>
            <span class="current">{{ $product->name }}</span>
        </nav>
        
        <!-- Main Content -->
        <div class="product-main">
            <!-- Image Section -->
            <div class="product-image-section">
                <div class="product-image-wrapper">
                    @if($product->thumbnail)
                        <img src="{{ asset('uploads/' . $product->thumbnail) }}" alt="{{ $product->name }}">
                    @else
                        <div class="product-image-placeholder">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif
                </div>
                
                <div class="product-badges">
                    @if($product->is_featured)
                        <span class="badge-featured"><i class="fas fa-star"></i> Nổi bật</span>
                    @endif
                    @if($product->hasDiscount())
                        <span class="badge-discount">-{{ $product->discount_percent ?? round((1 - $product->current_price / $product->price) * 100) }}%</span>
                    @endif
                </div>
            </div>
            
            <!-- Info Section -->
            <div class="product-info-section">
                <h1 class="product-title">{{ $product->name }}</h1>
                
                <div class="product-price-box">
                    <div class="price-label">Giá bán</div>
                    <div class="price-row">
                        <span class="price-current">{{ number_format($product->current_price ?? $product->price, 0, ',', '.') }} đ</span>
                        @if($product->hasDiscount())
                            <span class="price-old">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                            <span class="price-save">Tiết kiệm {{ number_format($product->price - ($product->current_price ?? $product->price), 0, ',', '.') }} đ</span>
                        @endif
                    </div>
                </div>
                
                @if($product->description)
                <p class="product-description">{{ $product->description }}</p>
                @endif
                
                <div class="product-tags">
                    <span class="tag tag-type">
                        <i class="fas fa-tag"></i>
                        @if($product->type == 'theme') Giao diện
                        @elseif($product->type == 'sticker') Sticker
                        @elseif($product->type == 'game_item') Vật phẩm
                        @else {{ ucfirst($product->type) }}
                        @endif
                    </span>
                    @if($product->category)
                    <span class="tag tag-category">
                        <i class="fas fa-folder"></i>
                        {{ ucfirst($product->category) }}
                    </span>
                    @endif
                    @if($product->rarity)
                    <span class="tag tag-rarity">
                        <i class="fas fa-gem"></i>
                        {{ ucfirst($product->rarity) }}
                    </span>
                    @endif
                </div>
                
                @if($owned)
                    <div class="owned-alert">
                        <i class="fas fa-check-circle"></i>
                        <span>Bạn đã sở hữu sản phẩm này</span>
                    </div>
                @else
                    <div class="cart-form">
                        <form id="addToCartForm">
                            @csrf
                            <div class="quantity-group">
                                <label class="quantity-label">Số lượng</label>
                                <div class="quantity-input-wrapper">
                                    <button type="button" class="quantity-btn" id="decreaseQty">−</button>
                                    <input type="number" name="quantity" value="1" min="1" max="99" class="quantity-input" id="quantityInput">
                                    <button type="button" class="quantity-btn" id="increaseQty">+</button>
                                </div>
                            </div>
                            <button type="submit" class="btn-add-cart" id="addToCartBtn">
                                <i class="fas fa-cart-plus"></i>
                                Thêm vào giỏ hàng
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="related-section">
            <div class="section-title">
                <i class="fas fa-th-large"></i>
                <h2>Sản phẩm liên quan</h2>
            </div>
            <div class="related-grid">
                @foreach($relatedProducts as $related)
                <a href="{{ route('marketplace.show', $related) }}" class="related-card">
                    <div class="related-card-image">
                        @if($related->thumbnail)
                            <img src="{{ asset('uploads/' . $related->thumbnail) }}" alt="{{ $related->name }}">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image" style="font-size: 2rem; color: rgba(0, 229, 255, 0.2);"></i>
                            </div>
                        @endif
                    </div>
                    <div class="related-card-body">
                        <h3 class="related-card-name">{{ $related->name }}</h3>
                        <div class="related-card-price">{{ number_format($related->current_price ?? $related->price, 0, ',', '.') }} đ</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantityInput');
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const addToCartForm = document.getElementById('addToCartForm');
    const addToCartBtn = document.getElementById('addToCartBtn');
    
    if (decreaseBtn && increaseBtn && quantityInput) {
        decreaseBtn.addEventListener('click', function() {
            let val = parseInt(quantityInput.value) || 1;
            if (val > 1) quantityInput.value = val - 1;
        });
        
        increaseBtn.addEventListener('click', function() {
            let val = parseInt(quantityInput.value) || 1;
            if (val < 99) quantityInput.value = val + 1;
        });
    }
    
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const quantity = quantityInput ? quantityInput.value : 1;
            const originalText = addToCartBtn.innerHTML;
            
            addToCartBtn.disabled = true;
            addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';
            
            try {
                const response = await fetch('{{ route("marketplace.addToCart", $product->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ quantity: quantity })
                });
                
                // Check if redirected (unauthenticated) - fetch follows redirects automatically
                if (response.redirected) {
                    showToast('Vui lòng đăng nhập để thêm vào giỏ hàng!', 'error');
                    setTimeout(() => window.location.href = '{{ route("auth.login") }}', 1500);
                    return;
                }
                
                // Check for auth error (401) or other errors
                if (response.status === 401 || response.status === 403) {
                    showToast('Vui lòng đăng nhập để thêm vào giỏ hàng!', 'error');
                    setTimeout(() => window.location.href = '{{ route("auth.login") }}', 1500);
                    return;
                }
                
                const data = await response.json();
                
                if (data.success) {
                    showToast('Đã thêm vào giỏ hàng!', 'success');
                    // Update cart badge in navbar
                    if (data.cart_count) {
                        updateCartBadge(data.cart_count);
                    }
                } else {
                    showToast(data.message || 'Có lỗi xảy ra', 'error');
                }
            } catch (error) {
                console.error('Add to cart error:', error);
                showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
            } finally {
                addToCartBtn.disabled = false;
                addToCartBtn.innerHTML = originalText;
            }
        });
    }
    
    function showToast(message, type) {
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) existingToast.remove();
        
        const borderColor = type === 'success' ? 'rgba(34, 197, 94, 0.4)' : 'rgba(239, 68, 68, 0.4)';
        const iconBg = type === 'success' ? 'rgba(34, 197, 94, 0.2)' : 'rgba(239, 68, 68, 0.2)';
        const iconColor = type === 'success' ? '#22c55e' : '#ef4444';
        const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
        
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = `
            <div class="toast-content" style="border-color: ${borderColor};">
                <div class="toast-icon" style="background: ${iconBg}; color: ${iconColor};">
                    <i class="fas fa-${icon}"></i>
                </div>
                <span class="toast-message">${message}</span>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'toastSlideOut 0.3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    // Real-time product updates
    function setupProductUpdates() {
        if (typeof window.Echo === 'undefined') {
            setTimeout(setupProductUpdates, 500);
            return;
        }
        
        window.Echo.channel('marketplace')
            .listen('.product.updated', (e) => {
                if (e.product.id === {{ $product->id }}) {
                    if (e.action === 'updated') {
                        // Update product info on page
                        updateProductInfo(e.product);
                        showToast('Thông tin sản phẩm đã được cập nhật', 'info');
                    }
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
    
    function updateProductInfo(product) {
        const titleEl = document.querySelector('.product-title');
        const priceEl = document.querySelector('.price-current');
        
        if (titleEl) titleEl.textContent = product.name;
        if (priceEl) priceEl.textContent = formatPrice(product.current_price) + ' đ';
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
    
    setupProductUpdates();
});
</script>
@endpush
