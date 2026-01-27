<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class IncidentController extends Controller
{
    /**
     * Afficher le formulaire de signalement d'incident
     */
    public function create()
    {
        return view('incident.create');
    }

    /**
     * Soumettre le signalement d'incident
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50|max:2000',
            'resource_id' => 'nullable|exists:resources,id',
            'priority' => 'required|in:low,medium,high,critical',
        ], [
            'title.required' => 'Le titre est obligatoire',
            'description.required' => 'La description est obligatoire (minimum 50 caractères)',
            'priority.required' => 'Le niveau de priorité est obligatoire',
        ]);

        // Créer une notification pour les admins et les responsables
        $admins = \App\Models\User::whereHas('role', function($q) {
            $q->whereIn('name', ['Admin', 'Responsable']);
        })->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'incident',
                'message' => "Incident signalé par " . auth()->user()->name . ": {$validated['title']} (Priorité: {$validated['priority']})",
                'is_read' => false,
            ]);
        }

        return redirect()->back()->with('success', 'Votre incident a été signalé. Nos équipes techniques en seront informées et vous contacteront rapidement.');
    }
}
