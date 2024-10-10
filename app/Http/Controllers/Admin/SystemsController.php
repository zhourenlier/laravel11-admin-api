<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminLog;
use App\Repository\AdminRepository;
use App\Repository\AdminRoleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SystemsController extends Controller
{
    /**
     * 清除缓存
     * @param Request $request
     * @return mixed
     */
    public function cleanCache(Request $request)
    {
        $adminId = AdminRepository::getLoginAdmin($request)->get('id'); //用户ID
        AdminRepository::cleanAdminCache($adminId);
        return responseSuccess();
    }

    /**
     * 获取系统配置
     * @param Request $request
     * @return mixed
     */
    public function config(Request $request)
    {

        return responseSuccess();
    }
}
