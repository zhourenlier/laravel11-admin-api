<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminLog;
use App\Repository\AdminRepository;
use App\Repository\AdminRoleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminLogController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function lists(Request $request)
    {
        $adminId = $request->attributes->get('login_admin_id');
        $search = $request->input('search');
        $per_page = $request->input('per_page',10);

        //判断管理员类型，超级管理员可以查看全部
        $adminRole = AdminRoleRepository::getAdminRole($adminId);

        $datas = AdminLog::query()
            ->when(!empty($search),function ($query) use ($search){
                $query->where('ip', 'like', "{$search}%")
                    ->orWhere('url', 'like', "{$search}%");
            })
            ->when(!in_array($adminRole->role_id, [1]),function ($query) use ($adminId){
                $query->where('admin_id', $adminId);
            })
            ->orderBy('id', 'desc')
            ->paginate($per_page);

        return responseSuccess($datas);
    }
}
