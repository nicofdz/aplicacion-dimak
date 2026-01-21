<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

use App\Services\MaintenanceService;

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

        // Check for maintenance alerts immediately
        (new MaintenanceService)->checkAndNotify();

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
        // 1. Sanear entradas
        $request->merge([
            'last_oil_change_km' => $request->last_oil_change_km ? str_replace('.', '', $request->last_oil_change_km) : null,
            'next_oil_change_km' => $request->next_oil_change_km ? str_replace('.', '', $request->next_oil_change_km) : null,
        ]);

        // 2. Validar datos básicos
        $validated = $request->validate([
            'last_oil_change_km' => 'nullable|integer|min:0',
            'next_oil_change_km' => 'required|integer|min:0',
            'tire_status_front' => 'required|in:good,fair,poor',
            'tire_status_rear' => 'required|in:good,fair,poor',
            'last_service_date' => 'nullable|date',
        ]);

        // 3. Validaciones estricas para finalizar
        $errors = [];

        // A) Aceite: El próximo cambio debe ser MAYOR al kilometraje actual (futuro)
        if ($validated['next_oil_change_km'] <= $vehicle->mileage) {
            $errors['next_oil_change_km'] = 'Para finalizar, el próximo cambio de aceite debe ser mayor al kilometraje actual (' . number_format($vehicle->mileage, 0, '', '.') . ' km).';
        }

        // B) Neumáticos: Deben estar en buen estado
        if ($validated['tire_status_front'] !== 'good') {
            $errors['tire_status_front'] = 'Los neumáticos delanteros deben estar en estado "Bueno" para finalizar.';
        }
        if ($validated['tire_status_rear'] !== 'good') {
            $errors['tire_status_rear'] = 'Los neumáticos traseros deben estar en estado "Bueno" para finalizar.';
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput()->with('error', 'No se puede finalizar el mantenimiento. Verifique los requisitos.');
        }

        // 4. Actualizar estado de mantenimiento con los nuevos datos
        $vehicle->currentMaintenanceState()->updateOrCreate(
            ['vehicle_id' => $vehicle->id],
            $validated
        );

        // 5. Completar solicitudes y liberar vehículo
        $vehicle->maintenanceRequests()
            ->where('status', 'in_progress')
            ->update(['status' => 'completed']);

        $vehicle->update(['status' => 'available']);

        // 6. Limpiar notificaciones
        \Illuminate\Support\Facades\DB::table('notifications')
            ->where('data->vehicle_id', $vehicle->id)
            ->delete();

        return back()->with('success', 'Mantenimiento finalizado exitosamente. Vehículo liberado.');
    }
}
