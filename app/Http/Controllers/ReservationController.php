<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Resource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Mail\ReservationStatusChanged;

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
        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'quantity' => 'required|integer|min:1',
            'date_start' => 'required|date_format:Y-m-d\TH:i|after:now',
            'date_end' => 'required|date_format:Y-m-d\TH:i|after:date_start',
            'justification' => 'required|string|min:10|max:500'
        ]);

        // Vérifier si la ressource est en maintenance pendant la période de réservation
        $maintenanceConflict = \App\Models\Maintenance::where('resource_id', $validated['resource_id'])
            ->where('status', '!=', 'completed')
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [
                    $validated['date_start'],
                    $validated['date_end']
                ])->orWhereBetween('end_date', [
                    $validated['date_start'],
                    $validated['date_end']
                ])->orWhere(function ($q) use ($validated) {
                    $q->where('start_date', '<=', $validated['date_start'])
                        ->where('end_date', '>=', $validated['date_end']);
                });
            })->exists();
        if ($maintenanceConflict) {
            throw ValidationException::withMessages(['date_start' => 'Cette ressource est en maintenance pendant cette période.']);
        }

        // Vérifier les conflits de dates
        $conflictCount = Reservation::where('resource_id', $validated['resource_id'])
            ->whereIn('status', ['pending', 'approved', 'active'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('date_start', [
                    $validated['date_start'],
                    $validated['date_end']
                ])->orWhereBetween('date_end', [
                    $validated['date_start'],
                    $validated['date_end']
                ])->orWhere(function ($q) use ($validated) {
                    $q->where('date_start', '<=', $validated['date_start'])
                      ->where('date_end', '>=', $validated['date_end']);
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
            'resource_id' => $validated['resource_id'],
            'quantity' => $validated['quantity'],
            'date_start' => $validated['date_start'],
            'date_end' => $validated['date_end'],
            'justification' => $validated['justification'],
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

        // Vérifier si la ressource est en maintenance pendant la période de réservation
        $maintenanceConflict = \App\Models\Maintenance::where('resource_id', $reservation->resource_id)
            ->where('status', '!=', 'completed')
            ->where(function ($query) use ($reservation) {
                $query->whereBetween('start_date', [
                    $reservation->date_start,
                    $reservation->date_end
                ])->orWhereBetween('end_date', [
                    $reservation->date_start,
                    $reservation->date_end
                ])->orWhere(function ($q) use ($reservation) {
                    $q->where('start_date', '<=', $reservation->date_start)
                        ->where('end_date', '>=', $reservation->date_end);
                });
            })->exists();
        if ($maintenanceConflict) {
            return redirect()->back()->with('error', 'Impossible d\'approuver : la ressource est en maintenance pendant cette période.');
        }

        $reservation->update(['status' => 'approved']);

        // Charger la relation user et resource pour le Mailable
        $reservation->load('user', 'resource');
        // Notifier l'utilisateur par email
        Mail::to($reservation->user->email)->send(new ReservationStatusChanged($reservation));

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

        // Charger la relation user et resource pour le Mailable
        $reservation->load('user', 'resource');
        // Notifier l'utilisateur par email
        Mail::to($reservation->user->email)->send(new ReservationStatusChanged($reservation));

        // Notifier l'utilisateur (in-app)
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
     * Obtenir les événements pour le calendrier (JSON)
     */
    public function getEvents()
    {
        $reservations = Reservation::where('status', '!=', 'rejected')
            ->get();
            
        $events = $reservations->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'title' => $reservation->resource->name . ' (' . $reservation->user->name . ')',
                'start' => $reservation->date_start,
                'end' => $reservation->date_end,
                'url' => route('reservations.show', $reservation->id),
                'className' => 'status-' . $reservation->status
            ];
        });

        return response()->json($events);
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
