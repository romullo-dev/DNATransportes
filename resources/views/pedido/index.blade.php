@extends('layouts.app')

@section('content')
    {{-- ✅ Mensagens de sucesso/erro --}}
    @if (session('success'))
        <div class="alert alert-success text-center fw-semibold rounded-pill shadow-sm" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger text-center fw-semibold rounded-pill shadow-sm" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="container-fluid py-5" style="background-color: #12181F; min-height: 100vh; color: #f1f1f1;">

        {{-- 🔍 Filtros --}}
        <form method="GET" class="row g-3 align-items-end mb-4 bg-dark p-4 rounded-4 shadow-sm">
            <div class="col-md-4 col-12">
                <label class="form-label text-light fw-semibold"><i class="bi bi-search me-1"></i>Buscar</label>
                <input type="text" name="busca" class="form-control bg-dark text-light border-secondary"
                    placeholder="Buscar por nome ou CPF" value="{{ request('busca') }}">
            </div>

            <div class="col-md-3 col-12">
                <label class="form-label text-light fw-semibold"><i class="bi bi-person-badge me-1"></i>Tipo de Usuário</label>
                <select name="tipo" class="form-select bg-dark text-light border-secondary">
                    <option value="">Tipo de Usuário</option>
                    {{-- <option value="admin" {{ request('tipo') == 'admin' ? 'selected' : '' }}>Admin</option> --}}
                </select>
            </div>

            <div class="col-md-3 col-12">
                <label class="form-label text-light fw-semibold"><i class="bi bi-check2-circle me-1"></i>Status</label>
                <select name="status" class="form-select bg-dark text-light border-secondary">
                    <option value="">Status</option>
                    {{-- <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option> --}}
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
                                    $ultimoHistorico = $pedido->historicos->last();
                                    $status = $ultimoHistorico?->status ?? 'Sem histórico';
                                    $badgeClass = match ($status) {
                                        'Em processo de transferência' => 'bg-warning text-dark',
                                        'Finalizado' => 'bg-success',
                                        'Cancelado' => 'bg-danger',
                                        'Entrega realizada' => 'bg-success',
                                        'Entrega não realizada' => 'bg-danger',
                                        default => 'bg-secondary',
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
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalShow{{ $pedido->id_pedido }}">
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
