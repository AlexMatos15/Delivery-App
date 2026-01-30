@extends('adminlte::page')

@section('title', 'Pedido #' . $order->order_number)

@section('content')
    <div class="row">
        <div class="col-md-8">
            <!-- Itens do Pedido -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Itens do Pedido</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th class="text-center">Qtd</th>
                                <th class="text-right">Preço</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-right">R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                                    <td class="text-right"><strong>R$ {{ number_format($item->subtotal, 2, ',', '.') }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                                <td class="text-right">R$ {{ number_format($order->subtotal, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Taxa de Entrega:</strong></td>
                                <td class="text-right">R$ {{ number_format($order->delivery_fee, 2, ',', '.') }}</td>
                            </tr>
                            <tr class="table-active">
                                <td colspan="3" class="text-right"><h4 class="mb-0"><strong>Total:</strong></h4></td>
                                <td class="text-right"><h4 class="mb-0 text-success"><strong>R$ {{ number_format($order->total, 2, ',', '.') }}</strong></h4></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Endereço de Entrega -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Endereço de Entrega</h3>
                </div>
                <div class="card-body">
                    <address>
                        <strong>{{ $order->address->label }}</strong><br>
                        {{ $order->address->street }}, {{ $order->address->number }}
                        @if ($order->address->complement)
                            <br>{{ $order->address->complement }}
                        @endif
                        <br>{{ $order->address->neighborhood }}<br>
                        {{ $order->address->city }}, {{ $order->address->state }}<br>
                        {{ $order->address->zip_code }}
                        @if ($order->address->reference_point)
                            <br><br><strong>Referência:</strong> {{ $order->address->reference_point }}
                        @endif
                    </address>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Informações do Cliente -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Cliente</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Nome</small>
                        <strong>{{ $order->user->name }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Email</small>
                        <strong>{{ $order->user->email }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Loja</small>
                        <strong>{{ $order->shop->name }}</strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">Data do Pedido</small>
                        <strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong>
                    </div>
                </div>
            </div>

            <!-- Status do Pedido -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Status do Pedido</h3>
                </div>
                <div class="card-body">
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
                    <div class="mb-3">
                        <span class="badge badge-lg badge-{{ $statusBadgeClass[$order->status] ?? 'secondary' }}">
                            {{ $statusLabels[$order->status] ?? ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>

                    @if (!in_array($order->status, ['delivered', 'cancelled']))
                        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label>Atualizar Status</label>
                                <select name="status" class="form-control">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pendente</option>
                                    <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                                    <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>Preparando</option>
                                    <option value="out_for_delivery" {{ $order->status === 'out_for_delivery' ? 'selected' : '' }}>Saiu para Entrega</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Entregue</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> Atualizar Status
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Status de Pagamento -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Pagamento</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Método</small>
                        @php
                            $paymentMethods = [
                                'credit_card' => 'Cartão de Crédito',
                                'debit_card' => 'Cartão de Débito',
                                'pix' => 'PIX',
                                'cash' => 'Dinheiro',
                            ];
                        @endphp
                        <strong>{{ $paymentMethods[$order->payment_method] ?? ucfirst(str_replace('_', ' ', $order->payment_method)) }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Status</small>
                        @php
                            $paymentBadgeClass = [
                                'pending' => 'warning',
                                'paid' => 'success',
                                'failed' => 'danger',
                                'refunded' => 'secondary',
                            ];
                            $paymentLabels = [
                                'pending' => 'Pendente',
                                'paid' => 'Pago',
                                'failed' => 'Falhou',
                                'refunded' => 'Reembolsado',
                            ];
                        @endphp
                        <span class="badge badge-lg badge-{{ $paymentBadgeClass[$order->payment_status] ?? 'secondary' }}">
                            {{ $paymentLabels[$order->payment_status] ?? ucfirst($order->payment_status) }}
                        </span>
                    </div>

                    @if (!in_array($order->payment_status, ['paid', 'refunded']))
                        <form method="POST" action="{{ route('admin.orders.update-payment', $order) }}">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label>Atualizar Pagamento</label>
                                <select name="payment_status" class="form-control">
                                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pendente</option>
                                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Pago</option>
                                    <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Falhou</option>
                                    <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-save"></i> Atualizar Pagamento
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Botão Voltar -->
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-block">
                <i class="fas fa-arrow-left"></i> Voltar para Pedidos
            </a>
        </div>
    </div>
@endsection
