<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


class PushEvent implements ShouldBroadcast 
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle($event)
    {
        
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['my-channel'];
    }
}
