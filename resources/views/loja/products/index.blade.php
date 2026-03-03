@extends('adminlte::page')

@section('title', 'Produtos da Loja')

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
                <h2 class="mb-0">Meus Produtos</h2>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('loja.products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Novo Produto
                </a>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-cube"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total de Produtos</span>
                        <span class="info-box-number">{{ $stats['total_products'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Ativos</span>
                        <span class="info-box-number">{{ $stats['active_products'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-pause-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Inativos</span>
                        <span class="info-box-number">{{ $stats['inactive_products'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Estoque Baixo</span>
                        <span class="info-box-number">{{ $stats['low_stock'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Produtos -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista de Produtos</h3>
            </div>

            <div class="card-body">
                @if ($products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 60px">Imagem</th>
                                    <th>Nome</th>
                                    <th>Categoria</th>
                                    <th>Preço</th>
                                    <th>Estoque</th>
                                    <th>Status</th>
                                    <th style="width: 120px">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="height: 40px;">
                                            @else
                                                <div class="bg-light text-center p-2" style="height: 40px; width: 40px;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $product->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $product->category->name }}</span>
                                        </td>
                                        <td>
                                            <strong>R$ {{ number_format($product->price, 2, ',', '.') }}</strong>
                                            @if ($product->promotional_price)
                                                <br>
                                                <small class="text-success">Promoção: R$ {{ number_format($product->promotional_price, 2, ',', '.') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                                {{ $product->stock }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $product->is_active ? 'success' : 'secondary' }}">
                                                {{ $product->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('loja.products.edit', $product) }}" class="btn btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('loja.products.destroy', $product) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-3">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Nenhum produto cadastrado. <a href="{{ route('loja.products.create') }}">Criar primeiro produto</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
