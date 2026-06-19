<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Models\Autor;
use App\Domain\Repositories\AutorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class EloquentAutorRepository implements AutorRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Autor::query();

        if (!empty($filters['nombre'])) {
            $query->where(function (Builder $q) use ($filters) {
                $q->where('nombre', 'ilike', '%' . $filters['nombre'] . '%')
                    ->orWhere('apellido', 'ilike', '%' . $filters['nombre'] . '%');
            });
        }

        if (!empty($filters['nacionalidad'])) {
            $query->where('nacionalidad', $filters['nacionalidad']);
        }

        return $query->orderBy('apellido')->paginate($perPage);
    }

    public function find(int $id): ?Autor
    {
        return Autor::find($id);
    }

    public function findWithLibros(int $id): ?Autor
    {
        return Autor::with('libros')->find($id);
    }

    public function create(array $data): Autor
    {
        return Autor::create($data);
    }

    public function update(int $id, array $data): Autor
    {
        $autor = Autor::findOrFail($id);
        $autor->update($data);
        return $autor->fresh();
    }

    public function delete(int $id): bool
    {
        $autor = Autor::findOrFail($id);
        return $autor->delete();
    }

    public function tieneLibros(int $id): bool
    {
        return Autor::findOrFail($id)->libros()->exists();
    }
}
