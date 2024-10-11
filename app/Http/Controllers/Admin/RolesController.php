<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use App\Models\Role;
use App\Models\RoleRule;
use App\Models\Rule;
use App\Repository\AdminRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 角色
 * Class RolesController
 * @package App\Http\Controllers\Admin
 */
class RolesController extends Controller
{
    public function __construct(
        protected readonly Role $roleModel,
        protected readonly RoleRule $roleRuleModel,
        protected readonly Rule $ruleModel,
    ){
    }

    /**
     * 列表
     * @param Request $request
     * @return mixed
     */
    public function lists(Request $request)
    {
        $per_page = intval($request->input('per_page',10));
        $data = $this->roleModel->paginateQuery($per_page);
        return responseSuccess($data);
    }

    /**
     * 详情
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function info(int $id)
    {
        $data = $this->roleModel->firstById($id);
        if($data == null){
            return responseError(["msg" => "角色不存在"]);
        }
        return responseSuccess($data);
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

        $res = $this->roleModel->createData($params);
        if (!$res){
            return responseError(["msg" => "创建角色失败"]);
        }
        return responseSuccess();
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AdminException
     */
    public function update(int $id, Request $request)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, [
            'name' => 'required'
        ],[])) {
            return responseError(['msg' => $message], HttpCode::WRONG_REQUEST);
        }

        $role = $this->roleModel->firstById($id);
        if($role == null){
            return responseError(["msg" => "角色不存在"]);
        }

        $res = $this->roleModel->updateData($role, $params);
        if (!$res){
            return responseError(["msg" => "更新角色失败"]);
        }
        return responseSuccess();
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(int $id, Request $request)
    {
        $role = $this->roleModel->firstById($id);
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

        $this->roleModel->deleteData($role);

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

        $role = $this->roleModel->firstById($id);
        if($role == null){
            return responseError(["msg" => "角色不存在"]);
        }

        $rules = explode(",", $params["rules"]);
        if(count($rules) > 0){
            $rules = $this->ruleModel->excludeRuleId($rules);
        }

        $this->roleRuleModel->roleRuleSync($role, $rules);

        //清除当前用户缓存
        $adminId = AdminRepository::getLoginAdmin($request)->get("id");
        AdminRepository::cleanAdminCache($adminId);

        return responseSuccess();
    }
}
