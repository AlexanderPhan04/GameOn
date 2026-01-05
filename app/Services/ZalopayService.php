<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZalopayService
{
    protected $appId;
    protected $key1;
    protected $key2;
    protected $createOrderUrl;
    protected $gatewayUrl;
    protected $queryOrderUrl;
    protected $refundUrl;
    protected $callbackUrl;
    protected $redirectUrl;

    public function __construct()
    {
        $this->appId = config('zalopay.app_id');
        $this->key1 = config('zalopay.key1');
        $this->key2 = config('zalopay.key2');
        $this->createOrderUrl = config('zalopay.create_order_url');
        $this->gatewayUrl = config('zalopay.gateway_url');
        $this->queryOrderUrl = config('zalopay.query_order_url');
        $this->refundUrl = config('zalopay.refund_url');
        $this->callbackUrl = config('zalopay.callback_url');
        $this->redirectUrl = config('zalopay.redirect_url');
    }

    /**
     * Generate transaction ID
     */
    protected function genTransId(): string
    {
        return date('ymd') . '_' . $this->appId . '_' . time() . rand(100, 999);
    }

    /**
     * Compute HMAC SHA256
     */
    protected function computeMac(string $data, ?string $key = null): string
    {
        return hash_hmac('sha256', $data, $key ?? $this->key1);
    }

    /**
     * Create payment URL
     */
    public function createPaymentUrl(array $data): ?string
    {
        try {
            $appTransId = $this->genTransId();
            $appTime = round(microtime(true) * 1000);
            
            $embedData = json_encode([
                'redirecturl' => $this->redirectUrl
            ]);
            
            $items = json_encode($data['items'] ?? []);
            
            $order = [
                'app_id' => (int) $this->appId,
                'app_trans_id' => $appTransId,
                'app_user' => $data['app_user'] ?? 'user_' . ($data['user_id'] ?? '0'),
                'app_time' => $appTime,
                'amount' => (int) $data['amount'],
                'item' => $items,
                'embed_data' => $embedData,
                'description' => $data['description'] ?? 'Thanh toán đơn hàng',
                'bank_code' => $data['bank_code'] ?? '',
                'callback_url' => $this->callbackUrl,
            ];

            // Generate MAC
            $macData = $order['app_id'] . '|' . $order['app_trans_id'] . '|' . $order['app_user'] . '|' 
                     . $order['amount'] . '|' . $order['app_time'] . '|' . $order['embed_data'] . '|' . $order['item'];
            $order['mac'] = $this->computeMac($macData);

            Log::info('ZaloPay Create Order Request', $order);

            $response = Http::asForm()->post($this->createOrderUrl, $order);
            $result = $response->json();

            Log::info('ZaloPay Create Order Response', $result ?? []);

            if (isset($result['return_code']) && $result['return_code'] == 1) {
                return $result['order_url'];
            }

            Log::error('ZaloPay Create Order Failed', [
                'return_code' => $result['return_code'] ?? 'unknown',
                'return_message' => $result['return_message'] ?? 'unknown',
                'sub_return_code' => $result['sub_return_code'] ?? 'unknown',
                'sub_return_message' => $result['sub_return_message'] ?? 'unknown',
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('ZaloPay Create Order Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get app_trans_id from last created order
     */
    public function getLastAppTransId(): ?string
    {
        return session('zalopay_app_trans_id');
    }

    /**
     * Create order and return both URL and app_trans_id
     */
    public function createOrder(array $data): array
    {
        try {
            $appTransId = $this->genTransId();
            $appTime = round(microtime(true) * 1000);
            
            $embedData = json_encode([
                'redirecturl' => $this->redirectUrl
            ]);
            
            $items = json_encode($data['items'] ?? []);
            
            $order = [
                'app_id' => (int) $this->appId,
                'app_trans_id' => $appTransId,
                'app_user' => $data['app_user'] ?? 'user_' . ($data['user_id'] ?? '0'),
                'app_time' => $appTime,
                'amount' => (int) $data['amount'],
                'item' => $items,
                'embed_data' => $embedData,
                'description' => $data['description'] ?? 'Thanh toán đơn hàng',
                'bank_code' => $data['bank_code'] ?? '',
                'callback_url' => $this->callbackUrl,
            ];

            $macData = $order['app_id'] . '|' . $order['app_trans_id'] . '|' . $order['app_user'] . '|' 
                     . $order['amount'] . '|' . $order['app_time'] . '|' . $order['embed_data'] . '|' . $order['item'];
            $order['mac'] = $this->computeMac($macData);

            $response = Http::asForm()->post($this->createOrderUrl, $order);
            $result = $response->json();

            if (isset($result['return_code']) && $result['return_code'] == 1) {
                session(['zalopay_app_trans_id' => $appTransId]);
                return [
                    'success' => true,
                    'order_url' => $result['order_url'],
                    'app_trans_id' => $appTransId,
                    'zp_trans_token' => $result['zp_trans_token'] ?? null,
                ];
            }

            return [
                'success' => false,
                'message' => $result['return_message'] ?? 'Tạo đơn hàng thất bại',
            ];
        } catch (\Exception $e) {
            Log::error('ZaloPay Create Order Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify callback from ZaloPay
     */
    public function verifyCallback(array $params): array
    {
        $data = $params['data'] ?? '';
        $requestMac = $params['mac'] ?? '';

        $mac = $this->computeMac($data, $this->key2);

        if ($mac !== $requestMac) {
            return [
                'return_code' => -1,
                'return_message' => 'mac not equal'
            ];
        }

        return [
            'return_code' => 1,
            'return_message' => 'success'
        ];
    }

    /**
     * Verify redirect checksum
     */
    public function verifyRedirect(array $data): bool
    {
        $checksum = $data['checksum'] ?? '';
        
        $macData = $data['appid'] . '|' . $data['apptransid'] . '|' . $data['pmcid'] . '|' . $data['bankcode']
                 . '|' . $data['amount'] . '|' . $data['discountamount'] . '|' . $data['status'];
        
        $computedChecksum = $this->computeMac($macData, $this->key2);

        return $checksum === $computedChecksum;
    }

    /**
     * Query order status
     */
    public function queryOrder(string $appTransId): array
    {
        try {
            $params = [
                'app_id' => (int) $this->appId,
                'app_trans_id' => $appTransId,
            ];

            $macData = $params['app_id'] . '|' . $params['app_trans_id'] . '|' . $this->key1;
            $params['mac'] = $this->computeMac($macData);

            $response = Http::asForm()->post($this->queryOrderUrl, $params);
            return $response->json() ?? [];
        } catch (\Exception $e) {
            Log::error('ZaloPay Query Order Exception: ' . $e->getMessage());
            return ['return_code' => -1, 'return_message' => $e->getMessage()];
        }
    }

    /**
     * Refund order
     */
    public function refund(string $zpTransId, int $amount, string $description): array
    {
        try {
            $timestamp = round(microtime(true) * 1000);
            $mRefundId = date('ymd') . '_' . $this->appId . '_' . time() . rand(100, 999);

            $params = [
                'app_id' => (int) $this->appId,
                'zp_trans_id' => $zpTransId,
                'm_refund_id' => $mRefundId,
                'amount' => $amount,
                'timestamp' => $timestamp,
                'description' => $description,
            ];

            $macData = $params['app_id'] . '|' . $params['zp_trans_id'] . '|' . $params['amount'] 
                     . '|' . $params['description'] . '|' . $params['timestamp'];
            $params['mac'] = $this->computeMac($macData);

            $response = Http::asForm()->post($this->refundUrl, $params);
            $result = $response->json();
            $result['m_refund_id'] = $mRefundId;

            return $result ?? [];
        } catch (\Exception $e) {
            Log::error('ZaloPay Refund Exception: ' . $e->getMessage());
            return ['return_code' => -1, 'return_message' => $e->getMessage()];
        }
    }
}
