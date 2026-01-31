<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DashboardRedirectController extends Controller
{
    /**
     * Redireciona para o dashboard apropriado baseado no role do usuário.
     *
     * @return RedirectResponse
     */
    public function __invoke(): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Redirecionar baseado no tipo de usuário
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isLoja()) {
            return redirect()->route('loja.dashboard');
        }

        if ($user->isCliente()) {
            return redirect()->route('cliente.dashboard');
        }

        // Se o role for inválido, retornar 403
        abort(403, 'User role is invalid.');
    }
}
