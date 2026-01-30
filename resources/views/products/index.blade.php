@extends('adminlte::page')

@section('title', 'Produtos')

@section('content')
    <div class="container-fluid">
        <!-- Busca e Filtros -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Buscar Produtos</h3>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('products.index') }}" class="row">
                            <!-- Busca -->
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Pesquisar</label>
                                    <input type="text" 
                                           name="search" 
                                           value="{{ request('search') }}" 
                                           placeholder="Buscar produtos..." 
                                           class="form-control">
                                </div>
                            </div>
                            
                            <!-- Categoria -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Categoria</label>
                                    <select name="category" class="form-control">
                                        <option value="">Todas as Categorias</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Destaque -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="featured" name="featured" value="1" {{ request('featured') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="featured">
                                            Apenas Destaques
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <!-- Botões -->
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" form="search-form" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                                @if(request()->hasAny(['search', 'category', 'featured']))
                                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Limpar
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid de Produtos -->
        @if ($products->count() > 0)
            <div class="row">
                @foreach ($products as $product)
                    <div class="col-md-3 col-sm-6 col-12 mb-4">
                        <div class="card h-100">
                            <!-- Imagem do Produto -->
                            <div style="position: relative; height: 250px; overflow: hidden;">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; background-color: #e9ecef; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                                
                                @if ($product->is_featured)
                                    <span class="badge badge-warning" style="position: absolute; top: 10px; left: 10px;">
                                        <i class="fas fa-star"></i> Destaque
                                    </span>
                                @endif
                                
                                @if ($product->isOnSale())
                                    <span class="badge badge-danger" style="position: absolute; top: 10px; right: 10px;">
                                        PROMOÇÃO
                                    </span>
                                @endif
                            </div>

                            <!-- Informações do Produto -->
                            <div class="card-body pb-2">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="text-muted small">{{ $product->category->name }}</p>
                                
                                <!-- Preço -->
                                @if ($product->isOnSale())
                                    <p class="text-muted small" style="text-decoration: line-through;">
                                        R$ {{ number_format($product->price, 2, ',', '.') }}
                                    </p>
                                    <h4 class="text-success font-weight-bold">
                                        R$ {{ number_format($product->promotional_price, 2, ',', '.') }}
                                    </h4>
                                @else
                                    <h4 class="text-dark font-weight-bold">
                                        R$ {{ number_format($product->price, 2, ',', '.') }}
                                    </h4>
                                @endif

                                <!-- Estoque -->
                                <p class="text-muted small mb-3">
                                    <i class="fas fa-box"></i> {{ $product->stock }} em estoque
                                </p>

                                <!-- Formulário de Adicionar ao Carrinho -->
                                <form method="POST" action="{{ route('cart.add', $product) }}">
                                    @csrf
                                    <div class="input-group input-group-sm mb-2">
                                        <input type="number" 
                                               name="quantity" 
                                               value="1" 
                                               min="1" 
                                               max="{{ $product->stock }}" 
                                               class="form-control" 
                                               placeholder="Qtd">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginação -->
            <div class="row">
                <div class="col-12">
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @else
            <!-- Nenhum Produto -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h4>Nenhum produto encontrado</h4>
                        <p class="text-muted">Tente ajustar seus filtros ou critérios de busca</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
