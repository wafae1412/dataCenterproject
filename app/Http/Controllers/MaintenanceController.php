<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\Resource;


class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = Maintenance::orderBy('created_at', 'desc')->get();
        return view('maintenances.index', compact('maintenances'));
    }

   public function create(Resource $resource)
{
    // Récupérer la ressource pré-sélectionnée
    $selectedResource = $resource;

    // Récupérer toutes les ressources pour la liste déroulante
    $resources = Resource::all();

    return view('maintenances.create', [
        'resource' => $selectedResource,
        'resources' => $resources
    ]);
}

    public function store(Request $request)
    {
        Maintenance::create($request->all());

        $resource = Resource::find($request->resource_id);
        $resource->update(['status' => 'maintenance']);

        return redirect()->back();
    }
}

