@foreach ($modelos as $veiculo)
<div class="modal fade" id="modalShow{{ $veiculo->id_Veiculo }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">

      {{-- Cabeçalho Dourado DNA --}}
      <div class="modal-header bg-dna-yellow text-dark py-3 px-4">
        <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
          <i class="bi bi-truck-front-fill fs-5 text-dark"></i> Detalhes do Veículo
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      {{-- Corpo --}}
      <div class="modal-body bg-dark text-light px-4 py-4">
        <div class="row g-3">

          {{-- 🔹 Dados principais --}}
          <div class="col-md-4">
            <label class="text-warning fw-semibold"><i class="bi bi-upc-scan me-2"></i>Placa</label>
            <div class="info-box">{{ strtoupper($veiculo->placa ?? '-') }}</div>
          </div>

          <div class="col-md-2">
            <label class="text-warning fw-semibold"><i class="bi bi-calendar-event me-2"></i>Ano</label>
            <div class="info-box">{{ $veiculo->ano ?? '-' }}</div>
          </div>

          <div class="col-md-3">
            <label class="text-warning fw-semibold"><i class="bi bi-palette-fill me-2"></i>Cor</label>
            <div class="info-box">{{ ucfirst($veiculo->cor ?? '-') }}</div>
          </div>

          <div class="col-md-3">
            <label class="text-warning fw-semibold"><i class="bi bi-activity me-2"></i>Status</label>
            <div class="info-box">
  <span class="{{ strtolower($veiculo->status_veiculo) === 'ativo' ? 'text-success' : 'text-danger' }}">
    <i class="bi {{ strtolower($veiculo->status_veiculo) === 'ativo' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
    {{ ucfirst($veiculo->status_veiculo ?? 'Ativo') }}
  </span>
</div>

          </div>

          <hr class="my-3 border-secondary opacity-25">

          {{-- 🔹 Informações técnicas --}}
          <div class="col-md-6">
            <label class="text-warning fw-semibold"><i class="bi bi-car-front-fill me-2"></i>Modelo</label>
            <div class="info-box">{{ $veiculo->modelo_veiculo->modelo ?? '-' }}</div>
          </div>

          <div class="col-md-6">
            <label class="text-warning fw-semibold"><i class="bi bi-building-fill-gear me-2"></i>Marca</label>
            <div class="info-box">{{ $veiculo->modelo_veiculo->marca ?? '-' }}</div>
          </div>

          <div class="col-md-4">
            <label class="text-warning fw-semibold"><i class="bi bi-diagram-3-fill me-2"></i>Categoria</label>
            <div class="info-box">{{ $veiculo->modelo_veiculo->categoria ?? '-' }}</div>
          </div>

          <div class="col-md-4">
            <label class="text-warning fw-semibold"><i class="bi bi-box-seam me-2"></i>Tara (Kg)</label>
            <div class="info-box">{{ number_format($veiculo->tara_kg ?? 0, 2, ',', '.') }}</div>
          </div>

          <div class="col-md-4">
            <label class="text-warning fw-semibold"><i class="bi bi-speedometer2 me-2"></i>PBT (Kg)</label>
            <div class="info-box">{{ number_format($veiculo->pbt_kg ?? 0, 2, ',', '.') }}</div>
          </div>

          <hr class="my-3 border-secondary opacity-25">

          {{-- 🔹 Documentação --}}
          <div class="col-md-6">
            <label class="text-warning fw-semibold"><i class="bi bi-123 me-2"></i>RENAVAM</label>
            <div class="info-box">{{ $veiculo->renavam ?? '-' }}</div>
          </div>

          <div class="col-md-6">
            <label class="text-warning fw-semibold"><i class="bi bi-upc me-2"></i>Chassi</label>
            <div class="info-box">{{ $veiculo->chassi ?? '-' }}</div>
          </div>

          {{-- 🔹 Observações --}}
          <div class="col-md-12">
            <label class="text-warning fw-semibold"><i class="bi bi-journal-text me-2"></i>Observações</label>
            <div class="info-box">
              {{ $veiculo->observacoes ? $veiculo->observacoes : 'Nenhuma observação registrada.' }}
            </div>
          </div>

          {{-- 🔹 Datas --}}
          <div class="col-md-12">
            <label class="text-warning fw-semibold"><i class="bi bi-calendar3 me-2"></i>Data de Cadastro</label>
            <div class="info-box">
              {{ $veiculo->created_at ? $veiculo->created_at->format('d/m/Y H:i') : '-' }}
            </div>
          </div>
        </div>
      </div>

      {{-- Rodapé --}}
      <div class="modal-footer bg-dna-footer border-0 py-3">
        <button type="button" class="btn btn-outline-warning rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i> Fechar
        </button>
      </div>
    </div>
  </div>
</div>
@endforeach

{{-- 🎨 Estilos DNA Transportes --}}
<style>
  .bg-dna-yellow {
    background: linear-gradient(90deg, #ffc107, #ffb300);
  }

  .bg-dna-footer {
    background-color: #161a1f !important;
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
    border: 1px solid rgba(255, 193, 7, 0.2);
    border-radius: 0.6rem;
    padding: 0.6rem 0.75rem;
    font-size: 0.95rem;
    color: #e9ecef;
    box-shadow: inset 0 0 6px rgba(255, 193, 7, 0.05);
    transition: background 0.3s ease;
  }

  .info-box:hover {
    background-color: #22262b;
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
