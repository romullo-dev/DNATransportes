<div class="modal fade" id="modalShow{{ $usuario->id_usuario }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden"
            style="background-color: #1b1e22; color: #f8f9fa;">

            {{-- Cabeçalho --}}
            <div class="modal-header border-0 py-3 px-4"
                style="background: linear-gradient(90deg, #ffc107,  #be9312);">
                <h5 class="modal-title fw-semibold">
                    <i class="bi bi-person-lines-fill me-2 text-warning"></i> Detalhes do Usuário
                </h5>
                <button type="button" class="btn-close btn-close-white shadow-sm" data-bs-dismiss="modal"
                    aria-label="Fechar"></button>
            </div>

            {{-- Corpo --}}
            <div class="modal-body px-5 py-4">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">

                    {{-- Foto e nome --}}
                    <div class="text-center flex-shrink-0">
                        @if ($usuario->foto)
                            <img src="{{ asset('usuarios/' . $usuario->foto) }}" alt="Foto do usuário"
                                class="rounded-circle border border-3 border-warning shadow-sm"
                                style="width: 170px; height: 170px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-semibold shadow-sm"
                                style="width: 170px; height: 170px; font-size: 0.95rem;">
                                Sem Foto
                            </div>
                        @endif

                        <h6 class="mt-3 mb-0 fw-bold text-warning">{{ $usuario->nome }}</h6>
                        <small>
                            <span class="text" style="color: #0dcaf0;">@</span>
                            <span class="text" style="color: #0dcaf0;">{{ $usuario->user }}</span>
                        </small>

                    </div>

                    {{-- Dados --}}
                    <div class="flex-grow-1 w-100">
                        <div class="bg-dark rounded-4 p-4 shadow-sm border border-secondary" style="min-height: 200px;">
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-2"><strong><i
                                                class="bi bi-envelope-fill text-warning me-2"></i>Email:</strong>
                                        {{ $usuario->email }}</p>
                                    <p class="mb-2"><strong><i
                                                class="bi bi-person-vcard-fill text-warning me-2"></i>CPF:</strong>
                                        {{ $usuario->cpf }}</p>
                                    <p class="mb-2"><strong><i
                                                class="bi bi-telephone-fill text-warning me-2"></i>Telefone:</strong>
                                        {{ $usuario->telefone }}</p>
                                    <p class="mb-0"><strong><i
                                                class="bi bi-shield-fill-check text-warning me-2"></i>Status:</strong>
                                        <span
                                            class="{{ $usuario->status_funcionario === 'Ativo' ? 'text-success' : 'text-danger' }}">
                                            {{ ucfirst($usuario->status_funcionario) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-2"><strong><i
                                                class="bi bi-key-fill text-warning me-2"></i>Tipo:</strong>
                                        {{ ucfirst($usuario->tipo_usuario) }}</p>
                                    <p class="mb-2"><strong><i
                                                class="bi bi-calendar-plus-fill text-warning me-2"></i>Criado:</strong>
                                        {{ $usuario->created_at->format('d/m/Y H:i') }}</p>
                                    <p class="mb-0"><strong><i
                                                class="bi bi-calendar-check-fill text-warning me-2"></i>Editado:</strong>
                                        {{ $usuario->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Rodapé --}}
            <div class="modal-footer border-0 d-flex justify-content-end gap-3 px-4 py-3"
                style="background-color: #111418; border-top: 1px solid #2c2c2c;">
                <button type="button" class="btn btn-outline-warning rounded-pill px-4 shadow-sm"
                    data-bs-dismiss="modal" data-bs-toggle="modal"
                    data-bs-target="#modalEdit{{ $usuario->id_usuario }}">
                    <i class="bi bi-pencil-square me-1"></i> Editar
                </button>

                <button type="button" class="btn btn-outline-danger rounded-pill px-4 shadow-sm"
                    data-bs-dismiss="modal" data-bs-toggle="modal"
                    data-bs-target="#modalDelete{{ $usuario->id_usuario }}">
                    <i class="bi bi-trash3-fill me-1"></i> Excluir
                </button>

                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm"
                    data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Fechar
                </button>
            </div>
        </div>
    </div>
</div>
