<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetAssignment extends Model
{
    protected $fillable = [
        'activo_id',
        'usuario_id',
        'worker_id',
        'trabajador_nombre',
        'trabajador_rut',
        'trabajador_departamento',
        'trabajador_cargo',
        'fecha_entrega',
        'fecha_estimada_devolucion',
        'fecha_devolucion',
        'estado_entrega',
        'estado_devolucion',
        'observaciones',
        'comentarios_devolucion',
    ];

    protected $casts = [
        'fecha_entrega' => 'datetime',
        'fecha_estimada_devolucion' => 'datetime',
        'fecha_devolucion' => 'datetime',
    ];

    /**
     * Relación con activo
     */
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'activo_id');
    }

    /**
     * Relación con usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relación con trabajador externo
     */
    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }
}
