<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\AgendaController;


// =========================
// ADM
// =========================
Route::prefix('adm')->group(function () {

    Route::get(
        '/agendamento',
        [AgendamentoController::class, 'index']
    );

    Route::delete(
        '/agendamento/{id}',
        [AgendamentoController::class, 'admCancelar']
    )->name('agendamento.admCancelar');

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
        '/cliente/disponibilidade/{slug}/{user}/{date?}',
        [AgendaController::class, 'show']
    )->name('cliente.agenda');

    Route::post(
        '/cliente/agendar',
        [AgendaController::class, 'store']
    )->name('cliente.agendar');

});