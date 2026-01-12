<?php

namespace App\Services;

use PayOS\PayOS;
use Illuminate\Support\Facades\Log;

class PayosService
{
    protected $payOS;

    public function __construct()
    {
        $clientId = config('services.payos.client_id') ?: env('PAYOS_CLIENT_ID');
        $apiKey = config('services.payos.api_key') ?: env('PAYOS_API_KEY');
        $checksumKey = config('services.payos.checksum_key') ?: env('PAYOS_CHECKSUM_KEY');

        if (empty($clientId) || empty($apiKey) || empty($checksumKey)) {
            throw new \Exception('PayOS configuration is missing. Please check PAYOS_CLIENT_ID, PAYOS_API_KEY, PAYOS_CHECKSUM_KEY in .env');
        }

        $this->payOS = new PayOS($clientId, $apiKey, $checksumKey);
    }

    /**
     * Tạo link thanh toán PayOS
     */
    public function createPaymentLink(array $data): array
    {
        $orderCode = $data['order_code'] ?? intval(substr(strval(microtime(true) * 10000), -6));
        
        $paymentData = [
            "orderCode" => $orderCode,
            "amount" => (int) $data['amount'],
            "description" => $data['description'] ?? 'Thanh toan don hang',
            "returnUrl" => $data['return_url'] ?? route('payment.success'),
            "cancelUrl" => $data['cancel_url'] ?? route('payment.cancel'),
        ];

        // Thêm items nếu có
        if (isset($data['items']) && is_array($data['items'])) {
            $paymentData['items'] = $data['items'];
        }

        // Thêm buyer info nếu có
        if (isset($data['buyer_name'])) {
            $paymentData['buyerName'] = $data['buyer_name'];
        }
        if (isset($data['buyer_email'])) {
            $paymentData['buyerEmail'] = $data['buyer_email'];
        }
        if (isset($data['buyer_phone'])) {
            $paymentData['buyerPhone'] = $data['buyer_phone'];
        }

        try {
            $response = $this->payOS->createPaymentLink($paymentData);
            
            return [
                'success' => true,
                'checkout_url' => $response['checkoutUrl'],
                'order_code' => $orderCode,
                'qr_code' => $response['qrCode'] ?? null,
            ];
        } catch (\Throwable $th) {
            Log::error('PayOS Create Payment Error: ' . $th->getMessage());
            throw $th;
        }
    }

    /**
     * Lấy thông tin thanh toán
     */
    public function getPaymentInfo(int $orderCode): array
    {
        try {
            return $this->payOS->getPaymentLinkInformation($orderCode);
        } catch (\Throwable $th) {
            Log::error('PayOS Get Payment Info Error: ' . $th->getMessage());
            throw $th;
        }
    }

    /**
     * Hủy link thanh toán
     */
    public function cancelPaymentLink(int $orderCode, string $reason = null): array
    {
        try {
            return $this->payOS->cancelPaymentLink($orderCode, $reason);
        } catch (\Throwable $th) {
            Log::error('PayOS Cancel Payment Error: ' . $th->getMessage());
            throw $th;
        }
    }

    /**
     * Xác thực webhook data từ PayOS
     */
    public function verifyWebhookData(array $data): array
    {
        try {
            return $this->payOS->verifyPaymentWebhookData($data);
        } catch (\Throwable $th) {
            Log::error('PayOS Verify Webhook Error: ' . $th->getMessage());
            throw $th;
        }
    }
}
