<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetMaintenance extends Model
{
    protected $fillable = [
        'activo_id',
        'tipo',
        'descripcion',
        'fecha',
        'costo',
        'evidencia_path',
        'detalles_solucion',
        'fecha_termino',
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_termino' => 'date',
    ];

    /**
     * Relación con activo
     */
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'activo_id');
    }
}
