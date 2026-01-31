<?php

namespace App\Http\Controllers\Loja;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard da loja.
     */
    public function index(): View
    {
        $loja = auth()->user();

        return view('loja.dashboard', compact('loja'));
    }
}
