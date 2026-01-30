@extends('adminlte::page')

@section('title', 'Gerenciar Pedidos')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Filtrar Pedidos</h3>
                </div>
                <form method="GET" action="{{ route('admin.orders.index') }}" class="form-horizontal">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Pesquisar (Nº do Pedido ou Cliente)</label>
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Pesquisar...">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">Todos os Status</option>
                                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                                        <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>Preparando</option>
                                        <option value="out_for_delivery" {{ request('status') === 'out_for_delivery' ? 'selected' : '' }}>Saiu para Entrega</option>
                                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Entregue</option>
                                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Método de Pagamento</label>
                                    <select name="payment_method" class="form-control">
                                        <option value="">Todos os Métodos</option>
                                        <option value="credit_card" {{ request('payment_method') === 'credit_card' ? 'selected' : '' }}>Cartão de Crédito</option>
                                        <option value="debit_card" {{ request('payment_method') === 'debit_card' ? 'selected' : '' }}>Cartão de Débito</option>
                                        <option value="pix" {{ request('payment_method') === 'pix' ? 'selected' : '' }}>PIX</option>
                                        <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Dinheiro</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Limpar
                        </a>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pedidos</h3>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Pedido #</th>
                                <th>Cliente</th>
                                <th>Loja</th>
                                <th>Data</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Pagamento</th>
                                <th>Total</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>
                                        <div>{{ $order->user->name }}</div>
                                        <small class="text-muted">{{ $order->user->email }}</small>
                                    </td>
                                    <td>{{ $order->shop->name }}</td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
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
                                        <span class="badge badge-{{ $statusBadgeClass[$order->status] ?? 'secondary' }}">
                                            {{ $statusLabels[$order->status] ?? ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div>
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
                                                $paymentMethods = [
                                                    'credit_card' => 'Cartão de Crédito',
                                                    'debit_card' => 'Cartão de Débito',
                                                    'pix' => 'PIX',
                                                    'cash' => 'Dinheiro',
                                                ];
                                            @endphp
                                            <span class="badge badge-{{ $paymentBadgeClass[$order->payment_status] ?? 'secondary' }}">
                                                {{ $paymentLabels[$order->payment_status] ?? ucfirst($order->payment_status) }}
                                            </span>
                                            <br>
                                            <small>{{ $paymentMethods[$order->payment_method] ?? ucfirst(str_replace('_', ' ', $order->payment_method)) }}</small>
                                        </div>
                                    </td>
                                    <td><strong>R$ {{ number_format($order->total, 2, ',', '.') }}</strong></td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x text-muted"></i>
                                        <p class="mt-2">Nenhum pedido encontrado</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($orders->hasPages())
                    <div class="card-footer">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
