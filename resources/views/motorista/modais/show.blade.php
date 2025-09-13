@foreach ($usuarios as $usuario)
    <div class="modal fade" id="modalShow{{ $usuario->motorista->id_motorista ?? 'new' }}" tabindex="-1"
        aria-labelledby="modalShowLabel{{ $usuario->id_usuario }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title text-dark fw-bold" id="modalShowLabel{{ $usuario->id_usuario }}">
                        <i class="bi bi-person-lines-fill me-2"></i> Detalhes do Motorista
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <p><strong>Nome:</strong> {{ $usuario->nome }}</p>
                                <p><strong>Usuário:</strong> {{ $usuario->user }}</p>
                                <p><strong>Email:</strong> {{ $usuario->email }}</p>
                                <p><strong>CPF:</strong> {{ $usuario->cpf }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>CNH:</strong> {{ $usuario->motorista->cnh ?? '—' }}</p>
                                <p><strong>Validade CNH:</strong> {{ $usuario->motorista->validade_cnh ?? '—' }}</p>
                                <p><strong>Status:</strong>
                                    <span
                                        class="badge {{ $usuario->status_funcionario == 'ativo' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($usuario->status_funcionario) }}
                                    </span>
                                </p>
                                <p><strong>Criado em:</strong>
                                    @if ($usuario->motorista && $usuario->motorista->created_at)
                                        {{ $usuario->motorista->created_at->format('d/m/Y H:i') }}
                                    @else
                                        —
                                    @endif
                                </p>

                                <p><strong>Editado em:</strong>
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
            </div>
        </div>
    </div>
@endforeach
