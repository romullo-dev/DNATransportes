@extends('layouts.app')

@section('content')
    @php
        $selecionados = collect(old('pedido_ids', $pedidoIdsSelecionados ?? []))->map(fn ($id) => (int) $id);
        $entregues = collect($pedidoIdsEntregues ?? [])->map(fn ($id) => (int) $id);
    @endphp

    <div class="container-fluid py-4 rota-admin-page">
        @if (session('success'))
            <div class="alert alert-warning text-center fw-semibold rounded-pill shadow-sm border-0 text-dark">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger text-center fw-semibold rounded-pill shadow-sm">
                <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger rounded-4 shadow-sm">
                <strong>Revise os campos:</strong>
                {{ $errors->first() }}
            </div>
        @endif

        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center mb-4">
            <div>
                <span class="text-warning fw-semibold small text-uppercase">Edição administrativa</span>
                <h2 class="fw-bold text-light mb-1">
                    <i class="bi bi-map-fill text-warning me-2"></i>Rota #{{ $rota->id_rotas }}
                </h2>
                <p class="text-secondary mb-0">{{ ucfirst($rota->tipo) }} · {{ $rota->status ?? 'Sem status' }}</p>
            </div>

            <a href="{{ route('rotas.index') }}" class="btn btn-outline-warning rounded-pill px-4 fw-semibold">
                <i class="bi bi-arrow-left me-1"></i>Voltar
            </a>
        </div>

        @if ($entregues->isNotEmpty())
            <div class="alert alert-warning border-0 rounded-4 text-dark fw-semibold shadow-sm">
                <i class="bi bi-lock-fill me-2"></i>
                Pedidos entregues ficam bloqueados para alteração nesta rota.
            </div>
        @endif

        <form method="POST" action="{{ route('rotas.admin.update', $rota) }}" class="row g-4">
            @csrf
            @method('PUT')

            <div class="col-xl-5">
                <div class="card dna-card h-100">
                    <div class="card-header dna-card-header">
                        <i class="bi bi-sliders2 me-2"></i>Dados da rota
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Motorista</label>
                                <select name="id_motorista" class="form-select dna-input" required>
                                    @foreach ($motoristas as $motorista)
                                        <option value="{{ $motorista->id_motorista }}"
                                            @selected((int) old('id_motorista', $rota->id_motorista) === (int) $motorista->id_motorista)>
                                            {{ $motorista->usuario->nome ?? 'Motorista' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Veículo</label>
                                <select name="id_veiculo" class="form-select dna-input" required>
                                    @foreach ($veiculos as $veiculo)
                                        <option value="{{ $veiculo->id_Veiculo }}"
                                            @selected((int) old('id_veiculo', $rota->id_veiculo) === (int) $veiculo->id_Veiculo)>
                                            {{ $veiculo->placa }} · {{ $veiculo->capacidade_kg ?? '0' }} kg
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Origem</label>
                                <select name="id_origem" class="form-select dna-input" required>
                                    @foreach ($centros as $centro)
                                        <option value="{{ $centro->id_centro_distribuicao }}"
                                            @selected((int) old('id_origem', $rota->id_origem) === (int) $centro->id_centro_distribuicao)>
                                            {{ $centro->nome }} · {{ $centro->uf }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Destino</label>
                                <select name="id_destino" class="form-select dna-input">
                                    @foreach ($centros as $centro)
                                        <option value="{{ $centro->id_centro_distribuicao }}"
                                            @selected((int) old('id_destino', $rota->id_destino) === (int) $centro->id_centro_distribuicao)>
                                            {{ $centro->nome }} · {{ $centro->uf }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status da rota</label>
                                <select name="status" class="form-select dna-input" required>
                                    @foreach ($statusRotas as $status)
                                        <option value="{{ $status }}" @selected(old('status', $rota->status) === $status)>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Observações</label>
                                <textarea name="observacoes" class="form-control dna-input" rows="3">{{ old('observacoes', $rota->observacoes) }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Motivo da alteração</label>
                                <textarea name="motivo_alteracao" class="form-control dna-input" rows="4" required>{{ old('motivo_alteracao') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-7">
                <div class="card dna-card mb-4">
                    <div class="card-header dna-card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-box-seam-fill me-2"></i>Pedidos vinculados</span>
                        <span class="badge bg-warning text-dark">{{ $pedidosVinculados->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Pedido</th>
                                        <th>NFe</th>
                                        <th>Cliente</th>
                                        <th>Status</th>
                                        <th class="text-center">Vínculo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pedidosVinculados as $pedido)
                                        @php
                                            $pedidoEntregue = $entregues->contains((int) $pedido->id_pedido);
                                            $statusAtual = $pedido->statusAtual();
                                        @endphp
                                        <tr>
                                            <td class="fw-semibold text-warning">#{{ $pedido->id_pedido }}</td>
                                            <td>{{ $pedido->notaFiscal->numero_nfe ?? '--' }}</td>
                                            <td>{{ $pedido->notaFiscal->destinatario->nome ?? '--' }}</td>
                                            <td>
                                                <span class="badge {{ $pedidoEntregue ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                                                    {{ $statusAtual }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if ($pedidoEntregue)
                                                    <input type="hidden" name="pedido_ids[]" value="{{ $pedido->id_pedido }}">
                                                    <button type="button" class="btn btn-sm btn-outline-warning rounded-pill"
                                                        onclick="alert('Este pedido já foi entregue e não pode entrar em uma nova rota.')">
                                                        <i class="bi bi-lock-fill me-1"></i>Bloqueado
                                                    </button>
                                                @else
                                                    <input class="form-check-input dna-check" type="checkbox" name="pedido_ids[]"
                                                        value="{{ $pedido->id_pedido }}" @checked($selecionados->contains((int) $pedido->id_pedido))>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-secondary py-4">Nenhum pedido vinculado.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card dna-card">
                    <div class="card-header dna-card-header">
                        <i class="bi bi-plus-circle-fill me-2"></i>Adicionar pedidos
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @forelse ($pedidosDisponiveis as $pedido)
                                <div class="col-md-6">
                                    <label class="pedido-option">
                                        <input class="form-check-input dna-check mt-1" type="checkbox" name="pedido_ids[]"
                                            value="{{ $pedido->id_pedido }}" @checked($selecionados->contains((int) $pedido->id_pedido))>
                                        <span>
                                            <strong>#{{ $pedido->id_pedido }}</strong>
                                            <small>NFe {{ $pedido->notaFiscal->numero_nfe ?? '--' }}</small>
                                            <small>{{ $pedido->notaFiscal->destinatario->nome ?? 'Destinatário não informado' }}</small>
                                        </span>
                                    </label>
                                </div>
                            @empty
                                <div class="col-12 text-secondary">Nenhum pedido disponível para adicionar.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('rotas.show', $rota->id_rotas) }}" class="btn btn-outline-light rounded-pill px-4">
                    <i class="bi bi-eye-fill me-1"></i>Ver rota
                </a>
                <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">
                    <i class="bi bi-save-fill me-1"></i>Salvar alterações
                </button>
            </div>
        </form>
    </div>

    <style>
        body {
            background-color: #12181f !important;
        }

        .rota-admin-page {
            color: #f1f1f1;
        }

        .dna-card {
            background: #181d24;
            border: 1px solid rgba(255, 193, 7, 0.16);
            border-radius: 8px;
            overflow: hidden;
        }

        .dna-card-header {
            background: linear-gradient(90deg, #ffc107, #be9312);
            color: #101318;
            font-weight: 800;
            border: 0;
        }

        .dna-input {
            background-color: #222832;
            border: 1px solid #343c48;
            color: #f8f9fa;
            border-radius: 8px;
        }

        .dna-input:focus {
            background-color: #222832;
            border-color: #ffc107;
            color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.22);
        }

        .form-label {
            color: #ffc107;
            font-weight: 700;
        }

        .pedido-option {
            display: flex;
            gap: 12px;
            min-height: 92px;
            padding: 14px;
            background: #11161d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            cursor: pointer;
        }

        .pedido-option:hover {
            border-color: rgba(255, 193, 7, 0.55);
            background: #171d25;
        }

        .pedido-option span {
            display: grid;
            gap: 2px;
        }

        .pedido-option small {
            color: #adb5bd;
        }

        .dna-check {
            border-color: #ffc107;
        }

        .dna-check:checked {
            background-color: #ffc107;
            border-color: #ffc107;
        }
    </style>
@endsection
