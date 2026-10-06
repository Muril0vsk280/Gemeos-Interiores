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
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
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
    */

    Route::middleware(
        'cargo:Administrador,Encarregado'
    )->group(function () {


        /*
        |--------------------------------------------------------------------------
        | MODELOS
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
        | ETAPAS
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/pecas/{peca}/etapas',
            [PecaEtapaController::class, 'store']
        )->name('pecas.etapas.store');


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


        Route::get(
            '/pecas/{peca}/materiais/{pecaMaterial}/edit',
            [PecaMaterialController::class, 'edit']
        )->name('pecas.materiais.edit');


        Route::put(
            '/pecas/{peca}/materiais/{pecaMaterial}',
            [PecaMaterialController::class, 'update']
        )->name('pecas.materiais.update');


        Route::delete(
            '/pecas/{peca}/materiais/{pecaMaterial}',
            [PecaMaterialController::class, 'destroy']
        )->name('pecas.materiais.destroy');


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


        /*
        |--------------------------------------------------------------------------
        | FOTO PRINCIPAL
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/pecas/{peca}/foto-principal',
            [FotoController::class, 'storePrincipal']
        )->name('pecas.foto-principal.store');


        Route::put(
            '/pecas/{peca}/foto-principal/{foto}',
            [FotoController::class, 'updatePrincipal']
        )->name('pecas.foto-principal.update');

    }); // FIM ADMINISTRADOR / ENCARREGADO


    /*
    |--------------------------------------------------------------------------
    | SOMENTE ADMINISTRADOR
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'cargo:Administrador'
    )->group(function () {


        /*
        |--------------------------------------------------------------------------
        | USUÁRIOS
        |--------------------------------------------------------------------------
        */

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
            '/usuarios/{usuario}',
            [UsuarioController::class, 'destroy']
        )->name('usuarios.destroy');


        /*
        |--------------------------------------------------------------------------
        | EXCLUIR MODELO
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/pecas/{peca}',
            [PecaController::class, 'destroy']
        )->name('pecas.destroy');

    }); // FIM SOMENTE ADMINISTRADOR


    /*
    |--------------------------------------------------------------------------
    | VISUALIZAR MODELO
    |--------------------------------------------------------------------------
    |
    | Qualquer usuário autenticado pode visualizar.
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

}); // FIM AUTH