<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class AccountRequestController extends Controller
{
    /**
     * Afficher le formulaire de demande d'ouverture de compte
     */
    public function create()
    {
        return view('account-request.create');
    }

    /**
     * Soumettre la demande d'ouverture de compte
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'institution' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'justification' => 'required|string|min:50|max:1000',
        ], [
            'name.required' => 'Le nom est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.unique' => 'Cet email est déjà utilisé',
            'institution.required' => 'L\'institution est obligatoire',
            'department.required' => 'Le département est obligatoire',
            'justification.required' => 'La justification est obligatoire (minimum 50 caractères)',
        ]);

        // Créer une notification pour les admins
        $admin_users = \App\Models\User::whereHas('role', function($q) {
            $q->where('name', 'Admin');
        })->get();

        foreach ($admin_users as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'account_request',
                'message' => "Nouvelle demande d'ouverture de compte de {$validated['name']} ({$validated['email']})",
                'is_read' => false,
            ]);
        }

        return redirect()->back()->with('success', 'Votre demande d\'ouverture de compte a été envoyée aux administrateurs. Vous recevrez une réponse dans les 48 heures.');
    }
}
