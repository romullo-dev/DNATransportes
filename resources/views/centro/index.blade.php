@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="color: #e0e0e0;">
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

    {{-- Cabeçalho --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-white">
            <i class="bi bi-geo-alt-fill me-2 text-warning"></i>Centro de Distribuição
        </h3>
        <button class="btn btn-warning fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNovoCentro"
            style="background-color:#eb8721; border:none;">
            <i class="bi bi-plus-circle-fill me-2"></i>Novo Centro
        </button>
    </div>

    {{-- Filtros --}}
    <form method="GET" class="row g-3 mb-4 p-3 rounded-4 shadow-sm" style="background-color:#1f242d;">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-dark border-0 text-light"><i class="bi bi-search"></i></span>
                <input type="text" name="busca" class="form-control border-0 text-light"
                    placeholder="Buscar por nome ou CPF" value="{{ request('busca') }}"
                    style="background-color:#2a2f3a;">
            </div>
        </div>
        <div class="col-md-3">
            <select name="tipo" class="form-select border-0 text-light" style="background-color:#2a2f3a;">
                <option value="">Tipo de Usuário</option>
                <option value="admin" {{ request('tipo') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="operador" {{ request('tipo') == 'operador' ? 'selected' : '' }}>Operador</option>
                <option value="motorista" {{ request('tipo') == 'motorista' ? 'selected' : '' }}>Motorista</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select border-0 text-light" style="background-color:#2a2f3a;">
                <option value="">Status</option>
                <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn w-100 fw-semibold shadow-sm text-dark"
                style="background-color:#eb8721; border:none;">
                <i class="bi bi-funnel-fill me-1"></i>Filtrar
            </button>
        </div>
    </form>

    {{-- Tabela --}}
    <div class="table-responsive rounded-4 shadow-sm" style="overflow:hidden;">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead style="background:linear-gradient(90deg,#eb8721,#d67400); color:#fff;">
                <tr>
                    <th><i class="bi bi-hash me-1"></i>Código</th>
                    <th><i class="bi bi-building me-1"></i>Nome</th>
                    <th><i class="bi bi-geo-alt-fill me-1"></i>Cep</th>
                    <th><i class="bi bi-geo-fill me-1"></i>Cidade</th>
                    <th><i class="bi bi-map me-1"></i>Estado</th>
                    <th><i class="bi bi-toggle-on me-1"></i>Status</th>
                    <th class="text-center"><i class="bi bi-gear-fill me-1"></i>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $cd)
                    <tr>
                        <td>{{ $cd->id_centro_distribuicao }}</td>
                        <td>{{ $cd->nome }}</td>
                        <td>{{ $cd->cep }}</td>
                        <td>{{ $cd->cidade }}</td>
                        <td>{{ $cd->uf }}</td>
                        <td>
                            @if ($cd->status == 'Ativo')
                                <span class="badge bg-success px-3 py-2"><i class="bi bi-check2"></i> Ativo</span>
                            @else
                                <span class="badge bg-danger px-3 py-2"><i class="bi bi-x-lg"></i> Inativo</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center">
                                {{-- Visualizar --}}
                                <button type="button" class="btn btn-outline-warning btn-sm me-1"
                                    title="Visualizar" data-bs-toggle="modal"
                                    data-bs-target="#modalShow{{ $cd->id_centro_distribuicao }}">
                                    <i class="bi bi-eye-fill"></i>
                                </button>

                                {{-- Editar --}}
                                <button type="button" class="btn btn-outline-primary btn-sm me-1"
                                    title="Editar" data-bs-toggle="modal"
                                    data-bs-target="#modalEdit{{ $cd->id_centro_distribuicao }}">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>

                                {{-- Excluir --}}
                                <form action="{{ route('destroy-user', $cd->id_centro_distribuicao) }}" method="POST"
                                    onsubmit="return confirm('Tem certeza que deseja excluir este centro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Excluir">
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

    {{-- Modal Novo Centro --}}
    @include('centro.modais.novo')
</div>
@endsection

@push('styles')
<style>
    body {
        background-color: #12181f !important;
    }

    .table-dark tbody tr:hover {
        background-color: #2a2f3a !important;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #eb8721 !important;
        box-shadow: 0 0 5px rgba(235, 135, 33, 0.6) !important;
    }

    .btn:hover {
        transform: scale(1.05);
        transition: 0.2s ease-in-out;
    }
</style>
@endpush
