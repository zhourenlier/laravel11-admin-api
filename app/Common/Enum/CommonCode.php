<?php
declare(strict_types=1);
namespace App\Common\Enum;

class CommonCode
{
    const SUCCESS_CODE = "SUCCESS";
    const ERROR_CODE = "ERROR";

    const ADMIN_DATA = [
        self::SUCCESS_CODE => 'Success',
        self::ERROR_CODE => 'Error',
        'NOT_LOGIN' => "您未登录",
        'NO_TOKEN' => "没有token",
        'INVALID_TOKEN' => "无效的token",
        'NOT_PERMISSION' => "没有权限",
        'MISSING_PARAM' => "参数缺失",
        'SERVICE_EXCEPTIONS' => "服务异常",
        'LOGIN_HAS_EXPIRED' => "登录已失效",
    ];
}
