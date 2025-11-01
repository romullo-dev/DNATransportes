@foreach ($modelos as $veiculo)
    <div class="modal fade" id="modalEdit{{ $veiculo->id_Veiculo }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('veiculo.update', $veiculo->id_Veiculo) }}"
                class="modal-content border-0 rounded-4 shadow-lg">
                @csrf
                @method('PUT')

                <div class="modal-header text-white"
                    style="background: linear-gradient(90deg, #017aaa, #2a9d8f);">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2"></i> Editar Veículo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Fechar"></button>
                </div>

                <div class="modal-body bg-dark text-light">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-semibold mb-1 text-warning">Placa</label>
                            <input name="placa" class="form-control bg-dark text-light border-secondary rounded-pill"
                                value="{{ $veiculo->placa }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold mb-1 text-warning">Ano</label>
                            <input name="ano" type="number"
                                class="form-control bg-dark text-light border-secondary rounded-pill"
                                value="{{ $veiculo->ano }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold mb-1 text-warning">Capacidade (Kg)</label>
                            <input name="capacidade_kg" type="number"
                                class="form-control bg-dark text-light border-secondary rounded-pill"
                                value="{{ $veiculo->capacidade_kg }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-semibold mb-1 text-warning">Status</label>
                            <select name="status" class="form-select bg-dark text-light border-secondary rounded-pill">
                                <option value="ativo" {{ $veiculo->status === 'ativo' ? 'selected' : '' }}>Ativo
                                </option>
                                <option value="inativo" {{ $veiculo->status === 'inativo' ? 'selected' : '' }}>Inativo
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-dark border-0 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-check-circle me-1"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endforeach
