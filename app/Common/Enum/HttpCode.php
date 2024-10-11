<?php
declare(strict_types=1);
namespace App\Common\Enum;

class HttpCode
{
    //错误的请求
    const WRONG_REQUEST = 400;

    //无权访问
    const UNAUTHORIZED_ACCESS = 401;

    //禁止访问
    const FORBIDDEN = 403;

    //页面不存在
    const NOT_FOUND_HTTP = 404;

    //内部服务器错误
    const INTERNAL_SERVER_ERROR = 500;
}
