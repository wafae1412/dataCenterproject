<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Auth;


Route::get('/', [LandingController::class, 'index'])->name('landing');


Auth::routes();

// Routes authentifiées
Route::middleware(['auth'])->group(function () {
    // Dashboard (différent selon le rôle)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Réservations
    Route::resource('reservations', ReservationController::class);
    Route::post('/reservations/{id}/approve', [ReservationController::class, 'approve'])->name('reservations.approve');
    Route::post('/reservations/{id}/reject', [ReservationController::class, 'reject'])->name('reservations.reject');
    Route::get('/reservations/events', [ReservationController::class, 'getEvents'])->name('reservations.events'); // New route for FullCalendar

    // Ressources
    Route::resource('resources', ResourceController::class);
    Route::put('/resources/{resource}/status', [ResourceController::class, 'changeStatus'])->name('resources.changeStatus');
    Route::post('/resources/{resource}/sync-maintenance', [ResourceController::class, 'syncMaintenance'])->name('resources.syncMaintenance');

    // Catégories
    Route::resource('categories', CategoryController::class);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // Maintenances (Route Ressource pour CRUD complet)
    Route::resource('maintenances', MaintenanceController::class);

    // Profil & Paramètres
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
    Route::put('/settings/password', [ProfileController::class, 'updatePassword'])->name('settings.password');
});

// Routes Guest
Route::middleware(['auth', 'guestUser'])->group(function () {
    Route::get('/guest/dashboard', [\App\Http\Controllers\GuestDashboardController::class, 'index'])->name('guest.dashboard');
});

// Routes Admin
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::post('/admin/users/{id}/role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});

// Routes Responsable
Route::middleware(['auth', 'role:Responsable'])->group(function () {
    Route::get('/responsable/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('responsable.dashboard');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
