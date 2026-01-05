<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CartUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $cartCount;
    public $action; // 'add', 'remove', 'clear'
    public $productId;
    public $productName;

    public function __construct($userId, $cartCount, $action = 'update', $productId = null, $productName = null)
    {
        $this->userId = $userId;
        $this->cartCount = $cartCount;
        $this->action = $action;
        $this->productId = $productId;
        $this->productName = $productName;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'cart.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'cart_count' => $this->cartCount,
            'action' => $this->action,
            'product_id' => $this->productId,
            'product_name' => $this->productName,
        ];
    }
}
