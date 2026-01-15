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

