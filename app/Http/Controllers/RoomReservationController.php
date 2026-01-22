<?php

namespace App\Http\Controllers;

use App\Models\MeetingRoom;
use App\Models\RoomReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RoomReservationController extends Controller
{

    public function index()
    {
    
        $rooms = MeetingRoom::where('status', 'active')->get();
        
       
        foreach($rooms as $room) {
            $now = Carbon::now();
            $currentReservation = RoomReservation::where('meeting_room_id', $room->id)
                ->where('status', 'approved') 
                ->where('start_time', '<=', $now)
                ->where('end_time', '>=', $now)
                ->first();
            
            $room->is_occupied = $currentReservation ? true : false;
            $room->current_reservation_end = $currentReservation ? $currentReservation->end_time : null;
        }

        return view('reservations.catalog', compact('rooms'));
    }

    
    public function store(Request $request)
    {
        
        $request->validate([
            'meeting_room_id' => 'required|exists:meeting_rooms,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'purpose' => 'required|string|max:255',
            'attendees' => 'required|integer|min:1', 
            'resources' => 'nullable|string|max:500',
        ]);

        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);

        
        $exists = RoomReservation::where('meeting_room_id', $request->meeting_room_id)
            ->where('status', '!=', 'rejected') 
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start, $end])
                      ->orWhereBetween('end_time', [$start, $end])
                      ->orWhere(function ($q) use ($start, $end) {
                          $q->where('start_time', '<', $start)
                            ->where('end_time', '>', $end);
                      });
            })
            ->exists();

        if ($exists) {
            return back()->withErrors(['error' => '⚠️ Lo sentimos, ya existe una reserva en ese intervalo de horario. Por favor revisa la disponibilidad.']);
        }

      
        RoomReservation::create([
            'user_id' => Auth::id(),
            'meeting_room_id' => $request->meeting_room_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'purpose' => $request->purpose,
            'attendees' => $request->attendees,
            'resources' => $request->resources,
            'status' => 'pending' 
        ]);

        return redirect()->route('reservations.my_reservations')->with('success', 'Solicitud enviada correctamente.');
    }

    // Aprobar Reserva
    public function approve($id)
    {
        $reservation = RoomReservation::findOrFail($id);
        
        $reservation->status = 'approved';
        $reservation->save();

        return redirect()->back()->with('success', 'Reserva aprobada con éxito.');
    }

    // Rechazar Reserva
    public function reject($id)
    {
        $reservation = RoomReservation::findOrFail($id);
        $reservation->status = 'rejected';
        $reservation->save();

        return redirect()->back()->with('success', 'Reserva rechazada.');
    }

    // Mostrar las reservas del usuario logueado
    public function myReservations()
    {
        $reservations = RoomReservation::where('user_id', Auth::id())
            ->with('meetingRoom') 
            ->orderBy('created_at', 'desc') 
            ->get();

        return view('reservations.my_reservations', compact('reservations'));
    }

   
    public function cancel($id)
    {
        $reservation = RoomReservation::findOrFail($id);

        
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para cancelar esta reserva.');
        }

        // Solo permitir cancelar si no ha pasado la fecha (opcional) o si está pendiente/aprobada
        if ($reservation->status === 'cancelled') {
            return redirect()->back()->with('error', 'La reserva ya estaba cancelada.');
        }

        $reservation->status = 'cancelled';
        $reservation->save();

        return redirect()->back()->with('success', 'Reserva cancelada correctamente.');
    }

    public function availability(MeetingRoom $room)
    {
        
        $reservations = $room->reservations()
            ->whereIn('status', ['approved', 'pending'])
            ->where('start_time', '>=', now()->startOfMonth()->subDays(7))
            ->get() 
            ->map(function ($res) {
                return [
                    
                    'day' => (int)$res->start_time->format('d'),
                    'month' => (int)$res->start_time->format('m') - 1, 
                    'year' => (int)$res->start_time->format('Y'),
                    'start_time' => $res->start_time->format('H:i'),
                    'end_time' => $res->end_time->format('H:i'),
                    'status' => $res->status
                ];
            });

        return response()->json($reservations);
    }
}