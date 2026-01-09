@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<div class="container py-4">
    <h2><i class="fas fa-credit-card"></i> Thanh toán</h2>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>{{ $item['product']->name }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>{{ number_format($item['product']->current_price, 0, ',', '.') }} đ</td>
                                <td>{{ number_format($item['subtotal'], 0, ',', '.') }} đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                <td><strong class="h5 text-success">{{ number_format($total, 0, ',', '.') }} đ</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Phương thức thanh toán</h5>
                </div>
                <div class="card-body">
                    <form id="checkoutForm">
                        @csrf
                        <div class="mb-3">
                            <label>Ghi chú (tùy chọn):</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-lock"></i> Thanh toán với VNPay
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
    e.preventDefault();
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
            alert(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        alert('Có lỗi xảy ra');
    }
});
</script>
@endsection

