<!-- 🟢 Modal Atualizar Status -->
<div class="modal fade" id="modalAtualizarStatus{{ $pedido->id_pedido }}" tabindex="-1" aria-labelledby="modalLabel{{ $pedido->id_pedido }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="modal-header text-white" style="background: linear-gradient(90deg, #017aaa, #2a9d8f);">
                <h5 class="modal-title fw-semibold" id="modalLabel{{ $pedido->id_pedido }}">
                    <i class="bi bi-pencil-square me-2"></i> Atualizar Status do Pedido
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <form action="" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body bg-light">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Novo Status:</label>
                        <select class="form-select shadow-sm" name="status" required>
                            <option value="" disabled selected>Selecione...</option>
                            <option value="Em preparo">Em preparo</option>
                            <option value="Em trânsito">Em trânsito</option>
                            <option value="Entregue">Entregue</option>
                            <option value="Cancelado">Cancelado</option>
                            <option value="Devolvido">Devolvido</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Observação (opcional):</label>
                        <textarea class="form-control shadow-sm" name="observacao" rows="3" placeholder="Ex: Cliente ausente, rota alterada..."></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning fw-semibold rounded-pill text-white" 
                            style="background: linear-gradient(90deg, #017aaa, #2a9d8f); border: none;">
                        <i class="bi bi-check-circle me-1"></i> Atualizar
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
