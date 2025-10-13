@extends('layouts.app')

@section('content')
<div class="container py-5" style="min-height: 90vh;">
    <div class="row justify-content-center">
        <div class="col-md-10">

            {{-- 🟡 Cabeçalho DNA Transportes --}}
            <div class="text-center mb-5">
                <i class="bi bi-truck-front text-warning" style="font-size: 3rem;"></i>
                <h2 class="fw-bold text-light mt-2 mb-1">DNA Transportes</h2>
                <p class="text-secondary mb-0">Rastreamento de Pedido</p>
            </div>

            {{-- 🗂️ Card Principal --}}
            <div class="card bg-dark text-light border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center"
                     style="background: #1a1a1a; border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <h4 class="mb-0 fw-semibold">
                        <i class="bi bi-box-seam me-2 text-warning"></i>Pedido #{{ $pedido->id_pedido }}
                    </h4>
                    <span class="badge bg-warning text-dark fw-semibold px-3 py-2">
                        {{ strtoupper(optional($pedido->historicos->last())->status ?? 'EM PROCESSAMENTO') }}
                    </span>
                </div>

                <div class="card-body p-4">

                    {{-- 🔹 Infos principais --}}
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="bg-black rounded-3 p-3 shadow-sm">
                                <small class="text-secondary d-block mb-1">
                                    <i class="bi bi-upc-scan me-1 text-warning"></i> Código de Rastreamento
                                </small>
                                <span class="fw-bold text-light">{{ $pedido->codigo_rastreamento }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="bg-black rounded-3 p-3 shadow-sm">
                                <small class="text-secondary d-block mb-1">
                                    <i class="bi bi-calendar-event me-1 text-warning"></i> Data de Emissão
                                </small>
                                <span class="fw-bold text-light">{{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- 👥 Remetente e Destinatário --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="bg-black rounded-3 p-3 shadow-sm border-start border-3 border-warning">
                                <h6 class="fw-bold text-warning mb-2"><i class="bi bi-person-fill me-1"></i> Remetente</h6>
                                <p class="mb-1"><i class="bi bi-person-badge text-secondary me-1"></i>
                                    {{ $pedido->notaFiscal->remetente->nome }}</p>
                                <p class="mb-0"><i class="bi bi-geo-alt text-secondary me-1"></i>
                                    {{ $pedido->notaFiscal->enderecoRemetente->logradouro ?? '---' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-black rounded-3 p-3 shadow-sm border-start border-3 border-warning">
                                <h6 class="fw-bold text-warning mb-2"><i class="bi bi-house-fill me-1"></i> Destinatário</h6>
                                <p class="mb-1"><i class="bi bi-person-badge text-secondary me-1"></i>
                                    {{ $pedido->notaFiscal->destinatario->nome }}</p>
                                <p class="mb-0"><i class="bi bi-geo-alt text-secondary me-1"></i>
                                    {{ $pedido->notaFiscal->enderecoDestinatario->logradouro ?? '---' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- ⏳ Timeline --}}
                    <h5 class="fw-semibold text-warning mb-3">
                        <i class="bi bi-clock-history me-2"></i> Histórico do Pedido
                    </h5>

                    <div class="timeline">
                        @foreach ($pedido->historicos as $historico)
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <span class="small text-secondary d-block mb-1">
                                        <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($historico->data)->format('d/m/Y H:i') }}
                                    </span>

                                    <h6 class="fw-bold text-light mb-1">
                                        <i class="bi bi-truck me-1 text-warning"></i>{{ $historico->status }}
                                    </h6>
                                    <p class="mb-1 text-secondary">
                                        {{ $historico->descricao ?? 'O pedido avançou nesta etapa.' }}
                                    </p>

                                    @if (!empty($historico->local))
                                        <p class="mb-0 text-light small">
                                            <i class="bi bi-geo-alt-fill text-warning me-1"></i>
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

{{-- 💅 Estilo DNA Clean --}}
<style>
    body {
        background: #0b0b0b;
    }

    .timeline {
        position: relative;
        margin: 25px 0;
        padding-left: 35px;
        border-left: 2px solid rgba(255, 215, 0, 0.4);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }

    .timeline-dot {
        position: absolute;
        left: -10px;
        top: 5px;
        width: 14px;
        height: 14px;
        background-color: #FFD700;
        border-radius: 50%;
        box-shadow: 0 0 6px rgba(255, 215, 0, 0.7);
    }

    .timeline-content {
        background: #111;
        padding: 12px 16px;
        border-radius: 8px;
        transition: 0.3s;
    }

    .timeline-content:hover {
        background: #1a1a1a;
    }

    .bg-black {
        background-color: #111 !important;
    }

    .text-warning {
        color: #FFD700 !important;
    }

    .text-secondary {
        color: #9a9a9a !important;
    }

    .card {
        transition: all 0.3s ease-in-out;
    }

    .card:hover {
        transform: translateY(-3px);
    }

    .badge {
        font-size: 0.9rem;
    }
</style>
@endsection
