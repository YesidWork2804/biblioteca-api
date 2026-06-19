<?php

namespace App\Infrastructure\Http\Controllers\Api;

use App\Domain\Models\Usuario;
use App\Infrastructure\Http\Resources\UsuarioResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class UsuarioController extends Controller
{
    public function index(): JsonResponse
    {
        $usuarios = Usuario::orderBy('nombre')->get();
        return response()->json([
            'data' => UsuarioResource::collection($usuarios),
        ], 200);
    }
}
