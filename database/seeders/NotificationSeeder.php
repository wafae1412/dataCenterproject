<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        // **Admins**
        $admins = User::whereHas('role', fn($q) => $q->where('name', 'Admin'))->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'message' => 'Nouvelle réservation en attente d\'approbation.',
                'is_read' => false
            ]);

            Notification::create([
                'user_id' => $admin->id,
                'message' => 'Maintenance planifiée sur la ressource X.',
                'is_read' => false
            ]);
        }

        // **Responsables**
        $responsables = User::whereHas('role', fn($q) => $q->where('name', 'Responsable'))->get();
        foreach ($responsables as $resp) {
            Notification::create([
                'user_id' => $resp->id,
                'message' => 'Une nouvelle réservation nécessite votre validation.',
                'is_read' => false
            ]);

            Notification::create([
                'user_id' => $resp->id,
                'message' => 'Maintenance programmée sur vos ressources supervisées.',
                'is_read' => false
            ]);
        }

        // **Utilisateurs internes**
        $internes = User::whereHas('role', fn($q) => $q->where('name', 'Utilisateur interne'))->get();
        foreach ($internes as $user) {
            Notification::create([
                'user_id' => $user->id,
                'message' => 'Votre réservation pour le serveur A a été approuvée.',
                'is_read' => false
            ]);

            Notification::create([
                'user_id' => $user->id,
                'message' => 'Maintenance prévue demain sur la ressource B que vous utilisez.',
                'is_read' => false
            ]);
        }
    }
}
