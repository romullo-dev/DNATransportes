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

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Usuários</h1>
            <!-- Botão para abrir o modal de novo usuário -->
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNovoUsuario">
                <i class="bi bi-plus-circle-fill"></i> Novo Usuário
            </button>
        </div>

        {{-- Filtros --}}
        <form method="GET" class="row g-2 mb-3" action="{{ route('usuarios.procurar') }}" >
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

        <!-- Tabela de usuários -->
        <div class="card shadow-sm rounded-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Data de criação</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Tipo</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usuarios as $usuario)
                                <tr>
                                    <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $usuario->nome }}</td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>{{ $usuario->telefone }}</td>
                                    <td>{{ ucfirst($usuario->tipo_usuario) }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <!-- Botão Visualizar -->
                                            <button type="button" class="btn btn-warning btn-sm me-1" title="Visualizar"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalShow{{ $usuario->id_usuario }}">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>

                                            <!-- Botão Editar -->
                                            <button type="button" class="btn btn-primary btn-sm me-1" title="Editar"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEdit{{ $usuario->id_usuario }}">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>

                                            <!-- Botão Excluir -->
                                            <form action="{{ route('destroy-user', $usuario->id_usuario) }}" method="POST"
                                                onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
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

                <!-- Paginação -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $usuarios->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Novo Usuário -->
    @include('User.modais.novo')

    <!-- Modal: Editar para cada usuário -->
    @foreach ($usuarios as $usuario)
        @include('User.modais.edit', ['usuario' => $usuario])
    @endforeach

    <!-- Modal: Visualizar para cada usuário (componente Blade) -->
    @foreach ($usuarios as $usuario)
        @include('User.modais.show', ['usuario' => $usuario])
    @endforeach
@endsection
