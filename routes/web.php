<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PecaController;
use App\Http\Controllers\MedidaController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'show'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.process');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    Route::get('/pecas', [PecaController::class, 'index'])
        ->name('pecas.index');

    Route::get('/pecas/create', [PecaController::class, 'create'])
        ->name('pecas.create');

    Route::post('/pecas', [PecaController::class, 'store'])
        ->name('pecas.store');

    Route::get('/pecas/{peca}', [PecaController::class, 'show'])
        ->name('pecas.show');

    Route::post(
        '/pecas/{peca}/medidas',
            [MedidaController::class, 'store']
            )->name('pecas.medidas.store');
});