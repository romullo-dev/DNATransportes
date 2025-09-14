@foreach ($result as $pedidos)
<div class="modal fade" id="modalShow{{ $pedidos->id_pedido }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header" style="background-color: #000000; color: #FFF;">
                <h5 class="modal-title" id="modalLabel{{ $pedidos->id_pedido }}" style="color: #FFD700;">
                    <i class="bi bi-box-arrow-in-up-right me-2"></i> Detalhes do Pedido #{{ $pedidos->id_pedido }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body" style="background-color: #f8f9fa; font-family: 'Arial', sans-serif;">
                <h6><strong><i class="bi bi-person-fill me-2"></i> Remetente:</strong></h6>
                <p><strong>Nome:</strong> {{ $pedidos->notaFiscal->remetente->nome ?? 'ELFA MEDICAMENTOS SA' }}</p>
                <p><strong>CPF/CNPJ:</strong> {{ $pedidos->notaFiscal->remetente->documento ?? '09053134000145' }}</p>
                <p><strong>Endereço:</strong> 
                    {{ $pedidos->notaFiscal->enderecoRemetente->logradouro ?? 'NUCR INTERSECÇÃO ROD DF001 C/ROD' }},
                    Casa {{ $pedidos->notaFiscal->enderecoRemetente->casa ?? '475' }},
                    {{ $pedidos->notaFiscal->enderecoRemetente->bairro ?? 'Ponte Alta Norte' }},
                    {{ $pedidos->notaFiscal->enderecoRemetente->cidade ?? 'Brasília' }},
                    {{ $pedidos->notaFiscal->enderecoRemetente->uf ?? 'DF' }},
                    CEP: {{ $pedidos->notaFiscal->enderecoRemetente->cep ?? '72427010' }}
                </p>

                <hr>

                <h6><strong><i class="bi bi-file-earmark-check-fill me-2"></i> Nota Fiscal:</strong></h6>
                <p><strong>Número do Pedido:</strong> {{ $pedidos->pedido_numero }}</p>
                <p><strong>Número da Nota:</strong> {{ $pedidos->notaFiscal->numero_nfe }}</p>
                <p><strong>Chave da Nota:</strong> {{ $pedidos->notaFiscal->chave_acesso }}</p>
                <p><strong>Valor Total da Nota:</strong> R$ {{ $pedidos->notaFiscal->valor_total }}</p>

                <hr>

                <h6><strong><i class="bi bi-person-check-fill me-2"></i> Destinatário:</strong></h6>
                <p><strong>Nome:</strong> {{ $pedidos->notaFiscal->destinatario->nome ?? 'DUPATRI HOSPITALAR COMERCIO, IMPORTACAO E EXPORTACAO LTDA' }}</p>
                <p><strong>CPF/CNPJ:</strong> {{ $pedidos->notaFiscal->destinatario->documento ?? '04027894000750' }}</p>
                <p><strong>Endereço:</strong>
                    {{ $pedidos->notaFiscal->enderecoDestinatario->logradouro ?? 'Não especificado' }},
                    {{ $pedidos->notaFiscal->enderecoDestinatario->bairro ?? 'Não especificado' }},
                    {{ $pedidos->notaFiscal->enderecoDestinatario->cidade ?? 'Não especificado' }},
                    {{ $pedidos->notaFiscal->enderecoDestinatario->uf ?? 'Não especificado' }},
                    CEP: {{ $pedidos->notaFiscal->enderecoDestinatario->cep ?? 'Não especificado' }}
                </p>

                <hr>

                <h6><strong><i class="bi bi-info-circle-fill me-2"></i> Detalhes do pedido:</strong></h6>
                <p><strong>Código de rastreio:</strong> {{ $pedidos->codigo_rastreamento ?? 'N/A' }}</p>
                <p><strong>Valor do Frete:</strong> R$ {{ $pedidos->frete->valor_frete ?? 'N/A' }}</p>
                <p><strong>Data de criação:</strong> {{ \Carbon\Carbon::parse($pedidos->data)->format('d/m/Y \à\s H:i') }}</p>

            </div>

            <div class="modal-footer" style="background-color: #343a40;">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Fechar
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
