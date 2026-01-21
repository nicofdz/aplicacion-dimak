<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleRequest;
use App\Models\VehicleReturn;
use App\Models\VehicleMaintenanceState;
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
        $user = Auth::user();

        // 1. Verificar si tiene licencia registrada
        if (!$user->license_expires_at) {
            return redirect()->route('profile.edit')
                ->with('error', 'Para solicitar un vehículo, primero debe registrar su Licencia de Conducir en su perfil.');
        }

        // 2. Verificar si está vencida
        if ($user->license_expires_at < now()->startOfDay()) {
            return redirect()->route('profile.edit')
                ->with('error', 'Su Licencia de Conducir está vencida. Por favor actualice el documento para continuar.');
        }

        $vehicles = Vehicle::all()->filter(function ($vehicle) {
            return $vehicle->display_status === 'available';
        });
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
            'destination_type' => 'required|in:local,outside',
        ]);

        $user = Auth::user();
        if (!$user->license_expires_at || $user->license_expires_at < now()->startOfDay()) {
            return redirect()->route('profile.edit')
                ->with('error', 'Su licencia de conducir no es válida o no está registrada.');
        }

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
            'destination_type' => $request->destination_type,
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
     * Finaliza una reserva (Devolución del vehículo) con checklist detallado.
     */
    public function complete(Request $request, $id)
    {
        $vehicleRequest = VehicleRequest::with('vehicle')->where('user_id', Auth::id())->findOrFail($id);

        // Limpiar formato de kilometraje (eliminar puntos)
        if ($request->has('return_mileage')) {
            $request->merge([
                'return_mileage' => (int) str_replace('.', '', $request->return_mileage)
            ]);
        }

        $request->validate([
            'return_mileage' => 'required|integer|min:' . $vehicleRequest->vehicle->mileage,
            'fuel_level' => 'required|in:1/4,1/2,3/4,full',
            'tire_status_front' => 'required|in:good,fair,poor',
            'tire_status_rear' => 'required|in:good,fair,poor',
            'cleanliness' => 'required|in:clean,dirty,very_dirty',
            'body_damage_reported' => 'nullable|boolean',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|max:10240', // Max 10MB per photo
            'comments' => 'nullable|string|max:1000',
        ]);

        // Procesar fotos si existen
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                // Generar nombre unico: return_{reqId}_{timestamp}_{uniqid}.jpg
                $filename = 'return_' . $vehicleRequest->id . '_' . time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $path = $photo->storeAs('returns', $filename, 'public');
                $photoPaths[] = $path;
            }
        }

        // Crear registro de devolución
        VehicleReturn::create([
            'vehicle_request_id' => $vehicleRequest->id,
            'return_mileage' => $request->return_mileage,
            'fuel_level' => $request->fuel_level,
            'tire_status_front' => $request->tire_status_front,
            'tire_status_rear' => $request->tire_status_rear,
            'cleanliness' => $request->cleanliness,
            'body_damage_reported' => $request->has('body_damage_reported'),
            'comments' => $request->comments,
            'photos_paths' => $photoPaths, // Casted to array in model
        ]);

        // Actualizar vehículo
        $vehicleRequest->vehicle->update([
            'mileage' => $request->return_mileage,
            'status' => 'available'
        ]);

        // Actualizar estado de mantenimiento del vehículo (Neumáticos)
        // Buscamos o creamos el estado de mantenimiento
        $maintenanceState = VehicleMaintenanceState::firstOrCreate(
            ['vehicle_id' => $vehicleRequest->vehicle_id]
        );

        $maintenanceState->update([
            'tire_status_front' => $request->tire_status_front,
            'tire_status_rear' => $request->tire_status_rear,
        ]);

        // Finalizar solicitud
        $vehicleRequest->update([
            'status' => 'completed',
            'return_mileage' => $request->return_mileage
        ]);

        return back()->with('success', 'Devolución registrada correctamente. Historial actualizado.');
    }
}
