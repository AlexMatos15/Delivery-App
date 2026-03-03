@extends('adminlte::page')

@section('title', 'Categorias')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gerenciar Categorias</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nova Categoria
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($categories->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Nenhuma categoria encontrada.
                        </div>
                    @else
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Categoria</th>
                                    <th>Slug</th>
                                    <th class="text-center">Ordem</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($category->image)
                                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;">
                                                @else
                                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; margin-right: 10px;">
                                                        <i class="fas fa-folder"></i>
                                                    </div>
                                                @endif
                                                <strong>{{ $category->name }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <code>{{ $category->slug }}</code>
                                        </td>
                                        <td class="text-center">
                                            <strong>{{ $category->display_order }}</strong>
                                        </td>
                                        <td class="text-center">
                                            @if ($category->is_active)
                                                <span class="badge badge-success">Ativa</span>
                                            @else
                                                <span class="badge badge-secondary">Inativa</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-info" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.categories.toggle', $category) }}" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $category->is_active ? 'btn-warning' : 'btn-success' }}" title="{{ $category->is_active ? 'Desativar' : 'Ativar' }}">
                                                    <i class="fas fa-{{ $category->is_active ? 'times' : 'check' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar esta categoria?');">
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

                        @if ($categories instanceof \Illuminate\Pagination\Paginator)
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    Mostrando {{ $categories->firstItem() }} a {{ $categories->lastItem() }} de {{ $categories->total() }} categorias
                                </div>
                                {{ $categories->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
