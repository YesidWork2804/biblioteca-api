<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Models\Prestamo;
use App\Domain\Repositories\PrestamoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class EloquentPrestamoRepository implements PrestamoRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Prestamo::with(['libro', 'usuario']);

        if (!empty($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }

        if (!empty($filters['usuario_id'])) {
            $query->where('usuario_id', $filters['usuario_id']);
        }

        if (!empty($filters['libro_id'])) {
            $query->where('libro_id', $filters['libro_id']);
        }

        return $query->orderBy('fecha_prestamo', 'desc')->paginate($perPage);
    }

    public function find(int $id): ?Prestamo
    {
        return Prestamo::find($id);
    }

    public function findWithRelations(int $id): ?Prestamo
    {
        return Prestamo::with(['libro', 'usuario'])->find($id);
    }

    public function create(array $data): Prestamo
    {
        return Prestamo::create($data);
    }

    public function update(int $id, array $data): Prestamo
    {
        $prestamo = Prestamo::findOrFail($id);
        $prestamo->update($data);
        return $prestamo->fresh();
    }

    public function devolver(int $id): Prestamo
    {
        $prestamo = Prestamo::findOrFail($id);
        $prestamo->update([
            'fecha_devolucion_real' => Carbon::now(),
            'estado' => 'devuelto',
        ]);
        return $prestamo->fresh();
    }

    public function countActivosByUsuario(int $usuarioId): int
    {
        return Prestamo::where('usuario_id', $usuarioId)
            ->where('estado', 'activo')
            ->count();
    }

    public function marcarVencidos(): int
    {
        return Prestamo::where('estado', 'activo')
            ->where('fecha_devolucion_estimada', '<', Carbon::now())
            ->update(['estado' => 'vencido']);
    }
}
