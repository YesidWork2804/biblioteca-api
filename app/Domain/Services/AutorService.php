<?php

namespace App\Domain\Services;

use App\Domain\Repositories\AutorRepositoryInterface;
use Exception;

class AutorService
{
    public function __construct(
        private readonly AutorRepositoryInterface $autorRepository
    ) {
    }

    public function eliminar(int $id): bool
    {
        if ($this->autorRepository->tieneLibros($id)) {
            throw new Exception('No se puede eliminar el autor porque tiene libros asociados');
        }

        return $this->autorRepository->delete($id);
    }
}
