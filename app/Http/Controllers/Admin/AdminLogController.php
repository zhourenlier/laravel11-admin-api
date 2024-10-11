<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\AdminLog;
use App\Repository\AdminRepository;
use App\Repository\AdminRoleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminLogController extends Controller
{
    public function __construct(
        protected readonly AdminLog $adminLogModel,
        protected readonly AdminRoleRepository $adminRoleRepository
    ){
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function lists(Request $request)
    {
        $adminId = AdminRepository::getLoginAdmin($request)->get("id");
        $search = $request->input('search');
        $per_page = intval($request->input('per_page',10));

        //判断管理员类型，超级管理员可以查看全部
        $adminRole = $this->adminRoleRepository->getAdminRole($adminId);

        $data = $this->adminLogModel->paginateQuery([
            "admin_id" => $adminId,
            "search" => $search,
            "admin_role_id" => $adminRole->id
        ], $per_page);
        return responseSuccess($data);
    }
}
