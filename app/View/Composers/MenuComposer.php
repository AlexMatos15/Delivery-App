<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class MenuComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $user = Auth::user();

        // Default empty menu
        $menu = [];

        if ($user) {
            if ($user->isAdmin()) {
                $menu = $this->getAdminMenu();
            } elseif ($user->isLoja()) {
                $menu = $this->getLojaMenu();
            } elseif ($user->isCliente()) {
                $menu = $this->getClienteMenu();
            }
        }

        Config::set('adminlte.menu', $menu);
    }

    /**
     * Get admin menu structure
     */
    private function getAdminMenu(): array
    {
        return [
            [
                'type' => 'navbar-search',
                'text' => 'search',
                'topnav_right' => true,
            ],
            [
                'type' => 'fullscreen-widget',
                'topnav_right' => true,
            ],
            ['header' => 'GERENCIAMENTO'],
            [
                'text' => 'Painel',
                'url' => 'admin/dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'text' => 'Pedidos',
                'url' => 'admin/orders',
                'icon' => 'fas fa-box',
                'badge' => 'novo',
                'badge_color' => 'danger',
            ],
            [
                'text' => 'Usuários',
                'url' => 'admin/users',
                'icon' => 'fas fa-users',
            ],
            [
                'text' => 'Lojas',
                'url' => '#',
                'icon' => 'fas fa-store',
            ],
            [
                'text' => 'Produtos',
                'url' => 'admin/products',
                'icon' => 'fas fa-cube',
            ],
            [
                'text' => 'Categorias',
                'url' => 'admin/categories',
                'icon' => 'fas fa-list',
            ],
            ['header' => 'CONTA'],
            [
                'text' => 'Perfil',
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
    }

    /**
     * Get loja menu structure
     */
    private function getLojaMenu(): array
    {
        return [
            [
                'type' => 'navbar-search',
                'text' => 'search',
                'topnav_right' => true,
            ],
            [
                'type' => 'fullscreen-widget',
                'topnav_right' => true,
            ],
            ['header' => 'OPERACIONAL'],
            [
                'text' => 'Painel da Loja',
                'url' => 'loja/dashboard',
                'icon' => 'fas fa-home',
            ],
            [
                'text' => 'Pedidos',
                'url' => 'loja/orders',
                'icon' => 'fas fa-box',
            ],
            [
                'text' => 'Produtos',
                'url' => 'loja/products',
                'icon' => 'fas fa-cube',
            ],
            ['header' => 'CONTA'],
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
    }

    /**
     * Get cliente menu structure (for Cliente layout with AdminLTE)
     */
    private function getClienteMenu(): array
    {
        return [
            [
                'type' => 'navbar-search',
                'text' => 'search',
                'topnav_right' => true,
            ],
            [
                'type' => 'fullscreen-widget',
                'topnav_right' => true,
            ],
            ['header' => 'NAVEGAÇÃO'],
            [
                'text' => 'Cardápio',
                'url' => '/products',
                'icon' => 'fas fa-shopping-bag',
            ],
            [
                'text' => 'Meu Carrinho',
                'url' => '/cliente/cart',
                'icon' => 'fas fa-shopping-cart',
            ],
            [
                'text' => 'Meus Pedidos',
                'url' => '/cliente/orders',
                'icon' => 'fas fa-box',
            ],
            [
                'text' => 'Meus Endereços',
                'url' => '/cliente/addresses',
                'icon' => 'fas fa-map-marker-alt',
            ],
            ['header' => 'CONTA'],
            [
                'text' => 'Perfil',
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
    }
}
