<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

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
                    // Admin menu - deixa o padrão do config/adminlte.php
                    return;
                }
                
                if ($user->isShop()) {
                    // Menu para lojas
                    $shopMenu = [
                        [
                            'type' => 'navbar-search',
                            'text' => 'search',
                            'topnav_right' => true,
                        ],
                        [
                            'type' => 'fullscreen-widget',
                            'topnav_right' => true,
                        ],
                        ['header' => 'Gestão'],
                        [
                            'text' => 'Painel',
                            'url' => 'dashboard',
                            'icon' => 'fas fa-home',
                        ],
                        [
                            'text' => 'Pedidos',
                            'url' => 'orders',
                            'icon' => 'fas fa-box',
                        ],
                        [
                            'text' => 'Produtos',
                            'url' => 'admin/products',
                            'icon' => 'fas fa-cube',
                        ],
                        [
                            'text' => 'Categorias',
                            'url' => 'products',
                            'icon' => 'fas fa-list',
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
                    
                    Config::set('adminlte.menu', $shopMenu);
                    return;
                }
                
                // Menu para clientes
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
                
                Config::set('adminlte.menu', $clientMenu);
            }
        });
    }
}
