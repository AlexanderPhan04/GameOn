<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceOrder;
use App\Models\MarketplaceOrderItem;
use App\Models\UserInventory;
use App\Services\ZalopayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ZalopayController extends Controller
{
    protected $zalopayService;

    public function __construct(ZalopayService $zalopayService)
    {
        $this->zalopayService = $zalopayService;
    }

    /**
     * Handle ZaloPay callback (server-to-server)
     */
    public function callback(Request $request)
    {
        Log::info('ZaloPay Callback received', $request->all());

        try {
            $params = $request->all();
            $result = $this->zalopayService->verifyCallback($params);

            if ($result['return_code'] == 1) {
                $data = json_decode($params['data'], true);
                $appTransId = $data['app_trans_id'] ?? null;

                if ($appTransId) {
                    $order = MarketplaceOrder::where('zalopay_trans_id', $appTransId)->first();

                    if ($order && $order->payment_status !== 'paid') {
                        DB::beginTransaction();
                        try {
                            $order->payment_status = 'paid';
                            $order->status = 'completed';
                            $order->zalopay_zp_trans_id = $data['zp_trans_id'] ?? null;
                            $order->paid_at = now();
                            $order->save();

                            // Add items to user inventory
                            $this->addItemsToInventory($order);

                            DB::commit();
                            Log::info('ZaloPay payment completed for order: ' . $order->order_id);
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Log::error('ZaloPay callback processing error: ' . $e->getMessage());
                        }
                    }
                }
            }

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('ZaloPay Callback Exception: ' . $e->getMessage());
            return response()->json([
                'return_code' => 0,
                'return_message' => 'exception'
            ]);
        }
    }

    /**
     * Handle ZaloPay redirect (user return)
     */
    public function return(Request $request)
    {
        Log::info('ZaloPay Return received', $request->all());

        $data = $request->all();
        $status = $data['status'] ?? null;
        $appTransId = $data['apptransid'] ?? null;

        // Verify checksum
        $isValid = $this->zalopayService->verifyRedirect($data);

        if (!$isValid) {
            return redirect()->route('marketplace.index')
                ->with('error', 'Xác thực thanh toán không hợp lệ');
        }

        // Find order
        $order = MarketplaceOrder::where('zalopay_trans_id', $appTransId)->first();

        if (!$order) {
            return redirect()->route('marketplace.index')
                ->with('error', 'Không tìm thấy đơn hàng');
        }

        // Check payment status
        if ($status == 1) {
            // Payment successful
            if ($order->payment_status !== 'paid') {
                // Query ZaloPay to confirm
                $queryResult = $this->zalopayService->queryOrder($appTransId);

                if ($queryResult['return_code'] == 1) {
                    DB::beginTransaction();
                    try {
                        $order->payment_status = 'paid';
                        $order->status = 'completed';
                        $order->zalopay_zp_trans_id = $queryResult['zp_trans_id'] ?? $data['zptransid'] ?? null;
                        $order->paid_at = now();
                        $order->save();

                        $this->addItemsToInventory($order);

                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('ZaloPay return processing error: ' . $e->getMessage());
                    }
                }
            }

            return redirect()->route('marketplace.payment.success', ['order' => $order->order_id]);
        } else {
            // Payment failed or cancelled
            $order->payment_status = 'failed';
            $order->status = 'cancelled';
            $order->save();

            return redirect()->route('marketplace.payment.failed', ['order' => $order->order_id]);
        }
    }

    /**
     * Add purchased items to user inventory
     */
    protected function addItemsToInventory(MarketplaceOrder $order)
    {
        $orderItems = MarketplaceOrderItem::where('order_id', $order->id)->get();

        foreach ($orderItems as $item) {
            for ($i = 0; $i < $item->quantity; $i++) {
                UserInventory::create([
                    'user_id' => $order->user_id,
                    'product_id' => $item->product_id,
                    'order_id' => $order->id,
                    'acquired_at' => now(),
                ]);
            }

            // Decrease stock if not unlimited
            if ($item->product && $item->product->stock > 0) {
                $item->product->decrement('stock', $item->quantity);
            }
        }
    }

    /**
     * Payment success page
     */
    public function success(Request $request, $orderId)
    {
        $order = MarketplaceOrder::where('order_id', $orderId)
            ->with('items.product')
            ->firstOrFail();

        return view('marketplace.payment-success', compact('order'));
    }

    /**
     * Payment failed page
     */
    public function failed(Request $request, $orderId)
    {
        $order = MarketplaceOrder::where('order_id', $orderId)->first();

        return view('marketplace.payment-failed', compact('order'));
    }
}
