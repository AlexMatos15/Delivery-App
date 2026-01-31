<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determina se o usuário pode visualizar qualquer pedido.
     */
    public function viewAny(User $user): bool
    {
        // Apenas lojas e admins podem visualizar pedidos
        return $user->isLoja() || $user->isAdmin();
    }

    /**
     * Determina se o usuário pode visualizar um pedido específico.
     */
    public function view(User $user, Order $order): bool
    {
        // Admin pode visualizar qualquer pedido
        if ($user->isAdmin()) {
            return true;
        }

        // Loja pode visualizar apenas seus pedidos
        if ($user->isLoja()) {
            return $order->items()
                ->whereIn('product_id', $user->products()->pluck('id'))
                ->exists();
        }

        // Cliente pode visualizar apenas seus próprios pedidos
        if ($user->isCliente()) {
            return $order->customer_id === $user->id;
        }

        return false;
    }

    /**
     * Determina se o usuário pode criar um pedido.
     */
    public function create(User $user): bool
    {
        return $user->isCliente();
    }

    /**
     * Determina se o usuário pode atualizar um pedido.
     */
    public function update(User $user, Order $order): bool
    {
        // Admin pode atualizar qualquer pedido
        if ($user->isAdmin()) {
            return true;
        }

        // Loja pode atualizar apenas seus pedidos
        if ($user->isLoja()) {
            return $order->items()
                ->whereIn('product_id', $user->products()->pluck('id'))
                ->exists();
        }

        // Cliente pode atualizar (cancelar) apenas seus próprios pedidos
        if ($user->isCliente()) {
            return $order->customer_id === $user->id;
        }

        return false;
    }

    /**
     * Determina se o usuário pode cancelar um pedido.
     */
    public function cancel(User $user, Order $order): bool
    {
        // Admin pode cancelar qualquer pedido
        if ($user->isAdmin()) {
            return true;
        }

        // Loja não pode cancelar pedidos
        if ($user->isLoja()) {
            return false;
        }

        // Cliente pode cancelar apenas seus próprios pedidos se estiverem pendentes/confirmados
        if ($user->isCliente()) {
            return $order->customer_id === $user->id 
                && in_array($order->status, ['pending', 'confirmed']);
        }

        return false;
    }

    /**
     * Determina se o usuário pode deletar um pedido.
     */
    public function delete(User $user, Order $order): bool
    {
        // Apenas admin pode deletar pedidos
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode restaurar um pedido.
     */
    public function restore(User $user, Order $order): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode deletar permanentemente um pedido.
     */
    public function forceDelete(User $user, Order $order): bool
    {
        return $user->isAdmin();
    }
}
