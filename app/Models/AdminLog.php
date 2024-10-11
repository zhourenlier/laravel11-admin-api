<?php
declare(strict_types=1);

namespace App\Models;

class AdminLog extends BaseModel
{
    protected $guarded = ['id'];

    const TYPE_LOGIN = 1; //登录
    const TYPE_BEHAVIOR = 2;    //行为

    /**
     * 查询分页
     * @param array $params
     * @param int $per_page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateQuery(array $params, int $per_page)
    {
        return self::query()
            ->when(!empty($params["search"]),function ($query) use ($params){
                $query->where('ip', 'like', "%{$params["search"]}%")
                    ->orWhere('url', 'like', "%{$params["search"]}%");
            })
            ->when(!in_array($params["admin_role_id"], [1]),function ($query) use ($params){
                $query->where('admin_id', $params["admin_id"]);
            })
            ->orderBy('id', 'desc')
            ->paginate($per_page);
    }
}
