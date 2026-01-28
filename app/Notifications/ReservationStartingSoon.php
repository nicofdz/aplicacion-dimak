<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationStartingSoon extends Notification
{
    use Queueable;

    protected $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function via(object $notifiable): array
    {
        return ['database']; 
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'title'   => 'Tu reserva comienza pronto',
            'message' => 'Tu reserva en la sala "' . $this->reservation->meetingRoom->name . '" comienza en 30 minutos.',
            'url'     => route('reservations.my_reservations'),
            'icon'    => 'clock',
            'color'   => 'yellow'
        ];
    }
}