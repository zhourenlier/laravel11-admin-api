<?php
declare(strict_types=1);

namespace App\Models;

use App\Exceptions\AdminException;
use Illuminate\Support\Facades\Log;

class Admin extends BaseModel
{

    protected $guarded = ['id'];
    protected $hidden = ['password', 'remember_token'];

    const ACTIVE       = 1;
    const NOT_ACTIVE   = 0;

    const PWD_COST = 12;

    /**
     * 查询分页
     * @param array $params
     * @param int $per_page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateQuery(array $params, int $per_page)
    {
        return self::query()
            ->with('roles')
            ->when(!empty($params["username"]), function ($query) use ($params){
                $query->where('username', 'like', '%' . $params["username"] . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate($per_page);
    }


    /**
     * 创建管理员
     * @param array $params
     * @return bool
     * @throws AdminException
     */
    public function createAdmin(array $params) : bool
    {
        $data = [
            'username' => $params['username'],
            'password' => password_hash($params['password'], PASSWORD_DEFAULT, ['cost' => self::PWD_COST]),
            'status' => self::ACTIVE,
            'mobile' => $params['mobile']??'',
            'email' => $params['email']??'',
        ];
        if(self::isUnique('username', $data['username'])){
            throw new AdminException('该账号名已被使用');
        }
        if(!empty($data['mobile']) && self::isUnique('mobile', $data['mobile'])){
            throw new AdminException('该手机号已被使用');
        }
        if(!empty($data['email']) && self::isUnique('email', $data['email'])){
            throw new AdminException('该邮箱已被使用');
        }

        //校验组是否存在
        if(!Role::isUnique('id', $params['role_id'])){
            throw new AdminException('管理员组不存在');
        }

        $admin = self::query()->create($data);
        if($params['role_id'] > 0){
            $admin->roles()->sync($params['role_id']);
        }
        return true;
    }

    /**
     * 更新管理员
     * @param Admin $admin
     * @param array $params
     * @return bool
     * @throws AdminException
     */
    public function updateAdmin(Admin $admin, array $params) : bool
    {
        $data = [];
        if (!empty($params['username']) && $params['username'] != $admin->username) {
            $data["username"] = $params['username'];
            if (!empty($data['username']) && self::isUnique('username', $params['username'])){
                throw new AdminException('该账号名已被使用');
            }
        }
        if (array_key_exists("email", $params) && $params['email'] != $admin->email) {
            $data["email"] = $params['email'] ?? "";
            if (!empty($data['email']) && self::isUnique('email', $data['email'])){
                throw new AdminException('该邮箱已被占用');
            }
        }

        if (array_key_exists("mobile", $params) && $params['mobile'] != $admin->mobile) {
            $data["mobile"] = $params['mobile'] ?? "";
            if (!empty($data['mobile']) && self::isUnique('mobile', $data['mobile'])){
                throw new AdminException('该手机号被占用');
            }
        }
        if (!empty($params['password'])) {
            $data["password"] = password_hash($params['password'], PASSWORD_DEFAULT, ['cost' => self::PWD_COST]);
        }
        if (array_key_exists("status", $params) && $params["status"] != "") {
            $data["status"] = $params['status'];
        }
        if (!empty($params['remember_token'])) {
            $data["remember_token"] = $params['remember_token'];
        }

        //校验组是否存在
        $role_id = $params['role_id'] ?? 0;
        if(isset($params['role_id']) && $params['role_id'] > 0 && !Role::isUnique('id', $params['role_id'])){
            throw new AdminException('管理员组不存在');
        }

        try {
            if(count($data) > 0){
                $res = $admin->update($data);
                if (!$res) {
                    return false;
                }
            }

            if($role_id > 0){
                $admin->roles()->sync($role_id);
            }

            return true;
        }catch (\Exception $exception){
            Log::info("更新管理员异常：".$exception->getMessage());
            throw new AdminException("更新管理员异常：".$exception->getMessage());
        }
        return false;
    }

    /**
     * 删除
     * @param Admin $admin
     * @return void
     */
    public function deleteData(Admin $admin)
    {
        $admin->roles()->detach();
        $admin->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, AdminRole::class, 'admin_id', 'role_id');
    }
}
