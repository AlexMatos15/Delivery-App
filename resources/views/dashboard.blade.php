@extends('adminlte::page')

@section('title', 'Meu Painel')

@section('content')
    <div class="container-fluid">
        <!-- Bem-vindo -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">
                            <i class="fas fa-user-circle"></i> Bem-vindo, {{ Auth::user()->name }}!
                        </h3>
                        <p class="text-muted">Você está logado na plataforma de entrega.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu rápido -->
        <div class="row">
            <div class="col-md-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ Auth::user()->orders_count ?? 0 }}</h3>
                        <p>Meus Pedidos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <a href="{{ route('orders.index') }}" class="small-box-footer">
                        Ver tudo <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ Auth::user()->addresses_count ?? 0 }}</h3>
                        <p>Endereços</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <a href="{{ route('addresses.index') }}" class="small-box-footer">
                        Gerenciar <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>Catálogo</h3>
                        <p>Produtos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <a href="{{ route('products.index') }}" class="small-box-footer">
                        Navegar <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>Carrinho</h3>
                        <p>Itens</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <a href="{{ route('cart.index') }}" class="small-box-footer">
                        Meu Carrinho <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Atalhos rápidos -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ações Rápidas</h3>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-search"></i> Explorar Produtos
                        </a>
                        <a href="{{ route('cart.index') }}" class="btn btn-info btn-block mb-2">
                            <i class="fas fa-shopping-cart"></i> Ver Carrinho
                        </a>
                        <a href="{{ route('addresses.index') }}" class="btn btn-success btn-block">
                            <i class="fas fa-address-book"></i> Meus Endereços
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informações da Conta</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Nome</small>
                            <strong>{{ Auth::user()->name }}</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Email</small>
                            <strong>{{ Auth::user()->email }}</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Tipo de Conta</small>
                            @php
                                $typeLabels = ['client' => 'Cliente', 'shop' => 'Loja', 'admin' => 'Administrador'];
                            @endphp
                            <strong>{{ $typeLabels[Auth::user()->type] ?? 'Desconhecido' }}</strong>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="btn btn-warning btn-block">
                            <i class="fas fa-edit"></i> Editar Perfil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
