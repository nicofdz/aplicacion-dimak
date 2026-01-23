<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReservationCancelled extends Notification
{
    use Queueable;

    public $reservation;
    public $reason;

    public function __construct($reservation, $reason)
    {
        $this->reservation = $reservation;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Reserva cancelada: ' . $this->reservation->meetingRoom->name,
            'reason' => $this->reason, 
            'action_url' => route('reservations.my_reservations'),
            'icon' => 'x', 
            'color' => 'red',
            'type' => 'danger'
        ];
    }
}