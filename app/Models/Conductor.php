<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    
    protected $table = 'conductores';

    // definir qué campos se pueden llenar en el formulario
    protected $fillable = [
        'nombre',
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