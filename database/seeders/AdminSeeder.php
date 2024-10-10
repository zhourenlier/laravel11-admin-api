<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::query()->insert([
            'id' => 1,
            'username' => "admin",
            'password' => password_hash("123456", PASSWORD_DEFAULT, ['cost' => Admin::PWD_COST]),
            'mobile' => null,
            'email' => null,
            'remember_token' => "",
            'status' => Admin::ACTIVE,
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
