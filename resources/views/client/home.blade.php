@extends('layouts.client')

@section('title', 'Início - ' . config('app.name'))

@section('content')
<div class="client-products-container">
    <!-- Cabeçalho com Saudação -->
    <div class="client-products-header">
        <div>
            <h1 class="client-products-title">👋 Olá, {{ auth()->user()->name }}!</h1>
            <p class="client-products-subtitle">
                Escolha seus pratos favoritos e aproveite as melhores ofertas
            </p>
        </div>
        <div class="client-products-actions">
            <button type="button" class="client-btn-secondary" id="store-modal-open">
                Escolher Unidade
            </button>
            <div class="client-products-subtitle" style="margin-top: 8px;">
                Unidade selecionada:
                <strong>
                    {{ $selectedStore ? $selectedStore->name : 'Nenhuma' }}
                </strong>
            </div>
        </div>
    </div>

    @if (!$selectedStore)
        <div class="client-alert client-alert-warning">
            <div class="client-alert-icon">🏪</div>
            <div>
                <strong>Escolha uma unidade para ver os produtos</strong>
                <p>Selecione a loja desejada para continuar.</p>
            </div>
        </div>
    @endif

    <!-- Filtros e Busca -->
    @if ($selectedStore)
        <div class="client-filters-section">
            <form method="GET" action="{{ route('client.home') }}" class="client-filters-form">
                <!-- Busca -->
                <div class="client-search-box">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="🔍 Buscar produtos..." 
                        value="{{ request('search') }}"
                        class="client-search-input"
                    >
                </div>

                <!-- Filtro de Categoria -->
                @if ($categories->count() > 0)
                    <div class="client-filter-categories">
                        <select name="category" class="client-filter-select" onchange="this.form.submit()">
                            <option value="">📂 Todas as Categorias</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Botão de Busca -->
                <button type="submit" class="client-filter-btn">
                    Buscar
                </button>
            </form>
        </div>
    @endif

    @if ($selectedStore && $products->isEmpty())
        <!-- Produtos não encontrados -->
        <div class="client-alert client-alert-info">
            <div class="client-alert-icon">🔍</div>
            <div>
                <strong>Nenhum produto encontrado</strong>
                <p>Tente ajustar seus filtros ou volte em breve!</p>
            </div>
        </div>
    @elseif ($selectedStore)
        <!-- Grid de Produtos -->
        <div class="client-products-grid">
            @foreach ($products as $product)
                <div class="client-product-card">
                    <!-- Container de Imagem com Badge de Destaque -->
                    <div class="client-product-image-wrapper">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}"
                                 class="client-product-image">
                        @else
                            <div class="client-product-image-placeholder">🍔</div>
                        @endif
                        
                        @if ($product->is_featured)
                            <span class="client-product-badge-featured">⭐ Destaque</span>
                        @endif

                        @if ($product->promotional_price && $product->promotional_price < $product->price)
                            <span class="client-product-badge-sale">
                                -{{ round((($product->price - $product->promotional_price) / $product->price) * 100) }}%
                            </span>
                        @endif
                    </div>

                    <!-- Corpo do Card -->
                    <div class="client-product-body">
                        <!-- Nome e Categoria -->
                        <h3 class="client-product-name">{{ $product->name }}</h3>
                        @if ($product->category)
                            <p class="client-product-category">{{ $product->category->name }}</p>
                        @endif

                        <!-- Preço -->
                        <div class="client-product-price-section">
                            @if ($product->promotional_price && $product->promotional_price < $product->price)
                                <span class="client-product-price-old">
                                    R$ {{ number_format($product->price, 2, ',', '.') }}
                                </span>
                                <span class="client-product-price-current">
                                    R$ {{ number_format($product->promotional_price, 2, ',', '.') }}
                                </span>
                            @else
                                <span class="client-product-price-current">
                                    R$ {{ number_format($product->price, 2, ',', '.') }}
                                </span>
                            @endif
                        </div>

                        <!-- Status de Estoque -->
                        @if ($product->stock <= 0)
                            <div class="client-product-stock out">
                                ❌ Fora de estoque
                            </div>
                        @elseif ($product->stock < 5)
                            <div class="client-product-stock low">
                                ⚠️ Apenas {{ $product->stock }} em estoque
                            </div>
                        @else
                            <div class="client-product-stock available">
                                ✅ Disponível
                            </div>
                        @endif

                        <!-- Botão Adicionar ao Carrinho -->
                        @auth
                            @if (auth()->user()->isClient())
                                @if ($product->stock > 0)
                                    <form method="POST" action="{{ route('cart.add', $product) }}" 
                                          class="client-product-form">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="client-product-btn-add">
                                            🛒 Adicionar
                                        </button>
                                    </form>
                                @else
                                    <button class="client-product-btn-add disabled" disabled>
                                        Indisponível
                                    </button>
                                @endif
                            @else
                                <button class="client-product-btn-add disabled" disabled 
                                        title="Apenas clientes podem comprar">
                                    Apenas clientes
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="client-product-btn-add" 
                               style="text-align: center; text-decoration: none; display: block;">
                                🔐 Entrar para Comprar
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        @if ($products->total() > 0)
            <div class="client-pagination-section">
                {{ $products->links('pagination::tailwind') }}
            </div>
        @endif
    @endif
