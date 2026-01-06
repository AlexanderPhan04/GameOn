@extends('layouts.app')

@section('title', 'Lịch sử đơn hàng - Marketplace')

@push('styles')
<style>
    .orders-page {
        min-height: calc(100vh - 64px);
        background: linear-gradient(180deg, #000814 0%, #0d1b2a 100%);
        padding: 2rem;
    }
    
    .orders-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
    }
    
    .page-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .page-title i {
        color: #00E5FF;
    }
    
    .orders-table {
        background: linear-gradient(135deg, rgba(13, 27, 42, 0.95), rgba(0, 0, 34, 0.95));
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
    }
    
    .orders-table table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .orders-table th {
        background: rgba(0, 229, 255, 0.1);
        padding: 1rem 1.25rem;
        text-align: left;
        color: #00E5FF;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid rgba(0, 229, 255, 0.15);
    }
    
    .orders-table td {
        padding: 1rem 1.25rem;
        color: #e2e8f0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .orders-table tr:hover td {
        background: rgba(0, 229, 255, 0.05);
    }
    
    .order-id {
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        color: #00E5FF;
    }
    
    .order-amount {
        font-family: 'Rajdhani', sans-serif;
        font-weight: 700;
        color: #22c55e;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
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
    
    .status-failed {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .order-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-action {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    
    .btn-view {
        background: rgba(0, 229, 255, 0.1);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.3);
    }
    
    .btn-view:hover {
        background: rgba(0, 229, 255, 0.2);
    }
    
    .btn-invoice {
        background: rgba(139, 92, 246, 0.1);
        color: #a78bfa;
        border: 1px solid rgba(139, 92, 246, 0.3);
    }
    
    .btn-invoice:hover {
        background: rgba(139, 92, 246, 0.2);
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #64748b;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .empty-state h3 {
        color: #94a3b8;
        margin-bottom: 0.5rem;
    }
    
    .pagination-wrapper {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }
    
    @media (max-width: 768px) {
        .orders-table {
            overflow-x: auto;
        }
        
        .orders-table table {
            min-width: 700px;
        }
    }
</style>
@endpush

@section('content')
<div class="orders-page">
    <div class="orders-container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-receipt"></i>
                Lịch sử đơn hàng
            </h1>
            <a href="{{ route('marketplace.index') }}" class="btn-action btn-view">
                <i class="fas fa-store"></i> Tiếp tục mua sắm
            </a>
        </div>
        
        @if($orders->count() > 0)
        <div class="orders-table">
            <table>
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt</th>
                        <th>Số sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td class="order-id">{{ $order->order_id }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $order->items->count() }} sản phẩm</td>
                        <td class="order-amount">{{ number_format($order->final_amount, 0, ',', '.') }} đ</td>
                        <td>
                            @if($order->payment_status === 'paid')
                                <span class="status-badge status-paid">
                                    <i class="fas fa-check-circle"></i> Đã thanh toán
                                </span>
                            @elseif($order->payment_status === 'pending')
                                <span class="status-badge status-pending">
                                    <i class="fas fa-clock"></i> Chờ thanh toán
                                </span>
                            @else
                                <span class="status-badge status-failed">
                                    <i class="fas fa-times-circle"></i> Thất bại
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="order-actions">
                                <a href="{{ route('marketplace.orderDetail', $order->order_id) }}" class="btn-action btn-view">
                                    <i class="fas fa-eye"></i> Chi tiết
                                </a>
                                @if($order->isPaid())
                                <a href="{{ route('marketplace.invoice', $order->order_id) }}" class="btn-action btn-invoice">
                                    <i class="fas fa-file-pdf"></i> Hóa đơn
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="pagination-wrapper">
            {{ $orders->links() }}
        </div>
        @else
        <div class="orders-table">
            <div class="empty-state">
                <i class="fas fa-shopping-bag"></i>
                <h3>Chưa có đơn hàng nào</h3>
                <p>Bạn chưa có đơn hàng nào. Hãy bắt đầu mua sắm!</p>
                <a href="{{ route('marketplace.index') }}" class="btn-action btn-view" style="margin-top: 1rem;">
                    <i class="fas fa-store"></i> Mua sắm ngay
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
