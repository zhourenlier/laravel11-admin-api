<?php
declare(strict_types=1);

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

    /**
     * @param array $params
     * @return null
     */
    public function firstByArray(array $params)
    {
        return self::query()->where($params)->first();
    }

    /**
     * @param array $params
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByArray(array $params)
    {
        return self::query()->where($params)->get();
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function firstById(int $id)
    {
        return self::query()->find($id);
    }

    /**
     * @param string $field
     * @param $value
     * @return null
     */
    public function firstByFeild(string $field, $value)
    {
        return self::query()->where($field, $value)->first();
    }
}
