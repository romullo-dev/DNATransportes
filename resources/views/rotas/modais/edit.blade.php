<div class="modal fade" id="modalEdit{{ $rotas->id_rotas }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('rotas.historico') }}" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Atualizar Status da Rota #{{ $rotas->id_rotas }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <input type="hidden" name="tipo" value="{{ ucfirst($rotas->tipo)  }}">

            <div class="modal-body">
                <input type="hidden" name="rotas_id_rotas" value="{{ $rotas->id_rotas }}">
                <input type="hidden" name="pedido_id_pedido"
                    value="{{ optional($rotas->historicos->last())->pedido_id_pedido }}">

                <div class="mb-3">
                    <label for="status_{{ $rotas->id_rotas }}" class="form-label">Novo Status</label>
                    <select class="form-select" id="status_{{ $rotas->id_rotas }}" name="status" required>
                        <option value="" disabled selected>Selecione o status</option>
                        <option value="Em trânsito">Em trânsito</option>
                        <option value="Finalizado">Finalizado</option>
                        <option value="Ocorrência">Ocorrência</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label>Foto (opcional)</label>
                    <input name="foto" type="file" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="data" class="form-label"><i class="bi bi-calendar-event me-1"></i>Data</label>
                    <input type="datetime-local" class="form-control" id="data" name="data" required>
                </div>
                <div class="mb-3">
                    <label for="observacao_{{ $rotas->id_rotas }}" class="form-label">Observações (opcional)</label>
                    <textarea class="form-control" id="observacao_{{ $rotas->id_rotas }}" name="observacao" rows="3"
                        placeholder="Detalhes adicionais..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
</div>
