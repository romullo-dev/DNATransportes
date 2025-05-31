<!-- SELECTOR -->
<div class="row mb-3">
    <div class="col-md-4">
        <label for="tipo_rota" class="form-label">
            <i class="bi bi-signpost-split-fill me-1"></i>Tipo da Rota
        </label>
        <select class="form-select" id="tipo_rota" name="status_rota" required>
            <option value="" selected disabled>Selecione o tipo</option>
            <option value="coleta">Coleta</option>
            <option value="transferencia">Transferência</option>
            <option value="distribuicao">Distribuição</option>
            <option value="transbordo">Transbordo</option>
            <option value="devolucao">Devolução</option>
            <option value="dedicada">Dedicada</option>
        </select>
    </div>
</div>

<!-- FORM COLETA -->
<div id="form_coleta" class="formulario" style="display: none;">
    <form>
        <div class="mb-3">
            <label for="coleta_endereco" class="form-label">Endereço de Coleta</label>
            <input type="text" class="form-control" id="coleta_endereco" placeholder="Rua, número, cidade...">
        </div>
        <div class="mb-3">
            <label for="coleta_data" class="form-label">Data da Coleta</label>
            <input type="date" class="form-control" id="coleta_data">
        </div>
        <button type="submit" class="btn btn-primary">Salvar Coleta</button>
    </form>
</div>

<!-- FORM TRANSFERÊNCIA -->
<div id="form_transferencia" class="formulario" style="display: none;">
    <form>
        <div class="mb-3">
            <label for="cd_origem" class="form-label">Centro de Distribuição Origem</label>
            <input type="text" class="form-control" id="cd_origem">
        </div>
        <div class="mb-3">
            <label for="cd_destino" class="form-label">Centro de Distribuição Destino</label>
            <input type="text" class="form-control" id="cd_destino">
        </div>
        <button type="submit" class="btn btn-success">Salvar Transferência</button>
    </form>
</div>

<!-- FORM DISTRIBUIÇÃO -->
<div id="form_distribuicao" class="formulario" style="display: none;">
    <form>
        <div class="mb-3">
            <label for="entrega_cliente" class="form-label">Nome do Cliente</label>
            <input type="text" class="form-control" id="entrega_cliente">
        </div>
        <div class="mb-3">
            <label for="entrega_previsao" class="form-label">Previsão de Entrega</label>
            <input type="date" class="form-control" id="entrega_previsao">
        </div>
        <button type="submit" class="btn btn-warning">Salvar Distribuição</button>
    </form>
</div>

<!-- FORM TRANSBORDO -->
<div id="form_transbordo" class="formulario" style="display: none;">
    <form>
        <div class="mb-3">
            <label for="local_transbordo" class="form-label">Local do Transbordo</label>
            <input type="text" class="form-control" id="local_transbordo">
        </div>
        <button type="submit" class="btn btn-info">Salvar Transbordo</button>
    </form>
</div>

<!-- FORM DEVOLUÇÃO -->
<div id="form_devolucao" class="formulario" style="display: none;">
    <form>
        <div class="mb-3">
            <label for="motivo_devolucao" class="form-label">Motivo da Devolução</label>
            <textarea class="form-control" id="motivo_devolucao" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-danger">Salvar Devolução</button>
    </form>
</div>

<!-- FORM DEDICADA -->
<div id="form_dedicada" class="formulario" style="display: none;">
    <form>
        <div class="mb-3">
            <label for="cliente_dedicado" class="form-label">Cliente Dedicado</label>
            <input type="text" class="form-control" id="cliente_dedicado">
        </div>
        <div class="mb-3">
            <label for="rota_fixa" class="form-label">Rota Fixa</label>
            <input type="text" class="form-control" id="rota_fixa">
        </div>
        <button type="submit" class="btn btn-secondary">Salvar Dedicada</button>
    </form>
</div>

<!-- SCRIPT MÁGICO -->
<script>
    document.getElementById('tipo_rota').addEventListener('change', function () {
        const tipoSelecionado = this.value;
        const formularios = document.querySelectorAll('.formulario');

        formularios.forEach(form => {
            form.style.display = 'none';
        });

        const formAtivo = document.getElementById('form_' + tipoSelecionado);
        if (formAtivo) {
            formAtivo.style.display = 'block';
        }
    });
</script>
