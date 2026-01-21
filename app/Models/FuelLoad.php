<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelLoad extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'user_id',
        'vehicle_request_id',
        'date',
        'mileage',
        'liters',
        'price_per_liter',
        'total_cost',
        'invoice_number',
        'receipt_photo_path',
        'efficiency_km_l', // We can store this or calculate it dynamically. Storing is often easier for reporting.
    ];

    protected $casts = [
        'date' => 'datetime',
        'mileage' => 'integer',
        'liters' => 'decimal:2',
        'price_per_liter' => 'integer',
        'total_cost' => 'integer',
        'efficiency_km_l' => 'decimal:2',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicleRequest()
    {
        return $this->belongsTo(VehicleRequest::class);
    }

    // Optional: Dynamic efficiency calculation logic
    // This could also be logic in the Observer or Controller to calculate upon creation.
}
