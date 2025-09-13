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
            <h3 class="fw-bold"><i class="bi bi-people-fill me-2"></i>Motoristas</h3>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNovoMotorista">
                <i class="bi bi-person-plus-fill me-1"></i> Novo Motorista
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
                        <th>Data de Criação</th>
                        <th>Nome</th>
                        <th>CNH</th>
                        <th>Categoria</th>
                        <th>Validade</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        @if ($usuario->motorista)
                            <tr>
                                <td>{{ $usuario->motorista->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>{{ $usuario->nome }}</td>
                                <td>{{ $usuario->motorista->cnh }}</td>
                                <td>{{ $usuario->motorista->categoria }}</td>
                                <td>{{ \Carbon\Carbon::parse($usuario->motorista->validade_cnh)->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal"
                                        data-bs-target="#modalShow{{ $usuario->motorista->id_motorista }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal"
                                        data-bs-target="#modalEdit{{ $usuario->motorista->id_motorista }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <form action="{{ route('destroy-user', $usuario->id_usuario) }}" method="post"
                                        style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Tem certeza que quer apagar o usuário {{ $usuario->nome }}?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhum motorista encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <br>

        <div class="d-flex justify-content-center">
            {{ $usuarios->links('pagination::bootstrap-5') }}

        </div>

        {{-- Modal para cadastro de motorista --}}
        @include('motorista.modais.novo')

        @foreach ($usuarios as $usuario)
            @include('motorista.modais.show', ['usuario' => $usuario])
        @endforeach

        @foreach ($usuarios as $usuario)
            @include('motorista.modais.edit', ['usuario' => $usuario])
        @endforeach
    </div>
@endsection
