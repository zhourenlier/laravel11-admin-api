<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\AdminCode;
use App\Common\Enum\HttpCode;
use App\Models\Role;
use App\Models\Rule;
use App\Repository\AdminRepository;
use App\Repository\RoleService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * 角色
 * Class RolesController
 * @package App\Http\Controllers\Admin
 */
class RolesController extends Controller
{
    /**
     * 列表
     * @param Request $request
     * @return mixed
     */
    public function lists(Request $request)
    {
        $per_page = $request->input('per_page',10);
        $datas = Role::query()
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
        $val = Role::query()->find($id);
        if($val == null){
            return responseError(["msg" => "角色不存在"]);
        }
        return responseSuccess($val);
    }


    /**
     * 保存
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\AdminException
     */
    public function store(Request $request)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, [
            'name' => 'required'
        ],[])) {
            return responseError(['msg' => $message], HttpCode::WRONG_REQUEST);
        }

        $res = Role::createData($params);
        if (!$res){
            return responseError(["msg" => "创建角色失败"]);
        }
        return responseSuccess();
    }

    /**
     * 更新
     * @param Request $request
     * @param int $id
     * @return mixed
     * @throws \App\Exceptions\AdminException
     */
    public function update(Request $request, int $id)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, [
            'name' => 'required'
        ],[])) {
            return responseError(['msg' => $message], HttpCode::WRONG_REQUEST);
        }

        $role = Role::query()->find($id);
        if($role == null){
            return responseError(["msg" => "角色不存在"]);
        }

        $res = Role::updateData($role, $params);
        if (!$res){
            return responseError(["msg" => "更新角色失败"]);
        }
        return responseSuccess();
    }

    /**
     * 删除
     * @param int $id
     * @return mixed
     * @throws \Exception
     */
    public function destroy($request, int $id)
    {
        $role = Role::query()->find($id);
        if($role == null){
            return responseError(["msg" => "角色不存在"]);
        }

        if($id == 1){
            return responseError(["msg" => "不允许删除超级管理员"]);
        }

        //验证角色所属管理员
        if($role->admins()->count() > 0){
            return responseError(["msg" => "该角色存在授权管理员"]);
        }

        Role::deleteData($role);

        //清除当前用户缓存
        $adminId = AdminRepository::getLoginAdmin($request)->get("id");
        AdminRepository::cleanAdminCache($adminId);

        return responseSuccess();
    }


    /**
     * 设置规则
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function setRules(Request $request, int $id)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, [
            'rules' => 'required'
        ],[])) {
            return responseError(['msg' => $message], HttpCode::WRONG_REQUEST);
        }

        $role = Role::query()->find($id);
        if($role == null){
            return responseError(["msg" => "角色不存在"]);
        }

        $rules = explode(",", $params["rules"]);
        if(count($rules) > 0){
            $rules = Rule::query()->whereIn("id", $rules)->select("id")->pluck("id")->toArray();
        }

        $result = $role->rules()->sync($rules);
        if (!$result){
            return responseError(["msg" => "权限更新失败"]);
        }

        //清除当前用户缓存
        $adminId = AdminRepository::getLoginAdmin($request)->get("id");
        AdminRepository::cleanAdminCache($adminId);

        return responseSuccess();
    }
}
