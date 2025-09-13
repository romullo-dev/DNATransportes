<div class="modal fade" id="modalNovoCentro" tabindex="-1" aria-labelledby="modalNovoCentroLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('centro.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalNovoCentroLabel">Novo Centro de Distribuição</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <!-- Nome -->
                    <div class="col-md-6">
                        <label class="form-label" for="nome"><i class="bi bi-building me-1"></i>Nome</label>
                        <input type="text" name="nome" id="nome" maxlength="225" class="form-control"
                            required>
                    </div>

                    <!-- CEP -->
                    <div class="col-md-3">
                        <label class="form-label" for="cep"><i class="bi bi-mailbox me-1"></i>CEP</label>
                        <input type="text" name="cep" id="cep" maxlength="8" class="form-control"
                            placeholder="Ex: 01001000" required>
                    </div>

                    <!-- Logradouro -->
                    <div class="col-md-6">
                        <label class="form-label" for="logradouro"><i class="bi bi-geo-alt me-1"></i>Logradouro</label>
                        <input type="text" name="logradouro" id="logradouro" maxlength="225" class="form-control"
                            readonly>
                    </div>

                    <!-- Bairro -->
                    <div class="col-md-6">
                        <label class="form-label" for="bairro"><i
                                class="bi bi-house-door-fill me-1"></i>Bairro</label>
                        <input type="text" name="bairro" id="bairro" maxlength="225" class="form-control"
                            readonly>
                    </div>



                    <!-- Cidade -->
                    <div class="col-md-4">
                        <label class="form-label" for="cidade"><i class="bi bi-geo-alt-fill me-1"></i>Cidade</label>
                        <input type="text" name="cidade" id="cidade" maxlength="225" class="form-control"
                            readonly>
                    </div>

                    <!-- UF -->
                    <div class="col-md-2">
                        <label class="form-label" for="uf"><i class="bi bi-flag-fill me-1"></i>UF</label>
                        <input type="text" name="uf" id="uf" maxlength="2"
                            class="form-control text-uppercase" readonly>
                    </div>

                    <!-- Latitude -->
                    <div class="col-md-6">
                        <label class="form-label" for="latitude"><i class="bi bi-compass-fill me-1"></i>Latitude</label>
                        <input type="text" name="latitude" id="latitude" maxlength="45" class="form-control"
                            required>
                    </div>

                    <!-- Longitude -->
                    <div class="col-md-6">
                        <label class="form-label" for="longitude"><i class="bi bi-compass me-1"></i>Longitude</label>
                        <input type="text" name="longitude" id="longitude" maxlength="45" class="form-control"
                            required>
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <label class="form-label" for="status"><i class="bi bi-toggle-on me-1"></i>Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="Ativo">Ativo</option>
                            <option value="Inativo">Inativo</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Salvar
                </button>
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>Limpar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('cep').addEventListener('blur', function() {
        const cep = this.value.replace(/\D/g, '');

        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('cidade').value = data.localidade || '';
                        document.getElementById('uf').value = data.uf || '';
                        document.getElementById('logradouro').value = data.logradouro || '';
                        document.getElementById('bairro').value = data.bairro || '';

                    } else {
                        alert('CEP não encontrado!');
                        document.getElementById('cidade').value = '';
                        document.getElementById('uf').value = '';
                    }
                })
                .catch(() => {
                    alert('Erro ao consultar CEP.');
                });
        } else {
            alert('CEP inválido!');
        }
    });
</script>
