<?php

namespace App\Http\Controllers;

use App\Models\FuelLoad;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FuelLoadController extends Controller
{
    /**
     * Muestra el historial de cargas de combustible.
     */
    public function index(Request $request)
    {
        $query = FuelLoad::with(['vehicle', 'user', 'vehicleRequest']);

        if ($request->has('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        $fuelLoads = $query->orderBy('date', 'desc')->paginate(15);
        $vehicle = $request->has('vehicle_id') ? Vehicle::find($request->vehicle_id) : null;

        return view('fuel-loads.index', compact('fuelLoads', 'vehicle'));
    }

    /**
     * Almacena una nueva carga de combustible.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'vehicle_request_id' => 'nullable|exists:vehicle_requests,id',
            'mileage' => 'required|integer|min:0',
            'liters' => 'required|numeric|min:0.1',
            'price_per_liter' => 'required|integer|min:0',
            'date' => 'required|date|before_or_equal:now',
            'receipt_photo' => 'nullable|image|max:10240', // 10MB
            'invoice_number' => 'nullable|string|max:255',
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        if ($request->mileage < $vehicle->mileage) {
            return back()->withErrors(['mileage' => 'El kilometraje no puede ser menor al actual del vehículo (' . $vehicle->mileage . ' km).'])->withInput();
        }

        // Calcular costo total
        $totalCost = $request->liters * $request->price_per_liter;

        // Manejo de Foto
        $photoPath = null;
        if ($request->hasFile('receipt_photo')) {
            $filename = 'fuel_' . $vehicle->id . '_' . time() . '.' . $request->file('receipt_photo')->getClientOriginalExtension();
            $photoPath = $request->file('receipt_photo')->storeAs('fuel_receipts', $filename, 'public');
        }

        // Calcular Eficiencia
        // Buscar la última carga ANTERIOR a esta (por fecha o created_at) para comparar kilometraje
        $lastLoad = FuelLoad::where('vehicle_id', $vehicle->id)
            ->where('date', '<', $request->date) // Asumiendo carga cronológica
            ->orderBy('date', 'desc')
            ->first();

        $efficiency = null;
        if ($lastLoad) {
            $distance = $request->mileage - $lastLoad->mileage;
            if ($distance > 0 && $request->liters > 0) {
                // Kilómetros recorridos desde la última carga / Litros cargados AHORA
                // Nota: La eficiencia exacta es "Km recorridos con el tanque anterior / Litros para llenar ahora".
                // Asumiendo tanque lleno es la mejor métrica, pero haremos un cálculo aproximado simple.
                $efficiency = $distance / $request->liters;
            }
        }

        FuelLoad::create([
            'vehicle_id' => $vehicle->id,
            'user_id' => Auth::id(),
            'vehicle_request_id' => $request->vehicle_request_id,
            'date' => $request->date,
            'mileage' => $request->mileage,
            'liters' => $request->liters,
            'price_per_liter' => $request->price_per_liter,
            'total_cost' => $totalCost,
            'invoice_number' => $request->invoice_number,
            'receipt_photo_path' => $photoPath,
            'efficiency_km_l' => $efficiency,
        ]);

        // Actualizar kilometraje del vehículo si es mayor
        if ($request->mileage > $vehicle->mileage) {
            $vehicle->update(['mileage' => $request->mileage]);
        }

        return back()->with('success', 'Carga de combustible registrada correctamente.');
    }
}
