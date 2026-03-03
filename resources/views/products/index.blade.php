@extends('layouts.client')

@section('title', 'Produtos - ' . config('app.name'))

@section('content')
<div class="client-products-container">
    <!-- Cabeçalho -->
    <div class="client-products-header">
        <h1 class="client-products-title">🍔 Produtos</h1>
        <p class="client-products-subtitle">Explore nosso cardápio delicioso</p>
    </div>

    <!-- Barra de Busca e Filtros -->
    <div class="client-section" style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('products.index') }}" style="display: grid; grid-template-columns: 1fr; gap: 12px;">
            <!-- Busca -->
            <div class="client-form-group">
                <input type="text" name="search" class="client-form-input" 
                       placeholder="🔍 Buscar produto..." 
                       value="{{ request('search') }}"
                       style="padding: 14px;">
            </div>

            <!-- Filtros e Busca (grid responsivo) -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <select name="category" class="client-form-input">
                        <option value="">📁 Todas as categorias</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                        <input type="checkbox" name="featured" value="1" 
                               {{ request('featured') ? 'checked' : '' }}>
                        <span style="font-weight: 600; font-size: 14px;">⭐ Destaques</span>
                    </label>
                </div>
            </div>

            <!-- Botão Buscar -->
            <button type="submit" class="client-btn client-btn-primary" style="width: 100%;">
                🔍 Buscar
            </button>
            
            @if (request()->hasAny(['search', 'category', 'featured']))
                <a href="{{ route('products.index') }}" class="client-btn client-btn-secondary" 
                   style="width: 100%; text-decoration: none; text-align: center;">
                    ✕ Limpar Filtros
                </a>
            @endif
        </form>
    </div>

    <!-- Grid de Produtos -->
    @if ($products->isEmpty())
        <div class="client-alert client-alert-info">
            <strong>😕 Nenhum produto encontrado</strong>
            <p>Tente mudar os filtros e tentar novamente</p>
        </div>
    @else
        <div class="client-products-grid">
            @foreach ($products as $product)
                <div class="client-product-card">
                    <!-- Imagem -->
                    <div class="client-product-image">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span>🍔</span>
                        @endif
                        
                        @if ($product->is_featured)
                            <div style="position: absolute; top: 8px; right: 8px; background: var(--client-warning); color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 700;">
                                ⭐ Destaque
                            </div>
                        @endif
                    </div>

                    <!-- Corpo -->
                    <div class="client-product-body">
                        <h3 class="client-product-name">{{ $product->name }}</h3>

                        <!-- Preço -->
                        <div class="client-product-price">
                            @if ($product->promotional_price)
                                <span class="client-product-price-old">
                                    R$ {{ number_format($product->price, 2, ',', '.') }}
                                </span>
                            @endif
                            <span>R$ {{ number_format($product->getCurrentPrice(), 2, ',', '.') }}</span>
                        </div>

                        <!-- Estoque -->
                        @if ($product->stock <= 0)
                            <div class="client-product-stock out">
                                ❌ Fora de estoque
                            </div>
                        @elseif ($product->stock < 5)
                            <div class="client-product-stock low">
                                ⚠️ Apenas {{ $product->stock }} disponível(is)
                            </div>
                        @else
                            <div class="client-product-stock">
                                ✅ Disponível
                            </div>
                        @endif

                        <!-- Ações -->
                        <div class="client-product-actions">
                            @auth
                                @if (auth()->user()->isClient())
                                    @if ($product->stock > 0)
                                        <form method="POST" action="{{ route('cart.add', $product) }}" 
                                              style="width: 100%;">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="client-product-btn client-product-btn-add">
                                                Adicionar
                                            </button>
                                        </form>
                                    @else
                                        <button class="client-product-btn client-product-btn-add" 
                                                disabled
                                                title="Produto sem estoque">
                                            Indisponível
                                        </button>
                                    @endif
                                @else
                                    <button class="client-product-btn client-product-btn-add" 
                                            disabled
                                            title="Apenas clientes podem comprar">
                                        Apenas clientes
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="client-product-btn client-product-btn-add"
                                   style="text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                                    Entrar
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Info de paginação -->
        <div style="text-align: center; padding: 20px 0; color: var(--client-text-light); margin-bottom: 40px;">
            <p>
                Mostrando {{ $products->count() }} de 
                {{ \App\Models\Product::where('is_active', true)->where('stock', '>', 0)->count() }} 
                produtos disponíveis
            </p>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartForms = document.querySelectorAll('form[action*="cart/add"]');
    cartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const btn = form.querySelector('button');
            const originalText = btn.textContent;
            btn.textContent = '✓ Adicionado!';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.textContent = originalText;
                btn.disabled = false;
            }, 2000);
        });
    });
});
</script>
@endsection
