@extends('adminlte::page')

@section('title', 'Meu Perfil')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Perfil do Usuário</h3>
                        <div class="card-tools">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Editar Perfil
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Nome:</strong>
                            </div>
                            <div class="col-md-9">
                                {{ $user->name }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Email:</strong>
                            </div>
                            <div class="col-md-9">
                                {{ $user->email }}
                                @if ($user->email_verified_at)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i> Verificado
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-exclamation"></i> Não verificado
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Tipo de Conta:</strong>
                            </div>
                            <div class="col-md-9">
                                @php
                                    $typeLabels = [
                                        'client' => 'Cliente',
                                        'shop' => 'Loja',
                                        'admin' => 'Administrador',
                                    ];
                                    $typeBadgeColors = [
                                        'client' => 'primary',
                                        'shop' => 'success',
                                        'admin' => 'danger',
                                    ];
                                @endphp
                                <span class="badge badge-{{ $typeBadgeColors[$user->type] ?? 'secondary' }}">
                                    {{ $typeLabels[$user->type] ?? ucfirst($user->type) }}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Status:</strong>
                            </div>
                            <div class="col-md-9">
                                @if ($user->is_active)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Ativo
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle"></i> Inativo
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Data de Cadastro:</strong>
                            </div>
                            <div class="col-md-9">
                                {{ $user->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Última Atualização:</strong>
                            </div>
                            <div class="col-md-9">
                                {{ $user->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Editar Informações
                        </a>
                        <a href="{{ route('password.request') }}" class="btn btn-warning">
                            <i class="fas fa-lock"></i> Alterar Senha
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
