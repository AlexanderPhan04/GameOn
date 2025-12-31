<?php

namespace App\Http\Controllers;

use App\Services\VnpayService;
use App\Models\MarketplaceOrder;
use App\Models\MarketplaceOrderItem;
use App\Models\UserInventory;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $vnpayService;

    public function __construct(VnpayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
    }

    /**
     * Tạo đơn hàng và chuyển hướng đến VNPay
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function createPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'amount' => 'required|numeric|min:1000',
            'order_desc' => 'required|string',
            'order_type' => 'nullable|string',
            'language' => 'nullable|string|in:vn,en',
            'bank_code' => 'nullable|string',
        ]);

        try {
            $data = [
                'order_id' => $request->order_id,
                'amount' => $request->amount,
                'order_desc' => $request->order_desc,
                'order_type' => $request->order_type ?? 'other',
                'language' => $request->language ?? 'vn',
                'bank_code' => $request->bank_code,
                'expire_date' => $request->txtexpire ?? null,
            ];

            // Thông tin billing (nếu có)
            if ($request->has('txt_billing_fullname')) {
                $data['billing'] = [
                    'fullname' => $request->txt_billing_fullname,
                    'email' => $request->txt_billing_email,
                    'mobile' => $request->txt_billing_mobile,
                    'address' => $request->txt_billing_addr1 ?? $request->txt_inv_addr1,
                    'city' => $request->txt_bill_city,
                    'country' => $request->txt_bill_country ?? 'VN',
                    'state' => $request->txt_bill_state,
                ];
            }

            // Thông tin invoice (nếu có)
            if ($request->has('txt_inv_customer')) {
                $data['invoice'] = [
                    'customer' => $request->txt_inv_customer,
                    'company' => $request->txt_inv_company,
                    'address' => $request->txt_inv_addr1,
                    'taxcode' => $request->txt_inv_taxcode,
                    'type' => $request->cbo_inv_type ?? 'I',
                    'email' => $request->txt_inv_email,
                    'phone' => $request->txt_inv_mobile,
                ];
            }

            $paymentUrl = $this->vnpayService->createPaymentUrl($data);

            // Nếu có redirect parameter, chuyển hướng trực tiếp
            if ($request->has('redirect')) {
                return redirect($paymentUrl);
            }

            // Trả về JSON cho AJAX
            return response()->json([
                'code' => '00',
                'message' => 'success',
                'data' => $paymentUrl
            ]);

        } catch (\Exception $e) {
            Log::error('VNPay Create Payment Error: ' . $e->getMessage());

            if ($request->has('redirect')) {
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo đơn hàng.');
            }

            return response()->json([
                'code' => '99',
                'message' => 'error',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xử lý callback từ VNPay sau khi thanh toán
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function vnpayReturn(Request $request)
    {
        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'vnp_') === 0) {
                $inputData[$key] = $value;
            }
        }

        $vnpSecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);

        // Xác thực chữ ký
        $isValid = $this->vnpayService->verifySignature($inputData);

        $orderId = $inputData['vnp_TxnRef'] ?? '';
        $amount = $inputData['vnp_Amount'] ?? 0;
        $responseCode = $inputData['vnp_ResponseCode'] ?? '';
        $transactionNo = $inputData['vnp_TransactionNo'] ?? '';
        $bankCode = $inputData['vnp_BankCode'] ?? '';
        $payDate = $inputData['vnp_PayDate'] ?? '';
        $orderInfo = $inputData['vnp_OrderInfo'] ?? '';

        $status = 'error';
        $message = 'Giao dịch không thành công';

        if ($isValid) {
            if ($responseCode == '00') {
                $status = 'success';
                $message = 'Giao dịch thành công';
                
                // Cập nhật trạng thái đơn hàng/donation
                $this->updatePaymentStatus($orderId, 'success', $transactionNo, $bankCode, $amount / 100);
            } else {
                $status = 'failed';
                $message = 'Giao dịch không thành công';
                
                // Cập nhật trạng thái thất bại
                $this->updatePaymentStatus($orderId, 'failed', null, null, $amount / 100);
            }
        } else {
            $status = 'invalid';
            $message = 'Chữ ký không hợp lệ';
        }

        return view('payment.return', compact(
            'status',
            'message',
            'orderId',
            'amount',
            'responseCode',
            'transactionNo',
            'bankCode',
            'payDate',
            'orderInfo',
            'isValid'
        ));
    }

    /**
     * Xử lý IPN (Instant Payment Notification) từ VNPay
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function vnpayIpn(Request $request)
    {
        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'vnp_') === 0) {
                $inputData[$key] = $value;
            }
        }

        $returnData = [
            'RspCode' => '99',
            'Message' => 'Unknown error'
        ];

        try {
            // Xác thực chữ ký
            $isValid = $this->vnpayService->verifySignature($inputData);

            if (!$isValid) {
                $returnData['RspCode'] = '97';
                $returnData['Message'] = 'Invalid signature';
                return response()->json($returnData);
            }

            $orderId = $inputData['vnp_TxnRef'] ?? '';
            $vnpAmount = ($inputData['vnp_Amount'] ?? 0) / 100;
            $vnpTransactionNo = $inputData['vnp_TransactionNo'] ?? '';
            $vnpBankCode = $inputData['vnp_BankCode'] ?? '';
            $responseCode = $inputData['vnp_ResponseCode'] ?? '';
            $transactionStatus = $inputData['vnp_TransactionStatus'] ?? '';

            // Kiểm tra là marketplace order hay donation
            $order = MarketplaceOrder::where('order_id', $orderId)->first();
            $donation = null;
            
            if (!$order) {
                $donation = Donation::where('donation_id', $orderId)->first();
            }

            if (!$order && !$donation) {
                $returnData['RspCode'] = '01';
                $returnData['Message'] = 'Order not found';
                return response()->json($returnData);
            }

            // Kiểm tra số tiền
            if ($order && abs($order->final_amount - $vnpAmount) > 0.01) {
                $returnData['RspCode'] = '04';
                $returnData['Message'] = 'Invalid amount';
                return response()->json($returnData);
            }

            if ($donation && abs($donation->amount - $vnpAmount) > 0.01) {
                $returnData['RspCode'] = '04';
                $returnData['Message'] = 'Invalid amount';
                return response()->json($returnData);
            }

            // Kiểm tra trạng thái (tránh xử lý trùng lặp)
            if ($order && $order->payment_status === 'paid') {
                $returnData['RspCode'] = '02';
                $returnData['Message'] = 'Order already confirmed';
                return response()->json($returnData);
            }

            if ($donation && $donation->payment_status === 'paid') {
                $returnData['RspCode'] = '02';
                $returnData['Message'] = 'Donation already confirmed';
                return response()->json($returnData);
            }

            // Cập nhật trạng thái
            if ($responseCode == '00' || $transactionStatus == '00') {
                if ($order) {
                    DB::transaction(function() use ($order, $vnpTransactionNo, $vnpBankCode) {
                        $order->payment_status = 'paid';
                        $order->status = 'completed';
                        $order->vnpay_transaction_no = $vnpTransactionNo;
                        $order->vnpay_bank_code = $vnpBankCode;
                        $order->paid_at = now();
                        $order->save();

                        // Thêm items vào inventory
                        foreach ($order->items as $item) {
                            for ($i = 0; $i < $item->quantity; $i++) {
                                UserInventory::create([
                                    'user_id' => $order->user_id,
                                    'product_id' => $item->product_id,
                                    'order_id' => $order->id,
                                    'quantity' => 1,
                                ]);
                            }
                            
                            // Cập nhật số lượng đã bán
                            $item->product->increment('sold_count', $item->quantity);
                        }
                    });
                } elseif ($donation) {
                    $donation->payment_status = 'paid';
                    $donation->status = 'completed';
                    $donation->vnpay_transaction_no = $vnpTransactionNo;
                    $donation->vnpay_bank_code = $vnpBankCode;
                    $donation->paid_at = now();
                    $donation->save();
                }

                $returnData['RspCode'] = '00';
                $returnData['Message'] = 'Confirm Success';
            } else {
                if ($order) {
                    $order->payment_status = 'failed';
                    $order->status = 'cancelled';
                    $order->save();
                } elseif ($donation) {
                    $donation->payment_status = 'failed';
                    $donation->status = 'cancelled';
                    $donation->save();
                }

                $returnData['RspCode'] = '00';
                $returnData['Message'] = 'Transaction failed';
            }

        } catch (\Exception $e) {
            Log::error('VNPay IPN Error: ' . $e->getMessage());
            $returnData['RspCode'] = '99';
            $returnData['Message'] = 'Unknown error: ' . $e->getMessage();
        }

        return response()->json($returnData);
    }

    /**
     * Query transaction từ VNPay
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function queryTransaction(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'payment_date' => 'required|string',
        ]);

        try {
            $result = $this->vnpayService->queryTransaction(
                $request->order_id,
                $request->payment_date
            );

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('VNPay Query Error: ' . $e->getMessage());
            return response()->json([
                'code' => '99',
                'message' => 'Query failed',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật trạng thái thanh toán cho order hoặc donation
     */
    protected function updatePaymentStatus($orderId, $status, $transactionNo = null, $bankCode = null, $amount = null)
    {
        try {
            // Kiểm tra là marketplace order hay donation
            $order = MarketplaceOrder::where('order_id', $orderId)->first();
            $donation = null;
            
            if (!$order) {
                $donation = Donation::where('donation_id', $orderId)->first();
            }

            if (!$order && !$donation) {
                return false;
            }

            DB::transaction(function() use ($order, $donation, $status, $transactionNo, $bankCode, $amount) {
                if ($order) {
                    if ($status === 'success') {
                        $order->payment_status = 'paid';
                        $order->status = 'completed';
                        $order->vnpay_transaction_no = $transactionNo;
                        $order->vnpay_bank_code = $bankCode;
                        $order->paid_at = now();
                        $order->save();

                        // Thêm items vào inventory
                        foreach ($order->items as $item) {
                            for ($i = 0; $i < $item->quantity; $i++) {
                                UserInventory::create([
                                    'user_id' => $order->user_id,
                                    'product_id' => $item->product_id,
                                    'order_id' => $order->id,
                                    'quantity' => 1,
                                ]);
                            }
                            
                            // Cập nhật số lượng đã bán
                            $item->product->increment('sold_count', $item->quantity);
                        }
                    } else {
                        $order->payment_status = 'failed';
                        $order->status = 'cancelled';
                        $order->save();
                    }
                } elseif ($donation) {
                    if ($status === 'success') {
                        $donation->payment_status = 'paid';
                        $donation->status = 'completed';
                        $donation->vnpay_transaction_no = $transactionNo;
                        $donation->vnpay_bank_code = $bankCode;
                        $donation->paid_at = now();
                        $donation->save();
                    } else {
                        $donation->payment_status = 'failed';
                        $donation->status = 'cancelled';
                        $donation->save();
                    }
                }
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Update Payment Status Error: ' . $e->getMessage());
            return false;
        }
    }
}

