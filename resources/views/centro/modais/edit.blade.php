@foreach ($data as $cd)
<div class="modal fade" id="modalEdit{{ $cd->id_centro_distribuicao }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form method="POST" action="{{ route('centro.update', $cd->id_centro_distribuicao) }}" class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
      @csrf
      @method('PUT')

      <!-- Cabeçalho -->
      <div class="modal-header" style="background: linear-gradient(90deg, #eb8721, #d67400); color:#fff;">
        <h5 class="modal-title fw-bold d-flex align-items-center">
          <i class="bi bi-pencil-square me-2"></i> Editar Centro de Distribuição
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <!-- Corpo -->
      <div class="modal-body bg-dark text-light py-4 px-4">
        <div class="row g-3">
          
          <!-- Nome -->
          <div class="col-md-6">
            <label class="form-label text-warning fw-semibold">
              <i class="bi bi-building me-1"></i> Nome
            </label>
            <input type="text" name="nome" class="form-control border-0 shadow-sm" 
                   value="{{ $cd->nome }}" maxlength="225"
                   style="background-color:#2a2f3a; color:#fff;" required>
          </div>

          <!-- CEP -->
          <div class="col-md-3">
            <label class="form-label text-warning fw-semibold">
              <i class="bi bi-mailbox me-1"></i> CEP
            </label>
            <input type="text" name="cep" class="form-control border-0 shadow-sm" 
                   value="{{ $cd->cep }}" maxlength="9"
                   style="background-color:#2a2f3a; color:#fff;" required>
          </div>

          <!-- Cidade -->
          <div class="col-md-5">
            <label class="form-label text-warning fw-semibold">
              <i class="bi bi-geo-alt-fill me-1"></i> Cidade
            </label>
            <input type="text" name="cidade" class="form-control border-0 shadow-sm" 
                   value="{{ $cd->cidade }}" maxlength="225"
                   style="background-color:#2a2f3a; color:#fff;" required>
          </div>

          <!-- UF -->
          <div class="col-md-2">
            <label class="form-label text-warning fw-semibold">
              <i class="bi bi-flag-fill me-1"></i> UF
            </label>
            <input type="text" name="uf" class="form-control text-uppercase border-0 shadow-sm" 
                   value="{{ $cd->uf }}" maxlength="2"
                   style="background-color:#2a2f3a; color:#fff;" required>
          </div>

          <!-- Bairro -->
          <div class="col-md-5">
            <label class="form-label text-warning fw-semibold">
              <i class="bi bi-house-door-fill me-1"></i> Bairro
            </label>
            <input type="text" name="bairro" class="form-control border-0 shadow-sm" 
                   value="{{ $cd->bairro }}" maxlength="225"
                   style="background-color:#2a2f3a; color:#fff;">
          </div>

          <!-- Logradouro -->
          <div class="col-md-7">
            <label class="form-label text-warning fw-semibold">
              <i class="bi bi-signpost-2-fill me-1"></i> Logradouro
            </label>
            <input type="text" name="logradouro" class="form-control border-0 shadow-sm" 
                   value="{{ $cd->logradouro }}" maxlength="225"
                   style="background-color:#2a2f3a; color:#fff;">
          </div>

          <!-- Latitude -->
          <div class="col-md-6">
            <label class="form-label text-warning fw-semibold">
              <i class="bi bi-compass-fill me-1"></i> Latitude
            </label>
            <input type="text" name="latitude" class="form-control border-0 shadow-sm" 
                   value="{{ $cd->latitude }}" maxlength="13"
                   style="background-color:#2a2f3a; color:#fff;">
          </div>

          <!-- Longitude -->
          <div class="col-md-6">
            <label class="form-label text-warning fw-semibold">
              <i class="bi bi-compass me-1"></i> Longitude
            </label>
            <input type="text" name="longitude" class="form-control border-0 shadow-sm" 
                   value="{{ $cd->longitude }}" maxlength="13"
                   style="background-color:#2a2f3a; color:#fff;">
          </div>

          <!-- Status -->
          <div class="col-md-4">
            <label class="form-label text-warning fw-semibold">
              <i class="bi bi-toggle-on me-1"></i> Status
            </label>
            <select name="status" class="form-select border-0 shadow-sm" 
                    style="background-color:#2a2f3a; color:#fff;" required>
              <option value="Ativo" {{ $cd->status == 'Ativo' ? 'selected' : '' }}>Ativo</option>
              <option value="Inativo" {{ $cd->status == 'Inativo' ? 'selected' : '' }}>Inativo</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Rodapé -->
      <div class="modal-footer bg-dark border-0">
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
  .modal-content {
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.6);
  }

  .form-control, .form-select {
    background-color: #2a2f3a !important;
    color: #fff !important;
    border-radius: 0.6rem;
    border: 1px solid #343a40;
    transition: 0.2s ease-in-out;
  }

  .form-control:focus, .form-select:focus {
    border-color: #eb8721 !important;
    box-shadow: 0 0 5px rgba(235,135,33,0.6) !important;
  }

  .btn-warning:hover {
    background-color: #ffca2c !important;
    transform: scale(1.03);
    transition: 0.2s ease-in-out;
  }

  .btn-outline-secondary:hover {
    background-color: #343a40 !important;
    color: #fff !important;
  }
</style>
