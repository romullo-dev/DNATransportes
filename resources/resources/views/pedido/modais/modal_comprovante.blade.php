<!-- Modal Comprovante DNA Transportes -->
<div class="modal fade" id="modalHistorico{{ $movimentacao->id_historico }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl"> <!-- XL para ficar grandão -->
        <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden" style="background-color: #101010; color: #fff;">

            <!-- Cabeçalho -->
            <div class="modal-header" style="background-color: #EB8721; border-bottom: 2px solid #ffb84a;">
                <h5 class="modal-title mb-0 fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-receipt-fill"></i>
                    Comprovante da Movimentação #{{ $movimentacao->id_historico }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <!-- Corpo -->
            <div class="modal-body d-flex flex-column align-items-center justify-content-center p-4">
                @if (!empty($movimentacao->foto))
                    <div class="rounded-3 shadow-lg border border-dark" style="background-color: #000; padding: 1rem;">
                        <img src="{{ asset('canhotos/' . $movimentacao->foto) }}"
                             alt="Comprovante da movimentação"
                             class="img-fluid rounded-3"
                             style="max-height: 650px; width: auto; object-fit: contain; border: 3px solid #EB8721;">
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-exclamation-triangle display-5 d-block mb-3 text-warning"></i>
                        <h5 class="fw-bold text-white-50">Nenhum comprovante disponível para esta movimentação.</h5>
                    </div>
                @endif
            </div>

            <!-- Rodapé -->
            <div class="modal-footer d-flex justify-content-between align-items-center" style="background-color: #1a1a1a; border-top: 1px solid #333;">
                <button type="button" class="btn btn-outline-light d-flex align-items-center gap-2" data-bs-dismiss="modal" style="border-color: #EB8721;">
                    <i class="bi bi-x-circle"></i> Fechar
                </button>

                @if (!empty($movimentacao->foto))
                    <div class="d-flex gap-2">
                        <a href="{{ asset('canhotos/' . $movimentacao->foto) }}" target="_blank"
                           class="btn d-flex align-items-center gap-2"
                           style="background-color: #EB8721; color: #fff; border: none;">
                            <i class="bi bi-box-arrow-up-right"></i> Abrir em nova aba
                        </a>
                        <a href="{{ asset('canhotos/' . $movimentacao->foto) }}" download
                           class="btn d-flex align-items-center gap-2"
                           style="background-color: transparent; color: #EB8721; border: 2px solid #EB8721;">
                            <i class="bi bi-download"></i> Baixar
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
