@extends('layouts.app')

@section('content')
    {{-- ✅ Mensagens de sucesso/erro --}}
     @if (session('success'))
        <div class="alert alert-warning text-center fw-semibold rounded-pill shadow-sm mt-3 border-0 text-dark" role="alert"
            style="background-color: #ffc107; color: #1b1e22;">
            <i class="bi bi-check-circle-fill me-2 text-dark"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger text-center fw-semibold rounded-pill shadow-sm mt-3" role="alert">
            <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="container-fluid py-5" style="background-color: #12181F; min-height: 100vh; color: #f1f1f1;">

        {{-- 🔍 Filtros --}}
        <form method="GET" class="row g-3 align-items-end mb-4 bg-dark p-4 rounded-4 shadow-sm">
            <div class="col-md-4 col-12">
                <label class="form-label text-light fw-semibold"><i class="bi bi-search me-1"></i>Buscar(NFe & Código) </label>
                <input type="text" name="busca" class="form-control bg-dark text-light border-secondary"
                    placeholder="Buscar por nome ou CPF" value="{{ request('busca') }}">
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label text-light fw-semibold"><i class="bi bi-search me-1"></i>Remetente </label>
                <input type="text" name="busca" class="form-control bg-dark text-light border-secondary"
                    placeholder="Buscar por nome ou CPF" value="{{ request('busca') }}">
            </div>

            <div class="col-md-2">
        <label class="form-label text-light fw-semibold mb-1">
            <i class="bi bi-flag-fill text-warning me-2"></i> UF
        </label>
        <select name="uf" class="form-select border-secondary text-light" style="background-color:#212529;">
            <option value="">Todas</option>
            @foreach (['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf)
                <option value="{{ $uf }}" {{ request('uf') == $uf ? 'selected' : '' }}>{{ $uf }}</option>
            @endforeach
        </select>
    </div>

            <div class="col-md-2 col-12 text-end">
                <button type="submit" class="btn btn-outline-warning w-100 rounded-pill fw-semibold">
                    <i class="bi bi-funnel me-1"></i> Filtrar
                </button>
            </div>
        </form>

        {{-- 📋 Tabela de Pedidos --}}
        <div class="table-responsive rounded-4 shadow-lg overflow-hidden">
            <table class="table table-dark table-hover align-middle mb-0" style="font-size: 0.9rem;">
                <thead style="background: linear-gradient(90deg, #017aaa, #2a9d8f);">
                    <tr class="text-light">
                        <th>Data de Emissão</th>
                        <th>Cliente</th>
                        <th>Destinatário</th>
                        <th>CNPJ do Cliente</th>
                        <th>UF</th>
                        <th>Valor do Frete</th>
                        <th>Número da Nota</th>
                        <th>Código de Rastreamento</th>
                        <th>Status da Nota</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($result as $pedido)
                        <tr class="border-bottom border-secondary">
                            <td>{{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y') }}</td>
                            <td class="fw-semibold">{{ $pedido->notaFiscal->remetente->nome }}</td>
                            <td>{{ $pedido->notaFiscal->destinatario->nome }}</td>
                            <td>{{ $pedido->notaFiscal->destinatario->documento }}</td>
                            <td>{{ $pedido->notaFiscal->enderecoRemetente->uf }}</td>
                            <td>
                                @if ($pedido->frete)
                                    <span class="text-success fw-semibold">
                                        R$ {{ number_format($pedido->frete->valor_frete, 2, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-muted fst-italic">Sem frete</span>
                                @endif
                            </td>

                            <td>{{ $pedido->notaFiscal->numero_nfe }}</td>
                            <td class="text-info fw-semibold">{{ $pedido->codigo_rastreamento }}</td>
                            <td>
                                @php
                                    $ultimoHistorico = $pedido->historicos->first();
                                    $status = $ultimoHistorico?->status ?? 'Sem histórico';

                                    $badgeClass = match ($status) {
                                        'Aguardando coleta' => 'bg-secondary text-light',
                                        'Em processo de coleta' => 'bg-info text-dark',
                                        'Coleta realizada' => 'bg-success',
                                        'Coleta não realizada' => 'bg-danger',
                                        'Aguardando transferência' => 'bg-secondary text-light',
                                        'Em processo de transferência' => 'bg-warning text-dark',
                                        'Transferência realizada' => 'bg-success',
                                        'Transferência não realizada' => 'bg-danger',
                                        'Em processo de separação no destino' => 'bg-info text-dark',
                                        'Em rota de entrega' => 'bg-primary',
                                        'Entrega realizada' => 'bg-success',
                                        'Entrega não realizada' => 'bg-danger',
                                        'Finalizado' => 'bg-success',
                                        'Cancelado' => 'bg-danger',
                                        'Sem histórico' => 'bg-dark text-light',
                                        default => 'bg-secondary text-light',
                                    };
                                @endphp


                                <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill fw-semibold">
                                    {{ $status }}
                                </span>
                            </td>


                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    {{-- Visualizar --}}
                                    <button class="btn btn-sm btn-outline-warning rounded-circle shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#modalShow{{ $pedido->id_pedido }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    {{-- Histórico --}}
                                    <a href="{{ route('pedidos.edit', $pedido->id_pedido) }}"
                                        class="btn btn-sm btn-outline-info rounded-circle shadow-sm text-white">
                                        <i class="bi bi-clock-history"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <br>

        {{-- 📄 Paginação --}}
        <div class="d-flex justify-content-center">
            {{ $result->links('pagination::bootstrap-5') }}
        </div>

        {{-- 📦 Modal: Visualizar Pedido --}}
        @include('pedido.modais.show')
    </div>

    {{-- 🎨 Estilo DNA Transportes --}}
    <style>
        body {
            background-color: #12181F !important;
        }

        .table-dark.table-hover tbody tr:hover {
            background-color: rgba(240, 192, 46, 0.08) !important;
        }

        .form-control,
        .form-select {
            border: 1px solid #2a2f36;
            transition: 0.25s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #2a9d8f;
            box-shadow: 0 0 0 0.2rem rgba(42, 157, 143, 0.25);
        }

        .btn-outline-warning {
            color: #f0c02e;
            border-color: #f0c02e;
            transition: 0.3s ease;
        }

        .btn-outline-warning:hover {
            background-color: #f0c02e;
            color: #12181F;
            box-shadow: 0 0 10px rgba(240, 192, 46, 0.3);
        }

        .btn-outline-info {
            color: #2a9d8f;
            border-color: #2a9d8f;
            transition: 0.3s ease;
        }

        .btn-outline-info:hover {
            background-color: #2a9d8f;
            color: #fff;
            box-shadow: 0 0 10px rgba(42, 157, 143, 0.3);
        }

        .badge {
            font-size: 0.85rem;
        }

        .pagination .page-link {
            background-color: #1b1e22;
            color: #f0c02e;
            border: none;
        }

        .pagination .page-link:hover {
            background-color: #2a9d8f;
            color: #fff;
        }

        .pagination .active .page-link {
            background-color: #f0c02e !important;
            color: #12181F !important;
        }

        .text-muted {
            color: #a0a0a0 !important;
        }
    </style>
@endsection
