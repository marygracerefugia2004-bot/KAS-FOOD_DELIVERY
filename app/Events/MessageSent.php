<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class MessageSent implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message) {}

    public function broadcastOn(): array {
        $ids = [$this->message->sender_id, $this->message->receiver_id];
        sort($ids);
        return [new PrivateChannel('chat.' . $ids[0] . '.' . $ids[1])];
    }

    public function broadcastWith(): array {
        return [
            'id'          => $this->message->id,
            'message'     => $this->message->message,
            'sender_id'   => $this->message->sender_id,
            'sender_name' => $this->message->sender->name,
            'created_at'  => $this->message->created_at->format('h:i A'),
        ];
    }
}