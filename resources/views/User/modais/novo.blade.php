<div class="modal fade" id="modalNovoUsuario" tabindex="-1" >
    <div class="modal-dialog"  >
        <form method="POST" action="{{ route('store-user') }}" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header" style="background-color: #101827;">
                <h5 class="modal-title text-white">Novo Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-2">
                <div class="col-md-12">
                    <label>Nome</label>
                    <input name="nome" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Usuário</label>
                    <input name="user" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Email</label>
                    <input name="email" type="email" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Senha</label>
                    <input name="password" type="password" class="form-control"  required>
                </div>
                <div class="col-md-6">
                    <label>CPF</label>
                    <input name="cpf" class="form-control" maxlength="11">
                </div>
                <div class="col-md-6">
                    <label>Telefone</label>
                    <input name="telefone" class="form-control" maxlength="11">
                </div>
                <div class="col-md-6">
                    <label>Tipo</label>
                    <select name="tipo_usuario" class="form-select" required>
                        <option value="admin">Admin</option>
                        <option value="operador">Torre</option>
                        <option value="motorista">Motorista</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="hidden"  name="status_funcionario"  value="ativo">
                </div>
                <div class="col-md-12">
                    <label>Foto (opcional)</label>
                    <input name="foto" type="file" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success">Salvar</button>
            </div>
        </form>
    </div>
</div>
