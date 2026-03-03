<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', config('app.name') . ' - Seu App de Delivery')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/client.css') }}">
</head>
<body class="client-app">
    <!-- Header Fixo -->
    <header class="client-header">
        <div class="client-header-content">
            <div class="client-logo">
                @auth
                    @if (auth()->user()->isClient())
                        <a href="{{ route('client.home') }}" class="client-logo-link">
                            <span class="client-logo-icon">🍔</span>
                            <span class="client-logo-text">{{ config('app.name') }}</span>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="client-logo-link">
                            <span class="client-logo-icon">🍔</span>
                            <span class="client-logo-text">{{ config('app.name') }}</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('welcome') }}" class="client-logo-link">
                        <span class="client-logo-icon">🍔</span>
                        <span class="client-logo-text">{{ config('app.name') }}</span>
                    </a>
                @endauth
            </div>
            
            @auth
                <div class="client-header-actions">
                    <a href="{{ route('cart.index') }}" class="client-cart-button">
                        <span class="client-cart-icon">🛒</span>
                        <span class="client-cart-count" id="cart-count">
                            <span id="cart-count-value">0</span>
                        </span>
                    </a>
                    
                    <div class="client-user-menu">
                        <button class="client-user-button" id="user-menu-trigger">
                            <span class="client-user-icon">👤</span>
                        </button>
                        <div class="client-user-dropdown" id="user-menu">
                            <a href="{{ route('profile.show') }}" class="client-dropdown-item">
                                Meu Perfil
                            </a>
                            <a href="{{ route('orders.index') }}" class="client-dropdown-item">
                                Meus Pedidos
                            </a>
                            <a href="{{ route('addresses.index') }}" class="client-dropdown-item">
                                Endereços
                            </a>
                            <hr class="client-dropdown-divider">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="client-dropdown-item client-dropdown-logout">
                                    Sair
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="client-header-actions">
                    <a href="{{ route('login') }}" class="client-btn-secondary">Entrar</a>
                    <a href="{{ route('register') }}" class="client-btn-primary">Registrar</a>
                </div>
            @endauth
        </div>
    </header>

    <!-- Main Content (com padding para header fixo) -->
    <main class="client-main">
        @if ($errors->any())
            <div class="client-alert client-alert-error">
                <button type="button" class="client-alert-close" onclick="this.parentElement.style.display='none';">×</button>
                <strong>Erros encontrados:</strong>
                <ul class="client-alert-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="client-alert client-alert-success">
                <button type="button" class="client-alert-close" onclick="this.parentElement.style.display='none';">×</button>
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="client-alert client-alert-error">
                <button type="button" class="client-alert-close" onclick="this.parentElement.style.display='none';">×</button>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="client-footer">
        <div class="client-footer-content">
            <div class="client-footer-section">
                <h4>{{ config('app.name') }}</h4>
                <p>Seu aplicativo favorito de delivery</p>
            </div>
            <div class="client-footer-section">
                <h4>Útil</h4>
                <ul>
                    @auth
                        <li><a href="{{ route('orders.index') }}">Meus Pedidos</a></li>
                        <li><a href="{{ route('addresses.index') }}">Meus Endereços</a></li>
                    @else
                        <li><a href="{{ route('login') }}">Entrar</a></li>
                    @endauth
                </ul>
            </div>
            <div class="client-footer-section">
                <h4>Contato</h4>
                <p>Email: contato@{{ config('app.name') }}.com</p>
            </div>
        </div>
        <div class="client-footer-bottom">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
        </div>
    </footer>

    <!-- Script para interatividade básica -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Menu do usuário
            const userMenuTrigger = document.getElementById('user-menu-trigger');
            const userMenu = document.getElementById('user-menu');
            
            if (userMenuTrigger && userMenu) {
                userMenuTrigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userMenu.classList.toggle('active');
                });
                
                document.addEventListener('click', function() {
                    userMenu.classList.remove('active');
                });
            }

            // Atualizar contagem do carrinho
            updateCartCount();
        });

        function updateCartCount() {
            @auth
            fetch('{{ route("cart.count") }}')
                .then(response => response.json())
                .then(data => {
                    const countElement = document.getElementById('cart-count-value');
                    if (countElement) {
                        countElement.textContent = data.count || 0;
                    }
                })
                .catch(() => {
                    // Falha silenciosa
                });
            @endauth
        }

        // Atualizar carrinho quando evento é disparado
        document.addEventListener('product-added', updateCartCount);
    </script>

    @yield('scripts')
</body>
</html>
