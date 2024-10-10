<?php

namespace App\Models;

use App\Exceptions\AdminException;
use Illuminate\Support\Facades\Log;

class Role extends BaseModel
{
    protected $guarded = ['id'];

    /**
     * 创建
     * @return string
     */
    public static function createData($params)
    {
        $name = $params['name']??'';
        if(self::isUnique('name',$name)){
            throw new AdminException("该角色已存在");
        }

        try {
            $result = self::query()->create([
                'name' => $name,
            ]);
            if($result){
                return true;
            }
        } catch (\Exception $exception) {
            Log::info('创建角色异常:'.$exception->getMessage());
            throw new AdminException('创建角色异常:'.$exception->getMessage());
        }
        return false;
    }


    /**
     * 更新
     * @return string
     */
    public static function updateData(Role $role, $params)
    {
        $name = $params['name']??'';

        $isExists = self::query()
            ->where('name', $name)
            ->where('id', '<>', $role->id)
            ->exists();
        if($isExists){
            throw new AdminException("角色名已存在");
        }

        try {
            $role->name = $name;
            return $role->save();
        } catch (\Exception $exception) {
            Log::info('更新角色异常:'.$exception->getMessage());
            throw new AdminException('更新角色异常:'.$exception->getMessage());
        }
    }

    /**
     * 删除
     * @return string
     */
    public static function deleteData(Role $role)
    {
        $role->admins()->detach();
        $role->rules()->detach();
        $role->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function admins()
    {
        return $this->belongsToMany(Admin::class, AdminRole::class, 'role_id', 'admin_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rules()
    {
        return $this->belongsToMany(Rule::class, RoleRule::class, 'role_id', 'rule_id');
    }

}
