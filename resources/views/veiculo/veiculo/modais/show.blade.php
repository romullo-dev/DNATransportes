@foreach ($modelos as $veiculo)
    <div class="modal fade" id="modalShow{{ $veiculo->id_Veiculo }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header bg-gradient text-white"
                    style="background: linear-gradient(90deg, #017aaa, #2a9d8f);">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-eye me-2"></i> Detalhes do Veículo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Fechar"></button>
                </div>

                <div class="modal-body bg-dark text-light rounded-bottom-4">
                    <div class="mb-2">
                        <label class="fw-semibold text-warning">Placa:</label>
                        <p>{{ $veiculo->placa }}</p>
                    </div>
                    <div class="mb-2">
                        <label class="fw-semibold text-warning">Ano:</label>
                        <p>{{ $veiculo->ano }}</p>
                    </div>
                    <div class="mb-2">
                        <label class="fw-semibold text-warning">Modelo:</label>
                        <p>{{ $veiculo->modelo_veiculo->modelo ?? '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <label class="fw-semibold text-warning">Categoria:</label>
                        <p>{{ $veiculo->modelo_veiculo->categoria ?? '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <label class="fw-semibold text-warning">Capacidade:</label>
                        <p>{{ $veiculo->capacidade_kg }} Kg</p>
                    </div>
                    <div class="mb-2">
                        <label class="fw-semibold text-warning">Status:</label>
                        <p>{{ ucfirst($veiculo->status ?? 'Ativo') }}</p>
                    </div>
                </div>

                <div class="modal-footer bg-dark border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
