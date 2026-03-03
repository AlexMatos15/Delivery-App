@extends('adminlte::page')

@section('title', 'Pedidos da Loja')

@section('adminlte_css')
    @php
        config([
            'adminlte.layout_topnav' => true,
            'adminlte.classes_body' => 'loja',
            'adminlte.menu' => [
                [
                    'text' => 'Painel',
                    'url' => 'loja/dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                ],
                [
                    'text' => 'Pedidos',
                    'url' => 'loja/orders',
                    'icon' => 'fas fa-box',
                ],
                [
                    'text' => 'Produtos',
                    'url' => 'loja/products',
                    'icon' => 'fas fa-cube',
                ],
            ],
        ]);
    @endphp
@stop

@section('css')
    @include('loja.partials.styles')
@stop

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-0">Meus Pedidos</h2>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-shopping-bag"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total de Pedidos</span>
                        <span class="info-box-number">{{ $stats['total_orders'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pendentes</span>
                        <span class="info-box-number">{{ $stats['pending_orders'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-primary"><i class="fas fa-cog"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Em Processamento</span>
                        <span class="info-box-number">{{ $stats['processing_orders'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Entregues</span>
                        <span class="info-box-number">{{ $stats['completed_orders'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Pedidos -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista de Pedidos</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                @if ($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Número do Pedido</th>
                                    <th>Cliente</th>
                                    <th>Total de Itens</th>
                                    <th>Valor Total</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>
                                            <strong>{{ $order->order_number }}</strong>
                                        </td>
                                        <td>
                                            {{ $order->customer->name }}
                                        </td>
                                        <td>
                                            {{ $order->items->count() }}
                                        </td>
                                        <td>
                                            <strong>R$ {{ number_format($order->total_amount, 2, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td>
                                            <a href="{{ route('loja.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-3">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Nenhum pedido encontrado.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
