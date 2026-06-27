<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarbeiroController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FCMController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;


// =========================
// BARBEIRO
// =========================
Route::prefix('barbeiro')->group(function () {

    // Login
    Route::get('/login', [AuthController::class, 'login'])
        ->name('barbeiro.login');

    Route::post('/login', [AuthController::class, 'autenticar'])
        ->name('barbeiro.autenticar');

    // Rotas protegidas
    Route::middleware('auth')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('barbeiro.logout');

        Route::get(
            '/novoBarbeiro',
            [BarbeiroController::class, 'create']
        )->name('barbeiro.novoBarbeiro');

        Route::post(
            '/criarBarbeiro',
            [BarbeiroController::class, 'store']
        )->name('barbeiro.criarBarbeiro');


        Route::get(
            '/createEditar/{id}',
            [BarbeiroController::class, 'createEditar']
        )->name('barbeiro.createEditar');

        Route::post(
            '/update/{id}',
            [BarbeiroController::class, 'update']
        )->name('barbeiro.update');


        Route::get(
            '/agendamentos',
            [BarbeiroController::class, 'index']
        )->name('barbeiro.agendamento.index');

        Route::get(
            '/clientes',
            [BarbeiroController::class, 'clientes']
        )->name('barbeiro.clientes');

        Route::get(
            '/barbeiros',
            [BarbeiroController::class, 'barbeiros']
        )->name('barbeiro.barbeiros');

        Route::delete(
            '/clientes/{cliente}',
            [BarbeiroController::class, 'destroyCliente']
        )->name('barbeiro.clientes.destroyCliente');

        Route::delete(
            '/barbeiros/{barbeiro}',
            [BarbeiroController::class, 'destroyBarbeiro']
        )->name('barbeiro.barbeiros.destroyBarbeiro');

        Route::delete(
            '/agendamentos/{id}',
            [BarbeiroController::class, 'cancelar']
        )->name('barbeiro.agendamento.cancelar');

    });

});

// =========================
// CLIENTE
// =========================
Route::prefix('cliente')->group(function () {

    Route::get(
        '/{slug}',
        [ClienteController::class, 'index']
    )->name('cliente.disponibilidade');

    Route::get(
        '/{slug}/{user}/{date?}',
        [ClienteController::class, 'show']
    )->name('cliente.agenda');

    Route::post(
        '/agendar',
        [ClienteController::class, 'store']
    )->name('cliente.agendar');

    Route::post(
        '/{id}/cancelar',
        [ClienteController::class, 'cancelar']
    )->name('cliente.cancelar');
});

