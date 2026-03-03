@extends('adminlte::page')

@section('title', 'Usuários')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gerenciar Usuários</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Novo Usuário
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($users->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Nenhum usuário encontrado.
                        </div>
                    @else
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Data de Cadastro</th>
                                    <th class="text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <strong>{{ $user->name }}</strong>
                                        </td>
                                        <td>
                                            {{ $user->email }}
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $typeBadgeClass = [
                                                    'client' => 'primary',
                                                    'shop' => 'success',
                                                    'admin' => 'danger',
                                                ];
                                                $typeLabels = [
                                                    'client' => 'Cliente',
                                                    'shop' => 'Loja',
                                                    'admin' => 'Administrador',
                                                ];
                                            @endphp
                                            <span class="badge badge-{{ $typeBadgeClass[$user->type] ?? 'secondary' }}">
                                                {{ $typeLabels[$user->type] ?? ucfirst($user->type) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if ($user->is_active)
                                                <span class="badge badge-success">Ativo</span>
                                            @else
                                                <span class="badge badge-danger">Inativo</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <small>{{ $user->created_at->format('d/m/Y') }}</small>
                                        </td>
                                        <td class="text-right">
                                            @if ($user->is_active)
                                                <form method="POST" action="{{ route('admin.users.toggle', $user) }}" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja desativar este usuário?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-warning" title="Desativar">
                                                        <i class="fas fa-times"></i> Desativar
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.users.toggle', $user) }}" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success" title="Ativar">
                                                        <i class="fas fa-check"></i> Ativar
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar este usuário? Esta ação não pode ser desfeita.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Deletar">
                                                    <i class="fas fa-trash"></i> Deletar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($users instanceof \Illuminate\Pagination\Paginator)
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    Mostrando {{ $users->firstItem() }} a {{ $users->lastItem() }} de {{ $users->total() }} usuários
                                </div>
                                {{ $users->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
