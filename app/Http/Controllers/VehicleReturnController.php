<?php

namespace App\Http\Controllers;

use App\Models\VehicleReturn;
use Illuminate\Http\Request;

class VehicleReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = VehicleReturn::with([
            'request.user',
            'request.vehicle' => function ($q) {
                $q->withTrashed();
            },
            'request.fuelLoads'
        ]);

        // Filtro por búsqueda (usuario o patente)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('request', function ($q) use ($searchTerm) {
                $q->whereHas('user', function ($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'LIKE', "%{$searchTerm}%");
                })->orWhereHas('vehicle', function ($vehicleQuery) use ($searchTerm) {
                    $vehicleQuery->where('plate', 'LIKE', "%{$searchTerm}%");
                });
            });
        }

        // Filtro por rango de fechas (día, mes, año)
        if ($request->filled('filter_type') && $request->filled('filter_value')) {
            $filterType = $request->filter_type;
            $filterValue = $request->filter_value;

            if ($filterType === 'day') {
                $query->whereDate('created_at', $filterValue);
            } elseif ($filterType === 'month') {
                $query->whereYear('created_at', substr($filterValue, 0, 4))
                    ->whereMonth('created_at', substr($filterValue, 5, 2));
            } elseif ($filterType === 'year') {
                $query->whereYear('created_at', $filterValue);
            }
        }

        // Filtro especial por ID de solicitud (para navegación desde historial de uso)
        if ($request->filled('request_id')) {
            $query->where('vehicle_request_id', $request->request_id);
        }

        $returns = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.returns.index', compact('returns'));
    }

    public function trash()
    {
        $returns = VehicleReturn::onlyTrashed()
            ->with([
                'request.user',
                'request.vehicle' => function ($query) {
                    $query->withTrashed();
                }
            ])
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return view('admin.returns.trash', compact('returns'));
    }

    public function destroy($id)
    {
        $return = VehicleReturn::findOrFail($id);
        $return->delete();
        return redirect()->route('admin.returns.index')->with('success', 'Entrega movida a la papelera.');
    }

    public function restore($id)
    {
        $return = VehicleReturn::withTrashed()->findOrFail($id);
        $return->restore();
        return redirect()->route('admin.returns.trash')->with('success', 'Entrega restaurada exitosamente.');
    }

    public function forceDelete($id)
    {
        $return = VehicleReturn::withTrashed()->findOrFail($id);

        // Opcional: Eliminar fotos asociadas si es necesario
        // if ($return->photos_paths) {
        //     foreach ($return->photos_paths as $photo) {
        //         Storage::disk('public')->delete($photo);
        //     }
        // }

        $return->forceDelete();
        return redirect()->route('admin.returns.trash')->with('success', 'Entrega eliminada permanentemente.');
    }
}
