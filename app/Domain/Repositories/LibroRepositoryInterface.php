<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Libro;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LibroRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Libro;
    public function findWithAutores(int $id): ?Libro;
    public function create(array $data, array $autores = []): Libro;
    public function update(int $id, array $data, array $autores = []): Libro;
    public function delete(int $id): bool;
    public function disponibles(): \Illuminate\Database\Eloquent\Collection;
    public function decrementarStock(int $id): bool;
    public function incrementarStock(int $id): bool;
}
