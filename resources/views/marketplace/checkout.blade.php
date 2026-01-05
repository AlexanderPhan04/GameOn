@extends('layouts.app')

@section('title', 'Thanh toán')

@push('styles')
<style>
    .checkout-page {
        min-height: calc(100vh - 64px);
        background: linear-gradient(180deg, #000814 0%, #0d1b2a 100%);
        padding: 2rem;
    }
    
    .checkout-container {
        max-width: 1100px;
        margin: 0 auto;
    }
    
    .checkout-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .checkout-header-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.5rem;
        box-shadow: 0 8px 24px rgba(34, 197, 94, 0.3);
    }
    
    .checkout-header-text h1 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }
    
    .checkout-header-text p {
        color: #94a3b8;
        font-size: 0.9rem;
        margin: 0;
    }
    
    .checkout-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 1.5rem;
    }
    
    /* Order Summary Card */
    .order-card {
        background: linear-gradient(135deg, rgba(13, 27, 42, 0.95), rgba(0, 0, 34, 0.95));
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 20px;
        overflow: hidden;
    }
    
    .order-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .order-card-header i {
        color: #00E5FF;
        font-size: 1.1rem;
    }
    
    .order-card-header h3 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: #fff;
        margin: 0;
    }
    
    /* Order Items */
    .order-items {
        padding: 0;
    }
    
    .order-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.08);
        transition: background 0.2s;
    }
    
    .order-item:last-child {
        border-bottom: none;
    }
    
    .order-item:hover {
        background: rgba(0, 229, 255, 0.03);
    }
    
    .order-item-image {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
        background: linear-gradient(135deg, #1e3a5f, #0d1b2a);
    }
    
    .order-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .order-item-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(0, 229, 255, 0.3);
    }
    
    .order-item-info {
        flex: 1;
        min-width: 0;
    }
    
    .order-item-name {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: #fff;
        margin: 0 0 0.25rem 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .order-item-meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.85rem;
        color: #64748b;
    }
    
    .order-item-qty {
        background: rgba(0, 229, 255, 0.1);
        padding: 0.15rem 0.5rem;
        border-radius: 4px;
        color: #00E5FF;
        font-weight: 500;
    }
    
    .order-item-price {
        text-align: right;
        min-width: 100px;
    }
    
    .order-item-unit {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 0.15rem;
    }
    
    .order-item-subtotal {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #22c55e;
    }
    
    /* Order Total */
    .order-total {
        padding: 1.25rem 1.5rem;
        background: rgba(0, 229, 255, 0.03);
        border-top: 1px solid rgba(0, 229, 255, 0.1);
    }
    
    .order-total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .order-total-row:last-child {
        margin-bottom: 0;
        padding-top: 0.75rem;
        border-top: 1px dashed rgba(0, 229, 255, 0.15);
    }
    
    .order-total-label {
        color: #94a3b8;
        font-size: 0.9rem;
    }
    
    .order-total-value {
        color: #fff;
        font-weight: 600;
    }
    
    .order-total-final {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: #fff;
    }
    
    .order-total-amount {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #22c55e;
    }
    
    /* Payment Card */
    .payment-card {
        background: linear-gradient(135deg, rgba(13, 27, 42, 0.95), rgba(0, 0, 34, 0.95));
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 20px;
        overflow: hidden;
        height: fit-content;
    }
    
    .payment-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .payment-card-header i {
        color: #22c55e;
        font-size: 1.1rem;
    }
    
    .payment-card-header h3 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: #fff;
        margin: 0;
    }
    
    .payment-card-body {
        padding: 1.5rem;
    }
    
    /* Payment Method */
    .payment-method {
        background: rgba(34, 197, 94, 0.08);
        border: 2px solid rgba(34, 197, 94, 0.3);
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .payment-method:hover {
        border-color: rgba(34, 197, 94, 0.5);
        background: rgba(34, 197, 94, 0.12);
    }
    
    .payment-method.selected {
        border-color: #22c55e;
        background: rgba(34, 197, 94, 0.15);
    }
    
    .payment-method-radio {
        width: 20px;
        height: 20px;
        border: 2px solid rgba(34, 197, 94, 0.5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .payment-method.selected .payment-method-radio {
        border-color: #22c55e;
    }
    
    .payment-method.selected .payment-method-radio::after {
        content: '';
        width: 10px;
        height: 10px;
        background: #22c55e;
        border-radius: 50%;
    }
    
    .payment-method-logo {
        width: 60px;
        height: 36px;
        background: #fff;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.25rem;
    }
    
    .payment-method-logo img {
        max-width: 100%;
        max-height: 100%;
    }
    
    .payment-method-info h4 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: #fff;
        margin: 0 0 0.15rem 0;
    }
    
    .payment-method-info p {
        font-size: 0.8rem;
        color: #64748b;
        margin: 0;
    }
    
    /* Notes */
    .notes-group {
        margin-bottom: 1.5rem;
    }
    
    .notes-label {
        display: block;
        font-size: 0.9rem;
        color: #94a3b8;
        margin-bottom: 0.5rem;
    }
    
    .notes-input {
        width: 100%;
        background: rgba(0, 0, 20, 0.5);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        color: #fff;
        font-size: 0.9rem;
        resize: none;
        transition: all 0.2s;
    }
    
    .notes-input:focus {
        outline: none;
        border-color: rgba(0, 229, 255, 0.4);
        background: rgba(0, 0, 20, 0.7);
    }
    
    .notes-input::placeholder {
        color: #475569;
    }
    
    /* Security Note */
    .security-note {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem;
        background: rgba(0, 229, 255, 0.05);
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
    
    .security-note i {
        color: #00E5FF;
        font-size: 0.9rem;
    }
    
    .security-note span {
        font-size: 0.8rem;
        color: #94a3b8;
    }
    
    /* Submit Button */
    .btn-checkout {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border: none;
        border-radius: 12px;
        color: #fff;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 20px rgba(34, 197, 94, 0.3);
    }
    
    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(34, 197, 94, 0.5);
    }
    
    .btn-checkout:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }
    
    .btn-checkout i {
        font-size: 1rem;
    }
    
    /* Back Link */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 0.9rem;
        text-decoration: none;
        margin-top: 1rem;
        transition: color 0.2s;
    }
    
    .back-link:hover {
        color: #00E5FF;
    }
    
    @media (max-width: 900px) {
        .checkout-page {
            padding: 1rem;
        }
        
        .checkout-grid {
            grid-template-columns: 1fr;
        }
        
        .order-item {
            flex-wrap: wrap;
        }
        
        .order-item-price {
            width: 100%;
            text-align: left;
            margin-top: 0.5rem;
            padding-left: 76px;
        }
    }
