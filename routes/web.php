<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PecaController;
use App\Http\Controllers\MedidaController;
use App\Http\Controllers\PecaEtapaController;
use App\Http\Controllers\PecaMaterialController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\UsuarioController;


/*
|--------------------------------------------------------------------------
| PÁGINA INICIAL
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
|
| Apenas usuários que ainda não estão logados.
|
*/

Route::middleware('guest')->group(function () {

    Route::get(
        '/login',
        [LoginController::class, 'show']
    )->name('login');

    Route::post(
        '/login',
        [LoginController::class, 'login']
    )->name('login.process');

});


/*
|--------------------------------------------------------------------------
| USUÁRIOS AUTENTICADOS
|--------------------------------------------------------------------------
|
| Qualquer usuário logado pode acessar dashboard,
| pesquisar modelos e consultar um modelo.
|
*/

Route::middleware('auth')->group(function () {


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | LISTAGEM DE MODELOS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/pecas',
        [PecaController::class, 'index']
    )->name('pecas.index');


    /*
    |--------------------------------------------------------------------------
    | ADMINISTRADOR / ENCARREGADO
    |--------------------------------------------------------------------------
    |
    | Somente esses cargos podem cadastrar ou alterar informações.
    |
    */

    Route::middleware(
        'cargo:Administrador,Encarregado'
    )->group(function () {



    /*
|--------------------------------------------------------------------------
| SOMENTE ADMINISTRADOR
|--------------------------------------------------------------------------
*/

Route::middleware(
    'cargo:Administrador'
)->group(function () {

    Route::get(
        '/usuarios',
        [UsuarioController::class, 'index']
    )->name('usuarios.index');


    Route::get(
        '/usuarios/create',
        [UsuarioController::class, 'create']
    )->name('usuarios.create');


    Route::post(
        '/usuarios',
        [UsuarioController::class, 'store']
    )->name('usuarios.store');


    Route::get(
        '/usuarios/{usuario}/edit',
        [UsuarioController::class, 'edit']
    )->name('usuarios.edit');


    Route::put(
        '/usuarios/{usuario}',
        [UsuarioController::class, 'update']
    )->name('usuarios.update');
   
    Route::delete(
    '/pecas/{peca}',
    [PecaController::class, 'destroy']
)->name('pecas.destroy');

    Route::delete(
    '/usuarios/{usuario}',
    [UsuarioController::class, 'destroy']
)->name('usuarios.destroy');

});

/*
|--------------------------------------------------------------------------
| MEDIDAS
|--------------------------------------------------------------------------
*/

Route::post(
    '/pecas/{peca}/medidas',
    [MedidaController::class, 'store']
)->name('pecas.medidas.store');


Route::get(
    '/pecas/{peca}/medidas/{medida}/edit',
    [MedidaController::class, 'edit']
)->name('pecas.medidas.edit');


Route::put(
    '/pecas/{peca}/medidas/{medida}',
    [MedidaController::class, 'update']
)->name('pecas.medidas.update');

    /*
|--------------------------------------------------------------------------
| MATERIAIS
|--------------------------------------------------------------------------
*/

Route::post(
    '/pecas/{peca}/materiais',
    [PecaMaterialController::class, 'store']
)->name('pecas.materiais.store');


Route::get(
    '/pecas/{peca}/materiais/{pecaMaterial}/edit',
    [PecaMaterialController::class, 'edit']
)->name('pecas.materiais.edit');


Route::put(
    '/pecas/{peca}/materiais/{pecaMaterial}',
    [PecaMaterialController::class, 'update']
)->name('pecas.materiais.update');

        /*
        |--------------------------------------------------------------------------
        | CADASTRAR MODELO
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pecas/create',
            [PecaController::class, 'create']
        )->name('pecas.create');


        Route::post(
            '/pecas',
            [PecaController::class, 'store']
        )->name('pecas.store');


        /*
        |--------------------------------------------------------------------------
        | EDITAR MODELO
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pecas/{peca}/edit',
            [PecaController::class, 'edit']
        )->name('pecas.edit');


        Route::put(
            '/pecas/{peca}',
            [PecaController::class, 'update']
        )->name('pecas.update');


        /*
        |--------------------------------------------------------------------------
        | MEDIDAS
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/pecas/{peca}/medidas',
            [MedidaController::class, 'store']
        )->name('pecas.medidas.store');


        /*
        |--------------------------------------------------------------------------
        | ETAPAS
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/pecas/{peca}/etapas',
            [PecaEtapaController::class, 'store']
        )->name('pecas.etapas.store');


        /*
        |--------------------------------------------------------------------------
        | EDITAR ETAPA
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pecas/{peca}/etapas/{etapa}/edit',
            [PecaEtapaController::class, 'edit']
        )->name('pecas.etapas.edit');


        Route::put(
            '/pecas/{peca}/etapas/{etapa}',
            [PecaEtapaController::class, 'update']
        )->name('pecas.etapas.update');


        /*
        |--------------------------------------------------------------------------
        | MATERIAIS
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/pecas/{peca}/materiais',
            [PecaMaterialController::class, 'store']
        )->name('pecas.materiais.store');


        /*
        |--------------------------------------------------------------------------
        | FOTOS
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/pecas/{peca}/fotos',
            [FotoController::class, 'store']
        )->name('pecas.fotos.store');
        
        Route::delete(
            '/pecas/{peca}/fotos/{foto}',
            [FotoController::class, 'destroy']
        )->name('pecas.fotos.destroy');

    });


    /*
    |--------------------------------------------------------------------------
    | VISUALIZAR MODELO
    |--------------------------------------------------------------------------
    |
    | Deve ficar depois de /pecas/create e /pecas/{peca}/edit.
    |
    */

    Route::get(
        '/pecas/{peca}',
        [PecaController::class, 'show']
    )->name('pecas.show');


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/logout',
        [LoginController::class, 'logout']
    )->name('logout');

});