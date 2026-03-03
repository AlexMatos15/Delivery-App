@extends('layouts.client')

@section('title', 'Editar Perfil')

@section('content')
<div class="client-container">
    <!-- Atalhos Rápidos -->
    <div class="client-section">
        <h3 class="client-section-title">⚡ Atalhos Rápidos</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
            <a href="{{ route('addresses.index') }}" class="client-btn secondary" style="text-align: center; text-decoration: none;">
                📍 Gerenciar Endereços
            </a>
            <a href="{{ route('orders.index') }}" class="client-btn secondary" style="text-align: center; text-decoration: none;">
                📦 Meus Pedidos
            </a>
        </div>
    </div>

    <!-- Atualizar Informações -->
    <div class="client-section">
        <h3 class="client-section-title">✏️ Informações do Perfil</h3>
        @include('profile.partials.update-profile-information-form')
    </div>

    <!-- Alterar Senha -->
    <div class="client-section">
        <h3 class="client-section-title">🔒 Alterar Senha</h3>
        @include('profile.partials.update-password-form')
    </div>
</div>
@endsection
