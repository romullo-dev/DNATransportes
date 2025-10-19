@extends('layouts.app')

@section('content')
<div class="container py-5" style="min-height: 100vh; background-color: #1b1e22;">
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        <div class="card-header text-white fw-semibold"
             style="background: linear-gradient(90deg, #017aaa, #2a9d8f); border-bottom: 3px solid #1f8574;">
            <i class="bi bi-pencil-square me-2"></i> Criar Histórico — Pedido #
        </div>

        <div class="card-body bg-light p-4">
            <form action="" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select class="form-select shadow-sm" name="status" required>
                        <option value="" disabled selected>Selecione...</option>
                        <option value="Em trânsito">Em trânsito</option>
                        <option value="Finalizado">Finalizado</option>
                        <option value="Ocorrência">Ocorrência</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label>Foto (opcional)</label>
                    <input name="foto" type="file" class="form-control">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Observação</label>
                    <textarea class="form-control shadow-sm" name="observacao" rows="3" placeholder="Ex: Cliente ausente, rota alterada..."></textarea>
                </div>

                <div class="text-end">
                    <a href="{{ route('rotas.show' ) }}" 
                       class="btn btn-secondary rounded-pill">
                        <i class="bi bi-arrow-left me-1"></i> Voltar
                    </a>
                    <button type="submit" class="btn btn-warning fw-semibold rounded-pill text-white"
                            style="background: linear-gradient(90deg, #017aaa, #2a9d8f); border: none;">
                        <i class="bi bi-check-circle me-1"></i> Registrar Histórico
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
