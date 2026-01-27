<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
{
    $this->call([
        RolesSeeder::class,
        CategoriesSeeder::class,
        UsersSeeder::class,
        ResourcesSeeder::class,
        MaintenanceSeeder::class,
    ]);
}
}
