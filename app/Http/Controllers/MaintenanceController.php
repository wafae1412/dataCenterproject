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

    public function create($resource_id)
    {
        return view('maintenance.create', compact('resource_id'));
    }

    public function store(Request $request)
    {
        Maintenance::create($request->all());

        $resource = Resource::find($request->resource_id);
        $resource->update(['status' => 'maintenance']);

        return redirect()->back();
    }
}

