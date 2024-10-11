<?php
declare(strict_types=1);

namespace App\Models;

use App\Repository\AdminRepository;
use App\Repository\SystemRepository;

class SystemConfig extends BaseModel
{
    protected $guarded = ['key'];

    /**
     * @param array $params
     * @return void
     */
    public function updateOrCreateData($key, $value)
    {
        return self::query()->updateOrCreate(
            ["key" => $key],
            ["value" => $value]
        );
    }

    /**
     * @return mixed[]
     */
    public function getConfig(){
        return self::query()->pluck("value", "key")->toArray();
    }
}
