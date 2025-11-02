@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="color:#e0e0e0;">

    {{-- Mensagens de sucesso/erro --}}
    @if (session('success'))
        <div class="alert alert-success border-0 rounded-3 shadow-sm text-white" style="background-color:#2d7d46;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger border-0 rounded-3 shadow-sm text-white" style="background-color:#c0392b;">
            <i class="bi bi-exclamation-octagon-fill me-2"></i>{{ session('error') }}
        </div>
    @endif

    {{-- Título --}}
    <div class="mb-4">
        <h3 class="fw-bold text-white">
            <i class="bi bi-box-seam-fill text-warning me-2"></i>Detalhes do Pedido #{{ $pedido->id_pedido }}
        </h3>
    </div>

    {{-- Informações do Pedido --}}
    <div class="card bg-dark border-0 shadow-sm rounded-4 mb-4" style="overflow:hidden;">
        <div class="card-header text-dark fw-bold border-0" style="background:linear-gradient(90deg,#eb8721,#d67400);">
            <i class="bi bi-file-earmark-text-fill me-2"></i>Informações do Pedido
        </div>
        <div class="card-body text-light">
            <p><strong class="text-warning">Cliente:</strong> {{ $pedido->notaFiscal->remetente->nome }}</p>
            <p><strong class="text-warning">Destinatário:</strong> {{ $pedido->notaFiscal->destinatario->nome }}</p>
            <p><strong class="text-warning">Data de Emissão:</strong> {{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y') }}</p>

            @if ($pedido->frete)
                <p><strong class="text-warning">Valor do Frete:</strong> R$ {{ number_format($pedido->frete->valor_frete, 2, ',', '.') }}</p>
            @else
                <p class="text-muted"><em>Sem frete</em></p>
            @endif

            <p><strong class="text-warning">Status:</strong> {{ ucfirst($pedido->status) }}</p>
        </div>
    </div>

    {{-- Detalhes das Rotas --}}
    @if ($pedido->rotas->isNotEmpty())
        <div class="card bg-dark border-0 shadow-sm rounded-4">
            <div class="card-header text-dark fw-bold border-0" style="background:linear-gradient(90deg,#eb8721,#d67400);">
                <i class="bi bi-signpost-split-fill me-2"></i>Detalhes das Rotas
            </div>
            <div class="card-body text-light">
                <div class="table-responsive rounded-4 overflow-hidden">
                    <table class="table table-dark table-hover align-middle mb-0">
                        <thead style="background:linear-gradient(90deg,#eb8721,#d67400); color:#fff;">
                            <tr>
                                <th><i class="bi bi-geo-alt-fill me-1"></i>Tipo de Rota</th>
                                <th><i class="bi bi-rulers me-1"></i>Distância (km)</th>
                                <th><i class="bi bi-calendar-event me-1"></i>Data da Rota</th>
                                <th><i class="bi bi-clock-history me-1"></i>Data de Início</th>
                                <th><i class="bi bi-chat-left-text me-1"></i>Observações</th>
                                <th><i class="bi bi-flag me-1"></i>Último Status</th>
                                <th class="text-center"><i class="bi bi-journal-text me-1"></i>Detalhar Histórico</th>
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
                                            <span class="badge bg-success px-3 py-2">
                                                {{ $rota->historicos->last()->status }}
                                            </span>
                                        @else
                                            <span class="text-muted"><em>Sem histórico</em></span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <button class="btn btn-outline-info btn-sm fw-semibold"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#historico{{ $rota->id_rotas }}">
                                            <i class="bi bi-clock-history me-1"></i>Ver Histórico
                                        </button>
                                    </td>
                                </tr>

                                {{-- Histórico --}}
                                <tr class="collapse bg-dark-subtle" id="historico{{ $rota->id_rotas }}">
                                    <td colspan="7" class="p-3">
                                        <table class="table table-sm table-dark table-striped rounded-3 mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Data</th>
                                                    <th>Observação</th>
                                                    <th>Status</th>
                                                    <th>Comprovante</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($rota->historicos as $movimentacao)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($movimentacao->data)->format('d/m/Y H:i:s') }}</td>
                                                        <td>{{ $movimentacao->observacao }}</td>
                                                        <td>{{ $movimentacao->status }}</td>
                                                        <td>
                                                            <button class="btn btn-warning btn-sm text-dark fw-semibold"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalHistorico{{ $movimentacao->id_historico }}">
                                                                <i class="bi bi-receipt me-1"></i>Comprovante
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
        </div>
    @else
        <div class="alert alert-warning mt-4 border-0 rounded-3 text-dark shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>Este pedido ainda não tem uma rota associada.
        </div>
    @endif
</div>

<style>
    body {
        background-color: #12181f !important;
    }

    .btn-outline-info {
        color: #00bcd4;
        border-color: #00bcd4;
    }

    .btn-outline-info:hover {
        background-color: #00bcd4;
        color: #12181f;
    }

    .table-dark th, .table-dark td {
        vertical-align: middle !important;
    }
</style>
@endsection
