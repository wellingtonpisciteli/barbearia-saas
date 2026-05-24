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
        Schema::create('servicos', function (Blueprint $table) {

            $table->id();

            // multi barbearia
            $table->foreignId('barbearia_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('nome');

            // ex: 35.00
            $table->decimal(
                'preco',
                8,
                2
            );

            // minutos
            $table->integer('duracao');

            $table->boolean('ativo')
                ->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicos');
    }
};
