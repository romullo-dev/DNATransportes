@extends('layouts.app')

@section('content')
<div class="min-vh-100 py-5 d-flex align-items-center" style="background-color: #12181F;">
    <div class="container text-center text-light">

        {{-- 🟡 Saudação --}}
        <h1 class="fw-bold text-warning display-5 mb-3">
            Bem-vindo(a), {{ Auth::user()->nome }}!
        </h1>
        <p class="lead mb-5 text-light-50">
            O sistema <span class="text-warning fw-semibold">DNA Transportes</span> está pronto para otimizar suas operações logísticas.<br>
            Gerencie rotas, pedidos e motoristas em um único ambiente moderno e intuitivo.
        </p>

        {{-- ⚙️ Bloco de ações principais --}}
        <div class="row justify-content-center g-4 mb-5">
            @php
                $atalhos = [
                    ['icon' => 'bi-signpost', 'text' => 'Gerenciar Rotas', 'route' => route('rotas.index'), 'color' => '#2A9D8F'],
                    ['icon' => 'bi-box-seam', 'text' => 'Visualizar Pedidos', 'route' => route('pedidos.index'), 'color' => '#FFD700'],
                    ['icon' => 'bi-person-badge', 'text' => 'Motoristas', 'route' => route('motorista.index'), 'color' => '#4CAF50'],
                    ['icon' => 'bi-geo-alt', 'text' => 'Centros de Distribuição', 'route' => route('centro.index'), 'color' => '#EB8721'],
                ];
            @endphp

            @foreach ($atalhos as $a)
                <div class="col-md-3 col-sm-6">
                    <a href="{{ $a['route'] }}" class="text-decoration-none">
                        <div class="card border-0 rounded-4 shadow-lg p-4 h-100"
                             style="background: #1B212A; transition: 0.3s;">
                            <i class="bi {{ $a['icon'] }} fs-1 mb-3" style="color: {{ $a['color'] }}"></i>
                            <h6 class="fw-semibold text-light">{{ $a['text'] }}</h6>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- 🚛 Bloco institucional --}}
        <div class="card border-0 rounded-4 shadow-lg p-5 mb-5 mx-auto" style="background: #1B212A; max-width: 900px;">
            <div class="row align-items-center">
                <div class="col-md-8 text-start">
                    <h3 class="fw-bold text-warning mb-3">Conectando distâncias. Movendo o Brasil.</h3>
                    <p class="text-light-50">
                        A <span class="text-warning fw-semibold">DNA Transportes</span> é mais do que um sistema — é uma plataforma que une tecnologia, eficiência e segurança
                        para transformar a gestão logística em uma experiência fluida e precisa.
                    </p>
                    <p class="text-light-50 mb-0">
                        Nosso objetivo é garantir total controle das rotas, rastreamento em tempo real
                        e excelência em cada entrega.  
                        <span class="fw-semibold text-warning">Seu sucesso é o nosso destino.</span>
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="bi bi-truck-front text-warning" style="font-size: 6rem;"></i>
                </div>
            </div>
        </div>

        {{-- 🧾 Rodapé informativo --}}
        <div class="text-light-50 small mt-4">
            <i class="bi bi-shield-lock-fill text-success me-2"></i> Sistema seguro e atualizado — 
            <span class="text-warning">{{ now()->format('d/m/Y H:i') }}</span>
        </div>

    </div>
</div>

{{-- 🌙 Estilo DNA --}}
<style>
    .text-light-50 { color: rgba(255,255,255,0.6); }
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    }
</style>
@endsection
