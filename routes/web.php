<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LibroWebController;
use App\Http\Controllers\PrestamoWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('libros.index'));

Route::get('/libros', [LibroWebController::class, 'index'])->name('libros.index');
Route::get('/prestamos/crear', [PrestamoWebController::class, 'create'])->name('prestamos.create');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
