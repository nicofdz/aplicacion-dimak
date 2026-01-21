<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Vehicle;

class VehicleDocumentExpired extends Notification
{
    use Queueable;

    public $vehicle;
    public $documentType;

    /**
     * Create a new notification instance.
     */
    public function __construct(Vehicle $vehicle, $documentType)
    {
        $this->vehicle = $vehicle;
        $this->documentType = $documentType;
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
        $docLabel = match($this->documentType) {
            'insurance' => 'Seguro Obligatorio',
            'permit' => 'Permiso de Circulación',
            'technical_review' => 'Revisión Técnica',
            default => 'Documento',
        };

        return [
            'title' => 'Documento Vencido',
            'message' => "El vehículo {$this->vehicle->plate} tiene el documento {$docLabel} vencido.",
            'vehicle_id' => $this->vehicle->id,
            'type' => 'document_expired',
        ];
    }
}
