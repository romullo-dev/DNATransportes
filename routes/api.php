<?php

use App\Http\Controllers\api\API;
use App\Http\Controllers\api\RotaController as RotaApiController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [API::class, 'loginApi'])->name('login.submit');

Route::prefix('rotas')->group(function () {
    Route::get('/', [RotaApiController::class, 'index']);
    Route::post('/', [RotaApiController::class, 'store']);
    Route::get('/{rota}', [RotaApiController::class, 'show']);
    Route::put('/{rota}', [RotaApiController::class, 'update']);
    Route::patch('/{rota}', [RotaApiController::class, 'update']);
    Route::delete('/{rota}', [RotaApiController::class, 'destroy']);
    Route::get('/{rota}/historicos', [RotaApiController::class, 'historicos']);
    Route::post('/{rota}/historicos', [RotaApiController::class, 'registrarHistorico']);
});
