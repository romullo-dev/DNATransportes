@extends('layouts.app')

@section('content')
    {{-- Mensagens de sucesso e erro --}}
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
        {{-- 🔸 Cabeçalho --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-warning mb-3 mb-md-0">
                <i class="bi bi-truck me-2"></i> Rotas
            </h2>
            <a href="{{ route('rotas.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm fw-semibold">
                <i class="bi bi-plus-circle me-1"></i> Nova Rota
            </a>
        </div>

        {{-- 🔍 Filtros --}}
        <form method="GET" class="row g-3 align-items-end mb-4 bg-dark p-4 rounded-4 shadow-sm">
            <div class="col-md-4">
                <label class="form-label text-light fw-semibold"><i class="bi bi-search me-1"></i>Buscar(Placa &
                    Motorista)</label>
                <input type="text" name="busca" class="form-control bg-dark text-light border-secondary"
                    placeholder="Motorista, placa" value="{{ request('busca') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label text-light fw-semibold"><i class="bi bi-geo-alt me-1"></i>Tipo de Rota</label>
                <select name="tipo" class="form-select bg-dark text-light border-secondary">
                    <option value="">Todas</option>
                    <option value="Entrega" {{ request('tipo') == 'Entrega' ? 'selected' : '' }}>Entrega</option>
                    <option value="transferencia" {{ request('tipo') == 'transferencia' ? 'selected' : '' }}>Transferência
                    </option>
                    <option value="coleta" {{ request('tipo') == 'coleta' ? 'selected' : '' }}>Coleta</option>
                </select>
            </div>

            <div class="col-md-3 col-12">
                <label class="form-label text-light fw-semibold mb-1">
                    <i class="bi bi-flag-fill text-white me-2"></i> UF (Destino)
                </label>
                <select name="uf" class="form-select border-secondary text-light" style="background-color:#212529;">
                    <option value="">Todas</option>
                    @foreach (['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'] as $uf)
                        <option value="{{ $uf }}" {{ request('uf') == $uf ? 'selected' : '' }}>{{ $uf }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 text-end">
                <button type="submit" class="btn btn-outline-warning w-100 rounded-pill fw-semibold">
                    <i class="bi bi-funnel me-1"></i> Filtrar
                </button>
            </div>
        </form>

        {{-- 📋 Tabela de Rotas --}}
        @if ($rota->isEmpty())
            <p class="text-center text-secondary mt-5">🚫 Nenhuma rota encontrada.</p>
        @else
            <div class="table-responsive rounded-4 shadow-lg overflow-hidden">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead style="background: linear-gradient(90deg,  #ffc107,  #be9312);">
                        <tr class="text-light">
                            <th>Data Início</th>
                            <th>Motorista</th>
                            <th>Placa</th>
                            <th>Tipo</th>
                            <th>Origem</th>
                            <th>Destino</th>
                            <th>Status Atual</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rota as $rotas)
                            <tr class="border-bottom border-secondary">
                                <td class="text-light">
                                    {{ \Carbon\Carbon::parse($rotas->data_inicio)->format('d/m/Y H:i') }}
                                </td>
                                <td class="fw-semibold">{{ $rotas->motorista->usuario->nome }}</td>
                                <td class="text-info fw-semibold">{{ $rotas->veiculo->placa }}</td>
                                <td>{{ ucfirst($rotas->tipo) }}</td>
                                <td>{{ $rotas->origem->uf ?? '-' }}</td>
                                <td>{{ $rotas->destino->uf ?? '-' }}</td>
                                <td>
                                    @php
                                        $status =
                                            optional($rotas->historicos->first())->status ??
                                            ($rotas->ultimo_status ?? 'Sem histórico');
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
                                            'Sem histórico' => 'bg-dark text-light',
                                            default => 'bg-secondary text-light',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill fw-semibold">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('rotas.show', $rotas->id_rotas) }}"
                                            class="btn btn-sm btn-outline-warning rounded-circle" title="Visualizar">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('rotas.romaneio', $rotas->id_rotas) }}" target="_blank"
                                            class="btn btn-sm btn-outline-light rounded-circle" title="Gerar Romaneio">
                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-info rounded-circle"
                                            data-bs-toggle="modal" data-bs-target="#modalEdit{{ $rotas->id_rotas }}"
                                            title="Movimentar">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </button>
                                        @if (strtolower((string) Auth::user()?->tipo_usuario) === 'admin')
                                            <a href="{{ route('rotas.admin.edit', $rotas->id_rotas) }}"
                                                class="btn btn-sm btn-outline-warning rounded-circle" title="Editar ADM">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        @endif
                                    </div>


                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Modais de Edição --}}
            @foreach ($rota as $rotas)
                @include('rotas.modais.edit', ['rotas' => $rotas])
            @endforeach
        @endif
    </div>

    <style>
        /* Modal overlay suave */
        .modal-backdrop.show {
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(3px);
        }

        /* Corpo do modal */
        .modal-content {
            background-color: #1b1e22;
            color: #f1f1f1;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
            overflow: hidden;
        }

        /* Cabeçalho */
        .modal-header {
            background: linear-gradient(90deg, #ffc107, #be9312);
            border-bottom: none;
            color: #fff;
            padding: 1rem 1.5rem;
        }

        .modal-header .modal-title {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .btn-close:hover {
            opacity: 1;
        }

        /* Corpo do formulário */
        .modal-body {
            background-color: #23272e;
            padding: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .form-label {
            font-weight: 600;
            color: #f0c02e;
            font-size: 0.9rem;
        }

        .form-control,
        .form-select {
            background-color: #1b1e22;
            color: #f1f1f1;
            border: 1px solid #343a40;
            border-radius: 0.6rem;
            transition: 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #2a9d8f;
            box-shadow: 0 0 0 0.25rem rgba(42, 157, 143, 0.25);
        }

        /* Botões do rodapé */
        .modal-footer {
            background-color: #1b1e22;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1rem 1.5rem;
        }

        .modal-footer .btn {
            border-radius: 30px;
            font-weight: 600;
            transition: 0.25s ease;
        }

        .btn-success,
        .btn-primary {
            background: linear-gradient(90deg, #e2aa02);
            border: none;
        }

        .btn-success:hover,
        .btn-primary:hover {
            background: linear-gradient(90deg, #ffc107);
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(42, 157, 143, 0.3);
        }

        .btn-secondary {
            background-color: #343a40;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #495057;
            transform: translateY(-1px);
        }

        /* Placeholders */
        ::placeholder {
            color: #a0a0a0 !important;
        }

        /* Inputs de arquivo */
        input[type="file"]::file-selector-button {
            background: #2a9d8f;
            color: #fff;
            border: none;
            padding: 0.4rem 0.8rem;
            border-radius: 0.5rem;
            margin-right: 0.8rem;
            transition: 0.2s;
        }

        input[type="file"]::file-selector-button:hover {
            background: #1f8574;
            cursor: pointer;
        }
    </style>

@endsection
