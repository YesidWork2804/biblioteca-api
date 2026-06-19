<?php

namespace App\Infrastructure\Http\Controllers\Api;

use App\Domain\Models\Usuario;
use App\Domain\Repositories\PrestamoRepositoryInterface;
use App\Infrastructure\Http\Requests\StorePrestamoRequest;
use App\Infrastructure\Http\Resources\PrestamoResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class PrestamoController extends Controller
{
    public function __construct(
        private readonly PrestamoRepositoryInterface $prestamoRepository
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['estado', 'usuario_id', 'libro_id']);
        $prestamos = $this->prestamoRepository->paginate($filters, 15);

        return response()->json([
            'data' => PrestamoResource::collection($prestamos->items()),
            'meta' => [
                'current_page' => $prestamos->currentPage(),
                'last_page' => $prestamos->lastPage(),
                'per_page' => $prestamos->perPage(),
                'total' => $prestamos->total(),
            ],
        ], Response::HTTP_OK);
    }

    public function store(StorePrestamoRequest $request): JsonResponse
    {
        $data = $request->validated();

        $usuario = Usuario::find($data['usuario_id']);
        if (!$usuario || !$usuario->estado) {
            return response()->json([
                'message' => 'El usuario no está activo',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $prestamosActivos = $this->prestamoRepository->countActivosByUsuario($data['usuario_id']);
        if ($prestamosActivos >= 3) {
            return response()->json([
                'message' => 'El usuario ya tiene 3 préstamos activos. No puede tener más.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $prestamo = $this->prestamoRepository->create($data);

        return response()->json([
            'message' => 'Préstamo creado exitosamente',
            'data' => new PrestamoResource($prestamo->load(['libro', 'usuario'])),
        ], Response::HTTP_CREATED);
    }

    public function devolver(int $id): JsonResponse
    {
        $prestamo = $this->prestamoRepository->find($id);

        if (!$prestamo) {
            return response()->json([
                'message' => 'Préstamo no encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($prestamo->estado === 'devuelto') {
            return response()->json([
                'message' => 'El préstamo ya fue devuelto',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $prestamo = $this->prestamoRepository->devolver($id);

        return response()->json([
            'message' => 'Préstamo marcado como devuelto',
            'data' => new PrestamoResource($prestamo->load(['libro', 'usuario'])),
        ], Response::HTTP_OK);
    }
}
