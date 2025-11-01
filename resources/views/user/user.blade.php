@extends('layouts.app')

@section('content')
    {{-- ✅ Mensagens de sucesso/erro --}}
    @if (session('success'))
        <div class="alert alert-success text-center fw-semibold rounded-pill shadow-sm mt-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger text-center fw-semibold rounded-pill shadow-sm mt-3" role="alert">
            <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="container-fluid py-5" style="background-color: #12181F; min-height: 100vh; color: #f1f1f1;">
        {{-- 🟡 Cabeçalho --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-warning mb-3 mb-md-0">
                <i class="bi bi-person-fill-gear me-2"></i> Usuários
            </h2>
            <button type="button" class="btn btn-warning text-dark rounded-pill px-4 shadow-sm fw-semibold"
                data-bs-toggle="modal" data-bs-target="#modalNovoUsuario" style="transition: all 0.3s;">
                <i class="bi bi-plus-circle me-1"></i> Novo Usuário
            </button>
        </div>

        {{-- 🔍 Filtros --}}
        <form method="GET" class="row g-3 align-items-end mb-4 bg-dark p-4 rounded-4 shadow-sm border border-secondary"
            action="{{ route('usuarios.procurar') }}">
            <div class="col-md-4">
                <label class="form-label text-light fw-semibold"><i class="bi bi-search me-1"></i>Buscar</label>
                <input type="text" name="busca" class="form-control bg-dark text-light border-secondary"
                    placeholder="Nome ou CPF..." value="{{ request('busca') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label text-light fw-semibold"><i class="bi bi-person-badge me-1"></i>Tipo</label>
                <select name="tipo" class="form-select bg-dark text-light border-secondary">
                    <option value="">Todos</option>
                    <option value="admin" {{ request('tipo') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="operador" {{ request('tipo') == 'operador' ? 'selected' : '' }}>Operador</option>
                    <option value="motorista" {{ request('tipo') == 'motorista' ? 'selected' : '' }}>Motorista</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label text-light fw-semibold"><i class="bi bi-toggle-on me-1"></i>Status</label>
                <select name="status" class="form-select bg-dark text-light border-secondary">
                    <option value="">Todos</option>
                    <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                    <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>

            <div class="col-md-2 text-end">
                <button type="submit" class="btn btn-outline-warning w-100 rounded-pill fw-semibold">
                    <i class="bi bi-funnel me-1"></i> Filtrar
                </button>
            </div>
        </form>

        {{-- 📋 Tabela de Usuários --}}
        <div class="table-responsive rounded-4 shadow-lg overflow-hidden border border-secondary">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead style="background: linear-gradient(90deg, #eb8721, #d67400);">
                    <tr class="text-light text-center">
                        <th>Data</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Tipo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr class="border-bottom border-secondary">
                            <td class="text-center">{{ $usuario->created_at->format('d/m/Y') }}</td>
                            <td class="fw-semibold text-warning">{{ $usuario->nome }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->telefone }}</td>
                            <td>{{ ucfirst($usuario->tipo_usuario) }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- Visualizar -->
                                    <button type="button" class="btn btn-sm btn-outline-warning rounded-circle"
                                        title="Visualizar" data-bs-toggle="modal"
                                        data-bs-target="#modalShow{{ $usuario->id_usuario }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    <!-- Editar -->
                                    <button type="button" class="btn btn-sm btn-outline-info rounded-circle" title="Editar"
                                        data-bs-toggle="modal" data-bs-target="#modalEdit{{ $usuario->id_usuario }}">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>

                                    <!-- Excluir -->
                                    <form action="{{ route('destroy-user', $usuario->id_usuario) }}" method="POST"
                                        onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle"
                                            title="Excluir">
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

        {{-- Paginação --}}
        <div class="d-flex justify-content-center mt-4">
{{ $usuarios->appends(request()->query())->links('pagination::simple-bootstrap-5') }}
        </div>


        {{-- Modais --}}
        @include('User.modais.novo')

        @foreach ($usuarios as $usuario)
            @include('User.modais.edit', ['usuario' => $usuario])
        @endforeach

        @foreach ($usuarios as $usuario)
            @include('User.modais.show', ['usuario' => $usuario])
        @endforeach
    </div>

    {{-- 🎨 Estilo DNA Transportes --}}
    <style>
        /* 🎨 Paginação DNA Transportes */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.4rem;
            background-color: transparent;
            padding: 0.6rem;
            border-radius: 0.6rem;
        }

        .page-item {
            margin: 0 3px;
        }

        .page-item .page-link {
            background-color: #1b1f27;
            color: #eb8721;
            border: 1px solid #2a2f3a;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.25s ease-in-out;
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.3);
        }

        .page-item .page-link:hover {
            background-color: #eb8721;
            color: #12181f;
            border-color: #eb8721;
            transform: translateY(-2px);
        }

        .page-item.active .page-link {
            background-color: #eb8721;
            color: #12181f;
            border-color: #eb8721;
            font-weight: 700;
            box-shadow: 0 0 10px rgba(235, 135, 33, 0.5);
        }

        .page-item.disabled .page-link {
            background-color: #23272e;
            color: #777;
            border-color: #2a2f3a;
            cursor: not-allowed;
        }

        .page-link:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(235, 135, 33, 0.4);
        }
    </style>
@endsection
