<!-- resources/views/pedido/modais/editar_status.blade.php -->
<div class="modal fade" id="modalEdit{{ $pedido->id_pedido }}" tabindex="-1" aria-labelledby="modalEditLabel{{ $pedido->id_pedido }}" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('pedidos.update', $pedido->id_pedido) }}" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title" id="modalEditLabel{{ $pedido->id_pedido }}">Editar Status do Pedido #{{ $pedido->id_pedido }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- Campo para Alterar o Status do Pedido -->
                <div class="mb-3">
                    <label for="status" class="form-label">Status do Pedido</label>
                    <select name="status" class="form-select" required>
                        <option value="em_preparo" {{ $pedido->status == 'em_preparo' ? 'selected' : '' }}>Em Preparo</option>
                        <option value="no_centro_transferencia" {{ $pedido->status == 'no_centro_transferencia' ? 'selected' : '' }}>No Centro de Transferência</option>
                        <option value="em_transito" {{ $pedido->status == 'em_transito' ? 'selected' : '' }}>Em Trânsito</option>
                        <option value="em_rota_entrega" {{ $pedido->status == 'em_rota_entrega' ? 'selected' : '' }}>Em Rota de Entrega</option>
                        <option value="entregue" {{ $pedido->status == 'entregue' ? 'selected' : '' }}>Entregue</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>
