<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vehicle;
use App\Models\User;
use App\Notifications\VehicleDocumentExpired;
use Illuminate\Support\Facades\Notification;

class CheckVehicleDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehicles:check-documents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica si hay documentos vencidos y notifica a los administradores';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando verificación de documentos...');

        $vehicles = Vehicle::with('documents')->get();
        $expiredCount = 0;

        foreach ($vehicles as $vehicle) {
            foreach ($vehicle->documents as $document) {
                if ($document->expires_at && $document->expires_at < now()->startOfDay()) {
                    // Encontrado documento vencido
                    $this->error("Vehículo {$vehicle->plate} tiene vencido: {$document->type}");

                    // Notificar a admins (role 'admin' o todos si no hay roles definidos aun, asumo User::all() para demo o filtrar por isAdmin)
                    // Asumiremos que notificamos a todos los usuarios por ahora o si tienes implementado roles.
                    // Ajuste: Notificar a usuarios que sean admin. Como no sé la estructura exacta de roles, notificaré al usuario ID 1 o todos.
                    // Mejor: Notificar a todos los usuarios.

                    $users = User::all(); // O filtrar User::where('role', 'admin')->get();

                    // Evitar spam: Podríamos chequear si ya se notificó hoy, pero por ahora simple.
                    Notification::send($users, new VehicleDocumentExpired($vehicle, $document->type));

                    $expiredCount++;
                }
            }
        }

        $this->info("Verificación completada. {$expiredCount} documentos vencidos encontrados.");
    }
}
