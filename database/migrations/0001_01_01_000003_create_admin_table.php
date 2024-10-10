<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->id();
            $table->string('username', 40)->comment("用户名");
            $table->string('password', 100)->comment("密码");
            $table->string('mobile', 20)->comment("手机号")->nullable(true);
            $table->string('email', 50)->comment("邮箱")->nullable(true);
            $table->string('remember_token', 100)->comment("记住token");
            $table->tinyInteger('status')->comment("状态")->default(0);
            $table->timestamps();

            $table->unique("username", "uni_username");
            $table->index("remember_token", "index_token");
        });

        Schema::create('admin_logs', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->id();
            $table->bigInteger('admin_id')->comment("管理员ID")->default(0);
            $table->tinyInteger('type')->comment("类型")->default(0);
            $table->string('ip', 40)->comment("ip")->nullable(true);
            $table->string('url', 200)->comment("请求url")->default("");
            $table->string('method', 40)->comment("请求方式")->nullable(true);
            $table->json('param')->comment("请求参数")->nullable(true);
            $table->timestamps();

            $table->index("admin_id", "index_admin_id");
            $table->index("url", "index_url");
            $table->index("created_at", "index_created_at");
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->id();
            $table->string('name', 100)->comment("角色名");
            $table->timestamps();

            $table->unique("name", "uni_name");
        });

        Schema::create('rules', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->id();
            $table->integer('pid')->comment("父级ID")->default(0);
            $table->string('title', 60)->comment("标题");
            $table->string('rule', 40)->comment("规则路由")->nullable(true);
            $table->tinyInteger('is_check')->comment("是否校验")->default(0);
            $table->tinyInteger('is_log')->comment("是否记录日志")->default(0);
            $table->integer('sort')->comment("排序")->default(0);
            $table->timestamps();

            $table->index("pid", "index_pid");
        });

        Schema::create('role_rules', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('role_id')->comment("角色ID")->default(0);
            $table->integer('rule_id')->comment("权限ID")->default(0);
            $table->timestamps();

            $table->unique(["role_id", "rule_id"], "uni_rr");
        });

        Schema::create('admin_roles', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->id();
            $table->integer('admin_id')->comment("管理员ID")->default(0);
            $table->integer('role_id')->comment("角色ID")->default(0);
            $table->timestamps();

            $table->unique(["admin_id", "role_id"], "uni_ar");
        });

        Schema::create('configs', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->string('key')->comment("key");
            $table->string('value', 2000)->comment("配置值");
            $table->unique("key", "uni_key");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
        Schema::dropIfExists('admin_logs');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('rules');
        Schema::dropIfExists('role_rules');
        Schema::dropIfExists('admin_roles');
    }
};
