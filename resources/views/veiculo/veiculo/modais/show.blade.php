@foreach ($modelos as $veiculo)
<div class="modal fade" id="modalShow{{ $veiculo->id_Veiculo }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">

      <!-- Cabeçalho Amarelo -->
      <div class="modal-header bg-dna-yellow text-dark py-3 px-4">
        <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
          <i class="bi bi-truck-front-fill fs-5"></i> 
          Detalhes do Veículo
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <!-- Corpo -->
      <div class="modal-body bg-dark text-light px-4 py-4">
        <div class="row g-3">

          <!-- Placa -->
          <div class="col-md-4">
            <label class="text-warning fw-semibold">Placa</label>
            <div class="info-box">{{ $veiculo->placa ?? '-' }}</div>
          </div>

          <!-- Ano -->
          <div class="col-md-2">
            <label class="text-warning fw-semibold">Ano</label>
            <div class="info-box">{{ $veiculo->ano ?? '-' }}</div>
          </div>

          <!-- Cor -->
          <div class="col-md-3">
            <label class="text-warning fw-semibold">Cor</label>
            <div class="info-box">{{ ucfirst($veiculo->cor ?? '-') }}</div>
          </div>

          <!-- Status -->
          <div class="col-md-3">
            <label class="text-warning fw-semibold">Status</label>
            <div class="info-box">{{ ucfirst($veiculo->status ?? 'Ativo') }}</div>
          </div>

          <hr class="my-3 border-secondary opacity-25">

          <!-- Modelo -->
          <div class="col-md-6">
            <label class="text-warning fw-semibold">Modelo</label>
            <div class="info-box">{{ $veiculo->modelo_veiculo->modelo ?? '-' }}</div>
          </div>

          <!-- Marca -->
          <div class="col-md-6">
            <label class="text-warning fw-semibold">Marca</label>
            <div class="info-box">{{ $veiculo->modelo_veiculo->marca ?? '-' }}</div>
          </div>

          <!-- Categoria -->
          <div class="col-md-4">
            <label class="text-warning fw-semibold">Categoria</label>
            <div class="info-box">{{ $veiculo->modelo_veiculo->categoria ?? '-' }}</div>
          </div>

          <!-- Tara -->
          <div class="col-md-4">
            <label class="text-warning fw-semibold">Tara (Kg)</label>
            <div class="info-box">{{ number_format($veiculo->tara_kg ?? 0, 2, ',', '.') }}</div>
          </div>

          <!-- PBT -->
          <div class="col-md-4">
            <label class="text-warning fw-semibold">PBT (Kg)</label>
            <div class="info-box">{{ number_format($veiculo->pbt_kg ?? 0, 2, ',', '.') }}</div>
          </div>

          <hr class="my-3 border-secondary opacity-25">

          <!-- RENAVAM -->
          <div class="col-md-6">
            <label class="text-warning fw-semibold">RENAVAM</label>
            <div class="info-box">{{ $veiculo->renavam ?? '-' }}</div>
          </div>

          <!-- Chassi -->
          <div class="col-md-6">
            <label class="text-warning fw-semibold">Chassi</label>
            <div class="info-box">{{ $veiculo->chassi ?? '-' }}</div>
          </div>

          <!-- Observações -->
          <div class="col-md-12">
            <label class="text-warning fw-semibold">Observações</label>
            <div class="info-box">
              {{ $veiculo->observacoes ? $veiculo->observacoes : 'Nenhuma observação registrada.' }}
            </div>
          </div>

          <!-- Data -->
          <div class="col-md-12">
            <label class="text-warning fw-semibold">Data de Cadastro</label>
            <div class="info-box">
              {{ $veiculo->created_at ? $veiculo->created_at->format('d/m/Y H:i') : '-' }}
            </div>
          </div>

        </div>
      </div>

      <!-- Rodapé -->
      <div class="modal-footer bg-dna-footer border-0 py-3">
        <button type="button" class="btn btn-outline-warning rounded-pill px-4" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i> Fechar
        </button>
      </div>
    </div>
  </div>
</div>
@endforeach

<style>
  /* DNA Transportes Modal Design */

  .bg-dna-yellow {
    background-color: #ffc107 !important;
  }

  .bg-dna-footer {
    background-color: #1a1f25 !important;
  }

  .modal-content {
    background-color: #12181F !important;
    color: #f8f9fa !important;
    border-radius: 1rem;
    border: none;
  }

  .modal-header {
    border: none !important;
    font-size: 1.1rem;
  }

  .info-box {
    background-color: #1b1e22;
    border: 1px solid rgba(255, 193, 7, 0.15);
    border-radius: 0.6rem;
    padding: 0.6rem 0.75rem;
    font-size: 0.95rem;
    color: #e9ecef;
    box-shadow: inset 0 0 6px rgba(255, 193, 7, 0.05);
  }

  .info-box:hover {
    background-color: #21262b;
    transition: 0.3s ease;
  }

  .text-warning {
    color: #ffc107 !important;
  }

  .btn-outline-warning {
    border-color: #ffc107 !important;
    color: #ffc107 !important;
  }

  .btn-outline-warning:hover {
    background-color: #ffc107 !important;
    color: #12181F !important;
  }

  .modal-footer {
    border-top: 1px solid rgba(255, 193, 7, 0.2);
  }
</style>
