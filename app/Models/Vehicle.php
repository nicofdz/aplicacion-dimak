<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\VehicleMaintenanceState;
use App\Models\MaintenanceRequest;
use App\Models\VehicleRequest;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'plate',
        'serial_number',
        'brand',
        'model',
        'year',
        'mileage',
        'status',
        'image_path',
    ];

    /**
     * Obtiene los atributos que deben ser convertidos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'string',
        ];
    }

    /**
     * Obtiene las reservas asociadas al vehículo.
     */
    public function reservations()
    {
        return $this->hasMany(VehicleRequest::class);
    }

    public function documents()
    {
        return $this->hasMany(VehicleDocument::class);
    }

    /**
     * Verifica si el vehículo está disponible en un rango de fechas.
     */
    public function isAvailable($startDate, $endDate)
    {
        return !$this->reservations()
            ->where('status', 'approved')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<', $startDate)
                            ->where('end_date', '>', $endDate);
                    });
            })
            ->exists();
    }
    /**
     * Obtiene el estado para mostrar (incluyendo reservas activas).
     */
    /**
     * Obtiene el estado para mostrar (incluyendo reservas activas).
     */
    /**
     * Obtiene el estado para mostrar (incluyendo reservas activas y documentos).
     */
    public function getDisplayStatusAttribute()
    {
        // 1. Prioridad Máxima: Documentación Vencida
        if ($this->hasExpiredDocuments()) {
            return 'expired_documents';
        }

        // 2. Estado manual (falla mecánica, mantención, etc.)
        if ($this->status !== 'available') {
            return $this->status;
        }

        // 3. Reserva Activa (ocupado)
        $activeReservation = $this->getActiveReservationAttribute();

        if ($activeReservation) {
            return 'occupied';
        }

        return 'available';
    }

    /**
     * Verifica si tiene documentos vencidos.
     */
    public function hasExpiredDocuments()
    {
        return $this->documents()->where('expires_at', '<', now()->startOfDay())->exists();
    }

    /**
     * Obtiene la reserva activa actual (si existe).
     */
    public function getActiveReservationAttribute()
    {
        return $this->reservations()
            ->where('status', 'approved')
            ->where('start_date', '<=', now()->endOfDay()) // Por si la reserva empieza hoy más tarde
            ->where('end_date', '>=', now()->startOfDay()) // Incluir todo el día de término
            ->with('user')
            ->orderBy('start_date', 'asc') // Tomar la más cercana
            ->first();
    }


    public function currentMaintenanceState()
    {
        return $this->hasOne(VehicleMaintenanceState::class);
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }
}
