<?php

namespace App\Http\Controllers;

use App\Services\PayosService;
use App\Models\MarketplaceOrder;
use App\Models\UserInventory;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $payosService;

    public function __construct(PayosService $payosService)
    {
        $this->payosService = $payosService;
    }

    /**
     * Tạo link thanh toán PayOS
     */
    public function createPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'amount' => 'required|numeric|min:2000',
            'description' => 'required|string',
        ]);

        try {
            $result = $this->payosService->createPaymentLink([
                'order_code' => intval(substr(str_replace(['ORD', 'DON'], '', $request->order_id), 0, 6) . time() % 1000000),
                'amount' => (int) $request->amount,
                'description' => $request->description,
                'return_url' => route('payment.success'),
                'cancel_url' => route('payment.cancel'),
            ]);

            // Lưu order_code vào order/donation
            $order = MarketplaceOrder::where('order_id', $request->order_id)->first();
            if ($order) {
                $order->order_code = $result['order_code'];
                $order->save();
            } else {
                $donation = Donation::where('donation_id', $request->order_id)->first();
                if ($donation) {
                    $donation->order_code = $result['order_code'];
                    $donation->save();
                }
            }

            return response()->json([
                'success' => true,
                'checkout_url' => $result['checkout_url'],
                'order_code' => $result['order_code'],
            ]);

        } catch (\Exception $e) {
            Log::error('PayOS Create Payment Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xử lý callback thành công từ PayOS
     */
    public function handleSuccess(Request $request)
    {
        $orderCode = $request->get('orderCode');
        $status = $request->get('status');
        
        Log::info('PayOS Success Callback', [
            'orderCode' => $orderCode,
            'status' => $status,
            'all_params' => $request->all()
        ]);
        
        if (!$orderCode) {
            return redirect()->route('marketplace.index')->with('error', 'Không tìm thấy mã đơn hàng');
        }

        try {
            // Kiểm tra status từ URL trước
            if ($status === 'PAID') {
                $this->updatePaymentStatus($orderCode, 'success', ['id' => $request->get('id')]);
                
                // Lấy order để hiển thị amount
                $order = MarketplaceOrder::where('order_code', $orderCode)->first();
                $amount = $order ? $order->final_amount : 0;
                
                return view('marketplace.payment-success', [
                    'orderCode' => $orderCode,
                    'amount' => $amount,
                    'message' => 'Thanh toán thành công!'
                ]);
            }
            
            // Nếu không có status trong URL, gọi API để kiểm tra
            $paymentInfo = $this->payosService->getPaymentInfo((int) $orderCode);
            
            Log::info('PayOS Payment Info', $paymentInfo);
            
            if ($paymentInfo['status'] === 'PAID') {
                $this->updatePaymentStatus($orderCode, 'success', $paymentInfo);
                return view('marketplace.payment-success', [
                    'orderCode' => $orderCode,
                    'amount' => $paymentInfo['amount'] ?? 0,
                    'message' => 'Thanh toán thành công!'
                ]);
            }
            
            return redirect()->route('marketplace.index')->with('error', 'Thanh toán chưa hoàn tất');
            
        } catch (\Exception $e) {
            Log::error('PayOS Success Callback Error: ' . $e->getMessage());
            return redirect()->route('marketplace.index')->with('error', 'Có lỗi xảy ra khi xác nhận thanh toán');
        }
    }

    /**
     * Xử lý callback hủy từ PayOS
     */
    public function handleCancel(Request $request)
    {
        $orderCode = $request->get('orderCode');
        
        if ($orderCode) {
            $this->updatePaymentStatus($orderCode, 'cancelled');
        }

        return view('marketplace.payment-failed', [
            'orderCode' => $orderCode,
            'message' => 'Bạn đã hủy thanh toán'
        ]);
    }

    /**
     * Webhook từ PayOS (IPN)
     */
    public function webhook(Request $request)
    {
        try {
            $webhookData = $this->payosService->verifyWebhookData($request->all());
            
            $orderCode = $webhookData['orderCode'];
            $status = $webhookData['code'] === '00' ? 'success' : 'failed';
            
            $this->updatePaymentStatus($orderCode, $status, $webhookData);

            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            Log::error('PayOS Webhook Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Cập nhật trạng thái thanh toán
     */
    protected function updatePaymentStatus($orderCode, $status, $paymentInfo = null)
    {
        try {
            // Tìm order hoặc donation theo order_code
            $order = MarketplaceOrder::where('order_code', $orderCode)->first();
            $donation = null;
            
            if (!$order) {
                $donation = Donation::where('order_code', $orderCode)->first();
            }

            if (!$order && !$donation) {
                return false;
            }

            DB::transaction(function() use ($order, $donation, $status, $paymentInfo) {
                if ($order) {
                    if ($status === 'success') {
                        $order->payment_status = 'paid';
                        $order->status = 'completed';
                        $order->payos_transaction_id = $paymentInfo['id'] ?? null;
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
                        $donation->payos_transaction_id = $paymentInfo['id'] ?? null;
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
