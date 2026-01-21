<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function read($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);

        // Marcamos como leída para limpiar el "puntito rojo", pero la mostramos igual en la lista
        $notification->markAsRead();

        // Obtener ID del vehículo para redirigir
        $vehicleId = $notification->data['vehicle_id'] ?? null;

        if ($vehicleId) {
            return redirect()->route('vehicles.index')->with('success', 'Redirigiendo a vehículo...');
        }

        return back();
    }

    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notificación eliminada.');
    }
}
