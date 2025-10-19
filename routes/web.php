<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CentroController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\HistoricoController;
use App\Http\Controllers\ImportacaoController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\MotoristaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\RotaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VeiculoController;
use App\Models\CentroDistribuicao;
use App\Models\Pedido;

Route::get('/', function () {
    return view('home.home');
});

Route::get('/all', [UsuarioController::class, 'all'])->name('all');



//return redirect()->route('login');

Route::get('/login', [AuthController::class, 'index'])->name(name: 'login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return view('home.dashboard');
    })->name('dashboard');

    /*Route::prefix('user')->name('user.')->group(function() {

    });*/


    Route::get('/user-read', [UsuarioController::class, 'read'])->name('read-user');

    Route::post('/user-store', [UsuarioController::class, 'store'])->name('store-user');

    Route::delete('/user-destroy/{user}', [UsuarioController::class, 'destroy'])->name('destroy-user');

    Route::put('user-update/{usuario}', [UsuarioController::class, 'update'])->name('update-user');

    Route::get('/user/show/{id}', [UsuarioController::class, 'show'])->name('show.user');

    Route::get('/user/senha/{id}', [UsuarioController::class, 'senha'])->name('senha.user');

    Route::put('user/updatePassword/{id}', [UsuarioController::class, 'updatePassword'])->name('updatePassword.user');

    Route::get('/user/procurar', [UsuarioController::class, 'procurar'])->name('usuarios.procurar');

    Route::post('/user/inserir{id}', [UsuarioController::class, 'inserirFoto'])->name('usuarios.inserir');

    Route::prefix('motorista')->name('motorista.')->group(function () {
        Route::get('/', [MotoristaController::class, 'index'])->name('index');
        Route::post('/store', [MotoristaController::class, 'store'])->name('store');
        Route::post('/show/{id}', [MotoristaController::class, 'show'])->name('show');
        Route::put('/update/{id}', [MotoristaController::class, 'update'])->name('update');
    });

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
        Route::post('/store',  [ImportacaoController::class, 'store'])->name('store');
    });

    Route::prefix('pedidos')->name('pedidos.')->group(function () {
        Route::get('/', [PedidoController::class, 'index'])->name('index');
        Route::get('/rastreamento', [PedidoController::class, 'rastreamento'])->name('rastreamento');
        Route::post('/show', [PedidoController::class, 'show'])->name('show');
        Route::get('/editar/{id}', [PedidoController::class, 'edit'])->name('edit');
        Route::put('/editando/{id}', [PedidoController::class, 'update'])->name('update');
        Route::get('/painel', [PedidoController::class, 'painel'])->name('painel');
        Route::get('/foto', [PedidoController::class, 'foto'])->name('foto');
    });

    Route::prefix('endereco')->name('endereco.')->group(function () {
        Route::get('/', [EnderecoController::class, 'index'])->name('index');
        Route::put('/{id_endereco}', [EnderecoController::class, 'update'])->name('update');
    });

    Route::prefix('historico')->name('historico.')->group(function () {
        Route::post('/store', [HistoricoController::class, 'store'])->name('store');
    });


    Route::prefix('rotas')->name('rotas.')->group(function () {
        Route::get('/', [RotaController::class, 'index'])->name('index');
        Route::get('/criacao', [RotaController::class, 'create'])->name('create');
        Route::post('/store', [RotaController::class, 'store'])->name('store');
        Route::post('/entrega', [RotaController::class, 'store_entrega'])->name('entrega.store');
        Route::get('/show/{rotas}', [RotaController::class, 'show'])->name('show');
        Route::post('/historico', [RotaController::class, 'historico'])->name('historico');
    });
});
