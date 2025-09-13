<!-- resources/views/User/modais/show.blade.php -->

@foreach ($result as $pedidos)
<div class="modal fade" id="modalShow{{ $pedidos->id_pedido }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-sm">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalLabel{{ $pedidos->id_pedido }}">
                    Detalhes do Pedido #{{ $pedidos->id_pedido }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body">
                <h6><strong>Remetente:</strong></h6>
                <p><strong>Nome:</strong> {{ $pedidos->notaFiscal->remetente->nome ?? 'N/A' }}</p>
                <p><strong>CPF/CNPJ:</strong> {{ $pedidos->notaFiscal->remetente->documento ?? 'N/A' }}</p>
                <p><strong>Endereço:</strong> {{ $pedidos->notaFiscal->enderecoRemetente->endereco ?? 'N/A' }}</p>

                <hr>

                <h6><strong>Nota Fiscal:</strong></h6>
                <p><strong>Número do Pedido:</strong> {{ $pedidos->pedido_numero }}</p>
                <p><strong>Número da Nota:</strong> {{ $pedidos->notaFiscal->numero_nfe}}</p>
                <p><strong>Chave da Nota:</strong> {{ $pedidos->notaFiscal->chave_acesso }}</p>
                <p><strong>Valor Total da Nota :</strong> R$ {{ $pedidos->notaFiscal->valor_total }}</p>

                <hr>

                <h6><strong>Destinatário:</strong></h6>
                <p><strong>Nome:</strong> {{ $pedidos->notaFiscal->destinatario->nome }}</p>
                <p><strong>CPF/CNPJ:</strong> {{ $pedidos->notaFiscal->destinatario->documento }}</p>
                <p><strong>Endereço:</strong> 
                    {{ $pedidos->notaFiscal->enderecoDestinatario->endereco }}</p>

                 <hr>

                <h6><strong>Detalhes do pedido:</strong></h6>
                <p><strong>Código de rastreio:</strong> {{ $pedidos->codigo_rastreamento ?? 'N/A' }}</p>
                <p><strong>Valor do Frete:</strong> R$ {{ $pedidos->frete->valor_frete ?? 'N/A' }}</p>
                <p><strong>Data de criação:</strong> {{ \Carbon\Carbon::parse($pedidos->data)->format(format: 'd/m/Y \à\s H:i') }}</p>

            </div>

           

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Fechar
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
