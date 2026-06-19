<?php

namespace Database\Seeders;

use App\Models\Autor;
use Illuminate\Database\Seeder;

class AutorSeeder extends Seeder
{
    public function run(): void
    {
        $autores = [
            ['Gabriel', 'García Márquez', '1927-03-06', 'Colombiana', 'Autor de Cien años de soledad.'],
            ['Mario', 'Vargas Llosa', '1936-03-28', 'Peruana', 'Premio Nobel de Literatura 2010.'],
            ['Pablo', 'Neruda', '1904-07-12', 'Chilena', 'Poeta, premio Nobel 1971.'],
            ['Jorge Luis', 'Borges', '1899-08-24', 'Argentina', 'Maestro del cuento y ensayo.'],
            ['Isabel', 'Allende', '1942-08-02', 'Chilena', 'Autora de La casa de los espíritus.'],
            ['Julio', 'Cortázar', '1914-08-26', 'Argentina', 'Autor de Rayuela.'],
            ['Carlos', 'Fuentes', '1928-11-11', 'Mexicana', 'Novelista y ensayista.'],
            ['Octavio', 'Paz', '1914-03-31', 'Mexicana', 'Poeta, Nobel 1990.'],
            ['Miguel', 'de Cervantes', '1547-09-29', 'Española', 'Autor de Don Quijote.'],
            ['George', 'Orwell', '1903-06-25', 'Británica', 'Autor de 1984 y Rebelión en la granja.'],
        ];

        foreach ($autores as $autor) {
            Autor::create([
                'nombre' => $autor[0],
                'apellido' => $autor[1],
                'fecha_nacimiento' => $autor[2],
                'nacionalidad' => $autor[3],
                'biografia' => $autor[4],
            ]);
        }
    }
}
