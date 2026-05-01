<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DriverLocationUpdated implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $orderId,
        public float $lat,
        public float $lng
    ) {}

    public function broadcastOn(): array {
        return [new Channel('order.' . $this->orderId)];
    }

    public function broadcastWith(): array {
        return ['lat' => $this->lat, 'lng' => $this->lng, 'order_id' => $this->orderId];
    }
}