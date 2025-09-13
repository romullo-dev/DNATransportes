@extends('layouts.app')

@section('content')
    <div class="container py-5" style="min-height: 80vh;">


        {{-- Tela central de rastreio --}}
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-md-6">

                {{-- Card principal --}}
                <div class="card shadow-lg border-0 rounded-4 p-4"
                    style="background: linear-gradient(135deg, #264653, #2a9d8f); color: #fff;">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">DNA Transportes</h2>
                        <p class="mb-0">Rastreie seu pedido em tempo real</p>
                    </div>

                    {{-- Formulário de rastreio --}}
                    <form action="{{ route('pedidos.show') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="codigo" class="form-label">Código de rastreio</label>
                            <input type="text" name="codigo_rastreamento" id="codigo"
                                class="form-control rounded-pill px-3 py-2" placeholder="Ex: DNA123456789" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning btn-lg rounded-pill fw-bold">Rastrear
                                Pedido</button>
                        </div>
                    </form>


                    <div class="mt-4 text-center">
                        <small>Não sabe seu código de rastreio? <a href="{{ route('pedidos.index') }}"
                                class="text-white fw-bold text-decoration-underline">Clique aqui</a></small>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- Estilo extra --}}
    <style>
        body {
            background: #f1f5f9;
        }

        .card input:focus {
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
            border-color: #fff;
        }

        .card button:hover {
            background-color: #e76f51 !important;
            color: #fff;
            transition: 0.3s;
        }
    </style>
@endsection
