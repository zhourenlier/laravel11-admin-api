<?php

namespace App\Models;

class AdminLog extends BaseModel
{
    protected $guarded = ['id'];

    const TYPE_LOGIN = 1; //登录
    const TYPE_BEHAVIOR = 2;    //行为
}
