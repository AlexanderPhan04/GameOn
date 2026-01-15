<?php

namespace App\Http\Controllers;

use App\Events\CartUpdated;
use App\Models\MarketplaceProduct;
use App\Models\MarketplaceOrder;
use App\Models\MarketplaceOrderItem;
use App\Models\UserInventory;
use App\Models\Donation;
use App\Models\User;
use App\Services\PayosService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MarketplaceController extends Controller
{
    protected $payosService;

    public function __construct(PayosService $payosService)
    {
        $this->payosService = $payosService;
    }

    /**
     * Hiển thị danh sách sản phẩm
     */
    public function index(Request $request)
    {
        $query = MarketplaceProduct::where('is_active', true);

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(12);

        return view('marketplace.index', compact('products'));
    }

    /**
     * Hiển thị chi tiết sản phẩm
     */
    public function show(MarketplaceProduct $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $owned = false;
        if (Auth::check()) {
            $owned = UserInventory::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->exists();
        }

        $relatedProducts = MarketplaceProduct::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('type', $product->type)
            ->limit(4)
            ->get();

        return view('marketplace.show', compact('product', 'owned', 'relatedProducts'));
    }

    /**
     * Thêm vào giỏ hàng
     */
    public function addToCart(Request $request, $id)
    {
        $product = MarketplaceProduct::findOrFail($id);

        if (!$product->is_active || !$product->isInStock()) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không khả dụng hoặc đã hết hàng'
            ], 400);
        }

        // Kiểm tra điều kiện mua vé giải đấu
        if ($product->type === 'tournament_ticket') {
            $validationResult = $this->validateTournamentTicketPurchase($product);
            if (!$validationResult['success']) {
                return response()->json($validationResult, 400);
            }
        }

        $cart = session()->get('cart', []);
        $quantity = max(1, intval($request->get('quantity', 1))); // Đảm bảo quantity >= 1
        
        // Tính tổng số lượng trong giỏ (hiện tại + thêm mới)
        $currentInCart = isset($cart[$id]) ? $cart[$id]['quantity'] : 0;
        $totalQuantity = $currentInCart + $quantity;
        
        // Kiểm tra số lượng tồn kho (nếu không phải unlimited)
        if ($product->stock != -1) {
            if ($totalQuantity > $product->stock) {
                $available = $product->stock - $currentInCart;
                if ($available <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bạn đã thêm tối đa số lượng có thể mua cho sản phẩm này'
                    ], 400);
                }
                return response()->json([
                    'success' => false,
                    'message' => "Chỉ còn {$available} sản phẩm có thể thêm vào giỏ (tồn kho: {$product->stock})"
                ], 400);
            }
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $totalQuantity;
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
     * Kiểm tra điều kiện mua vé giải đấu
     */
    private function validateTournamentTicketPurchase(MarketplaceProduct $product): array
    {
        if (!Auth::check()) {
            return [
                'success' => false,
                'message' => 'Vui lòng đăng nhập để mua vé giải đấu'
            ];
        }

        $user = Auth::user();
        $tournament = $product->tournament;

        if (!$tournament) {
            return [
                'success' => false,
                'message' => 'Giải đấu không tồn tại'
            ];
        }

        // Kiểm tra user có team không
        $userTeams = $user->teams()
            ->wherePivot('status', 'active')
            ->get();

        if ($userTeams->isEmpty()) {
            return [
                'success' => false,
                'message' => 'Bạn cần tham gia một đội để mua vé giải đấu'
            ];
        }

        // Kiểm tra có team nào chơi game của giải đấu không
        $matchingTeam = $userTeams->first(function ($team) use ($tournament) {
            return $team->game_id === $tournament->game_id;
        });

        if (!$matchingTeam) {
            $gameName = $tournament->game?->name ?? 'game này';
            return [
                'success' => false,
                'message' => "Bạn cần có đội chơi {$gameName} để mua vé giải đấu này"
            ];
        }

        // Kiểm tra đã mua vé cho giải đấu này chưa
        $existingTicket = \App\Models\TournamentTicket::where('user_id', $user->id)
            ->where('tournament_id', $tournament->id)
            ->whereIn('status', ['valid', 'used'])
            ->exists();

        if ($existingTicket) {
            return [
                'success' => false,
                'message' => 'Bạn đã có vé cho giải đấu này'
            ];
        }

        return ['success' => true, 'team' => $matchingTeam];
    }

    /**
     * Xem giỏ hàng
     */
    public function cart()
    {
        $cart = session()->get('cart', []);
        $items = [];
        $total = 0;
        $hasRestrictedItems = false;

        foreach ($cart as $item) {
            $product = MarketplaceProduct::find($item['id']);
            if ($product) {
                $isRestricted = !$product->is_active || !$product->isInStock();
                $subtotal = $isRestricted ? 0 : $product->current_price * $item['quantity'];
                
                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                    'is_restricted' => $isRestricted,
                ];
                
                if (!$isRestricted) {
                    $total += $subtotal;
                } else {
                    $hasRestrictedItems = true;
                }
            }
        }

        return view('marketplace.cart', compact('items', 'total', 'hasRestrictedItems'));
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
            'quantity' => 'required|integer|min:1|max:9999'
        ]);

        $product = MarketplaceProduct::find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại'
            ], 404);
        }
        
        // Kiểm tra sản phẩm còn active và còn hàng
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không còn khả dụng'
            ], 400);
        }

        $quantity = max(1, intval($request->quantity)); // Đảm bảo >= 1

        // Kiểm tra số lượng tồn kho
        if ($product->stock != -1) {
            if ($product->stock <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm đã hết hàng'
                ], 400);
            }
            if ($quantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng vượt quá kho hàng (còn ' . $product->stock . ' sản phẩm)',
                    'max_quantity' => $product->stock
                ], 400);
            }
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);

            $subtotal = $product->current_price * $quantity;
            
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
     * Xử lý thanh toán với PayOS
     */
    public function processPayment(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập'], 401);
        }

        $request->validate([
            'payment_method' => 'required|in:payos',
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
            $order->payment_method = 'payos';
            $order->notes = $request->notes;
            $order->save();

            // Tính tổng và tạo order items
            $total = 0;
            $payosItems = [];
            $ticketTeams = []; // Lưu team cho mỗi vé giải đấu
            
            foreach ($cart as $item) {
                $product = MarketplaceProduct::find($item['id']);
                if ($product && $product->is_active && $product->isInStock()) {
                    // Double-check vé giải đấu
                    if ($product->type === 'tournament_ticket') {
                        $validationResult = $this->validateTournamentTicketPurchase($product);
                        if (!$validationResult['success']) {
                            throw new \Exception($validationResult['message']);
                        }
                        $ticketTeams[$product->id] = $validationResult['team'];
                    }
                    
                    $price = $product->current_price;
                    $quantity = (int) $item['quantity'];
                    
                    // Đảm bảo quantity >= 1
                    if ($quantity < 1) {
                        $quantity = 1;
                    }
                    
                    $subtotal = $price * $quantity;

                    $orderItem = new MarketplaceOrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id = $product->id;
                    $orderItem->quantity = $quantity;
                    $orderItem->price = $product->price;
                    $orderItem->discount_price = $product->discount_price;
                    $orderItem->subtotal = $subtotal;
                    
                    // Lưu team_id cho vé giải đấu
                    if ($product->type === 'tournament_ticket' && isset($ticketTeams[$product->id])) {
                        $orderItem->metadata = [
                            'team_id' => $ticketTeams[$product->id]->id,
                            'tournament_id' => $product->tournament_id,
                        ];
                    }
                    
                    $orderItem->save();

                    $total += $subtotal;
                    
                    $payosItems[] = [
                        'name' => mb_substr($product->name, 0, 25),
                        'quantity' => $quantity,
                        'price' => (int) $price,
                    ];
                }
            }

            $order->total_amount = $total;
            $order->final_amount = $total;
            $order->save();

            // Tạo order_code cho PayOS (số nguyên duy nhất)
            $orderCode = intval(substr(strval(microtime(true) * 10000), -8));
            $order->order_code = $orderCode;
            $order->save();

            // Tạo payment link với PayOS
            $result = $this->payosService->createPaymentLink([
                'order_code' => $orderCode,
                'amount' => (int) $total,
                'description' => 'DH ' . $order->order_id,
                'items' => $payosItems,
                'return_url' => route('payment.success'),
                'cancel_url' => route('payment.cancel'),
                'buyer_name' => Auth::user()->name,
                'buyer_email' => Auth::user()->email,
            ]);

            DB::commit();

            // Xóa giỏ hàng
            session()->forget('cart');

            return response()->json([
                'success' => true,
                'payment_url' => $result['checkout_url'],
                'order_id' => $order->order_id,
                'order_code' => $orderCode,
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
            'amount' => 'required|numeric|min:2000',
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
            $donation->payment_method = 'payos';
            $donation->save();

            // Tạo order_code cho PayOS
            $orderCode = intval(substr(strval(microtime(true) * 10000), -8));
            $donation->order_code = $orderCode;
            $donation->save();

            // Tạo payment link
            $result = $this->payosService->createPaymentLink([
                'order_code' => $orderCode,
                'amount' => (int) $request->amount,
                'description' => 'Donate ' . ($donation->is_anonymous ? 'User' : $recipient->name),
                'return_url' => route('payment.success'),
                'cancel_url' => route('payment.cancel'),
                'buyer_name' => Auth::user()->name,
                'buyer_email' => Auth::user()->email,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'payment_url' => $result['checkout_url'],
                'donation_id' => $donation->donation_id,
                'order_code' => $orderCode,
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

    /**
     * Lịch sử đơn hàng
     */
    public function orderHistory()
    {
        $orders = MarketplaceOrder::where('user_id', Auth::id())
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('marketplace.order-history', compact('orders'));
    }

    /**
     * Chi tiết đơn hàng
     */
    public function orderDetail($orderCode)
    {
        $order = MarketplaceOrder::where('user_id', Auth::id())
            ->where('order_id', $orderCode)
            ->with(['items.product', 'user'])
            ->firstOrFail();

        return view('marketplace.order-detail', compact('order'));
    }

    /**
     * Xuất hóa đơn PDF
     */
    public function downloadInvoice($orderCode)
    {
        $order = MarketplaceOrder::where('user_id', Auth::id())
            ->where('order_id', $orderCode)
            ->with(['items.product', 'user'])
            ->firstOrFail();

        // Chỉ cho xuất hóa đơn khi đã thanh toán
        if (!$order->isPaid()) {
            return redirect()->route('marketplace.orderDetail', $orderCode)
                ->with('error', 'Chỉ có thể xuất hóa đơn cho đơn hàng đã thanh toán');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('marketplace.invoice', compact('order'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('hoa-don-' . $order->order_id . '.pdf');
    }
}
