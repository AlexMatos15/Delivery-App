@extends('adminlte::page')

@section('title', 'Carrinho de Compras')

@section('adminlte_css')
    @php
        // Se for loja, use menu da loja
        if (auth()->check() && auth()->user()->isShop()) {
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
        } else {
            // Cliente ou admin
            config(['adminlte.layout_topnav' => true]);
        }
    @endphp
@stop

@section('content')
    <div class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="row">
            @if (count($cart) > 0)
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Itens do Carrinho</h3>
                        </div>
                        <div class="card-body">
                            @foreach ($cart as $productId => $item)
                                <div class="row border-bottom pb-3 mb-3 align-items-center" data-product-id="{{ $productId }}">
                                    <!-- Imagem do Produto -->
                                    <div class="col-md-2">
                                        @if ($item['image'])
                                            <img src="{{ asset('storage/' . $item['image']) }}" 
                                                 alt="{{ $item['name'] }}" 
                                                 class="img-fluid rounded"
                                                 style="object-fit: cover; height: 100px;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                 style="height: 100px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Informações do Produto -->
                                    <div class="col-md-4">
                                        <h5>{{ $item['name'] }}</h5>
                                        <p class="text-muted mb-0">
                                            R$ {{ number_format($item['price'], 2, ',', '.') }}
                                        </p>
                                    </div>

                                    <!-- Controle de Quantidade -->
                                    <div class="col-md-3">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <button type="button" 
                                                        onclick="updateQuantity({{ $productId }}, {{ $item['quantity'] - 1 }})"
                                                        class="btn btn-outline-secondary"
                                                        {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="number" 
                                                   id="quantity-{{ $productId }}"
                                                   value="{{ $item['quantity'] }}" 
                                                   min="1"
                                                   onchange="updateQuantity({{ $productId }}, this.value)"
                                                   class="form-control text-center"
                                                   style="width: 60px;">
                                            <div class="input-group-append">
                                                <button type="button" 
                                                        onclick="updateQuantity({{ $productId }}, {{ $item['quantity'] + 1 }})"
                                                        class="btn btn-outline-secondary">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="col-md-2 text-right">
                                        <p id="subtotal-{{ $productId }}" class="font-weight-bold">
                                            R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}
                                        </p>
                                    </div>

                                    <!-- Remover -->
                                    <div class="col-md-1 text-right">
                                        <button type="button" 
                                                onclick="removeFromCart({{ $productId }})"
                                                class="btn btn-sm btn-danger"
                                                title="Remover">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Botões de Ação -->
                        <div class="card-footer">
                            <form method="POST" action="{{ route('cart.clear') }}" style="display: inline;" onsubmit="return confirm('Deseja limpar o carrinho?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-trash"></i> Limpar Carrinho
                                </button>
                            </form>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-arrow-left"></i> Continuar Comprando
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Resumo do Carrinho -->
                <div class="col-lg-4">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Resumo</h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-6"><strong>Subtotal:</strong></div>
                                <div class="col-6 text-right" id="subtotal-display">
                                    R$ {{ number_format($total, 2, ',', '.') }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Taxa de Entrega:</strong></div>
                                <div class="col-6 text-right">R$ 5,00</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6"><h5>Total:</h5></div>
                                <div class="col-6 text-right">
                                    <h5 id="cart-total" class="text-success font-weight-bold">
                                        R$ {{ number_format($total + 5, 2, ',', '.') }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('orders.checkout') }}" class="btn btn-success btn-block">
                                <i class="fas fa-check"></i> Ir para Checkout
                            </a>
                        </div>
                    </div>

                    <!-- Informações -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Informações</h3>
                        </div>
                        <div class="card-body small">
                            <p>
                                <i class="fas fa-info-circle"></i>
                                <strong>{{ count($cart) }}</strong> 
                                {{ count($cart) === 1 ? 'item' : 'itens' }} no carrinho
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-truck"></i>
                                Taxa de entrega fixa: <strong>R$ 5,00</strong>
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Carrinho Vazio -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                            <h4>Seu carrinho está vazio</h4>
                            <p class="text-muted mb-3">Adicione produtos para começar a comprar!</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-bag"></i> Explorar Produtos
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function updateQuantity(productId, quantity) {
            if (quantity < 1) return;

            fetch(`/cart/update/${productId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`quantity-${productId}`).value = quantity;
                    
                    const subtotalBRL = new Intl.NumberFormat('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    }).format(data.item_subtotal);
                    document.getElementById(`subtotal-${productId}`).textContent = subtotalBRL;
                    
                    const totalBRL = new Intl.NumberFormat('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    }).format(data.total + 5);
                    document.getElementById('cart-total').textContent = totalBRL;
                    
                    const subtotalDisplayBRL = new Intl.NumberFormat('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    }).format(data.total);
                    document.getElementById('subtotal-display').textContent = subtotalDisplayBRL;
                    
                    updateCartBadge();
                } else {
                    alert(data.message || 'Erro ao atualizar carrinho');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao atualizar carrinho');
            });
        }

        function removeFromCart(productId) {
            if (!confirm('Deseja remover este item do carrinho?')) {
                return;
            }

            fetch(`/cart/remove/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`[data-product-id="${productId}"]`).remove();
                    
                    if (data.cart_count === 0) {
                        location.reload();
                    } else {
                        const totalBRL = new Intl.NumberFormat('pt-BR', {
                            style: 'currency',
                            currency: 'BRL'
                        }).format(data.total + 5);
                        document.getElementById('cart-total').textContent = totalBRL;
                        
                        const subtotalDisplayBRL = new Intl.NumberFormat('pt-BR', {
                            style: 'currency',
                            currency: 'BRL'
                        }).format(data.total);
                        document.getElementById('subtotal-display').textContent = subtotalDisplayBRL;
                    }
                    
                    updateCartBadge();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao remover item');
            });
        }

        function updateCartBadge() {
            fetch('/cart/count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('cart-count');
                    if (badge) {
                        badge.textContent = data.count;
                        badge.style.display = data.count > 0 ? 'inline-block' : 'none';
                    }
                });
        }
    </script>
    @endpush
@endsection
