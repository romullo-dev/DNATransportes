@extends('layouts.app')

@section('content')
<div class="container py-4" style="min-height: 90vh;">
    {{-- Mensagens de sucesso/erro --}}
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

    {{-- Título do Painel --}}
    <div class="text-center mb-5">
        <h1 class="fw-bold display-5"
            style="color: #f1c40f; text-shadow: 1px 1px 3px rgba(0,0,0,0.4);">
            📊 Painel de Análises - <span style="color:#ffdd00;">DNA Transportes</span>
        </h1>
        <p class="fs-5" style="color:#c0b8a2;">
            Acompanhe em tempo real os principais indicadores do seu negócio 🚛
        </p>
    </div>

    {{-- Cards de Resumo --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 text-center p-3 bg-light">
                <h5 class="text-muted">Pedidos</h5>
                <h2 class="fw-bold" style="color:#f1c40f;">1.245</h2>
                <p class="mb-0 text-success">+12% este mês</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 text-center p-3 bg-light">
                <h5 class="text-muted">Clientes</h5>
                <h2 class="fw-bold" style="color:#f1c40f;">532</h2>
                <p class="mb-0 text-success">+8 novos hoje</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 text-center p-3 bg-light">
                <h5 class="text-muted">Rotas Ativas</h5>
                <h2 class="fw-bold" style="color:#f1c40f;">87</h2>
                <p class="mb-0 text-warning">Em andamento</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 text-center p-3 bg-light">
                <h5 class="text-muted">Entregas Concluídas</h5>
                <h2 class="fw-bold" style="color:#f1c40f;">1.102</h2>
                <p class="mb-0 text-success">95% de sucesso</p>
            </div>
        </div>
    </div>

    {{-- Gráfico de Pedidos --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <h5 class="card-title fw-bold">📈 Pedidos nos últimos 6 meses</h5>
            <canvas id="graficoPedidos"></canvas>
        </div>
    </div>

    {{-- Exportar Relatório --}}
    <div class="text-end mt-3">
        <a href="#" class="btn btn-success fw-bold shadow-sm">
            <i class="bi bi-file-earmark-excel"></i> Exportar Relatório Excel
        </a>
    </div>
</div>

{{-- Script Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graficoPedidos');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set'],
            datasets: [{
                label: 'Pedidos',
                data: [180, 220, 300, 280, 350, 400],
                borderColor: '#f1c40f',
                backgroundColor: 'rgba(241, 196, 15, 0.25)',
                tension: 0.3,
                fill: true,
                borderWidth: 3,
                pointBackgroundColor: '#f39c12',
                pointRadius: 6
            }]
        },
        options: {
            plugins: {
                legend: { 
                    labels: { 
                        color: '#333',
                        font: { weight: 'bold', size: 14 }
                    }
                }
            },
            scales: {
                x: { ticks: { color: '#555', font: { size: 13 } } },
                y: { ticks: { color: '#555', font: { size: 13 } } }
            }
        }
    });
</script>
@endsection
