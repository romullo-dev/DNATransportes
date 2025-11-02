@foreach ($usuarios as $usuario)
<div class="modal fade" id="modalShow{{ $usuario->motorista->id_motorista ?? 'new' }}" tabindex="-1"
     aria-labelledby="modalShowLabel{{ $usuario->id_usuario }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden"
             style="background-color: #1b1e22; color: #f8f9fa;">

            {{-- Cabeçalho --}}
            <div class="modal-header border-0 py-3 px-4"
                 style="background: linear-gradient(90deg, #f0ad00, #ffc107, #ffca2c);">
                <h5 class="modal-title fw-semibold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-person-vcard-fill"></i> Detalhes do Motorista
                </h5>
                <button type="button" class="btn-close shadow-sm" data-bs-dismiss="modal"
                        aria-label="Fechar"></button>
            </div>

            {{-- Corpo --}}
            <div class="modal-body p-4 bg-dark text-light">
                <div class="row g-4 align-items-center">

                    {{-- Coluna da esquerda: Dados básicos --}}
                    <div class="col-md-6">
                        <div class="rounded-4 border border-secondary p-3 shadow-sm bg-dark">
                            <p class="mb-2"><strong><i class="bi bi-person-fill text-warning me-2"></i>Nome:</strong>
                                {{ $usuario->nome }}</p>
                            <p class="mb-2"><strong><i class="bi bi-person-badge-fill text-warning me-2"></i>Usuário:</strong>
                                {{ $usuario->user }}</p>
                            <p class="mb-2"><strong><i class="bi bi-envelope-fill text-warning me-2"></i>Email:</strong>
                                {{ $usuario->email }}</p>
                            <p class="mb-2"><strong><i class="bi bi-person-vcard text-warning me-2"></i>CPF:</strong>
                                {{ $usuario->cpf }}</p>
                        </div>
                    </div>

                    {{-- Coluna da direita: CNH e status --}}
                    <div class="col-md-6">
                        <div class="rounded-4 border border-secondary p-3 shadow-sm bg-dark">
                            <p class="mb-2"><strong><i class="bi bi-card-text text-warning me-2"></i>CNH:</strong>
                                {{ $usuario->motorista->cnh ?? '—' }}</p>
                            <p class="mb-2"><strong><i class="bi bi-calendar-event text-warning me-2"></i>Validade CNH:</strong>
                                {{ $usuario->motorista->validade_cnh ?? '—' }}</p>

                            <p class="mb-2"><strong><i class="bi bi-activity text-warning me-2"></i>Status:</strong>
                                <span
                                    class="badge rounded-pill px-3 py-1 {{ strtolower($usuario->status_funcionario) === 'ativo' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($usuario->status_funcionario) }}
                                </span>
                            </p>

                            <p class="mb-2"><strong><i class="bi bi-calendar-plus-fill text-warning me-2"></i>Criado em:</strong>
                                @if ($usuario->motorista && $usuario->motorista->created_at)
                                    {{ $usuario->motorista->created_at->format('d/m/Y H:i') }}
                                @else
                                    —
                                @endif
                            </p>

                            <p class="mb-0"><strong><i class="bi bi-calendar-check-fill text-warning me-2"></i>Editado em:</strong>
                                @if ($usuario->motorista && $usuario->motorista->updated_at)
                                    {{ $usuario->motorista->updated_at->format('d/m/Y H:i') }}
                                @else
                                    —
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Rodapé --}}
            <div class="modal-footer border-0 d-flex justify-content-end gap-3 px-4 py-3"
                 style="background-color: #111418; border-top: 1px solid #2c2c2c;">

                {{-- Botão Editar --}}
                <button type="button" class="btn btn-outline-warning rounded-pill px-4 shadow-sm"
                        data-bs-dismiss="modal" data-bs-toggle="modal"
                        data-bs-target="#modalEdit{{ $usuario->motorista->id_motorista ?? 'new' }}">
                    <i class="bi bi-pencil-square me-1"></i> Editar
                </button>

                {{-- Botão Excluir --}}
                <button type="button" class="btn btn-outline-danger rounded-pill px-4 shadow-sm"
                        data-bs-dismiss="modal" data-bs-toggle="modal"
                        data-bs-target="#modalDelete{{ $usuario->motorista->id_motorista ?? 'new' }}">
                    <i class="bi bi-trash3-fill me-1"></i> Excluir
                </button>

                {{-- Botão Fechar --}}
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm"
                        data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Fechar
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
