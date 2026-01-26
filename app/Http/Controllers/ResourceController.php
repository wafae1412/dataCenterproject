<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Category;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ResourceController extends Controller
{
    // Afficher liste ressources avec filtres
    public function index(Request $request)
    {
        $query = Resource::with('category');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $resources = $query->orderBy('created_at', 'desc')->get();
        $categories = Category::all();

        return view('resources.index', compact('resources', 'categories'));
    }

    // Afficher formulaire création
    public function create()
    {
        $categories = Category::all();
        return view('resources.create', compact('categories'));
    }

    // Enregistrer nouvelle ressource
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'cpu' => 'required|integer|min:1',
            'ram' => 'required|integer|min:1',
            'storage' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255'
        ]);

        Resource::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'cpu' => $request->cpu,
            'ram' => $request->ram,
            'storage' => $request->storage,
            'description' => $request->description,
            'location' => $request->location,
            'status' => 'available'
        ]);

        return redirect()->route('resources.index')
            ->with('success', 'Ressource créée avec succès.');
    }

    // Afficher détails ressource
    public function show(Resource $resource)
    {
        $resource->load(['category', 'reservations.user', 'maintenances']);
        return view('resources.show', compact('resource'));
    }

    // Afficher formulaire édition
    public function edit(Resource $resource)
    {
        $categories = Category::all();
        return view('resources.edit', compact('resource', 'categories'));
    }

    // Mettre à jour ressource
    public function update(Request $request, Resource $resource)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'cpu' => 'required|integer|min:1',
            'ram' => 'required|integer|min:1',
            'storage' => 'required|integer|min:1',
            'status' => 'required|in:available,reserved,maintenance,disabled',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255'
        ]);

        $resource->update($request->all());

        return redirect()->route('resources.index')
            ->with('success', 'Ressource mise à jour avec succès.');
    }

    // Supprimer ressource
    public function destroy(Resource $resource)
    {
        if ($resource->reservations()->where('status', 'active')->exists()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer une ressource avec des réservations actives.');
        }

        $resource->delete();

        return redirect()->route('resources.index')
            ->with('success', 'Ressource supprimée avec succès.');
    }

    // Changer état ressource
    public function changeStatus(Request $request, Resource $resource)
    {
        // Test: vérifier si la fonction est appelée
        dd([
            'test' => 'changeStatus fonctionne',
            'resource_id' => $resource->id,
            'statut_actuel' => $resource->status,
            'nouveau_statut_demande' => $request->status,
            'donnees_requete' => $request->all()
        ]);

        $request->validate([
            'status' => 'required|in:available,reserved,maintenance,disabled'
        ]);

        $oldStatus = $resource->status;
        $newStatus = $request->status;

        $resource->update(['status' => $newStatus]);

        $this->syncMaintenanceWithResource($resource, $oldStatus, $newStatus);

        return redirect()->back()
            ->with('success', 'Statut de la ressource mis à jour.');
    }

    // Synchroniser maintenance avec ressource
    private function syncMaintenanceWithResource(Resource $resource, $oldStatus, $newStatus)
    {
        // Si maintenance -> disponible
        if ($oldStatus == 'maintenance' && $newStatus == 'available') {
            $resource->maintenances()
                ->whereIn('status', ['in_progress', 'scheduled'])
                ->update([
                    'status' => 'completed',
                    'end_date' => now(),
                    'updated_at' => now()
                ]);
        }

        // Si devient en maintenance
        elseif ($newStatus == 'maintenance') {
            $activeMaintenance = $resource->maintenances()
                ->whereIn('status', ['scheduled', 'in_progress'])
                ->first();

            if (!$activeMaintenance) {
                Maintenance::create([
                    'resource_id' => $resource->id,
                    'title' => 'Maintenance automatique - ' . $resource->name,
                    'description' => 'Maintenance déclenchée par changement de statut de la ressource',
                    'type' => 'corrective',
                    'start_date' => now(),
                    'end_date' => now()->addHours(2),
                    'estimated_duration' => 120,
                    'notes' => 'Maintenance automatique créée suite au changement de statut',
                    'status' => 'in_progress'
                ]);
            }
        }

        // Si devient désactivé
        elseif ($newStatus == 'disabled') {
            $resource->maintenances()
                ->where('status', 'scheduled')
                ->update(['status' => 'cancelled']);
        }
    }

    // Synchroniser maintenance manuellement
    public function syncMaintenance(Request $request, Resource $resource)
    {
        if ($resource->status == 'available') {
            $updatedCount = $resource->maintenances()
                ->whereIn('status', ['in_progress', 'scheduled'])
                ->update([
                    'status' => 'completed',
                    'end_date' => now()
                ]);

            return redirect()->back()
                ->with('success', $updatedCount . ' maintenance(s) synchronisée(s) avec succès.');
        }

        return redirect()->back()
            ->with('info', 'La ressource n\'est pas en maintenance.');
    }
}

