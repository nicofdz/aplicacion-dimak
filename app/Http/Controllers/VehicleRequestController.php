<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleRequestController extends Controller
{
    /**
     * Muestra las reservas del usuario actual.
     */
    public function index()
    {
        $requests = VehicleRequest::with('vehicle')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('requests.index', compact('requests'));
    }

    /**
     * Muestra el formulario para solicitar un vehículo.
     */
    public function create()
    {
        $vehicles = Vehicle::where('status', '!=', 'workshop')
            ->where('status', '!=', 'maintenance')
            ->get();
        return view('requests.create', compact('vehicles'));
    }

    /**
     * Almacena una nueva solicitud de reserva.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        if (!$vehicle->isAvailable($request->start_date, $request->end_date)) {
            return back()->withErrors(['vehicle_id' => 'El vehículo no está disponible en las fechas seleccionadas.'])->withInput();
        }

        VehicleRequest::create([
            'user_id' => Auth::id(),
            'vehicle_id' => $vehicle->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'pending',
        ]);

        return redirect()->route('requests.create')->with('success', 'Solicitud enviada correctamente. Esperando aprobación.');
    }

    /**
     * Aprueba una solicitud de reserva (Admin).
     */
    public function approve($id)
    {
        $request = VehicleRequest::findOrFail($id);

        // Verificar conflicto nuevamente por seguridad
        if (!$request->vehicle->isAvailable($request->start_date, $request->end_date)) {
            return back()->with('error', 'No se puede aprobar: Existe conflicto de fechas con otra reserva aprobada.');
        }

        $request->update(['status' => 'approved']);

        $request->vehicle->update(['status' => 'occupied']);
        return back()->with('success', 'Reserva aprobada exitosamente.');
    }

    /**
     * Rechaza una solicitud de reserva (Admin).
     */
    public function reject($id)
    {
        $request = VehicleRequest::findOrFail($id);
        $request->update(['status' => 'rejected']);

        return back()->with('success', 'Reserva rechazada.');
    }

    /**
     * Finaliza una reserva (Devolución del vehículo).
     */
    public function complete(Request $request, $id)
    {
        $vehicleRequest = VehicleRequest::with('vehicle')->where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'return_mileage' => 'required|integer|min:' . $vehicleRequest->vehicle->mileage,
        ]);

        // Actualizar kilometraje del vehículo si es mayor al actual
        if ($request->return_mileage > $vehicleRequest->vehicle->mileage) {
            $vehicleRequest->vehicle->update(['mileage' => $request->return_mileage]);
        }

        $vehicleRequest->vehicle->update([
            'status' => 'available' 
        ]);
        
        $vehicleRequest->update([
            'status' => 'completed',
            'return_mileage' => $request->return_mileage
        ]);

        return back()->with('success', 'Vehículo devuelto correctamente. Kilometraje actualizado.');
    }
}
