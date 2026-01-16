<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = \App\Models\Vehicle::all();
        $pendingRequests = \App\Models\MaintenanceRequest::with('vehicle')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('vehicles.index', compact('vehicles', 'pendingRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vehicles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'plate' => 'required|unique:vehicles|max:255',
            'brand' => 'required|max:255',
            'model' => 'required|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'mileage' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048', // 2MB Max
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
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
            // Delete old image if exists
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
     * Remove the specified resource from storage.
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
