<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\FCMController;
use App\Http\Controllers\AuthController;

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
            '/agendamentos',
            [AgendamentoController::class, 'index']
        )->name('barbeiro.agendamento.index');

        Route::get(
            '/clientes',
            [AgendamentoController::class, 'clientes']
        )->name('barbeiro.clientes');

        Route::delete(
            '/clientes/{cliente}',
            [AgendamentoController::class, 'destroy']
        )->name('barbeiro.clientes.destroy');

        Route::delete(
            '/agendamentos/{id}',
            [AgendamentoController::class, 'cancelar']
        )->name('barbeiro.agendamento.cancelar');

    });

});


// =========================
// CLIENTE
// =========================
Route::prefix('cliente')->group(function () {

    Route::get(
        '/disponibilidade/{slug}',
        [AgendaController::class, 'index']
    )->name('cliente.disponibilidade');

    Route::get(
        '/disponibilidade/{slug}/{user}/{date?}',
        [AgendaController::class, 'show']
    )->name('cliente.agenda');

    Route::post(
        '/agendar',
        [AgendaController::class, 'store']
    )->name('cliente.agendar');

    Route::post(
        '/{id}/cancelar',
        [AgendaController::class, 'cancelar']
    )->name('cliente.cancelar');
});

