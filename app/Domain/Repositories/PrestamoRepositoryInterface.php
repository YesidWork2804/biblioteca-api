<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Prestamo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PrestamoRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Prestamo;
    public function findWithRelations(int $id): ?Prestamo;
    public function create(array $data): Prestamo;
    public function update(int $id, array $data): Prestamo;
    public function devolver(int $id): Prestamo;
    public function countActivosByUsuario(int $usuarioId): int;
    public function marcarVencidos(): int;
}
