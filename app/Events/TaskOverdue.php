<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Queue\SerializesModels;

class TaskOverdue implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reminder;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct($reminder, $user)
    {
        $this->reminder = $reminder;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        // Para transmitir um evento em vários canais simultaneamente, você pode retornar um array de canais:
        // return [
        //     new PrivateChannel('channel-name'),
        // ];

        return new PrivateChannel('notifications.' . $this->user->id);
    }

    public function broadcastWith()
    {
        return ['data' => $this->reminder];
    }
}
