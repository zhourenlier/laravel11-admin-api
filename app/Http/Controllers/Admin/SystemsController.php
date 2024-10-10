<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminLog;
use App\Models\Config;
use App\Repository\AdminRepository;
use App\Repository\AdminRoleRepository;
use App\Repository\SystemRepository;
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
        $config = SystemRepository::getConfigs(true);
        return responseSuccess($config);
    }

    /**
     * 更新系统配置
     * @param Request $request
     * @return mixed
     */
    public function updateConfig(Request $request)
    {
        Config::updateData($request->all());
        return responseSuccess();
    }
}
