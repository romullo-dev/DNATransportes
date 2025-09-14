
<div class="modal fade" id="modalEdit{{ $usuario->id_usuario }}" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('update-user', $usuario->id_usuario) }}" enctype="multipart/form-data"
            class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Editar Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-2">
                <div class="col-md-12">
                    <label>Nome</label>
                    <input name="nome" class="form-control" value="{{ $usuario->nome }}" required>
                </div>
                <div class="col-md-6">
                    <label>Usuário</label>
                    <input name="user" class="form-control" value="{{ $usuario->user }}" disabled>
                </div>
                <div class="col-md-6">
                    <label>Email</label>
                    <input name="email" type="email" class="form-control" value="{{ $usuario->email }}" required>
                </div>
                <div class="col-md-6">
                    <label>CPF</label>
                    <input name="cpf" class="form-control" maxlength="11" value="{{ $usuario->cpf }}" disabled>
                </div>
                <div class="col-md-6">
                    <label>Telefone</label>
                    <input name="telefone" class="form-control" maxlength="11" value="{{ $usuario->telefone }}">
                </div>
                <div class="col-md-6">
                    <label>Tipo</label>
                    <select name="tipo_usuario" class="form-select" required>
                        <option value="admin" {{ $usuario->tipo_usuario === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="operador" {{ $usuario->tipo_usuario === 'operador' ? 'selected' : '' }}>Torre
                        </option>
                        <option value="motorista" {{ $usuario->tipo_usuario === 'motorista' ? 'selected' : '' }}>
                            Motorista
                        </option>
                    </select>
                </div>
                <label>Status</label>
                <select name="status_funcionario" class="form-select" required>
                    <option value="Ativo" {{ $usuario->status_funcionario === 'Ativo' ? 'selected' : '' }}>Ativo
                    </option>
                    <option value="Inativo" {{ $usuario->status_funcionario === 'Inativo' ? 'selected' : '' }}>Inativo
                    </option>

                </select>


                <div class="col-md-12">
                    <label>Foto (opcional)</label>
                    <input name="foto" type="file" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>
