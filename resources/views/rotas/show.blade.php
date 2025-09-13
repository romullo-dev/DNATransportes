@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">📍 Detalhes da Rota #{{ $data->id_rotas }}</h1>

        {{-- 🔹 Informações da Rota --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header text-white" style="background: linear-gradient(90deg, #264653, #2a9d8f);">
                <h5 class="mb-0">Informações da Rota</h5>
            </div>
            <div class="card-body row">
                <div class="col-md-6 mb-3">
                    <p><strong>Motorista:</strong> {{ $data->motorista->usuario->nome ?? 'Não informado' }}</p>
                    <p><strong>Documento:</strong> {{ $data->motorista->usuario->cpf ?? 'Não informado' }}</p>
                    <p><strong>Veículo:</strong> {{ $data->veiculo->placa ?? 'Não informado' }}</p>
                    <p><strong>Capacidade:</strong> KG {{ $data->veiculo->capacidade_kg ?? 'Não informado' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <p><strong>Origem:</strong> {{ $data->origem->nome ?? 'Não informado' }} ({{ $data->origem->uf ?? '--' }})</p>
                    <p><strong>Destino:</strong> {{ $data->destino->nome ?? 'Não informado' }} ({{ $data->destino->uf ?? '--' }})</p>
                    <p><strong>Status Atual:</strong> {{ optional($data->historicos->last())->status ?? 'Não informado' }}</p>
                    <p><strong>Data de Criação:</strong> {{ $data->historicos->first()->created_at?->format('d/m/Y H:i') ?? '--' }}</p>
                </div>
            </div>
        </div>

        {{-- 📜 Histórico de Status --}}
        @if ($data->historicos && $data->historicos->count() > 0)
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Histórico da Rota</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Status</th>
                                <th>Data/Hora</th>
                                <th>Observações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->historicos as $hist)
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

        {{-- 🗺️ Mapa da Rota --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header text-white" style="background: linear-gradient(90deg, #264653, #2a9d8f);">
                <h5 class="mb-0">Mapa da Rota</h5>
            </div>
            <div class="card-body">
                <div id="map" style="width: 100%; height: 400px; border-radius: 8px; overflow: hidden;"></div>
            </div>
        </div>
    </div>

    {{-- 📦 Mapbox Scripts --}}
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.14.0/mapbox-gl.css" rel="stylesheet" />
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.14.0/mapbox-gl.js"></script>

    <script>
        mapboxgl.accessToken = '{{ $mapboxToken }}';

        const origem = [{{ $data->origem->longitude }}, {{ $data->origem->latitude }}];
        const destino = [{{ $data->destino->longitude }}, {{ $data->destino->latitude }}];
        const veiculo = origem;

        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v12',
            center: origem,
            zoom: 12
        });

        const bounds = new mapboxgl.LngLatBounds();
        [origem, destino, veiculo].forEach(coord => bounds.extend(coord));
        map.fitBounds(bounds, { padding: 60 });

        function createMarkerWithFixedLabel(coordinates, color, labelText, customIconHTML = null) {
            const el = document.createElement('div');
            el.style.position = 'relative';
            el.style.display = 'flex';
            el.style.flexDirection = 'column';
            el.style.alignItems = 'center';

            if (customIconHTML) {
                el.innerHTML = customIconHTML;
            } else {
                el.style.width = '22px';
                el.style.height = '22px';
                el.style.backgroundColor = color;
                el.style.borderRadius = '50%';
                el.style.border = '2px solid white';
                el.style.boxShadow = '0 0 6px rgba(0,0,0,0.3)';
            }

            const label = document.createElement('div');
            label.textContent = labelText;
            label.style.marginTop = '6px';
            label.style.backgroundColor = '#264653';
            label.style.color = 'white';
            label.style.padding = '4px 10px';
            label.style.borderRadius = '6px';
            label.style.fontSize = '13px';
            label.style.fontWeight = '500';
            label.style.boxShadow = '0 2px 6px rgba(0,0,0,0.3)';
            label.style.whiteSpace = 'nowrap';
            label.style.pointerEvents = 'none';
            label.style.userSelect = 'none';

            el.appendChild(label);

            return new mapboxgl.Marker(el).setLngLat(coordinates).addTo(map);
        }

        createMarkerWithFixedLabel(origem, '#2a9d8f', "Origem - {{ $data->origem->nome }}");
        createMarkerWithFixedLabel(destino, '#e76f51', "Destino - {{ $data->destino->nome }}");

        const truckSVG = `
            <div style="width:40px; height:40px; display:flex; justify-content:center; align-items:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#2a9d8f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="3" width="15" height="13"></rect>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                </svg>
            </div>
        `;
        createMarkerWithFixedLabel(veiculo, null, "{{ $data->veiculo->placa }} - {{ $data->motorista->usuario->nome }}", truckSVG);

        map.on('load', async () => {
            const url = `https://api.mapbox.com/directions/v5/mapbox/driving/${origem.join(',')};${destino.join(',')}?geometries=geojson&access_token=${mapboxgl.accessToken}`;

            try {
                const response = await fetch(url);
                const data = await response.json();

                const route = data.routes[0].geometry;
                const duracaoMin = Math.round(data.routes[0].duration / 60);
                const distanciaKm = (data.routes[0].distance / 1000).toFixed(1);

                map.addSource('route', {
                    type: 'geojson',
                    data: {
                        type: 'Feature',
                        geometry: route
                    }
                });

                map.addLayer({
                    id: 'route',
                    type: 'line',
                    source: 'route',
                    layout: {
                        'line-join': 'round',
                        'line-cap': 'round'
                    },
                    paint: {
                        'line-color': '#2a9d8f',
                        'line-width': 5,
                        'line-opacity': 0.9
                    }
                });

                                const infoBox = document.createElement('div');
                infoBox.innerHTML = `
                    <div style="
                        position: absolute;
                        top: 10px;
                        left: 10px;
                        background-color: #264653;
                        color: white;
                        padding: 10px 16px;
                        border-radius: 8px;
                        font-size: 14px;
                        font-weight: 500;
                        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
                        z-index: 999;
                    ">
                        🕒 Estimado: ${duracaoMin} min<br>
                        📏 Distância: ${distanciaKm} km
                    </div>
                `;
                map.getContainer().appendChild(infoBox);

            } catch (error) {
                console.error('Erro ao carregar rota:', error);
            }
        });
    </script>
@endsection