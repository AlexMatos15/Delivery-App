<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Delivery Rápido e Fácil</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #ffffff 100%);
            color: #1a202c;
            line-height: 1.6;
        }

        /* Header */
        header {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #ef4444;
            text-decoration: none;
        }

        .header-nav {
            display: flex;
            gap: 16px;
        }

        .btn-header {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-login {
            color: #1a202c;
            background: transparent;
            border: 2px solid #e5e7eb;
        }

        .btn-login:hover {
            border-color: #ef4444;
            color: #ef4444;
        }

        .btn-register {
            background: #ef4444;
            color: white;
        }

        .btn-register:hover {
            background: #dc2626;
        }

        /* Hero Section */
        .hero {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
        }

        .hero-content h1 {
            font-size: 48px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 16px;
            color: #1a202c;
        }

        .hero-content .highlight {
            color: #ef4444;
        }

        .hero-content p {
            font-size: 18px;
            color: #6b7280;
            margin-bottom: 24px;
            line-height: 1.8;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-primary {
            padding: 16px 32px;
            background: #ef4444;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 16px;
            display: inline-block;
        }

        .btn-primary:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(239, 68, 68, 0.2);
        }

        .btn-secondary {
            padding: 16px 32px;
            background: white;
            color: #ef4444;
            border: 2px solid #ef4444;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
            font-size: 16px;
            display: inline-block;
        }

        .btn-secondary:hover {
            background: #fef2f2;
        }

        .hero-image {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 120px;
        }

        /* Features Section */
        .features {
            background: white;
            padding: 60px 20px;
        }

        .features-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #1a202c;
        }

        .section-subtitle {
            text-align: center;
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 48px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 32px;
        }

        .feature-card {
            padding: 32px 24px;
            background: #f9fafb;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s;
            border: 1px solid #f3f4f6;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
            background: white;
        }

        .feature-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .feature-card h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #1a202c;
        }

        .feature-card p {
            color: #6b7280;
            font-size: 14px;
        }

        /* How It Works */
        .how-it-works {
            padding: 60px 20px;
            background: linear-gradient(135deg, #fef2f2 0%, #fff9f9 100%);
        }

        .how-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-top: 40px;
        }

        .step {
            padding: 32px 24px;
            background: white;
            border-radius: 12px;
            position: relative;
            text-align: center;
        }

        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .step h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #1a202c;
        }

        .step p {
            color: #6b7280;
            font-size: 14px;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 60px 20px;
        }

        .cta-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }

        .cta-content h2 {
            font-size: 40px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .cta-content p {
            font-size: 18px;
            margin-bottom: 32px;
            opacity: 0.9;
        }

        .cta-content .btn-primary {
            background: white;
            color: #ef4444;
            font-size: 18px;
            padding: 16px 48px;
        }

        .cta-content .btn-primary:hover {
            background: #f9fafb;
            color: #ef4444;
        }

        /* Footer */
        footer {
            background: #1f2937;
            color: white;
            padding: 32px 20px;
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-text {
            font-size: 14px;
            opacity: 0.8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero {
                grid-template-columns: 1fr;
                padding: 40px 20px;
            }

            .hero-content h1 {
                font-size: 32px;
            }

            .hero-content p {
                font-size: 16px;
            }

            .hero-image {
                font-size: 80px;
                order: -1;
            }

            .header-nav {
                gap: 8px;
            }

            .btn-header {
                padding: 8px 16px;
                font-size: 13px;
            }

            .section-title {
                font-size: 28px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .steps-grid {
                grid-template-columns: 1fr;
            }

            .cta-content h2 {
                font-size: 28px;
            }

            .cta-content p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-content">
            <a href="/" class="logo">🍔 {{ config('app.name') }}</a>
            <nav class="header-nav">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-header btn-login">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-header btn-register">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-header btn-login">Entrar</a>
                    <a href="{{ route('register') }}" class="btn-header btn-register">Criar Conta</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Peça seu delivery em <span class="highlight">minutos</span></h1>
            <p>A forma mais rápida e fácil de pedir comida. Escolha entre centenas de restaurantes, monte seu pedido e receba em casa.</p>
            <div class="hero-actions">
                <a href="{{ route('register') }}" class="btn-primary">Começar Agora</a>
                <a href="{{ route('login') }}" class="btn-secondary">Entrar</a>
            </div>
        </div>
        <div class="hero-image">
            🚗
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="features-container">
            <h2 class="section-title">Por que escolher {{ config('app.name') }}?</h2>
            <p class="section-subtitle">Entrega confiável, rápida e segura todos os dias</p>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Entrega Rápida</h3>
                    <p>Seus pedidos chegam quentinhos. Rastreamento em tempo real do seu delivery.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💳</div>
                    <h3>Pagamento Fácil</h3>
                    <p>Pague com Pix, Cartão de Crédito, Débito ou até na entrega. Seu jeito.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🍽️</div>
                    <h3>Muitas Opções</h3>
                    <p>Centenas de restaurantes e lojas para você escolher. Sempre algo novo.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>100% Seguro</h3>
                    <p>Seus dados estão protegidos. Compre com tranquilidade e segurança.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📍</div>
                    <h3>Endereço Salvo</h3>
                    <p>Salve seus endereços favoritos. Próximos pedidos são mais rápidos.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⭐</div>
                    <h3>Avaliações Reais</h3>
                    <p>Veja avaliações de outros clientes. Escolha os melhores restaurantes.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works">
        <div class="how-container">
            <h2 class="section-title">Como Funciona</h2>
            <p class="section-subtitle">Rápido, simples e delicioso em 3 passos</p>

            <div class="steps-grid">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Escolha o Restaurante</h3>
                    <p>Navegue por centenas de opções e encontre seu favorito. Veja cardápios e avaliações.</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Monte seu Pedido</h3>
                    <p>Escolha o que deseja, customize sua ordem e adicione ao carrinho. Fácil assim.</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Receba em Casa</h3>
                    <p>Acompanhe a entrega em tempo real e receba quentinho. Aproveite o seu prato!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-content">
            <h2>Pronto para pedir?</h2>
            <p>Junte-se a milhares de clientes satisfeitos. Seu primeiro pedido está à espera.</p>
            <a href="{{ route('register') }}" class="btn-primary">Criar Conta Grátis</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p class="footer-text">© {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
            <p class="footer-text" style="margin-top: 8px; font-size: 12px;">Entrega rápida | Segurança garantida | Atendimento 24/7</p>
        </div>
    </footer>
</body>
</html>
