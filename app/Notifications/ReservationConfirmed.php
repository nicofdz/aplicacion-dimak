<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationConfirmed extends Notification
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
            'message' => '¡Aprobada! Tu reserva en ' . $this->reservation->meetingRoom->name . ' ha sido confirmada.',
            'action_url' => route('reservations.my_reservations'),
            'icon' => 'check',
            'color' => 'green'
        ];
    }
}