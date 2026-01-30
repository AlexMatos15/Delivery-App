@extends('adminlte::page')

@section('title', 'Produtos')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gerenciar Produtos</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Novo Produto
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($products->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Nenhum produto encontrado.
                        </div>
                    @else
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Categoria</th>
                                    <th class="text-center">Preço</th>
                                    <th class="text-center">Estoque</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;">
                                                @else
                                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; margin-right: 10px;">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $product->name }}</strong><br>
                                                    <small class="text-muted">SKU: {{ $product->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <strong>R$ {{ number_format($product->price, 2, ',', '.') }}</strong>
                                            @if ($product->promotional_price)
                                                <br><small class="text-success">Promoção: R$ {{ number_format($product->promotional_price, 2, ',', '.') }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($product->stock > 20)
                                                <span class="badge badge-success">{{ $product->stock }} un.</span>
                                            @elseif ($product->stock > 5)
                                                <span class="badge badge-warning">{{ $product->stock }} un.</span>
                                            @else
                                                <span class="badge badge-danger">{{ $product->stock }} un.</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($product->is_active)
                                                <span class="badge badge-success">Ativo</span>
                                            @else
                                                <span class="badge badge-secondary">Inativo</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-info" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.products.toggle-status', $product) }}" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $product->is_active ? 'btn-warning' : 'btn-success' }}" title="{{ $product->is_active ? 'Desativar' : 'Ativar' }}">
                                                    <i class="fas fa-{{ $product->is_active ? 'times' : 'check' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar este produto?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Deletar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($products instanceof \Illuminate\Pagination\Paginator)
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    Mostrando {{ $products->firstItem() }} a {{ $products->lastItem() }} de {{ $products->total() }} produtos
                                </div>
                                {{ $products->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
