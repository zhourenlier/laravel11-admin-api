<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use App\Exceptions\AdminException;
use App\Models\Admin;
use App\Repository\AdminRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 管理员
 * Class AdminsController
 * @package App\Http\Controllers\AdminRepository
 */
class AdminsController extends Controller
{
    public function __construct(
        protected readonly Admin $adminModel
    ){
    }

    /**
     * 管理员列表
     * @param Request $request
     * @return mixed
     */
    public function lists(Request $request)
    {
        $name = $request->input('name', "");
        $per_page = intval($request->input('per_page',10));

        $data = $this->adminModel->paginateQuery([
            "username" => $name
        ], $per_page);
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
        $admin = $this->adminModel->firstById($id);
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

        $res = $this->adminModel->createAdmin($params);
        if (!$res){
            return responseError(["msg" => "创建失败"]);
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
        $adminId = $request->attributes->get('login_admin_id');
        if($adminId == $id){
            return responseError(["msg" => "不允许删除自己"]);
        }

        $admin = $this->adminModel->firstById($id);
        if($admin == null){
            return responseError(["msg" => "管理员不存在"]);
        }

        $this->adminModel->deleteData($admin);

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

        $admin = $this->adminModel->firstById($id);
        if($admin == null){
            return responseError(["msg" => "管理员不存在"]);
        }

        $res = $this->adminModel->updateAdmin($admin, $params);
        if (!$res){
            return responseError(["msg" => "更新失败"]);
        }

        AdminRepository::cleanAdminCache($id);

        return responseSuccess();
    }
}
