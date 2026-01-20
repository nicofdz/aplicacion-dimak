<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function updateState(Request $request, Vehicle $vehicle)
    {
        // Sanear entradas (eliminar puntos de los separadores de miles)
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

        // Actualizar estado de la solicitud
        $maintenanceRequest->update(['status' => 'in_progress']);

        // Actualizar estado del vehículo
        if ($maintenanceRequest->vehicle) {
            $maintenanceRequest->vehicle->update(['status' => 'maintenance']);
        }

        return back()->with('success', 'Solicitud aceptada. El vehículo ha pasado a mantenimiento.');
    }

    public function complete(Request $request, Vehicle $vehicle)
    {
        // Buscar cualquier solicitud en progreso para este vehículo y marcarla como completada
        $vehicle->maintenanceRequests()
            ->where('status', 'in_progress')
            ->update(['status' => 'completed']);

        // Establecer el estado del vehículo nuevamente a disponible
        $vehicle->update(['status' => 'available']);

        return back()->with('success', 'Mantenimiento finalizado. Vehículo disponible nuevamente.');
    }
}
