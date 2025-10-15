@foreach ($result as $endereco)
   <div class="modal fade" id="modalEdit{{ $endereco->id_endereco }}" tabindex="-1"
    aria-labelledby="modalLabelEdit{{ $endereco->id_endereco }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('endereco.update', $endereco->id_endereco) }}" enctype="multipart/form-data"
            class="modal-content bg-light">
            @csrf
            @method('PUT')

            <div class="modal-header" style="background: #2c313a; color: #ffc107;">
                <h5 class="modal-title" id="modalLabelEdit{{ $endereco->id_endereco }}">Editar Endereço</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="logradouro" class="form-label">Logradouro</label>
                            <input type="text" class="form-control" id="logradouro" name="logradouro" value="{{ $endereco->logradouro }}" readonly required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="cep" name="cep" value="{{ $endereco->cep }}" required onblur="buscarCep(this.value)">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="bairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="bairro" name="bairro" value="{{ $endereco->bairro }}" readonly required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="cidade" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="cidade" name="cidade" value="{{ $endereco->cidade }}" readonly required>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="uf" class="form-label">Estado (UF)</label>
                            <input type="text" class="form-control" id="uf" name="uf" value="{{ $endereco->uf }}" readonly required>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="casa" class="form-label">Número</label>
                            <input type="text" class="form-control" id="casa" name="casa" value="{{ $endereco->casa }}" required>
                        </div>
                    </div>
                </div>

                {{-- Observações --}}
                <div class="mb-3">
                    <label for="observacao" class="form-label">Observações</label>
                    <textarea class="form-control" id="observacao" name="observacao" rows="3">{{ $endereco->observacao }}</textarea>
                </div>

                {{-- Longitude e Latitude --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control" id="longitude" name="longitude" value="{{ $endereco->longitude }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control" id="latitude" name="latitude" value="{{ $endereco->latitude }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="background: #f8f9fa;">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-warning fw-bold">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>


@endforeach

<script>
    function buscarCep(cep) {
        if (cep.length == 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('logradouro').value = data.logradouro;
                        document.getElementById('bairro').value = data.bairro;
                        document.getElementById('cidade').value = data.localidade;
                        document.getElementById('uf').value = data.uf;
                    } else {
                        alert("CEP não encontrado!");
                    }
                })
                .catch(error => {
                    alert("Erro ao buscar o CEP.");
                    console.error(error);
                });
        } else {
            alert("CEP inválido!");
        }
    }
</script>
