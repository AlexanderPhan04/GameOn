@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->order_id . ' - Marketplace')

@push('styles')
<style>
    .order-detail-page {
        min-height: calc(100vh - 64px);
        background: linear-gradient(180deg, #000814 0%, #0d1b2a 100%);
        padding: 2rem;
    }
    
    .order-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        text-decoration: none;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        transition: color 0.2s;
    }
    
    .back-link:hover {
        color: #00E5FF;
    }
    
    .order-card {
        background: linear-gradient(135deg, rgba(13, 27, 42, 0.95), rgba(0, 0, 34, 0.95));
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .order-header {
        background: rgba(0, 229, 255, 0.1);
        padding: 1.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .order-header-left h1 {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 0.25rem 0;
    }
    
    .order-header-left .order-date {
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .status-paid {
        background: rgba(34, 197, 94, 0.15);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    
    .status-pending {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    
    .order-items {
        padding: 1.5rem;
    }
    
    .order-items h3 {
        color: #fff;
        font-size: 1.1rem;
        margin: 0 0 1rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .item-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: rgba(0, 0, 20, 0.4);
        border-radius: 12px;
        margin-bottom: 0.75rem;
    }
    
    .item-image {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        overflow: hidden;
        background: linear-gradient(135deg, #1e3a5f, #0d1b2a);
        flex-shrink: 0;
    }
    
    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .item-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(0, 229, 255, 0.3);
    }
    
    .item-info {
        flex: 1;
    }
    
    .item-name {
        color: #fff;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .item-qty {
        color: #64748b;
        font-size: 0.85rem;
    }
    
    .item-price {
        font-family: 'Rajdhani', sans-serif;
        font-weight: 700;
        color: #22c55e;
        font-size: 1.1rem;
    }
    
    .order-summary {
        padding: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        color: #94a3b8;
    }
    
    .summary-row.total {
        padding-top: 0.75rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        color: #fff;
        font-size: 1.25rem;
        font-weight: 700;
    }
    
    .summary-row.total .amount {
        color: #22c55e;
        font-family: 'Rajdhani', sans-serif;
    }
    
    .order-actions {
        display: flex;
        gap: 1rem;
        padding: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .btn-invoice {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .btn-invoice:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
    }
    
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: rgba(255, 255, 255, 0.1);
        color: #94a3b8;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .btn-back:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
    }
    
    @media (max-width: 768px) {
        .order-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .item-row {
            flex-wrap: wrap;
        }
    }
</style>
@endpush

@section('content')
<div class="order-detail-page">
    <div class="order-container">
        <a href="{{ route('marketplace.orders') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Quay lại lịch sử đơn hàng
        </a>
        
        <div class="order-card">
            <div class="order-header">
                <div class="order-header-left">
                    <h1><i class="fas fa-receipt" style="color: #00E5FF;"></i> Đơn hàng #{{ $order->order_id }}</h1>
                    <div class="order-date">Đặt lúc: {{ $order->created_at->format('H:i - d/m/Y') }}</div>
                </div>
                @if($order->payment_status === 'paid')
                    <span class="status-badge status-paid">
                        <i class="fas fa-check-circle"></i> Đã thanh toán
                    </span>
                @else
                    <span class="status-badge status-pending">
                        <i class="fas fa-clock"></i> Chờ thanh toán
                    </span>
                @endif
            </div>
            
            <div class="order-items">
                <h3><i class="fas fa-shopping-bag" style="color: #00E5FF;"></i> Sản phẩm</h3>
                @foreach($order->items as $item)
                <div class="item-row">
                    <div class="item-image">
                        @if($item->product && $item->product->thumbnail)
                            <img src="{{ asset('uploads/' . $item->product->thumbnail) }}" alt="{{ $item->product->name ?? 'Sản phẩm' }}">
                        @else
                            <div class="item-image-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </div>
                    <div class="item-info">
                        <div class="item-name">{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</div>
                        <div class="item-qty">Số lượng: {{ $item->quantity }}</div>
                    </div>
                    <div class="item-price">{{ number_format($item->subtotal, 0, ',', '.') }} đ</div>
                </div>
                @endforeach
            </div>
            
            <div class="order-summary">
                <div class="summary-row">
                    <span>Tạm tính</span>
                    <span>{{ number_format($order->total_amount, 0, ',', '.') }} đ</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="summary-row">
                    <span>Giảm giá</span>
                    <span>-{{ number_format($order->discount_amount, 0, ',', '.') }} đ</span>
                </div>
                @endif
                <div class="summary-row total">
                    <span>Tổng cộng</span>
                    <span class="amount">{{ number_format($order->final_amount, 0, ',', '.') }} đ</span>
                </div>
            </div>
            
            <div class="order-actions">
                <a href="{{ route('marketplace.orders') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                @if($order->isPaid())
                <a href="{{ route('marketplace.invoice', $order->order_id) }}" class="btn-invoice">
                    <i class="fas fa-file-pdf"></i> Tải hóa đơn PDF
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
