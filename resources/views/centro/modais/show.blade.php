@foreach ($data as $cd)
<div class="modal fade" id="modalShow{{ $cd->id_centro_distribuicao }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">

      <!-- Cabeçalho -->
      <div class="modal-header" style="background: linear-gradient(90deg, #eb8721, #d67400); color:#fff;">
        <h5 class="modal-title fw-bold d-flex align-items-center">
          <i class="bi bi-geo-alt-fill me-2"></i> Detalhes do Centro de Distribuição
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <!-- Corpo -->
      <div class="modal-body bg-dark text-light p-4">
        <div class="row g-3">
          
          <!-- Código -->
          <div class="col-md-3">
            <label class="fw-semibold text-warning"><i class="bi bi-hash me-1"></i> Código</label>
            <p class="border-bottom border-secondary pb-1 mb-3">{{ $cd->id_centro_distribuicao }}</p>
          </div>

          <!-- Nome -->
          <div class="col-md-9">
            <label class="fw-semibold text-warning"><i class="bi bi-building me-1"></i> Nome</label>
            <p class="border-bottom border-secondary pb-1 mb-3">{{ $cd->nome }}</p>
          </div>

          <!-- CEP -->
          <div class="col-md-4">
            <label class="fw-semibold text-warning"><i class="bi bi-mailbox me-1"></i> CEP</label>
            <p class="border-bottom border-secondary pb-1 mb-3">{{ $cd->cep }}</p>
          </div>

          <!-- Cidade -->
          <div class="col-md-5">
            <label class="fw-semibold text-warning"><i class="bi bi-geo-fill me-1"></i> Cidade</label>
            <p class="border-bottom border-secondary pb-1 mb-3">{{ $cd->cidade }}</p>
          </div>

          <!-- UF -->
          <div class="col-md-3">
            <label class="fw-semibold text-warning"><i class="bi bi-flag me-1"></i> UF</label>
            <p class="border-bottom border-secondary pb-1 mb-3">{{ $cd->uf }}</p>
          </div>

          <!-- Bairro -->
          <div class="col-md-6">
            <label class="fw-semibold text-warning"><i class="bi bi-house-door-fill me-1"></i> Bairro</label>
            <p class="border-bottom border-secondary pb-1 mb-3">{{ $cd->bairro ?? 'Não informado' }}</p>
          </div>

          <!-- Logradouro -->
          <div class="col-md-6">
            <label class="fw-semibold text-warning"><i class="bi bi-signpost-2-fill me-1"></i> Logradouro</label>
            <p class="border-bottom border-secondary pb-1 mb-3">{{ $cd->logradouro ?? 'Não informado' }}</p>
          </div>

          <!-- Latitude -->
          <div class="col-md-6">
            <label class="fw-semibold text-warning"><i class="bi bi-compass-fill me-1"></i> Latitude</label>
            <p class="border-bottom border-secondary pb-1 mb-3">{{ $cd->latitude }}</p>
          </div>

          <!-- Longitude -->
          <div class="col-md-6">
            <label class="fw-semibold text-warning"><i class="bi bi-compass me-1"></i> Longitude</label>
            <p class="border-bottom border-secondary pb-1 mb-3">{{ $cd->longitude }}</p>
          </div>

          <!-- Status -->
          <div class="col-md-12">
            <label class="fw-semibold text-warning"><i class="bi bi-toggle-on me-1"></i> Status</label>
            <p class="border-bottom border-secondary pb-1 mb-0">
              @if ($cd->status === 'Ativo')
                <span class="badge bg-success px-3 py-2"><i class="bi bi-check2"></i> Ativo</span>
              @else
                <span class="badge bg-danger px-3 py-2"><i class="bi bi-x-lg"></i> Inativo</span>
              @endif
            </p>
          </div>

        </div>
      </div>

      <!-- Rodapé -->
      <div class="modal-footer bg-dark border-0">
        <button type="button" class="btn btn-outline-warning rounded-pill px-4" data-bs-dismiss="modal">
          <i class="bi bi-arrow-left-circle me-1"></i> Fechar
        </button>
      </div>
    </div>
  </div>
</div>
@endforeach

<style>
  .modal-content {
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.6);
  }

  .border-bottom {
    border-color: rgba(255,255,255,0.1) !important;
  }

  .modal-body label {
    font-size: 0.9rem;
  }

  .modal-body p {
    font-size: 1rem;
    margin-bottom: 0.5rem;
  }

  .btn-outline-warning {
    border: 1px solid #ffc107 !important;
    color: #ffc107 !important;
  }

  .btn-outline-warning:hover {
    background-color: #ffc107 !important;
    color: #12181F !important;
  }
</style>
