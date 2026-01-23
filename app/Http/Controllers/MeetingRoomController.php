<?php

namespace App\Http\Controllers;

use App\Models\MeetingRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 

class MeetingRoomController extends Controller
{
    
    public function index()
    {
        $rooms = MeetingRoom::all();
        $pendingReservations = \App\Models\RoomReservation::where('status', 'pending')
            ->with(['user', 'meetingRoom']) 
            ->orderBy('start_time', 'asc')
            ->get();
        
        return view('rooms.index', compact('rooms', 'pendingReservations'));        
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,maintenance',
            'image' => 'nullable|image|max:2048', 
        ]);

        $data = $request->all();

        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('rooms', 'public');
            $data['image_path'] = $path;
        }

        MeetingRoom::create($data);

        return redirect()->route('rooms.index')->with('success', 'Sala creada correctamente.');
    }

    
    public function edit(MeetingRoom $room)
    {
        return view('rooms.edit', compact('room'));
    }

    
    public function update(Request $request, MeetingRoom $room)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,maintenance',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            
            if ($room->image_path) {
                Storage::disk('public')->delete($room->image_path);
            }
            $data['image_path'] = $request->file('image')->store('rooms', 'public');
        }

        $room->update($data);

        return redirect()->route('rooms.index')->with('success', 'Sala actualizada.');
    }

    
    public function destroy(MeetingRoom $room)
    {
       
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Sala movida a la papelera.');
        
    }

    public function trash()
    {
        
        $rooms = MeetingRoom::onlyTrashed()->get();
        return view('rooms.trash', compact('rooms'));
    }

  
    public function restore($id)
    {
        $room = MeetingRoom::withTrashed()->findOrFail($id);
        $room->restore();
        return redirect()->route('rooms.index')->with('success', 'Sala restaurada correctamente.');
    }

   
    public function forceDelete($id)
    {
        $room = MeetingRoom::withTrashed()->findOrFail($id);

     
        if ($room->image_path) {
            Storage::disk('public')->delete($room->image_path);
        }

        $room->forceDelete(); 
        return redirect()->back()->with('success', 'Sala eliminada permanentemente.');
    }

    
}