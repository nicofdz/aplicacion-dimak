<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Worker;

use App\Models\User;
use App\Models\Conductor;

class WorkerController extends Controller
{
    /**
     * Check if RUT exists in Users or Drivers.
     */
    public function checkRut(Request $request)
    {
        $rut = $request->query('rut');

        // Limpieza básica si es necesario, pero la búsqueda idealmente debe coincidir con el formato de BD
        // Asumiremos que el usuario envía y guarda con puntos y guión, o buscamos flexiblemente. 
        // Para simplificar, buscamos exacto primero.

        $user = User::where('rut', $rut)->first();
        if ($user) {
            return response()->json(['exists_in_users' => true]);
        }

        $conductor = Conductor::where('rut', $rut)->first();
        if ($conductor) {
            return response()->json([
                'exists_in_conductores' => true,
                'data' => [
                    'nombre' => $conductor->nombre,
                    'cargo' => $conductor->cargo,
                    'departamento' => $conductor->departamento
                ]
            ]);
        }

        return response()->json(['exists' => false]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workers = Worker::orderBy('nombre')->paginate(10);
        return view('workers.index', compact('workers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'rut' => 'required|string|max:20|unique:workers,rut',
            'departamento' => 'nullable|string|max:255',
            'cargo' => 'nullable|string|max:255',
        ]);

        Worker::create($request->all());

        return redirect()->route('workers.index')->with('success', 'Trabajador creado exitosamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Worker $worker)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'rut' => 'required|string|max:20|unique:workers,rut,' . $worker->id,
            'departamento' => 'nullable|string|max:255',
            'cargo' => 'nullable|string|max:255',
        ]);

        $worker->update($request->all());

        return redirect()->route('workers.index')->with('success', 'Trabajador actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Worker $worker)
    {
        $worker->delete();
        return redirect()->route('workers.index')->with('success', 'Trabajador eliminado exitosamente. Puede restaurarlo desde la papelera.');
    }

    /**
     * Display a listing of trashed resources.
     */
    public function trash()
    {
        $workers = Worker::onlyTrashed()->orderBy('nombre')->paginate(10);
        return view('workers.trash', compact('workers'));
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $worker = Worker::onlyTrashed()->findOrFail($id);
        $worker->restore();
        return redirect()->route('workers.trash')->with('success', 'Trabajador restaurado exitosamente.');
    }

    /**
     * Remove the specified resource permanently from storage.
     */
    public function forceDelete($id)
    {
        $worker = Worker::onlyTrashed()->findOrFail($id);
        $worker->forceDelete();
        return redirect()->route('workers.trash')->with('success', 'Trabajador eliminado permanentemente.');
    }
}
