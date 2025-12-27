<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class VnpayService
{
    protected $tmnCode;
    protected $hashSecret;
    protected $url;
    protected $returnUrl;
    protected $apiUrl;
    protected $ipnUrl;

    public function __construct()
    {
        $this->tmnCode = config('vnpay.tmn_code');
        $this->hashSecret = config('vnpay.hash_secret');
        $this->url = config('vnpay.url');
        $this->returnUrl = config('vnpay.return_url');
        $this->apiUrl = config('vnpay.api_url');
        $this->ipnUrl = config('vnpay.ipn_url');
    }

    /**
     * Tạo URL thanh toán VNPay
     *
     * @param array $data
     * @return string
     */
    public function createPaymentUrl(array $data): string
    {
        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->tmnCode,
            "vnp_Amount" => $data['amount'] * 100, // VNPay yêu cầu số tiền nhân 100
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(),
            "vnp_Locale" => $data['language'] ?? 'vn',
            "vnp_OrderInfo" => $data['order_desc'] ?? '',
            "vnp_OrderType" => $data['order_type'] ?? 'other',
            "vnp_ReturnUrl" => $this->returnUrl,
            "vnp_TxnRef" => $data['order_id'],
            "vnp_ExpireDate" => $data['expire_date'] ?? $this->getExpireDate(),
        ];

        // Thông tin billing (nếu có)
        if (isset($data['billing'])) {
            $billing = $data['billing'];
            $inputData['vnp_Bill_Mobile'] = $billing['mobile'] ?? '';
            $inputData['vnp_Bill_Email'] = $billing['email'] ?? '';
            
            if (isset($billing['fullname']) && !empty($billing['fullname'])) {
                $name = explode(' ', trim($billing['fullname']));
                $inputData['vnp_Bill_FirstName'] = array_shift($name);
                $inputData['vnp_Bill_LastName'] = array_pop($name) ?? '';
            }
            
            $inputData['vnp_Bill_Address'] = $billing['address'] ?? '';
            $inputData['vnp_Bill_City'] = $billing['city'] ?? '';
            $inputData['vnp_Bill_Country'] = $billing['country'] ?? 'VN';
            $inputData['vnp_Bill_State'] = $billing['state'] ?? '';
        }

        // Thông tin invoice (nếu có)
        if (isset($data['invoice'])) {
            $invoice = $data['invoice'];
            $inputData['vnp_Inv_Phone'] = $invoice['phone'] ?? '';
            $inputData['vnp_Inv_Email'] = $invoice['email'] ?? '';
            $inputData['vnp_Inv_Customer'] = $invoice['customer'] ?? '';
            $inputData['vnp_Inv_Address'] = $invoice['address'] ?? '';
            $inputData['vnp_Inv_Company'] = $invoice['company'] ?? '';
            $inputData['vnp_Inv_Taxcode'] = $invoice['taxcode'] ?? '';
            $inputData['vnp_Inv_Type'] = $invoice['type'] ?? 'I';
        }

        // Ngân hàng (nếu có)
        if (isset($data['bank_code']) && !empty($data['bank_code'])) {
            $inputData['vnp_BankCode'] = $data['bank_code'];
        }

        // Sắp xếp lại mảng theo key
        ksort($inputData);

        // Tạo query string và hash data
        $query = "";
        $hashData = "";
        $i = 0;

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnpUrl = $this->url . "?" . $query;

        // Tạo secure hash
        if (!empty($this->hashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashData, $this->hashSecret);
            $vnpUrl .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnpUrl;
    }

    /**
     * Xác thực chữ ký từ VNPay
     *
     * @param array $data
     * @return bool
     */
    public function verifySignature(array $data): bool
    {
        $vnpSecureHash = $data['vnp_SecureHash'] ?? '';
        unset($data['vnp_SecureHash']);

        ksort($data);
        $hashData = "";
        $i = 0;

        foreach ($data as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $this->hashSecret);

        return $secureHash === $vnpSecureHash;
    }

    /**
     * Lấy ngày hết hạn thanh toán
     *
     * @return string
     */
    protected function getExpireDate(): string
    {
        $expireMinutes = config('vnpay.expire_minutes', 15);
        return date('YmdHis', strtotime("+{$expireMinutes} minutes"));
    }

    /**
     * Query transaction từ VNPay
     *
     * @param string $orderId
     * @param string $transDate
     * @return array
     */
    public function queryTransaction(string $orderId, string $transDate): array
    {
        $inputData = [
            "vnp_Version" => '2.1.0',
            "vnp_Command" => "querydr",
            "vnp_TmnCode" => $this->tmnCode,
            "vnp_TxnRef" => $orderId,
            "vnp_OrderInfo" => 'Query transaction',
            "vnp_TransDate" => $transDate,
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_IpAddr" => request()->ip()
        ];

        ksort($inputData);
        $query = "";
        $hashData = "";
        $i = 0;

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnpApiUrl = $this->apiUrl . "?" . $query;

        if (!empty($this->hashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashData, $this->hashSecret);
            $vnpApiUrl .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        $ch = curl_init($vnpApiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        return json_decode($data, true) ?? [];
    }
}

