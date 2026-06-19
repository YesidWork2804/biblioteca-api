<?php

namespace App\Domain\Services;

use App\Domain\Repositories\LibroRepositoryInterface;
use App\Domain\Repositories\PrestamoRepositoryInterface;
use App\Domain\Models\Usuario;
use App\Domain\Models\Prestamo;
use Exception;

class PrestamoService
{
    public function __construct(
        private readonly PrestamoRepositoryInterface $prestamoRepository,
        private readonly LibroRepositoryInterface $libroRepository
    ) {
    }

    public function crearPrestamo(array $data): Prestamo
    {
        $usuario = Usuario::find($data['usuario_id']);
        if (!$usuario || !$usuario->estado) {
            throw new Exception('El usuario no existe o no está activo');
        }

        $libro = $this->libroRepository->find($data['libro_id']);
        if (!$libro) {
            throw new Exception('El libro no existe');
        }

        if ($libro->stock_disponible <= 0) {
            throw new Exception('No hay stock disponible para este libro');
        }

        $prestamosActivos = $this->prestamoRepository->countActivosByUsuario($data['usuario_id']);
        if ($prestamosActivos >= 3) {
            throw new Exception('El usuario ya tiene 3 préstamos activos. No puede tener más.');
        }

        $this->libroRepository->decrementarStock($data['libro_id']);

        return $this->prestamoRepository->create($data);
    }

    public function devolver(int $id): Prestamo
    {
        $prestamo = $this->prestamoRepository->find($id);

        if (!$prestamo) {
            throw new Exception('Préstamo no encontrado');
        }

        if ($prestamo->estado === 'devuelto') {
            throw new Exception('El préstamo ya fue devuelto');
        }

        $this->libroRepository->incrementarStock($prestamo->libro_id);

        return $this->prestamoRepository->devolver($id);
    }
}
