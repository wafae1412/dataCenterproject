 <?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

/*
|--------------------------------------------------------------------------
| Routes Authentifiées (Tous les utilisateurs)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Réservations
    Route::resource('reservations', ReservationController::class);
    Route::post('/reservations/{id}/approve', [ReservationController::class, 'approve'])
        ->name('reservations.approve');
    Route::post('/reservations/{id}/reject', [ReservationController::class, 'reject'])
        ->name('reservations.reject');

    // Ressources (lecture فقط)
    Route::resource('resources', ResourceController::class);

    // Catégories

    Route::resource('categories', CategoryController::class);

    Route::resource('categories', CategoryController::class)
        ->only(['index', 'show']);


    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');
});

/*
|--------------------------------------------------------------------------
| Routes Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin'])->group(function () {

    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/admin/users', [UserController::class, 'index'])
        ->name('admin.users');

    Route::get('/admin/users/create', [UserController::class, 'create'])
        ->name('admin.users.create');

    Route::post('/admin/users', [UserController::class, 'store'])
        ->name('admin.users.store');

    Route::post('/admin/users/{id}/role', [UserController::class, 'updateRole'])
        ->name('admin.users.updateRole');

    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])
        ->name('admin.users.destroy');
});

/*
|--------------------------------------------------------------------------
| Routes Responsable
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Responsable'])->group(function () {

    Route::get('/responsable/dashboard', [DashboardController::class, 'index'])
        ->name('responsable.dashboard');

    // 🔧 Maintenances
    Route::get('/maintenances', [MaintenanceController::class, 'index'])
        ->name('maintenances.index');

    Route::get('/maintenance/{resource}', [MaintenanceController::class, 'create'])
        ->name('maintenance.create');

    Route::post('/maintenance', [MaintenanceController::class, 'store'])
        ->name('maintenance.store');

    // 📦 Ressources (Responsable)
    Route::get('/responsable/resources', [ResourceController::class, 'index'])
        ->name('responsable.resources');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home');
