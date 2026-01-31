<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determina se o usuário pode visualizar qualquer produto.
     */
    public function viewAny(User $user): bool
    {
        // Qualquer pessoa autenticada pode visualizar
        return true;
    }

    /**
     * Determina se o usuário pode visualizar um produto específico.
     */
    public function view(User $user, Product $product): bool
    {
        // Produtos públicos podem ser visualizados por todos
        return true;
    }

    /**
     * Determina se o usuário pode criar um produto.
     */
    public function create(User $user): bool
    {
        // Apenas lojas e admins podem criar produtos
        return $user->isLoja() || $user->isAdmin();
    }

    /**
     * Determina se o usuário pode atualizar um produto.
     */
    public function update(User $user, Product $product): bool
    {
        // Admin pode atualizar qualquer produto
        if ($user->isAdmin()) {
            return true;
        }

        // Loja pode atualizar apenas seus próprios produtos
        if ($user->isLoja()) {
            return $product->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determina se o usuário pode deletar um produto.
     */
    public function delete(User $user, Product $product): bool
    {
        // Admin pode deletar qualquer produto
        if ($user->isAdmin()) {
            return true;
        }

        // Loja pode deletar apenas seus próprios produtos
        if ($user->isLoja()) {
            return $product->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determina se o usuário pode restaurar um produto.
     */
    public function restore(User $user, Product $product): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode deletar permanentemente um produto.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode ativar/desativar um produto.
     */
    public function toggle(User $user, Product $product): bool
    {
        // Admin pode ativar/desativar qualquer produto
        if ($user->isAdmin()) {
            return true;
        }

        // Loja pode ativar/desativar apenas seus próprios produtos
        if ($user->isLoja()) {
            return $product->user_id === $user->id;
        }

        return false;
    }
}
