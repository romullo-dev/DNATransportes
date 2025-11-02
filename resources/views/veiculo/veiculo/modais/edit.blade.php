@foreach ($modelos as $veiculo)
<div class="modal fade" id="modalEdit{{ $veiculo->id_Veiculo }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form method="POST" action="{{ route('veiculo.update', $veiculo->id_Veiculo) }}" class="modal-content border-0 rounded-4 shadow-lg">
      @csrf
      @method('PUT')

      <!-- Cabeçalho -->
      <div class="modal-header bg-dna-yellow text-dark border-0">
        <h5 class="modal-title fw-bold d-flex align-items-center">
          <i class="bi bi-pencil-square me-2"></i> Editar Veículo
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <!-- Corpo -->
      <div class="modal-body bg-dark text-light rounded-bottom-4">
        <div class="row g-3">

          <!-- Placa (readonly mas enviada) -->
          <div class="col-md-4">
            <label class="form-label text-warning fw-semibold"><i class="bi bi-car-front-fill me-1"></i> Placa</label>
            <input type="text" name="placa" class="form-control" value="{{ $veiculo->placa }}" maxlength="7" readonly>
          </div>

          <!-- Ano -->
          <div class="col-md-2">
            <label class="form-label text-warning fw-semibold"><i class="bi bi-calendar-event me-1"></i> Ano</label>
            <input type="number" name="ano" class="form-control" value="{{ $veiculo->ano }}" min="1900" max="2099" required>
          </div>

          <!-- Cor -->
          <div class="col-md-3">
            <label class="form-label text-warning fw-semibold"><i class="bi bi-palette-fill me-1"></i> Cor</label>
            <input type="text" name="cor" class="form-control" value="{{ $veiculo->cor }}" required>
          </div>

          <!-- Status -->
          <div class="col-md-3">
            <label class="form-label text-warning fw-semibold"><i class="bi bi-toggle-on me-1"></i> Status</label>
            <select name="status_veiculo" class="form-select" required>
              <option value="Ativo" {{ $veiculo->status_veiculo == 'Ativo' ? 'selected' : '' }}>Ativo</option>
              <option value="Inativo" {{ $veiculo->status_veiculo == 'Inativo' ? 'selected' : '' }}>Inativo</option>
            </select>
          </div>

          <!-- Modelo -->
          <div class="col-md-6">
            <label class="form-label text-warning fw-semibold"><i class="bi bi-truck-front-fill me-1"></i> Modelo</label>
            <select name="id_modelo_veiculo" class="form-select" required>
              @foreach ($modeloSelect as $item)
                <option value="{{ $item->id_modelo_veiculo }}" 
                  {{ $veiculo->id_modelo_veiculo == $item->id_modelo_veiculo ? 'selected' : '' }}>
                  {{ $item->modelo }} - {{ $item->marca }}
                </option>
              @endforeach
            </select>
          </div>

          <!-- RENAVAM (readonly, mas enviado) -->
          <div class="col-md-6">
            <label class="form-label text-warning fw-semibold"><i class="bi bi-123 me-1"></i> RENAVAM</label>
            <input type="text" name="renavam" class="form-control" value="{{ $veiculo->renavam }}" readonly>
          </div>

          <!-- Chassi (readonly, mas enviado) -->
          <div class="col-md-6">
            <label class="form-label text-warning fw-semibold"><i class="bi bi-upc-scan me-1"></i> Chassi</label>
            <input type="text" name="chassi" class="form-control" value="{{ $veiculo->chassi }}" readonly>
          </div>

          <!-- Tara -->
          <div class="col-md-3">
            <label class="form-label text-warning fw-semibold"><i class="bi bi-box-seam-fill me-1"></i> Tara (kg)</label>
            <input type="number" name="tara_kg" class="form-control" value="{{ $veiculo->tara_kg }}" step="0.01" required>
          </div>

          <!-- PBT -->
          <div class="col-md-3">
            <label class="form-label text-warning fw-semibold"><i class="bi bi-truck me-1"></i> PBT (kg)</label>
            <input type="number" name="pbt_kg" class="form-control" value="{{ $veiculo->pbt_kg }}" step="0.01" required>
          </div>

          <!-- Observações -->
          <div class="col-md-12">
            <label class="form-label text-warning fw-semibold"><i class="bi bi-pencil-square me-1"></i> Observações</label>
            <textarea name="observacoes" class="form-control" rows="3">{{ $veiculo->observacoes }}</textarea>
          </div>

        </div>
      </div>

      <!-- Rodapé -->
      <div class="modal-footer bg-dark border-top border-warning">
        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-warning text-dark fw-semibold rounded-pill px-4">
          <i class="bi bi-save-fill me-1"></i> Salvar Alterações
        </button>
      </div>

    </form>
  </div>
</div>
@endforeach

<style>
  .bg-dna-yellow {
    background-color: #ffc107 !important;
  }

  .form-control, .form-select {
    background-color: #1b1e22 !important;
    color: #fff !important;
    border: 1px solid #343a40;
    border-radius: 0.6rem;
  }

  .form-control:focus, .form-select:focus {
    border-color: #ffc107 !important;
    box-shadow: 0 0 0 0.25rem rgba(255,193,7,0.25) !important;
  }

  input[readonly] {
    background-color: #262b31 !important;
    opacity: 0.9;
  }

  .btn-warning:hover {
    background-color: #ffca2c !important;
    transform: scale(1.03);
    transition: 0.2s;
  }
</style>
