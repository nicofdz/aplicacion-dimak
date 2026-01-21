<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceAlert extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $vehicle;
    public $message;
    public $type;
    public $nextKm;

    /**
     * Create a new notification instance.
     */
    public function __construct($vehicle, $message, $type, $nextKm)
    {
        $this->vehicle = $vehicle;
        $this->message = $message;
        $this->type = $type;
        $this->nextKm = $nextKm;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'vehicle_id' => $this->vehicle->id,
            'plate' => $this->vehicle->plate,
            'brand_model' => $this->vehicle->brand . ' ' . $this->vehicle->model,
            'image_path' => $this->vehicle->image_path,
            'message' => $this->message,
            'type' => $this->type, // 'danger' or 'warning'
            'next_oil_change_km' => $this->nextKm,
        ];
    }


}
