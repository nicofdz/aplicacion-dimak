<?php

namespace App\Http\Controllers;

use App\Models\VehicleReturn;
use Illuminate\Http\Request;

class VehicleReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $returns = VehicleReturn::with([
            'request.user',
            'request.vehicle' => function ($query) {
                $query->withTrashed();
            }
        ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

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
