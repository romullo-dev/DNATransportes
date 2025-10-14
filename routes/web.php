<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CentroController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\ImportacaoController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\MotoristaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\RotaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VeiculoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.home');
});

Route::get('/all', [UsuarioController::class, 'all'])->name('all');

/* 🔐 LOGIN / LOGOUT */
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', fn() => view('home.dashboard'))->name('dashboard');

    Route::get('/user/show/{id}', [UsuarioController::class, 'show'])->name('show.user');
    Route::get('/pedidos/rastreamento', [PedidoController::class, 'rastreamento'])->name('pedidos.rastreamento');


    /* =========================================================
       🟢 ADMIN — acesso total
       ========================================================= */
    Route::middleware('role:admin')->group(function () {

        /** 👥 Usuários */
        Route::get('/user-read', [UsuarioController::class, 'read'])->name('read-user');
        Route::post('/user-store', [UsuarioController::class, 'store'])->name('store-user');
        Route::delete('/user-destroy/{user}', [UsuarioController::class, 'destroy'])->name('destroy-user');
        Route::put('user-update/{usuario}', [UsuarioController::class, 'update'])->name('update-user');
        Route::get('/user/senha/{id}', [UsuarioController::class, 'senha'])->name('senha.user');
        Route::put('user/updatePassword/{id}', [UsuarioController::class, 'updatePassword'])->name('updatePassword.user');
        Route::get('/user/procurar', [UsuarioController::class, 'procurar'])->name('usuarios.procurar');
        Route::post('/user/inserir{id}', [UsuarioController::class, 'inserirFoto'])->name('usuarios.inserir');

        /** 🚛 Motoristas */
        Route::prefix('motorista')->name('motorista.')->group(function () {
            Route::get('/', [MotoristaController::class, 'index'])->name('index');
            Route::post('/store', [MotoristaController::class, 'store'])->name('store');
            Route::get('/show/{id}', [MotoristaController::class, 'show'])->name('show');
            Route::put('/update/{id}', [MotoristaController::class, 'update'])->name('update');
        });

        /** 🧩 Demais cadastros e imports */
        Route::prefix('modelo')->name('modelo.')->group(function () {
            Route::get('/', [ModeloController::class, 'index'])->name('index');
            Route::post('/store', [ModeloController::class, 'store'])->name('store');
        });

        Route::prefix('veiculo')->name('veiculo.')->group(function () {
            Route::get('/', [VeiculoController::class, 'index'])->name('index');
            Route::post('/store', [VeiculoController::class, 'store'])->name('store');
        });

        Route::prefix('centro')->name('centro.')->group(function () {
            Route::get('/', [CentroController::class, 'index'])->name('index');
            Route::post('/store', [CentroController::class, 'store'])->name('store');
        });

        Route::prefix('importacao')->name('importacao.')->group(function () {
            Route::get('/', [ImportacaoController::class, 'index'])->name('index');
            Route::post('/store', [ImportacaoController::class, 'store'])->name('store');
        });

        /** 🏠 Endereços */
        Route::get('/endereco', [EnderecoController::class, 'index'])->name('endereco.index');
        Route::put('/endereco/{id_endereco}', [EnderecoController::class, 'update'])->name('endereco.update');

        /** 📦 Pedidos */
        Route::prefix('pedidos')->name('pedidos.')->group(function () {
            Route::get('/', [PedidoController::class, 'index'])->name('index');
            Route::post('/show', [PedidoController::class, 'show'])->name('show');
            Route::get('/editar/{id}', [PedidoController::class, 'edit'])->name('edit');
            Route::put('/editando/{id}', [PedidoController::class, 'update'])->name('update');
            Route::get('/painel', [PedidoController::class, 'painel'])->name('painel');
            Route::get('/foto', [PedidoController::class, 'foto'])->name('foto');
        });

        Route::get('/pedidos/exportar', [PedidoController::class, 'exportarExcel'])->name('pedidos.exportar');

        /** 🗺️ Rotas */
        Route::prefix('rotas')->name('rotas.')->group(function () {
            Route::get('/', [RotaController::class, 'index'])->name('index');
            Route::get('/criacao', [RotaController::class, 'create'])->name('create');
            Route::post('/store', [RotaController::class, 'store'])->name('store');
            Route::post('/entrega', [RotaController::class, 'store_entrega'])->name('entrega.store');
            Route::get('/show/{rotas}', [RotaController::class, 'show'])->name('show');
            Route::post('/historico', [RotaController::class, 'historico'])->name('historico');
        });
    });


    /* =========================================================
       🟠 OPERADOR — tudo, menos usuários
       ========================================================= */
    Route::middleware('role:operador')->group(function () {

        /** 🚛 Motoristas */
        Route::prefix('motorista')->name('motorista.')->group(function () {
            Route::get('/', [MotoristaController::class, 'index'])->name('index');
            Route::get('/show/{id}', [MotoristaController::class, 'show'])->name('show');
            Route::post('/store', [MotoristaController::class, 'store'])->name('store');
            Route::put('/update/{id}', [MotoristaController::class, 'update'])->name('update');
        });

        /** 🧩 Cadastros */
        Route::prefix('modelo')->name('modelo.')->group(function () {
            Route::get('/', [ModeloController::class, 'index'])->name('index');
            Route::post('/store', [ModeloController::class, 'store'])->name('store');
        });

        Route::prefix('veiculo')->name('veiculo.')->group(function () {
            Route::get('/', [VeiculoController::class, 'index'])->name('index');
            Route::post('/store', [VeiculoController::class, 'store'])->name('store');
        });

        Route::prefix('centro')->name('centro.')->group(function () {
            Route::get('/', [CentroController::class, 'index'])->name('index');
            Route::post('/store', [CentroController::class, 'store'])->name('store');
        });

        Route::prefix('importacao')->name('importacao.')->group(function () {
            Route::get('/', [ImportacaoController::class, 'index'])->name('index');
            Route::post('/store', [ImportacaoController::class, 'store'])->name('store');
        });

        /** 🏠 Endereços */
        Route::get('/endereco', [EnderecoController::class, 'index'])->name('endereco.index');
        Route::put('/endereco/{id_endereco}', [EnderecoController::class, 'update'])->name('endereco.update');

        /** 📦 Pedidos */
        Route::prefix('pedidos')->name('pedidos.')->group(function () {
            Route::get('/', [PedidoController::class, 'index'])->name('index');
            Route::post('/show', [PedidoController::class, 'show'])->name('show');
            Route::get('/editar/{id}', [PedidoController::class, 'edit'])->name('edit');
            Route::put('/editando/{id}', [PedidoController::class, 'update'])->name('update');
            Route::get('/painel', [PedidoController::class, 'painel'])->name('painel');
            Route::get('/foto', [PedidoController::class, 'foto'])->name('foto');
        });

        Route::get('/pedidos/exportar', [PedidoController::class, 'exportarExcel'])->name('pedidos.exportar');

        /** 🗺️ Rotas (sem criar) */
        Route::prefix('rotas')->name('rotas.')->group(function () {
            Route::get('/', [RotaController::class, 'index'])->name('index');
            Route::get('/show/{rotas}', [RotaController::class, 'show'])->name('show');
            Route::post('/historico', [RotaController::class, 'historico'])->name('historico');
        });
    });


    /* =========================================================
       🔵 MOTORISTA — sem criação de rotas nem cadastros
       ========================================================= */
    Route::middleware('role:motorista')->group(function () {
        /** 🗺️ Rotas */
        Route::get('/rotas', [RotaController::class, 'index'])->name('rotas.index');
        Route::get('/rotas/show/{rotas}', [RotaController::class, 'show'])->name('rotas.show');
        Route::post('/rotas/historico', [RotaController::class, 'historico'])->name('rotas.historico');

        /** 🚛 Motoristas (somente visualização) */
        Route::prefix('motorista')->name('motorista.')->group(function () {
            Route::get('/', [MotoristaController::class, 'index'])->name('index');
            Route::get('/show/{id}', [MotoristaController::class, 'show'])->name('show');
        });

        /** 📦 Pedidos */
        Route::prefix('pedidos')->name('pedidos.')->group(function () {
            Route::get('/', [PedidoController::class, 'index'])->name('index');
        });

    });


    /* =========================================================
       🟣 CLIENTE — acesso limitado
       ========================================================= */
    Route::middleware('role:cliente')->group(function () {
        /** 📦 Pedidos */
        Route::prefix('pedidos')->name('pedidos.')->group(function () {
            Route::get('/', [PedidoController::class, 'index'])->name('index');
        });

    });
});
