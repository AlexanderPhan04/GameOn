<?php

namespace App\Services;

use PayOS\PayOS;
use Illuminate\Support\Facades\Log;

class PayosService
{
    protected $payOS;

    public function __construct()
    {
        $this->payOS = new PayOS(
            env('PAYOS_CLIENT_ID'),
            env('PAYOS_API_KEY'),
            env('PAYOS_CHECKSUM_KEY')
        );
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
