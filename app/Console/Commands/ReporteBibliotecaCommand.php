<?php

namespace App\Console\Commands;

use App\Domain\Models\Libro;
use App\Domain\Models\Prestamo;
use App\Domain\Models\Usuario;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReporteBibliotecaCommand extends Command
{
    protected $signature = 'reportes:biblioteca';
    protected $description = 'Genera un reporte con libros más prestados, usuarios con préstamos vencidos y libros sin stock';

    public function handle(): int
    {
        $this->info('=== REPORTE DE BIBLIOTECA ===');
        $this->newLine();

        $this->librosMasPrestados();
        $this->newLine();
        $this->usuariosConPrestamosVencidos();
        $this->newLine();
        $this->librosSinStock();

        return self::SUCCESS;
    }

    private function librosMasPrestados(): void
    {
        $this->info('--- LIBROS MÁS PRESTADOS (Top 10) ---');

        $libros = DB::table('prestamos')
            ->select('libro_id', DB::raw('COUNT(*) as total'))
            ->groupBy('libro_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        if ($libros->isEmpty()) {
            $this->warn('No hay datos de préstamos.');
            return;
        }

        $rows = [];
        foreach ($libros as $row) {
            $libro = Libro::find($row->libro_id);
            $rows[] = [
                $libro?->titulo ?? "ID {$row->libro_id}",
                $row->total,
            ];
        }

        $this->table(['Libro', 'Préstamos'], $rows);
    }

    private function usuariosConPrestamosVencidos(): void
    {
        $this->info('--- USUARIOS CON PRÉSTAMOS VENCIDOS ---');

        $prestamos = Prestamo::with('usuario')
            ->where('estado', 'vencido')
            ->get();

        if ($prestamos->isEmpty()) {
            $this->warn('No hay préstamos vencidos.');
            return;
        }

        $rows = [];
        foreach ($prestamos as $p) {
            $rows[] = [
                $p->usuario?->nombre_completo ?? 'N/A',
                $p->usuario?->email ?? 'N/A',
                $p->libro?->titulo ?? 'N/A',
                $p->fecha_devolucion_estimada?->format('Y-m-d'),
            ];
        }

        $this->table(['Usuario', 'Email', 'Libro', 'Vencido desde'], $rows);
    }

    private function librosSinStock(): void
    {
        $this->info('--- LIBROS SIN STOCK ---');

        $libros = Libro::where('stock_disponible', 0)->get();

        if ($libros->isEmpty()) {
            $this->warn('Todos los libros tienen stock disponible.');
            return;
        }

        $rows = [];
        foreach ($libros as $libro) {
            $rows[] = [
                $libro->id,
                $libro->titulo,
                $libro->isbn,
                $libro->anio_publicacion,
            ];
        }

        $this->table(['ID', 'Título', 'ISBN', 'Año'], $rows);
    }
}
