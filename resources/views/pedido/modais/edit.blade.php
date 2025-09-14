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
            <div class="card-header">
                <strong>Informações do Pedido</strong>
            </div>
            <div class="card-body">
                <p><strong>Cliente:</strong> {{ $pedido->notaFiscal->remetente->nome }}</p>
                <p><strong>Destinatário:</strong> {{ $pedido->notaFiscal->destinatario->nome }}</p>
                <p><strong>Data de Emissão:</strong> {{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y') }}</p>
                <p><strong>Valor do Frete:</strong> R$ {{ number_format($pedido->frete->valor_frete, 2, ',', '.') }}</p>
                <p><strong>Status:</strong> {{ $pedido->status }}</p>
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
                                <th>Último Status</th> <!-- Alterado para Último Status -->
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
                                    
                                    {{-- Pegando o último status do histórico --}}
                                    <td>
                                        @if ($rota->historicos->isNotEmpty())
                                            {{ $rota->historicos->last()->status }}
                                        @else
                                            <em>Sem histórico</em>
                                        @endif
                                    </td>

                                    <td>
                                        {{-- Botão para Detalhar Histórico --}}
                                        <button class="btn btn-info btn-sm" data-bs-toggle="collapse" data-bs-target="#historico{{ $rota->id_rotas }}">
                                            Detalhar Histórico
                                        </button>
                                    </td>
                                </tr>

                                {{-- Histórico de Movimentações da Rota --}}
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
                                                        <td>{{ \Carbon\Carbon::parse($movimentacao->data)->format('d/m/Y H:i:s') }}</td>
                                                        <td>{{ $movimentacao->observacao }}</td>
                                                        <td>{{ $movimentacao->status }}</td>
                                                        <td>
                                                            @if($movimentacao->foto)
                                                                <img src="{{ asset('storage/' . $movimentacao->foto) }}" alt="Foto da movimentação" style="max-width: 100px; max-height: 100px;">
                                                            @else
                                                                <em>Sem foto</em>
                                                            @endif
                                                        </td>
                                                    </tr>
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
@endsection
