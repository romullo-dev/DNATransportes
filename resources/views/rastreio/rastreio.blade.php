@extends('layouts.app')

@section('content')
    <div class="container py-5" style="min-height: 90vh;">
        <div class="row justify-content-center">
            <div class="col-md-10">

                {{-- Card de Cabeçalho --}}
                <div class="card shadow-lg border-0 rounded-4 mb-4"
                     style="background: linear-gradient(135deg, #1d3557, #264653); color: #fff;">
                    <div class="card-body text-center py-5">
                        <h1 class="fw-bold text-warning">DNA Transportes</h1>
                        <p class="mb-0 fs-5">Sistema de Rastreamento de Pedidos</p>
                    </div>
                </div>

                {{-- Card Principal --}}
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 fw-bold">
                            Pedido #{{ $pedido->id_pedido }}
                        </h4>
                        <span class="badge rounded-pill px-3 py-2" style="background-color: #FFD700; color:#000;">
                            {{ strtoupper($pedido->status) }}
                        </span>
                    </div>
                    <div class="card-body p-4">

                        {{-- Infos principais --}}
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="p-3 rounded-3 shadow-sm bg-light">
                                    <h6 class="fw-bold text-dark mb-2">Código de Rastreamento</h6>
                                    <p class="mb-0 fs-5 fw-bold text-info">{{ $pedido->codigo_rastreamento }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="p-3 rounded-3 shadow-sm bg-light">
                                    <h6 class="fw-bold text-dark mb-2">Data de Emissão</h6>
                                    <p class="mb-0">{{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Remetente e Destinatário --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 shadow-sm bg-light border-start border-4 border-warning">
                                    <h6 class="fw-bold text-dark mb-2">Remetente</h6>
                                    <p class="mb-1"><strong>Nome:</strong> {{ $pedido->notaFiscal->remetente->nome }}</p>
                                    <p class="mb-0"><strong>Endereço:</strong> {{ $pedido->notaFiscal->enderecoRemetente->logradouro ?? '---' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 shadow-sm bg-light border-start border-4 border-warning">
                                    <h6 class="fw-bold text-dark mb-2">Destinatário</h6>
                                    <p class="mb-1"><strong>Nome:</strong> {{ $pedido->notaFiscal->destinatario->nome }}</p>
                                    <p class="mb-0"><strong>Endereço:</strong> {{ $pedido->notaFiscal->enderecoDestinatario->logradouro ?? '---' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Timeline de histórico --}}
                        <h5 class="fw-bold text-dark mb-3">Histórico do Pedido</h5>
                        <div class="timeline">
                            @foreach ($pedido->historicos as $historico)
                                <div class="timeline-item">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-content shadow-sm">
                                        <span class="text-muted small d-block mb-1">
                                            {{ \Carbon\Carbon::parse($historico->data)->format('d/m/Y H:i') }}
                                        </span>

                                        {{-- Status resumido --}}
                                        <h6 class="fw-bold mb-1 text-dark">{{ $historico->status }}</h6>

                                        {{-- Descrição explicativa --}}
                                        <p class="mb-1 text-secondary">
                                            {{ $historico->descricao ?? 'O pedido passou por esta etapa do processo logístico.' }}
                                        </p>

                                        {{-- Localização opcional --}}
                                        @if (!empty($historico->local))
                                            <p class="mb-0"><i class="bi bi-geo-alt-fill text-warning"></i>
                                                {{ $historico->local }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Estilo extra --}}
    <style>
        body {
            background: #f8f9fa;
        }

        .timeline {
            position: relative;
            margin: 20px 0;
            padding-left: 40px;
            border-left: 3px solid #FFD700;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-dot {
            position: absolute;
            left: -11px;
            top: 0;
            width: 20px;
            height: 20px;
            background-color: #FFD700;
            border-radius: 50%;
            border: 2px solid #000;
        }

        .timeline-content {
            background: #ffffff;
            padding: 15px;
            border-radius: 10px;
        }

        .card input:focus {
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.7);
            border-color: #FFD700;
        }

        .card button {
            background-color: #FFD700;
            border: none;
            color: #000;
        }

        .card button:hover {
            background-color: #000 !important;
            color: #FFD700 !important;
            transition: 0.3s;
        }
    </style>
@endsection
