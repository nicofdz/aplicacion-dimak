<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomReservation extends Model
{
    protected $fillable = [
        'user_id',
        'meeting_room_id',
        'start_time',
        'end_time',
        'purpose',
        'attendees',
        'resources',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'resources' => 'array', 
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function meetingRoom()
    {
        return $this->belongsTo(MeetingRoom::class);
    }
   
    public function room()
    {
        return $this->belongsTo(MeetingRoom::class);
    }
}