</div>

<!-- Modal de Seleção de Unidade -->
<div class="client-modal" id="store-modal" aria-hidden="true">
    <div class="client-modal-backdrop"></div>
    <div class="client-modal-content">
        <h2 class="client-modal-title">Escolher Unidade</h2>
        <p class="client-modal-subtitle">Selecione a loja para ver os produtos disponíveis.</p>

        <form method="POST" action="{{ route('client.store.select') }}" class="client-modal-form">
            @csrf
            <select name="store_id" class="client-filter-select" required>
                <option value="" disabled selected>Selecione uma unidade</option>
                @foreach ($stores as $store)
                    <option value="{{ $store->id }}" {{ $selectedStore && $selectedStore->id === $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>

            @error('store_id')
                <div class="client-alert client-alert-danger" style="margin-top: 12px;">
                    {{ $message }}
                </div>
            @enderror

            <div class="client-modal-actions">
                <button type="submit" class="client-btn-primary">Confirmar</button>
                <button type="button" class="client-btn-secondary" id="store-modal-close">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<style>
    .client-modal {
        position: fixed;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    .client-modal.is-open {
        display: flex;
    }
    .client-modal-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
    }
    .client-modal-content {
        position: relative;
        background: #ffffff;
        border-radius: 12px;
        padding: 24px;
        width: min(520px, 92vw);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }
    .client-modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .client-modal-subtitle {
        color: #667085;
        margin-bottom: 16px;
    }
    .client-modal-actions {
        display: flex;
        gap: 12px;
        margin-top: 16px;
        justify-content: flex-end;
    }
</style>

<!-- Script para interatividade -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Feedback visual ao adicionar ao carrinho
    const cartForms = document.querySelectorAll('.client-product-form');
    
    cartForms.forEach(form => {
        form.addEventListener('submit', function() {
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

    // Auto-submit ao limpar busca
    const searchInput = document.querySelector('.client-search-input');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });
    }

    // Modal de unidade
    const modal = document.getElementById('store-modal');
    const openButton = document.getElementById('store-modal-open');
    const closeButton = document.getElementById('store-modal-close');
    const hasStore = {{ $selectedStore ? 'true' : 'false' }};

    const openModal = () => modal.classList.add('is-open');
    const closeModal = () => modal.classList.remove('is-open');

    if (!hasStore) {
        openModal();
    }

    if (openButton) {
        openButton.addEventListener('click', openModal);
    }

    if (closeButton) {
        closeButton.addEventListener('click', function() {
            if (hasStore) {
                closeModal();
            }
        });
    }
});
</script>
@endsection
