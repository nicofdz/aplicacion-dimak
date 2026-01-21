<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'start_date',
        'end_date',
        'status',
        'return_mileage',
        'destination_type',
        'conductor_id',
    ];

    /**
     * Obtiene conductor asociado (si aplica).
     */
    public function conductor()
    {
        return $this->belongsTo(Conductor::class);
    }

    /**
     * Obtiene los atributos que deben ser convertidos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    /**
     * Obtiene el usuario que hizo la solicitud.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene el vehículo solicitado.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
