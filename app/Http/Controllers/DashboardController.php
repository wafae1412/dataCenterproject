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
     * Afficher le dashboard utilisateur
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isResponsable()) {
            return $this->responsableDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    /**
     * Dashboard Admin avec statistiques globales
     */
    private function adminDashboard()
    {
        $stats = [
            'total_resources' => Resource::count(),
            'available_resources' => Resource::where('status', 'available')->count(),
            'occupied_resources' => Resource::where('status', 'reserved')->count(),
            'maintenance_resources' => Resource::where('status', 'maintenance')->count(),
            'total_users' => User::count(),
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'active_reservations' => Reservation::where('status', 'active')->count(),
            'finished_reservations' => Reservation::where('status', 'finished')->count(),
            'occupation_rate' => $this->calculateOccupationRate(),
            'total_maintenances' => Maintenance::count(),
            'scheduled_maintenances' => Maintenance::where('status', 'scheduled')->count(),
            'in_progress_maintenances' => Maintenance::where('status', 'in_progress')->count(),
        ];

        $recent_reservations = Reservation::with(['user', 'resource'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Maintenances récentes et à venir
        $recent_maintenances = Maintenance::with('resource')
            ->orderBy('start_date', 'desc')
            ->limit(5)
            ->get();

        $resources = Resource::with('category')->get();
        
        return view('admin.dashboard', compact('stats', 'recent_reservations', 'resources', 'recent_maintenances'));
    }

    /**
     * Dashboard Responsable
     */
    private function responsableDashboard()
    {
        $stats = [
            'total_resources' => Resource::count(),
            'available_resources' => Resource::where('status', 'available')->count(),
            'occupied_resources' => Resource::where('status', 'reserved')->count(),
            'maintenance_resources' => Resource::where('status', 'maintenance')->count(),
            'active_reservations' => Reservation::where('status', 'active')->count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'in_progress_maintenances' => Maintenance::where('status', 'in_progress')->count(),
            'scheduled_maintenances' => Maintenance::where('status', 'scheduled')->count(),
        ];

        $recent_reservations = Reservation::with(['user', 'resource'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Maintenances récentes et à venir
        $recent_maintenances = Maintenance::with('resource')
            ->orderBy('start_date', 'desc')
            ->limit(5)
            ->get();

        return view('responsable.dashboard', compact('stats', 'recent_reservations', 'recent_maintenances'));
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
     * Calculer le taux d'occupation
     */
    private function calculateOccupationRate()
    {
        $total_resources = Resource::count();
        if ($total_resources == 0) return 0;

        $unavailable = Resource::whereIn('status', ['reserved', 'maintenance'])->count();
        return round(($unavailable / $total_resources) * 100, 2);
    }

    /**
     * Obtenir les données pour les graphiques
     */
    public function getChartData()
    {
        // Données pour le graphique d'occupation par catégorie
        $categories = \App\Models\Category::with(['resources' => function ($query) {
            $query->withCount('reservations');
        }])->get();

        $chartData = [];
        foreach ($categories as $category) {
            $total = $category->resources->count();
            $occupied = $category->resources->filter(function ($resource) {
                return $resource->status === 'occupied';
            })->count();
            
            $chartData[] = [
                'name' => $category->name,
                'total' => $total,
                'occupied' => $occupied,
                'rate' => $total > 0 ? round(($occupied / $total) * 100, 2) : 0
            ];
        }

        // Données pour l'historique des réservations (7 derniers jours)
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
