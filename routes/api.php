<?php

use App\Http\Controllers\Api\API;
use App\Http\Controllers\Api\V1\AnalyticsController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [API::class, 'loginApi'])->name('login.submit');

Route::get('/rotas', [API::class, 'index']);

Route::POST('/historico', [API::class, 'historico']);

Route::middleware(['auth:sanctum', 'analytics.access'])
    ->prefix('v1/analytics')
    ->name('api.v1.analytics.')
    ->group(function () {
        Route::get('/resumo', [AnalyticsController::class, 'resumo'])->name('resumo');
        Route::get('/pedidos', [AnalyticsController::class, 'pedidos'])->name('pedidos');
        Route::get('/rotas', [AnalyticsController::class, 'rotas'])->name('rotas');
        Route::get('/motoristas', [AnalyticsController::class, 'motoristas'])->name('motoristas');
        Route::get('/clientes', [AnalyticsController::class, 'clientes'])->name('clientes');
        Route::get('/ocorrencias', [AnalyticsController::class, 'ocorrencias'])->name('ocorrencias');
        Route::get('/sla', [AnalyticsController::class, 'sla'])->name('sla');
        Route::get('/faturamento', [AnalyticsController::class, 'faturamento'])->name('faturamento');
        Route::get('/filiais', [AnalyticsController::class, 'filiais'])->name('filiais');
        Route::get('/evolucao-mensal', [AnalyticsController::class, 'evolucaoMensal'])->name('evolucao-mensal');
    });
