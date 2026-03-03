@extends('adminlte::page')

@section('title', 'Relatório de Clientes')

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
                <h2>Relatório de Clientes</h2>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('loja.reports.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Clientes com Maior Gasto</h3>
            </div>
            <div class="card-body">
                @if ($customers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Email</th>
                                    <th>Total de Pedidos</th>
                                    <th>Total Gasto</th>
                                    <th>Última Compra</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item['customer']->name }}</strong>
                                        </td>
                                        <td>{{ $item['customer']->email }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $item['orders_count'] }}</span>
                                        </td>
                                        <td>
                                            <strong>R$ {{ number_format($item['total_spent'], 2, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            {{ $item['last_order']->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Nenhum cliente registrado.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
