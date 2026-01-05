<?php

namespace App\Http\Controllers;

use App\Events\CartUpdated;
use App\Models\MarketplaceProduct;
use App\Models\MarketplaceOrder;
use App\Models\MarketplaceOrderItem;
use App\Models\UserInventory;
use App\Models\Donation;
use App\Models\User;
use App\Services\ZalopayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MarketplaceController extends Controller
{
    protected $zalopayService;

    public function __construct(ZalopayService $zalopayService)
    {
        $this->zalopayService = $zalopayService;
    }

    /**
     * Hiển thị danh sách sản phẩm
     */
    public function index(Request $request)
    {
        $query = MarketplaceProduct::where('is_active', true);

        // Lọc theo loại
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Lọc theo danh mục
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Tìm kiếm
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sắp xếp
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(12);

        return view('marketplace.index', compact('products'));
    }

    /**
     * Hiển thị chi tiết sản phẩm
     */
    public function show($id)
    {
        $product = MarketplaceProduct::findOrFail($id);

        if (!$product->is_active) {
            abort(404);
        }

        // Kiểm tra user đã sở hữu sản phẩm chưa
        $owned = false;
        if (Auth::check()) {
            $owned = UserInventory::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->exists();
        }

        // Sản phẩm liên quan
        $relatedProducts = MarketplaceProduct::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('type', $product->type)
            ->limit(4)
            ->get();

        return view('marketplace.show', compact('product', 'owned', 'relatedProducts'));
    }

    /**
     * Thêm vào giỏ hàng (session)
     */
    public function addToCart(Request $request, $id)
    {
        $product = MarketplaceProduct::findOrFail($id);

        if (!$product->is_active || !$product->isInStock()) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không khả dụng'
            ], 400);
        }

        $cart = session()->get('cart', []);
        $quantity = $request->get('quantity', 1);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->current_price,
                'thumbnail' => $product->thumbnail,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        // Broadcast cart update
        if (Auth::check()) {
            broadcast(new CartUpdated(
                Auth::id(),
                count($cart),
                'add',
                $product->id,
                $product->name
            ))->toOthers();
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào giỏ hàng',
            'cart_count' => count($cart)
        ]);
    }

    /**
     * Xem giỏ hàng
     */
    public function cart()
    {
        $cart = session()->get('cart', []);
        $items = [];
        $total = 0;

        foreach ($cart as $item) {
            $product = MarketplaceProduct::find($item['id']);
            if ($product && $product->is_active) {
                $subtotal = $product->current_price * $item['quantity'];
                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        return view('marketplace.cart', compact('items', 'total'));
    }

    /**
     * Xóa khỏi giỏ hàng
     */
    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);
        $productName = isset($cart[$id]) ? $cart[$id]['name'] : null;
        unset($cart[$id]);
        session()->put('cart', $cart);

        // Broadcast cart update
        if (Auth::check()) {
            broadcast(new CartUpdated(
                Auth::id(),
                count($cart),
                'remove',
                $id,
                $productName
            ))->toOthers();
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa khỏi giỏ hàng',
            'cart_count' => count($cart)
        ]);
    }

    /**
     * Cập nhật số lượng trong giỏ hàng
     */
    public function updateCartQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $product = MarketplaceProduct::find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại'
            ], 404);
        }

        $quantity = $request->quantity;

        // Check stock limit (-1 means unlimited)
        if ($product->stock != -1 && $quantity > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng vượt quá kho hàng (còn ' . $product->stock . ' sản phẩm)',
                'max_quantity' => $product->stock
            ], 400);
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);

            $subtotal = $product->current_price * $quantity;
            
            // Calculate new total
            $total = 0;
            foreach ($cart as $item) {
                $p = MarketplaceProduct::find($item['id']);
                if ($p) {
                    $total += $p->current_price * $item['quantity'];
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật số lượng',
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'total' => $total,
                'cart_count' => count($cart)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Sản phẩm không có trong giỏ hàng'
        ], 404);
    }

    /**
     * Thanh toán
     */
    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')->with('error', 'Vui lòng đăng nhập để thanh toán');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('marketplace.index')->with('error', 'Giỏ hàng trống');
        }

        // Tính tổng tiền
        $total = 0;
        $items = [];
        foreach ($cart as $item) {
            $product = MarketplaceProduct::find($item['id']);
            if ($product && $product->is_active && $product->isInStock()) {
                $subtotal = $product->current_price * $item['quantity'];
                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        if (empty($items)) {
            return redirect()->route('marketplace.cart')->with('error', 'Giỏ hàng không hợp lệ');
        }

        return view('marketplace.checkout', compact('items', 'total'));
    }

    /**
     * Xử lý thanh toán
     */
    public function processPayment(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập'], 401);
        }

        $request->validate([
            'payment_method' => 'required|in:zalopay',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Giỏ hàng trống'], 400);
        }

        DB::beginTransaction();
        try {
            // Tạo đơn hàng
            $order = new MarketplaceOrder();
            $order->user_id = Auth::id();
            $order->total_amount = 0;
            $order->discount_amount = 0;
            $order->final_amount = 0;
            $order->status = 'pending';
            $order->payment_status = 'pending';
            $order->payment_method = 'zalopay';
            $order->notes = $request->notes;
            $order->save();

            // Tính tổng và tạo order items
            $total = 0;
            $itemsData = [];
            foreach ($cart as $item) {
                $product = MarketplaceProduct::find($item['id']);
                if ($product && $product->is_active && $product->isInStock()) {
                    $price = $product->current_price;
                    $quantity = $item['quantity'];
                    $subtotal = $price * $quantity;

                    $orderItem = new MarketplaceOrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id = $product->id;
                    $orderItem->quantity = $quantity;
                    $orderItem->price = $product->price;
                    $orderItem->discount_price = $product->discount_price;
                    $orderItem->subtotal = $subtotal;
                    $orderItem->save();

                    $total += $subtotal;
                    
                    $itemsData[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $price,
                        'quantity' => $quantity,
                    ];
                }
            }

            $order->total_amount = $total;
            $order->final_amount = $total;
            $order->save();

            // Tạo payment URL với ZaloPay
            $paymentResult = $this->zalopayService->createOrder([
                'user_id' => Auth::id(),
                'app_user' => 'user_' . Auth::id(),
                'amount' => (int) $total,
                'description' => 'Thanh toán đơn hàng #' . $order->order_id,
                'items' => $itemsData,
            ]);

            if (!$paymentResult['success']) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $paymentResult['message'] ?? 'Không thể tạo thanh toán'
                ], 500);
            }

            // Lưu app_trans_id vào order
            $order->zalopay_trans_id = $paymentResult['app_trans_id'];
            $order->save();

            DB::commit();

            // Xóa giỏ hàng
            session()->forget('cart');

            return response()->json([
                'success' => true,
                'payment_url' => $paymentResult['order_url'],
                'order_id' => $order->order_id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Marketplace Payment Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kho đồ của user
     */
    public function inventory()
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login');
        }

        $inventory = UserInventory::where('user_id', Auth::id())
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('marketplace.inventory', compact('inventory'));
    }

    /**
     * Trang bị/Tháo item
     */
    public function equipItem(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập'], 401);
        }

        $inventory = UserInventory::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $equip = $request->get('equip', true);

        if ($equip) {
            // Tháo các item cùng loại
            if ($inventory->equipment_slot) {
                UserInventory::where('user_id', Auth::id())
                    ->where('equipment_slot', $inventory->equipment_slot)
                    ->where('id', '!=', $id)
                    ->update(['is_equipped' => false]);
            }

            $inventory->is_equipped = true;
        } else {
            $inventory->is_equipped = false;
        }

        $inventory->save();

        return response()->json([
            'success' => true,
            'message' => $equip ? 'Đã trang bị' : 'Đã tháo'
        ]);
    }

    /**
     * Quyên góp cho người dùng
     */
    public function donate(Request $request, $userId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'message' => 'nullable|string|max:500',
            'is_anonymous' => 'boolean',
        ]);

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập'], 401);
        }

        $recipient = User::findOrFail($userId);
        if (!$recipient->isParticipant()) {
            return response()->json(['success' => false, 'message' => 'Chỉ có thể quyên góp cho Participant'], 400);
        }

        DB::beginTransaction();
        try {
            $donation = new Donation();
            $donation->donor_id = Auth::id();
            $donation->recipient_id = $userId;
            $donation->amount = $request->amount;
            $donation->message = $request->message;
            $donation->is_anonymous = $request->get('is_anonymous', false);
            $donation->status = 'pending';
            $donation->payment_status = 'pending';
            $donation->payment_method = 'vnpay';
            $donation->save();

            // Tạo payment URL
            $paymentData = [
                'order_id' => $donation->donation_id,
                'amount' => $request->amount,
                'order_desc' => 'Quyên góp cho ' . ($donation->is_anonymous ? 'Người dùng' : $recipient->name),
                'order_type' => 'other',
                'language' => 'vn',
            ];

            $paymentUrl = $this->vnpayService->createPaymentUrl($paymentData);

            DB::commit();

            return response()->json([
                'success' => true,
                'payment_url' => $paymentUrl,
                'donation_id' => $donation->donation_id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Donation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
