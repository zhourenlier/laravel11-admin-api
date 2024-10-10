<?php

namespace App\Exceptions;


use App\Common\Enum\AdminCode;
use App\Common\Enum\CommonCode;
use Exception;

class AdminException extends Exception
{
    public function __construct(string $msg, $key = "")
    {
        $newCode = new AdminCode($key != "" ? $key : CommonCode::ERROR_CODE, $msg);
        $this->code = $newCode->getCode();
        $this->message = $newCode->getMsg();
    }



}
