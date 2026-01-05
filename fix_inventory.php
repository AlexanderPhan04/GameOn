<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$order = App\Models\MarketplaceOrder::where('order_code', '74208125')->first();
if ($order) {
    foreach ($order->items as $item) {
        App\Models\UserInventory::create([
            'user_id' => $order->user_id,
            'product_id' => $item->product_id,
            'order_id' => $order->id,
            'quantity' => 1,
        ]);
    }
    echo "Inventory added for order {$order->order_code}\n";
} else {
    echo "Order not found\n";
}
