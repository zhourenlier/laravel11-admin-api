<?php
declare(strict_types=1);

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
     * AdminLoginEvent constructor.
     * @param int $type
     * @param int $admin_id
     * @param array $data
     */
    public function __construct(int $type ,int $admin_id ,array $data)
    {
        $this->admin_id = $admin_id;
        $this->type = $type;
        $this->data = $data;
    }
}
