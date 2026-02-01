@extends('adminlte::page')

@section('title', 'Dashboard da Loja')
@section('adminlte_css')
    @php
        config(['adminlte.layout_topnav' => true]);
    @endphp
@stop
@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h2>Dashboard da Loja</h2>
                <p class="text-muted">Bem-vindo, {{ $loja->name }}!</p>
            </div>
        </div>

        <!-- Menu de Atalhos Rápidos -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <a href="{{ route('loja.orders.index') }}" class="btn btn-lg btn-primary btn-block mb-2">
                                    <i class="fas fa-shopping-bag"></i>
                                    <p>Meus Pedidos</p>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('loja.products.index') }}" class="btn btn-lg btn-info btn-block mb-2">
                                    <i class="fas fa-cube"></i>
                                    <p>Meus Produtos</p>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('loja.reports.dashboard') }}" class="btn btn-lg btn-success btn-block mb-2">
                                    <i class="fas fa-chart-bar"></i>
                                    <p>Relatórios</p>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('profile.edit') }}" class="btn btn-lg btn-warning btn-block mb-2">
                                    <i class="fas fa-user"></i>
                                    <p>Meu Perfil</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações Iniciais -->
        <div class="row">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-cube"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Produtos Cadastrados</span>
                        <span class="info-box-number">{{ $loja->products()->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-shopping-bag"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pedidos Recebidos</span>
                        <span class="info-box-number">{{ \App\Models\Order::whereHas('items', function ($q) use ($loja) { $q->whereIn('product_id', $loja->products()->pluck('id')); })->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Estoque Baixo</span>
                        <span class="info-box-number">{{ $loja->products()->where('stock', '<=', 10)->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pedidos Pendentes</span>
                        <span class="info-box-number">{{ \App\Models\Order::whereHas('items', function ($q) use ($loja) { $q->whereIn('product_id', $loja->products()->pluck('id')); })->where('status', 'pending')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title">Próximas Ações</h3>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="{{ route('loja.products.create') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-plus"></i> Adicionar novo produto
                            </a>
                            <a href="{{ route('loja.orders.index') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-list"></i> Visualizar pedidos
                            </a>
                            <a href="{{ route('loja.products.index') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-cube"></i> Gerenciar produtos
                            </a>
                            <a href="{{ route('loja.reports.dashboard') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-chart-line"></i> Ver relatórios financeiros
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title">Informações Úteis</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="fas fa-info-circle text-info"></i>
                                <strong>Visibilidade:</strong> Seus produtos aparecem na loja pública quando ativados.
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-info-circle text-info"></i>
                                <strong>Pedidos:</strong> Aqui você vê todos os pedidos que contêm seus produtos.
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-info-circle text-info"></i>
                                <strong>Estoque:</strong> Monitore o estoque dos seus produtos para não perder vendas.
                            </li>
                            <li>
                                <i class="fas fa-info-circle text-info"></i>
                                <strong>Relatórios:</strong> Acompanhe vendas e métricas financeiras em tempo real.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
