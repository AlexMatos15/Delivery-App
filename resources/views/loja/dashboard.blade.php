@extends('adminlte::page')

@section('title', 'Dashboard da Loja')

@section('content_header')
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
<style>
    .store-status-card {
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .store-status-card.open {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    .store-status-card.closed {
        background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        color: white;
    }
    .store-status-text {
        font-size: 24px;
        font-weight: bold;
    }
    .store-status-icon {
        font-size: 32px;
    }

    .pending-orders-card {
        background: linear-gradient(135deg, #ff6b6b 0%, #ff8e53 100%);
        color: white;
        border-radius: 12px;
        padding: 32px;
        text-align: center;
        margin-bottom: 24px;
        box-shadow: 0 8px 24px rgba(255, 107, 107, 0.3);
    }
    .pending-orders-count {
        font-size: 72px;
        font-weight: bold;
        line-height: 1;
        margin: 16px 0;
    }
    .pending-orders-label {
        font-size: 20px;
        opacity: 0.95;
        margin-bottom: 20px;
    }

    .metric-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 16px;
    }
    .metric-label {
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 8px;
    }
    .metric-value {
        font-size: 32px;
        font-weight: bold;
        color: #212529;
    }
    .metric-icon {
        font-size: 40px;
        opacity: 0.2;
        position: absolute;
        right: 20px;
        top: 20px;
    }

    .orders-table {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .orders-table h4 {
        margin-bottom: 16px;
    }

    .btn-action {
        padding: 12px 24px;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-pending { background: #ffc107; color: #000; }
    .status-confirmed { background: #17a2b8; color: #fff; }
    .status-preparing { background: #007bff; color: #fff; }
    .status-out_for_delivery { background: #6610f2; color: #fff; }
    .status-delivered { background: #28a745; color: #fff; }
    .status-cancelled { background: #dc3545; color: #fff; }

    .shortcuts-section {
        margin-top: 32px;
    }
    .shortcut-btn {
        display: block;
        width: 100%;
        text-align: left;
        padding: 12px 16px;
        margin-bottom: 8px;
        border-radius: 6px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #495057;
        transition: all 0.2s;
    }
    .shortcut-btn:hover {
        background: #e9ecef;
        text-decoration: none;
        color: #212529;
    }
</style>
@stop

@section('content')
<div class="container-fluid">
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <!-- 1. STATUS DA LOJA -->
    <div class="store-status-card {{ $loja->is_open ? 'open' : 'closed' }}">
        <div>
            <div class="store-status-icon">
                <i class="fas fa-{{ $loja->is_open ? 'store' : 'store-slash' }}"></i>
            </div>
            <div class="store-status-text">
                Loja {{ $loja->is_open ? 'Aberta' : 'Fechada' }}
            </div>
        </div>
        <form method="POST" action="{{ route('loja.toggleStatus') }}">
            @csrf
            <button type="submit" class="btn btn-lg {{ $loja->is_open ? 'btn-light' : 'btn-success' }}">
                <i class="fas fa-{{ $loja->is_open ? 'times' : 'check' }}"></i>
                {{ $loja->is_open ? 'Fechar Loja' : 'Abrir Loja' }}
            </button>
        </form>
    </div>

    <!-- 2. PEDIDOS AGUARDANDO -->
    @if ($pedidosAguardando > 0)
        <div class="pending-orders-card">
            <div class="pending-orders-label">
                <i class="fas fa-bell"></i> PEDIDOS AGUARDANDO
            </div>
            <div class="pending-orders-count">{{ $pedidosAguardando }}</div>
            <a href="{{ route('loja.orders.index') }}" class="btn btn-light btn-lg btn-action">
                <i class="fas fa-eye"></i> Ver Pedidos Agora
            </a>
        </div>
    @endif

    <!-- 3. MÉTRICAS DO DIA -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="metric-card position-relative">
                <i class="fas fa-dollar-sign metric-icon text-success"></i>
                <div class="metric-label">Faturamento Hoje</div>
                <div class="metric-value text-success">R$ {{ number_format($faturamentoHoje, 2, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card position-relative">
                <i class="fas fa-shopping-bag metric-icon text-primary"></i>
                <div class="metric-label">Pedidos Hoje</div>
                <div class="metric-value text-primary">{{ $pedidosHoje }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card position-relative">
                <i class="fas fa-times-circle metric-icon text-danger"></i>
                <div class="metric-label">Cancelados Hoje</div>
                <div class="metric-value text-danger">{{ $pedidosCanceladosHoje }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card position-relative">
                <i class="fas fa-clock metric-icon text-warning"></i>
                <div class="metric-label">Pendentes</div>
                <div class="metric-value text-warning">{{ $pedidosAguardando }}</div>
            </div>
        </div>
    </div>

    <!-- 4. ÚLTIMOS PEDIDOS -->
    @if ($ultimosPedidos->count() > 0)
        <div class="orders-table">
            <h4><i class="fas fa-list"></i> Últimos Pedidos</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nº Pedido</th>
                            <th>Cliente</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Criado em</th>
                            <th class="text-right">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ultimosPedidos as $pedido)
                            <tr>
                                <td><strong>{{ $pedido->order_number }}</strong></td>
                                <td>{{ $pedido->user->name }}</td>
                                <td><strong class="text-success">R$ {{ number_format($pedido->total, 2, ',', '.') }}</strong></td>
                                <td>
                                    <span class="status-badge status-{{ $pedido->status }}">
                                        @php
                                            $statusLabels = [
                                                'pending' => 'Pendente',
                                                'confirmed' => 'Confirmado',
                                                'preparing' => 'Preparando',
                                                'out_for_delivery' => 'Saiu p/ Entrega',
                                                'delivered' => 'Entregue',
                                                'cancelled' => 'Cancelado',
                                            ];
                                        @endphp
                                        {{ $statusLabels[$pedido->status] ?? $pedido->status }}
                                    </span>
                                </td>
                                <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-right">
                                    <a href="{{ route('loja.orders.show', $pedido) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Abrir
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- 5. ATALHOS SECUNDÁRIOS -->
    <div class="shortcuts-section">
        <h5 class="text-muted mb-3">Atalhos Rápidos</h5>
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('loja.products.index') }}" class="shortcut-btn">
                    <i class="fas fa-cube"></i> Meus Produtos
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('loja.reports.dashboard') }}" class="shortcut-btn">
                    <i class="fas fa-chart-bar"></i> Relatórios
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
