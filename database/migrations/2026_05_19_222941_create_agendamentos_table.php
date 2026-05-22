<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();

            // barbeiro
            $table->foreignId('barbeiro_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // cliente
            $table->string('nome_cliente');
            $table->string('telefone_cliente');

            // horários
            $table->dateTime('inicio');
            $table->dateTime('fim');

            // status
            $table->enum('status', [
                'confirmado',
                'cancelado',
                'finalizado'
            ])->default('confirmado');

            // índices
            $table->index(['barbeiro_id', 'inicio']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
