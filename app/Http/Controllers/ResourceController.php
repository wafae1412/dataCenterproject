<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    /**
     * Afficher la liste des ressources
     */
    public function index()
    {
        $resources = Resource::with('category')->orderBy('created_at', 'desc')->get();
        $categories = Category::all();

        return view('resources.index', compact('resources', 'categories'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $categories = Category::all();
        return view('resources.create', compact('categories'));
    }

    /**
     * Enregistrer une nouvelle ressource
     */
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

    /**
     * Afficher les détails d'une ressource
     */
    public function show(Resource $resource)
    {
        $resource->load(['category', 'reservations.user', 'maintenances']);
        return view('resources.show', compact('resource'));
    }

    /**
     * Afficher le formulaire d'édition
     */
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
