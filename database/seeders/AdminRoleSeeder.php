<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminRole::query()->insert([
            'id' => 1,
            'admin_id' => 1,
            'role_id' => 1,
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
