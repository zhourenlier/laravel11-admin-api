<?php
declare(strict_types=1);

namespace App\Repository;


use App\Models\SystemConfig;
use App\Models\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SystemRepository
{

    const CONFIG_CACHE_KEY = "config";

    public function __construct(
        protected readonly SystemConfig $systemConfigModel,
    ){
    }

    /**
     * 获取初始化配置
     * @return mixed
     */
   public function getStarConfigs(){
       $fileContent = file_get_contents(base_path("data/configs_star.json"));

       return json_decode($fileContent, true);
   }


    /**
     * 获取配置
     * @param bool $is_new
     * @return mixed
     */
    public function getConfigs(bool $is_new){
        $key = self::CONFIG_CACHE_KEY;
        if($is_new){
            Cache::forget($key);
        }

        return Cache::remember($key, 3600, function() {
            $configs = $this->getStarConfigs();

            $queryConfigs = $this->systemConfigModel->getConfig();

            foreach ($queryConfigs as $k => $v){
                if(array_key_exists($k, $configs)){
                    $configs[$k]["value"] = $v;
                }
            }

            return $configs;
        });
    }


    /**
     * @param array $params
     * @return void
     */
    public function updateConfig(array $params)
    {
        $configs = SystemRepository::getStarConfigs();
        foreach ($params as $k => $v){
            if(array_key_exists($k, $configs)){
                $this->systemConfigModel->updateOrCreateData($k,$v);
            }
        }

        //清理缓存
        $this->getConfigs(true);
    }
}
