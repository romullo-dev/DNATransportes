@foreach ($usuarios as $usuario)
    <div class="modal fade" id="modalShow{{ $usuario->id_usuario }}" tabindex="-1"
        aria-labelledby="modalShowLabel{{ $usuario->id_usuario }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title text-dark fw-bold" id="modalShowLabel{{ $usuario->id_usuario }}">
                        <i class="bi bi-person-lines-fill me-2"></i> Detalhes do Usuário
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4 text-center">
                            @if ($usuario->foto)
                                <img src="{{ asset('storage/' . $usuario->foto) }}" alt="Foto do usuário"
                                    class="img-thumbnail rounded-circle" width="150">
                            @else
                                <div class="border rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                    style="width: 150px; height: 150px;">Sem Foto</div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <p><strong>Nome:</strong> {{ $usuario->nome }}</p>
                            <p><strong>Usuário:</strong> {{ $usuario->user }}</p>
                            <p><strong>Email:</strong> {{ $usuario->email }}</p>
                            <p><strong>CPF:</strong> {{ $usuario->cpf }}</p>
                            <p><strong>Tipo:</strong> {{ ucfirst($usuario->tipo_usuario) }}</p>
                            <p><strong>Status:</strong> {{ ucfirst($usuario->status_funcionario) }}</p>
                            <p><strong>Criado em:</strong> {{ $usuario->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Editado em:</strong> {{ $usuario->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
