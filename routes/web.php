<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarbeiroController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FCMController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;

// =========================
// ADMIN
// =========================
Route::prefix('admin')->group(function () {

    // Login
    Route::get(
        '/login',
        [LoginController::class, 'login']
    )->name('admin.login');

    Route::post(
        '/login',
        [LoginController::class, 'autenticar']
    )->name('admin.autenticar');


    // Rotas protegidas
    Route::middleware(['admin.auth', 'admin'])->group(function () {

        Route::post(
            '/logout',
            [LoginController::class, 'logout']
        )->name('admin.logout');

        Route::get(
            '/',
            [DashboardController::class, 'index']
        )->name('admin.dashboard');

    });

});


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
            [BarbeiroController::class, 'agendamentos']
        )->name('barbeiro.agendamentos');

        Route::get(
            '/clientes',
            [BarbeiroController::class, 'clientes']
        )->name('barbeiro.clientes');

        Route::get(
            '/barbeiros',
            [BarbeiroController::class, 'barbeiros']
        )->name('barbeiro.barbeiros');

        Route::get(
            '/novoBarbeiro',
            [BarbeiroController::class, 'createBarbeiro']
        )->name('barbeiro.novoBarbeiro');
        
        Route::post(
            '/criarBarbeiro',
            [BarbeiroController::class, 'storeBarbeiro']
        )->name('barbeiro.criarBarbeiro');

        Route::get(
            '/createEditar/{id}',
            [BarbeiroController::class, 'editarBarbeiro']
        )->name('barbeiro.createEditar');

        Route::post(
            '/update/{id}',
            [BarbeiroController::class, 'updateBarbeiro']
        )->name('barbeiro.update');

        Route::get(
            '/configuracoes',
            [BarbeiroController::class, 'barbearia']
        )->name('barbeiro.configuracoes');

        Route::post(
            '/configuracoes-atualizar',
            [BarbeiroController::class, 'barbeariaUpdate']
        )->name('barbeiro.configuracoes-atualizar');

        Route::get(
            '/servicos',
            [BarbeiroController::class, 'servicos']
        )->name('barbeiro.servicos');

        Route::get(
            '/servicos-create',
            [BarbeiroController::class, 'servicosCreate']
        )->name('barbeiro.servicos-create');

        Route::post(
            '/servicos-store',
            [BarbeiroController::class, 'servicosStore']
        )->name('barbeiro.servicos-store');

        Route::get(
            '/servicos-edit/{id}',
            [BarbeiroController::class, 'servicosEditar']
        )->name('barbeiro.servicos-edit');

        Route::put(
            '/servicos-update/{id}',
            [BarbeiroController::class, 'servicosUpdate']
        )->name('barbeiro.servicos-update');

        Route::delete(
            '/servicos-destroy/{servico}',
            [BarbeiroController::class, 'destroyServico']
        )->name('barbeiro.servicos-destroy');

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

