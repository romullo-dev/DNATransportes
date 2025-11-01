<div class="modal fade" id="modalNovoUsuario" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('store-user') }}" enctype="multipart/form-data" class="modal-content border-0 shadow-lg">
            @csrf

            <!-- Cabeçalho -->
            <div class="modal-header" style="background: linear-gradient(90deg, #017aaa, #2a9d8f); color: #fff;">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Novo Usuário</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- Corpo -->
            <div class="modal-body row g-3 px-4 py-3" style="background-color: #1b1e22; color: #f1f1f1;">
                <!-- Nome -->
                <div class="col-md-12">
                    <label class="form-label"><i class="bi bi-person-fill me-1"></i>Nome</label>
                    <input name="nome" class="form-control" required maxlength="100">
                </div>

                <!-- Usuário -->
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-person-badge-fill me-1"></i>Usuário</label>
                    <input name="user" class="form-control" required minlength="4" maxlength="50">
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-envelope-fill me-1"></i>Email</label>
                    <input name="email" type="email" class="form-control" required>
                </div>

                <!-- Senha -->
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-lock-fill me-1"></i>Senha</label>
                    <input name="password" type="password" class="form-control" required minlength="6">
                </div>

                <!-- CPF -->
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-credit-card-2-front-fill me-1"></i>CPF</label>
                    <input name="cpf" class="form-control" required minlength="11" maxlength="11" pattern="[0-9]{11,14}">
                </div>

                <!-- Telefone -->
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-telephone-fill me-1"></i>Telefone</label>
                    <input name="telefone" class="form-control" required minlength="10" maxlength="11" pattern="[0-9]{10,11}">
                </div>

                <!-- Tipo -->
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-person-gear me-1"></i>Tipo</label>
                    <select name="tipo_usuario" class="form-select" required>
                        <option value="admin">Admin</option>
                        <option value="operador">Operador</option>
                        <option value="cliente">Cliente</option>
                        <option value="motorista">Motorista</option>
                    </select>
                </div>

                <input type="hidden" name="status_funcionario" value="ativo">

                <!-- Foto -->
                <div class="col-md-12">
                    <label class="form-label"><i class="bi bi-camera-fill me-1"></i>Foto (opcional)</label>
                    <input name="foto" type="file" class="form-control" accept="image/*">
                </div>
            </div>

            <!-- Rodapé -->
            <div class="modal-footer" style="background-color: #12181F; border-top: 1px solid #2a9d8f;">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancelar
                </button>
                <button type="submit" class="btn text-white fw-semibold" style="background-color: #2a9d8f; border: none;">
                    <i class="bi bi-check-circle me-1"></i>Salvar
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .form-control,
    .form-select {
        background-color: #23272e;
        color: #fff;
        border: 1px solid #343a40;
        border-radius: 0.5rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #2a9d8f;
        box-shadow: 0 0 0 0.25rem rgba(42, 157, 143, 0.25);
    }

    input[type="file"]::file-selector-button {
        background: #2a9d8f;
        color: #fff;
        border: none;
        padding: 0.4rem 0.8rem;
        border-radius: 0.4rem;
        margin-right: 0.8rem;
        transition: 0.3s;
    }

    input[type="file"]::file-selector-button:hover {
        background: #1f8574;
    }

    .modal-content {
        border-radius: 1rem;
        overflow: hidden;
    }
</style>
