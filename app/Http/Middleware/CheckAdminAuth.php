<?php

namespace App\Http\Middleware;

use App\Common\Enum\HttpCode;
use App\Models\Admin;
use App\Repository\AdminRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;
use function Termwind\ValueObjects\pr;

class CheckAdminAuth
{
    /**
     * 处理传入请求。
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = empty($request->get('token')) ? $request->header('Authorization') : $request->get('token');
        if (empty($token)) {
            return responseError(["code"=> "NO_TOKEN"], HttpCode::FORBIDDEN);
        }

        $key = AdminRepository::TOKEN_CACHE_KEY. md5($token);
        $adminId = Cache::get($key);
        if(empty($adminId)){
            return responseError(["code"=> "INVALID_TOKEN"], HttpCode::FORBIDDEN);
        }

        //验证通过
        $adminKey = AdminRepository::ADMIN_CACHE_KEY. $adminId;
        if(empty(Cache::get($adminKey))){
            $admin = Admin::query()->find($adminId);
            if(empty($admin) || $admin->status != Admin::ACTIVE){
                return responseError(["msg"=> "账号已禁用"], HttpCode::FORBIDDEN);
            }

            //10分钟校验一次
            Cache::put($adminKey, $admin->toArray(), 600);
        }

        //更改时长
        Redis::connection("cache")->expire(config("cache.prefix").$key, 3600*4);

        $request->attributes->set('login_admin_id', $adminId);
        return $next($request);
    }
}
