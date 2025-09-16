@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background-color: #3e4754;">
    <div class="card shadow-lg rounded-4 border-0 text-light p-5" style="max-width: 850px; background-color: #293241;">
        <!-- Cabeçalho -->
        <div class="text-center mb-5">
            <h1 class="display-5 text-warning fw-bold mb-2">
                Olá, {{ Auth::user()->nome }}! ✨
            </h1>
            <p class="lead text-light">
                Que bom te ver de volta! Aqui está um resumo das suas atividades recentes e atualizações importantes.
            </p>
        </div>

        <!-- Cards de Atividades -->
        <div class="row g-4 mb-4">
            @php
                $cards = [
                    ['icon'=>'⭐', 'text'=>'Novos pedidos prontos para análise.'],
                    ['icon'=>'📦', 'text'=>'Verifique o status dos envios em tempo real.'],
                    ['icon'=>'📈', 'text'=>'Relatórios atualizados de desempenho logístico.'],
                    ['icon'=>'💬', 'text'=>'Mensagens e notificações da equipe.'],
                ];
            @endphp

            @foreach($cards as $card)
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden"
                         style="background-color: rgba(0, 0, 0, 0.2); transition: transform 0.3s, background 0.3s;">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 fs-2 text-warning">
                                {{ $card['icon'] }}
                            </div>
                            <div class="text-light fw-medium">
                                {{ $card['text'] }}
                            </div>
                        </div>
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="pointer-events:none;"></div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Botão de ação -->
        <div class="text-center mt-4">
            <a href="{{ route('pedidos.painel') }}" class="btn btn-warning btn-lg fw-bold px-5 shadow-lg" 
               style="transition: transform 0.3s, box-shadow 0.3s;" 
               onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 0.75rem 1.5rem rgba(255,193,7,0.5)';" 
               onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 0.5rem 1rem rgba(0,0,0,0.3)';">
                Ir para o Painel
            </a>
        </div>
    </div>
</div>
@endsection
