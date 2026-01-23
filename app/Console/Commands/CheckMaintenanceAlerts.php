<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\MaintenanceService;

class CheckMaintenanceAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehicles:check-maintenance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chequea el kilometraje de los vehículos y envía alertas de mantenimiento si es necesario';

    /**
     * Execute the console command.
     */
    public function handle(MaintenanceService $service)
    {
        $this->info('Iniciando chequeo de mantenimiento...');

        $service->checkAndNotify();

        $this->info('Chequeo completado. Se enviaron las notificaciones correspondientes.');
    }
}
