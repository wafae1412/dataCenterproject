<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\Resource;
use App\Models\Reservation;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MaintenanceController extends Controller
{
    public function __construct()
    {
        // Protéger les actions de création/modification/suppression par rôle
        $this->middleware('auth');
        $this->middleware('role:Admin,Responsable')->except(['index', 'show']);
    }

    // Afficher la liste des maintenances
    public function index()
    {
        // Récupérer toutes les maintenances avec la ressource associée
        $maintenances = Maintenance::with('resource')
            ->orderBy('created_at', 'desc')
            ->get();

        // Mettre à jour les statuts des maintenances (simule une tâche cron)
        $this->updateMaintenanceStatuses();

        return view('maintenances.index', compact('maintenances'));
    }

    // Afficher le formulaire de création
    public function create(Request $request)
    {
        // Récupérer l'ID de la ressource depuis l'URL
        $resourceId = $request->query('resource_id');

        // La ressource sélectionnée (si fournie)
        $selectedResource = null;
        if ($resourceId) {
            $selectedResource = Resource::find($resourceId);
        }

        // Toutes les ressources pour la liste déroulante
        $allResources = Resource::orderBy('name')->get();

        return view('maintenances.create', [
            'resources' => $allResources,
            'selectedResource' => $selectedResource
        ]);
    }

    // Enregistrer une nouvelle maintenance
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:preventive,corrective,emergency,upgrade',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'estimated_duration' => 'nullable|integer|min:1|max:720',
            'notes' => 'nullable|string',
        ]);

        $resource = Resource::findOrFail($validated['resource_id']);
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        // GESTION DES CONFLITS : Annuler les réservations existantes qui chevauchent la maintenance
        $conflictingReservations = Reservation::where('resource_id', $resource->id)
            ->whereIn('status', ['approved', 'active'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date_start', [$startDate, $endDate])
                      ->orWhereBetween('date_end', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('date_start', '<', $startDate)
                            ->where('date_end', '>', $endDate);
                      });
            })
            ->get();

        foreach ($conflictingReservations as $reservation) {
            $reservation->update(['status' => 'rejected', 'rejection_reason' => 'Annulée pour cause de maintenance planifiée.']);
            
            // NOTIFICATION : Informer l'utilisateur de l'annulation
            Notification::create([
                'user_id' => $reservation->user_id,
                'message' => "Votre réservation pour '{$reservation->resource->name}' a été annulée pour cause de maintenance."
            ]);
        }

        // Création de la maintenance avec le statut par défaut
        $validated['status'] = 'scheduled';
        $maintenance = Maintenance::create($validated);

        // Le statut de la ressource sera mis à jour par la méthode updateMaintenanceStatuses()

        // Redirection avec message de succès
        return redirect()->route('maintenances.index')
            ->with('success', 'Maintenance planifiée avec succès. ' . $conflictingReservations->count() . ' réservation(s) affectée(s) ont été annulées.');
    }

    // Afficher les détails d'une maintenance
    public function show(Maintenance $maintenance)
    {
        return view('maintenances.show', compact('maintenance'));
    }

    // Afficher le formulaire d'édition
    public function edit(Maintenance $maintenance)
    {
        $resources = Resource::all();
        return view('maintenances.edit', compact('maintenance', 'resources'));
    }

    // Mettre à jour une maintenance
    public function update(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:preventive,corrective,emergency,upgrade',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'estimated_duration' => 'nullable|integer|min:1|max:720',
            'notes' => 'nullable|string',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
        ]);

        $maintenance->update($validated);

        // GESTION DES CONFLITS (Si les dates ont changé)
        if ($maintenance->wasChanged('start_date') || $maintenance->wasChanged('end_date')) {
            $startDate = Carbon::parse($validated['start_date']);
            $endDate = Carbon::parse($validated['end_date']);
            
            $conflictingReservations = Reservation::where('resource_id', $maintenance->resource_id)
                ->whereIn('status', ['approved', 'active'])
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date_start', [$startDate, $endDate])
                          ->orWhereBetween('date_end', [$startDate, $endDate])
                          ->orWhere(function ($q) use ($startDate, $endDate) {
                              $q->where('date_start', '<', $startDate)
                                ->where('date_end', '>', $endDate);
                          });
                })
                ->get();

            foreach ($conflictingReservations as $reservation) {
                $reservation->update(['status' => 'rejected', 'rejection_reason' => 'Annulée suite à la modification de la maintenance.']);
                Notification::create(['user_id' => $reservation->user_id, 'message' => "Votre réservation pour '{$reservation->resource->name}' a été annulée suite à une modification de maintenance."]);
            }
            Log::info("Maintenance #{$maintenance->id} modifiée. " . $conflictingReservations->count() . " réservations annulées.");
        }

        // Si la maintenance est manuellement marquée comme terminée ou annulée
        if (in_array($validated['status'], ['completed', 'cancelled'])) {
            $resource = $maintenance->resource;
            if ($resource) {
                // Vérifier s'il y a d'autres maintenances actives ou planifiées pour cette ressource
                $hasOtherMaintenances = Maintenance::where('resource_id', $resource->id)
                    ->where('id', '!=', $maintenance->id)
                    ->whereIn('status', ['scheduled', 'in_progress'])
                    ->exists();

                if (!$hasOtherMaintenances) {
                    $resource->update(['status' => 'available']);
                }
                
                // Notification aux admins
                $this->notifyAdmins("Maintenance terminée/annulée", "La maintenance '{$maintenance->title}' sur {$resource->name} est maintenant {$validated['status']}.");
            }
        }

        Log::info("Maintenance #{$maintenance->id} mise à jour par l'utilisateur #" . Auth::id());

        return redirect()->route('maintenances.index')
            ->with('success', 'Maintenance mise à jour avec succès.');
    }

    // Supprimer une maintenance
    public function destroy(Maintenance $maintenance)
    {
        // Vérification supplémentaire des permissions (déjà géré par middleware, mais double sécurité)
        if (!Auth::user()->isAdmin() && !Auth::user()->isResponsable()) {
            abort(403, 'Action non autorisée.');
        }

        $resource = $maintenance->resource;
        $title = $maintenance->title;

        $maintenance->delete();

        if ($resource) {
            // Vérifier s'il reste d'autres maintenances actives ou planifiées
            $hasOtherMaintenances = Maintenance::where('resource_id', $resource->id)
                ->whereIn('status', ['scheduled', 'in_progress'])
                ->exists();

            if (!$hasOtherMaintenances) {
                $resource->update(['status' => 'available']);
            }
        }

        Log::warning("Maintenance '{$title}' supprimée par l'utilisateur #" . Auth::id());
        $this->notifyAdmins("Maintenance supprimée", "La maintenance '{$title}' a été supprimée du planning.");

        return redirect()->route('maintenances.index')
            ->with('success', 'Maintenance supprimée avec succès.');
    }

  
    private function updateMaintenanceStatuses()
    {
        $now = Carbon::now();

        // 1. Démarrer les maintenances qui doivent commencer
        $maintenancesToStart = Maintenance::with('resource')
            ->where('status', 'scheduled')
            ->where('start_date', '<=', $now)
            ->get();

        foreach ($maintenancesToStart as $maintenance) {
            $maintenance->update(['status' => 'in_progress']);
            $maintenance->resource->update(['status' => 'maintenance']);
        }

        // 2. Terminer les maintenances qui sont finies
        $maintenancesToComplete = Maintenance::with('resource')
            ->where('status', 'in_progress')
            ->where('end_date', '<', $now)
            ->get();

        foreach ($maintenancesToComplete as $maintenance) {
            $maintenance->update(['status' => 'completed']);

            // Vérifier si une autre maintenance est active avant de rendre la ressource disponible
            $isOtherMaintenanceActive = Maintenance::where('resource_id', $maintenance->resource_id)
                ->where('id', '!=', $maintenance->id)
                ->whereIn('status', ['in_progress'])
                ->exists();

            if (!$isOtherMaintenanceActive) {
                $maintenance->resource->update(['status' => 'available']);
            }
        }
    }

    /**
     * Helper pour notifier les admins
     */
    private function notifyAdmins($title, $message)
    {
        $admins = \App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'Admin');
        })->get();

        foreach ($admins as $admin) {
            Notification::create(['user_id' => $admin->id, 'message' => "$title: $message"]);
        }
    }
}
