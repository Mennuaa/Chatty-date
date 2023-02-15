<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendGroupMessageEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private string $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('group-chat');
    }
    public function broadcastAs()
    {
        return 'group-chat-message';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            // 'user' => $this->user->only(['name', 'email'])
        ];
    }
}
