<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * 判断是否存在
     * @param string $field
     * @param string $val
     * @return bool
     */
    public static function isUnique(string $field, string $val)
    {
        return self::query()->where($field, $val)->exists();
    }
}
