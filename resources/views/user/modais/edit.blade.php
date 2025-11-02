<div class="modal fade" id="modalEdit{{ $usuario->id_usuario }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('update-user', $usuario->id_usuario) }}" enctype="multipart/form-data"
            class="modal-content border-0 shadow-lg rounded-4">
            @csrf
            @method('PUT')

            <div class="modal-header text-white"
                style="background: linear-gradient(90deg, #be9312, #ffc107); border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-gear me-2"></i>Editar Usuário</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body row g-3" style="background-color: #1b1f27; color: #f1f1f1;">
                <div class="col-md-12">
                    <label class="form-label text-warning fw-semibold">Nome</label>
                    <input name="nome" class="form-control border-0 shadow-sm"
                        style="background-color:#2a2f3a; color:#fff;" value="{{ $usuario->nome }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-warning fw-semibold">Usuário</label>
                    <input name="user" class="form-control border-0 shadow-sm"
                        style="background-color:#2a2f3a; color:#fff;" value="{{ $usuario->user }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-warning fw-semibold">Email</label>
                    <input name="email" type="email" class="form-control border-0 shadow-sm"
                        style="background-color:#2a2f3a; color:#fff;" value="{{ $usuario->email }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-warning fw-semibold">CPF</label>
                    <input name="cpf" class="form-control border-0 shadow-sm"
                        style="background-color:#2a2f3a; color:#fff;" maxlength="11" value="{{ $usuario->cpf }}"
                        disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-warning fw-semibold">Telefone</label>
                    <input name="telefone" class="form-control border-0 shadow-sm"
                        style="background-color:#2a2f3a; color:#fff;" maxlength="11" value="{{ $usuario->telefone }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label text-warning fw-semibold">Tipo</label>
                    <select name="tipo_usuario" class="form-select border-0 shadow-sm"
                        style="background-color:#2a2f3a; color:#fff;" required>
                        <option value="admin" {{ $usuario->tipo_usuario === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="operador" {{ $usuario->tipo_usuario === 'operador' ? 'selected' : '' }}>operador</option>
                        <option value="cliente" {{ $usuario->tipo_usuario === 'cliente' ? 'selected' : '' }}>Cliente</option>
                        <option value="motorista" {{ $usuario->tipo_usuario === 'motorista' ? 'selected' : '' }}>Motorista</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-warning fw-semibold">Status</label>
                    <select name="status_funcionario" class="form-select border-0 shadow-sm"
                        style="background-color:#2a2f3a; color:#fff;" required>
                        <option value="Ativo" {{ $usuario->status_funcionario === 'Ativo' ? 'selected' : '' }}>Ativo
                        </option>
                        <option value="Inativo" {{ $usuario->status_funcionario === 'Inativo' ? 'selected' : '' }}>
                            Inativo</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label text-warning fw-semibold">Foto (opcional)</label>
                    <input name="foto" type="file" class="form-control border-0 shadow-sm"
                        style="background-color:#2a2f3a; color:#fff;">
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-between"
                style="background-color:#12181f; border-top:1px solid #2a2f3a; border-bottom-left-radius:1rem; border-bottom-right-radius:1rem;">
                <button type="button" class="btn btn-outline-light rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancelar
                </button>
                <button class="btn rounded-pill px-4 fw-semibold text-dark"
                    style="background-color:#ffc107; border:none;">
                    <i class="bi bi-check2-circle me-1"></i>Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
