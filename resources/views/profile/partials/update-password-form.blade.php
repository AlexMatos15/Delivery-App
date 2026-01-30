<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    @if (session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> Senha alterada com sucesso!
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="alert alert-info">
        <small>Certifique-se de que sua conta está usando uma senha longa e aleatória para manter-se segura.</small>
    </div>

    <div class="form-group">
        <label for="current_password">Senha Atual</label>
        <input id="current_password" name="current_password" type="password" class="form-control @error('updatePassword.current_password') is-invalid @enderror" autocomplete="current-password">
        @error('updatePassword.current_password')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">Nova Senha</label>
        <input id="password" name="password" type="password" class="form-control @error('updatePassword.password') is-invalid @enderror" autocomplete="new-password">
        @error('updatePassword.password')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password_confirmation">Confirmar Senha</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control @error('updatePassword.password_confirmation') is-invalid @enderror" autocomplete="new-password">
        @error('updatePassword.password_confirmation')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> Atualizar Senha
    </button>
</form>
