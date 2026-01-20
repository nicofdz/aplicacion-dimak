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
    public function getDisplayStatusAttribute()
    {
        // Si está en taller o mantenimiento, mostrar eso
        if ($this->status !== 'available' && $this->status !== 'occupied') {
            return $this->status;
        }

        // Verificar si tiene una reserva ACTIVA en este momento
        $activeReservation = $this->reservations()
            ->where('status', 'approved')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if ($activeReservation) {
            return 'occupied';
        }

        return 'available';
    }

    /**
     * Obtiene la reserva activa actual (si existe).
     */
    public function getActiveReservationAttribute()
    {
        return $this->reservations()
            ->where('status', 'approved')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->with('user')
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
