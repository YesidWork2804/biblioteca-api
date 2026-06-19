<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Autor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AutorRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Autor;
    public function findWithLibros(int $id): ?Autor;
    public function create(array $data): Autor;
    public function update(int $id, array $data): Autor;
    public function delete(int $id): bool;
    public function tieneLibros(int $id): bool;
}
