<?php

namespace App\Models;

use App\Exceptions\AdminException;
use App\Repository\AdminRepository;
use App\Repository\SystemRepository;
use Illuminate\Support\Facades\Log;

class Config extends BaseModel
{
    protected $guarded = ['key'];

    /**
     * 更新
     * @param $params
     */
    public static function updateData($params)
    {
        $configs = SystemRepository::getStarConfigs();
        foreach ($params as $k => $v){
            if(array_key_exists($k, $configs)){
                self::query()->updateOrCreate(
                    ["key" => $k],
                    ["value" => $v]
                );
            }
        }

        //清理缓存
        SystemRepository::getConfigs(true);
    }
}
