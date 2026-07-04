<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /** Arahkan user ke dashboard sesuai peran. */
    public function index(): RedirectResponse
    {
        $user = Auth::user();

        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('sekretaris.dashboard');
    }
}
