<?php

use Illuminate\Support\Facades\Route;

Route::get('/home', function () {
    return response()->json([
        'titulo' => 'Bem-vindo à Home',
        'mensagem' => 'Esta é a API retornando dados da home',
        'dica' => 'Aqui você colocaria dados que a view consumiria'
    ]);
});
