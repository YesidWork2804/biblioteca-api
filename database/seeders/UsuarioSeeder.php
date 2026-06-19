<?php

namespace Database\Seeders;

use App\Domain\Models\Usuario;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            ['Juan', 'Pérez', 'juan.perez@email.com', '3001234567'],
            ['María', 'Gómez', 'maria.gomez@email.com', '3007654321'],
            ['Carlos', 'López', 'carlos.lopez@email.com', '3012345678'],
            ['Ana', 'Martínez', 'ana.martinez@email.com', '3023456789'],
            ['Luis', 'Rodríguez', 'luis.rodriguez@email.com', '3034567890'],
            ['Laura', 'Hernández', 'laura.hernandez@email.com', '3045678901'],
            ['Pedro', 'García', 'pedro.garcia@email.com', '3056789012'],
            ['Sofía', 'González', 'sofia.gonzalez@email.com', '3067890123'],
            ['Diego', 'Ramírez', 'diego.ramirez@email.com', '3078901234'],
            ['Valentina', 'Torres', 'valentina.torres@email.com', '3089012345'],
            ['Andrés', 'Vargas', 'andres.vargas@email.com', '3090123456'],
            ['Camila', 'Castro', 'camila.castro@email.com', '3101234567'],
            ['Felipe', 'Mendoza', 'felipe.mendoza@email.com', '3112345678'],
            ['Isabella', 'Ruiz', 'isabella.ruiz@email.com', '3123456789'],
            ['Santiago', 'Morales', 'santiago.morales@email.com', '3134567890'],
        ];

        foreach ($usuarios as $usuario) {
            Usuario::create([
                'nombre' => $usuario[0],
                'apellido' => $usuario[1],
                'email' => $usuario[2],
                'telefono' => $usuario[3],
                'fecha_registro' => Carbon::now()->subDays(rand(1, 365)),
                'estado' => true,
            ]);
        }
    }
}
