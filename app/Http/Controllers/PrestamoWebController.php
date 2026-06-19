<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class PrestamoWebController extends Controller
{
    public function create()
    {
        return view('prestamos.create');
    }
}
