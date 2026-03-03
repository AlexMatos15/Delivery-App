@extends('layouts.client')

@section('title', 'Carrinho - ' . config('app.name'))

@section('content')
<div class="client-section" style="margin-bottom: 40px;">
    <h2 class="client-section-title">🛒 Meu Carrinho</h2>

    @if (empty($cart))
        <!-- Carrinho Vazio -->
        <div class="client-cart-empty">
            <span class="client-cart-empty-icon">🛒</span>
            <p class="client-cart-empty-message">Seu carrinho está vazio</p>
            <a href="{{ route('client.home') }}" class="client-btn client-btn-lg">
                Ver Produtos
            </a>
        </div>
    @else
        <!-- Carrinho com Itens -->
        <div class="client-cart-container">
            <!-- Lista de Itens -->
            <div class="client-cart-items">
                @foreach ($cart as $productId => $item)
                    <div class="client-cart-item">
                        <!-- Imagem -->
                        <div class="client-cart-item-image">
                            @if (isset($item['image']))
                                <img src="{{ asset('storage/' . $item['image']) }}" 
                                     alt="{{ $item['name'] }}">
                            @else
                                <span>🍔</span>
                            @endif
                        </div>

                        <!-- Detalhes -->
                        <div class="client-cart-item-details">
                            <h3 class="client-cart-item-name">{{ $item['name'] }}</h3>
                            <div class="client-cart-item-price">
                                R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}
                                <small style="color: var(--client-text-light); margin-left: 8px;">
                                    (R$ {{ number_format($item['price'], 2, ',', '.') }} x {{ $item['quantity'] }})
                                </small>
                            </div>

                            <!-- Controles -->
                            <div class="client-cart-item-controls">
                                <form method="POST" 
                                      action="{{ route('cart.update', $productId) }}"
                                      style="display: inline-flex; gap: 8px; align-items: center;">
                                    @csrf
                                    @method('PATCH')

                                    <button type="button" class="client-cart-quantity-btn"
                                            onclick="decrementQuantity(this)">−</button>
                                    
                                    <input type="number" 
                                           name="quantity" 
                                           value="{{ $item['quantity'] }}"
                                           min="1" 
                                           max="99"
                                           class="client-cart-quantity"
                                           onchange="this.form.submit()"
                                           style="width: 50px; border: none; background: transparent; text-align: center; font-weight: 600;">
                                    
                                    <button type="button" class="client-cart-quantity-btn"
                                            onclick="incrementQuantity(this)">+</button>
                                </form>

                                <!-- Remove Button -->
                                <form method="POST" 
                                      action="{{ route('cart.remove', $productId) }}"
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="client-cart-item-remove">
                                        Remover
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Botão Limpar Carrinho -->
                <div style="padding-top: 16px; border-top: 1px solid var(--client-border);">
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="client-btn client-btn-secondary client-btn-sm"
                                onclick="return confirm('Tem certeza que deseja limpar o carrinho?')">
                            Limpar Carrinho
                        </button>
                    </form>
                </div>
            </div>

            <!-- Resumo -->
            <div class="client-cart-summary">
                <div class="client-cart-summary-title">Resumo do Pedido</div>

                <!-- Linhas do Resumo -->
                <div class="client-cart-summary-row client-cart-subtotal">
                    <span>Subtotal:</span>
                    <strong>R$ {{ number_format($total, 2, ',', '.') }}</strong>
                </div>

                <div class="client-cart-summary-row">
                    <span>Taxa de Entrega:</span>
                    <strong>R$ 5,00</strong>
                </div>

                <div class="client-cart-summary-row" style="padding: 12px 0; margin-top: 12px;">
                    <span style="font-size: 16px;">Total:</span>
                    <div class="client-cart-total">
                        R$ {{ number_format($total + 5.00, 2, ',', '.') }}
                    </div>
                </div>

                <!-- Botão Checkout -->
                <a href="{{ route('client.checkout') }}" class="client-btn client-btn-lg">
                    Finalizar Pedido →
                </a>

                <!-- Botão Continuar Comprando -->
                <a href="{{ route('client.home') }}" class="client-btn client-btn-secondary client-btn-lg"
                   style="margin-top: 12px; background: transparent; border: 2px solid var(--client-primary); color: var(--client-primary);">
                    ← Continuar Comprando
                </a>
            </div>
        </div>
    @endif
</div>

<script>
function incrementQuantity(btn) {
    const input = btn.parentElement.querySelector('input[name="quantity"]');
    let value = parseInt(input.value) || 1;
    value = Math.min(value + 1, 99);
    input.value = value;
    input.form.submit();
}

function decrementQuantity(btn) {
    const input = btn.parentElement.querySelector('input[name="quantity"]');
    let value = parseInt(input.value) || 1;
    value = Math.max(value - 1, 1);
    input.value = value;
    input.form.submit();
}
</script>
@endsection
