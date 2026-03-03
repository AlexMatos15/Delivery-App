@extends('layouts.client')

@section('title', 'Pedido Confirmado - ' . config('app.name'))

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <!-- Success Animation -->
    <div class="client-order-success">
        <span class="client-order-success-icon">✅</span>
        
        <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 12px;">
            Pedido Confirmado!
        </h2>
        
        <p class="client-order-message">
            Seu pedido foi criado com sucesso e está a caminho da loja
        </p>
    </div>

    <!-- Detalhes do Pedido -->
    <div class="client-section">
        <h3 class="client-section-title">
            📦 Número do Pedido
        </h3>
        
        <div style="text-align: center; padding: 20px; background: var(--client-bg-light); border-radius: 8px;">
            <div class="client-order-number">{{ $order->order_number }}</div>
            <p class="client-text-muted">
                Salve este número para rastrear seu pedido
            </p>
        </div>
    </div>

    <!-- Status -->
    <div class="client-section">
        <h3 class="client-section-title">Status do Pedido</h3>
        
        <div style="padding: 16px; background: var(--client-bg-light); border-radius: 8px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <span style="font-size: 24px;">
                    ⏳
                </span>
                <div>
                    <strong>Pendente</strong>
                    <p class="client-text-muted" style="font-size: 12px;">
                        A loja está preparando seu pedido
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Endereço de Entrega -->
    <div class="client-section">
        <h3 class="client-section-title">📍 Endereço de Entrega</h3>
        
        <div style="padding: 16px; background: var(--client-bg-light); border-radius: 8px;">
            <p style="margin: 0;">
                <strong>{{ $order->address->label ?? 'Endereço' }}</strong><br>
                {{ $order->address->street }}, {{ $order->address->number }}
            </p>
            @if ($order->address->complement)
                <p style="margin: 8px 0 0 0; color: var(--client-text-light); font-size: 14px;">
                    {{ $order->address->complement }}
                </p>
            @endif
            <p style="margin: 8px 0 0 0; color: var(--client-text-light); font-size: 14px;">
                {{ $order->address->neighborhood }} • {{ $order->address->city }}, {{ $order->address->state }}<br>
                CEP: {{ $order->address->zip_code }}
            </p>
        </div>
    </div>

    <!-- Itens do Pedido -->
    <div class="client-section">
        <h3 class="client-section-title">📋 Itens do Pedido</h3>
        
        @foreach ($order->items as $item)
            <div class="client-cart-summary-row" style="padding: 12px 0;">
                <span>
                    {{ $item->product_name }}<br>
                    <small style="color: var(--client-text-light);">
                        x{{ $item->quantity }} 
                        @ R$ {{ number_format($item->product_price, 2, ',', '.') }}
                    </small>
                </span>
                <strong>
                    R$ {{ number_format($item->product_price * $item->quantity, 2, ',', '.') }}
                </strong>
            </div>
        @endforeach
    </div>

    <!-- Resumo Financeiro -->
    <div class="client-section">
        <h3 class="client-section-title">💰 Resumo Financeiro</h3>
        
        <div class="client-order-detail-row">
            <span>Subtotal:</span>
            <strong>R$ {{ number_format($order->subtotal, 2, ',', '.') }}</strong>
        </div>
        
        <div class="client-order-detail-row">
            <span>Taxa de Entrega:</span>
            <strong>R$ {{ number_format($order->delivery_fee, 2, ',', '.') }}</strong>
        </div>
        
        <div class="client-order-detail-row" style="font-size: 16px; border-top: 2px solid var(--client-border); padding-top: 12px; margin-top: 12px;">
            <span><strong>Total:</strong></span>
            <strong style="color: var(--client-primary);">
                R$ {{ number_format($order->total, 2, ',', '.') }}
            </strong>
        </div>
    </div>

    <!-- Método de Pagamento -->
    <div class="client-section">
        <h3 class="client-section-title">💳 Pagamento</h3>
        
        <div style="padding: 16px; background: var(--client-bg-light); border-radius: 8px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <span style="font-size: 24px;">
                    @switch($order->payment_method)
                        @case('pix')
                            💜
                            @break
                        @case('credit_card')
                            💳
                            @break
                        @case('debit_card')
                            🏧
                            @break
                        @case('cash')
                            💵
                            @break
                        @default
                            ❓
                    @endswitch
                </span>
                <div>
                    <strong>
                        @switch($order->payment_method)
                            @case('pix')
                                Pix
                                @break
                            @case('credit_card')
                                Cartão de Crédito
                                @break
                            @case('debit_card')
                                Cartão de Débito
                                @break
                            @case('cash')
                                Dinheiro na Entrega
                                @break
                            @default
                                Método não definido
                        @endswitch
                    </strong>
                    <p class="client-text-muted" style="font-size: 12px;">
                        Status:
                        <span class="client-badge warning">
                            Pendente
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Observações (se houver) -->
    @if ($order->notes)
        <div class="client-section">
            <h3 class="client-section-title">📝 Observações</h3>
            <p style="color: var(--client-text-light); line-height: 1.6;">
                {{ $order->notes }}
            </p>
        </div>
    @endif

    <!-- Próximas Ações -->
    <div class="client-section" style="background: #f0fdf4; border-left: 4px solid var(--client-success);">
        <h3 class="client-section-title" style="color: #166534;">
            ✅ O que vem agora?
        </h3>
        
        <ol style="padding-left: 20px; color: #166534; line-height: 1.8;">
            <li>A loja receberá sua solicitação</li>
            <li>Seu pedido será preparado</li>
            <li>Um entregador será designado</li>
            <li>Você acompanhará a entrega em tempo real</li>
            <li>Produto entregue com sucesso!</li>
        </ol>
    </div>

    <!-- Botões de Ação -->
    <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 40px;">
        <a href="{{ route('orders.index') }}" class="client-btn client-btn-lg">
            👁️ Acompanhar Pedidos
        </a>
        <a href="{{ route('client.home') }}" class="client-btn client-btn-secondary client-btn-lg">
            ← Voltar às Compras
        </a>
    </div>

    <!-- Dúvidas -->
    <div class="client-alert client-alert-info">
        <strong>Precisa de ajuda?</strong>
        <p style="margin-top: 8px; font-size: 14px;">
            Você pode acompanhar seu pedido em "Meus Pedidos" ou entrar em contato com o suporte.
        </p>
    </div>
</div>
@endsection
