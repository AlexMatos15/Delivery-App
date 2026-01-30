<x-layouts.cliente>
    <div class="container">
        <div class="row mb-5">
            <div class="col-12">
                <h1 class="h2">Bem-vindo, {{ Auth::user()->name }}!</h1>
                <p class="text-muted">Explore nosso cardápio e faça seu pedido</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-5">
            <div class="col-md-4 mb-3">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-shopping-bag fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Cardápio</h5>
                        <p class="card-text">Veja nossos produtos</p>
                        <a href="/products" class="btn btn-primary">Navegar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-shopping-cart fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Meu Carrinho</h5>
                        <p class="card-text">Revise seu pedido</p>
                        <a href="/cliente/cart" class="btn btn-success">Ver Carrinho</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-box fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Meus Pedidos</h5>
                        <p class="card-text">Acompanhe seus pedidos</p>
                        <a href="/cliente/orders" class="btn btn-info">Ver Pedidos</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Products Section -->
        <div class="row">
            <div class="col-12">
                <h3 class="h4 mb-4">Produtos em Destaque</h3>
            </div>
        </div>

        <div class="row">
            @forelse ($featuredProducts ?? [] as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if ($product->promotional_price)
                                        <span class="h5 text-success">R$ {{ number_format($product->promotional_price, 2, ',', '.') }}</span>
                                        <small class="text-muted"><del>R$ {{ number_format($product->price, 2, ',', '.') }}</del></small>
                                    @else
                                        <span class="h5">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="/products/{{ $product->id }}" class="btn btn-primary btn-sm w-100">Ver Detalhes</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">Nenhum produto disponível no momento.</div>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.cliente>
