<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReservationRequest extends Notification
{
    use Queueable;

    public $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['database']; 
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Nueva solicitud: ' . $this->reservation->meetingRoom->name . ' (' . $this->reservation->start_time->format('d/m H:i') . ')',
            'action_url' => route('rooms.index'),
            'icon' => 'bell',
            'color' => 'yellow'
        ];
    }
}