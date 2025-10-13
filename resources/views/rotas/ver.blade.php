<!-- Modal Comprovante -->
<div class="modal fade" id="modalHistorico{{ $movimentacao->id_historico }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- Cabeçalho no mesmo estilo do sistema --}}
            <div class="modal-header text-white"
                 style="background: linear-gradient(90deg, #1d3557, #264653);">
                <h5 class="modal-title mb-0 fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-receipt-fill text-info"></i>
                    Comprovante da Movimentação #{{ $movimentacao->id_historico }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            {{-- Corpo do modal (fundo dark e imagem centralizada) --}}
            <div class="modal-body bg-dark text-center text-light p-4">
                @if (!empty($movimentacao->foto))
                    <div class="p-3 bg-black rounded-3 shadow d-inline-block border border-2 border-info">
                        <img src="{{ asset('canhotos/' . $movimentacao->foto) }}"
                             alt="Comprovante da movimentação"
                             class="img-fluid rounded-3"
                             style="max-height: 600px; width: auto; object-fit: contain;">
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-exclamation-triangle display-5 d-block mb-3 text-warning"></i>
                        <h5 class="fw-bold text-light">Nenhum comprovante disponível.</h5>
                    </div>
                @endif
            </div>

            {{-- Rodapé do modal (botões estilizados) --}}
            <div class="modal-footer bg-secondary text-white d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-outline-light shadow-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Fechar
                </button>

                @if (!empty($movimentacao->foto))
                    <div class="d-flex gap-2">
                        <a href="{{ asset('canhotos/' . $movimentacao->foto) }}" target="_blank"
                           class="btn btn-outline-info shadow-sm">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Abrir em nova aba
                        </a>
                        <a href="{{ asset('canhotos/' . $movimentacao->foto) }}" download
                           class="btn btn-info text-white shadow-sm">
                            <i class="bi bi-download me-1"></i> Baixar
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
