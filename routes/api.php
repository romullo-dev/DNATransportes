<?php

use App\Http\Controllers\api\API;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MotoristaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsuarioController;


Route::post('/login', [API::class, 'loginApi'])->name('login.submit');
use App\Http\Controllers\api\RotaApiController;

Route::middleware('auth:sanctum')->group(function () {
    // Listar rotas do motorista logado
    Route::get('/rotas', [API::class, 'index']);

    // Detalhar uma rota específica
    Route::get('/rotas/{id}', [API::class, 'show']);

    // Atualizar histórico da rota (status, observação, foto)
    Route::post('/rotas/historico', [API::class, 'historico']);
});
