<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleMaintenanceState extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'last_oil_change_km',
        'next_oil_change_km',
        'tire_status_front',
        'tire_status_rear',
        'last_service_date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
