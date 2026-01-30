<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Customize AdminLTE menu based on user role
        view()->composer('adminlte::page', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                
                if ($user->isAdmin()) {
                    // Admin menu - deixa o padrão
                    return;
                }
                
                // Menu para clientes e lojas
                $clientMenu = [
                    [
                        'type' => 'navbar-search',
                        'text' => 'search',
                        'topnav_right' => true,
                    ],
                    [
                        'type' => 'fullscreen-widget',
                        'topnav_right' => true,
                    ],
                    ['header' => 'Navegação'],
                    [
                        'text' => 'Dashboard',
                        'url' => 'dashboard',
                        'icon' => 'fas fa-home',
                    ],
                    [
                        'text' => 'Produtos',
                        'url' => 'products',
                        'icon' => 'fas fa-shopping-bag',
                    ],
                    [
                        'text' => 'Meu Carrinho',
                        'url' => 'cart',
                        'icon' => 'fas fa-shopping-cart',
                    ],
                    [
                        'text' => 'Meus Pedidos',
                        'url' => 'orders',
                        'icon' => 'fas fa-box',
                    ],
                    [
                        'text' => 'Meus Endereços',
                        'url' => 'addresses',
                        'icon' => 'fas fa-map-marker-alt',
                    ],
                    ['header' => 'Conta'],
                    [
                        'text' => 'Meu Perfil',
                        'url' => 'my-profile',
                        'icon' => 'fas fa-user',
                    ],
                    [
                        'text' => 'Alterar Senha',
                        'url' => 'password',
                        'icon' => 'fas fa-lock',
                    ],
                    [
                        'text' => 'Sair',
                        'url' => 'logout',
                        'icon' => 'fas fa-sign-out-alt',
                        'onclick' => 'event.preventDefault(); document.getElementById(\'logout-form\').submit();',
                    ],
                ];
                
                config(['adminlte.menu' => $clientMenu]);
            }
        });
    }
}
