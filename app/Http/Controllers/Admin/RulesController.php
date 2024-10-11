<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use App\Models\Rule;
use App\Repository\AdminRepository;
use App\Repository\RuleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 权限
 * Class RulesController
 * @package App\Http\Controllers\AdminRepository
 */
class RulesController extends Controller
{
    public function __construct(
        protected readonly RuleRepository $ruleRepository,
        protected readonly Rule $ruleModel
    ){
    }

    /**
     * 权限列表
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function all()
    {
        $rules = $this->ruleRepository->getRules();
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

        $res = $this->ruleModel->createData($params);
        if (!$res){
            return responseError(["msg" => "创建异常"]);
        }

        $adminId = AdminRepository::getLoginAdmin($request)->get("id");
        AdminRepository::cleanAdminCache($adminId);

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
            'pid' => 'nullable|integer',
            'title' => 'nullable|string',
            'is_check' => 'nullable|integer',
            'is_log' => 'nullable|integer',
            'rule' => 'nullable|string',
            'sort' => 'nullable|integer',
        ],[])) {
            return responseError(['msg' => $message], HttpCode::WRONG_REQUEST);
        }

        $data = $this->ruleModel->firstById($id);
        if($data == null){
            return responseError(["msg" => "权限不存在"]);
        }

        $res = $this->ruleModel->updateData($data, $params);
        if (!$res){
            return responseError(["msg" => "更新异常"]);
        }

        $adminId = AdminRepository::getLoginAdmin($request)->get("id");
        AdminRepository::cleanAdminCache($adminId);

        return responseSuccess();
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AdminException
     */
    public function destroy(int $id, Request $request)
    {
        $data = $this->ruleModel->firstById($id);
        if($data == null){
            return responseError(["msg" => "权限不存在"]);
        }

        $count = $this->ruleModel->getChildrenCount($id);
        if ($count > 0){
            return responseError(["msg" => "该权限下面有子级，不能删除"]);
        }

        $this->ruleModel->deleteData($data);

        $adminId = AdminRepository::getLoginAdmin($request)->get("id");
        AdminRepository::cleanAdminCache($adminId);

        return responseSuccess();
    }
}
