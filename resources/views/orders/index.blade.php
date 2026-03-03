@extends('layouts.client')

@section('title', 'Meus Pedidos - ' . config('app.name'))

@section('content')
<div class="client-orders-container">
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

    <div class="client-orders-header">
        <h1 class="client-orders-title">📋 Meus Pedidos</h1>
        <a href="{{ route('client.home') }}" class="client-btn-secondary">← Voltar às Compras</a>
    </div>
                        @if ($orders->count() > 0)
                            <div class="client-orders-list">
                                @foreach ($orders as $order)
                                    <div class="client-order-card">
                                        <div class="client-order-header">
                                            <div>
                                                <span class="client-order-number">Pedido {{ $order->order_number }}</span>
                                                <span class="client-order-date">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            @php
                                                $statusBadgeClass = [
                                                    'pending' => 'warning',
                                                    'confirmed' => 'info',
                                                    'preparing' => 'primary',
                                                    'out_for_delivery' => 'info',
                                                    'delivered' => 'success',
                                                    'cancelled' => 'danger',
                                                ];
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
                                                {{ $statusLabels[$order->status] ?? ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </div>

                                        <div class="client-order-body">
                                            <div class="client-order-info">
                                                <span class="client-order-label">Itens:</span>
                                                <span class="client-order-value">{{ $order->items->count() }} {{ $order->items->count() === 1 ? 'item' : 'itens' }}</span>
                                            </div>
                                            <div class="client-order-info">
                                                <span class="client-order-label">Total:</span>
                                                <span class="client-order-value client-order-total">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                                            </div>
                                            <div class="client-order-info">
                                                <span class="client-order-label">Pagamento:</span>
                                                @php
                                                    $paymentMethods = [
                                                        'credit_card' => 'Cartão Crédito',
                                                        'debit_card' => 'Cartão Débito',
                                                        'pix' => 'PIX',
                                                        'cash' => 'Dinheiro',
                                                    ];
                                                @endphp
                                                <span class="client-order-value">{{ $paymentMethods[$order->payment_method] ?? ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                                            </div>
                                        </div>

                                        <div class="client-order-footer">
                                            <a href="{{ route('orders.show', $order) }}" class="client-btn-primary">
                                                Ver Detalhes
                                            </a>
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
                                @endforeach
                            </div>

                            <!-- Paginação -->
                            @if ($orders->hasPages())
                                <div class="client-pagination-section">
                                    {{ $orders->links('pagination::tailwind') }}
                                </div>
                            @endif
                        @else
                            <!-- Nenhum Pedido -->
                            <div class="client-empty-state">
                                <div class="client-empty-icon">📦</div>
                                <h3>Nenhum pedido realizado ainda</h3>
                                <p>Comece a fazer suas compras agora!</p>
                                <a href="{{ route('client.home') }}" class="client-btn-primary">
                                    🛒 Explorar Produtos
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
