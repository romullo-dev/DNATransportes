<?php

use App\Http\Controllers\api\API;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MotoristaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsuarioController;


Route::post('/login', [API::class, 'loginApi'])->name('login.submit');

Route::get('/rotas', [API::class, 'index']);

Route::POST('/historico', [API::class, 'historico']);