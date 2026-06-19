<?php

namespace App\Console\Commands;

use App\Domain\Services\PrestamoVencidoService;
use Illuminate\Console\Command;

class MarcarPrestamosVencidosCommand extends Command
{
    protected $signature = 'prestamos:marcar-vencidos';
    protected $description = 'Marca como vencidos los préstamos activos que superaron la fecha estimada';

    public function handle(PrestamoVencidoService $service): int
    {
        $this->info('Buscando préstamos vencidos...');

        $cantidad = $service->marcarVencidos();

        $this->info("Se marcaron {$cantidad} préstamo(s) como vencido(s).");

        return self::SUCCESS;
    }
}
