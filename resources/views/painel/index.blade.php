@extends('layouts.app')

@section('content')
<div class="container-fluid py-5"
     style="min-height: 100vh; background: linear-gradient(180deg, #101820 0%, #151a1f 50%, #1e2329 100%);">

    {{-- ✅ Mensagens --}}
    @if (session('success'))
        <div class="alert alert-success shadow-sm text-center fw-semibold rounded-pill">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger shadow-sm text-center fw-semibold rounded-pill">{{ session('error') }}</div>
    @endif

    {{-- 🔸 Cabeçalho --}}
    <div class="text-center mb-5">
        <h1 class="fw-bold text-warning display-6">
            <i class="bi bi-bar-chart-fill me-2"></i> Painel de Análises
        </h1>
        <h5 class="text-light-50">DNA Transportes — Gestão Inteligente de Pedidos e Rotas 🚛</h5>
    </div>

    {{-- 🔹 Cards de Indicadores --}}
    <div class="row g-4 justify-content-center mb-5">
        @php
            $cards = [
                ['title' => 'Total de Pedidos', 'value' => $totalPedidos ?? 0, 'color' => 'text-warning', 'icon' => 'bi-box-seam', 'desc' => 'Pedidos registrados'],
                ['title' => 'Entregues', 'value' => $statusEntregue ?? 0, 'color' => 'text-success', 'icon' => 'bi-check-circle-fill', 'desc' => 'Concluídos com sucesso'],
                ['title' => 'Em Trânsito', 'value' => $statusTransito ?? 0, 'color' => 'text-info', 'icon' => 'bi-truck', 'desc' => 'A caminho do destino'],
                ['title' => 'Devoluções', 'value' => $statusCancelado ?? 0, 'color' => 'text-danger', 'icon' => 'bi-arrow-counterclockwise', 'desc' => 'Pedidos devolvidos']
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 rounded-4 shadow-lg p-4 text-center h-100 indicador-card">
                    <div class="mb-3">
                        <i class="bi {{ $card['icon'] }} fs-2 {{ $card['color'] }}"></i>
                    </div>
                    <h6 class="text-secondary fw-semibold">{{ $card['title'] }}</h6>
                    <h2 class="fw-bold {{ $card['color'] }}">{{ $card['value'] }}</h2>
                    <p class="small text-desc">{{ $card['desc'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- 📊 Gráficos --}}
    <div class="row g-4">
        {{-- 📈 Gráfico de Pedidos --}}
        <div class="col-lg-8">
            <div class="card bg-dark text-light border-0 rounded-4 shadow-lg h-100">
                <div class="card-body">
                    <h5 class="fw-bold text-warning mb-3">
                        <i class="bi bi-graph-up-arrow me-2"></i> Pedidos nos Últimos 6 Meses
                    </h5>
                    <canvas id="graficoPedidos" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        {{-- 🥧 Gráfico de Status --}}
        <div class="col-lg-4">
            <div class="card bg-dark text-light border-0 rounded-4 shadow-lg h-100">
                <div class="card-body">
                    <h5 class="fw-bold text-warning mb-3">
                        <i class="bi bi-pie-chart-fill me-2"></i> Status dos Pedidos
                    </h5>
                    <canvas id="graficoStatus" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- 📂 Exportar --}}
    <div class="text-end mt-5">
        <a href="#" class="btn btn-dna fw-bold rounded-pill shadow-sm text-dark px-4 py-2">
            <i class="bi bi-file-earmark-excel-fill me-2"></i> Exportar Relatório Excel
        </a>
    </div>
</div>

{{-- 📊 Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctxPedidos = document.getElementById('graficoPedidos');
    new Chart(ctxPedidos, {
        type: 'line',
        data: {
            labels: {!! json_encode($meses) !!},
            datasets: [{
                label: 'Pedidos Criados',
                data: {!! json_encode($dadosPedidos) !!},
                borderColor: '#FFD700',
                backgroundColor: 'rgba(255,215,0,0.25)',
                tension: 0.4,
                borderWidth: 3,
                fill: true,
                pointRadius: 5,
                pointBackgroundColor: '#FFD700',
                pointBorderColor: '#000'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { color: '#fff', font: { weight: 'bold', size: 13 } } },
                tooltip: {
                    backgroundColor: '#111',
                    titleColor: '#FFD700',
                    bodyColor: '#fff'
                }
            },
            scales: {
                x: {
                    ticks: { color: '#ccc' },
                    grid: { color: 'rgba(255,255,255,0.1)' }
                },
                y: {
                    ticks: { color: '#ccc' },
                    grid: { color: 'rgba(255,255,255,0.1)' }
                }
            }
        }
    });

    const ctxStatus = document.getElementById('graficoStatus');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Entrega realizada', 'Em andamento', 'Não realizada', 'Outros'],
            datasets: [{
                data: [
                    {{ $statusEntregue }},
                    {{ $statusTransito }},
                    {{ $statusCancelado }},
                    {{ $statusOutros }}
                ],
                backgroundColor: ['#2ecc71', '#3498db', '#e74c3c', '#FFD700'],
                borderColor: '#101820',
                borderWidth: 3,
                hoverOffset: 10
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#fff',
                        font: { weight: 'bold', size: 13 }
                    }
                }
            },
            cutout: '70%'
        }
    });
</script>

{{-- 🌙 Estilo DNA --}}
<style>
    :root {
        --dna-gold: #FFD700;
        --dna-orange: #eb8721;
        --dna-dark: #101820;
        --dna-gray: #1c1f26;
        --dna-light: rgba(255,255,255,0.6);
    }

    .text-light-50 { color: var(--dna-light); }

    .indicador-card {
        background: linear-gradient(145deg, var(--dna-gray), #111722);
        border: 1px solid rgba(255,255,255,0.08);
        color: #fff;
        transition: 0.3s;
    }
    .indicador-card:hover {
        border-color: var(--dna-orange);
        transform: scale(1.02);
    }

    .text-desc {
        color: rgba(255,255,255,0.55);
    }

    .btn-dna {
        background: linear-gradient(90deg, var(--dna-gold), var(--dna-orange));
        border: none;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-dna:hover {
        background: linear-gradient(90deg, var(--dna-orange), var(--dna-gold));
        color: #000;
    }

    .shadow-lg {
        box-shadow: 0 10px 20px rgba(0,0,0,0.6) !important;
    }

    canvas { max-width: 100%; }
</style>
@endsection
