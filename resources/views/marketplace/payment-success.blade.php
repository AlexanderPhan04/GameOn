@extends('layouts.app')

@section('title', 'Thanh toán thành công')

@push('styles')
<style>
    .result-page {
        min-height: calc(100vh - 64px);
        background: linear-gradient(180deg, #000814 0%, #0d1b2a 100%);
        padding: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .result-card {
        max-width: 500px;
        width: 100%;
        background: linear-gradient(135deg, rgba(13, 27, 42, 0.95), rgba(0, 0, 34, 0.95));
        border: 1px solid rgba(34, 197, 94, 0.3);
        border-radius: 24px;
        padding: 3rem 2rem;
        text-align: center;
    }
    
    .result-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(34, 197, 94, 0.1));
        border: 2px solid rgba(34, 197, 94, 0.4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .result-icon i {
        font-size: 3rem;
        color: #22c55e;
    }
    
    .result-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #22c55e;
        margin-bottom: 0.5rem;
    }
    
    .result-desc {
        color: #94a3b8;
        font-size: 1rem;
        margin-bottom: 2rem;
    }
    
    .order-info {
        background: rgba(0, 0, 20, 0.5);
        border: 1px solid rgba(0, 229, 255, 0.1);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 2rem;
    }
    
    .order-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(0, 229, 255, 0.08);
    }
    
    .order-row:last-child {
        border-bottom: none;
    }
    
    .order-label {
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .order-value {
        color: #fff;
        font-weight: 600;
    }
    
    .order-total {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.25rem;
        color: #22c55e;
    }
    
    .result-actions {
        display: flex;
        gap: 1rem;
    }
    
    .btn-inventory {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none;
        border-radius: 12px;
        color: #fff;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .btn-inventory:hover {
        transform: translateY(-2px);
        color: #fff;
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
</style>
@endpush

@section('content')
<div class="result-page">
    <div class="result-card">
        <div class="result-icon">
            <i class="fas fa-check"></i>
        </div>
        
        <h1 class="result-title">Thanh toán thành công!</h1>
        <p class="result-desc">Cảm ơn bạn đã mua hàng. Sản phẩm đã được thêm vào kho đồ của bạn.</p>
        
        <div class="order-info">
            <div class="order-row">
                <span class="order-label">Mã đơn hàng</span>
                <span class="order-value">{{ $order->order_id }}</span>
            </div>
            <div class="order-row">
                <span class="order-label">Số sản phẩm</span>
                <span class="order-value">{{ $order->items->sum('quantity') }} sản phẩm</span>
            </div>
            <div class="order-row">
                <span class="order-label">Tổng thanh toán</span>
                <span class="order-value order-total">{{ number_format($order->final_amount, 0, ',', '.') }} đ</span>
            </div>
        </div>
        
        <div class="result-actions">
            <a href="{{ route('marketplace.inventory') }}" class="btn-inventory">
                <i class="fas fa-box"></i>
                Xem kho đồ
            </a>
            <a href="{{ route('marketplace.index') }}" class="btn-continue">
                <i class="fas fa-store"></i>
                Tiếp tục mua
            </a>
        </div>
    </div>
</div>
@endsection
