@extends('layouts.client')

@section('title', 'Detalhes do Pedido - ' . config('app.name'))

@section('content')
<div class="client-order-detail-container">
    @if (session('status'))
        <div class="client-alert client-alert-success">
            <div class="client-alert-icon">✓</div>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div class="client-alert client-alert-danger">
            <div class="client-alert-icon">✕</div>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <div class="client-order-header">
        <div>
            <h1 class="client-order-title">Pedido {{ $order->order_number }}</h1>
            <p class="client-order-date">Realizado em {{ $order->created_at->format('d/m/Y \à\s H:i') }}</p>
        </div>
        @php
            $statusLabels = [
                'pending' => 'Pendente',
                'confirmed' => 'Confirmado',
                'preparing' => 'Preparando',
                'out_for_delivery' => 'Saiu para Entrega',
                'delivered' => 'Entregue',
                'cancelled' => 'Cancelado',
            ];
        @endphp
        <span class="client-order-status-badge client-status-{{ $order->status }}">
            {{ $statusLabels[$order->status] ?? $order->status }}
        </span>
    </div>
    <!-- Order Items -->
    <div class="client-order-section">
        <h3 class="client-section-title">🛍️ Produtos</h3>
        @foreach ($order->items as $item)
            <div class="client-order-item">
                <div class="client-order-item-image">
                    @if ($item->product && $item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}">
                    @else
                        <div class="client-product-image-placeholder">🍔</div>
                    @endif
                </div>
                <div class="client-order-item-info">
                    <h4>{{ $item->product_name }}</h4>
                    <p>R$ {{ number_format($item->product_price, 2, ',', '.') }} x {{ $item->quantity }}</p>
                </div>
                <div class="client-order-item-total">
                    R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                </div>
            </div>
        @endforeach
    </div>

    <!-- Delivery Information -->
    <div class="client-order-section">
        <h3 class="client-section-title">📍 Endereço de Entrega</h3>
        <p>{{ $order->delivery_address }}</p>
    </div>

    <!-- Payment Information -->
    <div class="client-order-section">
        <h3 class="client-section-title">💳 Pagamento</h3>
        <div class="client-order-info">
            <span class="client-order-label">Método:</span>
            <span class="client-order-value">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
        </div>
        <div class="client-order-info">
            <span class="client-order-label">Status:</span>
            <span class="client-order-value">{{ ucfirst($order->payment_status) }}</span>
        </div>
    </div>

    @if ($order->notes)
        <div class="client-order-section">
            <h3 class="client-section-title">📝 Observações</h3>
            <p>{{ $order->notes }}</p>
        </div>
    @endif

    <!-- Order Summary -->
    <div class="client-order-summary">
        <div class="client-summary-row">
            <span>Subtotal:</span>
            <span>R$ {{ number_format($order->subtotal, 2, ',', '.') }}</span>
        </div>
        <div class="client-summary-row">
            <span>Taxa de Entrega:</span>
            <span>R$ {{ number_format($order->delivery_fee, 2, ',', '.') }}</span>
        </div>
        <div class="client-summary-row client-summary-total">
            <span>Total:</span>
            <span>R$ {{ number_format($order->total, 2, ',', '.') }}</span>
        </div>
    </div>

    <!-- Actions -->
    <div class="client-order-actions">
        <a href="{{ route('orders.index') }}" class="client-btn-secondary">← Voltar aos Pedidos</a>
        @if (in_array($order->status, ['pending', 'confirmed']))
            <form method="POST" action="{{ route('orders.cancel', $order) }}" style="display: inline;">
                @csrf
                @method('PATCH')
                <button type="submit" 
                        onclick="return confirm('Tem certeza que deseja cancelar este pedido?')"
                        class="client-btn-danger">
                    Cancelar Pedido
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
