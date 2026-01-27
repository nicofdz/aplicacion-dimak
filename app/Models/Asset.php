<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'codigo_interno',
        'codigo_barra',
        'nombre',
        'categoria_id',
        'marca',
        'modelo',
        'numero_serie',
        'estado',
        'ubicacion',
        'fecha_adquisicion',
        'valor_referencial',
        'foto_path',
        'observaciones',
    ];

    protected $casts = [
        'fecha_adquisicion' => 'date',
    ];

    /**
     * Boot method para auto-generar códigos
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($asset) {
            // Generar código interno si no existe
            if (empty($asset->codigo_interno)) {
                $asset->codigo_interno = self::generateCodigoInterno();
            }

            // Generar código de barras si no existe
            if (empty($asset->codigo_barra)) {
                $asset->codigo_barra = self::generateCodigoBarra();
            }
        });
    }

    /**
     * Generar código interno único (ACT-0001, ACT-0002, etc.)
     */
    private static function generateCodigoInterno()
    {
        $lastAsset = self::orderBy('id', 'desc')->first();
        $nextNumber = $lastAsset ? ($lastAsset->id + 1) : 1;
        return 'ACT-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generar código de barras único
     */
    private static function generateCodigoBarra()
    {
        do {
            // Formato: ASSET-YYYYMMDD-XXXX
            $codigo = 'ASSET-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        } while (self::where('codigo_barra', $codigo)->exists());

        return $codigo;
    }

    /**
     * Relación con categoría
     */
    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'categoria_id');
    }

    /**
     * Relación con asignaciones
     */
    public function assignments()
    {
        return $this->hasMany(AssetAssignment::class, 'activo_id');
    }

    /**
     * Relación con mantenciones
     */
    public function maintenances()
    {
        return $this->hasMany(AssetMaintenance::class, 'activo_id');
    }

    /**
     * Obtener asignación activa (sin fecha de devolución)
     */
    public function getActiveAssignmentAttribute()
    {
        if ($this->relationLoaded('assignments')) {
            return $this->assignments
                ->where('fecha_devolucion', null)
                ->sortByDesc('fecha_entrega')
                ->first();
        }

        return $this->assignments()
            ->whereNull('fecha_devolucion')
            ->orderBy('fecha_entrega', 'desc')
            ->first();
    }
}
