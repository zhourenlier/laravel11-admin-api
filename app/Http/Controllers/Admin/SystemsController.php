<?php

namespace App\Http\Controllers\Admin;

use App\Models\SystemConfig;
use App\Repository\AdminRepository;
use App\Repository\SystemRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SystemsController extends Controller
{
    public function __construct(
        protected readonly SystemRepository $systemRepository,
        protected readonly SystemConfig $systemConfigModel,
    ){
    }

    /**
     * 清除缓存
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConfig(Request $request)
    {
        $config = $this->systemRepository->getConfigs(true);
        return responseSuccess($config);
    }

    /**
     * 更新系统配置
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateConfig(Request $request)
    {
        $this->systemRepository->updateConfig($request->all());
        return responseSuccess();
    }
}
