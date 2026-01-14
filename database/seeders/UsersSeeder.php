<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Wafae-user',
            'email' => 'user@datacenter.com',
            'password' => Hash::make('12345678'),
            'role_id' => 3, // user
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
