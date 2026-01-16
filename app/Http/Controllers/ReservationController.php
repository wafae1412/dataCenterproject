<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Resource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Afficher la liste des réservations de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            // Admin voit toutes les réservations
            $reservations = Reservation::with(['user', 'resource'])->orderBy('created_at', 'desc')->get();
        } elseif ($user->isResponsable()) {
            // Responsable voit les réservations liées à ses ressources
            $resourceIds = Resource::pluck('id')->toArray();
            $reservations = Reservation::with(['user', 'resource'])
                ->whereIn('resource_id', $resourceIds)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Utilisateur voit ses propres réservations
            $reservations = $user->reservations()
                ->with(['resource', 'resource.category'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $resources = Resource::with('category')->where('status', 'available')->get();
        return view('reservations.create', compact('resources'));
    }

    /**
     * Enregistrer une nouvelle réservation
     */
    public function store(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'date_start' => 'required|date_format:Y-m-d H:i|after:now',
            'date_end' => 'required|date_format:Y-m-d H:i|after:date_start',
            'justification' => 'required|string|min:10|max:500'
        ]);

        // Vérifier les conflits de dates
        $conflictCount = Reservation::where('resource_id', $request->resource_id)
            ->whereIn('status', ['pending', 'approved', 'active'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('date_start', [
                    $request->date_start,
                    $request->date_end
                ])->orWhereBetween('date_end', [
                    $request->date_start,
                    $request->date_end
                ])->orWhere(function ($q) use ($request) {
                    $q->where('date_start', '<=', $request->date_start)
                      ->where('date_end', '>=', $request->date_end);
                });
            })->count();

        if ($conflictCount > 0) {
            return redirect()->back()
                ->with('error', 'Cette ressource n\'est pas disponible pour cette période.')
                ->withInput();
        }

        // Créer la réservation
        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'resource_id' => $request->resource_id,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
            'justification' => $request->justification,
            'status' => 'pending'
        ]);

        // Créer une notification pour les administrateurs
        $this->notifyAdmins(
            'Nouvelle réservation en attente',
            'Une nouvelle réservation de ' . Auth::user()->name . ' est en attente d\'approbation.'
        );

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation créée avec succès. En attente d\'approbation.');
    }

    /**
     * Afficher les détails d'une réservation
     */
    public function show($id)
    {
        $reservation = Reservation::with(['user', 'resource', 'resource.category'])->find($id);

        if (!$reservation) {
            return redirect()->route('reservations.index')->with('error', 'Réservation non trouvée.');
        }

        // Vérifier les permissions
        $user = Auth::user();
        if (!$user->isAdmin() && $reservation->user_id !== $user->id) {
            abort(403);
        }

        return view('reservations.show', compact('reservation'));
    }

    /**
     * Approuver une réservation (Admin/Responsable)
     */
    public function approve($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return redirect()->back()->with('error', 'Réservation non trouvée.');
        }

        $reservation->update(['status' => 'approved']);

        // Notifier l'utilisateur
        Notification::create([
            'user_id' => $reservation->user_id,
            'message' => 'Votre réservation pour ' . $reservation->resource->name . ' a été approuvée.'
        ]);

        return redirect()->back()->with('success', 'Réservation approuvée.');
    }

    /**
     * Rejeter une réservation (Admin/Responsable)
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:5|max:255'
        ]);

        $reservation = Reservation::find($id);

        if (!$reservation) {
            return redirect()->back()->with('error', 'Réservation non trouvée.');
        }

        $reservation->update(['status' => 'rejected']);

        // Notifier l'utilisateur
        Notification::create([
            'user_id' => $reservation->user_id,
            'message' => 'Votre réservation a été rejetée. Raison: ' . $request->rejection_reason
        ]);

        return redirect()->back()->with('success', 'Réservation rejetée.');
    }

    /**
     * Supprimer une réservation (Admin uniquement)
     */
    public function destroy($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return redirect()->back()->with('error', 'Réservation non trouvée.');
        }

        $reservation->delete();

        return redirect()->back()->with('success', 'Réservation supprimée.');
    }

    /**
     * Vérifier les réservations expirées et mettre à jour le statut
     */
    private function updateExpiredReservations()
    {
        $now = Carbon::now();
        Reservation::where('status', 'active')
            ->where('date_end', '<', $now)
            ->update(['status' => 'finished']);
    }

    /**
     * Notifier les administrateurs
     */
    private function notifyAdmins($title, $message)
    {
        $admins = \App\Models\User::whereHas('role', function ($query) {
            $query->where('name', 'Admin');
        })->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'message' => $title . ': ' . $message
            ]);
        }
    }
}
