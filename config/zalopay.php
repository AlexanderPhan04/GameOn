<?php

return [
    'app_id' => env('ZALOPAY_APP_ID', '553'),
    'key1' => env('ZALOPAY_KEY1', ''),
    'key2' => env('ZALOPAY_KEY2', ''),
    'create_order_url' => env('ZALOPAY_CREATE_ORDER_URL', 'https://sb-openapi.zalopay.vn/v2/create'),
    'gateway_url' => env('ZALOPAY_GATEWAY_URL', 'https://sbgateway.zalopay.vn/pay?order='),
    'query_order_url' => env('ZALOPAY_QUERY_ORDER_URL', 'https://sb-openapi.zalopay.vn/v2/query'),
    'refund_url' => env('ZALOPAY_REFUND_URL', 'https://sb-openapi.zalopay.vn/v2/refund'),
    'callback_url' => env('ZALOPAY_CALLBACK_URL', 'http://localhost:8000/payment/zalopay/callback'),
    'redirect_url' => env('ZALOPAY_REDIRECT_URL', 'http://localhost:8000/payment/zalopay/return'),
];
