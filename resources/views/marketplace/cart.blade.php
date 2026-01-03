@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container py-4">
    <h2><i class="fas fa-shopping-cart"></i> Giỏ hàng</h2>
    
    @if(count($items) > 0)
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tổng</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ $item['product']->thumbnail ? asset('uploads/' . $item['product']->thumbnail) : 'https://via.placeholder.com/80' }}" 
                                 alt="{{ $item['product']->name }}" 
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; margin-right: 1rem;">
                            <div>
                                <strong>{{ $item['product']->name }}</strong>
                                <br>
                                <small class="text-muted">{{ ucfirst($item['product']->type) }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ number_format($item['product']->current_price, 0, ',', '.') }} đ</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td><strong>{{ number_format($item['subtotal'], 0, ',', '.') }} đ</strong></td>
                    <td>
                        <button class="btn btn-sm btn-danger remove-from-cart" data-id="{{ $item['product']->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                    <td><strong class="h5 text-success">{{ number_format($total, 0, ',', '.') }} đ</strong></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <div class="text-end mt-4">
        <a href="{{ route('marketplace.index') }}" class="btn btn-secondary">Tiếp tục mua sắm</a>
        <a href="{{ route('marketplace.checkout') }}" class="btn btn-primary btn-lg">Thanh toán</a>
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
        <p class="text-muted">Giỏ hàng trống</p>
        <a href="{{ route('marketplace.index') }}" class="btn btn-primary">Mua sắm ngay</a>
    </div>
    @endif
</div>

<script>
document.querySelectorAll('.remove-from-cart').forEach(btn => {
    btn.addEventListener('click', async function() {
        const productId = this.dataset.id;
        try {
            const response = await fetch(`/marketplace/cart/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            });
            const data = await response.json();
            if (data.success) {
                location.reload();
            }
        } catch (error) {
            alert('Có lỗi xảy ra');
        }
    });
});
</script>
@endsection

