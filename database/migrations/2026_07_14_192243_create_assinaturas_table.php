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
        Schema::create('assinaturas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('barbearia_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('valor', 10, 2);

            $table->enum('status', [
                'ativa',
                'pendente',
                'cancelada',
                'inadimplente',
            ])->default('pendente');

            $table->date('inicio');

            $table->date('proxima_cobranca');

            $table->date('fim')->nullable();

            $table->string('gateway')->nullable();

            $table->string('gateway_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assinaturas');
    }
};
