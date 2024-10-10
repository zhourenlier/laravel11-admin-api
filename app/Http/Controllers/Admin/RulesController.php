<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\AdminCode;
use App\Common\Enum\HttpCode;
use App\Models\Rule;
use App\Repository\AdminRepository;
use App\Repository\RuleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * 权限
 * Class RulesController
 * @package App\Http\Controllers\AdminRepository
 */
class RulesController extends Controller
{
    /**
     * 权限列表
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function all()
    {
        $rules = RuleRepository::getRules();
        return responseSuccess($rules);
    }

    /**
     * 保存
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, [
            'pid' => 'required|integer',
            'title' => 'required|string',
            'is_check' => 'nullable|integer',
            'is_log' => 'nullable|integer',
            'rule' => 'nullable|string',
            'sort' => 'nullable|integer',
        ],[])) {
            return responseError(['msg' => $message], HttpCode::WRONG_REQUEST);
        }

        $res = Rule::createData($params);
        if (!$res){
            return responseError(["msg" => "创建异常"]);
        }

        $adminId = AdminRepository::getLoginAdmin($request)->get("id");
        AdminRepository::cleanAdminCache($adminId);

        return responseSuccess();
    }

    /**
     * 更新
     * @param Request $request
     * @param Rule $rule
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, [
            'pid' => 'nullable|integer',
            'title' => 'nullable|string',
            'is_check' => 'nullable|integer',
            'is_log' => 'nullable|integer',
            'rule' => 'nullable|string',
            'sort' => 'nullable|integer',
        ],[])) {
            return responseError(['msg' => $message], HttpCode::WRONG_REQUEST);
        }

        $val = Rule::query()->find($id);
        if($val == null){
            return responseError(["msg" => "权限不存在"]);
        }

        $res = Rule::updateData($val, $params);
        if (!$res){
            return responseError(["msg" => "更新异常"]);
        }

        $adminId = AdminRepository::getLoginAdmin($request)->get("id");
        AdminRepository::cleanAdminCache($adminId);

        return responseSuccess();
    }


    public function destroy(Request $request, int $id)
    {
        $val = Rule::query()->find($id);
        if($val == null){
            return responseError(["msg" => "权限不存在"]);
        }

        $count = Rule::query()->where('pid', $id)->count();
        if ($count > 0){
            return responseError(["msg" => "该权限下面有子级，不能删除"]);
        }

        Rule::deleteData($val);

        $adminId = AdminRepository::getLoginAdmin($request)->get("id");
        AdminRepository::cleanAdminCache($adminId);

        return responseSuccess();
    }
}
