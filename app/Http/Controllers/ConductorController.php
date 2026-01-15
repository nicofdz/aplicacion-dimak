<?php

namespace App\Http\Controllers;

use App\Models\Conductor; 
use Illuminate\Http\Request;

class ConductorController extends Controller
{
    public function index()
    {
        //todos los conductores de la base de datos
        $conductores = Conductor::all();
        
        
        return view('conductores.index', compact('conductores'));
    }

    public function create()
    {
        return view('conductores.create');
    }
}