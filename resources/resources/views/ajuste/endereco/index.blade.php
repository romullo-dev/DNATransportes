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
        {{-- Filtros --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="busca" class="form-control" placeholder="Buscar por logradouro, bairro ou cidade" value="{{ request('busca') }}">
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

        {{-- Tabela de Endereços --}}
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover align-middle table-bordered bg-white">
                <thead class="table-light">
                    <tr>
                        <th>Logradouro</th>
                        <th>Bairro</th>
                        <th>Cidade</th>
                        <th>Estado</th>
                        <th>CEP</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($result as $endereco)
                        <tr>
                            <td>{{ $endereco->logradouro }}</td>
                            <td>{{ $endereco->bairro }}</td>
                            <td>{{ $endereco->cidade }}</td>
                            <td>{{ $endereco->uf }}</td>
                            <td>{{ $endereco->cep }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal"
                                    data-bs-target="#modalEdit{{ $endereco->id_endereco }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        <div class="d-flex justify-content-center">
            {{ $result->links('pagination::bootstrap-5') }}
        </div>

        {{-- Incluir Modal de Edição --}}
        @foreach ($result as $endereco)
            @include('ajuste.endereco.modais.edit', ['endereco' => $endereco])
        @endforeach
    </div>
@endsection
