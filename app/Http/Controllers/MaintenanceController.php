<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\Resource;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = Maintenance::with('resource')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('maintenances.index', compact('maintenances'));
    }

   public function create(Request $request)
{
    // Récupérer l'ID de la ressource depuis l'URL (query string)
    $resourceId = $request->query('resource_id');

    // La ressource sélectionnée (si fournie)
    $selectedResource = null;
    if ($resourceId) {
        $selectedResource = Resource::find($resourceId);
    }

    // Toutes les ressources pour la liste
    $allResources = Resource::all();

    return view('maintenances.create', [
        'resources' => $allResources,
        'selectedResource' => $selectedResource,
        'resource' => $selectedResource
    ]);
}

    public function store(Request $request)
    {
        // VALIDATION DES DONNÉES
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

        // AJOUTER LE STATUT PAR DÉFAUT
        $validated['status'] = 'scheduled';

        // CRÉATION DE LA MAINTENANCE
        Maintenance::create($validated);

        // MISE À JOUR DU STATUT DE LA RESSOURCE
        $resource = Resource::findOrFail($validated['resource_id']);
        $resource->update(['status' => 'maintenance']);

        // REDIRECTION AVEC MESSAGE DE SUCCÈS
        return redirect()->route('maintenances.index')
            ->with('success', 'Maintenance planifiée avec succès.');
    }

    // AJOUTEZ CES MÉTHODES POUR UN CRUD COMPLET
    public function show(Maintenance $maintenance)
    {
        return view('maintenances.show', compact('maintenance'));
    }

    public function edit(Maintenance $maintenance)
    {
        $resources = Resource::all();
        return view('maintenances.edit', compact('maintenance', 'resources'));
    }

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

        return redirect()->route('maintenances.index')
            ->with('success', 'Maintenance mise à jour avec succès.');
    }

    public function destroy(Maintenance $maintenance)
    {
        // Remettre la ressource en état disponible si nécessaire
        if ($maintenance->resource) {
            $maintenance->resource->update(['status' => 'available']);
        }

        $maintenance->delete();

        return redirect()->route('maintenances.index')
            ->with('success', 'Maintenance supprimée avec succès.');
    }
}
