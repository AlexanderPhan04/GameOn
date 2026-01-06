<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Hoa don #{{ $order->order_id }}</title>
    <style>
        @page {
            margin: 20px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        
        .invoice-container {
            padding: 15px;
        }
        
        .header {
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 2px solid #00bcd4;
            padding-bottom: 12px;
        }
        
        .header-table {
            width: 100%;
        }
        
        .logo {
            font-size: 22px;
            font-weight: bold;
            color: #0d1b2a;
        }
        
        .logo span {
            color: #00bcd4;
        }
        
        .company-info {
            margin-top: 5px;
            color: #666;
            font-size: 9px;
        }
        
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            color: #0d1b2a;
            text-align: right;
        }
        
        .invoice-number {
            font-size: 12px;
            color: #00bcd4;
            font-weight: bold;
            text-align: right;
        }
        
        .invoice-date {
            color: #666;
            text-align: right;
            font-size: 10px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            background: #4caf50;
            color: #fff;
        }
        
        .info-table {
            width: 100%;
            margin-bottom: 15px;
        }
        
        .info-table td {
            vertical-align: top;
            width: 50%;
            padding: 8px;
            background: #f5f5f5;
        }
        
        .info-table h3 {
            font-size: 10px;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 6px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
        }
        
        .info-table .name {
            font-weight: bold;
            font-size: 12px;
            color: #0d1b2a;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .items-table th {
            background: #0d1b2a;
            color: #fff;
            padding: 8px 6px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
        }
        
        .items-table th:last-child,
        .items-table td:last-child {
            text-align: right;
        }
        
        .items-table td {
            padding: 8px 6px;
            border-bottom: 1px solid #eee;
            font-size: 10px;
        }
        
        .items-table tr:nth-child(even) {
            background: #fafafa;
        }
        
        .item-name {
            font-weight: 600;
            color: #0d1b2a;
        }
        
        .summary-table {
            width: 250px;
            margin-left: auto;
            margin-bottom: 15px;
        }
        
        .summary-table td {
            padding: 5px 0;
        }
        
        .summary-table .label {
            color: #666;
        }
        
        .summary-table .value {
            text-align: right;
            font-weight: 600;
        }
        
        .summary-table .total td {
            border-top: 2px solid #0d1b2a;
            padding-top: 8px;
            font-size: 13px;
        }
        
        .summary-table .total .label {
            font-weight: bold;
            color: #0d1b2a;
        }
        
        .summary-table .total .value {
            color: #00bcd4;
            font-size: 14px;
        }
        
        .payment-info {
            padding: 10px;
            background: #e3f2fd;
            border-left: 3px solid #00bcd4;
            margin-bottom: 15px;
            font-size: 9px;
        }
        
        .payment-info h4 {
            color: #0d1b2a;
            margin-bottom: 5px;
            font-size: 11px;
        }
        
        .payment-info p {
            color: #666;
            margin-bottom: 2px;
        }
        
        .footer {
            text-align: center;
            color: #999;
            font-size: 9px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td style="width: 50%; vertical-align: top;">
                        <div class="logo">GAME <span>ON</span></div>
                        <div class="company-info">
                            <p>Pro Gaming Platform</p>
                            <p>support@gameon.alexstudio.id.vn</p>
                        </div>
                    </td>
                    <td style="width: 50%; vertical-align: top;">
                        <div class="invoice-title">INVOICE</div>
                        <div class="invoice-number">#{{ $order->order_id }}</div>
                        <div class="invoice-date">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        <div style="text-align: right; margin-top: 5px;">
                            <span class="status-badge">PAID</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Customer & Payment Info -->
        <table class="info-table">
            <tr>
                <td>
                    <h3>Customer</h3>
                    <p class="name">{{ $order->user->name }}</p>
                    <p>{{ $order->user->email }}</p>
                </td>
                <td>
                    <h3>Payment</h3>
                    <p><strong>Method:</strong> PayOS</p>
                    <p><strong>Transaction:</strong> {{ $order->order_code ?? 'N/A' }}</p>
                    <p><strong>Date:</strong> {{ $order->updated_at->format('H:i d/m/Y') }}</p>
                </td>
            </tr>
        </table>
        
        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Product</th>
                    <th style="width: 17%;">Price</th>
                    <th style="width: 13%;">Qty</th>
                    <th style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <span class="item-name">{{ $item->product->name ?? 'Product' }}</span>
                    </td>
                    <td>{{ number_format($item->price - ($item->discount_price ?? 0), 0, ',', '.') }} VND</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->subtotal, 0, ',', '.') }} VND</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Summary -->
        <table class="summary-table">
            <tr>
                <td class="label">Subtotal:</td>
                <td class="value">{{ number_format($order->total_amount, 0, ',', '.') }} VND</td>
            </tr>
            @if($order->discount_amount > 0)
            <tr>
                <td class="label">Discount:</td>
                <td class="value">-{{ number_format($order->discount_amount, 0, ',', '.') }} VND</td>
            </tr>
            @endif
            <tr class="total">
                <td class="label">TOTAL:</td>
                <td class="value">{{ number_format($order->final_amount, 0, ',', '.') }} VND</td>
            </tr>
        </table>
        
        <!-- Payment Info -->
        <div class="payment-info">
            <h4>Transaction Info</h4>
            <p>Payment completed successfully via PayOS payment gateway.</p>
            <p>Items have been added to your inventory and are ready to use.</p>
            <p>Contact: support@gameon.alexstudio.id.vn</p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Thank you for shopping at Game On!</p>
            <p>Auto-generated invoice - {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
