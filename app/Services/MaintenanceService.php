<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Models\User;
use App\Notifications\MaintenanceAlert;
use Carbon\Carbon;

class MaintenanceService
{
    /**
     * Revisa todos los vehículos y notifica a los administradores si es necesario.
     */
    public function checkAndNotify()
    {
        // 1. Obtener vehículos que requieren atención
        $vehicles = Vehicle::with('currentMaintenanceState')->get();

        // 2. Filtrar administradores y supervisores
        $admins = User::whereIn('role', ['admin', 'supervisor'])->get();

        foreach ($vehicles as $vehicle) {
            $status = $this->getMaintenanceStatus($vehicle);

            if ($status['type'] === 'none') {
                continue;
            }

            foreach ($admins as $admin) {
                // 3. Verificar si ya se notificó recientemente
                if ($this->shouldNotify($admin, $vehicle, $status)) {
                    $admin->notify(new MaintenanceAlert(
                        $vehicle,
                        $status['message'],
                        $status['type'],
                        $status['next_oil_change_km']
                    ));
                }
            }
        }
    }

    /**
     * Determina el estado de mantenimiento de un vehículo.
     */
    private function getMaintenanceStatus(Vehicle $vehicle)
    {
        $state = $vehicle->currentMaintenanceState;

        if (!$state || !$state->next_oil_change_km) {
            return ['type' => 'none'];
        }

        $kmParams = $state->next_oil_change_km;
        $currentKm = $vehicle->mileage;

        // Caso 1: Vencido (Rojo)
        if ($currentKm >= $kmParams) {
            $diff = $currentKm - $kmParams;
            return [
                'type' => 'danger',
                'message' => "Mantenimiento VENCIDO por {$diff} km",
                'next_oil_change_km' => $kmParams
            ];
        }

        // Caso 2: Próximo (Amarillo - 500km antes)
        if (($kmParams - $currentKm) <= 500) {
            $diff = $kmParams - $currentKm;
            return [
                'type' => 'warning',
                'message' => "Mantenimiento próximo (faltan {$diff} km)",
                'next_oil_change_km' => $kmParams
            ];
        }

        return ['type' => 'none'];
    }

    /**
     * Decide si se debe enviar una notificación basada en el historial.
     */
    private function shouldNotify($user, $vehicle, $status)
    {
        // Buscar la última notificación (LEÍDA O NO) para este vehículo
        $lastNotification = $user->notifications()
            ->where('type', MaintenanceAlert::class)
            ->where('data->vehicle_id', $vehicle->id)
            ->first();

        // Si no hay notificación previa sin leer, notificar.
        if (!$lastNotification) {
            return true;
        }

        // Si existe una, verificar si han pasado 2 días para reenviar recordatorio
        $createdAt = Carbon::parse($lastNotification->created_at);
        if ($createdAt->diffInDays(now()) >= 2) {
            return true; // Han pasado 2 días, insistir.
        }

        // Si el tipo de alerta cambió (de warning a danger), notificar igual
        // (Aunque técnicamente si cambia, el mensaje es distinto, pero por seguridad)
        if ($lastNotification->data['type'] !== $status['type']) {
            return true;
        }

        return false; // Ya tiene una reciente, no molestar.
    }
}
