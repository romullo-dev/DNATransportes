@foreach ($usuarios as $usuario)
    <div class="modal fade" id="modalEdit{{ $usuario->motorista->id_motorista ?? 'new' }}" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST"
                action="{{ $usuario->motorista ? route('motorista.update', $usuario->motorista->id_motorista) : route('motorista.store') }}"
                enctype="multipart/form-data" class="modal-content shadow-lg border-0">
                @csrf
                @method($usuario->motorista ? 'PUT' : 'POST')

                <!-- Cabeçalho -->
                <div class="modal-header text-dark" style="background: linear-gradient(90deg, #eb8721, #ffb84d);">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2"></i>Editar Motorista
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Corpo -->
                <div class="modal-body row g-3 px-4 py-3" style="background-color: #1b1e22; color: #f1f1f1;">
                    <div class="col-md-12">
                        <label class="fw-semibold mb-1">Nome</label>
                        <input name="nome" class="form-control" value="{{ $usuario->nome }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">CPF</label>
                        <input name="cpf" class="form-control" maxlength="11" value="{{ $usuario->cpf }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">CNH</label>
                        <input name="cnh" class="form-control" maxlength="11"
                            value="{{ $usuario->motorista->cnh ?? '' }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">Categoria</label>
                        <select name="categoria" class="form-select" required>
                            <option value="">Selecione</option>
                            <option value="A"
                                {{ ($usuario->motorista->categoria ?? '') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B"
                                {{ ($usuario->motorista->categoria ?? '') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="C"
                                {{ ($usuario->motorista->categoria ?? '') == 'C' ? 'selected' : '' }}>C</option>
                            <option value="D"
                                {{ ($usuario->motorista->categoria ?? '') == 'D' ? 'selected' : '' }}>D</option>
                            <option value="E"
                                {{ ($usuario->motorista->categoria ?? '') == 'E' ? 'selected' : '' }}>E</option>
                            <option value="AB"
                                {{ ($usuario->motorista->categoria ?? '') == 'AB' ? 'selected' : '' }}>AB</option>
                        </select>
                    </div>


                    <div class="col-md-12">
                        <label class="fw-semibold mb-1">Validade da CNH</label>
                        <input type="date" name="validade_cnh"
                            value="{{ old('validade_cnh', $usuario->motorista->validade_cnh ?? '') }}"
                            class="form-control">
                    </div>
                </div>

                <!-- Rodapé -->
                <div class="modal-footer border-0 d-flex justify-content-between" style="background-color: #12181F;">
                    <button class="btn btn-outline-light px-4 rounded-pill" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning text-dark fw-semibold px-4 rounded-pill">
                        <i class="bi bi-save-fill me-1"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
@endforeach

<style>
    /* ======== 🎨 DNA Transportes Modal Style ======== */
    :root {
        --dna-orange: #eb8721;
        --dna-orange-light: #ffb84d;
        --dna-dark: #12181F;
        --dna-gray: #1b1e22;
        --dna-input: #23272e;
    }

    .modal-content {
        background-color: var(--dna-gray);
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid #2d333b;
        color: #f1f1f1;
        animation: modalFadeIn 0.3s ease;
    }

    .form-control,
    .form-select {
        background-color: var(--dna-input);
        border: 1px solid #343a40;
        color: #fff;
        border-radius: 0.6rem;
        transition: 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--dna-orange);
        box-shadow: 0 0 0 0.25rem rgba(235, 135, 33, 0.25);
    }

    /* 🧱 Inputs desativados (corrige o branco feio) */
    .form-control:disabled,
    .form-select:disabled {
        background-color: #2a2f36 !important;
        color: #aaa !important;
        border-color: #3a3f46 !important;
        opacity: 1 !important;
        cursor: not-allowed;
    }

    .btn-warning {
        background: linear-gradient(90deg, var(--dna-orange), var(--dna-orange-light));
        border: none;
        color: #000;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-warning:hover {
        filter: brightness(1.1);
        box-shadow: 0 0 15px rgba(235, 135, 33, 0.4);
    }

    @keyframes modalFadeIn {
        from {
            transform: translateY(-10px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>
