<?php

namespace App\Repository;

use App\Events\AdminLoginEvent;
use App\Exceptions\AdminException;
use App\Models\Admin;
use App\Models\AdminLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

class AdminRepository
{
    const TOKEN_CACHE_KEY = 'admin_token_';
    const RULE_CACHE_KEY = 'admin_rules_';
    const MENU_CACHE_KEY = 'admin_menu_';
    const ADMIN_CACHE_KEY = 'admin_';

    /**
     * 后台用户登录
     * @param $request
     * @return \Arrar|array
     * @throws AdminException
     */
    public static function auth($request)
    {
        $username = $request->username;
        $password = $request->password;

        $error = '用户名或密码错误';
        $error2 = '该用户未启用';

        $admin = Admin::where('username', $username)->first();
        if (Admin::NOT_ACTIVE === $admin->status)
            throw new AdminException($error2);

        if (null === $admin)
            throw new AdminException($error);

        $res = password_verify($password, $admin->password);
        if ($res === false)
            throw new AdminException($error);


        //生成token
        $token = Hash::make(now().$admin->id.getRandomString(60));
        $admin->remember_token = $token;
        $admin->save();

        //缓存
        Cache::put(self::TOKEN_CACHE_KEY.md5($token), $admin->id, 3600 * 4);
        AdminRepository::cacheRules($admin->id);

        //记录日志
        AdminLogRepository::createAdminLog($request,AdminLog::TYPE_LOGIN, $admin->id);

        return resultSuccess([
            "token" => $token
        ]);
    }

    /**
     * 获取管理员权限
     * @param int $adminId
     * @param bool $isNew
     * @return mixed
     */
    public static function getAdminRules(int $adminId, $isNew = false)
    {
        $key = self::RULE_CACHE_KEY.$adminId;
        if ($isNew || !Cache::has($key)) self::cacheRules($adminId);
        return Cache::get($key);
    }

    /**
     * 缓存管理员权限
     * @param int $adminId
     */
    public static function cacheRules(int $adminId)
    {
        $key = self::RULE_CACHE_KEY.$adminId;
        $rules = self::getRules($adminId);
        Cache::put($key, $rules, 3600);
    }

    /**
     * 查询用户需认证的权限
     * @param int $adminId
     * @return array
     */
    public static function getRules(int $adminId)
    {
        return DB::table('admin_roles as ur')
            ->leftJoin('role_rules as rl', 'ur.role_id', '=', 'rl.role_id')
            ->leftJoin('rules as r', 'rl.rule_id', '=', 'r.id')
            ->where('ur.admin_id', $adminId)
            ->where('r.rule', '<>', '')
            ->where('r.is_check', 1)
            ->distinct()
            ->pluck('r.rule')
            ->toArray();
    }

    /**
     * 获取管理员菜单
     * @param int $adminId
     * @param bool $isNew
     * @return mixed
     */
    public static function getAdminMenu(int $adminId, $isNew = false)
    {
        $key = self::MENU_CACHE_KEY.$adminId;
        if ($isNew || !Cache::has($key)){
            self::cacheMenu($adminId);
        }
        return Cache::get($key);
    }

    /**
     * 缓存管理员菜单
     * @param int $adminId
     * @return mixed
     */
    public static function cacheMenu(int $adminId)
    {
        $key = self::MENU_CACHE_KEY.$adminId;
        $menu = self::getMenu($adminId);
        Cache::put($key, $menu, 3600);
    }

    /**
     * 查询管理员菜单
     * @param int $adminId
     * @return array
     */
    public static function getMenu(int $adminId)
    {
        // 获取该用户拥有的需要认证的菜单
        $menu = DB::table('admin_roles as ur')
            ->leftJoin('role_rules as rl', 'ur.role_id', '=', 'rl.role_id')
            ->leftJoin('rules as r', 'rl.rule_id', '=', 'r.id')
            ->where('ur.admin_id', $adminId)
            ->where('r.type', 1)
            ->select('r.id', 'r.pid', 'r.title', 'r.rule', 'r.sort')
            ->get();
        $menu = json_decode(json_encode($menu), true);
        usort($menu, function ($a, $b) {
            return $a['sort'] <=> $b['sort'];
        });
        return $menu;
    }

    /**
     * 清除管理员登录权限缓存
     * @param $request
     */
    public static function cleanAdminCache($adminId)
    {
        Cache::forget(self::RULE_CACHE_KEY.$adminId);
        Cache::forget(self::MENU_CACHE_KEY.$adminId);
        Cache::forget(self::ADMIN_CACHE_KEY.$adminId);
        Cache::forget(AdminRoleRepository::ADMIN_ROLE_CACHE_KEY.$adminId);
        Cache::forget(RuleRepository::LOG_RULES_CACHE_KEY);
    }


    /**
     * 获取登录用户信息
     * @param $request
     * @return \Illuminate\Support\Collection
     */
    public static function getLoginAdmin($request)
    {
        $adminId = $request->attributes->get('login_admin_id'); //用户ID
        return collect(Cache::get(self::ADMIN_CACHE_KEY.$adminId));
    }

}
