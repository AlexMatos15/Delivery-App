@extends('adminlte::page')

@section('title', 'Meus Pedidos')

@section('content')
    <div class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Histórico de Pedidos</h3>
                    </div>
                    <div class="card-body">
                        @if ($orders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Número</th>
                                            <th>Data</th>
                                            <th>Itens</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Pagamento</th>
                                            <th class="text-right">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>
                                                    <strong>{{ $order->order_number }}</strong>
                                                </td>
                                                <td>
                                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                                </td>
                                                <td>
                                                    {{ $order->items->count() }} {{ $order->items->count() === 1 ? 'item' : 'itens' }}
                                                </td>
                                                <td>
                                                    <strong class="text-success">
                                                        R$ {{ number_format($order->total, 2, ',', '.') }}
                                                    </strong>
                                                </td>
                                                <td>
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
                                                <td>
                                                    @php
                                                        $paymentMethods = [
                                                            'credit_card' => 'Cartão Crédito',
                                                            'debit_card' => 'Cartão Débito',
                                                            'pix' => 'PIX',
                                                            'cash' => 'Dinheiro',
                                                        ];
                                                    @endphp
                                                    {{ $paymentMethods[$order->payment_method] ?? ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                                                </td>
                                                <td class="text-right">
                                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i> Ver
                                                    </a>
                                                    @if (in_array($order->status, ['pending', 'confirmed']))
                                                        <form method="POST" action="{{ route('orders.cancel', $order) }}" style="display: inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" 
                                                                    onclick="return confirm('Tem certeza que deseja cancelar este pedido?')"
                                                                    class="btn btn-sm btn-danger">
                                                                <i class="fas fa-times"></i> Cancelar
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Paginação -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    Mostrando {{ $orders->firstItem() }} a {{ $orders->lastItem() }} de {{ $orders->total() }} pedidos
                                </div>
                                {{ $orders->links('pagination::bootstrap-4') }}
                            </div>
                        @else
                            <!-- Nenhum Pedido -->
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                <h4>Nenhum pedido realizado ainda</h4>
                                <p class="text-muted mb-3">Comece a fazer suas compras agora!</p>
                                <a href="{{ route('products.index') }}" class="btn btn-primary">
                                    <i class="fas fa-shopping-bag"></i> Explorar Produtos
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
