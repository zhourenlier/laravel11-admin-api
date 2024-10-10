<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Role;
use App\Models\RoleRule;
use Illuminate\Database\Seeder;

class RoleRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = date("Y-m-d H:i:s");

        RoleRule::query()->insert([
            ['role_id' => 1, 'rule_id' => 1, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 2, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 3, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 4, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 5, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 6, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 7, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 8, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 9, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 10, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 11, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 12, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 13, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 14, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 15, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 16, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 17, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 18, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 19, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 20, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 21, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 22, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 23, 'created_at' => $now]
            ,['role_id' => 1, 'rule_id' => 24, 'created_at' => $now]
        ]);
    }
}
