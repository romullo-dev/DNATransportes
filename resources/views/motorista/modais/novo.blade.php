<div class="modal fade" id="modalNovoMotorista" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('motorista.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Novo Motorista</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body row g-2">
                <div class="col-md-12">
                    <label for="usuario">Usuário</label>
                    <select name="id_Usuario" class="form-select" required>
                        <option value="">Selecione um usuário</option>
                        @foreach ($usuariosSelect as $usuario)
                            <option value="{{ $usuario->id_usuario }}">{{ $usuario->nome }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="col-md-6">
                    <label for="cnh">CNH</label>
                    <input name="cnh" class="form-control" required maxlength="20">
                </div>

                <div class="col-md-6">
                    <label for="categoria">Categoria</label>
                    <select name="categoria" class="form-select" required>
                        <option value="">Selecione</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                        <option value="AB">AB</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label for="validade_cnh">Validade da CNH</label>
                    <input type="date" name="validade_cnh" class="form-control" required>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Salvar</button>
            </div>
        </form>
    </div>
</div>