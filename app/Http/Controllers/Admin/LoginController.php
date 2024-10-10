<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use App\Exceptions\AdminException;
use App\Repository\AdminRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

/**
 * Class LoginController
 * @package App\Http\Controllers\Admin
 */
class LoginController extends Controller
{

    /**
     * 登录
     * @param Request $request
     * @return mixed
     * @throws AdminException
     */
    public function signIn(Request $request)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, [
            'username' => 'required',
            'password' => 'required',
        ],[])) {
            return responseError(['msg'=>$message], HttpCode::WRONG_REQUEST);
        }

        $token = AdminRepository::auth($request);
        return responseSuccess($token["data"]);
    }

    /**
     * 退出
     * @param Request $request
     * @return mixed
     */
    public function logOut(Request $request)
    {

        $token = empty($request->get('token')) ? $request->header('Authorization') : $request->get('token');
        Cache::forget(AdminRepository::TOKEN_CACHE_KEY.md5($token));

        $adminId = AdminRepository::getLoginAdmin($request)->get('id'); //用户ID
        AdminRepository::cleanAdminCache($adminId);
        return responseSuccess();
    }

}
