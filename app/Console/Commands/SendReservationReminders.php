<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RoomReservation;
use App\Notifications\ReservationStartingSoon;
use Carbon\Carbon;

class SendReservationReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Envía notificaciones para reservas próximas';

    public function handle()
    {
        // 1. Definimos la VENTANA DE TIEMPO (Desde YA hasta en 30 mins más)
        $now = Carbon::now();
        $future = Carbon::now()->addMinutes(30);

        // 2. Buscamos reservas que caigan en esa ventana Y que estén aprobadas
        $reservations = RoomReservation::whereBetween('start_time', [$now, $future])
            ->where('status', 'approved') // <--- IMPORTANTE: Solo las aprobadas
            ->with('user')
            ->get();

        foreach ($reservations as $reservation) {
            
            // 3. FILTRO ANTI-SPAM: Revisamos si ya le enviamos aviso por ESTA reserva
            $alreadyNotified = $reservation->user->notifications()
                ->where('type', ReservationStartingSoon::class)
                ->whereJsonContains('data->reservation_id', $reservation->id)
                ->exists();

            // 4. Si NO ha sido notificado, enviamos.
            if (! $alreadyNotified) {
                $reservation->user->notify(new ReservationStartingSoon($reservation));
                $this->info("Notificación enviada a: {$reservation->user->name} (Reserva: {$reservation->start_time->format('H:i')})");
            } else {
                // Esto es solo para que tú veas en consola que el sistema sí la vio, pero la omitió
                $this->comment("La reserva de las {$reservation->start_time->format('H:i')} ya fue notificada antes.");
            }
        }
    }
}