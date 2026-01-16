<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
