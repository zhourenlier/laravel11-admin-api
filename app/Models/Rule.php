<?php
declare(strict_types=1);

namespace App\Models;

use App\Exceptions\AdminException;
use App\Repository\AdminRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Rule extends BaseModel
{
    protected $guarded = ['id'];

    const UNSAVE_LOG = 0; //不记录
    const SAVE_LOG = 1; //记录

    const CHECK_NO = 0; //不校验
    const CHECK_YES = 1;

    const DEFAULT_SORT = 100;

    /**
     * @param array $ids
     * @return mixed[]
     */
    public function excludeRuleId(array $ids)
    {
        return self::query()
            ->whereIn("id", $ids)
            ->select("id")
            ->pluck("id")
            ->toArray();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllOrderBySort()
    {
        return self::query()
            ->orderBy('sort', 'asc')
            ->get();
    }

    /**
     * @param int $pid
     * @return int
     */
    public function getChildrenCount(int $pid)
    {
        return self::query()->where('pid', $pid)->count();
    }

    /**
     * @param $params
     * @return bool
     * @throws AdminException
     */
    public function createData($params)
    {
        try {
            $data = [
                'pid' => $params['pid'],
                'title' => $params['title'],
                'rule' => $params['rule']??'',
                'sort' => $params['sort']??self::DEFAULT_SORT,
                'is_log' => $params['is_log']??self::UNSAVE_LOG,
                'is_check' => $params['is_check']??self::CHECK_NO,
            ];
            return self::query()->insert($data);
        } catch (\Exception $exception) {
            Log::info('创建权限异常:'.$exception->getMessage());
            throw new AdminException('创建权限异常:'.$exception->getMessage());
        }
    }

    /**
     * 更新
     * @param Rule $rule
     * @param $params
     * @return bool
     * @throws AdminException
     */
    public function updateData(Rule $rule, $params)
    {
        if (array_key_exists("pid", $params)) {
            $rule->pid = $params['pid'] ?? 0;
        }
        if (!empty($params["title"])) {
            $rule->title = $params['title'];
        }
        if (array_key_exists("rule", $params)) {
            $rule->rule = $params['rule'];
        }
        if (array_key_exists("sort", $params)) {
            $rule->sort = $params['sort'] ?? self::DEFAULT_SORT;
        }
        if (array_key_exists("is_log", $params)) {
            $rule->is_log = $params['is_log'] ?? self::UNSAVE_LOG;
        }
        if (array_key_exists("is_check", $params)) {
            $rule->is_check = $params['is_check'] ?? self::CHECK_NO;
        }

        try {
            return $rule->save();
        } catch (\Exception $exception) {
            Log::info('更新权限异常:'.$exception->getMessage());
            throw new AdminException('更新权限异常:'.$exception->getMessage());
        }
    }

    /**
     * 删除
     * @return string
     */
    public function deleteData(Rule $rule)
    {
        $rule->roles()->detach();
        $res = $rule->delete();
        if (!$res){
            throw new AdminException('删除失败');
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, RoleRule::class, 'rule_id', 'role_id');
    }
}
