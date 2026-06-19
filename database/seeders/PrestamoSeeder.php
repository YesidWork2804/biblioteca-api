<?php

namespace Database\Seeders;

use App\Models\Prestamo;
use App\Models\Libro;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PrestamoSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = Usuario::all();
        $libros = Libro::where('stock_disponible', '>', 0)->get();

        for ($i = 0; $i < 10; $i++) {
            $libro = $libros->random();
            $fechaPrestamo = Carbon::now()->subDays(rand(1, 30));
            $fechaEstimada = $fechaPrestamo->copy()->addDays(15);

            Prestamo::create([
                'usuario_id' => $usuarios->random()->id,
                'libro_id' => $libro->id,
                'fecha_prestamo' => $fechaPrestamo,
                'fecha_devolucion_estimada' => $fechaEstimada,
                'fecha_devolucion_real' => rand(0, 1) ? $fechaEstimada->copy()->subDays(rand(1, 5)) : null,
                'estado' => rand(0, 1) ? 'devuelto' : 'activo',
            ]);
        }
    }
}
