<?php
declare(strict_types=1);

namespace App\Common\Enum;

class AdminCode extends CommonCode
{
    private $key;
    private $msg;

    public function __construct(string $key, string $msg = "")
    {
        if(empty($key)){
            throw new \Exception("key不能为空");
        }

        $this->key = $key;
        $this->msg = self::ADMIN_DATA[$key];

        if(!empty($msg)){
            $this->msg = $msg;
        }
    }

    /**
     * @return string
     */
    public function getCode():string
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getMsg():string
    {
        return $this->msg;
    }

}
