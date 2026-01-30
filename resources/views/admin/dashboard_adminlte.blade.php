@extends('adminlte::page')

@section('title', 'Painel Administrativo')

@section('content')
    <div class="row">
        <!-- Total de Pedidos -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-box"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total de Pedidos</span>
                    <span class="info-box-number">{{ $totalOrders }}</span>
                    <small class="text-muted">{{ $pendingOrders }} pendentes • {{ $deliveredOrders }} entregues</small>
                </div>
            </div>
        </div>

        <!-- Total de Clientes -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Clientes</span>
                    <span class="info-box-number">{{ $totalUsers }}</span>
                </div>
            </div>
        </div>

        <!-- Total de Produtos -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-cube"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Produtos</span>
                    <span class="info-box-number">{{ $totalProducts }}</span>
                    <small class="text-muted">{{ $totalCategories }} categorias</small>
                </div>
            </div>
        </div>

        <!-- Receita -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-money-bill-wave"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Receita</span>
                    <span class="info-box-number">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</span>
                    <small class="text-muted">R$ {{ number_format($pendingRevenue, 2, ',', '.') }} pendente</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gráfico de Pedidos por Status -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pedidos por Status</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $statusLabels = [
                                'pending' => 'Pendente',
                                'confirmed' => 'Confirmado',
                                'preparing' => 'Preparando',
                                'out_for_delivery' => 'Saiu para Entrega',
                                'delivered' => 'Entregue',
                                'cancelled' => 'Cancelado',
                            ];
                            $statusColors = [
                                'pending' => '#ffc107',
                                'confirmed' => '#17a2b8',
                                'preparing' => '#6f42c1',
                                'out_for_delivery' => '#007bff',
                                'delivered' => '#28a745',
                                'cancelled' => '#dc3545',
                            ];
                        @endphp
                        @foreach ($statusLabels as $status => $label)
                            @php
                                $count = $ordersByStatus[$status] ?? 0;
                                $percentage = $totalOrders > 0 ? ($count / $totalOrders) * 100 : 0;
                            @endphp
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="font-weight-bold">{{ $label }}</span>
                                    <span class="badge badge-lg" style="background-color: {{ $statusColors[$status] }}">{{ $count }}</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar" style="width: {{ $percentage }}%; background-color: {{ $statusColors[$status] }}"></div>
                                </div>
                                <small class="text-muted">{{ number_format($percentage, 1) }}%</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Produtos Mais Vendidos -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Produtos Mais Vendidos</h3>
                </div>
                <div class="card-body">
                    @if ($topProducts && $topProducts->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach ($topProducts as $product)
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <strong>{{ $product->name }}</strong>
                                        <span class="badge badge-primary badge-pill">{{ $product->order_items_count }}</span>
                                    </div>
                                    <small class="text-muted">{{ $product->category->name }}</small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">Sem vendas ainda</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pedidos Recentes -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pedidos Recentes</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">Ver Todos</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Número do Pedido</th>
                                <th>Cliente</th>
                                <th>Data</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentOrders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
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
                                    <td><strong>R$ {{ number_format($order->total, 2, ',', '.') }}</strong></td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-xs btn-info">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Nenhum pedido ainda</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
