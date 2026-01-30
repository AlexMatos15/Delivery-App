<x-layouts.loja>
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h2">Painel da Loja</h1>
                <p class="text-muted">Bem-vindo, {{ Auth::user()->name }}!</p>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card border-left-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="text-muted font-weight-bold">Pedidos Hoje</h6>
                                <h2 class="mb-0">{{ $todayOrders ?? 0 }}</h2>
                            </div>
                            <i class="fas fa-shopping-cart fa-3x text-primary ml-auto"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card border-left-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="text-muted font-weight-bold">Faturamento Hoje</h6>
                                <h2 class="mb-0">R$ {{ number_format($todayRevenue ?? 0, 2, ',', '.') }}</h2>
                            </div>
                            <i class="fas fa-dollar-sign fa-3x text-success ml-auto"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Ações Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('loja.orders.index') }}" class="btn btn-primary mr-2">
                            <i class="fas fa-box"></i> Ver Pedidos
                        </a>
                        <a href="{{ route('loja.products.index') }}" class="btn btn-info mr-2">
                            <i class="fas fa-cube"></i> Gerenciar Produtos
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-secondary">
                            <i class="fas fa-user"></i> Meu Perfil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Pedidos Recentes</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($recentOrders) && $recentOrders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Pedido</th>
                                            <th>Cliente</th>
                                            <th>Data</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentOrders as $order)
                                            <tr>
                                                <td>{{ $order->order_number }}</td>
                                                <td>{{ $order->user->name }}</td>
                                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <span class="badge badge-info">{{ ucfirst($order->status) }}</span>
                                                </td>
                                                <td>R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Nenhum pedido recente.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.loja>
