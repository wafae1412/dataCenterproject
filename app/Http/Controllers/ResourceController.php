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

    /**
     * Mettre à jour une ressource
     */
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

    /**
     * Supprimer une ressource
     */
    public function destroy(Resource $resource)
    {
        // Vérifier s'il y a des réservations actives
        if ($resource->reservations()->where('status', 'active')->exists()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer une ressource avec des réservations actives.');
        }

        $resource->delete();

        return redirect()->route('resources.index')
            ->with('success', 'Ressource supprimée avec succès.');
    }

    /**
     * Changer l'état d'une ressource
     */
    public function changeStatus(Request $request, Resource $resource)
    {
        $request->validate([
            'status' => 'required|in:available,reserved,maintenance,disabled'
        ]);

        $resource->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Statut de la ressource mis à jour.');
    }
}
