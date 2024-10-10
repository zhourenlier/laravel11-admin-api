<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Role;
use App\Models\Rule;
use Illuminate\Database\Seeder;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = date("Y-m-d H:i:s");

        Rule::query()->insert([
            ['id' => 1, 'pid' => 0, 'title' => "系统配置", 'rule' => null, 'is_check' => 0, 'is_log' => 0, 'sort' => 100, 'created_at' => $now]
            ,['id' => 2, 'pid' => 0, 'title' => "日志管理", 'rule' => null, 'is_check' => 0, 'is_log' => 0, 'sort' => 100, 'created_at' => $now]
            ,['id' => 3, 'pid' => 0, 'title' => "管理员", 'rule' => null, 'is_check' => 0, 'is_log' => 0, 'sort' => 100, 'created_at' => $now]
            ,['id' => 4, 'pid' => 0, 'title' => "角色管理", 'rule' => null, 'is_check' => 0, 'is_log' => 0, 'sort' => 100, 'created_at' => $now]
            ,['id' => 5, 'pid' => 0, 'title' => "权限管理", 'rule' => null, 'is_check' => 0, 'is_log' => 0, 'sort' => 100, 'created_at' => $now]
            ,['id' => 6, 'pid' => 1, 'title' => "查询", 'rule' => "GET|api/admin/system/config", 'is_check' => 1, 'is_log' => 0, 'sort' => 100, 'created_at' => $now]
            ,['id' => 7, 'pid' => 1, 'title' => "更新", 'rule' => "PATCH|api/admin/system/config", 'is_check' => 1, 'is_log' => 1, 'sort' => 100, 'created_at' => $now]
            ,['id' => 8, 'pid' => 2, 'title' => "日志列表", 'rule' => "GET|api/admin/adminLog", 'is_check' => 1, 'is_log' => 0, 'sort' => 100, 'created_at' => $now]
            ,['id' => 9, 'pid' => 3, 'title' => "列表", 'rule' => "GET|api/admin/admin", 'is_check' => 1, 'is_log' => 0, 'sort' => 100, 'created_at' => $now]
            ,['id' => 10, 'pid' => 3, 'title' => "更新", 'rule' => "PATCH|api/admin/admin/{id}", 'is_check' => 1, 'is_log' => 1, 'sort' => 100, 'created_at' => $now]
            ,['id' => 11, 'pid' => 3, 'title' => "详情", 'rule' => "GET|api/admin/admin/{id}", 'is_check' => 1, 'is_log' => 0, 'sort' => 100, 'created_at' => $now]
            ,['id' => 12, 'pid' => 3, 'title' => "创建", 'rule' => "POST|api/admin/admin", 'is_check' => 1, 'is_log' => 1, 'sort' => 100, 'created_at' => $now]
            ,['id' => 13, 'pid' => 3, 'title' => "删除", 'rule' => "DELETE|api/admin/admin/{id}", 'is_check' => 1, 'is_log' => 1, 'sort' => 100, 'created_at' => $now]
            ,['id' => 14, 'pid' => 4, 'title' => "列表", 'rule' => "GET|api/admin/role", 'is_check' => 1, 'is_log' => 0, 'sort' => 100, 'created_at' => $now]
            ,['id' => 15, 'pid' => 4, 'title' => "更新", 'rule' => "PATCH|api/admin/role/{id}", 'is_check' => 1, 'is_log' => 1, 'sort' => 100, 'created_at' => $now]
            ,['id' => 16, 'pid' => 4, 'title' => "详情", 'rule' => "GET|api/admin/role/{id}", 'is_check' => 1, 'is_log' => 0, 'sort' => 100, 'created_at' => $now]
            ,['id' => 17, 'pid' => 4, 'title' => "创建", 'rule' => "POST|api/admin/role", 'is_check' => 1, 'is_log' => 1, 'sort' => 100, 'created_at' => $now]
            ,['id' => 18, 'pid' => 4, 'title' => "删除", 'rule' => "DELETE|api/admin/role/{id}", 'is_check' => 1, 'is_log' => 1, 'sort' => 100, 'created_at' => $now]
            ,['id' => 19, 'pid' => 4, 'title' => "设置角色权限", 'rule' => 'POST|api/admin/role/{id}/rule', 'is_check' => 1, 'is_log' => 1, 'sort' => 100, 'created_at' => $now]
            ,['id' => 20, 'pid' => 4, 'title' => "全部权限", 'rule' => "GET|api/admin/role/all", 'is_check' => 1, 'is_log' => 0, 'sort' => 100, 'created_at' => $now]
            ,['id' => 21, 'pid' => 4, 'title' => "更新", 'rule' => "PATCH|api/admin/rule/{id}", 'is_check' => 1, 'is_log' => 1, 'sort' => 100, 'created_at' => $now]
            ,['id' => 22, 'pid' => 4, 'title' => "详情", 'rule' => "GET|api/admin/rule/{id}", 'is_check' => 1, 'is_log' => 0, 'sort' => 100, 'created_at' => $now]
            ,['id' => 23, 'pid' => 4, 'title' => "创建", 'rule' => "POST|api/admin/rule", 'is_check' => 1, 'is_log' => 1, 'sort' => 100, 'created_at' => $now]
            ,['id' => 24, 'pid' => 4, 'title' => "删除", 'rule' => "DELETE|api/admin/rule/{id}", 'is_check' => 1, 'is_log' => 1, 'sort' => 100, 'created_at' => $now]
        ]);
    }
}
