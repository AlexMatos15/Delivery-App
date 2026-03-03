@extends('adminlte::page')

@section('title', 'Relatório de Vendas')

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
            <div class="col-md-8">
                <h2>Relatório de Vendas</h2>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('loja.reports.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Vendas Diárias - Últimos 30 dias</h3>
            </div>
            <div class="card-body">
                @if ($dailySales->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Quantidade Vendida</th>
                                    <th>Receita</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalQty = 0;
                                    $totalRevenue = 0;
                                @endphp
                                @foreach ($dailySales as $sale)
                                    <tr>
                                        <td><strong>{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}</strong></td>
                                        <td>{{ $sale->quantity }}</td>
                                        <td><strong>R$ {{ number_format($sale->revenue, 2, ',', '.') }}</strong></td>
                                    </tr>
                                    @php
                                        $totalQty += $sale->quantity;
                                        $totalRevenue += $sale->revenue;
                                    @endphp
                                @endforeach
                                <tr class="table-active">
                                    <td><strong>Total</strong></td>
                                    <td><strong>{{ $totalQty }}</strong></td>
                                    <td><strong>R$ {{ number_format($totalRevenue, 2, ',', '.') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Nenhuma venda registrada nos últimos 30 dias.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
