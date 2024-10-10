<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminLoginEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;
    public $data;
    public $admin_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($type ,$admin_id ,$data)
    {
        $this->admin_id = $admin_id;
        $this->type = $type;
        $this->data = $data;
    }
}
