<div class="modal fade" id="modalNovoVeiculo" tabindex="-1" aria-labelledby="modalNovoVeiculoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('veiculo.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalNovoVeiculoLabel">Novo Veículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <!-- Placa -->
                    <div class="col-md-4">
                        <label class="form-label" for="placa"><i class="bi bi-car-front-fill me-1"></i>Placa</label>
                        <input type="text" name="placa" id="placa" maxlength="7" class="form-control"
                            required>
                    </div>

                    <!-- Ano -->
                    <div class="col-md-2">
                        <label class="form-label" for="ano"><i class="bi bi-calendar-event me-1"></i>Ano</label>
                        <input type="number" name="ano" id="ano"  class="form-control" min="1900"
                            max="2099" required>
                    </div>

                    <!-- Cor -->
                    <div class="col-md-3">
                        <label class="form-label" for="cor"><i class="bi bi-palette-fill me-1"></i>Cor</label>
                        <input type="text" name="cor" id="cor" class="form-control" required>
                    </div>

                    <!-- Status -->
                    <div class="col-md-3">
                        <label class="form-label" for="status_veiculo"><i
                                class="bi bi-toggle-on me-1"></i>Status</label>
                        <select name="status_veiculo" id="status_veiculo" class="form-select" required>
                            <option value="Ativo">Ativo</option>
                            <option value="Inativo">Inativo</option>
                        </select>
                    </div>

                    <!-- Modelo -->
                    <div class="col-md-6">
                        <label class="form-label" for="id_modelo_veiculo"><i
                                class="bi bi-truck-front-fill me-1"></i>Modelo</label>
                        <select name="id_modelo_veiculo" id="id_modelo_veiculo" class="form-control" required>
                            <option value="" disabled selected>Selecione um modelo</option>
                            @foreach ($modeloSelect as $item)
                                <option value="{{ $item->id_modelo_veiculo }}">{{ $item->modelo }} - {{ $item->marca }}
                                </option>
                            @endforeach
                        </select>
                    </div>



                    <!-- Renavam -->
                    <div class="col-md-6">
                        <label class="form-label" for="renavam"><i class="bi bi-123 me-1"></i>RENAVAM</label>
                        <input type="text" name="renavam" id="renavam" class="form-control">
                    </div>

                    <!-- Chassi -->
                    <div class="col-md-6">
                        <label class="form-label" for="chassi"><i class="bi bi-upc-scan me-1"></i>Chassi</label>
                        <input type="text" name="chassi" id="chassi" class="form-control">
                    </div>

                    <!-- Tara -->
                    <div class="col-md-3">
                        <label class="form-label" for="tara_kg"><i class="bi bi-box-seam-fill me-1"></i>Tara
                            (kg)</label>
                        <input type="number" name="tara_kg" id="tara_kg" class="form-control" step="0.01"
                            required>
                    </div>

                    <!-- PBT -->
                    <div class="col-md-3">
                        <label class="form-label" for="pbt_kg"><i class="bi bi-truck me-1"></i>PBT (kg)</label>
                        <input type="number" name="pbt_kg" id="pbt_kg" class="form-control" step="0.01"
                            required>
                    </div>

                    <!-- Observações -->
                    <div class="col-md-12">
                        <label class="form-label" for="observacoes"><i
                                class="bi bi-pencil-square me-1"></i>Observações</label>
                        <textarea name="observacoes" id="observacoes" class="form-control" rows="3"></textarea>
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
