<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Resource;

class GuestDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guestName = $user->name;

        // Statistics
        $reservationCount = Reservation::where('user_id', $user->id)->count();
        
        $activeReservationCount = Reservation::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();
            
        $pendingRequestCount = Reservation::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
            
        $availableResourceCount = Resource::where('status', 'available')->count();

        // Recent Reservations
        $recentReservations = Reservation::with('resource.category')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Available Resources (Grid)
        $availableResources = Resource::with('category')
            ->where('status', 'available')
            ->take(6)
            ->get();

        return view('guest-dashboard', compact(
            'guestName',
            'reservationCount',
            'activeReservationCount',
            'pendingRequestCount',
            'availableResourceCount',
            'recentReservations',
            'availableResources'
        ));
    }
}
