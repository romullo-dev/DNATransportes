@extends('layouts.app')

@section('content')

    {{-- ✅ Mensagens de sucesso/erro --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center fw-semibold rounded-pill shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show text-center fw-semibold rounded-pill shadow-sm" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    <div class="container-fluid py-5" style="background-color: #12181F; min-height: 100vh; color: #f1f1f1;">

        {{-- 🚛 Cabeçalho --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-warning mb-0">
                <i class="bi bi-truck-front-fill me-2"></i> Veículos
            </h2>
            <button class="btn btn-warning fw-semibold rounded-pill px-4 shadow-sm"
        data-bs-toggle="modal" data-bs-target="#modalNovoVeiculo">
    <i class="bi bi-plus-circle me-1"></i> Novo Veículo
</button>

        </div>

        {{-- 🔍 Filtros --}}
        <form method="GET" class="row g-3 align-items-end mb-4 bg-dark p-4 rounded-4 shadow-sm">
            <div class="col-md-4">
                <label class="form-label text-light fw-semibold">
                    <i class="bi bi-search me-1"></i> Buscar
                </label>
                <input type="text" name="busca" class="form-control bg-dark text-light border-secondary"
                    placeholder="Buscar por nome ou CPF" value="{{ request('busca') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label text-light fw-semibold"><i class="bi bi-person-badge me-1"></i> Tipo</label>
                <select name="tipo" class="form-select bg-dark text-light border-secondary">
                    <option value="">Tipo de Usuário</option>
                    <option value="admin" {{ request('tipo') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="operador" {{ request('tipo') == 'operador' ? 'selected' : '' }}>Operador</option>
                    <option value="motorista" {{ request('tipo') == 'motorista' ? 'selected' : '' }}>Motorista</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label text-light fw-semibold"><i class="bi bi-toggle-on me-1"></i> Status</label>
                <select name="status" class="form-select bg-dark text-light border-secondary">
                    <option value="">Status</option>
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

        {{-- 📋 Tabela de Veículos --}}
        <div class="table-responsive rounded-4 shadow-lg overflow-hidden">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead style="background: linear-gradient(90deg, #017aaa, #2a9d8f);">
                    <tr class="text-light">
                        <th>Data de Criação</th>
                        <th>Placa</th>
                        <th>Ano</th>
                        <th>Categoria</th>
                        <th>Capacidade</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modelos as $veiculo)
                        @if ($veiculo->modelo_veiculo)
                        <tr class="border-bottom border-secondary">
                            <td>{{ $veiculo->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td class="fw-semibold text-warning">{{ $veiculo->placa }}</td>
                            <td>{{ $veiculo->ano }}</td>
                            <td>{{ $veiculo->modelo_veiculo->categoria }}</td>
                            <td>{{ $veiculo->capacidade_kg . ' Kg' }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-outline-warning rounded-circle" 
                                            data-bs-toggle="modal" data-bs-target="#modalShow{{ $veiculo->id_Veiculo }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-info rounded-circle"
                                            data-bs-toggle="modal" data-bs-target="#modalEdit{{ $veiculo->id_Veiculo }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <form action="#" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir o veículo {{ $veiculo->placa }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- 🔸 Paginação --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $modelos->links('pagination::bootstrap-5') }}
        </div>

        {{-- 🟢 Modal Novo Veículo --}}
        @include('veiculo.veiculo.modais.novo', ['modelos' => $modelos, 'modeloSelect' => $modeloSelect])
    </div>
<style>
/* ==============================
   🔧 DNA TRANSPORTES — MODAL VEÍCULO
   ============================== */

/* Fundo geral do modal */
.modal-content {
  background-color: #1b1e22 !important;
  color: #f1f1f1 !important;
  border: none;
  border-radius: 1rem;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
  overflow: hidden;
}

/* Cabeçalho */
.modal-header {
  background: linear-gradient(90deg, #017aaa, #2a9d8f);
  border-bottom: none;
  color: #fff;
  padding: 1rem 1.5rem;
}

.modal-header .modal-title {
  font-weight: 600;
  color: #FFD700;
}

.btn-close {
  filter: brightness(0) invert(1);
  opacity: 0.8;
}
.btn-close:hover { opacity: 1; }

/* Corpo do modal */
.modal-body {
  background-color: #23272e;
  padding: 1.5rem;
  color: #f1f1f1;
  font-size: 0.95rem;
}

/* Labels */
.form-label {
  color: #f0c02e;
  font-weight: 600;
}

/* Inputs e selects */
.form-control,
.form-select,
textarea {
  background-color: #1b1e22 !important;
  color: #f1f1f1 !important;
  border: 1px solid #343a40;
  border-radius: 0.6rem;
  padding: 0.5rem 0.75rem;
  transition: 0.3s;
}

.form-control:focus,
.form-select:focus {
  border-color: #2a9d8f;
  box-shadow: 0 0 0 0.25rem rgba(42, 157, 143, 0.25);
}

/* Placeholder mais visível */
::placeholder {
  color: #aaa !important;
  opacity: 1 !important;
}

/* Select visível */
.form-select option {
  background-color: #1b1e22;
  color: #f1f1f1;
}

/* Rodapé */
.modal-footer {
  background-color: #1b1e22;
  border-top: 1px solid rgba(255, 255, 255, 0.05);
  padding: 1rem 1.5rem;
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
}

/* Botões personalizados */
.btn-dna-save {
  background: linear-gradient(90deg, #017aaa, #2a9d8f);
  color: #fff;
  border: none;
  border-radius: 30px;
  padding: 0.5rem 1.5rem;
  font-weight: 600;
  transition: 0.3s ease;
}
.btn-dna-save:hover {
  background: linear-gradient(90deg, #2a9d8f, #017aaa);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(42, 157, 143, 0.3);
}

.btn-dna-clear {
  background-color: #343a40;
  color: #fff;
  border: none;
  border-radius: 30px;
  padding: 0.5rem 1.5rem;
  font-weight: 600;
  transition: 0.25s ease;
}
.btn-dna-clear:hover {
  background-color: #495057;
  transform: translateY(-1px);
}
</style>


@endsection
