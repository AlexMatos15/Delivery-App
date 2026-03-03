@extends('layouts.client')

@section('title', 'Meu Perfil')

@section('content')
<div class="client-container">
    <div class="client-section">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 class="client-section-title">👤 Meu Perfil</h2>
            <a href="{{ route('profile.edit') }}" class="client-btn primary">
                ✏️ Editar Perfil
            </a>
        </div>

        <div class="client-profile-content">
            <div class="client-profile-item">
                <span class="client-profile-label">Nome:</span>
                <span class="client-profile-value">{{ $user->name }}</span>
            </div>

            <div class="client-profile-item">
                <span class="client-profile-label">Email:</span>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span class="client-profile-value">{{ $user->email }}</span>
                    @if ($user->email_verified_at)
                        <span class="client-badge success">✓ Verificado</span>
                    @else
                        <span class="client-badge warning">⚠ Não verificado</span>
                    @endif
                </div>
            </div>

            <div class="client-profile-item">
                <span class="client-profile-label">Tipo de Conta:</span>
                @php
                    $typeLabels = [
                        'client' => 'Cliente',
                        'shop' => 'Loja',
                        'admin' => 'Administrador',
                    ];
                    $typeBadgeClass = [
                        'client' => 'primary',
                        'shop' => 'success',
                        'admin' => 'error',
                    ];
                @endphp
                <span class="client-badge {{ $typeBadgeClass[$user->type] ?? 'secondary' }}">
                    {{ $typeLabels[$user->type] ?? ucfirst($user->type) }}
                </span>
            </div>

            <div class="client-profile-item">
                <span class="client-profile-label">Status:</span>
                @if ($user->is_active)
                    <span class="client-badge success">✓ Ativo</span>
                @else
                    <span class="client-badge error">✕ Inativo</span>
                @endif
            </div>

            <div class="client-profile-item">
                <span class="client-profile-label">Data de Cadastro:</span>
                <span class="client-profile-value">{{ $user->created_at->format('d/m/Y H:i') }}</span>
            </div>

            <div class="client-profile-item">
                <span class="client-profile-label">Última Atualização:</span>
                <span class="client-profile-value">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px; flex-wrap: wrap;">
            <a href="{{ route('profile.edit') }}" class="client-btn primary">
                ✏️ Editar Informações
            </a>
            <a href="{{ route('password.request') }}" class="client-btn secondary">
                🔒 Alterar Senha
            </a>
            <a href="{{ route('cart.index') }}" class="client-btn secondary">
                ← Voltar
            </a>
        </div>
    </div>

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
</div>
@endsection
