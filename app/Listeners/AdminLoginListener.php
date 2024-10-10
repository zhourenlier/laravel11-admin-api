<?php

namespace App\Listeners;

use App\Events\AdminLoginEvent;
use App\Models\AdminLog;
use App\Models\Rule;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AdminLoginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AdminLoginEvent  $event
     * @return void
     */
    public function handle(AdminLoginEvent $event)
    {
        $logData = [
            'admin_id' => $event->admin_id,
            'type' => $event->type,
            'ip' => $event->data['ip'] ?? "",
            'url' => $event->data['url'] ?? "",
            'method' => $event->data['method'] ?? "",
            'param' => $event->data['param'] ?? "",
            'created_at' => date('Y-m-d H:i:s')
        ];

        AdminLog::query()->insert($logData);
    }
}
