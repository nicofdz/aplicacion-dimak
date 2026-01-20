<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConductorController;
// Página de inicio
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Panel de control (requiere autenticación y verificación)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'force.password.change'])->name('dashboard');

// Grupo de rutas para el perfil de usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de Vehículos
    // Rutas de Vehículos
    Route::get('papelera/vehiculos', [\App\Http\Controllers\VehicleController::class, 'trash'])->name('vehicles.trash');
    Route::put('papelera/vehiculos/{id}/restore', [\App\Http\Controllers\VehicleController::class, 'restore'])->name('vehicles.restore');
    Route::delete('papelera/vehiculos/{id}/force-delete', [\App\Http\Controllers\VehicleController::class, 'forceDelete'])->name('vehicles.force-delete');

    // Recurso con nombres personalizados para mantener compatibilidad
    Route::resource('vehiculos', \App\Http\Controllers\VehicleController::class)
        ->names([
            'index' => 'vehicles.index',
            'create' => 'vehicles.create',
            'store' => 'vehicles.store',
            'show' => 'vehicles.show',
            'edit' => 'vehicles.edit',
            'update' => 'vehicles.update',
            'destroy' => 'vehicles.destroy',
        ])
        ->parameters(['vehiculos' => 'vehicle'])
        ->except(['show']);

    //Rutas de conductores
    Route::get('/conductores/trash', [ConductorController::class, 'trash'])->name('conductores.trash');
    Route::post('/conductores/{id}/restore', [ConductorController::class, 'restore'])->name('conductores.restore');
    Route::delete('/conductores/{id}/force-delete', [ConductorController::class, 'forceDelete'])->name('conductores.force-delete');

    Route::get('/conductores', [ConductorController::class, 'index'])->name('conductores.index');
    Route::get('/conductores/nuevo', [ConductorController::class, 'create'])->name('conductores.create');
    Route::post('/conductores', [ConductorController::class, 'store'])->name('conductores.store');
    Route::get('/conductores/{conductor}/edit', [ConductorController::class, 'edit'])->name('conductores.edit');
    Route::put('/conductores/{conductor}', [ConductorController::class, 'update'])->name('conductores.update');
    Route::delete('/conductores/{conductor}', [ConductorController::class, 'destroy'])->name('conductores.destroy');

    // Rutas de Mantenimiento
    Route::post('vehiculos/{vehicle}/maintenance/state', [\App\Http\Controllers\MaintenanceController::class, 'updateState'])->name('vehicles.maintenance.state');
    Route::post('vehiculos/{vehicle}/maintenance/request', [\App\Http\Controllers\MaintenanceController::class, 'storeRequest'])->name('vehicles.maintenance.request');
    Route::post('vehiculos/{vehicle}/maintenance/complete', [\App\Http\Controllers\MaintenanceController::class, 'complete'])->name('vehicles.maintenance.complete');
    Route::post('maintenance/requests/{id}/accept', [\App\Http\Controllers\MaintenanceController::class, 'acceptRequest'])->name('maintenance.requests.accept');

    // Rutas de Solicitudes de Vehículos (Reservas)
    Route::get('/solicitar-vehiculo', [\App\Http\Controllers\VehicleRequestController::class, 'create'])->name('requests.create');
    Route::post('/solicitar-vehiculo', [\App\Http\Controllers\VehicleRequestController::class, 'store'])->name('requests.store');
    Route::post('/requests/{id}/approve', [\App\Http\Controllers\VehicleRequestController::class, 'approve'])->name('requests.approve');
    Route::post('/requests/{id}/reject', [\App\Http\Controllers\VehicleRequestController::class, 'reject'])->name('requests.reject');
    Route::get('/mis-reservas', [\App\Http\Controllers\VehicleRequestController::class, 'index'])->name('requests.index');
    Route::post('/requests/{id}/complete', [\App\Http\Controllers\VehicleRequestController::class, 'complete'])->name('requests.complete');


});

// Incluir rutas de autenticación
// Rutas de cambio de contraseña forzado
Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [App\Http\Controllers\ForceChangePasswordController::class, 'show'])
        ->name('password.change.notice');
    Route::post('/change-password', [App\Http\Controllers\ForceChangePasswordController::class, 'update'])
        ->name('password.change.update');
});

// Rutas de gestión de usuarios
Route::middleware(['auth', 'force.password.change'])->group(function () {
    Route::put('users/{id}/restore', [App\Http\Controllers\UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{id}/force-delete', [App\Http\Controllers\UserController::class, 'forceDelete'])->name('users.force-delete');
    Route::resource('users', App\Http\Controllers\UserController::class);
});

require __DIR__ . '/auth.php';
