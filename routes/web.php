<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MaintenanceController;
<<<<<<< HEAD
use App\Http\Controllers\Admin\UserController;
=======
use Illuminate\Support\Facades\Auth;
>>>>>>> origin/maryam-resources

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware(['auth','role:Admin']);
    Route::middleware(['auth', 'role:Admin'])->group(function () {

    Route::get('/admin/users', [UserController::class, 'index'])
        ->name('admin.users');

    Route::post('/admin/users/{id}/role', [UserController::class, 'updateRole'])
        ->name('admin.users.updateRole');
});
    Route::get('/responsable/dashboard', function () {
        return view('responsable.dashboard');
    })->middleware('role:Responsable');
    

});
Route::middleware('auth')->group(function () {
    Route::get('/reservations/create', function () {
        return view('reservations.create');
    })->name('reservations.create');
});
Route::middleware('auth')->group(function () {
    Route::get('/notifications', function () {
        return view('notifications.index');
    })->name('notifications.index');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {

     // Routes pour les ressources (CRUD)
    Route::resource('resources', ResourceController::class);
    Route::put('/resources/{resource}/status', [ResourceController::class, 'changeStatus'])
        ->name('resources.changeStatus');

     // Routes pour les categories
    Route::resource('categories', CategoryController::class)->only(['index', 'show']);

    // Routes pour les notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    // Routes pour la maintenance
    Route::get('/maintenance/{resource}', [MaintenanceController::class, 'create']);
    Route::post('/maintenance', [MaintenanceController::class, 'store']);
<<<<<<< HEAD
    Route::get('/maintenances', [MaintenanceController::class, 'index'])
    ->middleware('auth');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

=======
    Route::get('/maintenances', [MaintenanceController::class, 'index']);
>>>>>>> origin/maryam-resources

});

