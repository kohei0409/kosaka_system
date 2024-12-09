<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run()
{
    \App\Models\Role::insert([
        ['name' => 'SuperAdmin', 'description' => '絶対管理者'],
        ['name' => 'Admin', 'description' => '管理者'],
        ['name' => 'Manager', 'description' => 'マネージャー'],
        ['name' => 'User', 'description' => 'ユーザー'],
        ['name' => 'Guest', 'description' => 'ゲスト'],
    ]);
}

}
