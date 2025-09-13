<div class="modal fade" id="modalNovoUsuario" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('store-user') }}" enctype="multipart/form-data" class="modal-content" novalidate>
      @csrf
      <div class="modal-header" style="background-color: #101827;">
        <h5 class="modal-title text-white">Novo Usuário</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body row g-2">
        <!-- Nome -->
        <div class="col-md-12">
          <label>Nome</label>
          <input name="nome" class="form-control" required maxlength="100">
        </div>

        <!-- Usuário -->
        <div class="col-md-6">
          <label>Usuário</label>
          <input name="user" class="form-control" required minlength="4" maxlength="50">
        </div>

        <!-- Email -->
        <div class="col-md-6">
          <label>Email</label>
          <input name="email" type="email" class="form-control" required>
        </div>

        <!-- Senha -->
        <div class="col-md-6">
          <label>Senha</label>
          <input name="password" type="password" class="form-control" required minlength="6">
        </div>

        <!-- CPF -->
        <div class="col-md-6">
          <label>CPF</label>
          <input name="cpf" class="form-control" required minlength="11" maxlength="11" pattern="[0-9]{11,14}">
        </div>

        <!-- Telefone -->
        <div class="col-md-6">
          <label>Telefone</label>
          <input name="telefone" class="form-control" required minlength="10" maxlength="11" pattern="[0-9]{10,11}">
        </div>

        <!-- Tipo -->
        <div class="col-md-6">
          <label>Tipo</label>
          <select name="tipo_usuario" class="form-select" required>
            <option value="admin">Admin</option>
            <option value="operador">Torre</option>
            <option value="motorista">Motorista</option>
          </select>
        </div>

        <!-- Status (fixo ativo) -->
        <div class="col-md-6">
          <input type="hidden" name="status_funcionario" value="ativo">
        </div>

        <!-- Foto -->
        <div class="col-md-12">
          <label>Foto (opcional)</label>
          <input name="foto" type="file" class="form-control" accept="image/*">
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-success">Salvar</button>
      </div>
    </form>
  </div>
</div>
