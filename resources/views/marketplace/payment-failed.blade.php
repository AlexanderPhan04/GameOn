@extends('layouts.app')

@section('title', 'Thanh toán thất bại')

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
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 24px;
        padding: 3rem 2rem;
        text-align: center;
    }
    
    .result-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(239, 68, 68, 0.1));
        border: 2px solid rgba(239, 68, 68, 0.4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .result-icon i {
        font-size: 3rem;
        color: #ef4444;
    }
    
    .result-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #ef4444;
        margin-bottom: 0.5rem;
    }
    
    .result-desc {
        color: #94a3b8;
        font-size: 1rem;
        margin-bottom: 2rem;
    }
    
    .order-info {
        background: rgba(0, 0, 20, 0.5);
        border: 1px solid rgba(239, 68, 68, 0.1);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 2rem;
    }
    
    .order-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
    }
    
    .order-label {
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .order-value {
        color: #fff;
        font-weight: 600;
    }
    
    .result-actions {
        display: flex;
        gap: 1rem;
    }
    
    .btn-retry {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border: none;
        border-radius: 12px;
        color: #fff;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .btn-retry:hover {
        transform: translateY(-2px);
        color: #fff;
    }
    
    .btn-back {
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
    
    .btn-back:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="result-page">
    <div class="result-card">
        <div class="result-icon">
            <i class="fas fa-times"></i>
        </div>
        
        <h1 class="result-title">Thanh toán thất bại</h1>
        <p class="result-desc">Giao dịch không thành công hoặc đã bị hủy. Vui lòng thử lại.</p>
        
        @if($order)
        <div class="order-info">
            <div class="order-row">
                <span class="order-label">Mã đơn hàng</span>
                <span class="order-value">{{ $order->order_id }}</span>
            </div>
        </div>
        @endif
        
        <div class="result-actions">
            <a href="{{ route('marketplace.cart') }}" class="btn-retry">
                <i class="fas fa-redo"></i>
                Thử lại
            </a>
            <a href="{{ route('marketplace.index') }}" class="btn-back">
                <i class="fas fa-store"></i>
                Về cửa hàng
            </a>
        </div>
    </div>
</div>
@endsection
