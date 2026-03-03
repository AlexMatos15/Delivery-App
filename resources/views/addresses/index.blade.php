@extends('layouts.client')

@section('title', 'Meus Endereços')

@section('content')
    <div class="client-container">
        @if (session('status'))
            <div class="client-alert success">
                ✓ {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="client-alert error">
                ⚠ {{ session('error') }}
            </div>
        @endif

        <div class="client-section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h2 class="client-section-title">📍 Meus Endereços</h2>
                <a href="{{ route('addresses.create') }}" class="client-btn primary">
                    + Adicionar Endereço
                </a>
            </div>

            @if ($addresses->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px;">
                    @foreach ($addresses as $address)
                        <div class="client-address-card {{ $address->is_default ? 'default' : '' }}">
                            <div class="client-address-header">
                                <h3>{{ $address->label }}</h3>
                                @if ($address->is_default)
                                    <span class="client-badge primary">⭐ Padrão</span>
                                @endif
                            </div>
                            <div class="client-address-body">
                                <p><strong>{{ $address->street }}, {{ $address->number }}</strong></p>
                                @if ($address->complement)
                                    <p>{{ $address->complement }}</p>
                                @endif
                                <p>{{ $address->neighborhood }}</p>
                                <p>{{ $address->city }} - {{ $address->state }}</p>
                                <p>CEP: {{ $address->zip_code }}</p>
                                @if ($address->reference)
                                    <p class="client-text-light">📍 {{ $address->reference }}</p>
                                @endif
                            </div>
                            <div class="client-address-actions">
                                @if (!$address->is_default)
                                    <form method="POST" action="{{ route('addresses.set-default', $address) }}" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="client-btn secondary client-btn-sm">
                                            ⭐ Padrão
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('addresses.edit', $address) }}" class="client-btn secondary client-btn-sm">
                                    ✏️ Editar
                                </a>

                                <form method="POST" action="{{ route('addresses.destroy', $address) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Tem certeza que deseja deletar este endereço?')"
                                            class="client-btn danger client-btn-sm">
                                        🗑️ Deletar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="client-empty-state">
                    <div style="font-size: 64px; margin-bottom: 16px;">📍</div>
                    <h3>Nenhum endereço cadastrado</h3>
                    <p>Adicione um endereço de entrega para fazer seu primeiro pedido.</p>
                    <a href="{{ route('addresses.create') }}" class="client-btn primary client-btn-lg" style="margin-top: 24px;">
                        + Adicionar Endereço
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
