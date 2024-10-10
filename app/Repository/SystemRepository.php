<?php

namespace App\Repository;


use App\Models\Config;
use App\Models\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SystemRepository
{

    const CONFIG_CACHE_KEY = "config";

   public static function getStarConfigs(){
       $fileContent = file_get_contents(base_path("data/configs_star.json"));

       return json_decode($fileContent, true);
   }


    public static function getConfigs(bool $is_new){
        $key = self::CONFIG_CACHE_KEY;
        if($is_new){
            Cache::forget($key);
        }

        return Cache::remember($key, 3600, function() {
            $configs = SystemRepository::getStarConfigs();

            $queryConfigs = Config::query()->pluck("value", "key")->toArray();

            foreach ($queryConfigs as $k => $v){
                if(array_key_exists($k, $configs)){
                    $configs[$k]["value"] = $v;
                }
            }

            return $configs;
        });
    }

}
