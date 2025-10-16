@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center"
         style="min-height: 100vh; background: linear-gradient(180deg, #0b0b0b 0%, #101820 100%); font-family: 'Poppins', sans-serif;">
        
        <div class="card shadow-lg border-0 rounded-4 p-4" style="max-width: 500px; width: 100%; background-color: #1E1E1E;">
            {{-- Título --}}
            <div class="text-center mb-4">
                <h1 class="fw-bold" style="color: #EB8721;">
                    <i class="bi bi-file-earmark-arrow-up-fill me-2"></i> Enviar XML
                </h1>
                <p class="text-light-50 mb-0">Envie o arquivo XML da nota fiscal para importação</p>
            </div>

            {{-- Mensagens de sessão --}}
            @if (session('success'))
                <div class="alert alert-success text-center fw-semibold shadow-sm">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger text-center fw-semibold shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                </div>
            @endif

            {{-- Formulário --}}
            <form action="{{ route('importacao.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="xml" class="form-label fw-semibold text-light">Arquivo XML</label>
                    <input type="file" class="form-control form-control-lg bg-dark text-light border-0 shadow-sm"
                           name="xml" accept=".xml" required>
                </div>

                <input type="hidden" name="status" value="em preparo">

                <button type="submit" class="btn w-100 py-2 fw-semibold text-uppercase shadow-sm"
                        style="background-color: #EB8721; border: none; color: #fff;">
                    <i class="bi bi-cloud-arrow-up-fill me-2"></i> Enviar Arquivo
                </button>
            </form>

            {{-- Exibição de erros --}}
            @if ($errors->any())
                <div class="alert alert-danger mt-3 shadow-sm">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Rodapé --}}
            <div class="text-center mt-4 text-secondary small">
                <i class="bi bi-truck me-1"></i> DNA Transportes • Sistema de Importação
            </div>
        </div>
    </div>
@endsection
