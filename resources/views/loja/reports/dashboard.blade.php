@extends('adminlte::page')

@section('title', 'Relatórios - Dashboard Financeiro')

@section('adminlte_css')
    @php
        config(['adminlte.layout_topnav' => true]);
    @endphp
@stop

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h2>Dashboard Financeiro</h2>
            </div>
        </div>

        <!-- KPIs Principais -->
        <div class="row">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Receita Total</span>
                        <span class="info-box-number">R$ {{ number_format($stats['total_revenue'], 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-primary"><i class="fas fa-shopping-bag"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total de Pedidos</span>
                        <span class="info-box-number">{{ $stats['total_orders'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-chart-bar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Ticket Médio</span>
                        <span class="info-box-number">R$ {{ number_format($stats['average_order_value'], 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pedidos Entregues</span>
                        <span class="info-box-number">{{ $stats['completed_orders'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 mb-4">
            <div class="col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-gradient-primary"><i class="fas fa-calendar-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Receita - Últimos 30 dias</span>
                        <span class="info-box-number">R$ {{ number_format($stats['revenue_last_month'], 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-gradient-info"><i class="fas fa-calendar-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pedidos - Últimos 30 dias</span>
                        <span class="info-box-number">{{ $stats['orders_last_month'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status dos Pedidos -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Distribuição por Status</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-text">Pendentes</span>
                                    <span class="info-box-number text-warning">{{ $ordersByStatus['pending'] }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-text">Confirmados</span>
                                    <span class="info-box-number text-info">{{ $ordersByStatus['confirmed'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-text">Preparando</span>
                                    <span class="info-box-number text-primary">{{ $ordersByStatus['preparing'] }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-text">Em Entrega</span>
                                    <span class="info-box-number text-secondary">{{ $ordersByStatus['out_for_delivery'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-text">Entregues</span>
                                    <span class="info-box-number text-success">{{ $ordersByStatus['delivered'] }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-text">Cancelados</span>
                                    <span class="info-box-number text-danger">{{ $ordersByStatus['cancelled'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produtos Mais Vendidos -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Produtos Mais Vendidos</h3>
                    </div>
                    <div class="card-body">
                        @if ($topProducts->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Produto</th>
                                            <th>Quantidade Vendida</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topProducts as $item)
                                            <tr>
                                                <td>{{ $item['product']->name }}</td>
                                                <td>
                                                    <span class="badge badge-success">{{ $item['quantity'] }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Nenhuma venda registrada ainda.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Links Rápidos para Relatórios Detalhados -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Relatórios Detalhados</h3>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('loja.reports.sales') }}" class="btn btn-primary">
                            <i class="fas fa-chart-line"></i> Relatório de Vendas
                        </a>
                        <a href="{{ route('loja.reports.customers') }}" class="btn btn-info">
                            <i class="fas fa-users"></i> Relatório de Clientes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
