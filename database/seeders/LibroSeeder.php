<?php

namespace Database\Seeders;

use App\Domain\Models\Libro;
use App\Domain\Models\Autor;
use Illuminate\Database\Seeder;

class LibroSeeder extends Seeder
{
    public function run(): void
    {
        $libros = [
            ['titulo' => 'Cien años de soledad', 'isbn' => '978-0307474728', 'anio' => 1967, 'paginas' => 471, 'stock' => 5],
            ['titulo' => 'La ciudad y los perros', 'isbn' => '978-8420471833', 'anio' => 1963, 'paginas' => 408, 'stock' => 3],
            ['titulo' => 'Veinte poemas de amor', 'isbn' => '978-9500426737', 'anio' => 1924, 'paginas' => 96, 'stock' => 4],
            ['titulo' => 'Ficciones', 'isbn' => '978-0394752846', 'anio' => 1944, 'paginas' => 224, 'stock' => 2],
            ['titulo' => 'La casa de los espíritus', 'isbn' => '978-1501117015', 'anio' => 1982, 'paginas' => 448, 'stock' => 6],
            ['titulo' => 'Rayuela', 'isbn' => '978-0394752846', 'anio' => 1963, 'paginas' => 736, 'stock' => 3],
            ['titulo' => 'La muerte de Artemio Cruz', 'isbn' => '978-9681661651', 'anio' => 1962, 'paginas' => 352, 'stock' => 2],
            ['titulo' => 'El laberinto de la soledad', 'isbn' => '978-9681661460', 'anio' => 1950, 'paginas' => 191, 'stock' => 4],
            ['titulo' => 'Don Quijote de la Mancha', 'isbn' => '978-8424116058', 'anio' => 1605, 'paginas' => 863, 'stock' => 7],
            ['titulo' => '1984', 'isbn' => '978-0451524935', 'anio' => 1949, 'paginas' => 328, 'stock' => 5],
            ['titulo' => 'Rebelión en la granja', 'isbn' => '978-0451526342', 'anio' => 1945, 'paginas' => 112, 'stock' => 3],
            ['titulo' => 'El amor en los tiempos del cólera', 'isbn' => '978-0307389732', 'anio' => 1985, 'paginas' => 348, 'stock' => 4],
            ['titulo' => 'La fiesta del chivo', 'isbn' => '978-8420471574', 'anio' => 2000, 'paginas' => 528, 'stock' => 2],
            ['titulo' => 'Canto general', 'isbn' => '978-9500426751', 'anio' => 1950, 'paginas' => 480, 'stock' => 0],
            ['titulo' => 'El Aleph', 'isbn' => '978-0142437886', 'anio' => 1949, 'paginas' => 224, 'stock' => 3],
            ['titulo' => 'Hija de la fortuna', 'isbn' => '978-0060545660', 'anio' => 1999, 'paginas' => 448, 'stock' => 4],
            ['titulo' => 'Bestiario', 'isbn' => '978-0394752921', 'anio' => 1951, 'paginas' => 224, 'stock' => 2],
            ['titulo' => 'Aura', 'isbn' => '978-9681661675', 'anio' => 1962, 'paginas' => 96, 'stock' => 0],
            ['titulo' => 'Luna Silvestre', 'isbn' => '978-6071602705', 'anio' => 1990, 'paginas' => 250, 'stock' => 1],
            ['titulo' => 'La rebelión de los animales', 'isbn' => '978-0141185428', 'anio' => 1945, 'paginas' => 112, 'stock' => 0],
        ];

        $autores = Autor::all();

        foreach ($libros as $index => $libroData) {
            $libro = Libro::create([
                'titulo' => $libroData['titulo'],
                'isbn' => $libroData['isbn'] . '-' . $index,
                'anio_publicacion' => $libroData['anio'],
                'numero_paginas' => $libroData['paginas'],
                'descripcion' => 'Descripción del libro ' . $libroData['titulo'],
                'stock_disponible' => $libroData['stock'],
            ]);

            $autorAsignado = $autores->random(rand(1, 2));
            foreach ($autorAsignado as $orden => $autor) {
                $libro->autores()->attach($autor->id, ['orden_autor' => $orden + 1]);
            }
        }
    }
}
