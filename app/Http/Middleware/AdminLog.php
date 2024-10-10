<?php

namespace App\Http\Middleware;

use App\Repository\AdminLogRepository;
use App\Repository\RuleRepository;
use Closure;
use Illuminate\Support\Facades\Route;

class AdminLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $currentRoute = Route::getCurrentRoute();
        //备注后面使用缓存
        $admin_need_logs = RuleRepository::getLogSaveRules(true)->toArray();

        $checkStr = $request->method()."|".$currentRoute->uri;
        if (!in_array($checkStr, $admin_need_logs)) {
            $adminId = $request->attributes->get('login_admin_id');
            AdminLogRepository::createAdminLog($request,\App\Models\AdminLog::TYPE_BEHAVIOR, $adminId);
        }

        return $response;
    }
}
