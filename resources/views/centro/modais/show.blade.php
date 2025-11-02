@foreach ($data as $cd)
<div class="modal fade" id="modalShow{{ $cd->id_centro_distribuicao }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">

      {{-- Cabeçalho --}}
      <div class="modal-header bg-gradient-dna text-dark py-3 px-4">
        <h5 class="modal-title fw-bold d-flex align-items-center">
          <i class="bi bi-geo-alt-fill me-2"></i> Detalhes do Centro de Distribuição
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      {{-- Corpo --}}
      <div class="modal-body bg-dark text-light p-4">
        <div class="row g-4">

          {{-- Código --}}
          <div class="col-md-3">
            <label class="fw-semibold text-warning"><i class="bi bi-hash me-2"></i>Código</label>
            <div class="info-box">{{ $cd->id_centro_distribuicao }}</div>
          </div>

          {{-- Nome --}}
          <div class="col-md-9">
            <label class="fw-semibold text-warning"><i class="bi bi-building-fill-gear me-2"></i>Nome</label>
            <div class="info-box">{{ $cd->nome }}</div>
          </div>

          {{-- CEP --}}
          <div class="col-md-4">
            <label class="fw-semibold text-warning"><i class="bi bi-mailbox2 me-2"></i>CEP</label>
            <div class="info-box">{{ $cd->cep }}</div>
          </div>

          {{-- Cidade --}}
          <div class="col-md-5">
            <label class="fw-semibold text-warning"><i class="bi bi-geo-alt-fill me-2"></i>Cidade</label>
            <div class="info-box">{{ $cd->cidade }}</div>
          </div>

          {{-- UF --}}
          <div class="col-md-3">
            <label class="fw-semibold text-warning"><i class="bi bi-flag-fill me-2"></i>UF</label>
            <div class="info-box">{{ $cd->uf }}</div>
          </div>

          {{-- Bairro --}}
          <div class="col-md-6">
            <label class="fw-semibold text-warning"><i class="bi bi-house-door-fill me-2"></i>Bairro</label>
            <div class="info-box">{{ $cd->bairro ?? 'Não informado' }}</div>
          </div>

          {{-- Logradouro --}}
          <div class="col-md-6">
            <label class="fw-semibold text-warning"><i class="bi bi-signpost-2-fill me-2"></i>Logradouro</label>
            <div class="info-box">{{ $cd->logradouro ?? 'Não informado' }}</div>
          </div>

          {{-- Latitude --}}
          <div class="col-md-6">
            <label class="fw-semibold text-warning"><i class="bi bi-compass-fill me-2"></i>Latitude</label>
            <div class="info-box">{{ $cd->latitude }}</div>
          </div>

          {{-- Longitude --}}
          <div class="col-md-6">
            <label class="fw-semibold text-warning"><i class="bi bi-compass me-2"></i>Longitude</label>
            <div class="info-box">{{ $cd->longitude }}</div>
          </div>

          {{-- Status --}}
          <div class="col-md-12">
            <label class="fw-semibold text-warning"><i class="bi bi-toggle2-on me-2"></i>Status</label>
            <div class="info-box">
              @if (strtolower($cd->status) === 'ativo')
                <span class="badge bg-success px-3 py-2">
                  <i class="bi bi-check-circle-fill me-1"></i> Ativo
                </span>
              @else
                <span class="badge bg-danger px-3 py-2">
                  <i class="bi bi-x-circle-fill me-1"></i> Inativo
                </span>
              @endif
            </div>
          </div>

        </div>
      </div>

      {{-- Rodapé --}}
      <div class="modal-footer bg-dna-footer border-0 py-3">
        <button type="button" class="btn btn-outline-warning rounded-pill px-4" data-bs-dismiss="modal">
          <i class="bi bi-arrow-left-circle me-1"></i> Fechar
        </button>
      </div>
    </div>
  </div>
</div>
@endforeach

<style>
  /* === DNA TRANSPORTES — Modal CD === */
  .bg-gradient-dna {
    background: linear-gradient(90deg, #ffc107, #dca308, #b48200);
  }

  .bg-dna-footer {
    background-color: #151a1f !important;
  }

  .modal-content {
    border: none;
    background-color: #12181f !important;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.6);
  }

  .modal-header {
    border: none;
    color: #12181F;
  }

  .info-box {
    background-color: #1b1e22;
    border: 1px solid rgba(255, 193, 7, 0.2);
    border-radius: 0.6rem;
    padding: 0.6rem 0.75rem;
    font-size: 0.95rem;
    color: #f8f9fa;
    box-shadow: inset 0 0 6px rgba(255, 193, 7, 0.05);
    transition: background 0.3s ease;
  }

  .info-box:hover {
    background-color: #23272b;
  }

  .btn-outline-warning {
    border-color: #ffc107 !important;
    color: #ffc107 !important;
    font-weight: 600;
  }

  .btn-outline-warning:hover {
    background-color: #ffc107 !important;
    color: #12181F !important;
  }

  .badge {
    font-size: 0.9rem;
    border-radius: 0.5rem;
    font-weight: 600;
  }

  .text-warning {
    color: #ffc107 !important;
  }
</style>
