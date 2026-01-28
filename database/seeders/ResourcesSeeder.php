<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourcesSeeder extends Seeder
{
    public function run()
    {
        $resources = [
            // Serveurs Physiques
            [
                'name' => 'Serveur ProLiant DL380',
                'category_id' => 1,
                'cpu' => 16,
                'ram' => 64,
                'storage' => 2000,
                'status' => 'available',
                'description' => 'Serveur HPE ProLiant DL380 Gen10',
                'location' => 'Rack A-01'
            ],
            [
                'name' => 'Serveur Dell PowerEdge R740',
                'category_id' => 1,
                'cpu' => 24,
                'ram' => 128,
                'storage' => 4000,
                'status' => 'available',
                'description' => 'Serveur Dell PowerEdge R740xd',
                'location' => 'Rack A-02'
            ],

            // Machines Virtuelles
            [
                'name' => 'VM Web Server',
                'category_id' => 2,
                'cpu' => 4,
                'ram' => 8,
                'storage' => 200,
                'status' => 'available',
                'description' => 'Machine virtuelle pour serveur web',
                'location' => 'Cluster VMware'
            ],
            [
                'name' => 'VM Database Server',
                'category_id' => 2,
                'cpu' => 8,
                'ram' => 16,
                'storage' => 500,
                'status' => 'reserved',
                'description' => 'Machine virtuelle pour base de données',
                'location' => 'Cluster VMware'
            ],

            // Stockage
            [
                'name' => 'SAN NetApp AFF A300',
                'category_id' => 3,
                'cpu' => 0,
                'ram' => 0,
                'storage' => 50000,
                'status' => 'available',
                'description' => 'Stockage NetApp All Flash',
                'location' => 'Salle Stockage'
            ],

            // Équipements Réseau
            [
                'name' => 'Switch Cisco Nexus 93180',
                'category_id' => 4,
                'cpu' => 0,
                'ram' => 0,
                'storage' => 0,
                'status' => 'available',
                'description' => 'Switch 48 ports 10G',
                'location' => 'Rack réseau'
            ],

            // Sauvegarde
            [
                'name' => 'Backup Server Veeam',
                'category_id' => 5,
                'cpu' => 8,
                'ram' => 32,
                'storage' => 10000,
                'status' => 'available',
                'description' => 'Serveur de sauvegarde Veeam',
                'location' => 'Rack B-01'
            ],

            // Sécurité
            [
                'name' => 'Firewall FortiGate 600E',
                'category_id' => 6,
                'cpu' => 0,
                'ram' => 0,
                'storage' => 0,
                'status' => 'maintenance',
                'description' => 'Firewall nouvelle génération',
                'location' => 'DMZ'
            ]
        ];

        DB::table('resources')->insert($resources);
    }
}
