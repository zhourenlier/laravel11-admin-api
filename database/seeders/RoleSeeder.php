<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->insert([
            'id' => 1,
            'name' => "超级管理员",
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
