@foreach ($usuarios as $usuario)
    <div class="modal fade" id="modalEdit{{ $usuario->motorista->id_motorista ?? 'new' }}" tabindex="-1">
        <div class="modal-dialog">
            <!-- Verifique se o motorista existe e, se não, use uma rota diferente -->
            <form method="POST" action="{{ $usuario->motorista ? route('motorista.update', $usuario->motorista->id_motorista) : route('motorista.store') }}" enctype="multipart/form-data" class="modal-content">
                @csrf
                @method($usuario->motorista ? 'PUT' : 'POST')
                <div class="modal-header">
                    <h5 class="modal-title">Editar Motorista</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-2">
                    <div class="col-md-12">
                        <label>Nome</label>
                        <input name="nome" class="form-control" value="{{ $usuario->nome }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label>CPF</label>
                        <input name="cpf" class="form-control" maxlength="11" value="{{ $usuario->cpf }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label>CNH</label>
                        <input name="cnh" class="form-control" maxlength="11" value="{{ $usuario->motorista->cnh ?? '' }}" required>
                    </div>

                    <div class="col-md-6">
                        <label>Categoria</label>
                        <input name="categoria" class="form-control" maxlength="2" value="{{ $usuario->motorista->categoria ?? '' }}" required>
                    </div>

                    <div class="col-md-12">
                        <label for="validade_cnh">Validade da CNH</label>
                        <input type="date" name="validade_cnh" value="{{ old('validade_cnh', $usuario->motorista->validade_cnh ?? '') }}" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
