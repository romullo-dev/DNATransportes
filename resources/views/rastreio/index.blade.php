@extends('layouts.app')

@section('content')
<div class="container py-5" style="min-height: 85vh;">
    <div class="row justify-content-center align-items-center h-100">
        <div class="col-md-6">

            {{-- 🧭 Card principal --}}
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden bg-dark text-light position-relative">
                {{-- 🔸 Faixa superior --}}
                <div class="position-absolute top-0 start-0 w-100"
                    style="height: 6px; background: linear-gradient(90deg, #f4a261, #e9c46a, #e76f51);"></div>

                {{-- Conteúdo --}}
                <div class="card-body p-5 text-center">
                    {{-- Logo + título --}}
                    <div class="mb-4">
                        <img src="{{ asset('images/logo2.jpg') }}" alt="DNA Transportes" class="mb-3"
                             style="width: 80px; height: auto; border-radius: 10px;">
                        <h2 class="fw-bold text-warning mb-0">DNA Transportes</h2>
                        <p class="text-secondary mb-0">Rastreie seu pedido em tempo real 🚚</p>
                    </div>

                    {{-- Formulário de rastreio --}}
                    <form action="{{ route('pedidos.show') }}" method="POST" class="mt-4">
                        @csrf
                        <div class="mb-3 text-start">
                            <label for="codigo" class="form-label fw-semibold text-light">Código de Rastreamento</label>
                            <input type="text" name="codigo_rastreamento" id="codigo"
                                class="form-control bg-dark text-light border-secondary rounded-pill px-3 py-2 shadow-sm"
                                placeholder="Ex: DNA123456789" required>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit"
                                class="btn btn-warning btn-lg rounded-pill fw-bold shadow-lg text-dark"
                                style="letter-spacing: 0.5px;">
                                <i class="bi bi-geo-alt-fill me-2"></i> Rastrear Pedido
                            </button>
                        </div>
                    </form>

                    {{-- Link de ajuda --}}
                    <div class="mt-4">
                        <small class="text-light">Não sabe seu código de rastreio?
                            <a href="{{ route('pedidos.index') }}" class="text-warning fw-semibold text-decoration-underline">
                                Clique aqui
                            </a>
                        </small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- 🎨 Estilo DNA Transportes --}}
<style>
    body {
        background: linear-gradient(135deg, #0b0b0b, #1e1e1e, #111827);
        color: #fff;
    }

    .card {
        background: #101820;
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease-in-out;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    input.form-control:focus {
        border-color: #e9c46a;
        box-shadow: 0 0 12px rgba(233, 196, 106, 0.5);
        background-color: #1a1f26;
    }

    .btn-warning {
        background: linear-gradient(90deg, #f4a261, #e9c46a);
        border: none;
        transition: 0.3s ease-in-out;
    }

    .btn-warning:hover {
        background: linear-gradient(90deg, #e76f51, #f4a261);
        color: #fff;
        transform: scale(1.02);
    }

    a.text-warning:hover {
        color: #f4a261 !important;
        text-shadow: 0 0 8px rgba(244, 162, 97, 0.5);
    }
</style>
@endsection
