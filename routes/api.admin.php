<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->namespace("App\Http\Controllers\Admin")->group(function () {
    Route::post('signIn', 'LoginController@signIn');//登录

    Route::middleware(['admin.auth'])->group(function () {
        Route::delete('cache', 'SystemsController@cleanCache');//清理缓存
        Route::get('logout', 'LoginController@logout');//退出系统

        //接入授权管理
        Route::middleware(['admin.rbac', 'admin.log'])->group(function () {
            //系统管理
            Route::prefix('system')->group(function (){
                Route::get('config', 'SystemsController@getConfig'); //获取系统设置
                Route::patch('config', 'SystemsController@updateConfig'); //获取系统设置
            });

            //管理员日志
            Route::prefix('adminLog')->group(function (){
                Route::get('', 'AdminLogController@lists'); //日志
            });

            //管理员
            Route::prefix('admin')->group(function (){
                Route::get('', 'AdminsController@lists');  //列表
                Route::post('', 'AdminsController@store'); //保存
                Route::patch('{id}', 'AdminsController@update'); //更新
                Route::delete('{id}', 'AdminsController@destroy'); //删除
                Route::get('{id}', 'AdminsController@info'); //详情
            });

            //管理员角色
            Route::prefix('role')->group(function (){
                Route::get('', 'RolesController@lists');  //列表
                Route::post('', 'RolesController@store'); //保存
                Route::patch('{id}', 'RolesController@update'); //更新
                Route::delete('{id}', 'RolesController@destroy'); //删除
                Route::get('{id}', 'RolesController@info'); //详情
                Route::post('{id}/rule', 'RolesController@setRules'); //设置规则
            });

            //权限规则
            Route::prefix('rule')->group(function (){
                Route::get('all', 'RulesController@all');  //全部规则
                Route::post('', 'RulesController@store'); //保存
                Route::patch('{id}', 'RulesController@update'); //更新
                Route::delete('{id}', 'RulesController@destroy'); //删除
            });
        });

    });
});
