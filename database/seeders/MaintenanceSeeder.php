<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Maintenance;
use App\Models\Resource;
use Carbon\Carbon;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $resources = Resource::all();

        if ($resources->isEmpty()) {
            return;
        }

        // Créer des maintenances de test
        $maintenances = [
            [
                'resource_id' => $resources->first()->id,
                'title' => 'Maintenance Préventive Serveur 1',
                'description' => 'Vérification complète et mise à jour du système',
                'type' => 'preventive',
                'start_date' => Carbon::now()->addDays(2),
                'end_date' => Carbon::now()->addDays(2)->addHours(2),
                'status' => 'scheduled',
            ],
            [
                'resource_id' => $resources->get(1)->id ?? $resources->first()->id,
                'title' => 'Maintenance Corrective Serveur 2',
                'description' => 'Réparation de la panne détectée',
                'type' => 'corrective',
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(5)->addHours(3),
                'status' => 'scheduled',
            ],
            [
                'resource_id' => $resources->get(2)->id ?? $resources->first()->id,
                'title' => 'Entretien Stockage',
                'description' => 'Nettoyage et optimisation du stockage',
                'type' => 'preventive',
                'start_date' => Carbon::now()->addDays(7),
                'end_date' => Carbon::now()->addDays(7)->addHours(1),
                'status' => 'scheduled',
            ],
            [
                'resource_id' => $resources->get(3)->id ?? $resources->first()->id,
                'title' => 'Mise à jour Sécurité',
                'description' => 'Application des patchs de sécurité',
                'type' => 'preventive',
                'start_date' => Carbon::now()->subDays(2),
                'end_date' => Carbon::now()->subDays(2)->addHours(1),
                'status' => 'completed',
            ],
        ];

        foreach ($maintenances as $maintenance) {
            Maintenance::create($maintenance);
        }
    }
}
