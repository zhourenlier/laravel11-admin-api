<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Common\Enum\HttpCode;
use App\Exceptions\AdminException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Middleware\CheckAdminAuth;
use App\Http\Middleware\CheckRbac;
use App\Http\Middleware\AdminLog;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Throwable;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: [__DIR__.'/../routes/api.php', __DIR__.'/../routes/api.admin.php'],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin.auth' => CheckAdminAuth::class,
            'admin.rbac' => CheckRbac::class,
            'admin.log' => AdminLog::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontReportDuplicates();

        //忽略日志输出
        $exceptions->dontReport([
            AdminException::class,
        ]);

        //处理不同类型日志
        $exceptions->respond(function (Response $response, Throwable $e){
            switch ($e){
                case $e instanceof AdminException:
                    return responseError( ["code" => $e->getCode(), "msg" =>$e->getMessage()], HttpCode::INTERNAL_SERVER_ERROR);

                case $e instanceof NotFoundHttpException:
                    return responseError( ["msg" =>"接口不存在"], HttpCode::NOT_FOUND_HTTP);

                case $e instanceof \Exception:
                default:
                    return responseError(["msg" =>$e->getMessage()], HttpCode::INTERNAL_SERVER_ERROR);

            }
        });

    })->create();
