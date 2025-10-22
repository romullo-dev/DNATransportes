@extends('layouts.app')

@section('content')

 @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    <div class="container-fluid py-5" style="background-color: #1b1e22; min-height: 100vh;">

        {{-- 🔸 Cabeçalho --}}
        <div class="text-center mb-5">
            <h1 class="fw-bold text-warning display-6">
                <i class="bi bi-geo-alt-fill me-2"></i> Detalhes da Rota #{{ $data->id_rotas }}
            </h1>
            <h5 class="text-muted">DNA Transportes — Monitoramento e Logística Inteligente</h5>
        </div>

        {{-- 🧾 Informações da Rota --}}
        <div class="card mb-5 border-0 shadow-lg rounded-4 overflow-hidden info-card">
            <div class="card-header text-white fw-semibold"
                style="background: linear-gradient(90deg, #017aaa, #2a9d8f); border-bottom: 3px solid #1f8574;">
                <i class="bi bi-info-circle me-2"></i> Informações da Rota
            </div>
            <div class="card-body row px-4 py-4">
                <div class="col-md-6 mb-3">
                    <p><strong>Motorista:</strong> {{ $data->motorista->usuario->nome ?? 'Não informado' }}</p>
                    <p><strong>CPF:</strong> {{ $data->motorista->usuario->cpf ?? 'Não informado' }}</p>
                    <p><strong>Veículo:</strong> {{ $data->veiculo->placa ?? 'Não informado' }}</p>
                    <p><strong>Capacidade:</strong> KG {{ $data->veiculo->capacidade_kg ?? 'Não informado' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <p><strong>Origem:</strong> {{ $data->origem->nome ?? 'Não informado' }}
                        ({{ $data->origem->uf ?? '--' }})</p>
                    <p><strong>Destino:</strong> {{ $data->destino->nome ?? 'Não informado' }}
                        ({{ $data->destino->uf ?? '--' }})</p>
                    <p><strong>Tipo de Rota:</strong> {{ $data->tipo ?? 'Não informado' }}</p>
                    <p><strong>Status Atual:</strong> {{ optional($data->historicos->last())->status ?? 'Não informado' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- 📦 Notas Fiscais --}}
        @if ($data->pedidos && $data->pedidos->count() > 0)
            <div class="card mb-5 border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header text-white fw-semibold"
                    style="background: linear-gradient(90deg, #f0c02e, #eb8721); border-bottom: 3px solid #d68a1a;">
                    <i class="bi bi-receipt me-2"></i> Notas Fiscais da Rota
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: #ffcece; color: #ffbf00; font-weight: 600;">
                            <tr>
                                <th>#</th>
                                <th>Número NFe</th>
                                <th>Valor Total</th>
                                <th>Peso</th>
                                <th>Cliente Remetente</th>
                                <th>Cliente Destinatário</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody class="table-light">
                            @foreach ($data->pedidos->unique('notaFiscal.numero_nfe') as $pedido)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $pedido->notaFiscal->numero_nfe ?? '--' }}</td>
                                    <td>R$ {{ number_format($pedido->notaFiscal->valor_total ?? 0, 2, ',', '.') }}</td>
                                    <td>{{ $pedido->notaFiscal->peso ?? '--' }} kg</td>
                                    <td>{{ $pedido->notaFiscal->remetente->nome ?? '--' }}</td>
                                    <td>{{ $pedido->notaFiscal->destinatario->nome ?? '--' }}</td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-warning fw-semibold rounded-pill shadow-sm"
                                            data-bs-toggle="modal" data-bs-target="#modalStatus{{ $pedido->id_pedido }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </td>


                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        @endif

        @foreach ($data->pedidos as $pedido)
            <div class="modal fade" id="modalStatus{{ $pedido->id_pedido }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 shadow-lg border-0">
                        <div class="modal-header text-white" style="background: linear-gradient(90deg, #017aaa, #2a9d8f);">
                            <h5 class="modal-title">
                                <i class="bi bi-pencil-square me-2"></i> Atualizar Status — Pedido #{{ $pedido->id_pedido }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <form action="{{ route('historico.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="pedido_id_pedido" value="{{ $pedido->id_pedido }}">
                                <input type="hidden" name="rotas_id_rotas" value="{{ $data->id_rotas }}">
                                <input type="hidden" name="tipo" value="{{ $data->tipo }}">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select name="status" class="form-select rounded-pill" required>
                                        <option value="">Selecione...</option>
                                        <option value="Entrega não realizada">Entrega não realizada</option>
                                        <option value="Entrega realizada">Entrega realizada</option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label>Foto (opcional)</label>
                                    <input name="foto" type="file" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label for="data" class="form-label"><i class="bi bi-calendar-event me-1"></i>Data</label>
                                    <input type="datetime-local" class="form-control" id="data" name="data" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Observações</label>
                                    <textarea name="observacao" class="form-control rounded-4" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold">
                                    <i class="bi bi-check-circle me-2"></i> Salvar
                                </button>
                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach


        {{-- 📜 Histórico --}}
        @if ($data->historicos && $data->historicos->count() > 0)
            <div class="card mb-5 border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header text-white fw-semibold"
                    style="background: linear-gradient(90deg, #1e5e73, #2a9d8f); border-bottom: 3px solid #1b7b6b;">
                    <i class="bi bi-clock-history me-2"></i> Histórico de Movimentações
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: #d8f3dc; color: #1b4332; font-weight: 600;">
                            <tr>
                                <th>Status</th>
                                <th>Data/Hora</th>
                                <th>Observações</th>
                            </tr>
                        </thead>
                        <tbody class="table-light">
                            @foreach ($data->historicos->unique('status') as $hist)
                                <tr>
                                    <td>{{ $hist->status }}</td>
                                    <td>{{ $hist->created_at?->format('d/m/Y H:i') ?? '--' }}</td>
                                    <td>{{ $hist->observacao ?? '--' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif


        {{-- 🗺️ Mapa --}}
<div class="card border-0 shadow-lg rounded-4 overflow-hidden">
    <div class="card-header text-white fw-semibold"
        style="background: linear-gradient(90deg, #002b5c, #264653); border-bottom: 3px solid #19364d;">
        <i class="bi bi-map me-2"></i> Mapa da Rota
    </div>
    <div class="card-body">
        <div id="map" style="width: 100%; height: 500px; border-radius: 8px; overflow: hidden;"></div>
        <div id="map-fallback"
            class="text-center py-5 text-muted fw-semibold"
            style="display:none;">
            🚧 Nenhuma coordenada válida encontrada para esta rota.
        </div>
    </div>
</div>

{{-- 📦 Mapbox Scripts --}}
<link href="https://api.mapbox.com/mapbox-gl-js/v3.14.0/mapbox-gl.css" rel="stylesheet" />
<script src="https://api.mapbox.com/mapbox-gl-js/v3.14.0/mapbox-gl.js"></script>

<script>
window.addEventListener('load', async () => {
    mapboxgl.accessToken = '{{ $mapboxToken ?? env('MAPBOX_TOKEN') }}';

    const origem = [{{ $data->origem->longitude ?? 0 }}, {{ $data->origem->latitude ?? 0 }}];
    const destino = [{{ $data->destino->longitude ?? 0 }}, {{ $data->destino->latitude ?? 0 }}];
    const motorista = "{{ $data->motorista->usuario->nome ?? 'Motorista' }}";
    const veiculo = "{{ $data->veiculo->placa ?? '--' }}";
    const tipoRota = "{{ $data->tipo ?? 'Rota' }}";

    const temCoordenadasValidas = origem[0] !== 0 && destino[0] !== 0;

    if (!temCoordenadasValidas) {
        document.getElementById('map').style.display = 'none';
        document.getElementById('map-fallback').style.display = 'block';
        return;
    }

    // 🗺️ Cria o mapa
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/navigation-night-v1',
        center: origem,
        zoom: 5,
        pitch: 60,
        bearing: 20,
        antialias: true
    });

    // 🧭 Controles de navegação
    map.addControl(new mapboxgl.NavigationControl());
    map.addControl(new mapboxgl.FullscreenControl());
    map.addControl(new mapboxgl.ScaleControl({ unit: 'metric' }));
    map.addControl(new mapboxgl.GeolocateControl({
        positionOptions: { enableHighAccuracy: true },
        trackUserLocation: true
    }));

    // 📍 Ajusta o zoom entre origem e destino
    const bounds = new mapboxgl.LngLatBounds();
    [origem, destino].forEach(coord => bounds.extend(coord));
    map.fitBounds(bounds, { padding: 120, duration: 1500 });

    // 🔖 Função genérica para marcador com ícone e popup
    function addMarker(coord, icon, color, title, desc) {
        const el = document.createElement('div');
        el.innerHTML = `<i class="bi ${icon}" style="font-size:26px;color:${color};
                        filter:drop-shadow(0 0 5px #000)"></i>`;
        return new mapboxgl.Marker(el)
            .setLngLat(coord)
            .setPopup(new mapboxgl.Popup({ offset: 25 })
                .setHTML(`
                    <div style="color:#0b0b0b;">
                        <h6 style="margin:0;font-weight:700;">${title}</h6>
                        <p style="margin:0;font-size:13px;">${desc}</p>
                    </div>
                `))
            .addTo(map);
    }

    // 🟢 Origem e destino
    addMarker(origem, "bi-geo-alt-fill", "#00c853",
        "Origem", "{{ $data->origem->nome ?? 'Local não informado' }} ({{ $data->origem->uf ?? '--' }})");
    addMarker(destino, "bi-flag-fill", "#ff5252",
        "Destino", "{{ $data->destino->nome ?? 'Local não informado' }} ({{ $data->destino->uf ?? '--' }})");

    // 🧭 Busca rota e desenha no mapa
    try {
        const url = `https://api.mapbox.com/directions/v5/mapbox/driving/${origem.join(',')};${destino.join(',')}?geometries=geojson&overview=full&access_token=${mapboxgl.accessToken}`;
        const response = await fetch(url);
        const json = await response.json();

        const route = json.routes[0].geometry;
        const duracaoMin = Math.round(json.routes[0].duration / 60);
        const distanciaKm = (json.routes[0].distance / 1000).toFixed(1);
        const meio = route.coordinates[Math.floor(route.coordinates.length / 2)];

        // 🚗 Linha principal
        map.addSource('route', { type: 'geojson', data: { type: 'Feature', geometry: route } });
        map.addLayer({
            id: 'route',
            type: 'line',
            source: 'route',
            layout: { 'line-join': 'round', 'line-cap': 'round' },
            paint: {
                'line-color': '#2a9d8f',
                'line-width': 6,
                'line-opacity': 0.9
            }
        });

        // ✨ Linha animada (efeito de movimento)
        map.addLayer({
            id: 'route-animation',
            type: 'line',
            source: 'route',
            paint: {
                'line-color': '#ffbf00',
                'line-width': 6,
                'line-opacity': 0.6,
                'line-dasharray': [0, 2]
            }
        });
        let dashOffset = 0;
        function animateRoute() {
            dashOffset -= 0.1;
            map.setPaintProperty('route-animation', 'line-dasharray', [dashOffset, 2]);
            requestAnimationFrame(animateRoute);
        }
        animateRoute();

        // 🚛 Motorista no meio da rota
        const truckEl = document.createElement('div');
        truckEl.innerHTML = `
            <div style="display:flex;flex-direction:column;align-items:center;">
                <i class="bi bi-truck-front-fill"
                   style="font-size:28px;color:#f4a261;
                   filter:drop-shadow(0 0 6px #000)"></i>
                <span style="
                    background:#264653;
                    color:#fff;
                    font-size:12px;
                    padding:3px 8px;
                    border-radius:6px;
                    margin-top:4px;
                    font-weight:600;
                ">${motorista}<br>${veiculo}</span>
            </div>`;
        new mapboxgl.Marker(truckEl).setLngLat(meio).addTo(map);

        // 🧾 Caixa de informações da rota
        const infoBox = document.createElement('div');
        infoBox.innerHTML = `
            <div style="
                position:absolute;
                top:10px;
                left:10px;
                background:#264653;
                color:white;
                padding:12px 16px;
                border-radius:10px;
                font-size:14px;
                font-weight:500;
                box-shadow:0 4px 10px rgba(0,0,0,0.3);
            ">
                <strong>🧭 Tipo:</strong> ${tipoRota}<br>
                <strong>📏 Distância:</strong> ${distanciaKm} km<br>
                <strong>⏱ Estimado:</strong> ${duracaoMin} min
            </div>`;
        map.getContainer().appendChild(infoBox);

    } catch (err) {
        console.error('Erro ao carregar rota:', err);
    }
});
</script>


    {{-- 🎨 Fundo Unificado DNA --}}
    <style>
        body {
            background-color: #1b1e22 !important;
        }

        .info-card {
            background: #23272e;
            color: #f1f1f1;
            border-radius: 1rem;
        }

        .table-light td,
        .table-light th {
            color: #222 !important;
        }

        .table-hover tbody tr:hover {
            background: rgba(255, 215, 0, 0.1) !important;
        }

        .text-muted {
            color: #b0b0b0 !important;
        }

        .shadow-lg {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5) !important;
        }

        .card {
            transition: 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }
    </style>
@endsection