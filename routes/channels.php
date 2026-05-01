<?php
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id1}.{id2}', function ($user, $id1, $id2) {
    return in_array($user->id, [(int)$id1, (int)$id2]);
});

Broadcast::channel('order.{orderId}', function ($user, $orderId) {
    $order = \App\Models\Order::find($orderId);
    return $order && ($user->id === $order->user_id || $user->id === $order->driver_id || $user->role === 'admin');
});