<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Serveurs Physiques'],
            ['name' => 'Machines Virtuelles'],
            ['name' => 'Stockage (SAN/NAS)'],
            ['name' => 'Équipements Réseau'],
            ['name' => 'Sauvegarde'],
            ['name' => 'Sécurité'],
            ['name' => 'Cloud Privé'],
            ['name' => 'Conteneurs']
        ];

        DB::table('categories')->insert($categories);
    }
}
