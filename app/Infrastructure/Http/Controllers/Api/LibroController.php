<?php

namespace App\Infrastructure\Http\Controllers\Api;

use App\Domain\Repositories\LibroRepositoryInterface;
use App\Infrastructure\Http\Requests\StoreLibroRequest;
use App\Infrastructure\Http\Requests\UpdateLibroRequest;
use App\Infrastructure\Http\Resources\LibroResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class LibroController extends Controller
{
    public function __construct(
        private readonly LibroRepositoryInterface $libroRepository
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['titulo', 'autor_id', 'anio', 'disponibles']);
        $libros = $this->libroRepository->paginate($filters, 15);

        return response()->json([
            'data' => LibroResource::collection($libros->items()),
            'meta' => [
                'current_page' => $libros->currentPage(),
                'last_page' => $libros->lastPage(),
                'per_page' => $libros->perPage(),
                'total' => $libros->total(),
            ],
        ], Response::HTTP_OK);
    }

    public function show(int $id): JsonResponse
    {
        $libro = $this->libroRepository->findWithAutores($id);

        if (!$libro) {
            return response()->json([
                'message' => 'Libro no encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => new LibroResource($libro),
        ], Response::HTTP_OK);
    }

    public function store(StoreLibroRequest $request): JsonResponse
    {
        $data = $request->validated();
        $autores = $data['autores'];
        unset($data['autores']);

        $libro = $this->libroRepository->create($data, $autores);

        return response()->json([
            'message' => 'Libro creado exitosamente',
            'data' => new LibroResource($libro),
        ], Response::HTTP_CREATED);
    }

    public function update(UpdateLibroRequest $request, int $id): JsonResponse
    {
        $libro = $this->libroRepository->find($id);

        if (!$libro) {
            return response()->json([
                'message' => 'Libro no encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        $data = $request->validated();
        $autores = $data['autores'] ?? null;
        if ($autores !== null) {
            unset($data['autores']);
        }

        $libro = $this->libroRepository->update($id, $data, $autores ?? []);

        return response()->json([
            'message' => 'Libro actualizado exitosamente',
            'data' => new LibroResource($libro),
        ], Response::HTTP_OK);
    }

    public function destroy(int $id): JsonResponse
    {
        $libro = $this->libroRepository->find($id);

        if (!$libro) {
            return response()->json([
                'message' => 'Libro no encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        $this->libroRepository->delete($id);

        return response()->json([
            'message' => 'Libro eliminado exitosamente',
        ], Response::HTTP_OK);
    }
}
