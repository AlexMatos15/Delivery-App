<x-layouts.admin>
    @section('title', 'Painel Administrativo')

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h2">Painel Administrativo</h1>
                <p class="text-muted">Bem-vindo de volta, {{ Auth::user()->name }}!</p>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $totalOrders ?? 0 }}</h3>
                        <p>Pedidos Totais</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="small-box-footer">
                        Ver mais <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>R$ {{ number_format($totalRevenue ?? 0, 2, ',', '.') }}</h3>
                        <p>Faturamento Total</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        Relatórios <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $totalUsers ?? 0 }}</h3>
                        <p>Usuários Ativos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                        Gerenciar <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $pendingOrders ?? 0 }}</h3>
                        <p>Pedidos Pendentes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="small-box-footer">
                        Resolver <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Management Sections -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Gestão do Sistema</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-users text-primary me-2"></i> Gerenciar Usuários
                                </div>
                                <span class="badge badge-primary">{{ $totalUsers ?? 0 }}</span>
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-list text-info me-2"></i> Categorias
                                </div>
                                <span class="badge badge-info">{{ $totalCategories ?? 0 }}</span>
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-cube text-success me-2"></i> Produtos
                                </div>
                                <span class="badge badge-success">{{ $totalProducts ?? 0 }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Ações Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-box text-primary me-2"></i> Ver Todos os Pedidos
                                </div>
                                <i class="fas fa-chevron-right text-muted"></i>
                            </a>
                            <a href="{{ route('admin.products.create') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-plus text-success me-2"></i> Novo Produto
                                </div>
                                <i class="fas fa-chevron-right text-muted"></i>
                            </a>
                            <a href="{{ route('admin.categories.create') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-plus text-info me-2"></i> Nova Categoria
                                </div>
                                <i class="fas fa-chevron-right text-muted"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