</style>
@endpush

@section('content')
<div class="checkout-page">
    <div class="checkout-container">
        <div class="checkout-header">
            <div class="checkout-header-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <div class="checkout-header-text">
                <h1>Thanh toán</h1>
                <p>Xác nhận đơn hàng và hoàn tất thanh toán</p>
            </div>
        </div>
        
        <div class="checkout-grid">
            <!-- Order Summary -->
            <div class="order-card">
                <div class="order-card-header">
                    <i class="fas fa-shopping-bag"></i>
                    <h3>Thông tin đơn hàng ({{ count($items) }} sản phẩm)</h3>
                </div>
                
                <div class="order-items">
                    @foreach($items as $item)
                    <div class="order-item">
                        <div class="order-item-image">
                            @if($item['product']->thumbnail)
                                <img src="{{ asset('uploads/' . $item['product']->thumbnail) }}" alt="{{ $item['product']->name }}">
                            @else
                                <div class="order-item-placeholder">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="order-item-info">
                            <h4 class="order-item-name">{{ $item['product']->name }}</h4>
                            <div class="order-item-meta">
                                <span class="order-item-qty">x{{ $item['quantity'] }}</span>
                                <span>{{ number_format($item['product']->current_price, 0, ',', '.') }} đ/sp</span>
                            </div>
                        </div>
                        
                        <div class="order-item-price">
                            <div class="order-item-subtotal">{{ number_format($item['subtotal'], 0, ',', '.') }} đ</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="order-total">
                    <div class="order-total-row">
                        <span class="order-total-label">Tạm tính</span>
                        <span class="order-total-value">{{ number_format($total, 0, ',', '.') }} đ</span>
                    </div>
                    <div class="order-total-row">
                        <span class="order-total-label">Giảm giá</span>
                        <span class="order-total-value">0 đ</span>
                    </div>
                    <div class="order-total-row">
                        <span class="order-total-final">Tổng thanh toán</span>
                        <span class="order-total-amount">{{ number_format($total, 0, ',', '.') }} đ</span>
                    </div>
                </div>
            </div>
            
            <!-- Payment -->
            <div class="payment-card">
                <div class="payment-card-header">
                    <i class="fas fa-wallet"></i>
                    <h3>Phương thức thanh toán</h3>
                </div>
                
                <div class="payment-card-body">
                    <form id="checkoutForm">
                        @csrf
                        <input type="hidden" name="payment_method" value="zalopay">
                        
                        <div class="payment-method selected">
                            <div class="payment-method-radio"></div>
                            <div class="payment-method-logo">
                                <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-ZaloPay.png" alt="ZaloPay">
                            </div>
                            <div class="payment-method-info">
                                <h4>ZaloPay</h4>
                                <p>Thanh toán qua ví ZaloPay hoặc thẻ ngân hàng</p>
                            </div>
                        </div>
                        
                        <div class="notes-group">
                            <label class="notes-label">Ghi chú đơn hàng (tùy chọn)</label>
                            <textarea name="notes" class="notes-input" rows="3" placeholder="Nhập ghi chú nếu có..."></textarea>
                        </div>
                        
                        <div class="security-note">
                            <i class="fas fa-shield-alt"></i>
                            <span>Giao dịch được bảo mật bởi ZaloPay</span>
                        </div>
                        
                        <button type="submit" class="btn-checkout" id="btnCheckout">
                            <i class="fas fa-lock"></i>
                            <span>Thanh toán {{ number_format($total, 0, ',', '.') }} đ</span>
                        </button>
                    </form>
                    
                    <a href="{{ route('marketplace.cart') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i>
                        Quay lại giỏ hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('btnCheckout');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Đang xử lý...</span>';
    btn.disabled = true;
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("marketplace.processPayment") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: formData
        });
        
        const data = await response.json();
        if (data.success && data.payment_url) {
            window.location.href = data.payment_url;
        } else {
            showToast(data.message || 'Có lỗi xảy ra', 'error');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    } catch (error) {
        showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
});

