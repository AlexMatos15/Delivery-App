@extends('adminlte::page')

@section('title', 'Detalhes do Pedido - ' . $order->order_number)

@section('adminlte_css')
    @php
        config(['adminlte.layout_topnav' => true]);
    @endphp
@stop

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('loja.orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Informações do Pedido -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title">Pedido {{ $order->order_number }}</h3>
                    </div>

                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Cliente:</strong> {{ $order->customer->name }}</p>
                                <p><strong>Email:</strong> {{ $order->customer->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Data:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                <p><strong>Status Atual:</strong>
                                    <span class="badge badge-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <hr>

                        <!-- Itens do Pedido -->
                        <h5 class="mb-3">Itens do Pedido</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Quantidade</th>
                                        <th>Preço Unitário</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item->product_name }}</strong>
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                                            <td><strong>R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <hr>

                        <!-- Totais -->
                        <div class="row justify-content-end">
                            <div class="col-md-4">
                                <div class="row mb-2">
                                    <div class="col-8"><strong>Subtotal:</strong></div>
                                    <div class="col-4 text-right">R$ {{ number_format($order->items->sum(fn($i) => $i->price * $i->quantity), 2, ',', '.') }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-8"><strong>Taxa de Entrega:</strong></div>
                                    <div class="col-4 text-right">R$ {{ number_format($order->delivery_fee ?? 5.00, 2, ',', '.') }}</div>
                                </div>
                                <div class="row border-top pt-2">
                                    <div class="col-8"><h5>Total:</h5></div>
                                    <div class="col-4 text-right"><h5>R$ {{ number_format($order->total_amount, 2, ',', '.') }}</h5></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gerenciar Pedido -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title">Gerenciar Pedido</h3>
                    </div>

                    <div class="card-body">
                        <!-- Endereço de Entrega -->
                        <h6 class="mb-3"><strong>Endereço de Entrega</strong></h6>
                        @if ($order->address)
                            <div class="alert alert-light">
                                <p class="mb-1"><strong>{{ $order->address->label }}</strong></p>
                                <p class="mb-1">{{ $order->address->street }}, {{ $order->address->number }}</p>
                                @if ($order->address->complement)
                                    <p class="mb-1">{{ $order->address->complement }}</p>
                                @endif
                                <p class="mb-1">{{ $order->address->neighborhood }}, {{ $order->address->city }} - {{ $order->address->state }}</p>
                                <p class="mb-0">CEP: {{ $order->address->zip_code }}</p>
                            </div>
                        @endif

                        <hr>

                        <!-- Atualizar Status -->
                        @if (!in_array($order->status, ['delivered', 'cancelled']))
                            <h6 class="mb-3"><strong>Atualizar Status</strong></h6>
                            <form action="{{ route('loja.orders.update-status', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="form-group">
                                    <select name="status" class="form-control">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pendente</option>
                                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                                        <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>Preparando</option>
                                        <option value="out_for_delivery" {{ $order->status === 'out_for_delivery' ? 'selected' : '' }}>Saiu para Entrega</option>
                                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Entregue</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-save"></i> Atualizar Status
                                </button>
                            </form>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-lock"></i> Pedido {{ $order->status === 'delivered' ? 'entregue' : 'cancelado' }} - não pode ser modificado.
                            </div>
                        @endif

                        <hr>

                        <!-- Informações de Pagamento -->
                        <h6 class="mb-3"><strong>Pagamento</strong></h6>
                        <div class="alert alert-light">
                            <p class="mb-0"><strong>Método:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'Não definido')) }}</p>
                            <p class="mb-0"><strong>Status:</strong> <span class="badge badge-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($order->payment_status) }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
