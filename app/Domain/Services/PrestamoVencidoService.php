<?php

namespace App\Domain\Services;

use App\Domain\Repositories\PrestamoRepositoryInterface;

class PrestamoVencidoService
{
    public function __construct(
        private readonly PrestamoRepositoryInterface $prestamoRepository
    ) {
    }

    public function marcarVencidos(): int
    {
        return $this->prestamoRepository->marcarVencidos();
    }
}
