<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsuarioController;

Route::get('/usuarios', [UsuarioController::class, 'all']); // retorna todos os usuários
