<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;            
use App\Models\MaintenanceRequest;  
use App\Models\VehicleRequest;      
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    /**
     * Muestra un listado del recurso.
     */
    public function index(Request $request)
    {
        $vehicles = \App\Models\Vehicle::all();
        
        // Estados
        $countDisponible = Vehicle::where('status', 'available')->count();
        $countTaller = Vehicle::where('status', 'workshop')->count();
        $countMantenimiento = Vehicle::where('status', 'maintenance')->count();
        $countAsignado = Vehicle::where('status', 'occupied')->count();

        // Solicitudes de mantenimiento
        $pendingRequests = MaintenanceRequest::with('vehicle')
            ->where('status', 'pending')
            ->latest()
            ->get();

        
        $pendingReservations = VehicleRequest::with(['vehicle', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        // 
        $data = compact(
            'vehicles', 
            'pendingRequests', 
            'pendingReservations',
            'countDisponible', 
            'countAsignado', 
            'countMantenimiento', 
            'countTaller'
        );

        // Lógica de separación de vistas
        if ($request->routeIs('dashboard')) {
            return view('dashboard', $data);
        }

        return view('vehicles.index', $data);
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        return view('vehicles.create');
    }

    /**
     * Almacena un recurso recién creado en el almacenamiento.
     */
    public function store(Request $request)
    {
        $request->validate([
            'plate' => 'required|unique:vehicles|max:255',
            'brand' => 'required|max:255',
            'model' => 'required|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'mileage' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048', // Máx 2MB
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('vehicles', 'public');
            $data['image_path'] = $path;
        }

        \App\Models\Vehicle::create($data);

        return redirect()->route('vehicles.index')->with('success', 'Vehículo creado exitosamente.');
    }

    /**
     * Muestra el recurso especificado.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Muestra el formulario para editar el recurso especificado.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     */
    public function update(Request $request, \App\Models\Vehicle $vehicle)
    {
        $request->validate([
            'plate' => 'required|max:255|unique:vehicles,plate,' . $vehicle->id,
            'brand' => 'required|max:255',
            'model' => 'required|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'mileage' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Eliminar imagen antigua si existe
            if ($vehicle->image_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($vehicle->image_path);
            }
            $path = $request->file('image')->store('vehicles', 'public');
            $data['image_path'] = $path;
        }

        $vehicle->update($data);

        return redirect()->route('vehicles.index')->with('success', 'Vehículo actualizado correctamente.');
    }

    /**
     * Elimina el recurso especificado del almacenamiento.
     */
    public function destroy(\App\Models\Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Vehículo enviado a la papelera.');
    }

    public function trash()
    {
        $vehicles = \App\Models\Vehicle::onlyTrashed()->get();
        return view('vehicles.trash', compact('vehicles'));
    }

    public function restore($id)
    {
        $vehicle = \App\Models\Vehicle::withTrashed()->findOrFail($id);
        $vehicle->restore();
        return redirect()->route('vehicles.trash')->with('success', 'Vehículo restaurado exitosamente.');
    }

    public function forceDelete($id)
    {
        $vehicle = \App\Models\Vehicle::withTrashed()->findOrFail($id);
        if ($vehicle->image_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($vehicle->image_path);
        }
        $vehicle->forceDelete();
        return redirect()->route('vehicles.trash')->with('success', 'Vehículo eliminado permanentemente.');
    }
}
