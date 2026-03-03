@extends('adminlte::page')

@section('title', 'Criar Usuário')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Criar Novo Usuário</h3>
                </div>
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="card-body">

                        {{-- Nome --}}
                        <div class="form-group">
                            <label for="name">Nome <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}"
                                   required autofocus placeholder="Nome completo">
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}"
                                   required placeholder="email@exemplo.com">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Tipo de Usuário --}}
                        <div class="form-group">
                            <label for="type">Tipo de Usuário <span class="text-danger">*</span></label>
                            <select class="form-control @error('type') is-invalid @enderror"
                                    id="type" name="type" required>
                                <option value="">-- Selecione --</option>
                                <option value="client" {{ old('type') === 'client' ? 'selected' : '' }}>Cliente</option>
                                <option value="shop" {{ old('type') === 'shop' ? 'selected' : '' }}>Loja</option>
                                <option value="admin" {{ old('type') === 'admin' ? 'selected' : '' }}>Administrador</option>
                            </select>
                            @error('type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Senha --}}
                        <div class="form-group">
                            <label for="password">Senha <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required placeholder="Mínimo 8 caracteres">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Confirmar Senha --}}
                        <div class="form-group">
                            <label for="password_confirmation">Confirmar Senha <span class="text-danger">*</span></label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation"
                                   required placeholder="Repita a senha">
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Criar Usuário
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
