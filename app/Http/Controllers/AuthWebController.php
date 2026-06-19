<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class AuthWebController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }
}
