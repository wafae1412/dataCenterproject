<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
     $users = [
        [
            'name' => 'Wafae ',
            'email' => 'admin@datacenter.com',
            'password' => Hash::make('admin123'),
            'role_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ],  [
            'name' => 'nour ',
            'email' => 'responsable@datacenter.com',
            'password' => Hash::make('responsable123'),
            'role_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ],  [
            'name' => 'sara',
            'email' => 'user@datacenter.com',
            'password' => Hash::make('user123'),
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'maryam',
            'email' => 'guest@datacenter.com',
            'password' => Hash::make('guest123'),
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        ];
        DB::table('users')->insert($users);
    }
}
