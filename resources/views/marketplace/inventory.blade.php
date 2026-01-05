@extends('layouts.app')

@section('title', 'Kho đồ')

@push('styles')
<style>
    .inventory-page {
        min-height: calc(100vh - 64px);
        background: linear-gradient(180deg, #000814 0%, #0d1b2a 100%);
        padding: 2rem;
    }
    
    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .page-title i {
        color: #22c55e;
    }
    
    .inventory-stats {
        display: flex;
        gap: 1.5rem;
        margin-top: 0.5rem;
    }
    
    .stat-item {
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .stat-item span {
        color: #22c55e;
        font-weight: 600;
    }
    
    .inventory-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.5rem;
    }
    
    .inventory-card {
        background: linear-gradient(135deg, rgba(13, 27, 42, 0.95), rgba(0, 0, 34, 0.95));
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s;
    }
    
    .inventory-card:hover {
        transform: translateY(-5px);
        border-color: rgba(34, 197, 94, 0.3);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    .inventory-card.equipped {
        border-color: rgba(34, 197, 94, 0.5);
        box-shadow: 0 0 20px rgba(34, 197, 94, 0.1);
    }
    
    .card-image {
        position: relative;
        height: 180px;
        overflow: hidden;
    }
    
    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }
    
    .inventory-card:hover .card-image img {
        transform: scale(1.05);
    }
    
    .equipped-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .item-type {
        position: absolute;
        bottom: 10px;
        left: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: #94a3b8;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .card-content {
        padding: 1rem;
    }
    
    .item-name {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 0.5rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .item-date {
        color: #64748b;
        font-size: 0.8rem;
        margin-bottom: 1rem;
    }
    
    .btn-equip {
        width: 100%;
        padding: 0.625rem;
        border: none;
        border-radius: 8px;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-equip.equip {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff;
    }
    
    .btn-equip.equip:hover {
        box-shadow: 0 5px 15px rgba(34, 197, 94, 0.3);
    }
    
    .btn-equip.unequip {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }
    
    .btn-equip.unequip:hover {
        background: rgba(239, 68, 68, 0.2);
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    
    .empty-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 1.5rem;
        background: rgba(255, 255, 255, 0.05);
        border: 2px dashed rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .empty-icon i {
        font-size: 3rem;
        color: #64748b;
    }
    
    .empty-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 0.5rem;
    }
    
    .empty-desc {
        color: #64748b;
        margin-bottom: 1.5rem;
    }
    
    .btn-shop {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 2rem;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border: none;
        border-radius: 12px;
        color: #fff;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .btn-shop:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(34, 197, 94, 0.3);
        color: #fff;
    }
    
    /* Pagination */
    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }
    
    .pagination {
        display: flex;
        gap: 0.5rem;
    }
    
    .pagination .page-item .page-link {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #94a3b8;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s;
    }
    
    .pagination .page-item.active .page-link,
    .pagination .page-item .page-link:hover {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border-color: transparent;
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="inventory-page">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-box"></i>
                Kho đồ của tôi
            </h1>
            <div class="inventory-stats">
                <div class="stat-item">
                    Tổng vật phẩm: <span>{{ $inventory->total() }}</span>
                </div>
                <div class="stat-item">
                    Đang trang bị: <span>{{ $inventory->where('is_equipped', true)->count() }}</span>
                </div>
            </div>
        </div>
        
        @if($inventory->count() > 0)
        <div class="inventory-grid">
            @foreach($inventory as $item)
            <div class="inventory-card {{ $item->is_equipped ? 'equipped' : '' }}">
                <div class="card-image">
                    <img src="{{ $item->product->thumbnail ? asset('uploads/' . $item->product->thumbnail) : 'https://via.placeholder.com/300x180?text=' . urlencode($item->product->name) }}" 
                         alt="{{ $item->product->name }}">
                    
                    @if($item->is_equipped)
                    <div class="equipped-badge">
                        <i class="fas fa-check"></i> Đang dùng
                    </div>
                    @endif
                    
                    <div class="item-type">{{ $item->product->type }}</div>
                </div>
                
                <div class="card-content">
                    <h3 class="item-name">{{ $item->product->name }}</h3>
                    <p class="item-date">
                        <i class="far fa-calendar-alt"></i>
                        Nhận ngày {{ $item->created_at->format('d/m/Y') }}
                    </p>
                    
                    @if(in_array($item->product->type, ['theme', 'sticker', 'badge', 'frame']))
                    <button class="btn-equip {{ $item->is_equipped ? 'unequip' : 'equip' }}" 
                            data-id="{{ $item->id }}" 
                            data-equip="{{ $item->is_equipped ? 'false' : 'true' }}">
                        <i class="fas {{ $item->is_equipped ? 'fa-times' : 'fa-check' }}"></i>
                        {{ $item->is_equipped ? 'Tháo ra' : 'Trang bị' }}
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="pagination-wrapper">
            {{ $inventory->links() }}
        </div>
        @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-box-open"></i>
            </div>
            <h2 class="empty-title">Kho đồ trống</h2>
            <p class="empty-desc">Bạn chưa có vật phẩm nào. Hãy ghé thăm cửa hàng để mua sắm!</p>
            <a href="{{ route('marketplace.index') }}" class="btn-shop">
                <i class="fas fa-shopping-bag"></i>
                Mua sắm ngay
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.btn-equip').forEach(btn => {
    btn.addEventListener('click', async function() {
        const itemId = this.dataset.id;
        const equip = this.dataset.equip === 'true';
        
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        
        try {
            const response = await fetch(`/marketplace/inventory/${itemId}/equip`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ equip: equip })
            });
            
            const data = await response.json();
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra');
                this.disabled = false;
                this.innerHTML = equip ? '<i class="fas fa-check"></i> Trang bị' : '<i class="fas fa-times"></i> Tháo ra';
            }
        } catch (error) {
            alert('Có lỗi xảy ra');
            this.disabled = false;
            this.innerHTML = equip ? '<i class="fas fa-check"></i> Trang bị' : '<i class="fas fa-times"></i> Tháo ra';
        }
    });
});
</script>
@endpush
