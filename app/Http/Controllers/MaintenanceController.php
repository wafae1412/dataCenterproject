<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\Resource;
use Illuminate\Support\Facades\Log;

class MaintenanceController extends Controller
{
    // Afficher la liste des maintenances
    public function index()
    {
        // Récupérer toutes les maintenances avec la ressource associée
        $maintenances = Maintenance::with('resource')
            ->orderBy('created_at', 'desc')
            ->get();

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
        $allResources = Resource::all();

        return view('maintenances.create', [
            'resources' => $allResources,
            'selectedResource' => $selectedResource,
            'resource' => $selectedResource
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
            'start_date' => 'required|date_format:Y-m-d\TH:i',
            'end_date' => 'required|date_format:Y-m-d\TH:i|after:start_date',
            'estimated_duration' => 'nullable|integer|min:1|max:720',
            'notes' => 'nullable|string',
        ]);

        // Ajouter le statut par défaut
        $validated['status'] = 'scheduled';

        // Création de la maintenance
        $maintenance = Maintenance::create($validated);

        // Mise à jour du statut de la ressource
        $resource = Resource::findOrFail($validated['resource_id']);
        $resource->status = 'maintenance';
        $resource->save(); // Force la sauvegarde

        // Redirection avec message de succès
        return redirect()->route('maintenances.index')
            ->with('success', 'Maintenance planifiée avec succès.');
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

        return redirect()->route('maintenances.index')
            ->with('success', 'Maintenance mise à jour avec succès.');
    }

    // Supprimer une maintenance
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