function showToast(message, type) {
    const existingToast = document.querySelector('.checkout-toast');
    if (existingToast) existingToast.remove();
    
    const iconClass = type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle';
    const borderColor = type === 'error' ? 'rgba(239, 68, 68, 0.4)' : 'rgba(34, 197, 94, 0.4)';
    const iconColor = type === 'error' ? '#ef4444' : '#22c55e';
    
    const toast = document.createElement('div');
    toast.className = 'checkout-toast';
    toast.innerHTML = `
        <div style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 99999; animation: toastIn 0.3s ease;">
            <div style="background: linear-gradient(135deg, rgba(13, 27, 42, 0.98), rgba(0, 0, 34, 0.98)); border: 1px solid ${borderColor}; border-radius: 12px; padding: 14px 20px; display: flex; align-items: center; gap: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.5);">
                <div style="width: 32px; height: 32px; background: ${type === 'error' ? 'rgba(239, 68, 68, 0.2)' : 'rgba(34, 197, 94, 0.2)'}; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: ${iconColor};">
                    <i class="fas ${iconClass}"></i>
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
</script>
<style>
@keyframes toastIn { from { opacity: 0; transform: translateX(-50%) translateY(-20px); } to { opacity: 1; transform: translateX(-50%) translateY(0); } }
@keyframes toastOut { from { opacity: 1; transform: translateX(-50%) translateY(0); } to { opacity: 0; transform: translateX(-50%) translateY(-20px); } }
</style>
@endpush
