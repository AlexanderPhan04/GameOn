@extends('layouts.app')

@section('title', 'Kho đồ')

@section('content')
<div class="container py-4">
    <h2><i class="fas fa-box"></i> Kho đồ của tôi</h2>
    
    <div class="row g-4 mt-2">
        @forelse($inventory as $item)
        <div class="col-md-3">
            <div class="card">
                <img src="{{ $item->product->thumbnail ? asset('uploads/' . $item->product->thumbnail) : 'https://via.placeholder.com/300' }}" 
                     class="card-img-top" alt="{{ $item->product->name }}">
                <div class="card-body">
                    <h6 class="card-title">{{ $item->product->name }}</h6>
                    <p class="text-muted small">{{ ucfirst($item->product->type) }}</p>
                    
                    @if($item->is_equipped)
                        <span class="badge bg-success">Đang sử dụng</span>
                    @endif
                    
                    @if($item->product->type == 'theme' || $item->product->type == 'sticker')
                        <button class="btn btn-sm btn-primary mt-2 equip-btn" 
                                data-id="{{ $item->id }}" 
                                data-equip="{{ $item->is_equipped ? 'false' : 'true' }}">
                            {{ $item->is_equipped ? 'Tháo' : 'Trang bị' }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">Kho đồ trống</p>
                <a href="{{ route('marketplace.index') }}" class="btn btn-primary">Mua sắm ngay</a>
            </div>
        </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        {{ $inventory->links() }}
    </div>
</div>

<script>
document.querySelectorAll('.equip-btn').forEach(btn => {
    btn.addEventListener('click', async function() {
        const itemId = this.dataset.id;
        const equip = this.dataset.equip === 'true';
        
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
            }
        } catch (error) {
            alert('Có lỗi xảy ra');
        }
    });
});
</script>
@endsection

