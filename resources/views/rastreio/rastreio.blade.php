@extends('layouts.app')

@section('content')
    <div class="container my-4">



        {{-- 1️⃣ Identificação do pedido --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">


                <h4 class="card-title">Pedido #{{ $pedido->id_pedido }}</h4>
                <p class="mb-1"><strong>Data do pedido:</strong> {{ $pedido->created_at }}</p>
                @if ($pedido && $pedido->historicos->count())
                    @php
                        $ultimoHistorico = $pedido->historicos->sortByDesc('data')->first();
                    @endphp

                    <p class="mb-0">
                        <strong>Status atual:</strong>
                        <span
                            class="badge 
            @if ($ultimoHistorico->status == 'Em rota de entrega') bg-warning text-dark
            @elseif($ultimoHistorico->status == 'Pedido entregue') bg-success
            @elseif($ultimoHistorico->status == 'Pedido em preparo') bg-info text-dark
            @else bg-secondary @endif">
                            {{ $ultimoHistorico->status }}
                        </span>
                    </p>
                @else
                    <p class="mb-0">
                        <strong>Status atual:</strong>
                        <span class="badge bg-secondary">Sem histórico</span>
                    </p>
                @endif



            </div>
        </div>

        {{-- 2️⃣ Linha do tempo do status --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h5>Tracking Timeline</h5>
            </div>
            <div class="card-body">
                <ul class="timeline list-unstyled">
                   @if ($pedido->historicos && $pedido->historicos->count() > 0)
    @php
        // Inicializa os passos da timeline
        $timeline = [
            'Coleta no cliente' => ['badge' => 'bg-success', 'icon' => '✔', 'status_final' => 'Finalizada'],
            'Em transferência para o destino' => ['badge' => 'bg-warning text-dark', 'icon' => '⏳', 'status_final' => 'Finalizada'],
            'Em rota de entrega' => ['badge' => 'bg-secondary', 'icon' => '🚚', 'status_final' => 'Finalizada', 'entregue' => true],
        ];

        // Organiza os históricos por status e tipo de rota
        $historicosPorStatus = $pedido->historicos->groupBy(function($h) {
            return $h->status;
        });
    @endphp

    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <h5>Tracking Timeline</h5>
        </div>
        <div class="card-body">
            <ul class="timeline list-unstyled">
                @foreach ($timeline as $label => $info)
                    @php
                        $histEncontrado = $pedido->historicos
                            ->where('status', $info['status_final'])
                            ->first();

                        $dataExibida = $histEncontrado ? $histEncontrado->data->format('d/m/Y H:i') : '---';
                        $badge = $histEncontrado ? 'bg-success' : $info['badge'];
                        $icon = $histEncontrado ? '✔' : $info['icon'];

                        // Se for rota de entrega e finalizada, mostra como entregue
                        if(isset($info['entregue']) && $histEncontrado) {
                            $label = 'Entregue';
                        }
                    @endphp
                    <li class="mb-2">
                        <span class="badge {{ $badge }}">{{ $icon }}</span>
                        {{ $label }} - {{ $dataExibida }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
                </ul>
            </div>
        </div>

        {{-- 3️⃣ Informações do transporte --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h5>Informações do Transporte</h5>
            </div>
            <div class="card-body d-flex align-items-center">
                <img src="https://via.placeholder.com/60" alt="Entregador" class="rounded-circle me-3">
                <div>
                    <p class="mb-1"><strong>Entregador:</strong> João Silva</p>
                    <p class="mb-1"><strong>Veículo:</strong> Moto</p>
                    <p class="mb-0"><strong>Contato:</strong> (11) 91234-5678</p>
                </div>
            </div>
        </div>

        {{-- 4️⃣ Localização do pedido (mapa) --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h5>Localização do Pedido</h5>
            </div>
            <div class="card-body">
                <div id="map" style="height: 300px;"></div>
            </div>
        </div>

        {{-- 5️⃣ Detalhes do pedido --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h5>Detalhes do Pedido</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Peso</th>
                            <th>Preço</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Notebook Gamer XYZ</td>
                            <td>1</td>
                            <td>2.5 kg</td>
                            <td>R$ 5.500,00</td>
                        </tr>
                        <tr>
                            <td>Mouse Óptico RGB</td>
                            <td>1</td>
                            <td>0.2 kg</td>
                            <td>R$ 150,00</td>
                        </tr>
                        <tr>
                            <td>Teclado Mecânico</td>
                            <td>1</td>
                            <td>1.0 kg</td>
                            <td>R$ 500,00</td>
                        </tr>
                    </tbody>
                </table>
                <p class="mb-0"><strong>Valor total:</strong> R$ 6.150,00</p>
            </div>
        </div>

    </div>

    {{-- Mapbox ou Leaflet JS (exemplo com Leaflet) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-23.55052, -46.633308], 13); // coordenadas fictícias São Paulo
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);
        L.marker([-23.55052, -46.633308]).addTo(map)
            .bindPopup('Local atual do pedido')
            .openPopup();
    </script>

    {{-- Timeline CSS simples --}}
    <style>
        .timeline {
            list-style: none;
            padding-left: 0;
        }

        .timeline li {
            position: relative;
            padding-left: 2rem;
            margin-bottom: 1rem;
        }

        .timeline li::before {
            content: '';
            position: absolute;
            left: 0.75rem;
            top: 0.25rem;
            width: 2px;
            height: 100%;
            background: #dee2e6;
        }

        .timeline li:last-child::before {
            height: 0;
        }

        .timeline li {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
@endsection
