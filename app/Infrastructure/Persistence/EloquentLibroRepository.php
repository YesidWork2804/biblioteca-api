<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Models\Libro;
use App\Domain\Repositories\LibroRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class EloquentLibroRepository implements LibroRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Libro::query()->with('autores');

        if (!empty($filters['titulo'])) {
            $query->where('titulo', 'ilike', '%' . $filters['titulo'] . '%');
        }

        if (!empty($filters['anio'])) {
            $query->where('anio_publicacion', $filters['anio']);
        }

        if (!empty($filters['autor_id'])) {
            $query->porAutor((int) $filters['autor_id']);
        }

        if (!empty($filters['disponibles'])) {
            $query->disponibles();
        }

        return $query->orderBy('titulo')->paginate($perPage);
    }

    public function find(int $id): ?Libro
    {
        return Libro::find($id);
    }

    public function findWithAutores(int $id): ?Libro
    {
        return Libro::with('autores')->find($id);
    }

    public function create(array $data, array $autores = []): Libro
    {
        $libro = Libro::create($data);

        if (!empty($autores)) {
            $syncData = [];
            foreach ($autores as $index => $autorId) {
                $syncData[$autorId] = ['orden_autor' => $index + 1];
            }
            $libro->autores()->sync($syncData);
        }

        return $libro->load('autores');
    }

    public function update(int $id, array $data, array $autores = []): Libro
    {
        $libro = Libro::findOrFail($id);
        $libro->update($data);

        if (!empty($autores)) {
            $syncData = [];
            foreach ($autores as $index => $autorId) {
                $syncData[$autorId] = ['orden_autor' => $index + 1];
            }
            $libro->autores()->sync($syncData);
        }

        return $libro->fresh('autores');
    }

    public function delete(int $id): bool
    {
        $libro = Libro::findOrFail($id);
        return $libro->delete();
    }

    public function disponibles(): Collection
    {
        return Libro::disponibles()->orderBy('titulo')->get();
    }

    public function decrementarStock(int $id): bool
    {
        $libro = Libro::findOrFail($id);
        if ($libro->stock_disponible <= 0) {
            return false;
        }
        $libro->decrement('stock_disponible');
        return true;
    }

    public function incrementarStock(int $id): bool
    {
        $libro = Libro::findOrFail($id);
        $libro->increment('stock_disponible');
        return true;
    }
}
