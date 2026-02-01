@extends('adminlte::page')

@section('title', 'Detalhes do Pedido')

@section('adminlte_css')
    @php
        config(['adminlte.layout_topnav' => true]);
    @endphp
@stop

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

        <div class="card">
            <!-- Order Header -->
            <div class="card-header border-bottom">
                <div class="row">
                    <div class="col-md-8">
                        <h2 class="mb-2">{{ $order->order_number }}</h2>
                        <p class="text-muted mb-0">Realizado em {{ $order->created_at->format('d/m/Y \à\s H:i') }}</p>
                    </div>
                    <div class="col-md-4 text-right">
                        @php
                            $statusColors = [
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
                        <span class="badge badge-{{ $statusColors[$order->status] ?? 'secondary' }} badge-lg p-2">
                            {{ $statusLabels[$order->status] ?? $order->status }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Order Items -->
                <div class="mb-4">
                    <h4 class="mb-3"><i class="fas fa-shopping-bag mr-2"></i>Produtos</h4>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr class="border-bottom">
                                        <td width="80">
                                            @if ($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                     alt="{{ $item->product_name }}" 
                                                     class="img-thumbnail" width="70">
                                            @else
                                                <div class="bg-light rounded p-2 text-center" style="width: 70px; height: 70px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <h6 class="mb-0">{{ $item->product_name }}</h6>
                                            <small class="text-muted">R$ {{ number_format($item->product_price, 2, ',', '.') }} x {{ $item->quantity }}</small>
                                        </td>
                                        <td class="text-right">
                                            <strong>R$ {{ number_format($item->subtotal, 2, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title"><i class="fas fa-map-marker-alt mr-2"></i>Endereço de Entrega</h6>
                                <p class="mb-0">{{ $order->delivery_address }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title"><i class="fas fa-credit-card mr-2"></i>Pagamento</h6>
                                <p class="mb-1"><strong>Método:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                                <p class="mb-0"><strong>Status:</strong> <span class="badge badge-info">{{ ucfirst($order->payment_status) }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($order->notes)
                    <div class="mb-4">
                        <h6><i class="fas fa-sticky-note mr-2"></i>Observações</h6>
                        <div class="alert alert-info mb-0">{{ $order->notes }}</div>
                    </div>
                @endif

                <!-- Order Summary -->
                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>R$ {{ number_format($order->subtotal, 2, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Taxa de Entrega:</span>
                                    <span>R$ {{ number_format($order->delivery_fee, 2, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between border-top pt-3 font-weight-bold">
                                    <span>Total:</span>
                                    <span class="text-success">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card-footer">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar aos Pedidos
                </a>
                @if (in_array($order->status, ['pending', 'confirmed']))
                    <form method="POST" action="{{ route('orders.cancel', $order) }}" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                onclick="return confirm('Tem certeza que deseja cancelar este pedido?')"
                                class="btn btn-danger">
                            <i class="fas fa-times"></i> Cancelar Pedido
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
