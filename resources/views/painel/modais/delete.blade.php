<div class="modal fade" id="modaldelete{{ $usuario->id_usuario }}" tabindex="-1" aria-labelledby="modaldeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('destroy-user', $usuario->id_usuario ) }}" class="modal-content border-0 shadow-sm">
            @csrf
            @method('DELETE')

            <div class="modal-header bg-light border-bottom">
                <h5 class="modal-title fw-bold text-dark" id="modaldeleteLabel">
                    <i class="bi bi-exclamation-circle-fill text-warning me-2"></i> Confirmação de Exclusão
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body text-center py-4">
                <p class="fs-5 mb-0 text-dark">
                    Deseja realmente excluir o usuário <strong>{{ $usuario->nome }}</strong>?
                </p>
                <p class="text-muted small mt-2">Esta ação é irreversível.</p>
            </div>

            <div class="modal-footer justify-content-between px-4 pb-3">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-outline-success">
                    <i class="bi bi-check2-circle me-1"></i> Confirmar
                </button>
            </div>
        </form>
    </div>
</div>
