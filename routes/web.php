<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConductorController;
// Página de inicio
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (requiere autenticación y verificación)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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

    // Resource with custom names to preserve existing helper calls
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

    
});

// Incluir rutas de autenticación
require __DIR__ . '/auth.php';




