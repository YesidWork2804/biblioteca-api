<?php

use App\Infrastructure\Http\Controllers\Api\AuthController;
use App\Infrastructure\Http\Controllers\Api\LibroController;
use App\Infrastructure\Http\Controllers\Api\PrestamoController;
use App\Infrastructure\Http\Controllers\Api\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::get('/user', fn (Request $request) => $request->user());

    Route::get('/libros', [LibroController::class, 'index']);
    Route::get('/libros/{id}', [LibroController::class, 'show'])->whereNumber('id');
    Route::post('/libros', [LibroController::class, 'store']);
    Route::put('/libros/{id}', [LibroController::class, 'update'])->whereNumber('id');
    Route::patch('/libros/{id}', [LibroController::class, 'update'])->whereNumber('id');
    Route::delete('/libros/{id}', [LibroController::class, 'destroy'])->whereNumber('id');

    Route::get('/prestamos', [PrestamoController::class, 'index']);
    Route::post('/prestamos', [PrestamoController::class, 'store']);
    Route::put('/prestamos/{id}/devolver', [PrestamoController::class, 'devolver'])->whereNumber('id');

    Route::get('/usuarios', [UsuarioController::class, 'index']);
});
