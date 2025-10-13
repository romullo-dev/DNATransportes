@extends('layouts.app')

@section('content')
    {{-- Alertas de Sessão (dismissible) --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        {{-- Topo escuro --}}
        <div class="card-header text-white rounded-top p-3 d-flex justify-content-between align-items-center"
             style="background: linear-gradient(90deg, #1d3557, #264653);">
            <h4 class="mb-0">
                <i class="bi bi-person-circle me-2"></i> Perfil do Usuário
            </h4>
            <a href="{{ route('read-user') }}" class="btn btn-outline-light btn-sm">
                <i class="bi bi-arrow-left me-2"></i> Voltar
            </a>
        </div>

        {{-- Corpo em dark --}}
        <div class="card-body p-4 bg-dark text-light">
            <div class="row g-4">
                {{-- Coluna da Foto --}}
                <div class="col-md-4 text-center d-flex flex-column align-items-center justify-content-center">
                    @if ($usuario->foto)
                        <img src="{{ asset('usuarios/' . $usuario->foto) }}" alt="Foto do Usuário"
                            class="rounded-circle shadow mb-3 border border-3 border-primary"
                            style="width: 200px; height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded-circle mb-3 shadow-sm"
                            style="width: 200px; height: 200px;">
                            <span>Sem Foto</span>
                        </div>
                    @endif

                    <h5 class="mt-3 fw-bold text-info">{{ $usuario->nome }}</h5>
                    <p class="text-muted small">@ {{ $usuario->user }}</p>

                    {{-- Botão Alterar Foto --}}
                    <form action="{{ route('usuarios.inserir', ['id' => $usuario->id_usuario]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="foto" class="btn btn-outline-info btn-sm mt-2 shadow-sm">
                            <i class="bi bi-upload me-1"></i> Alterar Foto
                        </label>
                        <input type="file" name="foto" id="foto" class="d-none" onchange="this.form.submit()">
                    </form>
                </div>

                {{-- Coluna dos Dados --}}
                <div class="col-md-8">
                    <div class="list-group shadow-sm rounded-3">
                        <div class="list-group-item bg-dark text-light d-flex justify-content-between align-items-center">
                            <strong><i class="bi bi-envelope-fill text-info me-2"></i>Email:</strong>
                            <span>{{ $usuario->email }}</span>
                        </div>
                        <div class="list-group-item bg-dark text-light d-flex justify-content-between align-items-center">
                            <strong><i class="bi bi-key-fill text-info me-2"></i>Tipo:</strong>
                            <span>{{ ucfirst($usuario->tipo_usuario) }}</span>
                        </div>
                        <div class="list-group-item bg-dark text-light d-flex justify-content-between align-items-center">
                            <strong><i class="bi bi-person-vcard-fill text-info me-2"></i>CPF:</strong>
                            <span>{{ $usuario->cpf }}</span>
                        </div>
                        <div class="list-group-item bg-dark text-light d-flex justify-content-between align-items-center">
                            <strong><i class="bi bi-telephone-fill text-info me-2"></i>Telefone:</strong>
                            <span>{{ $usuario->telefone }}</span>
                        </div>
                        <div class="list-group-item bg-dark text-light d-flex justify-content-between align-items-center">
                            <strong><i class="bi bi-shield-fill-check text-info me-2"></i>Status:</strong>
                            <span>{{ ucfirst($usuario->status_funcionario) }}</span>
                        </div>
                        <div class="list-group-item bg-dark text-light d-flex justify-content-between align-items-center">
                            <strong><i class="bi bi-calendar-plus-fill text-info me-2"></i>Criado em:</strong>
                            <span>{{ $usuario->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="list-group-item bg-dark text-light d-flex justify-content-between align-items-center">
                            <strong><i class="bi bi-calendar-check-fill text-info me-2"></i>Última atualização:</strong>
                            <span>{{ $usuario->updated_at->format('d/m/Y H:i') }}</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Footer escuro --}}
        <div class="card-footer bg-secondary text-white d-flex justify-content-end gap-2 p-3">
            <a href="{{ route('senha.user', ['id' => $usuario->id_usuario]) }}" class="btn btn-outline-light shadow-sm">
                <i class="bi bi-key-fill me-2"></i> Mudar Senha
            </a>
        </div>
    </div>
@endsection