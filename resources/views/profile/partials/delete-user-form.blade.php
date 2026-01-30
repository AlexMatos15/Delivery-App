<div>
    <div class="alert alert-warning">
        <strong>Aviso:</strong> Uma vez que sua conta é deletada, todos os seus recursos e dados serão permanentemente deletados. Antes de deletar sua conta, faça o download de qualquer dado ou informação que você deseje manter.
    </div>

    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeletion">
        <i class="fas fa-trash"></i> Deletar Conta
    </button>

    <!-- Modal de Confirmação -->
    <div class="modal fade" id="confirmDeletion" tabindex="-1" role="dialog" aria-labelledby="confirmDeletionLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="confirmDeletionLabel">
                        <i class="fas fa-exclamation-triangle"></i> Deletar Conta
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    
                    <div class="modal-body">
                        <p>Tem certeza que deseja deletar sua conta?</p>
                        <p class="text-muted small">
                            Uma vez que sua conta é deletada, todos os seus recursos e dados serão permanentemente deletados. Por favor, digite sua senha para confirmar que você deseja permanentemente deletar sua conta.
                        </p>

                        <div class="form-group">
                            <label for="password">Senha</label>
                            <input id="password" name="password" type="password" class="form-control @error('userDeletion.password') is-invalid @enderror" placeholder="Digite sua senha">
                            @error('userDeletion.password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Deletar Conta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
