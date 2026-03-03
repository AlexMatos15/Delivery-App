@extends('layouts.client')

@section('title', 'Finalizar Pedido - ' . config('app.name'))

@section('content')
<div class="client-checkout-wrapper">
    <div class="client-checkout-header">
        <h1>📋 Finalizar Seu Pedido</h1>
    </div>

    <form method="POST" action="{{ route('client.store') }}" class="client-checkout-form">
        @csrf

        <!-- SEÇÃO 1: ENDEREÇO DE ENTREGA -->
        <div class="client-checkout-section">
            <h3 class="client-checkout-section-title">
                📍 Onde Entregamos?
            </h3>

            @if ($addresses->isEmpty())
                <div class="client-checkout-alert error">
                    <strong>Nenhum endereço cadastrado</strong>
                    <p>Você precisa adicionar um endereço antes de fazer o pedido.</p>
                </div>
                <a href="{{ route('addresses.create') }}" class="client-btn primary">
                    + Adicionar Endereço
                </a>
            @else
                <div class="client-checkout-options">
                    @foreach ($addresses as $address)
                        <label class="client-checkout-option">
                            <input type="radio" name="address_id" value="{{ $address->id }}"
                                   {{ (isset($defaultAddress) && $defaultAddress->id === $address->id) ? 'checked' : '' }}>
                            <div class="client-checkout-option-content">
                                <div class="client-checkout-option-header">
                                    <span class="client-checkout-option-title">
                                        {{ $address->label ?? 'Endereço' }}
                                    </span>
                                    @if (isset($defaultAddress) && $defaultAddress->id === $address->id)
                                        <span class="client-checkout-badge">⭐ Padrão</span>
                                    @endif
                                </div>
                                <div class="client-checkout-option-text">
                                    {{ $address->street }}, {{ $address->number }}
                                    @if ($address->complement)
                                        — {{ $address->complement }}
                                    @endif
                                    <br>
                                    {{ $address->neighborhood }} • {{ $address->city }}, {{ $address->state }}
                                    <br>
                                    CEP: {{ $address->zip_code }}
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                @if ($errors->has('address_id'))
                    <p class="client-checkout-error">{{ $errors->first('address_id') }}</p>
                @endif

                <a href="{{ route('addresses.create') }}" class="client-btn secondary small">
                    + Adicionar novo endereço
                </a>
            @endif
        </div>

        <!-- SEÇÃO 2: MÉTODO DE PAGAMENTO -->
        <div class="client-checkout-section">
            <h3 class="client-checkout-section-title">
                💳 Como Você vai Pagar?
            </h3>

            <div class="client-checkout-options">
                <label class="client-checkout-option">
                    <input type="radio" name="payment_method" value="pix" required>
                    <div class="client-checkout-option-content">
                        <span class="client-checkout-option-title">💜 Pix</span>
                        <span class="client-checkout-option-text">Transferência instantânea</span>
                    </div>
                </label>

                <label class="client-checkout-option">
                    <input type="radio" name="payment_method" value="credit_card">
                    <div class="client-checkout-option-content">
                        <span class="client-checkout-option-title">💳 Cartão de Crédito</span>
                        <span class="client-checkout-option-text">Parcele em até 3x</span>
                    </div>
                </label>

                <label class="client-checkout-option">
                    <input type="radio" name="payment_method" value="debit_card">
                    <div class="client-checkout-option-content">
                        <span class="client-checkout-option-title">🏧 Cartão de Débito</span>
                        <span class="client-checkout-option-text">Débito imediato</span>
                    </div>
                </label>

                <label class="client-checkout-option">
                    <input type="radio" name="payment_method" value="cash">
                    <div class="client-checkout-option-content">
                        <span class="client-checkout-option-title">💵 Dinheiro na Entrega</span>
                        <span class="client-checkout-option-text">Pague quando receber</span>
                    </div>
                </label>
            </div>

            @if ($errors->has('payment_method'))
                <p class="client-checkout-error">{{ $errors->first('payment_method') }}</p>
            @endif
        </div>

        <!-- SEÇÃO 3: OBSERVAÇÕES -->
        <div class="client-checkout-section">
            <h3 class="client-checkout-section-title">
                📝 Observações (Opcional)
            </h3>

            <div class="client-checkout-field">
                <label class="client-checkout-label">Deixe uma mensagem para o entregador</label>
                <textarea name="notes" class="client-checkout-textarea" 
                          placeholder="Ex: Toque a campainha, tenho cachorro na porta..."
                          rows="3"></textarea>
                @if ($errors->has('notes'))
                    <p class="client-checkout-error">{{ $errors->first('notes') }}</p>
                @endif
            </div>
        </div>

        <!-- SEÇÃO 4: RESUMO DO PEDIDO -->
        <div class="client-checkout-section">
            <h3 class="client-checkout-section-title">
                📦 Seu Pedido
            </h3>

            <div class="client-checkout-summary">
                <!-- Itens -->
                @foreach ($cartItems as $item)
                    <div class="client-checkout-summary-item">
                        <span>
                            {{ $item['name'] }} <small>(x{{ $item['quantity'] }})</small>
                        </span>
                        <strong>R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</strong>
                    </div>
                @endforeach

                <!-- Divisor -->
                <div class="client-checkout-summary-divider"></div>

                <!-- Subtotal -->
                <div class="client-checkout-summary-item">
                    <span>Subtotal:</span>
                    <strong>R$ {{ number_format($subtotal, 2, ',', '.') }}</strong>
                </div>

                <!-- Taxa de Entrega -->
                <div class="client-checkout-summary-item">
                    <span>📦 Taxa de Entrega:</span>
                    <strong>R$ {{ number_format($deliveryFee, 2, ',', '.') }}</strong>
                </div>

                <!-- Total -->
                <div class="client-checkout-summary-total">
                    <span>Total:</span>
                    <strong>R$ {{ number_format($total, 2, ',', '.') }}</strong>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="client-checkout-actions">
            <a href="{{ route('cart.index') }}" class="client-btn secondary">
                ← Voltar ao Carrinho
            </a>
            <button type="submit" class="client-btn primary large">
                ✓ Confirmar Pedido
            </button>
        </div>

        <!-- Disclaimer -->
        <div class="client-checkout-disclaimer">
            <small>
                ℹ️ Ao confirmar o pedido, você está autorizando o pagamento e a entrega para o endereço selecionado.
                O pedido será enviado imediatamente para a loja.
            </small>
        </div>
    </form>
</div>

<script>
// Validação básica antes de enviar
document.querySelector('form').addEventListener('submit', function(e) {
    const addressSelected = document.querySelector('input[name="address_id"]:checked');
    const paymentSelected = document.querySelector('input[name="payment_method"]:checked');

    if (!addressSelected) {
        e.preventDefault();
        alert('Selecione um endereço para entrega');
        return false;
    }

    if (!paymentSelected) {
        e.preventDefault();
        alert('Selecione um método de pagamento');
        return false;
    }
});
</script>
@endsection
