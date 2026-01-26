<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'internal_code',
        'barcode',
        'name',
        'brand',
        'model',
        'asset_category_id',
        'status', // available, assigned, maintenance, written_off
        'location',
        'acquisition_date',
        'cost',
        'image_path',
        'observations',
    ];

    protected $casts = [
        'acquisition_date' => 'date',
    ];

    // Relaciones
    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }

    public function assignments()
    {
        return $this->hasMany(AssetAssignment::class);
    }

    public function maintenances()
    {
        return $this->hasMany(AssetMaintenance::class);
    }

    // Helpers
    public function getActiveAssignmentAttribute()
    {
        return $this->assignments()
            ->whereNull('returned_at')
            ->orderBy('assigned_at', 'desc')
            ->first();
    }
}
