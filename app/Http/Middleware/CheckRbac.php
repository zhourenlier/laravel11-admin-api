<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Common\Enum\AdminCode;
use App\Common\Enum\HttpCode;
use App\Repository\AdminRepository;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use function Termwind\ValueObjects\pr;

class CheckRbac
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
        $currentRoute = Route::getCurrentRoute();
        $adminId = AdminRepository::getLoginAdmin($request)->get('id');
        //备注后面使用缓存
        $rules = AdminRepository::getAdminRules(intval($adminId), true);

        $checkStr = $request->method()."|".$currentRoute->uri;
        if (!in_array($checkStr, $rules)) {
            return responseError(["code" => 'NOT_PERMISSION' ,"msg" => $checkStr], HttpCode::UNAUTHORIZED_ACCESS);
        }

        return $next($request);
    }
}
