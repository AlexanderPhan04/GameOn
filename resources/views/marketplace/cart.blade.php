@extends('layouts.app')

@section('title', 'Giỏ hàng')

@push('styles')
<style>
    .cart-page {
        min-height: calc(100vh - 64px);
        background: linear-gradient(180deg, #000814 0%, #0d1b2a 100%);
        padding: 2rem;
    }
    
    .cart-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .cart-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .cart-header-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.5rem;
        box-shadow: 0 8px 24px rgba(99, 102, 241, 0.3);
    }
    
    .cart-header-text h1 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }
    
    .cart-header-text p {
        color: #94a3b8;
        font-size: 0.9rem;
        margin: 0;
    }
    
    /* Cart Items */
    .cart-items {
        background: linear-gradient(135deg, rgba(13, 27, 42, 0.95), rgba(0, 0, 34, 0.95));
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .cart-item {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1.25rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        transition: background 0.2s;
        position: relative;
    }
    
    .cart-item.restricted {
        opacity: 0.7;
        background: rgba(239, 68, 68, 0.05);
    }
    
    .cart-item.restricted:hover {
        background: rgba(239, 68, 68, 0.08);
    }
    
    .restricted-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        z-index: 1;
    }
    
    .restricted-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: repeating-linear-gradient(
            45deg,
            transparent,
            transparent 10px,
            rgba(239, 68, 68, 0.03) 10px,
            rgba(239, 68, 68, 0.03) 20px
        );
        pointer-events: none;
        border-radius: inherit;
    }
    
    .cart-item:last-child {
        border-bottom: none;
    }
    
    .cart-item:hover {
        background: rgba(0, 229, 255, 0.03);
    }
    
    .cart-item-image {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        overflow: hidden;
        flex-shrink: 0;
        background: linear-gradient(135deg, #1e3a5f, #0d1b2a);
    }
    
    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .cart-item-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .cart-item-image-placeholder i {
        font-size: 1.5rem;
        color: rgba(0, 229, 255, 0.3);
    }
    
    .cart-item-info {
        flex: 1;
        min-width: 0;
    }
    
    .cart-item-name {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: #fff;
        margin: 0 0 0.25rem 0;
    }
    
    .cart-item-type {
        color: #64748b;
        font-size: 0.8rem;
    }
    
    .cart-item-price {
        text-align: right;
        min-width: 120px;
    }
    
    .price-unit {
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
    }
    
    .price-subtotal {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #22c55e;
    }
    
    .cart-item-quantity {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 140px;
    }
    
    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 0;
        background: rgba(0, 0, 20, 0.6);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        overflow: hidden;
    }
    
    .quantity-btn {
        width: 32px;
        height: 32px;
        background: rgba(0, 229, 255, 0.1);
        border: none;
        color: #00E5FF;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        font-size: 0.85rem;
    }
    
    .quantity-btn:hover:not(:disabled) {
        background: rgba(0, 229, 255, 0.25);
        color: #fff;
    }
    
    .quantity-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    .quantity-input {
        width: 45px;
        height: 32px;
        background: transparent;
        border: none;
        border-left: 1px solid rgba(0, 229, 255, 0.2);
        border-right: 1px solid rgba(0, 229, 255, 0.2);
        color: #fff;
        font-weight: 600;
        font-size: 0.95rem;
        text-align: center;
        -moz-appearance: textfield;
    }
    
    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    .quantity-input:focus {
        outline: none;
        background: rgba(0, 229, 255, 0.05);
    }
    
    .cart-item-remove {
        width: 36px;
        height: 36px;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 10px;
        color: #ef4444;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    
    .cart-item-remove:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #fff;
    }
    
    /* Cart Summary */
    .cart-summary {
        background: linear-gradient(135deg, rgba(13, 27, 42, 0.95), rgba(0, 0, 34, 0.95));
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 20px;
        padding: 1.5rem;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    }
    
    .summary-row:last-of-type {
        border-bottom: none;
        padding-top: 1rem;
        margin-top: 0.5rem;
    }
    
    .summary-label {
        color: #94a3b8;
        font-size: 0.95rem;
    }
    
    .summary-value {
        color: #fff;
        font-weight: 600;
    }
    
    .summary-total-label {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: #fff;
    }
    
    .summary-total-value {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #22c55e;
    }
    
    .cart-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .btn-continue {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: #94a3b8;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .btn-continue:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }
    
    .btn-checkout {
        flex: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 2rem;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border: none;
        border-radius: 12px;
        color: #fff;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 4px 20px rgba(34, 197, 94, 0.3);
    }
    
    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(34, 197, 94, 0.5);
        color: #fff;
    }
    
    /* Empty State */
    .empty-cart {
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
        margin-bottom: 1.5rem;
    }
    
    .btn-shop {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 2rem;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none;
        border-radius: 12px;
        color: #fff;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 4px 20px rgba(99, 102, 241, 0.3);
    }
    
    .btn-shop:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(99, 102, 241, 0.5);
        color: #fff;
    }
    
    @media (max-width: 768px) {
        .cart-page {
            padding: 1rem;
        }
        
        .cart-item {
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .cart-item-price {
            text-align: left;
            min-width: auto;
        }
        
        .cart-item-quantity {
            min-width: auto;
        }
        
        .cart-actions {
            flex-direction: column;
        }
        
        .btn-continue, .btn-checkout {
            flex: none;
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="cart-page">
    <div class="cart-container">
        <div class="cart-header">
            <div class="cart-header-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="cart-header-text">
                <h1>Giỏ hàng</h1>
                <p>{{ count($items) }} sản phẩm trong giỏ hàng</p>
            </div>
        </div>
        
        @if(count($items) > 0)
        
        @if($hasRestrictedItems ?? false)
        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-exclamation-triangle" style="color: #ef4444; font-size: 1.25rem;"></i>
            <div>
                <p style="color: #fff; margin: 0; font-weight: 600;">Một số vật phẩm đang bị hạn chế</p>
                <p style="color: #94a3b8; margin: 0; font-size: 0.85rem;">Các vật phẩm này sẽ không được tính vào đơn hàng. Bạn có thể xóa hoặc giữ lại chờ mở bán.</p>
            </div>
        </div>
        @endif
        
        <div class="cart-items">
            @foreach($items as $item)
            <div class="cart-item {{ ($item['is_restricted'] ?? false) ? 'restricted' : '' }}" data-id="{{ $item['product']->id }}">
                @if($item['is_restricted'] ?? false)
                <div class="restricted-badge">
                    <i class="fas fa-ban"></i>
                    Đang hạn chế
                </div>
                <div class="restricted-overlay"></div>
                @endif
                
                <div class="cart-item-image">
                    @if($item['product']->thumbnail)
                        <img src="{{ asset('uploads/' . $item['product']->thumbnail) }}" alt="{{ $item['product']->name }}">
                    @else
                        <div class="cart-item-image-placeholder">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif
                </div>
                
                <div class="cart-item-info">
                    <h3 class="cart-item-name">{{ $item['product']->name }}</h3>
                    <span class="cart-item-type">
                        @if($item['product']->type == 'theme') Giao diện
                        @elseif($item['product']->type == 'sticker') Sticker
                        @elseif($item['product']->type == 'game_item') Vật phẩm
                        @else {{ ucfirst($item['product']->type) }}
                        @endif
                    </span>
                </div>
                
                <div class="cart-item-quantity">
                    @if($item['is_restricted'] ?? false)
                    <div class="quantity-controls" style="opacity: 0.5; pointer-events: none;">
                        <button type="button" class="quantity-btn" disabled>
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" class="quantity-input" value="{{ $item['quantity'] }}" disabled>
                        <button type="button" class="quantity-btn" disabled>
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    @else
                    <div class="quantity-controls">
                        <button type="button" class="quantity-btn quantity-decrease" data-id="{{ $item['product']->id }}" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" class="quantity-input" 
                               value="{{ $item['quantity'] }}" 
                               min="1" 
                               max="{{ $item['product']->stock > 0 ? $item['product']->stock : 999 }}"
                               data-id="{{ $item['product']->id }}"
                               data-stock="{{ $item['product']->stock }}"
                               data-original="{{ $item['quantity'] }}">
                        <button type="button" class="quantity-btn quantity-increase" data-id="{{ $item['product']->id }}" data-stock="{{ $item['product']->stock }}" {{ ($item['product']->stock > 0 && $item['quantity'] >= $item['product']->stock) ? 'disabled' : '' }}>
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    @endif
                </div>
                
                <div class="cart-item-price">
                    @if($item['is_restricted'] ?? false)
                    <div class="price-unit" style="text-decoration: line-through; color: #64748b;">{{ number_format($item['product']->current_price ?? $item['product']->price, 0, ',', '.') }} đ</div>
                    <div class="price-subtotal" style="color: #ef4444; font-size: 0.9rem;">Không khả dụng</div>
                    @else
                    <div class="price-unit">{{ number_format($item['product']->current_price ?? $item['product']->price, 0, ',', '.') }} đ</div>
                    <div class="price-subtotal">{{ number_format($item['subtotal'], 0, ',', '.') }} đ</div>
                    @endif
                </div>
                
                <button class="cart-item-remove" data-id="{{ $item['product']->id }}" title="Xóa">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            @endforeach
        </div>
        
        <div class="cart-summary">
            <div class="summary-row">
                <span class="summary-label">Tạm tính ({{ collect($items)->where('is_restricted', false)->count() }} sản phẩm khả dụng)</span>
                <span class="summary-value">{{ number_format($total, 0, ',', '.') }} đ</span>
            </div>
            <div class="summary-row">
                <span class="summary-total-label">Tổng cộng</span>
                <span class="summary-total-value">{{ number_format($total, 0, ',', '.') }} đ</span>
            </div>
            
            <div class="cart-actions">
                <a href="{{ route('marketplace.index') }}" class="btn-continue">
                    <i class="fas fa-arrow-left"></i>
                    Tiếp tục mua
                </a>
                @if($total > 0)
                <a href="{{ route('marketplace.checkout') }}" class="btn-checkout">
                    <i class="fas fa-credit-card"></i>
                    Thanh toán ngay
                </a>
                @else
                <button class="btn-checkout" disabled style="opacity: 0.5; cursor: not-allowed;">
                    <i class="fas fa-credit-card"></i>
                    Không có sản phẩm khả dụng
                </button>
                @endif
            </div>
        </div>
        
        @else
        <div class="empty-cart">
            <div class="empty-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3 class="empty-title">Giỏ hàng trống</h3>
            <p class="empty-desc">Bạn chưa thêm sản phẩm nào vào giỏ hàng</p>
            <a href="{{ route('marketplace.index') }}" class="btn-shop">
                <i class="fas fa-store"></i>
                Mua sắm ngay
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.cart-item-remove').forEach(btn => {
    btn.addEventListener('click', async function() {
        const productId = this.dataset.id;
        const cartItem = this.closest('.cart-item');
        
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        this.disabled = true;
        
        try {
            const response = await fetch(`/marketplace/cart/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            });
            const data = await response.json();
            if (data.success) {
                cartItem.style.animation = 'fadeOut 0.3s ease forwards';
                setTimeout(() => location.reload(), 300);
            }
        } catch (error) {
            this.innerHTML = '<i class="fas fa-trash"></i>';
            this.disabled = false;
        }
    });
});

// Quantity decrease button
document.querySelectorAll('.quantity-decrease').forEach(btn => {
    btn.addEventListener('click', async function() {
        const productId = this.dataset.id;
        const input = this.parentElement.querySelector('.quantity-input');
        const currentQty = parseInt(input.value);
        
        if (currentQty <= 1) return;
        
        await updateQuantity(productId, currentQty - 1, this);
    });
});

// Quantity increase button
document.querySelectorAll('.quantity-increase').forEach(btn => {
    btn.addEventListener('click', async function() {
        const productId = this.dataset.id;
        const input = this.parentElement.querySelector('.quantity-input');
        const currentQty = parseInt(input.value);
        const stock = parseInt(this.dataset.stock);
        
        if (stock > 0 && currentQty >= stock) {
            showCartToast(`Kho không đủ! Chỉ còn ${stock} sản phẩm`, 'error');
            return;
        }
        
        await updateQuantity(productId, currentQty + 1, this);
    });
});

// Direct input change
document.querySelectorAll('.quantity-input').forEach(input => {
    let debounceTimer;
    
    input.addEventListener('change', function() {
        clearTimeout(debounceTimer);
        const productId = this.dataset.id;
        const stock = parseInt(this.dataset.stock);
        const originalQty = parseInt(this.dataset.original);
        let newQty = parseInt(this.value);
        
        // Validate
        if (isNaN(newQty) || newQty < 1) {
            newQty = 1;
            this.value = 1;
        }
        
        if (stock > 0 && newQty > stock) {
            showCartToast(`Kho không đủ! Chỉ còn ${stock} sản phẩm`, 'error');
            newQty = stock;
            this.value = stock;
        }
        
        if (newQty === originalQty) return;
        
        updateQuantity(productId, newQty, this);
    });
    
    // Debounce on keyup for better UX
    input.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            this.blur();
        }
    });
});

async function updateQuantity(productId, newQuantity, element) {
    const cartItem = element.closest('.cart-item');
    const controls = cartItem.querySelector('.quantity-controls');
    const input = cartItem.querySelector('.quantity-input');
    const decreaseBtn = cartItem.querySelector('.quantity-decrease');
    const increaseBtn = cartItem.querySelector('.quantity-increase');
    const stock = parseInt(increaseBtn.dataset.stock);
    
    // Show loading
    controls.style.opacity = '0.6';
    controls.style.pointerEvents = 'none';
    
    try {
        const response = await fetch(`/marketplace/cart/${productId}`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ quantity: newQuantity })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update input value and original
            input.value = data.quantity;
            input.dataset.original = data.quantity;
            
            // Update subtotal
            const subtotalEl = cartItem.querySelector('.price-subtotal');
            subtotalEl.textContent = formatPrice(data.subtotal) + ' đ';
            
            // Update total
            const totalEl = document.querySelector('.summary-total-value');
            const subtotalSummary = document.querySelector('.summary-value');
            if (totalEl) totalEl.textContent = formatPrice(data.total) + ' đ';
            if (subtotalSummary) subtotalSummary.textContent = formatPrice(data.total) + ' đ';
            
            // Update button states
            decreaseBtn.disabled = data.quantity <= 1;
            increaseBtn.disabled = stock > 0 && data.quantity >= stock;
            
            // Update cart badge
            updateCartBadge(data.cart_count);
            
            showCartToast('Đã cập nhật số lượng', 'success');
        } else {
            showCartToast(data.message || 'Có lỗi xảy ra', 'error');
            input.value = input.dataset.original;
        }
    } catch (error) {
        showCartToast('Có lỗi xảy ra', 'error');
        input.value = input.dataset.original;
    } finally {
        controls.style.opacity = '1';
        controls.style.pointerEvents = 'auto';
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}

// Real-time cart updates
@auth
function setupCartUpdates() {
    if (typeof window.Echo === 'undefined') {
        setTimeout(setupCartUpdates, 500);
        return;
    }
    
    window.Echo.private('user.{{ Auth::id() }}')
        .listen('.cart.updated', (e) => {
            // Update cart badge in navbar
            updateCartBadge(e.cart_count);
            
            // Show toast notification
            if (e.action === 'add') {
                showCartToast(`Đã thêm "${e.product_name}" vào giỏ hàng`, 'success');
            }
        });
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
    
    // Update header text
    const headerText = document.querySelector('.cart-header-text p');
    if (headerText) {
        headerText.textContent = `${count} sản phẩm trong giỏ hàng`;
    }
}

function showCartToast(message, type) {
    const existingToast = document.querySelector('.cart-toast');
    if (existingToast) existingToast.remove();
    
    const toast = document.createElement('div');
    toast.className = 'cart-toast';
    toast.innerHTML = `
        <div style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 99999; animation: toastIn 0.3s ease;">
            <div style="background: linear-gradient(135deg, rgba(13, 27, 42, 0.98), rgba(0, 0, 34, 0.98)); border: 1px solid rgba(34, 197, 94, 0.4); border-radius: 12px; padding: 14px 20px; display: flex; align-items: center; gap: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.5);">
                <div style="width: 32px; height: 32px; background: rgba(34, 197, 94, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #22c55e;">
                    <i class="fas fa-check-circle"></i>
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

setupCartUpdates();
@endauth
</script>
<style>
@keyframes fadeOut {
    to { opacity: 0; transform: translateX(-20px); }
}
@keyframes toastIn { from { opacity: 0; transform: translateX(-50%) translateY(-20px); } to { opacity: 1; transform: translateX(-50%) translateY(0); } }
@keyframes toastOut { from { opacity: 1; transform: translateX(-50%) translateY(0); } to { opacity: 0; transform: translateX(-50%) translateY(-20px); } }
</style>
@endpush
