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
    Route::get('trash/vehicles', [\App\Http\Controllers\VehicleController::class, 'trash'])->name('vehicles.trash');
    Route::put('trash/vehicles/{id}/restore', [\App\Http\Controllers\VehicleController::class, 'restore'])->name('vehicles.restore');
    Route::delete('trash/vehicles/{id}/force-delete', [\App\Http\Controllers\VehicleController::class, 'forceDelete'])->name('vehicles.force-delete');
    Route::resource('vehicles', \App\Http\Controllers\VehicleController::class)->except(['show']);

    // Rutas de Mantenimiento
    Route::post('vehicles/{vehicle}/maintenance/state', [\App\Http\Controllers\MaintenanceController::class, 'updateState'])->name('vehicles.maintenance.state');
    Route::post('vehicles/{vehicle}/maintenance/request', [\App\Http\Controllers\MaintenanceController::class, 'storeRequest'])->name('vehicles.maintenance.request');
    Route::post('maintenance/requests/{id}/accept', [\App\Http\Controllers\MaintenanceController::class, 'acceptRequest'])->name('maintenance.requests.accept');
});

// Incluir rutas de autenticación
require __DIR__ . '/auth.php';


//ruta para ver todos los conductores
Route::get('/conductores', [ConductorController::class, 'index'])->name('conductores.index');
//ruta para entrar el formulario de conductores
Route::get('/conductores/nuevo', [ConductorController::class, 'create'])->name('conductores.create');
//envia la informacion
Route::post('/conductores', [ConductorController::class, 'store'])->name('conductores.store');
//ruta para ver formulario de edicion
Route::get('/conductores/{conductor}/edit', [ConductorController::class, 'edit'])->name('conductores.edit');
//ruta para guardar cambios
Route::put('/conductores/{conductor}', [ConductorController::class, 'update'])->name('conductores.update');
//ruta para eliminar conductor 
Route::delete('/conductores/{conductor}', [ConductorController::class, 'destroy'])->name('conductores.destroy');

