<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conductor extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at', 'fecha_licencia'];

    protected $table = 'conductores';

    // definir qué campos se pueden llenar en el formulario
    protected $fillable = [
        'nombre',
        'rut',
        'cargo',
        'departamento',
        'fotografia',
        'fecha_licencia'
    ];

    // hace que la fecha_licencia se comporte como una fecha real para hacer calculos
    protected $casts = [
        'fecha_licencia' => 'date',
    ];
}