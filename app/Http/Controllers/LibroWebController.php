<?php

namespace App\Http\Controllers;

use App\Domain\Repositories\LibroRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LibroWebController extends Controller
{
    public function __construct(
        private readonly LibroRepositoryInterface $libroRepository
    ) {
    }

    public function index(Request $request)
    {
        return view('libros.index');
    }
}
