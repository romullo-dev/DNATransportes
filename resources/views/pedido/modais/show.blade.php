@foreach ($result as $pedidos)
<div class="modal fade" id="modalShow{{ $pedidos->id_pedido }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- 🟦 Cabeçalho --}}
            <div class="modal-header text-white" 
                style="background: linear-gradient(90deg, #ffc107,  #be9312); border: none;">
                <h5 class="modal-title fw-semibold">
                    <i class="bi bi-box-arrow-in-up-right me-2"></i>
                    Detalhes do Pedido #{{ $pedidos->id_pedido }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- 🔹 Corpo --}}
            <div class="modal-body" style="background-color: #1b1e22; color: #f1f1f1; font-family: 'Inter', sans-serif;">
                {{-- Remetente --}}
                <div class="mb-4">
                    <h6 class="text-warning fw-bold mb-2">
                        <i class="bi bi-person-fill me-1"></i> Remetente
                    </h6>
                    <p><strong>Nome:</strong> {{ $pedidos->notaFiscal->remetente->nome ?? '---' }}</p>
                    <p><strong>CPF/CNPJ:</strong> {{ $pedidos->notaFiscal->remetente->documento ?? '---' }}</p>
                    <p><strong>Endereço:</strong> 
                        {{ $pedidos->notaFiscal->enderecoRemetente->logradouro ?? '-' }},
                        {{ $pedidos->notaFiscal->enderecoRemetente->numero ?? '-' }},
                        {{ $pedidos->notaFiscal->enderecoRemetente->bairro ?? '-' }},
                        {{ $pedidos->notaFiscal->enderecoRemetente->cidade ?? '-' }} -
                        {{ $pedidos->notaFiscal->enderecoRemetente->uf ?? '-' }},
                        CEP: {{ $pedidos->notaFiscal->enderecoRemetente->cep ?? '-' }}
                    </p>
                </div>

                <hr class="border-secondary">

                {{-- Nota Fiscal --}}
                <div class="mb-4">
                    <h6 class="text-warning fw-bold mb-2">
                        <i class="bi bi-file-earmark-text me-1"></i> Nota Fiscal
                    </h6>
                    <p><strong>Número do Pedido:</strong> {{ $pedidos->pedido_numero ?? '---' }}</p>
                    <p><strong>Número da Nota:</strong> {{ $pedidos->notaFiscal->numero_nfe ?? '---' }}</p>
                    <p><strong>Chave da Nota:</strong> {{ $pedidos->notaFiscal->chave_acesso ?? '---' }}</p>
                    <p><strong>Valor Total:</strong>
                        R$ {{ number_format($pedidos->notaFiscal->valor_total ?? 0, 2, ',', '.') }}</p>
                </div>

                <hr class="border-secondary">

                {{-- Destinatário --}}
                <div class="mb-4">
                    <h6 class="text-warning fw-bold mb-2">
                        <i class="bi bi-person-check-fill me-1"></i> Destinatário
                    </h6>
                    <p><strong>Nome:</strong> {{ $pedidos->notaFiscal->destinatario->nome ?? '---' }}</p>
                    <p><strong>CPF/CNPJ:</strong> {{ $pedidos->notaFiscal->destinatario->documento ?? '---' }}</p>
                    <p><strong>Endereço:</strong>
                        {{ $pedidos->notaFiscal->enderecoDestinatario->logradouro ?? '-' }},
                        {{ $pedidos->notaFiscal->enderecoDestinatario->numero ?? '-' }},
                        {{ $pedidos->notaFiscal->enderecoDestinatario->bairro ?? '-' }},
                        {{ $pedidos->notaFiscal->enderecoDestinatario->cidade ?? '-' }} -
                        {{ $pedidos->notaFiscal->enderecoDestinatario->uf ?? '-' }},
                        CEP: {{ $pedidos->notaFiscal->enderecoDestinatario->cep ?? '-' }}
                    </p>
                </div>

                <hr class="border-secondary">

                {{-- Detalhes do Pedido --}}
                <div>
                    <h6 class="text-warning fw-bold mb-2">
                        <i class="bi bi-truck me-1"></i> Detalhes do Pedido
                    </h6>
                    <p><strong>Código de Rastreio:</strong> {{ $pedidos->codigo_rastreamento ?? '---' }}</p>
                    <p><strong>Valor do Frete:</strong>
                        R$ {{ number_format($pedidos->frete->valor_frete ?? 0, 2, ',', '.') }}</p>
                    <p><strong>Data de Criação:</strong>
                        {{ \Carbon\Carbon::parse($pedidos->data)->format('d/m/Y \à\s H:i') }}</p>
                </div>
            </div>

            {{-- 🔸 Rodapé --}}
            <div class="modal-footer" style="background-color: #12181F;">
                <button type="button" class="btn btn-outline-warning rounded-pill fw-semibold px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Fechar
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
