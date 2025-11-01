@extends('layouts.app')

@section('content')
    {{-- ✅ Mensagens de sucesso/erro --}}
    @if (session('success'))
        <div class="alert alert-success text-center fw-semibold rounded-pill shadow-sm" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger text-center fw-semibold rounded-pill shadow-sm" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="container-fluid py-5" style="background-color: #12181F; min-height: 100vh; color: #f1f1f1;">
        {{-- 🟠 Cabeçalho --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-warning mb-3 mb-md-0">
                <i class="bi bi-truck-front-fill me-2"></i> Motoristas
            </h2>
            <button class="btn btn-warning rounded-pill px-4 shadow-sm fw-semibold text-dark"
                data-bs-toggle="modal" data-bs-target="#modalNovoMotorista">
                <i class="bi bi-person-plus-fill me-1"></i> Novo Motorista
            </button>
        </div>

        {{-- 🔍 Filtros --}}
        <form method="GET" class="row g-3 align-items-end mb-4 bg-dark p-4 rounded-4 shadow-sm border border-secondary">
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

        {{-- 🚛 Tabela de Motoristas --}}
        <div class="table-responsive rounded-4 shadow-lg overflow-hidden border border-secondary">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead style="background: linear-gradient(90deg, #eb8721, #ffb84d);">
                    <tr class="text-dark fw-bold text-center">
                        <th>Data de Criação</th>
                        <th>Nome</th>
                        <th>CNH</th>
                        <th>Categoria</th>
                        <th>Validade</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        @if ($usuario->motorista)
                            <tr class="border-bottom border-secondary">
                                <td class="text-center">{{ $usuario->motorista->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="fw-semibold text-warning">{{ $usuario->nome }}</td>
                                <td class="text-center">{{ $usuario->motorista->cnh }}</td>
                                <td class="text-center">{{ $usuario->motorista->categoria }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($usuario->motorista->validade_cnh)->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Visualizar -->
                                        <button class="btn btn-sm btn-outline-warning rounded-circle"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalShow{{ $usuario->motorista->id_motorista }}"
                                            title="Visualizar">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>

                                        <!-- Editar -->
                                        <button class="btn btn-sm btn-outline-info rounded-circle"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEdit{{ $usuario->motorista->id_motorista }}"
                                            title="Editar">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>

                                        <!-- Excluir -->
                                        <form action="{{ route('motorista.destroy', $usuario->motorista->id_motorista) }}" method="post"
                                            style="display:inline"
                                            onsubmit="return confirm('Tem certeza que quer apagar o motorista {{ $usuario->motorista->id_motorista }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle"
                                                title="Excluir">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-3">🚫 Nenhum motorista encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 📄 Paginação --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $usuarios->links('pagination::bootstrap-5') }}
        </div>

        {{-- 🔧 Modais --}}
        @include('motorista.modais.novo')

        @foreach ($usuarios as $usuario)
            @include('motorista.modais.show', ['usuario' => $usuario])
        @endforeach

        @foreach ($usuarios as $usuario)
            @include('motorista.modais.edit', ['usuario' => $usuario])
        @endforeach
    </div>

    {{-- 🎨 Tema DNA Transportes --}}
    <style>
        /* Cores padrão DNA */
        :root {
            --dna-dark: #12181F;
            --dna-gray: #1e242c;
            --dna-orange: #eb8721;
            --dna-orange-light: #ffb84d;
            --dna-border: #2d333b;
        }

        .table-dark td,
        .table-dark th {
            color: #e0e0e0 !important;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(235, 135, 33, 0.1) !important;
        }

        .btn-outline-warning {
            border-color: var(--dna-orange);
            color: var(--dna-orange);
        }

        .btn-outline-warning:hover {
            background: linear-gradient(90deg, var(--dna-orange), var(--dna-orange-light));
            border: none;
            color: #000;
            box-shadow: 0 0 10px rgba(235, 135, 33, 0.4);
        }

        .modal-content {
            background-color: var(--dna-gray);
            color: #f1f1f1;
            border: 1px solid var(--dna-border);
            border-radius: 1rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
        }

        .modal-header {
            background: linear-gradient(90deg, var(--dna-orange), var(--dna-orange-light));
            color: #000;
            border-bottom: none;
            font-weight: 600;
        }

        .modal-footer {
            background-color: var(--dna-dark);
            border-top: 1px solid var(--dna-orange);
        }

        .form-control,
        .form-select {
            background-color: #23272e;
            color: #fff;
            border: 1px solid #343a40;
            border-radius: 0.6rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--dna-orange);
            box-shadow: 0 0 0 0.25rem rgba(235, 135, 33, 0.25);
        }

        input[type="file"]::file-selector-button {
            background: var(--dna-orange);
            color: #000;
            border: none;
            padding: 0.4rem 0.8rem;
            border-radius: 0.5rem;
            margin-right: 0.8rem;
            font-weight: 600;
        }

        input[type="file"]::file-selector-button:hover {
            background: var(--dna-orange-light);
            color: #000;
        }

        .btn-success {
            background: linear-gradient(90deg, var(--dna-orange), var(--dna-orange-light));
            border: none;
            color: #000;
            font-weight: 600;
        }

        .btn-success:hover {
            filter: brightness(1.1);
            box-shadow: 0 0 10px rgba(235, 135, 33, 0.5);
        }

        .btn-secondary {
            background-color: #444;
            border: none;
        }
    </style>
@endsection
