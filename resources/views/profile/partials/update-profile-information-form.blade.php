<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    @php
        $typeLabels = [
            'client' => 'Cliente',
            'shop' => 'Loja',
            'admin' => 'Administrador',
        ];
    @endphp

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> Perfil atualizado com sucesso!
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="form-group">
        <label for="name">Nome</label>
        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus>
        @error('name')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
        @error('email')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
        
        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="alert alert-warning alert-sm mt-2">
                <small>
                    Seu email não foi verificado.
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-link btn-sm" style="padding: 0;">
                            Clique aqui para reenviar o email de verificação.
                        </button>
                    </form>
                </small>
            </div>
            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success alert-sm mt-2">
                    <small>Um novo email de verificação foi enviado para seu endereço de email.</small>
                </div>
            @endif
        @endif
    </div>

    <div class="form-group">
        <label>Tipo de Conta</label>
        <div class="alert alert-info">
            <small>
                <strong>{{ $typeLabels[$user->type] ?? ucfirst($user->type) }}</strong>
                @if ($user->is_admin)
                    <span class="badge badge-danger">Admin</span>
                @endif
                <br>
                <em>O tipo de conta não pode ser alterado. Entre em contato com o suporte se necessário.</em>
            </small>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> Salvar Alterações
    </button>
</form>
