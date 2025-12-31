<?php

return [
    /*
    |--------------------------------------------------------------------------
    | VNPay Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình tích hợp thanh toán VNPay
    |
    */

    // Mã website của bạn trong hệ thống VNPay
    'tmn_code' => env('VNPAY_TMN_CODE', ''),

    // Secret key từ VNPay
    'hash_secret' => env('VNPAY_HASH_SECRET', ''),

    // URL thanh toán VNPay
    'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),

    // URL trả về sau khi thanh toán
    'return_url' => env('VNPAY_RETURN_URL', env('APP_URL') . '/payment/vnpay/return'),

    // URL API VNPay
    'api_url' => env('VNPAY_API_URL', 'http://sandbox.vnpayment.vn/merchant_webapi/merchant.html'),

    // URL IPN (Instant Payment Notification)
    'ipn_url' => env('VNPAY_IPN_URL', env('APP_URL') . '/payment/vnpay/ipn'),

    // Môi trường: sandbox hoặc production
    'environment' => env('VNPAY_ENVIRONMENT', 'sandbox'),

    // Thời gian hết hạn thanh toán (phút)
    'expire_minutes' => env('VNPAY_EXPIRE_MINUTES', 15),
];

