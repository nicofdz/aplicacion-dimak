<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_request_id',
        'return_mileage',
        'fuel_level',
        'tire_status_front',
        'tire_status_rear',
        'cleanliness',
        'body_damage_reported',
        'comments',
        'photos_paths',
    ];

    protected $casts = [
        'body_damage_reported' => 'boolean',
        'photos_paths' => 'array',
    ];

    public function request()
    {
        return $this->belongsTo(VehicleRequest::class, 'vehicle_request_id');
    }
}
