@extends('layouts.client')

@section('title', 'Dashboard - ' . config('app.name'))

@section('content')
<div class="client-products-container">
    <!-- Header -->
    <div class="client-products-header">
        <div>
            <h1 class="client-products-title">👋 Bem-vindo de volta, {{ auth()->user()->name }}!</h1>
            <p class="client-products-subtitle">
                Seu app favorito de delivery. Peça agora!
            </p>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="client-dashboard-stats">
        <div class="client-stat-card">
            <div class="client-stat-icon">📦</div>
            <div class="client-stat-content">
                <span class="client-stat-label">Total de Pedidos</span>
                {{-- Deprecated: client dashboard removed in new flow. --}}
        
        <div class="client-quicklinks-grid">
            <a href="{{ route('client.home') }}" class="client-quicklink-card">
                <span class="client-quicklink-icon">🛍️</span>
                <span class="client-quicklink-text">Explorar Produtos</span>
            </a>

            <a href="{{ route('cart.index') }}" class="client-quicklink-card">
                <span class="client-quicklink-icon">🛒</span>
                <span class="client-quicklink-text">Seu Carrinho</span>
            </a>

            <a href="{{ route('orders.index') }}" class="client-quicklink-card">
                <span class="client-quicklink-icon">📦</span>
                <span class="client-quicklink-text">Meus Pedidos</span>
            </a>

            <a href="{{ route('addresses.index') }}" class="client-quicklink-card">
                <span class="client-quicklink-icon">📍</span>
                <span class="client-quicklink-text">Meus Endereços</span>
            </a>

            <a href="{{ route('profile.show') }}" class="client-quicklink-card">
                <span class="client-quicklink-icon">👤</span>
                <span class="client-quicklink-text">Meu Perfil</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" style="display: contents;">
                @csrf
                <button type="submit" class="client-quicklink-card" style="cursor: pointer; border: none; background: none; padding: 0;">
                    <span class="client-quicklink-icon">🚪</span>
                    <span class="client-quicklink-text">Sair</span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Script para feedback ao adicionar ao carrinho -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartForms = document.querySelectorAll('.client-product-form');
    
    cartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const btn = form.querySelector('button');
            const originalText = btn.textContent;
            btn.textContent = '✓ Adicionado!';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.textContent = originalText;
                btn.disabled = false;
            }, 2000);
        });
    });
});
</script>
@endsection
