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

    <div class="container-fluid py-4">

        {{-- Cabeçalho --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold"><i class="bi bi-people-fill me-2"></i> Centro de Distribuição</h3>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNovoCentro">
                <i class="bi bi-person-plus-fill me-1"></i> Centro de Distribuição
            </button>
        </div>

        {{-- Filtros --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="busca" class="form-control" placeholder="Buscar por nome ou CPF"
                    value="{{ request('busca') }}">
            </div>
            <div class="col-md-3">
                <select name="tipo" class="form-select">
                    <option value="">Tipo de Usuário</option>
                    <option value="admin" {{ request('tipo') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="operador" {{ request('tipo') == 'operador' ? 'selected' : '' }}>Operador</option>
                    <option value="motorista" {{ request('tipo') == 'motorista' ? 'selected' : '' }}>Motorista</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Status</option>
                    <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                    <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search"></i> Filtrar
                </button>
            </div>
        </form>

        {{-- Tabela de motoristas --}}
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover align-middle table-bordered bg-white">
                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Cep</th>
                        <th>Cidade</th>
                        <th>Estado</th>
                        <th>Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                @foreach ($data as $cd)
                    <tbody>
                        <td>{{ $cd->id_centro_distribuicao }}</td>
                        <td>{{ $cd->nome }}</td>
                        <td>{{ $cd->cep }}</td>
                        <td>{{ $cd->cidade }}</td>
                        <td>{{ $cd->uf }}</td>
                        <td>{{ ucfirst($cd->status) }}</td>
                        <td>
                            <div class="d-flex">
                                <!-- Botão Visualizar -->
                                <button type="button" class="btn btn-warning btn-sm me-1" title="Visualizar"
                                    data-bs-toggle="modal" data-bs-target="#modalShow{{ $cd->id_centro_distribuicao }}">
                                    <i class="bi bi-eye-fill"></i>
                                </button>

                                <!-- Botão Editar -->
                                <button type="button" class="btn btn-primary btn-sm me-1" title="Editar"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit{{ $cd->id_centro_distribuicao }}">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>

                                <!-- Botão Excluir -->
                                <form action="{{ route('destroy-user', $cd->id_centro_distribuicao) }}" method="POST"
                                    onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Excluir">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>


                    </tbody>
                @endforeach

            </table>
        </div>
        <br>



        @include('centro.modais.novo')

    </div>
@endsection
