<!-- resources/views/pedido/index.blade.php -->

@extends('layouts.app')

@section('content')
    {{-- Mensagens de sucesso/erro --}}
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- Detalhes do Pedido --}}
    <div class="container">
        <h2>Detalhes do Pedido #{{ $pedido->id_pedido }}</h2>

        {{-- Informações do Pedido --}}
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Informações do Pedido</strong>

                {{-- Botão de editar status do pedido --}}
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                    data-bs-target="#modalEditPedido{{ $pedido->id_pedido }}">
                    <i class="bi bi-pencil-fill"></i> Editar Status
                </button>
            </div>
            <div class="card-body">
                <p><strong>Cliente:</strong> {{ $pedido->notaFiscal->remetente->nome }}</p>
                <p><strong>Destinatário:</strong> {{ $pedido->notaFiscal->destinatario->nome }}</p>
                <p><strong>Data de Emissão:</strong> {{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y') }}</p>

                @if ($pedido->frete)
                    <p><strong>Valor do Frete:</strong> R$ {{ number_format($pedido->frete->valor_frete, 2, ',', '.') }}</p>
                @else
                    <p class="text-muted">Sem frete</p>
                @endif
                <p><strong>Status:</strong> {{ ucfirst($pedido->status) }}</p>
            </div>
        </div>

        {{-- Detalhes das Rotas --}}
        @if ($pedido->rotas->isNotEmpty())
            <div class="card mt-4">
                <div class="card-header">
                    <strong>Detalhes das Rotas</strong>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Tipo de Rota</th>
                                <th>Distância (km)</th>
                                <th>Data da Rota</th>
                                <th>Data de Início</th>
                                <th>Observações</th>
                                <th>Último Status</th>
                                <th>Detalhar Histórico</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rotas as $rota)
                                <tr>
                                    <td>{{ $rota->tipo }}</td>
                                    <td>{{ $rota->distancia }} km</td>
                                    <td>{{ \Carbon\Carbon::parse($rota->data_rota)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($rota->data_inicio)->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $rota->observacoes ?? 'Nenhuma observação' }}</td>

                                    <td>
                                        @if ($rota->historicos->isNotEmpty())
                                            {{ $rota->historicos->last()->status }}
                                        @else
                                            <em>Sem histórico</em>
                                        @endif
                                    </td>

                                    <td>
                                        <button class="btn btn-info btn-sm" data-bs-toggle="collapse"
                                            data-bs-target="#historico{{ $rota->id_rotas }}">
                                            Detalhar Histórico
                                        </button>
                                    </td>
                                </tr>

                                {{-- Histórico --}}
                                <tr class="collapse" id="historico{{ $rota->id_rotas }}">
                                    <td colspan="7">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Data</th>
                                                    <th>Observação</th>
                                                    <th>Status</th>
                                                    <th>Foto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($rota->historicos as $movimentacao)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($movimentacao->data)->format('d/m/Y H:i:s') }}
                                                        </td>
                                                        <td>{{ $movimentacao->observacao }}</td>
                                                        <td>{{ $movimentacao->status }}</td>
                                                        <td>
                                                                <button class="btn btn-success d-flex align-items-center gap-2"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#modalHistorico{{ $movimentacao->id_historico }}">
                                                                    <i class="bi bi-receipt"></i>
                                                                    <span>Comprovante</span>
                                                                </button>
                                                        </td>
                                                    </tr>

                                                        @include('pedido.modais.modal_comprovante', ['movimentacao' => $movimentacao])

                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="alert alert-warning mt-4">
                Este pedido ainda não tem uma rota associada.
            </div>
        @endif
    </div>

    {{-- Modal único para editar o status do pedido --}}
    <div class="modal fade" id="modalEditPedido{{ $pedido->id_pedido }}" tabindex="-1"
        aria-labelledby="modalEditPedidoLabel{{ $pedido->id_pedido }}" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('pedidos.update', $pedido->id_pedido) }}" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditPedidoLabel{{ $pedido->id_pedido }}">
                        Editar Status do Pedido #{{ $pedido->id_pedido }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status do Pedido</label>
                        <select name="status" class="form-select" required>
                            <option value="em_preparo" {{ $pedido->status == 'em_preparo' ? 'selected' : '' }}>Em Preparo
                            </option>
                            <option value="no_centro_transferencia" {{ $pedido->status == 'no_centro_transferencia' ? 'selected' : '' }}>No Centro de
                                Transferência</option>
                            <option value="em_transito" {{ $pedido->status == 'em_transito' ? 'selected' : '' }}>Em
                                Trânsito</option>
                            <option value="em_rota_entrega" {{ $pedido->status == 'em_rota_entrega' ? 'selected' : '' }}>Em
                                Rota de Entrega</option>
                            <option value="entregue" {{ $pedido->status == 'entregue' ? 'selected' : '' }}>Entregue
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto (Opcional)</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        @if ($pedido->foto)
                            <p><em>Foto atual:
                                    <img src="{{ asset('storage/' . $pedido->foto) }}" alt="Foto do pedido"
                                        style="max-width: 100px; max-height: 100px;">
                                </em></p>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
@endsection