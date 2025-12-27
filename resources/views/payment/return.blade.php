@extends('layouts.app')

@section('title', 'Kết quả thanh toán')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">
                        <i class="fas fa-credit-card"></i> Kết quả thanh toán VNPay
                    </h3>
                </div>
                <div class="card-body">
                    @if($status === 'success')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <strong>{{ $message }}</strong>
                        </div>
                    @elseif($status === 'failed')
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle"></i> <strong>{{ $message }}</strong>
                        </div>
                    @elseif($status === 'invalid')
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> <strong>{{ $message }}</strong>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> <strong>{{ $message }}</strong>
                        </div>
                    @endif

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="40%">Mã đơn hàng:</th>
                                    <td><strong>{{ $orderId }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Số tiền:</th>
                                    <td><strong>{{ number_format($amount / 100, 0, ',', '.') }} VNĐ</strong></td>
                                </tr>
                                <tr>
                                    <th>Nội dung thanh toán:</th>
                                    <td>{{ $orderInfo }}</td>
                                </tr>
                                <tr>
                                    <th>Mã phản hồi:</th>
                                    <td>
                                        <span class="badge {{ $responseCode == '00' ? 'badge-success' : 'badge-danger' }}">
                                            {{ $responseCode }}
                                        </span>
                                    </td>
                                </tr>
                                @if($transactionNo)
                                <tr>
                                    <th>Mã giao dịch VNPay:</th>
                                    <td>{{ $transactionNo }}</td>
                                </tr>
                                @endif
                                @if($bankCode)
                                <tr>
                                    <th>Ngân hàng:</th>
                                    <td>{{ $bankCode }}</td>
                                </tr>
                                @endif
                                @if($payDate)
                                <tr>
                                    <th>Thời gian thanh toán:</th>
                                    <td>{{ $payDate }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i> Về trang chủ
                        </a>
                        @if($status === 'success')
                            <a href="#" class="btn btn-success">
                                <i class="fas fa-download"></i> Tải hóa đơn
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

