<?php

namespace App\Repository;

use App\Events\AdminLoginEvent;
use App\Exceptions\AdminException;
use App\Models\Admin;
use App\Models\AdminLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

class AdminLogRepository
{
    /**
     * 创建管理员日志
     * @param $request
     * @param int $adminId
     * @param int $type
     */
    public static function createAdminLog($request, int $adminId, int $type)
    {
        $currentRoute = Route::getCurrentRoute();
        $data = [
            'ip' => $request->getClientIp(),
            'url' => $currentRoute->uri(),
            'method' => $request->getMethod(),
            'param' => json_encode($request->all())
        ];
        event(new AdminLoginEvent($type, $adminId, $data));
    }

}
