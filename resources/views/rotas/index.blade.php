@extends('layouts.app')

@section('content')
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

    <div class="container-fluid py-4">

        {{-- Cabeçalho --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold"><i class="bi bi-truck me-2"></i> Rotas</h3>
            <a href="{{ route('rotas.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> Nova Rota
            </a>
        </div>

        {{-- Filtro --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="busca" class="form-control" placeholder="Buscar por motorista, placa, origem..."
                    value="{{ request('busca') }}">
            </div>
            <div class="col-md-3">
                <select name="tipo" class="form-select">
                    <option value="">Tipo de Rota</option>
                    <option value="Entrega" {{ request('tipo') == 'Entrega' ? 'selected' : '' }}>Entrega</option>
                    <option value="transferencia" {{ request('tipo') == 'transferencia' ? 'selected' : '' }}>Transferência</option>
                    <option value="coleta" {{ request('tipo') == 'coleta' ? 'selected' : '' }}>Coleta</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Status</option>
                    <option value="Em trânsito" {{ request('status') == 'Em trânsito' ? 'selected' : '' }}>Em trânsito</option>
                    <option value="Finalizado" {{ request('status') == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                    <option value="Cancelado" {{ request('status') == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search"></i> Filtrar
                </button>
            </div>
        </form>

        {{-- Tabela de Rotas --}}
        @if ($rota->isEmpty())
            <p class="text-center mt-4">Nenhuma rota encontrada.</p>
        @else
            <div class="table-responsive shadow-sm rounded">
                <table class="table table-hover align-middle table-bordered bg-white">
                    <thead class="table-light">
                        <tr>
                            <th>Data Início</th>
                            <th>Motorista</th>
                            <th>Placa</th>
                            <th>Tipo de Rota</th>
                            <th>Origem</th>
                            <th>Destino</th>
                            <th>Status Atual</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rota as $rotas)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($rotas->data_inicio)->format('d/m/Y H:i') }}</td>
                                <td>{{ $rotas->motorista->usuario->nome }}</td>
                                <td>{{ $rotas->veiculo->placa }}</td>
                                <td>{{ ucfirst($rotas->tipo) }}</td>
                                <td>{{ $rotas->origem->uf ?? '-' }}</td>
                                <td>{{ $rotas->destino->uf ?? '-' }}</td>
                                <td>
                                    {{ optional($rotas->historicos->last())->status ?? $rotas->ultimo_status ?? 'Sem histórico' }}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <!-- Botão Visualizar -->
                                        <a href="{{ route('rotas.show', $rotas->id_rotas) }}" class="btn btn-warning btn-sm" title="Visualizar">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>

                                        <!-- Botão Editar (abre modal) -->
                                        <button type="button" class="btn btn-primary btn-sm" title="Editar"
                                            data-bs-toggle="modal" data-bs-target="#modalEdit{{ $rotas->id_rotas }}">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>

                                        <!-- Botão Excluir -->
                                        <form action="#" method="POST" 
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta rota?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Excluir">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Modais de edição --}}
            @foreach ($rota as $rotas)
                @include('rotas.modais.edit', ['rotas' => $rotas])
            @endforeach
        @endif

    </div>
@endsection
