@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4" style="color: #e0e0e0;">
        {{-- Mensagens de sucesso/erro --}}
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

        {{-- Cabeçalho --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-white">
                <i class="bi bi-geo-alt-fill me-2 text-warning"></i>Centro de Distribuição
            </h3>
            <button class="btn btn-warning fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNovoCentro"
                style="background-color:#ffc107; border:none;">
                <i class="bi bi-plus-circle-fill me-2"></i>Novo Centro
            </button>
        </div>

        {{-- 🏭 Filtros Centro de Distribuição --}}
<form method="GET" class="row g-3 mb-4 p-4 rounded-4 shadow-sm" style="background-color:#1f242d;">

    {{-- 🔍 Buscar por nome --}}
    <div class="col-md-4">
        <label class="form-label text-light fw-semibold mb-1">
            <i class="bi bi-building-fill text-warning me-2"></i> Nome 
        </label>
        <div class="input-group">
            <span class="input-group-text bg-dark border-0 text-warning">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" name="busca" class="form-control border-0 text-light"
                value="{{ request('busca') }}" style="background-color:#2a2f3a;">
        </div>
    </div>

    {{-- 🌆 Cidade --}}
    <div class="col-md-3">
        <label class="form-label text-light fw-semibold mb-1">
            <i class="bi bi-geo-alt-fill text-warning me-2"></i> Cidade
        </label>
        <input type="text" name="cidade" class="form-control border-0 text-light"
            value="{{ request('cidade') }}"
            style="background-color:#2a2f3a;">
    </div>

    {{-- 🚩 UF --}}
    <div class="col-md-2">
        <label class="form-label text-light fw-semibold mb-1">
            <i class="bi bi-flag-fill text-warning me-2"></i> UF
        </label>
        <select name="uf" class="form-select border-0 text-light" style="background-color:#2a2f3a;">
            <option value="">Todas</option>
            @foreach (['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf)
                <option value="{{ $uf }}" {{ request('uf') == $uf ? 'selected' : '' }}>{{ $uf }}</option>
            @endforeach
        </select>
    </div>

    {{-- ⚙️ Status --}}
    <div class="col-md-2">
        <label class="form-label text-light fw-semibold mb-1">
            <i class="bi bi-toggle2-on text-warning me-2"></i> Status
        </label>
        <select name="status" class="form-select border-0 text-light" style="background-color:#2a2f3a;">
            <option value="">Todos</option>
            <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
            <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
        </select>
    </div>

    {{-- 🔘 Botão Filtrar --}}
    <div class="col-md-1 d-flex align-items-end">
        <button type="submit" class="btn w-100 fw-semibold shadow-sm text-dark"
            style="background-color:#ffc107; border:none;">
            <i class="bi bi-funnel-fill me-1"></i>
        </button>
    </div>

</form>


        {{-- Tabela --}}
        <div class="table-responsive rounded-4 shadow-sm" style="overflow:hidden;">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead style="background:linear-gradient(90deg,#ffc107,#d67400); color:#fff;">
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
                                    <button type="button" class="btn btn-outline-warning btn-sm me-1" title="Visualizar"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalShow{{ $cd->id_centro_distribuicao }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    {{-- Editar --}}
                                    <button type="button" class="btn btn-outline-primary btn-sm me-1" title="Editar"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit{{ $cd->id_centro_distribuicao }}">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>

                                    {{-- Excluir --}}
                                    <form action="{{ route('centro.destroy', $cd->id_centro_distribuicao) }}"
                                        method="POST"
                                        onsubmit="return confirm('Tem certeza que deseja excluir o centro {{ $cd->nome }}?')">
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

        @include('centro.modais.show')
        @include('centro.modais.edit')

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
            border-color: #ffc107 !important;
            box-shadow: 0 0 5px rgba(235, 135, 33, 0.6) !important;
        }

        .btn:hover {
            transform: scale(1.05);
            transition: 0.2s ease-in-out;
        }
    </style>
@endpush
