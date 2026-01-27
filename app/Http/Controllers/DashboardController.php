<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Afficher le dashboard selon le rôle
     */
    public function index()
    {
        $user = Auth::user();
        
        // Vérifier le rôle
        if ($user->role->name === 'Admin') {
            return $this->adminDashboard();
        } elseif ($user->role->name === 'Responsable') {
            return $this->responsableDashboard();
        } elseif ($user->role->name === 'Guest') {
            return $this->guestDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    /**
     * Dashboard Admin
     */
    private function adminDashboard()
    {
        // 1. Statistiques RESSOURCES
        $total_resources = Resource::count();
        $available_resources = Resource::where('status', 'available')->count();
        $reserved_resources = Resource::where('status', 'reserved')->count(); // CHANGÉ: 'reserved' pas 'occupied'
        $maintenance_resources = Resource::where('status', 'maintenance')->count(); // ⭐ IMPORTANT
        
        // 2. Statistiques MAINTENANCE
        $active_maintenances_count = Maintenance::whereIn('status', ['scheduled', 'in_progress'])->count();
        $upcoming_maintenances_count = Maintenance::where('start_date', '>', now())
            ->where('status', 'scheduled')
            ->count();
        
        // 3. Statistiques UTILISATEURS
        $total_users = User::count();
        
        // 4. Statistiques RÉSERVATIONS
        $total_reservations = Reservation::count();
        $pending_reservations = Reservation::where('status', 'pending')->count();
        $active_reservations = Reservation::where('status', 'active')->count();
        $finished_reservations = Reservation::where('status', 'finished')->count();
        
        // 5. Calcul des pourcentages
        $occupation_rate = $total_resources > 0 ? round(($reserved_resources / $total_resources) * 100, 2) : 0;
        $maintenance_percentage = $total_resources > 0 ? round(($maintenance_resources / $total_resources) * 100, 2) : 0;
        
        // 6. Tableau des stats
        $stats = [
            // Ressources
            'total_resources' => $total_resources,
            'available_resources' => $available_resources,
            'reserved_resources' => $reserved_resources, // CHANGÉ
            'maintenance_resources' => $maintenance_resources, // AJOUTÉ
            
            // Maintenance
            'active_maintenances' => $active_maintenances_count,
            'upcoming_maintenances' => $upcoming_maintenances_count,
            'maintenance_percentage' => $maintenance_percentage,
            
            // Utilisateurs
            'total_users' => $total_users,
            
            // Réservations
            'total_reservations' => $total_reservations,
            'pending_reservations' => $pending_reservations,
            'active_reservations' => $active_reservations,
            'finished_reservations' => $finished_reservations,
            
            // Pourcentages
            'occupation_rate' => $occupation_rate,
        ];
        
        // 7. Données pour affichage
        $active_maintenances = Maintenance::with('resource')
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->orderBy('start_date', 'asc')
            ->get();
            
        $recent_reservations = Reservation::with(['user', 'resource'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        $resources = Resource::with('category')->get();
        
        // 8. Retourner la vue avec TOUTES les données
        return view('admin.dashboard', compact(
            'stats',
            'active_maintenances',
            'recent_reservations',
            'resources'
        ));
    }

    /**
     * Dashboard Responsable
     */
    private function responsableDashboard()
    {
        // Mêmes corrections ici
        $stats = [
            'total_resources' => Resource::count(),
            'available_resources' => Resource::where('status', 'available')->count(),
            'occupied_resources' => Resource::where('status', 'reserved')->count(),
            'reserved_resources' => Resource::where('status', 'reserved')->count(),
            'maintenance_resources' => Resource::where('status', 'maintenance')->count(),
            'active_reservations' => Reservation::where('status', 'active')->count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
        ];

        $recent_reservations = Reservation::with(['user', 'resource'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('responsable.dashboard', compact('stats', 'recent_reservations'));
    }

    /**
     * Dashboard Utilisateur
     */
    private function userDashboard()
    {
        $user = Auth::user();

        $stats = [
            'my_reservations' => $user->reservations()->count(),
            'active_reservations' => $user->reservations()->where('status', 'active')->count(),
            'pending_reservations' => $user->reservations()->where('status', 'pending')->count(),
            'finished_reservations' => $user->reservations()->where('status', 'finished')->count(),
        ];

        $my_reservations = $user->reservations()
            ->with('resource')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $available_resources = Resource::where('status', 'available')
            ->with('category')
            ->limit(6)
            ->get();

        return view('dashboard', compact('stats', 'my_reservations', 'available_resources'));
    }

    /**
     * Dashboard Guest
     */
    private function guestDashboard()
    {
        $stats = [
            'total_resources' => Resource::count(),
            'available_resources' => Resource::where('status', 'available')->count(),
            'maintenance_resources' => Resource::where('status', 'maintenance')->count(),
            'total_maintenances' => Maintenance::count(),
        ];

        $available_resources = Resource::where('status', 'available')
            ->with('category')
            ->limit(6)
            ->get();

        $upcoming_maintenances = Maintenance::where('status', 'scheduled')
            ->with('resource')
            ->orderBy('start_date', 'asc')
            ->limit(5)
            ->get();

        return view('guest.dashboard', compact('stats', 'available_resources', 'upcoming_maintenances'));
    }

    /**
     * Données pour graphiques (optionnel)
     */
    public function getChartData()
    {
        $categories = \App\Models\Category::with(['resources' => function ($query) {
            $query->withCount('reservations');
        }])->get();

        $chartData = [];
        foreach ($categories as $category) {
            $total = $category->resources->count();
            $reserved = $category->resources->filter(function ($resource) {
                return $resource->status === 'reserved'; // CHANGÉ
            })->count();
            
            $chartData[] = [
                'name' => $category->name,
                'total' => $total,
                'reserved' => $reserved,
                'rate' => $total > 0 ? round(($reserved / $total) * 100, 2) : 0
            ];
        }

        $reservations_per_day = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $count = Reservation::whereDate('created_at', $date)->count();
            $reservations_per_day[] = [
                'date' => Carbon::parse($date)->format('d/m'),
                'count' => $count
            ];
        }

        return response()->json([
            'categories' => $chartData,
            'reservations' => $reservations_per_day
        ]);
    }
}