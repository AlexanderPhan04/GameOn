<?php

namespace App\Events;

use App\Models\MarketplaceProduct;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product;
    public $action; // 'created', 'updated', 'deleted'

    public function __construct(MarketplaceProduct $product, $action = 'updated')
    {
        $this->product = $product;
        $this->action = $action;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('marketplace'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'product.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'action' => $this->action,
            'product' => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'description' => $this->product->description,
                'price' => $this->product->price,
                'current_price' => $this->product->current_price ?? $this->product->price,
                'type' => $this->product->type,
                'thumbnail' => $this->product->thumbnail ? asset('uploads/' . $this->product->thumbnail) : null,
                'is_featured' => $this->product->is_featured,
                'is_active' => $this->product->is_active,
            ],
        ];
    }
}
