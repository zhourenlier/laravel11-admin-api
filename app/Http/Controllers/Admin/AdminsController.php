<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use App\Exceptions\AdminException;
use App\Models\Role;
use App\Models\Admin;
use App\Repository\AdminRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use function Termwind\ValueObjects\pr;

/**
 * 管理员
 * Class AdminsController
 * @package App\Http\Controllers\AdminRepository
 */
class AdminsController extends Controller
{
    /**
     * 管理员列表
     * @param Request $request
     * @return mixed
     */
    public function lists(Request $request)
    {
        $name = $request->input('name', "");
        $per_page = $request->input('per_page',10);

        $datas = Admin::query()
            ->with('roles')
            ->when(!empty($name), function ($query) use ($name){
                $query->where('username', 'like', $name . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate($per_page);

        return responseSuccess($datas);
    }

    /**
     * 详情
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function info(int $id)
    {
        $admin = Admin::query()->find($id);
        if($admin == null){
            return responseError(["msg" => "管理员不存在"]);
        }
        return responseSuccess($admin);
    }


    /**
     * 保存/创建管理员
     * @param Request $request
     * @return mixed
     * @throws AdminException
     */
    public function store(Request $request)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, [
            'role_id' => 'required|integer',
            'username' => 'required|alpha_dash|max:64',
            'password' => 'required|min:6',
            'email' => 'nullable|email',
            'mobile' => 'nullable|regex:/^1[3-9]\d{9}$/'
        ],[
            'role_id.required' => '管理员组不能为空',
            'username.required' => '用户名不能为空',
            'username.alpha_dash' => '用户名格式为数字+字母',
            'password.required' => '密码不能为空',
        ])) {
            return responseError(['msg' => $message], HttpCode::WRONG_REQUEST);
        }

        $res = Admin::createAdmin($params);
        if (!$res){
            return responseError(["msg" => "创建失败"]);
        }
        return responseSuccess();
    }

    /**
     * 删除管理员
     * @param $id
     * @return mixed
     */
    public function destroy(Request $request, int $id)
    {
        $adminId = $request->attributes->get('login_admin_id');
        if($adminId == $id){
            return responseError(["msg" => "不允许删除自己"]);
        }

        $admin = Admin::query()->find($id);
        if($admin == null){
            return responseError(["msg" => "管理员不存在"]);
        }

        Admin::deleteData($admin);

        //清除当前用户缓存
        AdminRepository::cleanAdminCache($id);

        return responseSuccess();
    }


    /**
     * 更新管理员
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws AdminException
     */
    public function update(Request $request, int $id)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, [
            'role_id' => 'nullable|integer',
            'username' => 'nullable|alpha_dash|max:64',
            'password' => 'nullable|min:6',
            'email' => 'nullable|email',
            'mobile' => 'nullable|regex:/^1[3-9]\d{9}$/',
            'status' => 'nullable|integer',
        ],[
        ])) {
            return responseError(['msg' => $message], HttpCode::WRONG_REQUEST);
        }

        $admin = Admin::query()->find($id);
        if($admin == null){
            return responseError(["msg" => "管理员不存在"]);
        }

        $res = Admin::updateAdmin($params, $admin);
        if (!$res){
            return responseError(["msg" => "更新失败"]);
        }

        AdminRepository::cleanAdminCache($id);

        return responseSuccess();
    }
}
