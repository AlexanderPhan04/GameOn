@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ $product->thumbnail ? asset('uploads/' . $product->thumbnail) : 'https://via.placeholder.com/600x400' }}" 
                 alt="{{ $product->name }}" 
                 class="img-fluid rounded">
        </div>
        <div class="col-md-6">
            <h2>{{ $product->name }}</h2>
            <div class="mb-3">
                @if($product->hasDiscount())
                    <span class="text-muted text-decoration-line-through me-2">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                    <span class="h4 text-success">{{ number_format($product->current_price, 0, ',', '.') }} đ</span>
                @else
                    <span class="h4 text-success">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                @endif
            </div>
            
            <p class="text-muted">{{ $product->description }}</p>
            
            <div class="mb-3">
                <span class="badge bg-primary">{{ ucfirst($product->type) }}</span>
                <span class="badge bg-secondary">{{ ucfirst($product->category) }}</span>
                @if($product->rarity)
                    <span class="badge bg-warning">{{ ucfirst($product->rarity) }}</span>
                @endif
            </div>

            @if($owned)
                <div class="alert alert-info">
                    <i class="fas fa-check-circle"></i> Bạn đã sở hữu sản phẩm này
                </div>
            @else
                <form id="addToCartForm">
                    @csrf
                    <div class="mb-3">
                        <label>Số lượng:</label>
                        <input type="number" name="quantity" value="1" min="1" class="form-control" style="max-width: 100px;">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if($relatedProducts->count() > 0)
    <div class="mt-5">
        <h4>Sản phẩm liên quan</h4>
        <div class="row g-4">
            @foreach($relatedProducts as $related)
            <div class="col-md-3">
                <div class="card">
                    <img src="{{ $related->thumbnail ? asset('uploads/' . $related->thumbnail) : 'https://via.placeholder.com/300' }}" 
                         class="card-img-top" alt="{{ $related->name }}">
                    <div class="card-body">
                        <h6 class="card-title">{{ $related->name }}</h6>
                        <p class="text-success fw-bold">{{ number_format($related->current_price, 0, ',', '.') }} đ</p>
                        <a href="{{ route('marketplace.show', $related->id) }}" class="btn btn-sm btn-primary">Xem chi tiết</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
document.getElementById('addToCartForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const quantity = formData.get('quantity');
    
    try {
        const response = await fetch('{{ route("marketplace.addToCart", $product->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ quantity: quantity })
        });
        
        const data = await response.json();
        if (data.success) {
            alert('Đã thêm vào giỏ hàng!');
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        alert('Có lỗi xảy ra');
    }
});
</script>
@endsection

