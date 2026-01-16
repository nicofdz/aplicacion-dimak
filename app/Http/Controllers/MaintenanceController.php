<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function updateState(Request $request, Vehicle $vehicle)
    {
        // Sanitize inputs (remove dots from thousand separators)
        $request->merge([
            'last_oil_change_km' => $request->last_oil_change_km ? str_replace('.', '', $request->last_oil_change_km) : null,
            'next_oil_change_km' => $request->next_oil_change_km ? str_replace('.', '', $request->next_oil_change_km) : null,
        ]);

        $validated = $request->validate([
            'last_oil_change_km' => 'nullable|integer|min:0',
            'next_oil_change_km' => 'nullable|integer|min:0',
            'tire_status_front' => 'required|in:good,fair,poor',
            'tire_status_rear' => 'required|in:good,fair,poor',
            'last_service_date' => 'nullable|date',
        ]);

        $vehicle->currentMaintenanceState()->updateOrCreate(
            ['vehicle_id' => $vehicle->id],
            $validated
        );

        return back()->with('success', 'Estado de mantenimiento actualizado.');
    }

    public function storeRequest(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'type' => 'required|in:oil,tires,mechanics,general',
            'description' => 'required|string|max:1000',
        ]);

        $vehicle->maintenanceRequests()->create([
            'type' => $validated['type'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Solicitud de mantenimiento creada.');
    }

    public function acceptRequest($id)
    {
        $maintenanceRequest = \App\Models\MaintenanceRequest::findOrFail($id);

        // Update request status
        $maintenanceRequest->update(['status' => 'in_progress']);

        // Update vehicle status
        $maintenanceRequest->vehicle->update(['status' => 'maintenance']);

        return back()->with('success', 'Solicitud aceptada. El vehículo ha pasado a mantenimiento.');
    }
}
